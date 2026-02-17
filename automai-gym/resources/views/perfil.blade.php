@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
    <div class="main">
        <div>
            <header class="hero">
                <h1>Perfil.</h1>
                <p>Datos reales, objetivos y base de entrenamiento.</p>
            </header>

            <div class="section-title">
                <span class="dot" aria-hidden="true"></span>
                <span>Cuenta (usuarios)</span>
            </div>

            @if ($errors->any())
                <div
                    style="margin: 0 6px 14px; padding: 12px; background: rgba(255, 0, 0, 0.1); border: 1px solid rgba(255, 0, 0, 0.2); border-radius: var(--r); color: #ff4444; font-size: 13px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="panel" aria-label="Cuenta de usuario">
                <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="profile-row">
                        <div class="avatar-lg" style="cursor: pointer; position: relative;"
                            onclick="document.getElementById('avatarInput').click()">
                            <img src="{{ Auth::user()->avatar_url }}" alt="Foto de perfil" id="avatarPreview">
                            <div
                                style="position: absolute; bottom: 0; right: 0; background: gold; color: #000; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                <i class="fas fa-camera"></i>
                            </div>
                        </div>
                        <input type="file" name="foto_perfil" id="avatarInput" style="display: none;"
                            onchange="previewImage(this)">
                        <div style="min-width:0;">
                            <button type="button" class="btn-small"
                                style="margin-bottom: 8px; font-size: 12px; padding: 4px 12px; background: rgba(239,231,214,.08); color: var(--gold); border: 1px solid rgba(190,145,85,.3);"
                                onclick="document.getElementById('avatarInput').click()">
                                <i class="fas fa-upload" style="margin-right: 5px;"></i> Cambiar foto
                            </button>
                            <div style="font-family:var(--serif); font-size:22px; color:rgba(239,231,214,.92); line-height:1.1;"
                                id="nombreMostrado">
                                {{ Auth::user()->nombre_mostrado_usuario ?? 'Usuario' }}
                            </div>
                            <div class="meta-line">
                                <span id="correoUsuario">{{ Auth::user()->correo_usuario ?? 'email@example.com' }}</span>
                                · Estado: <span id="estadoUsuario">activo</span>
                            </div>
                            @error('foto_perfil')
                                <div style="color: #ff4444; font-size: 11px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <div class="label">Correo</div>
                            <input class="input" type="email" id="correoInput"
                                value="{{ Auth::user()->correo_usuario ?? '' }}" readonly />
                        </div>
                        <div class="field">
                            <div class="label">Nombre mostrado</div>
                            <input class="input" type="text" id="nombreMostradoInput"
                                value="{{ Auth::user()->nombre_mostrado_usuario ?? '' }}" readonly />
                        </div>
                    </div>

                    <div style="height:14px;"></div>

                    <div class="section-title" style="margin:4px 0 10px; padding:0;">
                        <span class="dot" aria-hidden="true"></span>
                        <span>Perfil</span>
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <div class="label">Nombre real</div>
                            <input class="input" id="nombreReal" type="text" placeholder="Nombre y apellidos"
                                value="{{ Auth::user()->perfil?->nombre_real_usuario ?? '' }}" readonly />
                        </div>
                        <div class="field">
                            <div class="label">Teléfono</div>
                            <input class="input @error('telefono_usuario') error-border @enderror" id="telefono"
                                name="telefono_usuario" type="tel" placeholder="+34 600 000 000"
                                value="{{ old('telefono_usuario', Auth::user()->perfil?->telefono_usuario ?? '') }}" />
                            @error('telefono_usuario') <div class="error-msg">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div style="height:12px;"></div>

                    <div class="grid-2">
                        <div class="field">
                            <div class="label">Fecha inicio</div>
                            <input class="input @error('fecha_inicio_usuario') error-border @enderror" id="fechaInicio"
                                name="fecha_inicio_usuario" type="date"
                                value="{{ old('fecha_inicio_usuario', Auth::user()->perfil?->fecha_inicio_usuario?->format('Y-m-d')) }}" />
                            @error('fecha_inicio_usuario') <div class="error-msg">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div style="height:14px;"></div>

                    <div class="section-title" style="margin:4px 0 10px; padding:0;">
                        <span class="dot" aria-hidden="true"></span>
                        <span>Objetivo y nivel</span>
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <div class="label">Objetivo principal</div>
                            <select class="select @error('objetivo_principal_usuario') error-border @enderror"
                                id="objetivoPrincipal" name="objetivo_principal_usuario">
                                <option value="salud" @selected(old('objetivo_principal_usuario', Auth::user()->perfil?->objetivo_principal_usuario) == 'salud')>
                                    salud
                                </option>
                                <option value="definir" @selected(old('objetivo_principal_usuario', Auth::user()->perfil?->objetivo_principal_usuario) == 'definir')>
                                    definir</option>
                                <option value="volumen" @selected(old('objetivo_principal_usuario', Auth::user()->perfil?->objetivo_principal_usuario) == 'volumen')>
                                    volumen</option>
                                <option value="rendimiento" @selected(old('objetivo_principal_usuario', Auth::user()->perfil?->objetivo_principal_usuario) == 'rendimiento')>rendimiento
                                </option>
                            </select>
                            @error('objetivo_principal_usuario') <div class="error-msg">{{ $message }}</div> @enderror
                        </div>
                        <div class="field">
                            <div class="label">Nivel</div>
                            <select class="select @error('nivel_usuario') error-border @enderror" id="nivelUsuario"
                                name="nivel_usuario">
                                <option value="principiante" @selected(old('nivel_usuario', Auth::user()->perfil?->nivel_usuario) == 'principiante')>
                                    principiante</option>
                                <option value="intermedio" @selected(old('nivel_usuario', Auth::user()->perfil?->nivel_usuario) == 'intermedio')>
                                    intermedio</option>
                                <option value="avanzado" @selected(old('nivel_usuario', Auth::user()->perfil?->nivel_usuario) == 'avanzado')>avanzado
                                </option>
                            </select>
                            @error('nivel_usuario') <div class="error-msg">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div style="height:12px;"></div>

                    <div class="grid-2">
                        <div class="field">
                            <div class="label">Días por semana</div>
                            <input class="input @error('dias_entrenamiento_semana_usuario') error-border @enderror"
                                id="diasSemana" name="dias_entrenamiento_semana_usuario" type="number" min="1" max="7"
                                value="{{ old('dias_entrenamiento_semana_usuario', Auth::user()->perfil?->dias_entrenamiento_semana_usuario ?? '') }}" />
                            @error('dias_entrenamiento_semana_usuario') <div class="error-msg">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="field">
                            <div class="label">Duración estimada por sesión (solo UI)</div>
                            <input class="input" type="text" value="" readonly />
                        </div>
                    </div>

                    <div style="height:14px;"></div>

                    <div class="section-title" style="margin:4px 0 10px; padding:0;">
                        <span class="dot" aria-hidden="true"></span>
                        <span>Medidas base</span>
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <div class="label">Peso (kg)</div>
                            <input class="input @error('peso_kg_usuario') error-border @enderror" id="pesoKg"
                                name="peso_kg_usuario" type="number" step="0.01"
                                value="{{ old('peso_kg_usuario', Auth::user()->perfil?->peso_kg_usuario ?? '') }}" />
                            @error('peso_kg_usuario') <div class="error-msg">{{ $message }}</div> @enderror
                        </div>
                        <div class="field">
                            <div class="label">Altura (cm)</div>
                            <input class="input @error('altura_cm_usuario') error-border @enderror" id="alturaCm"
                                name="altura_cm_usuario" type="number"
                                value="{{ old('altura_cm_usuario', Auth::user()->perfil?->altura_cm_usuario ?? '') }}" />
                            @error('altura_cm_usuario') <div class="error-msg">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    @if (session('status') === 'perfil-updated')
                        <div id="statusMsg" style="margin-top: 10px; font-size: 13px; color: #16fa16;">
                            ¡Perfil actualizado con éxito!
                        </div>
                    @endif

                    <div class="actions justify-content-center">
                        <button class="btn" type="submit" id="saveBtn">Guardar cambios</button>
                    </div>
                </form>
            </section>
        </div>

        <!-- IA COACH -->
        <aside class="chat" aria-label="IA Coach">
            <div class="chat-head">
                <div class="left">
                    <div class="chip" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path
                                d="M12 2a6 6 0 0 0-6 6v3H5a3 3 0 0 0-3 3v2a3 3 0 0 0 3 3h1v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1h1a3 3 0 0 0 3-3v-2a3 3 0 0 0-3-3h-1V8a6 6 0 0 0-6-6Zm-4 6a4 4 0 1 1 8 0v3H8V8Zm10 5a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-1v-4h1ZM6 17H5a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1h1v4Zm2 3v-7h8v7H8Z" />
                        </svg>
                    </div>
                    <div style="min-width:0;">
                        <h2>IA Coach</h2>
                        <p>Tu perfil guía las rutinas.</p>
                    </div>
                </div>
                <button class="kebab" type="button" aria-label="Opciones">···</button>
            </div>

            <div class="chat-body">
                <div class="bubble ai">Veo tu objetivo en “salud” y 3 días/semana. ¿Quieres que lo adapte a 4
                    días?</div>
                <div class="bubble user">Aún no, prefiero 3.</div>
                <div class="bubble ai">Perfecto. Con 3 días, priorizamos básicos + progresión suave.</div>
            </div>

            <div class="chat-foot">
                <button class="iconbtn" type="button" aria-label="Adjuntar">＋</button>
                <input class="chat-input" type="text" placeholder="Escribe un mensaje…" />
                <button class="iconbtn" type="button" aria-label="Enviar">➤</button>
            </div>
        </aside>
    </div>
