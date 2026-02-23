@extends('emails.layout', ['title' => 'Verifica tu Cuenta'])

@section('content')
    <h1>Bienvenido a AutomAI</h1>

    <p>Hola <strong>{{ $nombre }}</strong>,</p>

    <p>Gracias por unirte a nuestra comunidad. Estás a un solo paso de transformar tu entrenamiento con la ayuda de nuestra
        IA.</p>

    <p>Por favor, confirma tu dirección de correo electrónico para activar tu cuenta y acceder a todas las funcionalidades
        del gimnasio.</p>

    <div class="button-container">
        <a href="{{ $url }}" class="button">Verificar Cuenta</a>
    </div>

    <p style="font-size: 14px; color: rgba(239, 231, 214, 0.6);">
        Si no has creado esta cuenta, no es necesario que realices ninguna acción.
    </p>

    <div
        style="margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(239, 231, 214, 0.1); font-size: 12px; color: rgba(239, 231, 214, 0.5);">
        Si tienes problemas con el botón, copia y pega este enlace en tu navegador:<br>
        <a href="{{ $url }}" style="color: #466248; text-decoration: none;">{{ $url }}</a>
    </div>
@endsection
