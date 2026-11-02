<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/kecamatans.csv');
        if (!file_exists($path)){
            $this->command->error('File kecamatans.csv tidak ditemukan.');
            return;
        }
        $handle = fopen($path, 'r');

        fgetcsv($handle);
        $data = [];
        while (($row = fgetcsv($handle)) !== false){
            if (count($row) < 3){
                continue;
            }
            $kodeKecamatan = trim($row[0]);
            $namaKecamatan = trim($row[1]);

            $kodeKabupatenKota = substr($kodeKecamatan, 0, 4);
            $kabupatenKotaId = DB::table('kabupaten_kotas')->where('kode_kabupaten_kota', $kodeKabupatenKota)->value('id');
            if (!$kabupatenKotaId){
                $this->command->warn(
                    "Kabupaten/kota dengan kode {$kodeKabupatenKota} tidak ditemukan untuk kecamatan {$namaKecamatan}"
                );
                continue;
            }
            $data[] = [
                'kabupaten_kota_id' => $kabupatenKotaId,
                'kode_kecamatan' => $kodeKecamatan,
                'nama_kecamatan' => $namaKecamatan,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($data) >= 500){
                DB::table('kecamatans')->insert($data);
                $data = [];
            }
        }

        if (!empty($data)){
            DB::table('kecamatans')->insert($data);
        }
        fclose($handle);
        $jumlah = DB::table('kecamatans')->count();
        $this->command->info(
            "Data kecamatan berhasil dimasukkan. Total: {$jumlah} data"
        );
    }
}
