@extends('layouts.auth')

@section('title', 'Login')

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
            font-size: 46px;
            line-height: 1.00;
            letter-spacing: .005em;
            color: var(--cream);
        }

        .sub {
            position: relative;
            z-index: 2;
            margin: 0 0 22px;
            font-size: 12px;
            letter-spacing: .10em;
            text-transform: uppercase;
            color: rgba(239, 231, 214, .60);
        }

        .field {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 4px;
            margin-bottom: 20px;
        }

        .input {
            width: 100%;
            height: 54px;
            border-radius: 14px;
            padding: 0 16px;
            border: 1px solid var(--stroke2);
            background: rgba(0, 0, 0, .16);
            color: rgba(239, 231, 214, .90);
            outline: none;
            font-family: var(--sans);
            font-size: 14px;
            transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .05);
        }

        .input::placeholder {
            color: rgba(239, 231, 214, .52);
        }

        .input:focus {
            border-color: rgba(239, 231, 214, .26);
            box-shadow: 0 0 0 4px rgba(190, 145, 85, .12), inset 0 1px 0 rgba(255, 255, 255, .06);
            transform: translateY(-1px);
        }

        .row {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 6px;
        }

        .check {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: rgba(239, 231, 214, .70);
            font-size: 13px;
            user-select: none;
        }

        .check input {
            width: 16px;
            height: 16px;
            accent-color: rgba(70, 98, 72, .92);
        }

        .link {
            color: rgba(239, 231, 214, .65);
            text-decoration: none;
            font-size: 13px;
            transition: transform .2s ease, color .2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .link:hover {
            color: rgba(239, 231, 214, .95);
            transform: translateX(2px);
        }

        .msg {
            position: relative;
            z-index: 2;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .14);
            padding: 12px 12px;
            color: rgba(239, 231, 214, .80);
            font-size: 13px;
            line-height: 1.35;
            display: none;
        }

        .msg.show {
            display: block;
        }

        .msg.ok {
            border-color: rgba(22, 250, 22, .18);
            background: radial-gradient(120% 160% at 30% 0%, rgba(22, 250, 22, 0.10), transparent 40%), rgba(0, 0, 0, .14);
        }

        .msg.err {
            border-color: rgba(255, 195, 32, 0.22);
            background: radial-gradient(120% 160% at 30% 0%, rgba(255, 195, 32, 0.10), transparent 40%), rgba(0, 0, 0, .14);
        }

        .btn {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 56px;
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
        }

        .btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.06);
            border-color: rgba(239, 231, 214, .22);
        }

        .btn:active {
            transform: translateY(0);
            filter: brightness(1.02);
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

        @media (max-width: 980px) {
            .right {
                max-width: 720px;
            }

            .card {
                min-height: auto;
            }
        }

        @media (max-width: 520px) {
            .headline {
                font-size: 40px;
            }

            .card {
                padding: 28px 20px 24px;
            }
        }
    </style>
@endpush

@section('content')
    <form class="card" id="formLogin" method="POST" action="{{ route('login') }}">
        @csrf
        <a href="{{ route('welcome') }}" class="brand" style="text-decoration: none;">
            <b>AUTOMAI</b>
            GYM
        </a>

        <div class="header-group">
            <h1 class="headline">Entrena<br>con intención.</h1>
            <p class="sub">Ritmo, rutina y foco. Sin ruido.</p>
        </div>

        <div class="field">
            <input class="input" id="correo_usuario" type="email" name="correo_usuario" placeholder="Correo" required />
            <input class="input" id="password" type="password" name="password" placeholder="Contraseña" required />
        </div>

        <div class="row">
            <label class="check">
                <input type="checkbox" name="remember" id="rememberMe" />
                Recordarme
            </label>

            <a class="link" href="{{ route('password.request') }}" id="linkOlvido">¿Olvidaste la contraseña?</a>
        </div>

        @if (session('status'))
            <div class="msg show ok" id="uiMsg">{{ session('status') }}</div>
        @elseif ($errors->any())
            <div class="msg show err" id="uiMsg">Los datos son incorrectos</div>
        @else
            <div class="msg" id="uiMsg"></div>
        @endif

        <div class="row" style="margin-top:10px">
            <a class="link" href="{{ route('register') }}" id="linkRegistro">¿No tienes cuenta? Regístrate</a>
            <a class="link" href="{{ route('welcome') }}">Volver al inicio</a>
        </div>

        <button class="btn" type="submit">Entrar →</button>
    </form>
@endsection

@section('right-content')
    <div class="right">
        <p class="quote">La disciplina es el puente entre tus metas y tus logros.<span
                style="display:block;margin-top:40px;white-space:nowrap;">Créalo, créelo y constrúyelo.</span>
        </p>
    </div>
@endsection
