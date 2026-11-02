<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $path = database_path('seeders/data/provinsis.csv');

        if (!file_exists($path)) {
            $this->command->error('File provinsis.csv tidak ditemukan.');
            return;
        }

        $handle = fopen($path, 'r');

        // Lewati baris header
        fgetcsv($handle);

        $data = [];

        while (($row = fgetcsv($handle)) !== false) {

            if (count($row) < 2) {
                continue;
            }

            $data[] = [
                'kode_provinsi' => trim($row[0]),
                'nama_provinsi' => trim($row[1]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($data)) {
            DB::table('provinsis')->insert($data);
        }

        fclose($handle);

        $this->command->info(
            count($data) . ' data provinsi berhasil dimasukkan.'
        );
    }
}
