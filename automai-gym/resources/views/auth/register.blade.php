@extends('layouts.auth')

@section('title', 'Registro')

@push('styles')
    <style>
        .wrap {
            grid-template-columns: 520px 1fr;
            gap: clamp(60px, 10vw, 140px);
        }

        .card {
            min-height: 660px;
        }

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
            margin: 18px 0 10px;
            font-family: var(--serif);
            font-weight: 500;
            font-size: 44px;
            line-height: 1.02;
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

        .grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 14px;
        }

        .grid.one {
            grid-template-columns: 1fr;
        }

        .input,
        .select {
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

        .input:focus,
        .select:focus {
            border-color: rgba(239, 231, 214, .26);
            box-shadow: 0 0 0 4px rgba(190, 145, 85, .12), inset 0 1px 0 rgba(255, 255, 255, .06);
            transform: translateY(-1px);
        }

        .select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 46px;
            background-image: linear-gradient(45deg, transparent 50%, rgba(239, 231, 214, .65) 50%), linear-gradient(135deg, rgba(239, 231, 214, .65) 50%, transparent 50%);
            background-position: calc(100% - 22px) 50%, calc(100% - 16px) 50%;
            background-size: 6px 6px, 6px 6px;
            background-repeat: no-repeat;
        }

        .line {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin: 4px 0 0;
        }

        .link {
            color: rgba(239, 231, 214, .65);
            text-decoration: none;
            font-size: 13px;
            transition: transform .2s ease, color .2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .link:hover {
            color: rgba(239, 231, 214, .95);
            transform: translateX(2px);
        }

        .note {
            color: rgba(239, 231, 214, .55);
            font-size: 12px;
            line-height: 1.35;
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
            margin-top: 10px;
        }

        .msg.show {
            display: block;
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

        @media (max-width: 1100px) {
            .wrap {
                grid-template-columns: 1fr;
                gap: 26px;
                padding: 44px 0;
            }

            .right {
                max-width: 720px;
            }

            .card {
                min-height: auto;
            }
        }

        @media (max-width: 560px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .headline {
                font-size: 38px;
            }

            .card {
                padding: 28px 20px 24px;
            }
        }
    </style>
@endpush

@section('content')
    <form class="card" id="formRegistro" autocomplete="on" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="brand">
            <b>AUTOMAI</b>
            GYM
        </div>

        <div>
            <h1 class="headline">Crea tu cuenta.</h1>
            <p class="sub">usuario · perfil · ajustes</p>

            <div class="grid one">
                <input class="input" id="correo_usuario" type="email" name="correo_usuario" placeholder="Correo" required
                    old="{{ old('correo_usuario') }}" />
            </div>

            <div class="grid">
                <input class="input" id="nombre_mostrado_usuario" type="text" name="nombre_mostrado_usuario"
                    placeholder="Nombre mostrado" required
                    old="{{ old('nombre_mostrado_usuario') }}" />
                <input class="input" id="nombre_real_usuario" type="text" name="nombre_real_usuario"
                    placeholder="Nombre real" required
                    old="{{ old('nombre_real_usuario') }}" />
            </div>

            <div class="grid">
                <input class="input" id="contrasena_usuario" type="password" name="password" placeholder="Contraseña"
                    required
                    old="{{ old('password') }}" />
                <input class="input" id="contrasena_repetir" type="password" name="password_confirmation"
                    placeholder="Repetir contraseña" required
                    old="{{ old('password_confirmation') }}" />
            </div>

            <div class="grid">
                <select class="select" id="objetivo_principal_usuario" name="objetivo_principal_usuario" required>
                    <option value="salud">Objetivo (salud)</option>
                    <option value="definir">Objetivo (definir)</option>
                    <option value="volumen">Objetivo (volumen)</option>
                    <option value="rendimiento">Objetivo (rendimiento)</option>
                </select>

                <select class="select" id="nivel_usuario" name="nivel_usuario" required>
                    <option value="principiante">Nivel (principiante)</option>
                    <option value="intermedio">Nivel (intermedio)</option>
                    <option value="avanzado">Nivel (avanzado)</option>
                </select>
            </div>

            <div class="grid">
                <select class="select" id="dias_entrenamiento_semana_usuario" name="dias_entrenamiento_semana_usuario"
                    required>
                    <option value="3">Días/semana (3)</option>
                    <option value="4">Días/semana (4)</option>
                    <option value="5">Días/semana (5)</option>
                    <option value="6">Días/semana (6)</option>
                </select>

                <input class="input" id="telefono_usuario" type="tel" name="telefono_usuario" placeholder="Teléfono" />
            </div>

            <div class="grid">
                <input class="input" id="peso_kg_usuario" type="number" step="0.01" min="0" name="peso_kg_usuario"
                    placeholder="Peso kg" />
                <input class="input" id="altura_cm_usuario" type="number" step="1" min="0" name="altura_cm_usuario"
                    placeholder="Altura cm" />
            </div>

            <div class="grid">
                <select class="select" id="tono_ia_coach" name="tono_ia_coach" required>
                    <option value="directo">Tono IA (directo)</option>
                    <option value="motivador">Tono IA (motivador)</option>
                </select>

                <select class="select" id="idioma_preferido" name="idioma_preferido" required>
                    <option value="es">Idioma (es)</option>
                    <option value="en">Idioma (en)</option>
                </select>
            </div>

            <div class="line">
                <a class="link" href="{{ route('login') }}">← Volver a Login</a>
                <span class="note">Se crea como rol: usuario</span>
            </div>

            @if ($errors->any())
                <div class="msg show err" id="uiMsg">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @else
                <div class="msg" id="uiMsg"></div>
            @endif
        </div>

        <button class="btn" type="submit">Crear cuenta →</button>
    </form>
@endsection

@section('right-content')
    <div class="right">
        <p class="quote">No es motivación. Es sistema.<span style="display:block;margin-top:40px;">Paso a paso, cada
                semana.</span></p>
    </div>
@endsection