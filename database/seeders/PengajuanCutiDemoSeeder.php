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
        $ids = []; // akan menampung id pengajuan yang baru dibuat

        // 1. Pengajuan Cuti (3 data)
        $pengajuanData = [
            [
                'kode_pengajuan'     => 'CUTI-2025-00001',
                'nip'                => '198501012010011001',
                'nama_lengkap'       => 'Budi Santoso',
                'tempat_lahir'       => 'Jakarta',
                'tanggal_lahir'      => '1985-01-01',
                'jenis_kelamin'      => 'Laki-laki',        // sesuaikan dengan ENUM kamu
                'pangkat_golongan'   => 'III/d',
                'jabatan'            => 'Analis Kepegawaian',
                'unit_kerja'         => 'Bagian Kepegawaian',
                'nomor_hp'           => '081234567890',
                'alamat'             => 'Jl. Sudirman No. 123, Jakarta',
                'jenis_cuti_id'      => 1,
                'tanggal_mulai'      => '2025-12-24',
                'tanggal_selesai'    => '2025-12-31',
                'jumlah_hari'        => 6,
                'alasan_cuti'        => 'Libur akhir tahun bersama keluarga',
                'alamat_selama_cuti' => 'Bandung',
                'kontak_selama_cuti' => '081234567890',
                'status'             => 'disetujui',
                'nomor_surat'        => '800/1234/HRD/2025',
                'final_pdf'          => 'surat_cuti/CUTI-2025-00001.pdf',
                'tanggal_pengajuan'  => now()->subDays(15),
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'kode_pengajuan'     => 'CUTI-2025-00002',
                'nip'                => '199003152015032002',
                'nama_lengkap'       => 'Siti Aminah',
                'tempat_lahir'       => 'Surabaya',
                'tanggal_lahir'      => '1990-03-15',
                'jenis_kelamin'      => 'Perempuan',
                'pangkat_golongan'   => 'III/b',
                'jabatan'            => 'Staff Administrasi',
                'unit_kerja'         => 'Bagian Umum',
                'nomor_hp'           => '085678901234',
                'alamat'             => 'Jl. Gatot Subroto No. 45, Surabaya',
                'jenis_cuti_id'      => 3,
                'tanggal_mulai'      => '2025-12-10',
                'tanggal_selesai'    => '2025-12-20',
                'jumlah_hari'        => 10,
                'alasan_cuti'        => 'Demam berdarah, dirawat di rumah sakit',
                'alamat_selama_cuti' => 'RS Mitra Keluarga Surabaya',
                'kontak_selama_cuti' => '085678901234',
                'status'             => 'sedang_diproses',
                'tanggal_pengajuan'  => now()->subDays(3),
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'kode_pengajuan'     => 'CUTI-2025-00003',
                'nip'                => '197812312004012003',
                'nama_lengkap'       => 'Ahmad Yani',
                'tempat_lahir'       => 'Bandung',
                'tanggal_lahir'      => '1978-12-31',
                'jenis_kelamin'      => 'Laki-laki',
                'pangkat_golongan'   => 'IV/a',
                'jabatan'            => 'Kepala Sub Bagian',
                'unit_kerja'         => 'Bagian Keuangan',
                'nomor_hp'           => '081987654321',
                'alamat'             => 'Jl. Asia Afrika No. 78, Bandung',
                'jenis_cuti_id'      => 4,
                'tanggal_mulai'      => '2025-11-25',
                'tanggal_selesai'    => '2025-11-27',
                'jumlah_hari'        => 3,
                'alasan_cuti'        => 'Menikahkan anak pertama',
                'alamat_selama_cuti' => 'Bandung',
                'kontak_selama_cuti' => '081987654321',
                'status'             => 'ditolak',
                'catatan_penolakan'  => 'Lampiran undangan nikah tidak lengkap',
                'tanggal_pengajuan'  => now()->subDays(20),
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
        ];

        foreach ($pengajuanData as $data) {
            $id = DB::table('pengajuan_cuti')->insertGetId($data);
            $ids[] = $id;
        }

        // Pastikan ID benar (debug cepat)
        // dd($ids); // uncomment kalau masih ragu → harus [1,2,3] atau [10,11,12] dst

        // 2. Berkas Pengajuan
        DB::table('berkas_pengajuan')->insert([
            [
                'pengajuan_cuti_id' => $ids[1], // Siti Aminah (index 1 = record ke-2)
                'tipe_berkas'       => 'surat_dokter',
                'nama_asli'         => 'Surat_Dokter_Siti.pdf',
                'path'              => 'berkas/surat_dokter.pdf',
                'mime_type'         => 'application/pdf',
                'ukuran'            => 285432,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'pengajuan_cuti_id' => $ids[2], // Ahmad Yani
                'tipe_berkas'       => 'undangan_pernikahan',
                'nama_asli'         => 'Undangan_Nikah_Ahmad.pdf',
                'path'              => 'berkas/undangan_nikah.pdf',
                'mime_type'         => 'application/pdf',
                'ukuran'            => 612345,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);

        // 3. Riwayat Status (status_lama nullable di record pertama)
        $riwayat = [
            ['pengajuan_cuti_id' => $ids[0], 'status_lama' => null,       'status_baru' => 'draft',     'catatan' => 'Pengajuan dibuat', 'oleh' => '198501012010011001', 'tanggal' => now()->subDays(15), 'created_at' => now(), 'updated_at' => now()],
            ['pengajuan_cuti_id' => $ids[0], 'status_lama' => 'draft',    'status_baru' => 'menunggu',  'catatan' => 'Diajukan ke atasan', 'oleh' => '198501012010011001', 'tanggal' => now()->subDays(14), 'created_at' => now(), 'updated_at' => now()],
            ['pengajuan_cuti_id' => $ids[0], 'status_lama' => 'menunggu', 'status_baru' => 'disetujui', 'catatan' => 'Disetujui', 'oleh' => '197001011999031001', 'tanggal' => now()->subDays(7), 'created_at' => now(), 'updated_at' => now()],

            ['pengajuan_cuti_id' => $ids[2], 'status_lama' => null,       'status_baru' => 'draft',     'catatan' => null, 'oleh' => '197812312004012003', 'tanggal' => now()->subDays(20), 'created_at' => now(), 'updated_at' => now()],
            ['pengajuan_cuti_id' => $ids[2], 'status_lama' => 'draft',    'status_baru' => 'menunggu',  'catatan' => null, 'oleh' => '197812312004012003', 'tanggal' => now()->subDays(19), 'created_at' => now(), 'updated_at' => now()],
            ['pengajuan_cuti_id' => $ids[2], 'status_lama' => 'menunggu', 'status_baru' => 'ditolak',   'catatan' => 'Lampiran undangan nikah tidak lengkap', 'oleh' => '197001011999031001', 'tanggal' => now()->subDays(10), 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('riwayat_status')->insert($riwayat);
    }
}
