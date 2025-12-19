<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Accesos Udec</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div
        style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="background-color: #1e293b; padding: 20px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0;">Credenciales de Acceso</h1>
        </div>
        <div style="padding: 30px;">
            <p>Hola <strong>{{ $nombre }}</strong>,</p>
            <p>Se ha restablecido tu acceso a la plataforma. Aquí tienes tus nuevas credenciales:</p>

            <div
                style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p style="margin: 5px 0;"><strong>Usuario:</strong> {{ $email }}</p>
                <p style="margin: 5px 0;"><strong>Contraseña:</strong> <span
                        style="font-family: monospace; background-color: #e2e8f0; padding: 2px 5px; border-radius: 4px;">{{ $password }}</span>
                </p>
            </div>

            <p>Te recomendamos cambiar tu contraseña una vez que hayas ingresado.</p>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('participant.login') }}"
                    style="display: inline-block; background-color: #3b82f6; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 5px; font-weight: bold;">Ir
                    a la Plataforma</a>
            </div>
        </div>
        <div style="background-color: #f1f5f9; padding: 15px; text-align: center; font-size: 12px; color: #64748b;">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>

</html>