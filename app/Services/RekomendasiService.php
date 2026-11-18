<?php

namespace App\Services;

use App\Models\AturanRekomendasi;
use App\Models\CatatanHarian;
use App\Models\Kandang;
use App\Models\Pakan;
use App\Models\Rekomendasi;
use App\Models\RiwayatKesehatan;
use App\Models\SimulasiBiaya;
use Carbon\Carbon;

class RekomendasiService
{
    /**
     * Evaluasi seluruh aturan pakan dan kesehatan untuk sebuah kandang
     */
    public function evaluasiSemua(?int $kandangId = null): void
    {
        $kandang = $kandangId ? Kandang::find($kandangId) : Kandang::first();
        if (!$kandang) {
            return;
        }

        $this->evaluasiPakan($kandang);
        $this->evaluasiKesehatan($kandang);
    }

    /**
     * Evaluasi Aturan Pakan (R1, R2, R3, dan aturan pakan kustom)
     */
    public function evaluasiPakan(Kandang $kandang): void
    {
        $aturanPakan = AturanRekomendasi::where('kategori', 'pakan')
            ->where('aktif', true)
            ->get();

        foreach (Pakan::all() as $pakan) {
            // Total pakan terpakai 7 hari terakhir
            $tujuhHariLalu = Carbon::today()->subDays(6);
            $total7Hari = CatatanHarian::where('pakan_id', $pakan->id)
                ->where('tanggal', '>=', $tujuhHariLalu)
                ->sum('pakan_terpakai');

            // Rata-rata pakan harian = total 7 hari / 7 (atau fallback ke pemakaian terakhir jika belum 7 hari)
            $rataRataHarian = $total7Hari > 0 ? round($total7Hari / 7, 2) : 0;
            if ($rataRataHarian <= 0) {
                $catatanTerakhir = CatatanHarian::where('pakan_id', $pakan->id)->latest('tanggal')->first();
                $rataRataHarian = $catatanTerakhir ? (float) $catatanTerakhir->pakan_terpakai : 0;
            }

            if ($rataRataHarian <= 0) {
                continue;
            }

            // Sisa hari stok & jumlah pembelian disarankan (target cadangan 10 hari)
            $sisaHariStok = round($pakan->stok_saat_ini / $rataRataHarian, 1);
            $hariCadangan = 10;
            $jumlahDisarankan = max(0, ceil(($rataRataHarian * $hariCadangan) - $pakan->stok_saat_ini));

            foreach ($aturanPakan as $aturan) {
                $terpenuhi = $this->cekKondisiNumerik(
                    $sisaHariStok,
                    $aturan->operator,
                    $aturan->nilai_ambang,
                    $aturan->nilai_ambang_atas
                );

                // Bersihkan rekomendasi aktif lama untuk kombinasi aturan + pakan ini agar tidak menumpuk
                Rekomendasi::where('kandang_id', $kandang->id)
                    ->where('aturan_rekomendasi_id', $aturan->id)
                    ->where('status', 'aktif')
                    ->where('alasan_data', 'like', "%{$pakan->nama_pakan}%")
                    ->delete();

                if ($terpenuhi) {
                    $alasan = "Pakan: {$pakan->nama_pakan} | Sisa stok: {$pakan->stok_saat_ini} {$pakan->satuan} | "
                        . "Rata-rata pakan/hari: {$rataRataHarian} {$pakan->satuan} | Sisa hari stok: {$sisaHariStok} hari | "
                        . "Disarankan beli: {$jumlahDisarankan} {$pakan->satuan} (cadangan {$hariCadangan} hari).";

                    Rekomendasi::create([
                        'kandang_id' => $kandang->id,
                        'aturan_rekomendasi_id' => $aturan->id,
                        'tanggal' => Carbon::today(),
                        'nama_aturan' => "{$aturan->nama_aturan} ({$pakan->nama_pakan})",
                        'isi_rekomendasi' => $aturan->saran_tindakan,
                        'alasan_data' => $alasan,
                        'prioritas' => $aturan->prioritas,
                        'status' => 'aktif',
                    ]);
                }
            }
        }
    }

