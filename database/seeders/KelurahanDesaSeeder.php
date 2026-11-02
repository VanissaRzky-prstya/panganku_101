<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelurahanDesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $path = database_path('seeders/data/kelurahan_desas.csv');

        if (!file_exists($path)) {
            $this->command->error(
                'File kelurahan_desas.csv tidak ditemukan.'
            );

            return;
        }

        $handle = fopen($path, 'r');

        // Lewati header CSV
        fgetcsv($handle);

        $data = [];

        while (($row = fgetcsv($handle)) !== false) {

            if (count($row) < 3) {
                continue;
            }

            $kodeKelurahanDesa = trim($row[0]);
            $namaKelurahanDesa = trim($row[1]);

            /*
             * Contoh kode:
             *
             * 3577011001
             *
             * 35     = Provinsi
             * 77     = Kabupaten/Kota
             * 01     = Kecamatan
             * 1001   = Kelurahan/Desa
             *
             * Maka kode kecamatan:
             *
             * 357701
             */
            $kodeKecamatan = substr($kodeKelurahanDesa, 0, 6);

            // Cari ID kecamatan berdasarkan kode wilayah
            $kecamatanId = DB::table('kecamatans')
                ->where('kode_kecamatan', $kodeKecamatan)
                ->value('id');

            if (!$kecamatanId) {
                $this->command->warn(
                    "Kecamatan dengan kode {$kodeKecamatan} tidak ditemukan untuk {$namaKelurahanDesa}."
                );

                continue;
            }

            /*
             * Tentukan tipe wilayah.
             *
             * Biasanya dataset memiliki informasi
             * kelurahan/desa pada kolom berikutnya.
             */
            $tipeWilayah = null;

            if (isset($row[2])) {
                $tipeWilayah = trim($row[2]);
            }

            $data[] = [
                'kecamatan_id' => $kecamatanId,
                'kode_kelurahan_desa' => $kodeKelurahanDesa,
                'nama_kelurahan_desa' => $namaKelurahanDesa,
                'tipe_wilayah' => $tipeWilayah,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            /*
             * Insert bertahap.
             */
            if (count($data) >= 500) {
                DB::table('kelurahan_desas')->insert($data);
                $data = [];
            }
        }

        // Masukkan data yang tersisa
        if (!empty($data)) {
            DB::table('kelurahan_desas')->insert($data);
        }

        fclose($handle);

        $jumlah = DB::table('kelurahan_desas')->count();

        $this->command->info(
            "Data kelurahan/desa berhasil dimasukkan. Total: {$jumlah} data."
        );
    }
}
