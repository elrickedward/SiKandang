<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Kandang (Pemilik mengelola Kandang)
        Schema::create('kandang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_kandang');
            $table->string('jenis_ayam');
            $table->integer('populasi_awal');
            $table->integer('kapasitas');
            $table->date('tanggal_masuk');
            $table->timestamps();
        });

        // 2. Tabel Pakan
        Schema::create('pakan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pakan');
            $table->string('satuan')->default('kg');
            $table->decimal('stok_saat_ini', 10, 2)->default(0);
            $table->decimal('stok_minimum', 10, 2)->default(20);
            $table->timestamps();
        });

        // 3. Tabel Pakan Masuk
        Schema::create('pakan_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pakan_id')->constrained('pakan')->cascadeOnDelete();
            $table->date('tanggal');
            $table->decimal('jumlah', 10, 2);
            $table->decimal('harga_satuan', 12, 2);
            $table->string('pemasok')->nullable();
            $table->timestamps();
        });

        // 4. Tabel Catatan Harian
        Schema::create('catatan_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kandang_id')->constrained('kandang')->cascadeOnDelete();
            $table->foreignId('pakan_id')->constrained('pakan')->restrictOnDelete();
            $table->date('tanggal');
            $table->integer('jumlah_ayam');
            $table->integer('kematian')->default(0);
            $table->decimal('pakan_terpakai', 10, 2);
            $table->string('kondisi')->default('Normal');
            $table->timestamps();
        });

        // 5. Tabel Riwayat Kesehatan
        Schema::create('riwayat_kesehatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kandang_id')->constrained('kandang')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('gejala_penyakit');
            $table->text('tindakan');
            $table->timestamps();
        });

        // 6. Tabel Aturan Rekomendasi (Rule-Based DSS)
        Schema::create('aturan_rekomendasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('kode_aturan')->unique();
            $table->string('nama_aturan');
            $table->enum('kategori', ['pakan', 'kesehatan', 'keuangan']);
            $table->string('parameter');
            $table->string('operator')->default('<=');
            $table->decimal('nilai_ambang', 10, 2)->nullable();
            $table->decimal('nilai_ambang_atas', 10, 2)->nullable();
            $table->string('kata_kunci_kondisi')->nullable();
            $table->text('saran_tindakan');
            $table->enum('prioritas', ['Segera', 'Waspada', 'Aman'])->default('Waspada');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // 7. Tabel Rekomendasi (Hasil Evaluasi Sistem)
        Schema::create('rekomendasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kandang_id')->nullable()->constrained('kandang')->cascadeOnDelete();
            $table->foreignId('aturan_rekomendasi_id')->nullable()->constrained('aturan_rekomendasi')->nullOnDelete();
            $table->date('tanggal');
            $table->string('nama_aturan');
            $table->text('isi_rekomendasi');
            $table->text('alasan_data');
            $table->enum('prioritas', ['Segera', 'Waspada', 'Aman']);
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->timestamps();
        });

        // 8. Tabel Simulasi Biaya (IOFC)
        Schema::create('simulasi_biaya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_simulasi');
            $table->date('tanggal');
            $table->decimal('rencana_pakan', 10, 2);
            $table->decimal('harga_pakan', 12, 2);
            $table->integer('ayam_dijual');
            $table->decimal('harga_jual', 12, 2);
            $table->decimal('est_biaya', 14, 2);
            $table->decimal('est_pendapatan', 14, 2);
            $table->decimal('est_laba', 14, 2);
            $table->timestamps();
        });

        // 9. Tabel Produk
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_produk');
            $table->string('satuan')->default('ekor');
            $table->decimal('harga_satuan', 12, 2);
            $table->integer('stok')->default(0);
            $table->string('foto')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 10. Tabel Pembeli (Tanpa Login)
        Schema::create('pembeli', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_wa');
            $table->text('alamat');
            $table->timestamps();
        });

        // 11. Tabel Pesanan
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembeli_id')->constrained('pembeli')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nomor_pesanan')->unique();
            $table->dateTime('tanggal_pesanan');
            $table->decimal('total_harga', 14, 2);
            $table->string('metode_pembayaran')->default('COD / Bayar di Tempat');
            $table->enum('status', ['Baru', 'Diproses', 'Selesai', 'Dibatalkan'])->default('Baru');
            $table->timestamps();
        });

        // 12. Tabel Detail Pesanan
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->restrictOnDelete();
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 14, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('pembeli');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('simulasi_biaya');
        Schema::dropIfExists('rekomendasi');
        Schema::dropIfExists('aturan_rekomendasi');
        Schema::dropIfExists('riwayat_kesehatan');
        Schema::dropIfExists('catatan_harian');
        Schema::dropIfExists('pakan_masuk');
        Schema::dropIfExists('pakan');
        Schema::dropIfExists('kandang');
    }
};