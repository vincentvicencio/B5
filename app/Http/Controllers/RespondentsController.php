<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TraitModel; // Import the TraitModel to fetch data

class RespondentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('respondents.index');
    }

    /**
     * Get the list of respondents data (with dummy data for now).
     * This will be the API endpoint for AJAX.
     */
    public function list(Request $request)
    {
        // 1. Fetch the active trait pool from the database
        // We assume TraitModel maps to the 'traits' table and has a 'title' column.
        $dbTraits = TraitModel::select('title')->get();
        
        // Extract the titles into an array to be used for table generation
        $traitPool = $dbTraits->pluck('title')->toArray();

        // Check if any traits were found before proceeding
        if (empty($traitPool)) {
            // Return an empty data set if no traits are configured
            return response()->json([
                'data' => [],
                'total' => 0,
                'per_page' => 5,
                'current_page' => 1,
                'last_page' => 1,
                'from' => 0,
                'to' => 0,
                'trait_names' => [],
                'message' => 'No traits configured in the database.'
            ]);
        }

        // 2. Set the active traits to all fetched traits.
        $activeTraits = $traitPool; 
        $numTraits = count($activeTraits);


        // Dummy data for 10 respondents
        $respondents = [];
        for ($i = 1; $i <= 10; $i++) {
            $scores = [];
            $totalScoreSum = 0;
            
            // 3. Assign random scores based on the active traits
            foreach ($activeTraits as $traitName) {
                $score = rand(40, 95);
                $scores[$traitName] = $score;
                $totalScoreSum += $score;
            }

            // Calculate overall score (average of the dynamic traits)
            $overallScore = $numTraits > 0 ? $totalScoreSum / $numTraits : 0;

            // Determine interpretation
            if ($overallScore > 80) {
                $interpretation = 'High';
            } elseif ($overallScore > 65) {
                $interpretation = 'Moderate';
            } else {
                $interpretation = 'Low';
            }
            
            // Format scores with percentage
            $traitScoresFormatted = [];
            foreach ($scores as $name => $score) {
                $traitScoresFormatted[$name] = "{$score}%";
            }

            $respondents[] = [
                'id' => $i,
                'name' => "Respondent $i Name",
                'email' => "respondent{$i}@example.com",
                'scores' => $traitScoresFormatted, // Key is the trait name (e.g., 'Initiative')
                'overall_score' => number_format($overallScore, 1) . '%',
                'interpretation' => $interpretation,
            ];
        }

        // Apply basic server-side filtering
        $search = $request->input('search');
        if ($search) {
            $search = strtolower($search);
            $respondents = array_filter($respondents, function ($r) use ($search) {
                return str_contains(strtolower($r['name']), $search) || 
                       str_contains(strtolower($r['email']), $search);
            });
            // Re-index array after filtering
            $respondents = array_values($respondents); 
        }

        // Simulate pagination (get data for the current page)
        $perPage = 5;
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedData = array_slice($respondents, $offset, $perPage);
        $totalPages = ceil(count($respondents) / $perPage);

        // Also return the list of active trait names so the front-end can dynamically build the table headers
        $traitNames = $activeTraits;

        return response()->json([
            'data' => $paginatedData,
            'total' => count($respondents),
            'per_page' => $perPage,
            'current_page' => (int) $currentPage,
            'last_page' => $totalPages,
            'from' => $offset + 1,
            'to' => min($offset + $perPage, count($respondents)),
            'trait_names' => $traitNames, // Send the dynamic trait headers
        ]);
    }

    /**
     * API to handle the deletion of a respondent (dummy implementation).
     */
    public function destroy($id)
    {
        // In a real application, you would perform the database deletion here.
        // Respondent::destroy($id); 

        // Simulate successful deletion for the front-end to handle.
        return response()->json(['message' => "Respondent ID $id deleted successfully."]);
    }

    public function show()
    {
        return view('respondents.show');
    }
}
