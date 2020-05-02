<?php

namespace App\Http\Controllers;

use App\Place;
use App\Room;
use App\Round;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
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

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Room $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Room $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Room $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Room $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        //
    }

    public function prepare(Request $request, string $code)
    {
        try {
            DB::beginTransaction();
            $room = Room::find($code);
            $room->status = 'prepare';
            $newUsers = User::where('room_code', $code)->get()->toArray();
            $oldUsers = Round::where('room_code', $code)->get();
            Round::destroy($oldUsers->pluck('uuid')->toArray());
            foreach ($newUsers as &$user) {
                $user['user_uuid'] = $user['uuid'];
                unset($user['uuid']);
                unset($user['name']);
                unset($user['role_label']);
                unset($user['created_at']);
                unset($user['updated_at']);
            }
            $spyUuid = $newUsers[array_rand($newUsers, 1)]['user_uuid'];
            Round::insert($newUsers);
            $randomPlace = Place::all()->random(1)->first()->label;
            $room->place_label = $randomPlace;
            $room->spy_uuid = $spyUuid;
            $room->fill($request->all())->save();
            DB::commit();
            return $room;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function playing(Request $request, string $code)
    {
        $roleReactions = Round::where('room_code', 'XVQX0H')->pluck('role_reaction')->toArray();
        $room = Room::find($code);
        if (!in_array(null, $roleReactions)) {
            $room->status = 'playing';
            $room->fill($request->all())->save();
        }
        return $room;
    }

    public function finished(Request $request, string $code)
    {
        $room = Room::find($code);
        $room->status = 'finished';
        $room->fill($request->all())->save();
        $room->spy_name = User::find($room->spy_uuid)->name;
        $voters = Round::where('voted_for_user_uuid', $room->spy_uuid)->pluck('user_uuid')->toArray();
        foreach ($voters as &$voter) {
            $voter = User::find($voter)->name;
        }
        $room->voters = $voters;
        return $room;
    }

    public function reset(Request $request, string $code)
    {
        $room = Room::find($code);
        $room->status = null;
        $oldUsers = Round::where('room_code', $code)->get();
        foreach ($oldUsers->pluck('user_uuid')->toArray() as $userUuid) {
            Round::where('user_uuid', $userUuid)->delete();
        }
        $room->fill($request->all())->save();

        return $room;
    }
}
