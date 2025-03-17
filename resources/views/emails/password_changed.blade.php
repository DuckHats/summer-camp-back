<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contraseña Modificada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #ffffff;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #333333;
        }
        .content {
            text-align: center;
        }
        .content p {
            color: #666666;
            line-height: 1.5;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #dddddd;
            margin-top: 20px;
        }
        .footer p {
            color: #999999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Contraseña Modificada</h1>
        </div>
        <div class="content">
            <p>Hola,</p>
            <p>Te informamos que la contraseña de tu cuenta ha sido modificada exitosamente.</p>
            <p>Si no realizaste este cambio, por favor contacta con nuestro soporte inmediatamente.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Tu Empresa. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>