<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $nama_sistem }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>{{ $nama_sistem }}</h1>
        <p class="lead">Selamat datang di sistem perpustakaan berbasis Laravel {{ $versi }}</p>
        
        <div class="alert alert-info">
            <strong>Info:</strong> Total buku yang tersedia: {{ $total_buku }}
        </div>
        
        <h3>Daftar Buku</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($buku_list as $index => $buku)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $buku['judul'] }}</td>
                    <td>{{ $buku['pengarang'] }}</td>
                    <td>Rp {{ number_format($buku['harga'], 0, ',', '.') }}</td>
                    <td>
                        @if ($buku['stok'] > 0)
                            <span class="badge bg-success">{{ $buku['stok'] }}</span>
                        @else
                            <span class="badge bg-danger">Habis</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>