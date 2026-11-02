<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KabupatenKotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/kabupaten_kotas.csv');
        if (!file_exists($path)){
            $this->command->error('File kabupaten_kotas.csv tidak ditemukan.');
            return;
        }
        $handle = fopen($path, 'r');

        fgetcsv($handle);
        $data = [];
        while (($row = fgetcsv($handle)) !== false){
            if (count($row) < 3){
                continue;
            }
            $kodeKabupatenKota = trim($row[0]);
            $namaKabupatenKota = trim($row[1]);
            $kodeProvinsi = substr($kodeKabupatenKota, 0, 2);

            $provinsiId = DB::table('provinsis')->where('kode_provinsi', $kodeProvinsi)->value('id');
            if (!$provinsiId){
                $this->command->warn(
                    "Provinsi dengan kode {$kodeProvinsi} tidak ditemukan untuk {$namaKabupatenKota}"
                );
                continue;
            }
            $data[] = [
                'provinsi_id' => $provinsiId,
                'kode_kabupaten_kota' => $kodeKabupatenKota,
                'nama_kabupaten_kota' => $namaKabupatenKota,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (count($data) >= 500){
                DB::table('kabupaten_kotas')->insert($data);
                $data = [];
            }
        }

        if (!empty($data)){
            DB::table('kabupaten_kotas')->insert($data);
        }
        fclose($handle);

        $jumlah = DB::table('kabupaten_kotas')->count();
        $this->command->info(
            "Data kabupaten/kota berhasil dimasukkan. Total: {$jumlah} data"
        );
    }
}
