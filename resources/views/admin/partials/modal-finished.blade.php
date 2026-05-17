<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Disimpan</title>
</head>
<body>
    <p>{{ $message }}</p>
    <script>
        (function () {
            var payload = { type: 'church-admin-modal-finished', message: @json($message) };
            if (window.parent && window.parent !== window) {
                window.parent.postMessage(payload, window.location.origin);
            }
        })();
    </script>
</body>
</html>
