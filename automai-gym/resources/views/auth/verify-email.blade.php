@extends('layouts.auth')

@section('title', 'Verificar Correo')

@push('styles')
    <style>
        .brand {
            position: relative;
            z-index: 2;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: .30em;
            font-weight: 700;
            font-size: 11px;
            color: var(--cream-3);
        }

        .brand b {
            display: block;
            font-size: 17px;
            letter-spacing: .34em;
            color: rgba(239, 231, 214, .90);
            margin-bottom: 2px;
        }

        .headline {
            position: relative;
            z-index: 2;
            margin: 22px 0 10px;
            font-family: var(--serif);
            font-weight: 500;
            font-size: 34px;
            line-height: 1.1;
            letter-spacing: .005em;
            color: var(--cream);
            text-align: center;
        }

        .sub {
            position: relative;
            z-index: 2;
            margin: 0 0 22px;
            font-size: 13px;
            line-height: 1.5;
            letter-spacing: .02em;
            color: rgba(239, 231, 214, .60);
            text-align: center;
        }

        .btn {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 52px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .16);
            cursor: pointer;
            background:
                radial-gradient(120% 160% at 30% 0%, rgba(22, 250, 22, 0.22), transparent 35%),
                linear-gradient(180deg, var(--greenBtn1), var(--greenBtn2));
            color: rgba(239, 231, 214, .95);
            font-family: var(--sans);
            font-weight: 750;
            letter-spacing: .06em;
            box-shadow: var(--shadow-lg);
            transition: transform .18s ease, filter .18s ease, border-color .18s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            margin-bottom: 12px;
        }

        .btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.06);
            border-color: rgba(239, 231, 214, .22);
        }

        .btn.secondary {
            background: rgba(255, 255, 255, .05);
            border-color: rgba(239, 231, 214, .12);
            height: 44px;
            font-size: 12px;
        }

        .msg {
            position: relative;
            z-index: 2;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .14);
            padding: 12px 14px;
            color: rgba(239, 231, 214, .80);
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 20px;
            text-align: center;
        }

        .msg.ok {
            border-color: rgba(22, 250, 22, .18);
            background: radial-gradient(120% 160% at 30% 0%, rgba(22, 250, 22, 0.10), transparent 40%), rgba(0, 0, 0, .14);
        }

        .right {
            align-self: start;
            padding-left: 5px;
            max-width: 1500px;
        }

        .quote {
            margin: 0;
            font-family: var(--serif);
            font-weight: 500;
            font-size: clamp(30px, 3.2vw, 46px);
            line-height: 1.05;
            letter-spacing: .012em;
            color: rgba(239, 231, 214, .86);
            text-shadow: 0 12px 40px rgba(0, 0, 0, .62);
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="brand">
            <b>AUTOMAI</b>
            GYM
        </div>

        <h1 class="headline">Verifica tu cuenta</h1>

        <p class="sub">
            ¡Gracias por unirte! Antes de empezar, necesitamos que verifiques tu dirección de correo electrónico haciendo
            clic en el enlace que te acabamos de enviar. Si no lo recibiste, con gusto te enviaremos otro.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="msg ok">
                Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionaste
                durante el registro.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn">
                Reenviar correo de verificación
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn secondary">
                Cerrar Sesión
            </button>
        </form>
    </div>
@endsection

@section('right-content')
    <div class="right">
        <p class="quote">Cuidar de tu cuerpo es el mejor proyecto en el que trabajarás.<span
                style="display:block;margin-top:40px;white-space:nowrap;">La constancia es la clave del progreso.</span>
        </p>
    </div>
@endsection
