@extends('emails.layout', ['title' => 'Reserva Confirmada'])

@section('content')
    <h1>¡Reserva Confirmada!</h1>

    <p>Hola <strong>{{ $reserva->user->nombre_mostrado_usuario }}</strong>,</p>

    <p>Tu plaza para la clase de <strong>{{ $reserva->clase->titulo_clase }}</strong> ya está asegurada. Estamos listos para
        darlo todo en la sesión.</p>

    <div class="panel">
        <p style="margin-top: 0;"><strong>Detalles de la sesión:</strong></p>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="margin-bottom: 8px;">📅 <strong>Fecha:</strong>
                {{ $reserva->clase->fecha_inicio_clase->translatedFormat('l, d \d\e F') }}</li>
            <li style="margin-bottom: 8px;">⏰ <strong>Hora:</strong> {{ $reserva->clase->fecha_inicio_clase->format('H:i') }}
            </li>
            <li style="margin-bottom: 0;">💪 <strong>Instructor:</strong> {{ $reserva->clase->instructor_clase }}</li>
        </ul>
    </div>

    <p>Te recomendamos llegar unos minutos antes para calentar y preparar el material necesario.</p>

    <div class="button-container">
        <a href="{{ route('detalle-reserva', $reserva->clase->id_clase_gimnasio) }}" class="button">Ver Detalles</a>
    </div>

    <p style="font-size: 14px; text-align: center; color: rgba(239, 231, 214, 0.6);">
        Si no puedes asistir, recuerda cancelar la reserva desde tu panel para dejar el hueco a otro compañero.
    </p>

    <p style="margin-top: 40px; text-align: center;">
        ¡Nos vemos en el entrenamiento!<br>
        <strong>El equipo de AutomAI</strong>
    </p>
@endsection
