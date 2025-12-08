<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PengajuanCutiDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $now = Carbon::now();
        $ids = [];

        $pengajuan = [
            // 1. SUDAH FULL APPROVE (disetujui Kasubbag)
            [
                'kode_pengajuan'        => 'CUTI-2025-00001',
                'nip'                   => '198501012010011001',
                'nama_lengkap'          => 'Budi Santoso',
                'jenis_kelamin'         => 'Laki-laki',
                'tempat_lahir'          => 'Jakarta',
                'tanggal_lahir'         => '1985-01-01',
                'pangkat_golongan'      => 'III/d',
                'jabatan'               => 'Analis Kepegawaian',
                'unit_kerja'            => 'Bagian Kepegawaian',
                'nomor_hp'              => '081234567890',
                'alamat'                => 'Jl. Sudirman No. 123, Jakarta',

                'jenis_cuti_id'         => 1,
                'tanggal_mulai'         => '2025-12-24',
                'tanggal_selesai'       => '2025-12-31',
                'jumlah_hari'           => 6,
                'alasan_cuti'           => 'Libur akhir tahun bersama keluarga',
                'alamat_selama_cuti'    => 'Bandung',
                'kontak_selama_cuti'    => '081234567890',

                'status'                => 'disetujui',           // ← sesuai ENUM kamu
                'level_approval'        => 'selesai',
                'status_revisi'         => 'tidak_perlu',
                'nomor_surat'           => '800/123/HRD/2025',
                'final_pdf'             => 'surat_cuti/budi-2025.pdf',

                'approved_by_tu'        => '199003152015032002',
                'approved_at_tu'        => $now->copy()->subDays(7),
                'approved_by_kasubbag'  => '197812312004012003',
                'approved_at_kasubbag'  => $now->copy()->subDays(3),

                'tanggal_pengajuan'     => $now->copy()->subDays(20),
                'created_at'            => $now->copy()->subDays(20),
                'updated_at'            => $now,
            ],

            // 2. SEDANG DIMINTA REVISI OLEH ADMIN TU
            [
                'kode_pengajuan'        => 'CUTI-2025-00002',
                'nip'                   => '199003152015032002',
                'nama_lengkap'          => 'Siti Aminah',
                'jenis_kelamin'         => 'Perempuan',
                'tempat_lahir'          => 'Surabaya',
                'tanggal_lahir'         => '1990-03-15',
                'pangkat_golongan'      => 'III/b',
                'jabatan'               => 'Staff Administrasi',
                'unit_kerja'            => 'Bagian Umum',
                'nomor_hp'              => '085678901234',
                'alamat'                => 'Jl. Gatot Subroto No. 45, Surabaya',

                'jenis_cuti_id'         => 2, // Cuti Sakit
                'tanggal_mulai'         => '2025-12-10',
                'tanggal_selesai'       => '2025-12-20',
                'jumlah_hari'           => 10,
                'alasan_cuti'           => 'Demam berdarah, dirawat di rumah sakit',
                'alamat_selama_cuti'    => 'RS Mitra Keluarga Surabaya',
                'kontak_selama_cuti'    => '085678901234',

                'status'                => 'sedang_diproses',     // ← masih diproses (ada revisi)
                'level_approval'        => 'tu',
                'status_revisi'         => 'perlu_revisi',
                'catatan_revisi'        => 'Surat dokter buram dan tidak ada cap rumah sakit. Mohon upload ulang dengan format PDF yang jelas + cap basah.',
                'revisi_oleh'           => '199112312018031001', // NIP Admin TU

                'tanggal_pengajuan'     => $now->copy()->subDays(5),
                'created_at'            => $now->copy()->subDays(5),
                'updated_at'            => $now,
            ],

            // 3. SUDAH LOLOS TU → SEDANG DIPROSES KASUBBAG
            [
                'kode_pengajuan'        => 'CUTI-2025-00003',
                'nip'                   => '197812312004012003',
                'nama_lengkap'          => 'Ahmad Yani',
                'jenis_kelamin'         => 'Laki-laki',
                'tempat_lahir'          => 'Bandung',
                'tanggal_lahir'         => '1978-12-31',
                'pangkat_golongan'      => 'IV/a',
                'jabatan'               => 'Kepala Sub Bagian',
                'unit_kerja'            => 'Bagian Keuangan',
                'nomor_hp'              => '081987654321',
                'alamat'                => 'Jl. Asia Afrika No. 78, Bandung',

                'jenis_cuti_id'         => 4, // Cuti Alasan Penting
                'tanggal_mulai'         => '2025-11-25',
                'tanggal_selesai'       => '2025-11-27',
                'jumlah_hari'           => 3,
                'alasan_cuti'           => 'Menikahkan anak pertama',
                'alamat_selama_cuti'    => 'Bandung',
                'kontak_selama_cuti'    => '081987654321',

                'status'                => 'sedang_diproses',
                'level_approval'        => 'kasubbag',
                'status_revisi'         => 'tidak_perlu',

                'approved_by_tu'        => '199112312018031001',
                'approved_at_tu'        => $now->copy()->subDays(6),

                'tanggal_pengajuan'     => $now->copy()->subDays(15),
                'created_at'            => $now->copy()->subDays(15),
                'updated_at'            => $now,
            ],
        ];

        foreach ($pengajuan as $p) {
            $ids[] = DB::table('pengajuan_cuti')->insertGetId($p);
        }

        // Berkas
        DB::table('berkas_pengajuan')->insert([
            ['pengajuan_cuti_id' => $ids[1], 'tipe_berkas' => 'surat_dokter', 'nama_asli' => 'Surat_Dokter_Siti.pdf', 'path' => 'berkas/surat_dokter_siti.pdf', 'mime_type' => 'application/pdf', 'ukuran' => 285432],
            ['pengajuan_cuti_id' => $ids[2], 'tipe_berkas' => 'undangan_nikah', 'nama_asli' => 'Undangan_Ahmad.pdf', 'path' => 'berkas/undangan_ahmad.pdf', 'mime_type' => 'application/pdf', 'ukuran' => 612345],
        ]);

        // Riwayat Status (pakai status ENUM yang sudah ada)
        $riwayat = [
            // Budi - sudah disetujui
            ['pengajuan_cuti_id' => $ids[0], 'status_lama' => null, 'status_baru' => 'draft', 'catatan' => 'Pengajuan dibuat', 'oleh' => '198501012010011001', 'tanggal' => $now->copy()->subDays(20)],
            ['pengajuan_cuti_id' => $ids[0], 'status_lama' => 'draft', 'status_baru' => 'diajukan', 'catatan' => 'Diajukan', 'oleh' => '198501012010011001', 'tanggal' => $now->copy()->subDays(19)],
            ['pengajuan_cuti_id' => $ids[0], 'status_lama' => 'diajukan', 'status_baru' => 'sedang_diproses', 'catatan' => 'Diproses TU', 'oleh' => '199112312018031001', 'tanggal' => $now->copy()->subDays(15)],
            ['pengajuan_cuti_id' => $ids[0], 'status_lama' => 'sedang_diproses', 'status_baru' => 'sedang_diproses', 'catatan' => 'Diteruskan ke Kasubbag', 'oleh' => '199112312018031001', 'tanggal' => $now->copy()->subDays(10)],
            ['pengajuan_cuti_id' => $ids[0], 'status_lama' => 'sedang_diproses', 'status_baru' => 'disetujui', 'catatan' => 'Disetujui Kasubbag', 'oleh' => '197812312004012003', 'tanggal' => $now->copy()->subDays(3)],

            // Siti - diminta revisi
            ['pengajuan_cuti_id' => $ids[1], 'status_lama' => null, 'status_baru' => 'draft', 'catatan' => 'Dibuat', 'oleh' => '199003152015032002', 'tanggal' => $now->copy()->subDays(5)],
            ['pengajuan_cuti_id' => $ids[1], 'status_lama' => 'draft', 'status_baru' => 'diajukan', 'catatan' => 'Diajukan', 'oleh' => '199003152015032002', 'tanggal' => $now->copy()->subDays(5)],
            ['pengajuan_cuti_id' => $ids[1], 'status_lama' => 'diajukan', 'status_baru' => 'sedang_diproses', 'catatan' => 'Minta revisi berkas', 'oleh' => '199112312018031001', 'tanggal' => $now->copy()->subDays(2)],

            // Ahmad - sedang di Kasubbag
            ['pengajuan_cuti_id' => $ids[2], 'status_lama' => null, 'status_baru' => 'draft', 'catatan' => 'Dibuat', 'oleh' => '197812312004012003', 'tanggal' => $now->copy()->subDays(15)],
            ['pengajuan_cuti_id' => $ids[2], 'status_lama' => 'draft', 'status_baru' => 'diajukan', 'catatan' => 'Diajukan', 'oleh' => '197812312004012003', 'tanggal' => $now->copy()->subDays(14)],
            ['pengajuan_cuti_id' => $ids[2], 'status_lama' => 'diajukan', 'status_baru' => 'sedang_diproses', 'catatan' => 'Disetujui TU, diteruskan ke Kasubbag', 'oleh' => '199112312018031001', 'tanggal' => $now->copy()->subDays(6)],
        ];

        DB::table('riwayat_status')->insert($riwayat);
    }
}
