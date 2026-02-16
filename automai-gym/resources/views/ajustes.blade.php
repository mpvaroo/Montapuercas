@extends('layouts.app')

@section('title', 'Ajustes')

@section('content')
    <div class="main">
        <div>
            <header class="hero">
                <h1>Ajustes.</h1>
                <p>Preferencias, notificaciones y tono de IA.</p>
            </header>

            <div class="section-title">
                <span class="dot" aria-hidden="true"></span>
                <span>Preferencias (ajustes_usuario)</span>
            </div>

            <section class="panel" aria-label="Ajustes de usuario">
                <div class="row">
                    <div class="left">
                        <div class="title">Notificaciones de entrenamiento</div>
                        <div class="desc">Campo: <b>notificaciones_entrenamiento_activas</b>.</div>
                    </div>
                    <div class="switch" id="swTrain" data-on="true" role="switch" aria-checked="true" tabindex="0"
                        data-name="notificaciones_entrenamiento_activas"></div>
                </div>

                <div class="row">
                    <div class="left">
                        <div class="title">Notificaciones de clases</div>
                        <div class="desc">Campo: <b>notificaciones_clases_activas</b>.</div>
                    </div>
                    <div class="switch" id="swClasses" data-on="true" role="switch" aria-checked="true" tabindex="0"
                        data-name="notificaciones_clases_activas"></div>
                </div>

                <div class="row">
                    <div class="left">
                        <div class="title">Tono de IA Coach</div>
                        <div class="desc">Campo: <b>tono_ia_coach</b> (directo / motivador).</div>
                    </div>
                    <select class="select" id="tone" name="tono_ia_coach" style="width: 170px;">
                        <option value="directo" selected>directo</option>
                        <option value="motivador">motivador</option>
                    </select>
                </div>

                <div class="row">
                    <div class="left">
                        <div class="title">Idioma preferido</div>
                        <div class="desc">Campo: <b>idioma_preferido</b> (es / en).</div>
                    </div>
                    <select class="select" id="lang" name="idioma_preferido" style="width: 170px;">
                        <option value="es" selected>es</option>
                        <option value="en">en</option>
                    </select>
                </div>

                <div class="row">
                    <div class="left">
                        <div class="title">Semana empieza en</div>
                        <div class="desc">Campo: <b>semana_empieza_en</b> (lunes / domingo).</div>
                    </div>
                    <select class="select" id="weekStart" name="semana_empieza_en" style="width: 170px;">
                        <option value="lunes" selected>lunes</option>
                        <option value="domingo">domingo</option>
                    </select>
                </div>

                <button class="btn" type="button" id="save">Guardar ajustes</button>
                <div id="saved" class="note" style="display:none;">
                    Guardado (demo). Este panel corresponde a <b>ajustes_usuario</b>.
                </div>
            </section>

            <div class="section-title">
                <span class="dot" aria-hidden="true"></span>
                <span>Seguridad (seguridad_usuario)</span>
            </div>

            <section class="panel" aria-label="Seguridad de usuario">
                <div class="grid-2">
                    <div class="field">
                        <div class="label">Requiere cambio contraseña</div>
                        <input class="input" name="requiere_cambio_contrasena" type="text" value="No" readonly />
                    </div>
                    <div class="field">
                        <div class="label">Último cambio contraseña</div>
                        <input class="input" name="fecha_ultimo_cambio_contrasena" type="text" value="2026-01-20 12:31"
                            readonly />
                    </div>
                </div>

                <div style="height:12px;"></div>

                <div class="grid-2">
                    <div class="field">
                        <div class="label">Intentos fallidos login</div>
                        <input class="input" name="intentos_fallidos_login" type="text" value="1" readonly />
                    </div>
                    <div class="field">
                        <div class="label">Último intento fallido</div>
                        <input class="input" name="fecha_ultimo_intento_fallido" type="text" value="2026-02-06 22:10"
                            readonly />
                    </div>
                </div>

                <div class="note">
                    Este bloque es UI (solo lectura) y corresponde a <b>seguridad_usuario</b>.
                </div>
            </section>
        </div>

        <!-- IA COACH -->
        <aside class="chat" aria-label="IA Coach">
            <div class="chat-head">
                <div class="left">
                    <div class="chip" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2a6 6 0 0 0-6 6v3H5a3 3 0 0 0-3 3v2a3 3 0 0 0 3 3h1v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1h1a3 3 0 0 0 3-3v-2a3 3 0 0 0-3-3h-1V8a6 6 0 0 0-6-6Zm-4 6a4 4 0 1 1 8 0v3H8V8Zm10 5a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-1v-4h1ZM6 17H5a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1h1v4Zm2 3v-7h8v7H8Z" />
                        </svg>
                    </div>
                    <div style="min-width:0;">
                        <h2>IA Coach</h2>
                        <p>Tu tono afecta mis respuestas.</p>
                    </div>
                </div>
                <button class="kebab" type="button" aria-label="Opciones">···</button>
            </div>

            <div class="chat-body">
                <div class="bubble ai">Si activas “motivador”, usaré mensajes más energéticos y refuerzo
                    positivo.</div>
                <div class="bubble user">Vale, lo pruebo.</div>
                <div class="bubble ai">Perfecto. Guardarlo no cambia tu rendimiento, solo tu experiencia.</div>
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

        .select,
        .input {
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

        .select:focus,
        .input:focus {
            border-color: rgba(239, 231, 214, .20);
            box-shadow: 0 0 0 4px rgba(190, 145, 85, .10);
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 14px 0;
            border-top: 1px solid rgba(239, 231, 214, .10);
        }

        .row:first-child {
            border-top: none;
            padding-top: 0;
        }

        .row:last-child {
            padding-bottom: 0;
        }

        .row .left {
            min-width: 0;
        }

        .row .title {
            color: rgba(239, 231, 214, .90);
            font-weight: 850;
            letter-spacing: .02em;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .row .desc {
            margin-top: 4px;
            color: rgba(239, 231, 214, .56);
            font-size: 12.5px;
            letter-spacing: .02em;
            line-height: 1.3;
        }

        /* Switch premium */
        .switch {
            width: 52px;
            height: 30px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .14);
            background: rgba(0, 0, 0, .14);
            position: relative;
            cursor: pointer;
            transition: border-color .18s ease, filter .18s ease;
            flex: 0 0 52px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, .18);
        }

        .switch::after {
            content: "";
            position: absolute;
            top: 4px;
            left: 4px;
            width: 22px;
            height: 22px;
            border-radius: 999px;
            background:
                radial-gradient(120% 160% at 30% 0%, rgba(239, 231, 214, .14), transparent 50%),
                rgba(239, 231, 214, .22);
            border: 1px solid rgba(239, 231, 214, .18);
            transition: transform .18s ease, background .18s ease;
        }

        .switch[data-on="true"] {
            border-color: rgba(239, 231, 214, .18);
            background:
                radial-gradient(120% 160% at 30% 0%, rgba(22, 250, 22, .12), transparent 50%),
                rgba(0, 0, 0, .16);
        }

        .switch[data-on="true"]::after {
            transform: translateX(22px);
            background:
                radial-gradient(120% 160% at 30% 0%, rgba(22, 250, 22, .12), transparent 50%),
                rgba(239, 231, 214, .22);
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
            margin-top: 12px;
        }

        .btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.06);
            border-color: rgba(239, 231, 214, .22);
        }

        .note {
            margin-top: 10px;
            color: rgba(239, 231, 214, .56);
            font-size: 12.5px;
            letter-spacing: .02em;
            line-height: 1.4;
        }

        /* Chat (igual) */
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
            background: radial-gradient(120% 160% at 30% 0%, rgba(22, 250, 22, 0.10), transparent 35%), rgba(0, 0, 0, .14);
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
            background: radial-gradient(120% 140% at 20% 0%, rgba(190, 145, 85, .12), transparent 45%), rgba(0, 0, 0, .14);
        }

        .bubble.user {
            align-self: flex-end;
            border-top-right-radius: 10px;
            background: radial-gradient(120% 140% at 20% 0%, rgba(22, 250, 22, .10), transparent 45%), rgba(0, 0, 0, .16);
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
        function toggleSwitch(el) {
            const on = el.getAttribute('data-on') === 'true';
            const next = !on;
            el.setAttribute('data-on', String(next));
            el.setAttribute('aria-checked', String(next));
            // Guardado "de cara a BD": 1/0
            el.dataset.value = next ? '1' : '0';
        }

        ['swTrain', 'swClasses'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.dataset.value = el.getAttribute('data-on') === 'true' ? '1' : '0';
            el.addEventListener('click', () => toggleSwitch(el));
            el.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleSwitch(el);
                }
            });
        });

        const save = document.getElementById('save');
        const saved = document.getElementById('saved');

        if (save) {
            save.addEventListener('click', () => {
                saved.style.display = 'block';
                clearTimeout(window.__s);
                window.__s = setTimeout(() => saved.style.display = 'none', 2200);
            });
        }
    </script>
@endpush