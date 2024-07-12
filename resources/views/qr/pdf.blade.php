<!DOCTYPE html>
<html>
<head>
    <title>QR Code PDF</title>
</head>
<body>
<h1>QR Code</h1>
<p>Label: {{ $qrCode->label }}</p>
<img src="{{ Storage::url('public/qrcodes/' . $qrCode->id . '.svg') }}" alt="QR Code">
</body>
</html>
