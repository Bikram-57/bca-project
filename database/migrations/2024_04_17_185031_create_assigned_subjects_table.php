<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assigned_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('cid');
            $table->unsignedBigInteger('faculty_id');
            $table->unique(['cid', 'faculty_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigned_subjects');
    }
};
