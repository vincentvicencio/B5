<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Handles the main landing page for the Trait Assessment tool.
 */
class LandingController extends Controller
{
    /**
     * Display the main landing page view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Define the data for the feature cards.
        // In a complex application, this data would typically come from a database.
        $features = [
            [
                'title' => 'Quick & Efficient',
                'description' => 'Complete assessment in under 20 minutes with immediate results.',
            ],
            [
                'title' => 'Detailed Analytics',
                'description' => 'Comprehensive trait breakdown with hiring recommendations.',
            ],
            [
                'title' => 'Team Focused',
                'description' => 'Evaluate key traits for teamwork, leadership, and collaboration.',
            ],
            [
                'title' => 'Professional Grade',
                'description' => 'Scientifically designed for accurate hiring decisions.',
            ],
        ];

        // Pass the feature data to the 'landing' view
        return view('landing', compact('features'));
    }
}
