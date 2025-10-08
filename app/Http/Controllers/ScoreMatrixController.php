<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\LikertScale;
use App\Models\TraitModel;
use App\Models\SubTrait;
use App\Models\Interpretation;
use App\Models\TraitScoreMatrix;
use App\Models\SubTraitScoreMatrix;

class ScoreMatrixController extends Controller
{
    public function index()
    {
        return view('score-matrix');
    }

    // ============================================================================
    // LIKERT SCALE METHODS
    // ============================================================================

    public function getLikertScales()
    {
        try {
            $scales = LikertScale::orderBy('value')->get();
            return response()->json([
                'success' => true,
                'data' => $scales
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch Likert Scales: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load Likert scales.'
            ], 500);
        }
    }

    public function storeLikertScale(Request $request)
    {
        $validated = $request->validate([
            'value' => 'required|integer|unique:likert_scales,value',
            'label' => 'required|string|max:255'
        ]);

        try {
            $scale = LikertScale::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Likert scale added successfully.',
                'data' => $scale
            ], 201);
        } catch (\Exception $e) {
            Log::error("Failed to create Likert Scale: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add Likert scale.'
            ], 500);
        }
    }

    public function updateLikertScale(Request $request, $id)
    {
        $validated = $request->validate([
            'value' => 'required|integer|unique:likert_scales,value,' . $id . ',id',
            'label' => 'required|string|max:255'
        ]);

        try {
            $scale = LikertScale::findOrFail($id);
            $scale->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Likert scale updated successfully.',
                'data' => $scale
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to update Likert Scale: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Likert scale.'
            ], 500);
        }
    }

    public function destroyLikertScale($id)
    {
        try {
            $scale = LikertScale::findOrFail($id);
            $scale->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Likert scale deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to delete Likert Scale: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Likert scale.'
            ], 500);
        }
    }

    // ============================================================================
    // SUB-TRAIT MATRIX METHODS
    // ============================================================================

    public function getSubTraitMatrices()
    {
        try {
            // CRITICAL FIX: Use 'id' instead of 'subtrait_id' for ordering
            $matrices = SubTraitScoreMatrix::with(['subTrait.trait', 'interpretation'])
                ->join('sub_traits', 'sub_trait_score_matrices.subtrait_id', '=', 'sub_traits.id')
                ->select('sub_trait_score_matrices.*')
                ->orderBy('sub_traits.subtrait_name')
                ->orderBy('sub_trait_score_matrices.min_score')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $matrices
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch Sub-Trait matrices: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load sub-trait matrices.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getSubTraits()
    {
        try {
            $subTraits = SubTrait::with('trait')->orderBy('subtrait_name')->get();
            return response()->json([
                'success' => true,
                'data' => $subTraits
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch Sub-Traits: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load sub-traits.'
            ], 500);
        }
    }

    public function getSubTraitInterpretations()
    {
        try {
            $interpretations = Interpretation::whereHas('type', function($query) {
                $query->where('name', 'Sub-Trait Standard');
            })->orderBy('trait_level')->get();
            
            return response()->json([
                'success' => true,
                'data' => $interpretations
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch Sub-Trait Interpretations: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load sub-trait interpretations.'
            ], 500);
        }
    }

    public function storeSubTraitMatrix(Request $request)
    {
        // CRITICAL FIX: Validate against sub_traits.id instead of subtrait_id
        $validated = $request->validate([
            'subtrait_id' => 'required|exists:sub_traits,id',
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|gt:min_score',
            'interpretation_id' => 'required|exists:interpretations,id'
        ]);

        try {
            // Check for overlapping ranges
            $overlapping = SubTraitScoreMatrix::where('subtrait_id', $validated['subtrait_id'])
                ->where(function($query) use ($validated) {
                    $query->whereBetween('min_score', [$validated['min_score'], $validated['max_score']])
                        ->orWhereBetween('max_score', [$validated['min_score'], $validated['max_score']])
                        ->orWhere(function($q) use ($validated) {
                            $q->where('min_score', '<=', $validated['min_score'])
                              ->where('max_score', '>=', $validated['max_score']);
                        });
                })
                ->exists();

            if ($overlapping) {
                return response()->json([
                    'success' => false,
                    'message' => 'Score range overlaps with existing matrix entry.'
                ], 422);
            }

            $matrix = SubTraitScoreMatrix::create($validated);
            $matrix->load(['subTrait.trait', 'interpretation']);
            
            return response()->json([
                'success' => true,
                'message' => 'Sub-trait matrix added successfully.',
                'data' => $matrix
            ], 201);
        } catch (\Exception $e) {
            Log::error("Failed to create Sub-Trait matrix: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add sub-trait matrix.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateSubTraitMatrix(Request $request, $id)
    {
        $validated = $request->validate([
            'subtrait_id' => 'required|exists:sub_traits,id',
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|gt:min_score',
            'interpretation_id' => 'required|exists:interpretations,id'
        ]);

        try {
            $matrix = SubTraitScoreMatrix::findOrFail($id);
            
            // Check for overlapping ranges (excluding current record)
            $overlapping = SubTraitScoreMatrix::where('subtrait_id', $validated['subtrait_id'])
                ->where('id', '!=', $id)
                ->where(function($query) use ($validated) {
                    $query->whereBetween('min_score', [$validated['min_score'], $validated['max_score']])
                        ->orWhereBetween('max_score', [$validated['min_score'], $validated['max_score']])
                        ->orWhere(function($q) use ($validated) {
                            $q->where('min_score', '<=', $validated['min_score'])
                              ->where('max_score', '>=', $validated['max_score']);
                        });
                })
                ->exists();

            if ($overlapping) {
                return response()->json([
                    'success' => false,
                    'message' => 'Score range overlaps with existing matrix entry.'
                ], 422);
            }

            $matrix->update($validated);
            $matrix->load(['subTrait.trait', 'interpretation']);
            
            return response()->json([
                'success' => true,
                'message' => 'Sub-trait matrix updated successfully.',
                'data' => $matrix
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to update Sub-Trait matrix: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sub-trait matrix.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroySubTraitMatrix($id)
    {
        try {
            $matrix = SubTraitScoreMatrix::findOrFail($id);
            $matrix->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Sub-trait matrix deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to delete Sub-Trait matrix: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete sub-trait matrix.'
            ], 500);
        }
    }

    // ============================================================================
    // TRAIT MATRIX METHODS
    // ============================================================================

    public function getTraitMatrices()
    {
        try {
            $matrices = TraitScoreMatrix::with(['trait', 'interpretation'])
                ->join('traits', 'trait_score_matrices.trait_id', '=', 'traits.id')
                ->select('trait_score_matrices.*')
                ->orderBy('traits.title')
                ->orderBy('trait_score_matrices.min_score')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $matrices
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch Trait matrices: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load trait matrices.'
            ], 500);
        }
    }

    public function getTraits()
    {
        try {
            $traits = TraitModel::with('subTraits')->orderBy('title')->get();
            return response()->json([
                'success' => true,
                'data' => $traits
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch Traits: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load traits.'
            ], 500);
        }
    }

    public function getTraitInterpretations()
    {
        try {
            $interpretations = Interpretation::whereHas('type', function($query) {
                $query->where('name', 'Trait Standard');
            })->orderBy('trait_level')->get();
            
            return response()->json([
                'success' => true,
                'data' => $interpretations
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch Trait Interpretations: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load trait interpretations.'
            ], 500);
        }
    }

    public function storeTraitMatrix(Request $request)
    {
        $validated = $request->validate([
            'trait_id' => 'required|exists:traits,id',
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|gt:min_score',
            'interpretation_id' => 'required|exists:interpretations,id'
        ]);

        try {
            // Check for overlapping ranges
            $overlapping = TraitScoreMatrix::where('trait_id', $validated['trait_id'])
                ->where(function($query) use ($validated) {
                    $query->whereBetween('min_score', [$validated['min_score'], $validated['max_score']])
                        ->orWhereBetween('max_score', [$validated['min_score'], $validated['max_score']])
                        ->orWhere(function($q) use ($validated) {
                            $q->where('min_score', '<=', $validated['min_score'])
                              ->where('max_score', '>=', $validated['max_score']);
                        });
                })
                ->exists();

            if ($overlapping) {
                return response()->json([
                    'success' => false,
                    'message' => 'Score range overlaps with existing matrix entry.'
                ], 422);
            }

            $matrix = TraitScoreMatrix::create($validated);
            $matrix->load(['trait', 'interpretation']);
            
            return response()->json([
                'success' => true,
                'message' => 'Trait matrix added successfully.',
                'data' => $matrix
            ], 201);
        } catch (\Exception $e) {
            Log::error("Failed to create Trait matrix: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add trait matrix.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateTraitMatrix(Request $request, $id)
    {
        $validated = $request->validate([
            'trait_id' => 'required|exists:traits,id',
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|gt:min_score',
            'interpretation_id' => 'required|exists:interpretations,id'
        ]);

        try {
            $matrix = TraitScoreMatrix::findOrFail($id);
            
            // Check for overlapping ranges (excluding current record)
            $overlapping = TraitScoreMatrix::where('trait_id', $validated['trait_id'])
                ->where('id', '!=', $id)
                ->where(function($query) use ($validated) {
                    $query->whereBetween('min_score', [$validated['min_score'], $validated['max_score']])
                        ->orWhereBetween('max_score', [$validated['min_score'], $validated['max_score']])
                        ->orWhere(function($q) use ($validated) {
                            $q->where('min_score', '<=', $validated['min_score'])
                              ->where('max_score', '>=', $validated['max_score']);
                        });
                })
                ->exists();

            if ($overlapping) {
                return response()->json([
                    'success' => false,
                    'message' => 'Score range overlaps with existing matrix entry.'
                ], 422);
            }

            $matrix->update($validated);
            $matrix->load(['trait', 'interpretation']);
            
            return response()->json([
                'success' => true,
                'message' => 'Trait matrix updated successfully.',
                'data' => $matrix
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to update Trait matrix: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update trait matrix.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyTraitMatrix($id)
    {
        try {
            $matrix = TraitScoreMatrix::findOrFail($id);
            $matrix->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Trait matrix deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to delete Trait matrix: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete trait matrix.'
            ], 500);
        }
    }
}