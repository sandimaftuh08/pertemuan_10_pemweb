<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($nama_sistem); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1><?php echo e($nama_sistem); ?></h1>
        <p class="lead">Selamat datang di sistem perpustakaan berbasis Laravel <?php echo e($versi); ?></p>
        
        <div class="alert alert-info">
            <strong>Info:</strong> Total buku yang tersedia: <?php echo e($total_buku); ?>

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
                <?php $__currentLoopData = $buku_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $buku): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($buku['judul']); ?></td>
                    <td><?php echo e($buku['pengarang']); ?></td>
                    <td>Rp <?php echo e(number_format($buku['harga'], 0, ',', '.')); ?></td>
                    <td>
                        <?php if($buku['stok'] > 0): ?>
                            <span class="badge bg-success"><?php echo e($buku['stok']); ?></span>
                        <?php else: ?>
                            <span class="badge bg-danger">Habis</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\perpustakaan\resources\views/perpustakaan/index.blade.php ENDPATH**/ ?>