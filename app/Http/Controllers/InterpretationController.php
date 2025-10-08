<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Interpretation;
use App\Models\InterpretationType;

class InterpretationController extends Controller
{
    /**
     * Show the interpretation management page
     */
    public function index()
    {
        return view('interpretation');
    }

    /**
     * Get all interpretation types
     */
    public function getInterpretationTypes()
    {
        try {
            $types = InterpretationType::all();
            return response()->json([
                'success' => true,
                'data' => $types
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch interpretation types: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load interpretation types.'
            ], 500);
        }
    }

    /**
     * Get all interpretations, optionally filtered by type ID
     */
    public function getInterpretations(Request $request)
    {
        try {
            $query = Interpretation::with('type')->orderBy('id', 'asc');

            // Apply filter if provided
            if ($request->has('type_id') && $request->type_id !== null && $request->type_id !== '') {
                $query->where('interpretation_type_id', $request->type_id);
            }

            $interpretations = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $interpretations
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch interpretations: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load interpretations.'
            ], 500);
        }
    }

    /**
     * Get a single interpretation
     */
    public function show($id)
    {
        try {
            $interpretation = Interpretation::with('type')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $interpretation
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch interpretation: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load interpretation.'
            ], 404);
        }
    }

    /**
     * Store a new interpretation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'interpretation_type_id' => 'required|exists:interpretation_types,id',
            'trait_level' => 'required|string|max:255',
            'interpretation' => 'required|string'
        ]);

        try {
            $interpretation = Interpretation::create($validated);
            $interpretation->load('type');

            return response()->json([
                'success' => true,
                'message' => 'Interpretation created successfully.',
                'data' => $interpretation
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to store interpretation: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save interpretation.'
            ], 500);
        }
    }

    /**
     * Update an existing interpretation
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'interpretation_type_id' => 'required|exists:interpretation_types,id',
            'trait_level' => 'required|string|max:255',
            'interpretation' => 'required|string'
        ]);

        try {
            $interpretation = Interpretation::findOrFail($id);
            $interpretation->update($validated);
            $interpretation->load('type');

            return response()->json([
                'success' => true,
                'message' => 'Interpretation updated successfully.',
                'data' => $interpretation
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to update interpretation: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update interpretation.'
            ], 500);
        }
    }

    /**
     * Delete an interpretation
     */
    public function destroy($id)
    {
        try {
            $interpretation = Interpretation::findOrFail($id);
            
            // Check if interpretation is being used in matrices
            $usedInTraitMatrix = $interpretation->traitScoreMatrices()->count();
            $usedInSubTraitMatrix = $interpretation->subTraitScoreMatrices()->count();
            
            if ($usedInTraitMatrix > 0 || $usedInSubTraitMatrix > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete interpretation as it is being used in score matrices.'
                ], 422);
            }
            
            $interpretation->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Interpretation deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to delete interpretation: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete interpretation.'
            ], 500);
        }
    }
}