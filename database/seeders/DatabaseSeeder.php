<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kandang;
use App\Models\Pakan;
use App\Models\AturanRekomendasi;
use App\Models\Produk;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Pemilik
        $pemilik = User::create([
            'name' => 'Bapak Lukman',
            'email' => 'admin@sikandang.com',
            'password' => bcrypt('rahasia123'),
        ]);

        // 2. Data Kandang Awal
        Kandang::create([
            'user_id' => $pemilik->id,
            'nama_kandang' => 'Kandang A',
            'jenis_ayam' => 'Ayam Broiler',
            'populasi_awal' => 250,
            'kapasitas' => 300,
            'tanggal_masuk' => now()->subDays(14)->toDateString(),
        ]);

        // 3. Data Stok Pakan Awal
        Pakan::create([
            'nama_pakan' => 'Pakan Broiler Starter (BR-1)',
            'satuan' => 'kg',
            'stok_saat_ini' => 42,
            'stok_minimum' => 30,
        ]);

        Pakan::create([
            'nama_pakan' => 'Pakan Broiler Finisher (BR-2)',
            'satuan' => 'kg',
            'stok_saat_ini' => 100,
            'stok_minimum' => 40,
        ]);

        // 4. Data 8 Aturan Rekomendasi Awal (Tabel 3.1 Proposal)
        $aturanList = [
            [
                'kode_aturan' => 'R1',
                'nama_aturan' => 'Stok Pakan Menipis (Kritis)',
                'kategori' => 'pakan',
                'parameter' => 'sisa_hari_stok',
                'operator' => '<=',
                'nilai_ambang' => 3,
                'nilai_ambang_atas' => null,
                'kata_kunci_kondisi' => null,
                'saran_tindakan' => 'Segera beli pakan; sistem menampilkan jumlah pembelian yang disarankan.',
                'prioritas' => 'Segera',
            ],
            [
                'kode_aturan' => 'R2',
                'nama_aturan' => 'Persiapan Pembelian Pakan',
                'kategori' => 'pakan',
                'parameter' => 'sisa_hari_stok',
                'operator' => 'between',
                'nilai_ambang' => 4,
                'nilai_ambang_atas' => 7,
                'kata_kunci_kondisi' => null,
                'saran_tindakan' => 'Rencanakan pembelian pakan dan bandingkan harga pemasok.',
                'prioritas' => 'Waspada',
            ],
            [
                'kode_aturan' => 'R3',
                'nama_aturan' => 'Stok Pakan Berlebih',
                'kategori' => 'pakan',
                'parameter' => 'sisa_hari_stok',
                'operator' => '>',
                'nilai_ambang' => 14,
                'nilai_ambang_atas' => null,
                'kata_kunci_kondisi' => null,
                'saran_tindakan' => 'Stok berlebih; tunda pembelian untuk menghemat biaya penyimpanan.',
                'prioritas' => 'Aman',
            ],
            [
                'kode_aturan' => 'R4',
                'nama_aturan' => 'Kematian Harian Melebihi Ambang',
                'kategori' => 'kesehatan',
                'parameter' => 'persentase_kematian',
                'operator' => '>',
                'nilai_ambang' => 1.0,
                'nilai_ambang_atas' => null,
                'kata_kunci_kondisi' => null,
                'saran_tindakan' => 'Periksa kandang, pisahkan ayam yang tampak sakit, dan hubungi petugas kesehatan hewan.',
                'prioritas' => 'Segera',
            ],
            [
                'kode_aturan' => 'R5',
                'nama_aturan' => 'Kematian Meningkat 3 Hari Berturut-turut',
                'kategori' => 'kesehatan',
                'parameter' => 'tren_kematian_3_hari',
                'operator' => '>=',
                'nilai_ambang' => 3,
                'nilai_ambang_atas' => null,
                'kata_kunci_kondisi' => null,
                'saran_tindakan' => 'Evaluasi kebersihan kandang, air minum, dan ventilasi; pantau kondisi setiap hari.',
                'prioritas' => 'Waspada',
            ],
            [
                'kode_aturan' => 'R6',
                'nama_aturan' => 'Nafsu Makan Ayam Menurun',
                'kategori' => 'kesehatan',
                'parameter' => 'kondisi_kandang',
                'operator' => 'contains',
                'nilai_ambang' => null,
                'nilai_ambang_atas' => null,
                'kata_kunci_kondisi' => 'nafsu makan turun',
                'saran_tindakan' => 'Periksa kesehatan ayam, suhu, dan tempat pakan karena konsumsi pakan dipengaruhi kesehatan.',
                'prioritas' => 'Waspada',
            ],
            [
                'kode_aturan' => 'R7',
                'nama_aturan' => 'Riwayat Penyakit Berulang',
                'kategori' => 'kesehatan',
                'parameter' => 'riwayat_penyakit',
                'operator' => 'contains',
                'nilai_ambang' => null,
                'nilai_ambang_atas' => null,
                'kata_kunci_kondisi' => null,
                'saran_tindakan' => 'Tampilkan kembali tindakan yang pernah dicatat dan ingatkan pencegahan serta konsultasi dengan petugas kesehatan hewan.',
                'prioritas' => 'Waspada',
            ],
            [
                'kode_aturan' => 'R8',
                'nama_aturan' => 'Estimasi Simulasi IOFC Negatif (Rugi)',
                'kategori' => 'keuangan',
                'parameter' => 'est_laba',
                'operator' => '<',
                'nilai_ambang' => 0,
                'nilai_ambang_atas' => null,
                'kata_kunci_kondisi' => null,
                'saran_tindakan' => 'Estimasi rugi; tinjau kembali rencana pembelian pakan atau harga jual.',
                'prioritas' => 'Waspada',
            ],
        ];

        foreach ($aturanList as $aturan) {
            AturanRekomendasi::create(array_merge($aturan, ['user_id' => $pemilik->id]));
        }

        // 5. Data Produk Katalog Awal (Sesuai Mockup)
        Produk::create([
            'user_id' => $pemilik->id,
            'nama_produk' => 'Ayam Utuh',
            'satuan' => 'ekor',
            'harga_satuan' => 35000,
            'stok' => 50,
            'deskripsi' => 'Ayam broiler hidup/utuh segar langsung dari kandang.',
        ]);

        Produk::create([
            'user_id' => $pemilik->id,
            'nama_produk' => 'Ayam Potong',
            'satuan' => 'kg',
            'harga_satuan' => 32000,
            'stok' => 40,
            'deskripsi' => 'Ayam potong bersih siap masak per kilogram.',
        ]);
    }
}