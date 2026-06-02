<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->string('gender');
            $table->foreignId('school_class_id')->nullable()->constrained('school_classes')->nullOnDelete();
            $table->string('parent_phone')->nullable();
            $table->string('address')->nullable();
            $table->text('social_status')->nullable();
            $table->text('medical_notes')->nullable();
            $table->string('status')->default('actif');
            $table->timestamp('archived_at')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();
            $table->index(['school_class_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
