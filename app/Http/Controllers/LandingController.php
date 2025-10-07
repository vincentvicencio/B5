<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Respondent;

class LandingController extends Controller
{
    public function index()
    {
        $features = [
            [
                'title' => 'Quick & Efficient',
                'description' => 'Complete comprehensive personality assessment in just 15-20 minutes with our streamlined process.'
            ],
            [
                'title' => 'Detailed Analytics',
                'description' => 'Receive in-depth personality insights with visual reports and actionable recommendations.'
            ],
            [
                'title' => 'Team Focused',
                'description' => 'Understand team dynamics and improve collaboration with personality-based insights.'
            ],
            [
                'title' => 'Professional Grade',
                'description' => 'Built on validated psychological frameworks trusted by HR professionals worldwide.'
            ]
        ];

        return view('user.landing', compact('features'));
    }

    public function showOverview()
    {
        return view('user.overview');
    }

    public function showPersonalInfo()
    {
        return view('user.personal-info');
    }

    public function storePersonalInfo(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:respondents,email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:20',
        ]);

        $respondent = Respondent::create($validated);

        // Store respondent ID in session for the assessment
        session(['respondent_id' => $respondent->id]);

        // Redirect to the actual assessment page (you'll create this next)
        return redirect()->route('assessment.start');
    }
}