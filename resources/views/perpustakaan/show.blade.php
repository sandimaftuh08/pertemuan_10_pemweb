<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $buku['judul'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/perpustakaan">Perpustakaan</a></li>
                <li class="breadcrumb-item active">{{ $buku['judul'] }}</li>
            </ol>
        </nav>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">{{ $buku['judul'] }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">ID Buku</th>
                                <td>: {{ $buku['id'] }}</td>
                            </tr>
                            <tr>
                                <th>Judul</th>
                                <td>: {{ $buku['judul'] }}</td>
                            </tr>
                            <tr>
                                <th>Pengarang</th>
                                <td>: {{ $buku['pengarang'] }}</td>
                            </tr>
                            <tr>
                                <th>Penerbit</th>
                                <td>: {{ $buku['penerbit'] }}</td>
                            </tr>
                            <tr>
                                <th>Tahun Terbit</th>
                                <td>: {{ $buku['tahun'] }}</td>
                            </tr>
                            <tr>
                                <th>Harga</th>
                                <td>: Rp {{ number_format($buku['harga'], 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Stok</th>
                                <td>: 
                                    @if ($buku['stok'] > 0)
                                        <span class="badge bg-success">{{ $buku['stok'] }} tersedia</span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        
                        <hr>
                        
                        <h5>Deskripsi:</h5>
                        <p>{{ $buku['deskripsi'] }}</p>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h4 class="text-primary">Rp {{ number_format($buku['harga'], 0, ',', '.') }}</h4>
                                
                                @if ($buku['stok'] > 0)
                                    <button class="btn btn-success btn-lg w-100 mt-3">
                                        <i class="bi bi-cart-plus"></i> Pinjam Buku
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-lg w-100 mt-3" disabled>
                                        Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="/perpustakaan" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Buku
            </a>
        </div>
    </div>
</body>