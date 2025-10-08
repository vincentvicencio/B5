<?php

namespace App\Http\Controllers;

use App\Models\TraitModel;
use App\Models\LikertScale;
use App\Models\Assessment;
use App\Models\TraitScore;
use App\Models\SubTraitScore;
use App\Models\TraitScoreMatrix;
use App\Models\SubTraitScoreMatrix;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    /**
     * Redirect to first trait
     */
    public function index()
    {
        // Check if respondent has completed personal info
        $respondentId = Session::get('respondent_id');
        
        if (!$respondentId) {
            return redirect()->route('personal-info')
                ->with('error', 'Please complete your personal information first.');
        }

        // Get first trait
        $firstTrait = TraitModel::orderBy('id')->first();

        if (!$firstTrait) {
            return redirect()->route('landing')
                ->with('error', 'No assessment available at the moment.');
        }

        // Initialize session for tracking responses
        Session::put('assessment_responses', []);
        Session::put('current_trait_index', 0);

        return redirect()->route('assessment.trait', ['traitId' => $firstTrait->id]);
    }

    /**
     * Show specific trait page
     */
    public function showTrait($traitId)
    {
        // Check if respondent has completed personal info
        $respondentId = Session::get('respondent_id');
        
        if (!$respondentId) {
            return redirect()->route('personal-info')
                ->with('error', 'Please complete your personal information first.');
        }

        // Get the trait with its subtraits and questions
        $trait = TraitModel::with(['subTraits.questions'])->findOrFail($traitId);

        // Get Likert scales
        $likertScales = LikertScale::orderBy('value')->get();

        // Get all traits for navigation
        $allTraits = TraitModel::orderBy('id')->get();
        $currentIndex = $allTraits->search(function($t) use ($traitId) {
            return $t->id == $traitId;
        });

        $totalTraits = $allTraits->count();
        $isFirstTrait = ($currentIndex === 0);
        $isLastTrait = ($currentIndex === $totalTraits - 1);

        $previousTraitId = !$isFirstTrait ? $allTraits[$currentIndex - 1]->id : null;
        $nextTraitId = !$isLastTrait ? $allTraits[$currentIndex + 1]->id : null;

        // Get previously saved responses for this trait
        $savedResponses = Session::get('assessment_responses', []);

        return view('user.assessment-trait', compact(
            'trait',
            'likertScales',
            'currentIndex',
            'totalTraits',
            'isFirstTrait',
            'isLastTrait',
            'previousTraitId',
            'nextTraitId',
            'savedResponses'
        ));
    }

    /**
     * Store responses for a trait and move to next
     */
    public function storeTrait(Request $request, $traitId)
    {
        // Validate responses
        $validated = $request->validate([
            'responses' => 'required|array',
            'responses.*' => 'required|integer|min:1|max:5',
        ], [
            'responses.required' => 'Please answer all questions before continuing.',
            'responses.*.required' => 'Please answer all questions.',
            'responses.*.integer' => 'Invalid response value.',
            'responses.*.min' => 'Response must be between 1 and 5.',
            'responses.*.max' => 'Response must be between 1 and 5.',
        ]);

        // Convert string values to integers
        $responses = array_map('intval', $validated['responses']);

        // Merge responses with existing session data
        $allResponses = Session::get('assessment_responses', []);
        $allResponses = array_merge($allResponses, $responses);
        Session::put('assessment_responses', $allResponses);

        // Get all traits ordered by ID
        $allTraits = TraitModel::orderBy('id')->get();
        
        // Find current trait index
        $currentIndex = $allTraits->search(function($t) use ($traitId) {
            return $t->id == $traitId;
        });

        // Check if this is the last trait
        if ($currentIndex === false || $currentIndex === $allTraits->count() - 1) {
            // Last trait - complete assessment
            return $this->completeAssessment();
        } else {
            // Move to next trait page
            $nextTrait = $allTraits[$currentIndex + 1];
            
            return redirect()->route('assessment.trait', ['traitId' => $nextTrait->id])
                ->with('success', 'Section completed! Continue to the next section.');
        }
    }

    /**
     * Complete the assessment and save to database
     */
    private function completeAssessment()
    {
        $respondentId = Session::get('respondent_id');
        $responses = Session::get('assessment_responses', []);

        \Log::info('=== STARTING ASSESSMENT COMPLETION ===', [
            'respondent_id' => $respondentId,
            'total_responses' => count($responses),
            'first_5_responses' => array_slice($responses, 0, 5, true)
        ]);

        if (!$respondentId || empty($responses)) {
            return redirect()->route('personal-info')
                ->with('error', 'Session expired. Please start again.');
        }

        DB::beginTransaction();

        try {
            // Calculate scores
            $traitScores = $this->calculateTraitScores($responses);
            $subTraitScores = $this->calculateSubTraitScores($responses);
            $overallScore = $this->calculateOverallScore($traitScores);

            \Log::info('=== SCORES CALCULATED ===', [
                'overall_score' => $overallScore,
                'trait_scores' => $traitScores,
                'subtrait_count' => count($subTraitScores)
            ]);

            // Get overall interpretation
            $overallInterpretation = $this->getOverallInterpretation($overallScore);

            // Create assessment record
            $assessment = Assessment::create([
                'respondent_id' => $respondentId,
                'date_completed' => now(),
                'overall_score' => $overallScore,
                'interpretation' => $overallInterpretation,
                'all_response' => json_encode($responses),
            ]);

            \Log::info('Assessment created', [
                'assessment_id' => $assessment->id,
                'overall_score' => $overallScore
            ]);

            // Store trait scores
            foreach ($traitScores as $traitId => $scoreData) {
                TraitScore::create([
                    'assessment_id' => $assessment->id,
                    'trait_id' => $traitId,
                    'score_pct' => $scoreData['percentage'],
                    'interpretation' => $scoreData['interpretation'],
                ]);
            }

            // Store subtrait scores
            foreach ($subTraitScores as $subTraitId => $scoreData) {
                SubTraitScore::create([
                    'assessment_id' => $assessment->id,
                    'sub_trait_id' => $subTraitId,
                    'score_pct' => $scoreData['percentage'],
                    'interpretation' => $scoreData['interpretation'],
                ]);
            }

            DB::commit();

            \Log::info('=== ASSESSMENT COMPLETED SUCCESSFULLY ===');

            // Clear session data
            Session::forget(['respondent_id', 'assessment_responses', 'current_trait_index']);

            // Redirect to completion page
            return redirect()->route('assessment.complete')
                ->with('success', 'Assessment completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('=== ASSESSMENT COMPLETION FAILED ===', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Show completion page
     */
    public function showComplete()
    {
        return view('user.assessment-complete');
    }

    /**
     * Calculate trait scores
     */
    private function calculateTraitScores($responses)
    {
        $traitScores = [];
        $traits = TraitModel::with(['subTraits.questions'])->get();

        \Log::info('=== CALCULATING TRAIT SCORES ===', [
            'total_traits' => $traits->count()
        ]);

        foreach ($traits as $trait) {
            $totalScore = 0;
            $questionCount = 0;
            $questionIds = [];

            foreach ($trait->subTraits as $subTrait) {
                foreach ($subTrait->questions as $question) {
                    $questionIds[] = $question->id;
                    if (isset($responses[$question->id])) {
                        $totalScore += (int)$responses[$question->id];
                        $questionCount++;
                    }
                }
            }

            \Log::info('Trait calculation details', [
                'trait_id' => $trait->id,
                'trait_title' => $trait->title,
                'question_ids' => $questionIds,
                'questions_found' => $questionCount,
                'total_score' => $totalScore
            ]);

            if ($questionCount === 0) {
                \Log::warning('No questions answered for trait', [
                    'trait_id' => $trait->id,
                    'trait_title' => $trait->title
                ]);
                continue;
            }

            $maxPossibleScore = $questionCount * 5;
            $percentage = ($totalScore / $maxPossibleScore) * 100;
            $roundedPercentage = round($percentage, 2);

            // Get interpretation from TraitScoreMatrix
            $interpretation = $this->getTraitInterpretation($trait->id, $roundedPercentage);

            $traitScores[$trait->id] = [
                'raw_score' => $totalScore,
                'percentage' => $roundedPercentage,
                'interpretation' => $interpretation,
            ];
        }

        return $traitScores;
    }

    /**
     * Calculate subtrait scores
     */
    private function calculateSubTraitScores($responses)
    {
        $subTraitScores = [];
        $traits = TraitModel::with(['subTraits.questions'])->get();

        foreach ($traits as $trait) {
            foreach ($trait->subTraits as $subTrait) {
                $totalScore = 0;
                $questionCount = 0;

                foreach ($subTrait->questions as $question) {
                    if (isset($responses[$question->id])) {
                        $totalScore += (int)$responses[$question->id];
                        $questionCount++;
                    }
                }

                if ($questionCount === 0) {
                    continue;
                }

                $maxPossibleScore = $questionCount * 5;
                $percentage = ($totalScore / $maxPossibleScore) * 100;
                $roundedPercentage = round($percentage, 2);

                // Get interpretation from SubTraitScoreMatrix
                $interpretation = $this->getSubTraitInterpretation($subTrait->id, $roundedPercentage);

                $subTraitScores[$subTrait->id] = [
                    'raw_score' => $totalScore,
                    'percentage' => $roundedPercentage,
                    'interpretation' => $interpretation,
                ];
            }
        }

        return $subTraitScores;
    }

    /**
     * Calculate overall score
     */
    private function calculateOverallScore($traitScores)
    {
        if (empty($traitScores)) {
            \Log::error('No trait scores to calculate overall score');
            return 0;
        }

        $totalPercentage = 0;
        foreach ($traitScores as $scoreData) {
            $totalPercentage += $scoreData['percentage'];
        }
        
        $average = $totalPercentage / count($traitScores);
        
        \Log::info('Overall score calculation', [
            'total_percentage' => $totalPercentage,
            'trait_count' => count($traitScores),
            'average' => $average
        ]);
        
        return round($average, 2);
    }

    /**
     * Get interpretation for trait score using TraitScoreMatrix
     */
    private function getTraitInterpretation($traitId, $percentage)
    {
        // Check what matrices exist for this trait
        $allMatrices = TraitScoreMatrix::where('trait_id', $traitId)->get();
        
        \Log::info('Looking for trait interpretation', [
            'trait_id' => $traitId,
            'percentage' => $percentage,
            'matrices_count' => $allMatrices->count(),
            'available_ranges' => $allMatrices->map(function($m) {
                return [
                    'min' => $m->min_score,
                    'max' => $m->max_score,
                    'interpretation_id' => $m->interpretation_id
                ];
            })->toArray()
        ]);

        $matrix = TraitScoreMatrix::with('interpretation')
            ->where('trait_id', $traitId)
            ->where('min_score', '<=', $percentage)
            ->where('max_score', '>=', $percentage)
            ->first();

        if ($matrix && $matrix->interpretation) {
            \Log::info('Found trait interpretation', [
                'trait_id' => $traitId,
                'percentage' => $percentage,
                'interpretation' => $matrix->interpretation->interpretation
            ]);
            return $matrix->interpretation->interpretation;
        }

        \Log::warning('No trait interpretation found', [
            'trait_id' => $traitId, 
            'percentage' => $percentage
        ]);
        
        return 'No interpretation available';
    }

    /**
     * Get interpretation for subtrait score using SubTraitScoreMatrix
     */
    private function getSubTraitInterpretation($subTraitId, $percentage)
    {
        $matrix = SubTraitScoreMatrix::with('interpretation')
            ->where('subtrait_id', $subTraitId)
            ->where('min_score', '<=', $percentage)
            ->where('max_score', '>=', $percentage)
            ->first();

        if ($matrix && $matrix->interpretation) {
            return $matrix->interpretation->interpretation;
        }

        \Log::warning('No subtrait interpretation found', [
            'subtrait_id' => $subTraitId, 
            'percentage' => $percentage
        ]);
        
        return 'No interpretation available';
    }

    /**
     * Get overall interpretation based on average of all trait scores
     */
    private function getOverallInterpretation($percentage)
    {
        // Use the first trait's matrix as reference for overall interpretation
        $firstTrait = TraitModel::orderBy('id')->first();
        
        if ($firstTrait) {
            \Log::info('Getting overall interpretation using first trait', [
                'trait_id' => $firstTrait->id,
                'percentage' => $percentage
            ]);

            $matrix = TraitScoreMatrix::with('interpretation')
                ->where('trait_id', $firstTrait->id)
                ->where('min_score', '<=', $percentage)
                ->where('max_score', '>=', $percentage)
                ->first();

            if ($matrix && $matrix->interpretation) {
                \Log::info('Found overall interpretation', [
                    'interpretation' => $matrix->interpretation->interpretation
                ]);
                return $matrix->interpretation->interpretation;
            }
        }

        \Log::warning('No overall interpretation found', [
            'percentage' => $percentage
        ]);
        
        return 'No interpretation available';
    }
}