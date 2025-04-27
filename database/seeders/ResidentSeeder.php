<?php

namespace Database\Seeders;

use App\Models\Resident;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data pertama - Kepala Keluarga
        $father = Resident::create([
            'name' => 'Ahmad Budiman',
            'nik' => '3273010101010001',
            'no_kk' => '3273010101010001',
            'gender' => 'male',
            'birth_date' => '1980-01-15',
            'birth_place' => 'Jakarta',
            'religion' => 'islam',
            'education' => 'SARJANA',
            'job' => 'PNS',
            'blood_type' => 'A',
            'marital_status' => 'kawin tercatat',
            'marital_date' => '2005-06-20',
            'family_status' => 'kepala keluarga',
            'nationality' => 'INDONESIA',
            'status' => 'active'
        ]);

        // Data kedua - Istri (satu KK dengan Ahmad)
        $mother = Resident::create([
            'name' => 'Siti Aminah',
            'nik' => '3273010202020002',
            'no_kk' => '3273010101010001', // Satu KK dengan Ahmad
            'gender' => 'female',
            'birth_date' => '1985-05-20',
            'birth_place' => 'Bandung',
            'religion' => 'islam',
            'education' => 'SLTA/SEDERAJAT',
            'job' => 'Ibu Rumah Tangga',
            'blood_type' => 'B',
            'marital_status' => 'kawin tercatat',
            'marital_date' => '2005-06-20',
            'family_status' => 'istri',
            'nationality' => 'INDONESIA',
            'status' => 'active'
        ]);

        // Data ketiga - Anak (referensi ke orang tua)
        Resident::create([
            'name' => 'Budi Santoso',
            'nik' => '3273010303030003',
            'no_kk' => '3273010101010001', // Satu KK dengan orang tua
            'gender' => 'male',
            'birth_date' => '1995-10-10',
            'birth_place' => 'Surabaya',
            'religion' => 'protestan',
            'education' => 'DIPLOMA',
            'job' => 'Wiraswasta',
            'blood_type' => 'O',
            'marital_status' => 'belum kawin',
            'marital_date' => null,
            'family_status' => 'anak',
            'nationality' => 'INDONESIA',
            'father_id' => $father->id,
            'mother_id' => $mother->id,
            'status' => 'active'
        ]);
    }
}