    /**
     * Evaluasi Aturan Kesehatan (R4, R5, R6, R7, dan aturan kesehatan kustom)
     */
    public function evaluasiKesehatan(Kandang $kandang): void
    {
        $catatanTerbaru = CatatanHarian::where('kandang_id', $kandang->id)
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->take(3)
            ->get();

        if ($catatanTerbaru->isEmpty()) {
            return;
        }

        $hariIni = $catatanTerbaru->first();
        $aturanKesehatan = AturanRekomendasi::where('kategori', 'kesehatan')
            ->where('aktif', true)
            ->get();

        // Hitung persentase kematian harian
        $persenKematian = $hariIni->jumlah_ayam > 0
            ? round(($hariIni->kematian / $hariIni->jumlah_ayam) * 100, 2)
            : 0;

        // Cek tren kematian meningkat 3 hari berturut-turut
        $trenMeningkat3Hari = false;
        if ($catatanTerbaru->count() === 3) {
            $h0 = $catatanTerbaru[0]->kematian;
            $h1 = $catatanTerbaru[1]->kematian;
            $h2 = $catatanTerbaru[2]->kematian;
            if ($h0 > $h1 && $h1 > $h2 && $h0 > 0) {
                $trenMeningkat3Hari = true;
            }
        }

        foreach ($aturanKesehatan as $aturan) {
            $terpenuhi = false;
            $alasan = '';

            if ($aturan->parameter === 'persentase_kematian') {
                $terpenuhi = $this->cekKondisiNumerik($persenKematian, $aturan->operator, $aturan->nilai_ambang);
                $alasan = "Kandang: {$kandang->nama_kandang} | Kematian hari ini: {$hariIni->kematian} ekor dari {$hariIni->jumlah_ayam} ekor ({$persenKematian}%), melebihi ambang {$aturan->nilai_ambang}%.";
            } elseif ($aturan->parameter === 'tren_kematian_3_hari' && $trenMeningkat3Hari) {
                $terpenuhi = true;
                $alasan = "Kandang: {$kandang->nama_kandang} | Kematian meningkat 3 hari berturut-turut ({$catatanTerbaru[2]->kematian} → {$catatanTerbaru[1]->kematian} → {$catatanTerbaru[0]->kematian} ekor).";
            } elseif ($aturan->parameter === 'kondisi_kandang' && $aturan->kata_kunci_kondisi) {
                if (stripos($hariIni->kondisi, $aturan->kata_kunci_kondisi) !== false) {
                    $terpenuhi = true;
                    $alasan = "Kandang: {$kandang->nama_kandang} | Tercatat kondisi '{$hariIni->kondisi}' pada tanggal {$hariIni->tanggal}.";
                }
            }

            if ($terpenuhi) {
                Rekomendasi::updateOrCreate(
                    [
                        'kandang_id' => $kandang->id,
                        'aturan_rekomendasi_id' => $aturan->id,
                        'tanggal' => $hariIni->tanggal,
                        'status' => 'aktif',
                    ],
                    [
                        'nama_aturan' => $aturan->nama_aturan,
                        'isi_rekomendasi' => $aturan->saran_tindakan,
                        'alasan_data' => $alasan,
                        'prioritas' => $aturan->prioritas,
                    ]
                );
            }
        }
    }

    /**
     * Evaluasi Riwayat Penyakit Berulang (Aturan R7) saat input Riwayat Kesehatan
     */
    public function evaluasiRiwayatPenyakit(RiwayatKesehatan $riwayatBaru): void
    {
        $riwayatSebelumnya = RiwayatKesehatan::where('kandang_id', $riwayatBaru->kandang_id)
            ->where('id', '!=', $riwayatBaru->id)
            ->where('gejala_penyakit', 'like', "%{$riwayatBaru->gejala_penyakit}%")
            ->latest('tanggal')
            ->first();

        if ($riwayatSebelumnya) {
            $aturanR7 = AturanRekomendasi::where('parameter', 'riwayat_penyakit')
                ->where('aktif', true)
                ->first();

            $kandang = Kandang::find($riwayatBaru->kandang_id);
            $saran = $aturanR7 ? $aturanR7->saran_tindakan : 'Tinjau tindakan sebelumnya dan konsultasikan ke petugas kesehatan hewan.';

            Rekomendasi::create([
                'kandang_id' => $riwayatBaru->kandang_id,
                'aturan_rekomendasi_id' => $aturanR7?->id,
                'tanggal' => $riwayatBaru->tanggal,
                'nama_aturan' => "Riwayat Penyakit Berulang: {$riwayatBaru->gejala_penyakit}",
                'isi_rekomendasi' => "{$saran} Tindakan terdahulu: {$riwayatSebelumnya->tindakan}",
                'alasan_data' => "Kandang: {$kandang->nama_kandang} | Gejala '{$riwayatBaru->gejala_penyakit}' pernah tercatat pada {$riwayatSebelumnya->tanggal}.",
                'prioritas' => $aturanR7 ? $aturanR7->prioritas : 'Waspada',
                'status' => 'aktif',
            ]);
        }
    }

    private function cekKondisiNumerik(float $nilai, string $operator, ?float $ambang, ?float $ambangAtas = null): bool
    {
        if ($ambang === null) {
            return false;
        }

        return match ($operator) {
            '<=' => $nilai <= $ambang,
            '<'  => $nilai < $ambang,
            '>=' => $nilai >= $ambang,
            '>'  => $nilai > $ambang,
            '==' => $nilai == $ambang,
            'between' => $ambangAtas !== null && ($nilai >= $ambang && $nilai <= $ambangAtas),
            default => false,
        };
    }
}