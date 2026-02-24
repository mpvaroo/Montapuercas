@extends('emails.layout', ['title' => 'Recuperar Contraseña'])

@section('content')
    <h1>Recuperar Contraseña</h1>

    <p>Hola <strong>{{ $nombre }}</strong>,</p>

    <p>Has recibido este correo porque hemos recibido una solicitud de restablecimiento de contraseña para tu cuenta en AutomAI Gym.</p>

    <div class="button-container">
        <a href="{{ $url }}" class="button">Restablecer Contraseña</a>
    </div>

    <p style="font-size: 14px; color: rgba(239, 231, 214, 0.6);">
        Este enlace para restablecer la contraseña caducará en 60 minutos.
    </p>

    <p style="font-size: 14px; color: rgba(239, 231, 214, 0.6);">
        Si no solicitaste restablecer tu contraseña, no es necesario que realices ninguna acción.
    </p>

    <div
        style="margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(239, 231, 214, 0.1); font-size: 12px; color: rgba(239, 231, 214, 0.5);">
        Si tienes problemas con el botón, copia y pega este enlace en tu navegador:<br>
        <a href="{{ $url }}" style="color: #466248; text-decoration: none;">{{ $url }}</a>
    </div>
@endsection
