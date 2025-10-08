<?php

namespace App\Http\Controllers;

use App\Models\TraitModel;
use App\Models\LikertScale;
use App\Models\Assessment;
use App\Models\TraitScore;
use App\Models\SubTraitScore;
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
        // Log incoming request
        \Log::info('StoreTrait called', [
            'traitId' => $traitId,
            'has_responses' => $request->has('responses'),
            'response_count' => $request->has('responses') ? count($request->input('responses')) : 0
        ]);

        // Validate responses
        try {
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        }

        // Merge responses with existing session data
        $allResponses = Session::get('assessment_responses', []);
        $allResponses = array_merge($allResponses, $validated['responses']);
        Session::put('assessment_responses', $allResponses);
        
        \Log::info('Responses saved to session', [
            'total_responses' => count($allResponses)
        ]);

        // Get all traits ordered by ID
        $allTraits = TraitModel::orderBy('id')->get();
        
        \Log::info('All traits', [
            'count' => $allTraits->count(),
            'ids' => $allTraits->pluck('id')->toArray()
        ]);
        
        // Find current trait index
        $currentIndex = $allTraits->search(function($t) use ($traitId) {
            return $t->id == $traitId;
        });
        
        \Log::info('Current trait index', [
            'currentIndex' => $currentIndex,
            'isLast' => $currentIndex === $allTraits->count() - 1
        ]);

        // Check if this is the last trait
        if ($currentIndex === false || $currentIndex === $allTraits->count() - 1) {
            // Last trait - complete assessment
            \Log::info('Last trait reached, completing assessment');
            return $this->completeAssessment();
        } else {
            // Move to next trait page
            $nextTrait = $allTraits[$currentIndex + 1];
            \Log::info('Moving to next trait', [
                'nextTraitId' => $nextTrait->id,
                'nextTraitTitle' => $nextTrait->title
            ]);
            
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

            // Create assessment record
            $assessment = Assessment::create([
                'respondent_id' => $respondentId,
                'date_completed' => now(),
                'overall_score' => $overallScore,
                'interpretation' => $this->generateInterpretation($overallScore),
                'all_response' => json_encode($responses),
            ]);

            // Store trait scores
            foreach ($traitScores as $traitId => $scoreData) {
                TraitScore::create([
                    'assessment_id' => $assessment->assessment_id,
                    'trait_id' => $traitId,
                    'score_pct' => $scoreData['percentage'],
                    'interpretation' => $scoreData['interpretation'],
                ]);
            }

            // Store subtrait scores
            foreach ($subTraitScores as $subTraitId => $scoreData) {
                SubTraitScore::create([
                    'assessment_id' => $assessment->assessment_id,
                    'sub_trait_id' => $subTraitId,
                    'score_pct' => $scoreData['percentage'],
                    'interpretation' => $scoreData['interpretation'],
                ]);
            }

            DB::commit();

            // Clear session data
            Session::forget(['respondent_id', 'assessment_responses', 'current_trait_index']);

            // Redirect to completion page
            return redirect()->route('assessment.complete')
                ->with('success', 'Assessment completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
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

        foreach ($traits as $trait) {
            $totalScore = 0;
            $questionCount = 0;

            foreach ($trait->subTraits as $subTrait) {
                foreach ($subTrait->questions as $question) {
                    if (isset($responses[$question->id])) {
                        $totalScore += $responses[$question->id];
                        $questionCount++;
                    }
                }
            }

            $maxPossibleScore = $questionCount * 5;
            $percentage = $maxPossibleScore > 0 ? ($totalScore / $maxPossibleScore) * 100 : 0;

            $traitScores[$trait->id] = [
                'raw_score' => $totalScore,
                'percentage' => round($percentage, 2),
                'interpretation' => $this->getInterpretationForScore($percentage),
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
                        $totalScore += $responses[$question->id];
                        $questionCount++;
                    }
                }

                $maxPossibleScore = $questionCount * 5;
                $percentage = $maxPossibleScore > 0 ? ($totalScore / $maxPossibleScore) * 100 : 0;

                $subTraitScores[$subTrait->id] = [
                    'raw_score' => $totalScore,
                    'percentage' => round($percentage, 2),
                    'interpretation' => $this->getInterpretationForScore($percentage),
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
            return 0;
        }

        $totalPercentage = array_sum(array_column($traitScores, 'percentage'));
        return round($totalPercentage / count($traitScores), 2);
    }

    /**
     * Generate interpretation
     */
    private function generateInterpretation($score)
    {
        if ($score >= 80) return 'Excellent';
        elseif ($score >= 60) return 'Good';
        elseif ($score >= 40) return 'Average';
        elseif ($score >= 20) return 'Below Average';
        else return 'Needs Improvement';
    }

    /**
     * Get interpretation for score
     */
    private function getInterpretationForScore($percentage)
    {
        if ($percentage >= 80) return 'Very High';
        elseif ($percentage >= 60) return 'High';
        elseif ($percentage >= 40) return 'Moderate';
        elseif ($percentage >= 20) return 'Low';
        else return 'Very Low';
    }
}