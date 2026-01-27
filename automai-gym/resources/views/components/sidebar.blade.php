<aside class="sidebar">
    <div>
        <div class="brand">
            <b>AUTOMAI</b>
            GYM
        </div>

        <nav class="nav" aria-label="Menú principal">
            <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <span class="ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 3 3 10v11h6v-7h6v7h6V10l-9-7Z" />
                    </svg>
                </span>
                Inicio
            </a>
            <a class="{{ request()->routeIs('rutinas') ? 'active' : '' }}" href="{{ route('rutinas') }}">
                <span class="ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M7 4h10v2H7V4Zm-2 4h14v12H5V8Zm2 2v8h10v-8H7Z" />
                    </svg>
                </span>
                Rutinas
            </a>
            <a class="{{ request()->routeIs('reservas') ? 'active' : '' }}" href="{{ route('reservas') }}">
                <span class="ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M6 7h12v2H6V7Zm0 4h12v2H6v-2Zm0 4h8v2H6v-2Z" />
                    </svg>
                </span>
                Reservas
            </a>
            <a class="{{ request()->routeIs('calendario') ? 'active' : '' }}" href="{{ route('calendario') }}">
                <span class="ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M19 3h-1V1h-2v2H8V1H6v3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z" />
                    </svg>
                </span>
                Calendario
            </a>
            <a class="{{ request()->routeIs('progreso') ? 'active' : '' }}" href="{{ route('progreso') }}">
                <span class="ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M3.5 18.49l6-6.01 4 4L22 6.92l-1.41-1.41-7.09 7.97-4-4L2 16.99z" />
                    </svg>
                </span>
                Progreso
            </a>
            <a class="{{ request()->routeIs('ia-coach') ? 'active' : '' }}" href="{{ route('ia-coach') }}">
                <span class="ico">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M12 2a7 7 0 0 0-7 7c0 2.6 1.4 4.9 3.5 6.1V22l3-1.6 3 1.6v-6.9C17.6 13.9 19 11.6 19 9a7 7 0 0 0-7-7Zm0 2a5 5 0 1 1 0 10 5 5 0 0 1 0-10Z" />
                    </svg>
                </span>
                IA Coach
            </a>
        </nav>
    </div>

    <div class="user">
        <div class="avatar">
            <img src="{{ asset('img/user.png') }}" alt="Foto de perfil">
        </div>
        <div>
            <div class="name">Marcelo</div>
            <div class="role">Usuario</div>
        </div>
    </div>
</aside>

<style>
    .sidebar {
        position: sticky;
        top: 28px;
        height: calc(100vh - 56px);
        padding: 18px 14px;
        background: transparent;
        border: none;
        -webkit-backdrop-filter: none;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
    }

    .brand {
        text-align: left;
        font-family: var(--sans);
        text-transform: uppercase;
        letter-spacing: .30em;
        font-weight: 700;
        font-size: 11px;
        color: var(--cream-3);
        padding: 8px 10px 14px;
    }

    .brand b {
        display: block;
        font-size: 16px;
        letter-spacing: .34em;
        color: rgba(239, 231, 214, .90);
        margin-bottom: 2px;
    }

    .nav {
        display: grid;
        gap: 6px;
        padding: 8px 8px 14px;
    }

    .nav a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 10px;
        border-radius: 14px;
        text-decoration: none;
        color: rgba(239, 231, 214, .72);
        border: 1px solid transparent;
        transition: transform .18s ease, background .18s ease, border-color .18s ease, color .18s ease;
        user-select: none;
    }

    .nav a:hover {
        transform: translateX(2px);
        background: rgba(0, 0, 0, .12);
        border-color: rgba(239, 231, 214, .14);
        color: rgba(239, 231, 214, .92);
    }

    .nav a.active {
        background:
            radial-gradient(120% 160% at 30% 0%, rgba(22, 250, 22, 0.12), transparent 35%),
            linear-gradient(180deg, rgba(0, 0, 0, .20), rgba(0, 0, 0, .12));
        border-color: rgba(239, 231, 214, .16);
        color: rgba(239, 231, 214, .95);
    }

    .ico {
        width: 18px;
        height: 18px;
        flex: 0 0 18px;
        opacity: .9;
    }

    .ico svg {
        width: 18px;
        height: 18px;
        display: block;
        fill: currentColor;
    }

    .user {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 10px;
        border-radius: 16px;
        border: 1px solid rgba(239, 231, 214, .12);
        background: rgba(0, 0, 0, .10);
    }

    .avatar {
        width: 38px;
        height: 38px;
        border-radius: 999px;
        border: 1px solid rgba(239, 231, 214, .18);
        overflow: hidden;
        background: rgba(0, 0, 0, .20);
        flex: 0 0 38px;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .user .name {
        font-family: var(--sans);
        font-weight: 700;
        letter-spacing: .02em;
        color: rgba(239, 231, 214, .92);
        font-size: 14px;
        line-height: 1.15;
    }

    .user .role {
        font-size: 12px;
        color: rgba(239, 231, 214, .55);
        margin-top: 2px;
    }

    @media (max-width: 900px) {
        .sidebar {
            position: relative;
            top: 0;
            height: auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .nav {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .nav {
            grid-template-columns: 1fr;
        }
    }
</style>
