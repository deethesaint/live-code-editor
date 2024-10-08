<?php

namespace App\Services;

use App\Http\Requests\FinishContestFormRequest;
use App\Models\Participation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ParticipationService
{
    public function participate(Request $request)
    {
        $participation = Participation::query();
        $participation->where('user_id', $request->user()->id);
        $participation->where('contest_id', $request->id);
        $participation->with('contest');
        $participation = $participation->first();
        if ($participation) {
            return $participation;
        } else {
            $participation = new Participation();
            $participation->contest_id = $request->id;
            $participation->user_id = $request->user()->id;
            $participation->started_at = Carbon::now();
            $participation->save();
        }
        return Participation::where('user_id', $request->user()->id)->first();
    }

    public function getParticipationU(Request $request)
    {
        $participation = Participation::query();
        $participation->where('user_id', $request->user()->id);
        $participation->where('contest_id', $request->input('contest_id'));
        return $participation->first();
    }

    public function finish(FinishContestFormRequest $request)
    {
        $participation = Participation::where('id', $request->input('id'))->first();
        $participation->update([
            'finished_at' => Carbon::now(),
            'finished_problems' => $request->input('finishedProblems')
        ]);
        return $participation;
    }
}
