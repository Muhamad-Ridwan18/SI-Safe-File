<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;

class DokumenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['title'=>'Dokumen Administrasi','file_path'=>'administrasi.pdf', 'user_id'=>'1'],
            ['title'=>'Dokumen Kurikulum','file_path'=>'kurikulum.pdf', 'user_id'=>'2'],
        ];

        Document::insert($data);
    }
}
