<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Contact mail</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #111827;">

<h1>Nieuw contactbericht</h1>

<p><strong>Naam:</strong> {{ $data['name'] }}</p>
<p><strong>E-mail:</strong> {{ $data['email'] }}</p>

<p><strong>Bericht:</strong></p>
<p>{!! nl2br(e($data['message'])) !!}</p>

</body>
</html>
