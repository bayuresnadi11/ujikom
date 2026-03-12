<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Struk</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; }
        .error-container { border: 1px solid #f5c6cb; background-color: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px; display: inline-block; }
        h3 { margin-top: 0; }
        .btn { display: inline-block; margin-top: 15px; padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="error-container">
        <h3>Terjadi Kesalahan</h3>
        <p>{{ $message ?? 'Gagal memuat struk.' }}</p>
        <a href="javascript:window.close()" class="btn">Tutup</a>
    </div>
</body>
</html>
