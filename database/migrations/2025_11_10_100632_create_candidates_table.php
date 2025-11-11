<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Enums\GenderEnums;
use App\Models\Competition;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Competition::class)->onDelete('cascade');            
            $table->string('photo');
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', [
                GenderEnums::MASCULIN->value,
                GenderEnums::FEMININ->value,
            ]);
            $table->date('birth_date');
            $table->float('height');
            $table->float('weight');
            $table->string('nationality');
            $table->text('description');
            $table->integer('accumulated_vote')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
