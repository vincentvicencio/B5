<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TraitModel;
use App\Models\Respondent;

class RespondentsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $respondents = Respondent::with('assessments')->paginate(10);
        return view('respondents.index', compact('respondents'));
    }

  public function getRespondents(Request $request)
{   
    
    $traits = TraitModel::select('title')->pluck('title')->toArray();

    $query = Respondent::query();

    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }
    
    $query->leftJoin('assessments', 'respondents.id', '=', 'assessments.respondent_id')
          ->select(
              'respondents.id',
              'respondents.first_name',
              'respondents.last_name',
              'respondents.email',
              'assessments.all_response',
              'assessments.overall_score',
              'assessments.interpretation'
          );

    $respondents = $query->get();
    $formatted = $respondents->map(function ($r) use ($traits) {
        $scores = [];
        foreach ($traits as $t) {
            $score = rand(50, 95);
            $scores[$t] = "{$score}%";
        }

        return [
            'id' => $r->id,
            'name' => "{$r->first_name} {$r->last_name}",
            'email' => $r->email,
            'scores' => $scores,
            'overall_score' => $r->overall_score ? "{$r->overall_score}%" : "N/A",
            'interpretation' => $r->interpretation ?? 'No interpretation',
        ];
    });

    return response()->json([
        'respondents' => $formatted,
        'traits' => $traits,
    ]);
}


    public function destroy($id)
    {
        // Simulate deletion for now
        return response()->json(['message' => "Respondent ID $id deleted successfully."]);
    }

    public function show()
    {
        return view('respondents.show');
    }
}
