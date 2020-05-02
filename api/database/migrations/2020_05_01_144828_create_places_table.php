<?php

use App\Place;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('label');
        });

        $labels = [
            "museum",
            "stadium",
            "barber shop",
            "night club",
            "medieval castle",
            "fast food restaurant",
            "church",
            "mosque",
            "school",
            "university",
            "supermarket",
            "rescue boat",
            "tropical island",
            "pharmacy",
            "hospital",
            "sex shop",
            "running sushi",
            "car",
            "whale stomache",
            "prison",
            "chernobyl factory",
            "planet mars",
            "casino",
            "auto service center",
            "kindergarden",
            "betting place",
            "jewelery store",
            "bakery",
            "re-education school",
            "fish market",
            "dictator's residence",
            "hostel",
            "big brother house",
            "military bunker",
            "apocalypse bunker",
            "middle of the street",
            "mountain",
            "forest",
            "farm",
            "desert",
            "border point",
            "office",
            "toilette",
            "call center",
            "animal rescue home",
            "candy shop",
            "zoo",
            "parlament",
            "paralel dimension",
            "submarine",
            "helicopter",
            "bus",
            "train",
            "airplane",
            "top-fest scene",
            "small village",
            "satellite",
            "sailing-boat",
            "bermuda triangle",
            "wedding",
            "movie scene",
            "lake",
            "river",
            "bunjee jumping bridge",
            "paradise",
            "hell",
            "bar",
            "tv studio",
        ];

        foreach ($labels as $label) {
            Place::create([
                'label' => $label,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
