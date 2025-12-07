<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Permintaan Ganti Kata Sandi</title>
</head>

<body>
    <h2>Halo, </h2>
    <p>{{ $details['nama'] . ' ' . $details['detail'] }} .</p>

    <p><strong>Detail User:</strong></p>
    <ul>
        <li><strong>Email:</strong> {{ $details['email'] }}</li>
        <li><strong>No Telepon:</strong> {{ $details['no_telepon'] }}</li>
        <li><strong>Pesan:</strong> {{ $details['pesan'] }}</li>
    </ul>

    <p>Terima kasih!</p>
</body>

</html>
