@extends('layouts.auth')

@section('title', 'Restablecer contraseña')

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
            font-size: 38px;
            line-height: 1.03;
            letter-spacing: .006em;
            color: var(--cream);
        }

        .sub {
            position: relative;
            z-index: 2;
            margin: 0 0 18px;
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
            margin-top: 6px;
            margin-bottom: 14px;
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

        .msg-error {
            color: #ff6b6b;
            font-size: 12px;
            margin-top: 2px;
            margin-bottom: 4px;
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
            margin-top: 10px;
        }

        .btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.06);
            border-color: rgba(239, 231, 214, .22);
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
        }
    </style>
@endpush

@section('content')
    <form class="card" method="POST" action="{{ route('password.update') }}">
        @csrf
        
        <!-- Token oculto necesario para la verificación -->
        <input type="hidden" name="token" value="{{ current(request()->route()->parameters()) }}">

        <div class="brand">
            <b>AUTOMAI</b>
            GYM
        </div>

        <div>
            <h1 class="headline">Introduce tu nueva clave.</h1>
            <p class="sub">restablecer contraseña</p>

            <div class="field">
                <input class="input" id="correo_usuario" type="email" name="correo_usuario" value="{{ request()->email }}" readonly required />
                @error('correo_usuario') <span class="msg-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <input class="input" id="password" type="password" name="password" placeholder="Nueva contraseña" required autofocus />
                @error('password') <span class="msg-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <input class="input" id="password_confirmation" type="password" name="password_confirmation" placeholder="Repite la contraseña" required />
            </div>
        </div>

        <button class="btn" type="submit">Actualizar contraseña →</button>
    </form>
@endsection

@section('right-content')
    <div class="right">
        <p class="quote">Una nueva etapa.<span style="display:block;margin-top:40px;">Un nuevo comienzo.</span></p>
    </div>
@endsection
