<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wiadomość kontaktowa</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #111827;">

<h2>Nowa wiadomość z formularza kontaktowego</h2>

<p><strong>Imię i nazwisko:</strong> {{ $data['name'] }}</p>
<p><strong>E-mail:</strong> {{ $data['email'] }}</p>
@if(!empty($data['phone']))
    <p><strong>Telefon:</strong> {{ $data['phone'] }}</p>
@endif
@if(!empty($data['subject']))
    <p><strong>Temat:</strong> {{ $data['subject'] }}</p>
@endif

<p><strong>Treść wiadomości:</strong></p>
<p style="background-color: #f9fafb; padding: 12px; border-radius: 6px; border: 1px solid #e5e7eb;">{!! nl2br(e($data['message'])) !!}</p>

</body>
</html>
