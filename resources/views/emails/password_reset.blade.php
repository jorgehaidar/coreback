<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #4CAF50;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            font-size: 12px;
            color: #999;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Recuperación de Contraseña</h1>
    <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta.</p>
    <p>Introduzca el siguiente código para restablecer la contraseña:</p>
    <div class="code">{{ $code }}</div>
    {{-- <p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p> --}}
    {{-- <a href="{{ url('reset-password?token=' . $token . '&email=' . $email) }}">Restablecer Contraseña</a> --}}
    <p>Si no solicitaste un restablecimiento de contraseña, puedes ignorar este correo.</p>
    <div class="footer">
        &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
    </div>
</div>
</body>
</html>
