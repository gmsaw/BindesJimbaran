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
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('nik', 228)->unique();
            $table->string('no_kk', 228)->unique();
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->string('birth_place', 100);
            $table->enum('religion', ['islam', 'hindu', 'buddha', 'katolik', 'protestan', 'konghucu']);
            $table->enum('education', ['TIDAK SEKOLAH', 'SD', 'SMP', 'SLTA/SEDERAJAT', 'DIPLOMA', 'SARJANA', 'PASCASARJANA']);
            $table->string('job', 50);
            $table->enum('blood_type', ['A', 'B', 'AB', 'O'])->nullable();
            $table->enum('marital_status', ['kawin tercatat', 'belum kawin', 'cerai']);
            $table->date('marital_date')->nullable();
            $table->enum('family_status', ['kepala keluarga', 'istri', 'anak', 'orang tua']);
            $table->string('nationality', 25)->default('INDONESIA');
            $table->unsignedBigInteger('father_id')->nullable();
            $table->unsignedBigInteger('mother_id')->nullable();
            $table->enum('status', ['active', 'moved', 'decease'])->default('active');
            
            $table->foreign('father_id')->references('id')->on('residents')->onDelete('set null');
            $table->foreign('mother_id')->references('id')->on('residents')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
