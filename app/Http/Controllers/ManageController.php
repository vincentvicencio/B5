<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\TraitModel; 
use App\Models\SubTrait;
use App\Models\Question;

class ManageController extends Controller
{
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

            // 1. Create Trait
            $trait = TraitModel::create([
                'title' => $validated['traitTitle'],
                'description' => $validated['traitDescription'],
                'trait_display_color' => $validated['traitColor'],
                'max_raw_score' => 100, 
            ]);

            $subTraitMap = [];

            // 2. Create SubTraits
            foreach ($validated['subTraits'] as $subTraitName) {
                $subTrait = $trait->subTraits()->create([
                    'subtrait_name' => $subTraitName,
                    'max_raw_score' => 50, // Default score
                ]);
                
                $subTraitMap[$subTraitName] = $subTrait->id;
            }

            // 3. Create Questions
            foreach ($validated['questions'] as $questionData) {
                // Find the associated sub_trait_id using the map
                $subTraitId = $subTraitMap[$questionData['subTrait']] ?? null;

                if ($subTraitId) {
                    Question::create([
                        'subtrait_id' => $subTraitId, // Use subtrait_id as per model fix
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
                'error' => 'DEBUG: ' . $e->getMessage(), // Expose the error temporarily
                'message' => 'A server error occurred while saving the data.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not implemented
    }

    /**
     * Show the form for editing the specified resource.
     */
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
                    'text' => $question->question_text,       // Key name matches JS structure
                ];
            }
        }
        
        // Pass all three required variables to the view
        return view('manage.edit', compact('trait', 'subTraits', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
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

            // 1. Update Trait Details
            $trait->update([
                'title' => $validated['traitTitle'],
                'description' => $validated['traitDescription'],
                'trait_display_color' => $validated['traitColor'],
                'max_raw_score' => 100, // Keep default for now
            ]);

            // --- SubTrait and Question synchronization ---
            
            // Get existing SubTraits mapped by name
            $existingSubTraits = $trait->subTraits->keyBy('subtrait_name');
            $subTraitMap = []; // To map submitted name to new/existing ID
            $subTraitIdsToKeep = []; // To track which existing SubTraits survived

            // 2. Process SubTraits (Update/Create)
            foreach ($validated['subTraits'] as $name) {
                $subTrait = $existingSubTraits->get($name);

                if ($subTrait) {
                    // Existing SubTrait: Use its ID
                    $subTraitIdsToKeep[] = $subTrait->id;
                } else {
                    // New SubTrait: Create it
                    $subTrait = $trait->subTraits()->create([
                        'subtrait_name' => $name,
                        'max_raw_score' => 50,
                    ]);
                }
                $subTraitMap[$name] = $subTrait->id;
            }

            // 3. Delete old SubTraits (and their cascadingly deleted questions)
            // Get IDs of ALL current SubTraits, and filter those NOT in $subTraitIdsToKeep
            $subTraitIdsToDelete = $existingSubTraits->pluck('id')->diff($subTraitIdsToKeep);
            SubTrait::whereIn('id', $subTraitIdsToDelete)->delete();
            
            
            // 4. Synchronization of Questions (The "replace all" strategy)

            // Get the IDs of ALL SubTraits *currently* associated with the Trait (the survivors + the new ones)
            // We use a fresh query to ensure we have the IDs of the newly created SubTraits.
            $allCurrentSubTraitIds = SubTrait::where('trait_id', $trait->id)->pluck('id');
            
            // CRITICAL FIX: Delete all existing questions tied to the remaining/new SubTraits.
            // This ensures that any questions deleted on the front end are removed from the database.
            Question::whereIn('subtrait_id', $allCurrentSubTraitIds)->delete();

            // 5. Insert New Questions
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