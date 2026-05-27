<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Buku;
use App\Models\Anggota;

// ============================================================
// Route Utama
// ============================================================

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        $dbName = DB::connection()->getDatabaseName();
        return "Koneksi database berhasil!<br />Database: <strong>{$dbName}</strong>";
    } catch (\Exception $e) {
        return "Koneksi database gagal!<br />Error: " . $e->getMessage();
    }
});

Route::get('/tes', function () {
    return 'Laravel berjalan';
});

// ============================================================
// Helper: layout wrapper
// ============================================================

function layoutOpen(string $title): string
{
    return '<!DOCTYPE html><html lang="id"><head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>' . $title . '</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            body { background-color: #f0f2f5; }
            .navbar-brand { font-weight: 700; letter-spacing: 1px; }
            .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
            .card-header { border-radius: 12px 12px 0 0 !important; font-weight: 600; }
            .table thead th { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
            .btn { border-radius: 8px; font-size: 0.85rem; }
            h1, h2, h3 { font-weight: 700; }
        </style>
    </head><body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand"><i class="bi bi-book-half me-2"></i>Perpustakaan Laravel</a>
            <div class="d-flex gap-2">
                <a href="/buku" class="btn btn-light btn-sm"><i class="bi bi-journals me-1"></i>Buku</a>
                <a href="/anggota" class="btn btn-light btn-sm"><i class="bi bi-people me-1"></i>Anggota</a>
                <a href="/test-query" class="btn btn-light btn-sm"><i class="bi bi-search me-1"></i>Query</a>
                <a href="/test-accessor-scope" class="btn btn-warning btn-sm"><i class="bi bi-lightning me-1"></i>Tugas 2</a>
            </div>
        </div>
    </nav>
    <div class="container pb-5">';
}

function layoutClose(): string
{
    return '</div>
    <footer class="text-center text-muted py-4 mt-4" style="font-size:0.82rem;">
        &copy; ' . date('Y') . ' Perpustakaan Laravel &mdash; UIN K.H. Abdurrahman Wahid Pekalongan
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body></html>';
}

// ============================================================
// Route Buku
// ============================================================

Route::get('/buku', function () {
    $bukus = Buku::all();

    $html  = layoutOpen('Daftar Buku');
    $html .= '<div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><i class="bi bi-journals me-2 text-primary"></i>Daftar Buku</h1>
              </div>';
    $html .= '<div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-table me-1"></i> Total: ' . $bukus->count() . ' buku
                </div>
                <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
                </thead><tbody>';

    foreach ($bukus as $i => $buku) {
        $html .= '<tr>';
        $html .= '<td class="text-muted">' . ($i + 1) . '</td>';
        $html .= '<td><span class="badge bg-secondary">' . $buku->kode_buku . '</span></td>';
        $html .= '<td><strong>' . $buku->judul . '</strong></td>';
        $html .= '<td><span class="badge bg-info text-dark">' . $buku->kategori . '</span></td>';
        $html .= '<td class="text-success fw-semibold">' . $buku->harga_format . '</td>';
        $html .= '<td>' . ($buku->stok > 0
                    ? '<span class="badge bg-success">' . $buku->stok . '</span>'
                    : '<span class="badge bg-danger">Habis</span>') . '</td>';
        $html .= '<td><a href="/buku/' . $buku->id . '" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-eye"></i> Detail</a></td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table></div></div>';
    $html .= layoutClose();
    return $html;
});

Route::get('/buku/{id}', function ($id) {
    $buku = Buku::findOrFail($id);

    $html  = layoutOpen('Detail Buku');
    $html .= '<div class="mb-3"><a href="/buku" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali</a></div>';
    $html .= '<div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-book me-1"></i> Detail Buku
                </div>
                <div class="card-body">
                <table class="table table-bordered align-middle">';

    $rows = [
        ['ID', $buku->id],
        ['Kode Buku', '<span class="badge bg-secondary">' . $buku->kode_buku . '</span>'],
        ['Judul', '<strong>' . $buku->judul . '</strong>'],
        ['Kategori', '<span class="badge bg-info text-dark">' . $buku->kategori . '</span>'],
        ['Pengarang', $buku->pengarang],
        ['Penerbit', $buku->penerbit],
        ['Tahun Terbit', $buku->tahun_terbit],
        ['ISBN', $buku->isbn ?? '-'],
        ['Harga', '<span class="text-success fw-semibold">' . $buku->harga_format . '</span>'],
        ['Stok', $buku->stok],
        ['Tersedia?', $buku->tersedia ? '<span class="badge bg-success">Ya</span>' : '<span class="badge bg-danger">Tidak</span>'],
        ['Dibuat', $buku->created_at],
        ['Diperbarui', $buku->updated_at],
    ];

    foreach ($rows as [$field, $value]) {
        $html .= '<tr><th class="table-light" style="width:30%">' . $field . '</th><td>' . $value . '</td></tr>';
    }

    $html .= '</table></div></div>';
    $html .= layoutClose();
    return $html;
});

// ============================================================
// Route Anggota
// ============================================================

Route::get('/anggota', function () {
    $anggotas = Anggota::all();

    $html  = layoutOpen('Daftar Anggota');
    $html .= '<div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><i class="bi bi-people me-2 text-primary"></i>Daftar Anggota</h1>
              </div>';
    $html .= '<div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-table me-1"></i> Total: ' . $anggotas->count() . ' anggota
                </div>
                <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Umur</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
                </thead><tbody>';

    foreach ($anggotas as $i => $anggota) {
        $html .= '<tr>';
        $html .= '<td class="text-muted">' . ($i + 1) . '</td>';
        $html .= '<td><span class="badge bg-secondary">' . $anggota->kode_anggota . '</span></td>';
        $html .= '<td><strong>' . $anggota->nama . '</strong></td>';
        $html .= '<td class="text-muted">' . $anggota->email . '</td>';
        $html .= '<td>' . $anggota->umur . ' thn</td>';
        $html .= '<td>' . ($anggota->status === 'Aktif'
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-secondary">Nonaktif</span>') . '</td>';
        $html .= '<td><a href="/anggota/' . $anggota->id . '" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-eye"></i> Detail</a></td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table></div></div>';
    $html .= layoutClose();
    return $html;
});

Route::get('/anggota/{id}', function ($id) {
    $anggota = Anggota::findOrFail($id);

    $html  = layoutOpen('Detail Anggota');
    $html .= '<div class="mb-3"><a href="/anggota" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali</a></div>';
    $html .= '<div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-person me-1"></i> Detail Anggota
                </div>
                <div class="card-body">
                <table class="table table-bordered align-middle">';

    $rows = [
        ['Kode Anggota', '<span class="badge bg-secondary">' . $anggota->kode_anggota . '</span>'],
        ['Nama', '<strong>' . $anggota->nama . '</strong>'],
        ['Email', $anggota->email],
        ['Telepon', $anggota->telepon],
        ['Alamat', $anggota->alamat],
        ['Tanggal Lahir', $anggota->tanggal_lahir->format('d-m-Y')],
        ['Umur', $anggota->umur . ' tahun'],
        ['Jenis Kelamin', $anggota->jenis_kelamin],
        ['Pekerjaan', $anggota->pekerjaan ?? '-'],
        ['Tanggal Daftar', $anggota->tanggal_daftar->format('d-m-Y')],
        ['Lama Anggota', $anggota->lama_anggota . ' hari'],
        ['Status', $anggota->status === 'Aktif'
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-secondary">Nonaktif</span>'],
    ];

    foreach ($rows as [$field, $value]) {
        $html .= '<tr><th class="table-light" style="width:30%">' . $field . '</th><td>' . $value . '</td></tr>';
    }

    $html .= '</table></div></div>';
    $html .= layoutClose();
    return $html;
});

// ============================================================
// Route Testing Scope & Query
// ============================================================

Route::get('/test-query', function () {
    $html  = layoutOpen('Testing Query');
    $html .= '<h1 class="h3 mb-4"><i class="bi bi-search me-2 text-primary"></i>Testing Query Eloquent</h1>';

    // Buku Tersedia
    $tersedia = Buku::tersedia()->get();
    $html .= '<div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-check-circle me-1"></i> Buku Tersedia (Stok > 0) &mdash; ' . $tersedia->count() . ' buku
                </div>
                <ul class="list-group list-group-flush">';
    foreach ($tersedia as $buku) {
        $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">'
               . $buku->judul
               . '<span class="badge bg-success">' . $buku->stok . ' stok</span></li>';
    }
    $html .= '</ul></div>';

    // Buku Programming
    $programming = Buku::kategori('Programming')->get();
    $html .= '<div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-code-slash me-1"></i> Buku Programming &mdash; ' . $programming->count() . ' buku
                </div>
                <ul class="list-group list-group-flush">';
    foreach ($programming as $buku) {
        $html .= '<li class="list-group-item">' . $buku->judul . '</li>';
    }
    $html .= '</ul></div>';

    // Anggota Aktif
    $aktif = Anggota::aktif()->get();
    $html .= '<div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-people me-1"></i> Anggota Aktif &mdash; ' . $aktif->count() . ' anggota
                </div>
                <ul class="list-group list-group-flush">';
    foreach ($aktif as $anggota) {
        $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">'
               . $anggota->nama
               . '<span class="text-muted small">' . $anggota->email . '</span></li>';
    }
    $html .= '</ul></div>';

    $html .= layoutClose();
    return $html;
});

// ============================================================
// Route Testing Accessor & Scope — TUGAS 2
// ============================================================

Route::get('/test-accessor-scope', function () {

    $html  = layoutOpen('Testing Accessor & Scope');
    $html .= '<h1 class="h3 mb-4"><i class="bi bi-lightning-charge me-2 text-warning"></i>Testing Accessor &amp; Scope — Tugas 2</h1>';

    // 1. Semua Buku + badge
    $html .= '<div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <i class="bi bi-journals me-1"></i> 1. Semua Buku + Status Stok Badge & Tahun Label
                </div>
                <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                <tr>
                    <th>Kode</th><th>Judul</th><th>Tahun</th>
                    <th>Label</th><th>Stok</th><th>Status Stok</th>
                </tr>
                </thead><tbody>';

    foreach (Buku::all() as $buku) {
        $html .= '<tr>
            <td><span class="badge bg-secondary">' . $buku->kode_buku . '</span></td>
            <td><strong>' . $buku->judul . '</strong></td>
            <td>' . $buku->tahun_terbit . '</td>
            <td><span class="badge bg-primary">' . $buku->tahun_label . '</span></td>
            <td>' . $buku->stok . '</td>
            <td>' . $buku->status_stok_badge . '</td>
        </tr>';
    }
    $html .= '</tbody></table></div></div>';

    // 2. Buku Terbaru
    $bukuTerbaru = Buku::terbaru()->get();
    $html .= '<div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-stars me-1"></i> 2. Buku Terbaru (tahun &ge; 2024) — ' . $bukuTerbaru->count() . ' buku
                </div>
                <ul class="list-group list-group-flush">';
    foreach ($bukuTerbaru as $buku) {
        $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">'
               . '<strong>' . $buku->judul . '</strong>'
               . '<span class="badge bg-primary">' . $buku->tahun_terbit . '</span></li>';
    }
    $html .= '</ul></div>';

    // 3. Buku Stok Menipis
    $bukuMenipis = Buku::stokMenipis()->get();
    $html .= '<div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <i class="bi bi-exclamation-triangle me-1"></i> 3. Buku Stok Menipis (stok &lt; 5) — ' . $bukuMenipis->count() . ' buku
                </div>
                <ul class="list-group list-group-flush">';
    foreach ($bukuMenipis as $buku) {
        $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">'
               . $buku->judul
               . $buku->status_stok_badge . '</li>';
    }
    $html .= '</ul></div>';

    // 4. Harga Range
    $bukuRange = Buku::hargaRange(100000, 180000)->get();
    $html .= '<div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-cash-stack me-1"></i> 4. Buku Harga Rp 100.000–180.000 — ' . $bukuRange->count() . ' buku
                </div>
                <ul class="list-group list-group-flush">';
    foreach ($bukuRange as $buku) {
        $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">'
               . $buku->judul
               . '<span class="badge bg-success">' . $buku->harga_format . '</span></li>';
    }
    $html .= '</ul></div>';

    // 5. Semua Anggota + badge
    $html .= '<div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <i class="bi bi-people me-1"></i> 5. Semua Anggota + Status Badge & Kategori Usia
                </div>
                <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                <tr>
                    <th>Kode</th><th>Nama</th><th>Umur</th>
                    <th>Kategori Usia</th><th>Status</th>
                </tr>
                </thead><tbody>';

    foreach (Anggota::all() as $anggota) {
        $html .= '<tr>
            <td><span class="badge bg-secondary">' . $anggota->kode_anggota . '</span></td>
            <td><strong>' . $anggota->nama . '</strong></td>
            <td>' . $anggota->umur . ' thn</td>
            <td><span class="badge bg-info text-dark">' . $anggota->kategori_usia . '</span></td>
            <td>' . $anggota->status_badge . '</td>
        </tr>';
    }
    $html .= '</tbody></table></div></div>';

    // 6. Jenis Kelamin
    $lakiLaki  = Anggota::jenisKelamin('Laki-laki')->get();
    $perempuan = Anggota::jenisKelamin('Perempuan')->get();

    $html .= '<div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-gender-ambiguous me-1"></i> 6. Anggota per Jenis Kelamin
                </div>
                <div class="card-body">
                <div class="row">';

    $html .= '<div class="col-md-6">
                <h6 class="fw-bold"><i class="bi bi-person me-1"></i>Laki-laki (' . $lakiLaki->count() . ')</h6>
                <ul class="list-group">';
    foreach ($lakiLaki as $anggota) {
        $html .= '<li class="list-group-item">' . $anggota->nama . '</li>';
    }
    $html .= '</ul></div>';

    $html .= '<div class="col-md-6">
                <h6 class="fw-bold"><i class="bi bi-person me-1"></i>Perempuan (' . $perempuan->count() . ')</h6>
                <ul class="list-group">';
    foreach ($perempuan as $anggota) {
        $html .= '<li class="list-group-item">' . $anggota->nama . '</li>';
    }
    $html .= '</ul></div></div></div></div>';

    // 7. Terdaftar Bulan Ini
    $bulanIni = Anggota::terdaftarBulanIni()->get();
    $html .= '<div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-calendar-check me-1"></i> 7. Anggota Terdaftar Bulan Ini (' . now()->format('F Y') . ') — ' . $bulanIni->count() . ' anggota
                </div>';

    if ($bulanIni->count() > 0) {
        $html .= '<ul class="list-group list-group-flush">';
        foreach ($bulanIni as $anggota) {
            $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">'
                   . $anggota->nama
                   . '<span class="badge bg-info text-dark">' . $anggota->tanggal_daftar->format('d-m-Y') . '</span></li>';
        }
        $html .= '</ul>';
    } else {
        $html .= '<div class="card-body">
                    <div class="alert alert-info mb-0">Tidak ada anggota yang terdaftar bulan ini.</div>
                  </div>';
    }

    $html .= '</div>';
    $html .= layoutClose();
    return $html;
});