<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JenisCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_cuti')->insert([
            [
                'nama' => 'Izin Cuti Tahunan',
                'maks_hari' => 12,
                'butuh_surat_dokter' => false,
                'butuh_lampiran_tambahan' => false,
                'keterangan' => 'Surat Permohonan + Lampiran Cuti',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Izin Cuti Sakit',
                'maks_hari' => 20,
                'butuh_surat_dokter' => true,
                'butuh_lampiran_tambahan' => false,
                'keterangan' => 'Surat Permohonan + Lampiran Cuti + Surat Keterangan Dokter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Izin Cuti Bersalin',
                'maks_hari' => 90,
                'butuh_surat_dokter' => true,
                'butuh_lampiran_tambahan' => false,
                'keterangan' => 'Surat Permohonan + Lampiran Cuti + Surat Keterangan Dokter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Izin Cuti Alasan Penting',
                'maks_hari' => null,
                'butuh_surat_dokter' => false,
                'butuh_lampiran_tambahan' => true,
                'keterangan' => 'Surat Permohonan + Lampiran Cuti + Lampiran Tambahan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Izin Cuti Besar',
                'maks_hari' => 12,
                'butuh_surat_dokter' => false,
                'butuh_lampiran_tambahan' => true,
                'keterangan' => 'Surat Permohonan + Lampiran Cuti + Lampiran Tambahan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
