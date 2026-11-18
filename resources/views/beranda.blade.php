<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiKandang - Ayam Segar dan Berkualitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f5f7; font-family: 'Segoe UI', sans-serif; }
        .brand-red { color: #d92525; }
        .btn-red { background-color: #d92525; color: #fff; border: none; }
        .btn-red:hover { background-color: #b71c1c; color: #fff; }
        .btn-orange { background-color: #f57c00; color: #fff; border: none; }
        .btn-orange:hover { background-color: #e65100; color: #fff; }
        .hero-section {
            background: linear-gradient(rgba(255,255,255,0.82), rgba(255,255,255,0.82)),
                        url('https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?auto=format&fit=crop&w=1400&q=80') center/cover;
            padding: 90px 0;
        }
        .step-circle {
            width: 38px; height: 38px; background-color: #d92525; color: #fff;
            border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
            font-weight: bold; margin-bottom: 12px;
        }
        .qty-btn {
            width: 34px; height: 34px; border-radius: 50%; background-color: #d92525;
            color: #fff; border: none; font-weight: bold;
        }
        .qty-btn:hover { background-color: #b71c1c; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 brand-red" href="{{ route('beranda') }}">SIKANDANG</a>
            <div class="ms-auto d-flex align-items-center gap-4">
                <a href="#beranda" class="text-decoration-none text-dark fw-semibold">Beranda</a>
                <a href="#stok-produk" class="text-decoration-none text-dark fw-semibold">Stok Produk</a>
                <a href="#form-pesan" class="btn btn-red rounded-pill px-4 fw-semibold">Pesan Sekarang</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-dark btn-sm">Dasbor Pemilik</a>
                @else
                    <a href="{{ route('login') }}" class="text-muted small text-decoration-none">Login Pemilik</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Notifikasi Sukses / Error -->
    <div class="container mt-3">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('pesanan_sukses'))
            @php
                $order = session('pesanan_sukses');
                $nomorPesanan = data_get($order, 'nomor_pesanan');
                $namaPembeli = data_get($order, 'pembeli.nama');
                $totalHarga = data_get($order, 'total_harga', 0);
            @endphp
            <div class="alert alert-success shadow-sm p-4">
                <h5 class="fw-bold"><i class="bi bi-check-circle-fill me-2"></i>Pesanan Berhasil Dikirim!</h5>
                <p class="mb-2">Nomor Pesanan Anda: <strong>{{ $nomorPesanan }}</strong> atas nama <strong>{{ $namaPembeli }}</strong> (Total: <strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong>).</p>
                <p class="mb-3 small text-muted">Pemilik peternakan akan segera menghubungi nomor WhatsApp Anda untuk konfirmasi pesanan. Anda juga dapat menghubungi pemilik langsung melalui tombol di bawah:</p>
                <a href="https://wa.me/6281234567890?text={{ urlencode('Halo SiKandang, saya baru saja membuat pesanan dengan nomor ' . $nomorPesanan . ' atas nama ' . $namaPembeli) }}"
                   target="_blank" class="btn btn-success">
                    <i class="bi bi-whatsapp me-1"></i> Hubungi Contact Person (WhatsApp)
                </a>
            </div>
        @endif
    </div>

    <!-- Hero Banner -->
    <section id="beranda" class="hero-section">
        <div class="container">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold text-dark">Ayam Segar<br>dan Berkualitas</h1>
                <p class="fs-5 fw-semibold brand-red mt-2">Kualitas Adalah Prioritas Utama Kami</p>
                <a href="#stok-produk" class="btn btn-red btn-lg mt-3 px-4">Lihat Stok Hari Ini</a>
            </div>
        </div>
    </section>

    <!-- Cara Pesan (4 Langkah) -->
    <section class="py-5 bg-white">
        <div class="container">
            <h3 class="text-center fw-bold mb-4">Cara Pesan</h3>
            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="card h-100 border shadow-sm p-4">
                        <div><span class="step-circle">1</span></div>
                        <h6 class="fw-bold mb-0">Lihat stok produk</h6>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border shadow-sm p-4">
                        <div><span class="step-circle">2</span></div>
                        <h6 class="fw-bold mb-0">Pilih jumlah</h6>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border shadow-sm p-4">
                        <div><span class="step-circle">3</span></div>
                        <h6 class="fw-bold mb-0">Isi data pesanan</h6>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border shadow-sm p-4">
                        <div><span class="step-circle">4</span></div>
                        <h6 class="fw-bold mb-0">Pesananmu siap diantar</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Form Utama Pemesanan -->
    <form action="{{ route('pembeli.pesan') }}" method="POST">
        @csrf

        <!-- Stok Produk Hari Ini -->
        <section id="stok-produk" class="py-5">
            <div class="container">
                <h3 class="text-center fw-bold mb-4">Stok Produk Hari Ini</h3>
                <div class="row g-4 justify-content-center">
                    @forelse($produks as $produk)
                        <div class="col-md-5">
                            <div class="card border-0 shadow-sm p-4 h-100">
                                <div class="row align-items-center">
                                    <div class="col-4 text-center">
                                        <div class="bg-light rounded p-3">
                                            <i class="bi bi-box-seam fs-1 text-secondary"></i>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <h5 class="fw-bold mb-1">{{ $produk->nama_produk }}</h5>
                                        <p class="text-muted small mb-1">Stok tersedia: <strong>{{ $produk->stok }} {{ $produk->satuan }}</strong></p>
                                        <p class="fw-bold brand-red fs-5 mb-3">Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }} / {{ $produk->satuan }}</p>

                                        @if($produk->stok > 0)
                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <button type="button" class="qty-btn" onclick="ubahQty({{ $produk->id }}, -1)">-</button>
                                                <input type="number"
                                                       name="items[{{ $produk->id }}]"
                                                       id="qty-{{ $produk->id }}"
                                                       value="0"
                                                       min="0"
                                                       max="{{ $produk->stok }}"
                                                       data-nama="{{ $produk->nama_produk }}"
                                                       data-harga="{{ $produk->harga_satuan }}"
                                                       class="form-control text-center fw-bold qty-input"
                                                       style="width: 70px;"
                                                       onchange="hitungTotal()">
                                                <button type="button" class="qty-btn" onclick="ubahQty({{ $produk->id }}, 1)">+</button>
                                            </div>
                                            <a href="#form-pesan" onclick="tambahSatu({{ $produk->id }})" class="btn btn-orange rounded-pill px-4 btn-sm fw-semibold">Pesan</a>
                                        @else
                                            <span class="badge bg-secondary">Stok Habis</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted">Belum ada produk tersedia hari ini.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Isi Data Pesanan Anda -->
        <section id="form-pesan" class="pb-5">
            <div class="container">
                <h3 class="fw-bold mb-4">Isi Data Pesanan Anda</h3>
                <div class="row g-4">
                    <!-- Kolom Kiri: Ringkasan Pesanan -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-4 h-100">
                            <h5 class="fw-bold border-bottom pb-3 mb-3">Ringkasan Pesanan</h5>
                            <div id="daftar-ringkasan" class="mb-3">
                                <p class="text-muted small">Belum ada produk dipilih. Gunakan tombol (+) pada katalog di atas.</p>
                            </div>
                            <div class="d-flex justify-content-between border-top border-bottom py-3 fw-bold fs-5">
                                <span>TOTAL</span>
                                <span id="total-harga" class="brand-red">Rp 0</span>
                            </div>

                            <div class="mt-4">
                                <label class="form-label fw-bold">Pilih Metode Pembayaran</label>
                                <select name="metode_pembayaran" class="form-select" required>
                                    <option value="COD / Bayar di Tempat">COD / Bayar di Tempat</option>
                                    <option value="Transfer Bank (Konfirmasi WA)">Transfer Bank (Konfirmasi WA)</option>
                                    <option value="Ambil Langsung di Kandang">Ambil Langsung di Kandang</option>
                                </select>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <p class="small text-muted mb-2">Butuh bantuan atau bertanya stok sebelum pesan?</p>
                                <a href="https://wa.me/6281234567890?text=Halo%20SiKandang,%20saya%20ingin%20bertanya%20mengenai%20produk%20ayam." target="_blank" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-whatsapp me-1"></i> Chat Contact Person (WhatsApp)
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Data Pemesanan -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-4 h-100">
                            <h5 class="fw-bold border-bottom pb-3 mb-3">Data Pemesanan</h5>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Pemesan</label>
                                <input type="text" name="nama" class="form-control" placeholder="Silakan masukkan nama Anda" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">No WhatsApp</label>
                                <input type="text" name="no_wa" class="form-control" placeholder="Contoh: 081234567890" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Alamat Pengiriman</label>
                                <textarea name="alamat" rows="3" class="form-control" placeholder="Masukkan alamat lengkap pengiriman Anda" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-red btn-lg w-100 rounded-pill fw-bold">
                                Kirim Pesanan Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>

    <!-- Footer Merah -->
    <footer class="py-3 text-white text-center" style="background-color: #d92525;">
        <div class="container">
            <small>&copy; 2026 SiKandang - Sistem Informasi Manajemen & Rekomendasi Peternakan Ayam</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function ubahQty(id, delta) {
            const input = document.getElementById('qty-' + id);
            const max = parseInt(input.getAttribute('max'));
            let val = parseInt(input.value || 0) + delta;
            if (val < 0) val = 0;
            if (val > max) val = max;
            input.value = val;
            hitungTotal();
        }

        function tambahSatu(id) {
            const input = document.getElementById('qty-' + id);
            if (parseInt(input.value || 0) === 0) {
                ubahQty(id, 1);
            }
        }

        function hitungTotal() {
            const inputs = document.querySelectorAll('.qty-input');
            const ringkasanEl = document.getElementById('daftar-ringkasan');
            let html = '';
            let total = 0;

            inputs.forEach(input => {
                const qty = parseInt(input.value || 0);
                if (qty > 0) {
                    const nama = input.dataset.nama;
                    const harga = parseFloat(input.dataset.harga);
                    const subtotal = qty * harga;
                    total += subtotal;
                    html += `<div class="d-flex justify-content-between mb-2">
                                <span>${nama} (${qty}x)</span>
                                <span class="fw-semibold">Rp ${subtotal.toLocaleString('id-ID')}</span>
                             </div>`;
                }
            });

            ringkasanEl.innerHTML = html || '<p class="text-muted small">Belum ada produk dipilih. Gunakan tombol (+) pada katalog di atas.</p>';
            document.getElementById('total-harga').innerText = 'Rp ' + total.toLocaleString('id-ID');
        }
    </script>
</body>
</html>