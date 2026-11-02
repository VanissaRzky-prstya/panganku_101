<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaggleWilayahSeeder extends Seeder
{
    public function run(): void{
        $file=database_path('seeders/data/kaggle/provinsi.csv');
        $handle=fopen($file, 'r');
        fgetcsv($handle);
        while(( $row = fgetcsv($handle)) !== false){
            DB::table('provinsis')->insert([
                'kode_provinsi' => $row[0],
                'nama_provinsi' => $row[2],
                'created_at' => now(),
                'updated_at' => now(),
            ]);   
        }
        fclose($handle);

        $file=database_path('seeders/data/kaggle/kabupaten.csv');
        $handle=fopen($file, 'r');
        fgetcsv($handle);
        while(( $row = fgetcsv($handle)) !== false){
            $provinsi = DB::table('provinsis')->where('kode_provinsi', $row[1])->first();
            if ($provinsi){
                DB::table('kabupaten_kotas')->insert([
                    'provinsi_id' => $provinsi->id,
                    'kode_kabupaten_kota' => $row[0],
                    'nama_kabupaten_kota' => $row[2],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }   
        }
        fclose($handle);

        $file=database_path('seeders/data/kaggle/kecamatan.csv');
        $handle=fopen($file, 'r');
        fgetcsv($handle);
        while(($row = fgetcsv($handle)) !== false){
            $kabupaten = DB::table('kabupaten_kotas')->where('kode_kabupaten_kota', $row[1])->first();
            if ($kabupaten){
                DB::table('kecamatans')->insert([
                    'kabupaten_kota_id' => $kabupaten->id,
                    'kode_kecamatan' => $row[0],
                    'nama_kecamatan' => $row[2],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        fclose($handle);

        $file=database_path('seeders/data/kaggle/desa.csv');
        $handle=fopen($file, 'r');
        fgetcsv($handle);
        while(($row = fgetcsv($handle)) !== false){
            $kecamatan = DB::table('kecamatans')->where('kode_kecamatan', $row[1])->first();
            if ($kecamatan){
                DB::table('kelurahan_desas')->insert([
                    'kecamatan_id' => $kecamatan->id,
                    'kode_kelurahan_desa' => $row[0],
                    'nama_kelurahan_desa' => $row[2],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        fclose($handle);
    }
}