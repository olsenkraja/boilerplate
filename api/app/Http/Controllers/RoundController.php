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
            if ($userUuid !== $votedForUserUuid) {
                DB::beginTransaction();
                $voter = Round::where('user_uuid', $userUuid)->first();
                $room = Room::find($voter->room_code);
                if ($room->status === 'playing') {
                    $voter->voted_for_user_uuid = $votedForUserUuid;
                    $voter->save();
                    $users = Round::where('room_code', $room->code)->get();
                    $winners = Round::where('voted_for_user_uuid', $votedForUserUuid)->get();

                    if ($room->min_vote_percentage < $winners->count() / $users->count() * 100) {
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
                        $room->status = 'finished';
                        $room->save();
                    }
                }
            }
            DB::commit();

            return $room->status;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function reveal(string $userUuid, string $placeLabel)
    {
        try {
            DB::beginTransaction();
            $voter = Round::where('user_uuid', $userUuid)->first();
            $room = Room::find($voter->room_code);
            if ($room->status === 'playing' && $room->spy_uuid === $userUuid) {
                $users = Round::where('room_code', $room->code)->get();
                foreach ($users->toArray() as $user) {
                    $score = Score::where('user_uuid', $user['user_uuid'])->first();
                    $isSpy = $user['user_uuid'] === $room->spy_uuid;
                    $correctPlace = $room->place_label === $placeLabel;
                    if ($score) {
                        if ($isSpy && $correctPlace) {
                            $score->points = $score->points + $room->points_for_spy_win;
                        } else if (!$isSpy && !$correctPlace) {
                            $score->points = $score->points + $room->points_for_spy_loss;
                        }
                        $score->rounds_played = $score->rounds_played + 1;
                        $score->save();
                    } else {
                        if ($isSpy && $correctPlace) {
                            $points = $room->points_for_spy_win;
                        } else if (!$isSpy && !$correctPlace) {
                            $points = $room->points_for_spy_loss;
                        } else {
                            $points = 0;
                        }
                        Score::create([
                            'room_code' => $room->code,
                            'user_uuid' => $user['user_uuid'],
                            'points' => $points,
                            'rounds_played' => 1,
                        ]);
                    }
                }
                $room->status = 'finished';
                $room->save();
            }
            DB::commit();

            return $room->status;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
