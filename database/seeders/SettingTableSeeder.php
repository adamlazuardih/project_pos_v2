<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('setting')->insert([
            'id_setting' => 1,
            'nama_perusahaan' => 'Project POS',
            'alamat' => 'Malang',
            'telepon' => '021',
            'tipe_nota' => 1, //1 kecil, 2 besar
            'diskon' => 5,
            'path_logo' =>'/images/logo.png',
            'path_kartu_member' =>'/images/member.png'
        ]);
    }
}