@endsection

@push('styles')
    <style>
        .main {
            min-width: 0;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 10px;
            align-content: start;
        }

        .hero {
            padding: 10px 6px 6px;
        }

        .hero h1 {
            margin: 0;
            font-family: var(--serif);
            font-weight: 500;
            font-size: clamp(34px, 3vw, 54px);
            line-height: 1.02;
            color: rgba(239, 231, 214, .90);
            text-shadow: 0 12px 40px rgba(0, 0, 0, .62);
            letter-spacing: .01em;
        }

        .hero p {
            margin: 10px 0 0;
            color: rgba(239, 231, 214, .62);
            letter-spacing: .08em;
            text-transform: uppercase;
            font-size: 12px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 18px 0 10px;
            color: rgba(239, 231, 214, .78);
            font-weight: 750;
            letter-spacing: .02em;
            user-select: none;
            padding: 0 6px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: rgba(22, 250, 22, .28);
            box-shadow: 0 0 0 4px rgba(22, 250, 22, .06);
            border: 1px solid rgba(239, 231, 214, .14);
        }

        .panel {
            border-radius: var(--r);
            padding: 18px;
            background:
                radial-gradient(140% 120% at 18% 10%, rgba(190, 145, 85, .16), transparent 65%),
                linear-gradient(180deg, rgba(0, 0, 0, .12), rgba(0, 0, 0, .08)),
                rgba(0, 0, 0, .12);
            border: 1px solid rgba(239, 231, 214, .12);
            box-shadow: 0 18px 52px rgba(0, 0, 0, .40);
            backdrop-filter: blur(16px) saturate(112%);
            -webkit-backdrop-filter: blur(16px) saturate(112%);
            overflow: hidden;
            margin: 0 6px 14px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .field {
            display: grid;
            gap: 7px;
        }

        .label {
            font-size: 12px;
            color: rgba(239, 231, 214, .58);
            letter-spacing: .10em;
            text-transform: uppercase;
        }

        .input,
        .select {
            height: 44px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            color: rgba(239, 231, 214, .90);
            padding: 0 12px;
            outline: none;
            font-family: var(--sans);
            font-size: 13px;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .select option {
            background-color: #121110;
            color: rgba(239, 231, 214, .90);
        }

        .input::placeholder {
            color: rgba(239, 231, 214, .52);
        }

        .input:focus,
        .select:focus {
            border-color: rgba(239, 231, 214, .20);
            box-shadow: 0 0 0 4px rgba(190, 145, 85, .10);
        }

        .input[readonly] {
            opacity: .75;
            cursor: not-allowed;
        }

        .profile-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(239, 231, 214, .10);
            margin-bottom: 14px;
        }

        .avatar-lg {
            width: 62px;
            height: 62px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .18);
            overflow: hidden;
            background: rgba(0, 0, 0, .20);
            box-shadow: 0 14px 38px rgba(0, 0, 0, .30);
            flex: 0 0 62px;
        }

        .avatar-lg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .meta-line {
            color: rgba(239, 231, 214, .56);
            font-size: 12.5px;
            letter-spacing: .02em;
            margin-top: 4px;
            line-height: 1.35;
        }

        .btn {
            width: 100%;
            height: 44px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .16);
            cursor: pointer;
            background:
                radial-gradient(120% 160% at 30% 0%, var(--greenGlow), transparent 35%),
                linear-gradient(180deg, var(--greenBtn1), var(--greenBtn2));
            color: rgba(239, 231, 214, .95);
            font-family: var(--sans);
            font-weight: 800;
            letter-spacing: .06em;
            box-shadow: 0 18px 52px rgba(0, 0, 0, .50);
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

        .btn-ghost {
            width: 100%;
            height: 44px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            color: rgba(239, 231, 214, .82);
            font-family: var(--sans);
            font-weight: 800;
            letter-spacing: .06em;
            cursor: pointer;
            transition: transform .18s ease, border-color .18s ease, background .18s ease;
        }

        .btn-ghost:hover {
            transform: translateY(-1px);
            border-color: rgba(239, 231, 214, .18);
            background: rgba(0, 0, 0, .14);
        }

        .actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 12px;
        }

        .toast {
            display: none;
            margin-top: 12px;
            color: rgba(239, 231, 214, .70);
            font-size: 12.5px;
            line-height: 1.45;
        }

        .chat {
            grid-column: 2 / 3;
            align-self: start;
            position: sticky;
            top: 28px;
            height: calc(100vh - 56px);
            display: grid;
            grid-template-rows: auto 1fr auto;
            border-radius: var(--r2);
            padding: 14px;
            background:
                radial-gradient(140% 120% at 18% 10%, rgba(190, 145, 85, .16), transparent 65%),
                linear-gradient(180deg, rgba(0, 0, 0, .16), rgba(0, 0, 0, .10)),
                rgba(0, 0, 0, .12);
            border: 1px solid rgba(239, 231, 214, .14);
            box-shadow: var(--shadow-lg);
            backdrop-filter: blur(18px) saturate(115%);
            -webkit-backdrop-filter: blur(18px) saturate(115%);
            overflow: hidden;
            min-width: 0;
        }

        .chat-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 6px 6px 10px;
            border-bottom: 1px solid rgba(239, 231, 214, .10);
        }

        .chat-head .left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .chip {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            border: 1px solid rgba(239, 231, 214, .14);
            background:
                radial-gradient(120% 160% at 30% 0%, rgba(22, 250, 22, 0.10), transparent 35%),
                rgba(0, 0, 0, .14);
            display: grid;
            place-items: center;
            color: rgba(239, 231, 214, .86);
            flex: 0 0 34px;
        }

        .chat-head h2 {
            margin: 0;
            font-weight: 900;
            letter-spacing: .02em;
            color: rgba(239, 231, 214, .92);
            font-size: 15px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-head p {
            margin: 2px 0 0;
            color: rgba(239, 231, 214, .56);
            font-size: 12px;
            letter-spacing: .02em;
        }

        .kebab {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            color: rgba(239, 231, 214, .70);
            cursor: pointer;
            transition: transform .18s ease, border-color .18s ease;
        }

        .kebab:hover {
            transform: translateY(-1px);
            border-color: rgba(239, 231, 214, .18);
        }

        .chat-body {
            padding: 14px 6px;
            overflow: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            scrollbar-width: thin;
            scrollbar-color: rgba(239, 231, 214, .18) transparent;
        }

        .bubble {
            max-width: 92%;
            padding: 12px 12px;
            border-radius: 16px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .14);
            color: rgba(239, 231, 214, .82);
            font-size: 13px;
            line-height: 1.4;
            letter-spacing: .01em;
            box-shadow: 0 12px 32px rgba(0, 0, 0, .24);
        }

        .bubble.ai {
            border-top-left-radius: 10px;
            background:
                radial-gradient(120% 140% at 20% 0%, rgba(190, 145, 85, .12), transparent 45%),
                rgba(0, 0, 0, .14);
        }

        .bubble.user {
            align-self: flex-end;
            border-top-right-radius: 10px;
            background:
                radial-gradient(120% 140% at 20% 0%, rgba(22, 250, 22, .10), transparent 45%),
                rgba(0, 0, 0, .16);
        }

        .chat-foot {
            padding: 10px 6px 6px;
            border-top: 1px solid rgba(239, 231, 214, .10);
            display: grid;
            grid-template-columns: 44px 1fr 44px;
            gap: 10px;
            align-items: center;
        }

        .iconbtn {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            color: rgba(239, 231, 214, .74);
            cursor: pointer;
            transition: transform .18s ease, border-color .18s ease;
        }

        .iconbtn:hover {
            transform: translateY(-1px);
            border-color: rgba(239, 231, 214, .18);
        }

        .chat-input {
            height: 44px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            color: rgba(239, 231, 214, .90);
            padding: 0 12px;
            outline: none;
            font-family: var(--sans);
            font-size: 13px;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .chat-input::placeholder {
            color: rgba(239, 231, 214, .52);
        }

        .chat-input:focus {
            border-color: rgba(239, 231, 214, .20);
            box-shadow: 0 0 0 4px rgba(190, 145, 85, .10);
        }

        .error-border {
            border-color: #ff4444 !important;
            box-shadow: 0 0 0 4px rgba(255, 0, 0, 0.1);
        }

        .error-msg {
            color: #ff4444;
            font-size: 11px;
            margin-top: 4px;
            font-family: var(--sans);
        }

        @media (max-width:1180px) {
            .main {
                grid-template-columns: 1fr;
            }

            .chat {
                position: relative;
                top: 0;
                height: auto;
                grid-column: 1 / -1;
            }

            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        const resetBtn = document.getElementById('resetBtn');

        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                location.reload();
            });
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush