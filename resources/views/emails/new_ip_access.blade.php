<!DOCTYPE html>
<html>
<head>
    <title>Nuevo inicio de sesión detectado</title>
</head>
<body>
    <p>Hola,</p>
    <p>Hemos detectado un nuevo inicio de sesión en tu cuenta desde una dirección IP diferente.</p>
    <p><strong>Detalles de la IP:</strong></p>
    <ul>
        <li><strong>Fecha y Hora:</strong> {{ $ipDetails['date'] }}</li>
        <li><strong>Dirección IP:</strong> {{ $ipDetails['ip'] }}</li>
        <li><strong>País:</strong> {{ $ipDetails['location']['country'] ?? 'Desconocido' }}</li>
        <li><strong>Ciudad:</strong> {{ $ipDetails['location']['city'] ?? 'Desconocido' }}</li>
        <li><strong>Código Postal:</strong> {{ $ipDetails['location']['zipcode'] ?? 'Desconocido' }}</li>
    </ul>
    <p>Si no reconoces esta actividad, por favor contacta con nuestro soporte.</p>
    <p>Saludos,<br>Equipo de Seguridad</p>
</body>
</html>