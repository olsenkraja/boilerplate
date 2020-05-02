<?php

namespace App\Http\Controllers;

use App\Room;
use App\Round;
use App\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Round $round
     * @return \Illuminate\Http\Response
     */
    public function show(Round $round)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Round $round
     * @return \Illuminate\Http\Response
     */
    public function edit(Round $round)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Round $round
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Round $round)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Round $round
     * @return \Illuminate\Http\Response
     */
    public function destroy(Round $round)
    {
        //
    }

    public function vote(string $userUuid, string $votedForUserUuid)
    {
        try {
            DB::beginTransaction();
            $voter = Round::where('user_uuid', $userUuid)->first();
            $room = Room::find($voter->room_code);
            if ($room->status === 'playing') {
                $voter->voted_for_user_uuid = $votedForUserUuid;
                $voter->save();
                $users = Round::where('room_code', $room->code)->get();
                $winners = Round::where('voted_for_user_uuid', $votedForUserUuid)->get();

                foreach ($users->toArray() as $user) {
                    $score = Score::where('user_uuid', $user['user_uuid'])->first();
                    if ($score) {
                        if (in_array($user['user_uuid'], $winners->pluck('user_uuid')->toArray())) {
                            $score->points = $score->points + $room->points_for_spy_found;
                        }
                        $score->rounds_played = $score->rounds_played + 1;
                        $score->save();
                    } else {
                        $isWinner = in_array($user['user_uuid'], $winners->pluck('user_uuid')->toArray());
                        Score::create([
                            'room_code' => $room->code,
                            'user_uuid' => $user['user_uuid'],
                            'points' => $isWinner ? $room->points_for_spy_found : 0,
                            'rounds_played' => 1,
                        ]);
                    }
                }
                if ($room->min_vote_percentage < $winners->count() / $users->count() * 100) {
                    $room->status = 'finished';
                    $room->save();
                }
            }
            DB::commit();

//            return $round;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
