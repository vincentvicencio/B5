<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\TraitModel; 
use App\Models\SubTrait;
use App\Models\Question;
use App\Models\LikertScale; // Import the LikertScale model

class ManageController extends Controller
{
    /**
     * Get the highest 'value' from the likert_scales table.
     * @return int
     */
    private function getMaxLikertValue(): int
    {
        // Assumes at least one record exists. Default to 5 if the table is empty.
        return LikertScale::max('value') ?? 5; 
    }

    public function index()
    {
        $traits = TraitModel::with('subTraits')->get();
        return view('manage.index', compact('traits')); 
    }

    public function create()
    {
        return view('manage.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'traitTitle' => 'required|string|max:255|unique:traits,title',
            'traitDescription' => 'required|string',
            'traitColor' => ['required', 'string', new \App\Rules\HexColor],
            'subTraits' => 'required|array|min:1',
            'subTraits.*' => 'required|string|max:255',
            'questions' => 'required|array|min:1',
            'questions.*.subTrait' => 'required|string|max:255',
            'questions.*.text' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $maxLikertValue = $this->getMaxLikertValue();
            $totalTraitMaxScore = 0;
            $subTraitMaxScores = [];

            // Group questions by subTrait name for easy counting
            $questionsBySubTrait = collect($validated['questions'])->groupBy('subTrait');

            // 1. Calculate Max Scores for SubTraits and Trait
            foreach ($validated['subTraits'] as $subTraitName) {
                // Count how many questions belong to this subtrait
                $questionCount = $questionsBySubTrait->has($subTraitName) 
                                 ? $questionsBySubTrait->get($subTraitName)->count()
                                 : 0;
                
                // Calculate max score for this subtrait
                $subTraitMaxScore = $maxLikertValue * $questionCount;
                $subTraitMaxScores[$subTraitName] = $subTraitMaxScore;
                $totalTraitMaxScore += $subTraitMaxScore; // Sum up for the trait total
            }

            // 2. Create Trait with the calculated max_raw_score
            $trait = TraitModel::create([
                'title' => $validated['traitTitle'],
                'description' => $validated['traitDescription'],
                'trait_display_color' => $validated['traitColor'],
                'max_raw_score' => $totalTraitMaxScore, // <--- MODIFIED
            ]);

            $subTraitMap = [];

            // 3. Create SubTraits with their calculated max_raw_score
            foreach ($validated['subTraits'] as $subTraitName) {
                $subTrait = $trait->subTraits()->create([
                    'subtrait_name' => $subTraitName,
                    'max_raw_score' => $subTraitMaxScores[$subTraitName], // <--- MODIFIED
                ]);
                
                $subTraitMap[$subTraitName] = $subTrait->id;
            }

            // 4. Create Questions
            foreach ($validated['questions'] as $questionData) {
                $subTraitId = $subTraitMap[$questionData['subTrait']] ?? null;

                if ($subTraitId) {
                    Question::create([
                        'subtrait_id' => $subTraitId,
                        'question_text' => $questionData['text'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trait, Sub-Traits, and Questions created successfully.',
                'redirect' => route('manage.index'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Trait creation failed: " . $e->getMessage());
            
            // DEBUGGING: Return specific error
            return response()->json([
                'success' => false,
                'error' => 'DEBUG: ' . $e->getMessage(),
                'message' => 'A server error occurred while saving the data.',
            ], 500);
        }
    }

    public function show(string $id)
    {
        // Not implemented
    }


    public function edit(string $id)
    {
        // Eager load subTraits and questions, and fail if not found
        $trait = TraitModel::with('subTraits.questions')->findOrFail($id);

        // 1. Prepare $subTraits array for JavaScript (list of names)
        $subTraits = $trait->subTraits->pluck('subtrait_name')->toArray();

        // 2. Prepare $questions array for JavaScript (list of objects with subTrait name and text)
        $questions = [];
        foreach ($trait->subTraits as $subTrait) {
            foreach ($subTrait->questions as $question) {
                $questions[] = [
                    'subTrait' => $subTrait->subtrait_name, // Key name matches JS structure
                    'text' => $question->question_text,  // Key name matches JS structure
                ];
            }
        }
        
        // Pass all three required variables to the view
        return view('manage.edit', compact('trait', 'subTraits', 'questions'));
    }
    
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            // FIX: Use Rule::unique to ignore the current trait ID when updating
            'traitTitle' => ['required', 'string', 'max:255', Rule::unique('traits', 'title')->ignore($id)],
            
            'traitDescription' => 'required|string',
            'traitColor' => ['required', 'string', new \App\Rules\HexColor],
            'subTraits' => 'required|array|min:1',
            'subTraits.*' => 'required|string|max:255',
            'questions' => 'required|array|min:1',
            'questions.*.subTrait' => 'required|string|max:255',
            'questions.*.text' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            
            $trait = TraitModel::findOrFail($id);

            $maxLikertValue = $this->getMaxLikertValue();
            $totalTraitMaxScore = 0;
            $subTraitMaxScores = [];

            // Group questions by subTrait name for easy counting
            $questionsBySubTrait = collect($validated['questions'])->groupBy('subTrait');

            // 1. Calculate Max Scores for SubTraits and Trait
            foreach ($validated['subTraits'] as $subTraitName) {
                // Count how many questions belong to this subtrait
                $questionCount = $questionsBySubTrait->has($subTraitName) 
                                 ? $questionsBySubTrait->get($subTraitName)->count()
                                 : 0;
                
                // Calculate max score for this subtrait
                $subTraitMaxScore = $maxLikertValue * $questionCount;
                $subTraitMaxScores[$subTraitName] = $subTraitMaxScore;
                $totalTraitMaxScore += $subTraitMaxScore; // Sum up for the trait total
            }

            // 2. Update Trait Details with the new total score
            $trait->update([
                'title' => $validated['traitTitle'],
                'description' => $validated['traitDescription'],
                'trait_display_color' => $validated['traitColor'],
                'max_raw_score' => $totalTraitMaxScore, // <--- MODIFIED
            ]);

            // --- SubTrait and Question synchronization ---
            
            // Get existing SubTraits mapped by name
            $existingSubTraits = $trait->subTraits->keyBy('subtrait_name');
            $subTraitMap = []; // To map submitted name to new/existing ID
            $subTraitIdsToKeep = []; // To track which existing SubTraits survived

            // 3. Process SubTraits (Update/Create)
            foreach ($validated['subTraits'] as $name) {
                $subTrait = $existingSubTraits->get($name);
                $maxScore = $subTraitMaxScores[$name]; // Get the pre-calculated score

                if ($subTrait) {
                    // Existing SubTrait: Update name and score (though name shouldn't change here)
                    $subTrait->update([
                        'max_raw_score' => $maxScore, // <--- MODIFIED
                    ]);
                    $subTraitIdsToKeep[] = $subTrait->id;
                } else {
                    // New SubTrait: Create it with its score
                    $subTrait = $trait->subTraits()->create([
                        'subtrait_name' => $name,
                        'max_raw_score' => $maxScore, // <--- MODIFIED
                    ]);
                }
                $subTraitMap[$name] = $subTrait->id;
            }

            // 4. Delete old SubTraits (and their cascadingly deleted questions)
            $subTraitIdsToDelete = $existingSubTraits->pluck('id')->diff($subTraitIdsToKeep);
            SubTrait::whereIn('id', $subTraitIdsToDelete)->delete();
            
            
            // 5. Synchronization of Questions (The "replace all" strategy)
            $allCurrentSubTraitIds = SubTrait::where('trait_id', $trait->id)->pluck('id');
            
            // CRITICAL FIX: Delete all existing questions tied to the remaining/new SubTraits.
            Question::whereIn('subtrait_id', $allCurrentSubTraitIds)->delete();

            // 6. Insert New Questions
            $questionsToInsert = [];
            foreach ($validated['questions'] as $questionData) {
                $subTraitId = $subTraitMap[$questionData['subTrait']] ?? null;

                if ($subTraitId) {
                    $questionsToInsert[] = [
                        'subtrait_id' => $subTraitId,
                        'question_text' => $questionData['text'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Perform bulk insert for new questions
            if (!empty($questionsToInsert)) {
                Question::insert($questionsToInsert);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trait configuration updated successfully.',
                'redirect' => route('manage.index'),
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Trait update failed: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'A server error occurred while updating the data.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the Trait by ID, throwing a 404 if not found
            $trait = TraitModel::findOrFail($id);
            
            // Delete the Trait. This should cascade delete all associated 
            // SubTraits and Questions if your database schema is configured for it.
            $trait->delete(); 

            // Return a success JSON response to the JavaScript AJAX call
            return response()->json([
                'success' => true,
                'message' => 'Trait and associated data deleted successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error("Trait deletion failed: " . $e->getMessage());
            
            // Return an error JSON response
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete trait. Server error: ' . $e->getMessage(),
            ], 500);
        }
    }
}