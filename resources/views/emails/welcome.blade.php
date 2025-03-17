<!DOCTYPE html>
<html>
<head>
    <title>¡Bienvenido!</title>
</head>
<body>
    <h1>¡Bienvenido, {{ $user->name }}!</h1>
    <p>Gracias por unirte a {{ config('app.name') }}. Estamos encantados de tenerte como parte de nuestra comunidad.</p>
    <p>Si necesitas ayuda o tienes preguntas, no dudes en contactarnos.</p>
    <p>Saludos,<br>El equipo de {{ config('app.name') }}</p>
</body>
</html>
