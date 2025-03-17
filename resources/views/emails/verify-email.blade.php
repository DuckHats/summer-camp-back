<!DOCTYPE html>
<html>
<head>
    <title>Verificación de correo electrónico</title>
</head>
<body>
    <h1>Hola,</h1>
    <p>Gracias por registrarte en nuestra aplicación. Use el siguiente codigo para verificar el correo electrónico:</p>
    <p> {{ $verificationCode }} </p>
    <p>Si no solicitaste esta verificación, puedes ignorar este mensaje.</p>
    <p>Saludos,<br>El equipo de {{ config('app.name') }}</p>
</body>
</html>
