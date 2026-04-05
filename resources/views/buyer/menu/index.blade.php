<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <script>
        // Redirect langsung ke Komunitas
        window.location.href = "{{ route('buyer.communities.index') }}";
    </script>
</head>
<body>
    <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <p>Redirecting to Komunitas...</p>
    </div>
</body>
</html>