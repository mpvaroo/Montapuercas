@extends('layouts.app')

@section('title', 'Panel Admin')

@section('content')
    <main class="main">
        <header class="topbar">
            <div class="hero">
                <h1>Panel Administrativo</h1>
                <p>Control total, visión estratégica.</p>
            </div>
            <div class="top-actions">
                <button class="icon-btn" aria-label="Notificaciones">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M12 22a2 2 0 0 0 2-2h-4a2 2 0 0 0 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4a1.5 1.5 0 0 0-3 0v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z" />
                    </svg>
                    <span class="notif-dot"></span>
                </button>
                <div class="user-pill">
                    <div class="mini-avatar"><img src="{{ Auth::user()->avatar_url }}" alt=""></div>
                    Admin: {{ Auth::user()->nombre_mostrado_usuario }}
                </div>
            </div>
        </header>

        <livewire:admin.admin-dashboard />
    </main>
@endsection

@push('styles')
    <style>
        .main {
            min-width: 0;
            display: grid;
            grid-template-rows: auto auto 1fr;
            gap: 18px;
            align-content: start;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            padding: 10px 6px;
        }

        .hero h1 {
            margin: 0;
            font-family: var(--serif);
            font-weight: 500;
            font-size: 44px;
            color: var(--cream);
            letter-spacing: .01em;
            line-height: 1.05;
        }

        .hero p {
            margin: 6px 0 0;
            color: rgba(239, 231, 214, .60);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .12em;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .18);
            display: grid;
            place-items: center;
            color: rgba(239, 231, 214, .80);
            cursor: pointer;
            position: relative;
        }

        .icon-btn svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        .notif-dot {
            position: absolute;
            top: 9px;
            right: 10px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #be9155;
            border: 2px solid #000;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .15);
            color: var(--cream-2);
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
        }

        .mini-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            overflow: hidden;
            border: 1px solid rgba(239, 231, 214, .2);
        }

        .mini-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .surface {
            border-radius: 18px;
            border: 1px solid rgba(239, 231, 214, .1);
            background: rgba(0, 0, 0, .1);
            overflow: hidden;
        }

        .tabs {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: rgba(239, 231, 214, .03);
            border-bottom: 1px solid rgba(239, 231, 214, .08);
        }

        .tab-left {
            display: flex;
            gap: 10px;
        }

        .tab {
            height: 34px;
            padding: 0 15px;
            border-radius: 999px;
            background: transparent;
            border: 1px solid transparent;
            color: var(--cream-3);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            cursor: pointer;
            transition: .2s;
        }

        .tab.active {
            background: rgba(239, 231, 214, .08);
            border-color: rgba(239, 231, 214, .15);
            color: var(--cream);
        }

        .tab-right {
            display: flex;
            gap: 12px;
        }

        .search {
            width: 280px;
            height: 36px;
            border-radius: 18px;
            background: rgba(0, 0, 0, .3);
            border: 1px solid rgba(239, 231, 214, .1);
            color: #fff;
            padding: 0 15px;
            font-size: 12px;
            outline: none;
        }

        .cta {
            height: 36px;
            padding: 0 18px;
            border-radius: 999px;
            background: var(--greenBtn1);
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .cta svg {
            width: 14px;
            height: 14px;
            fill: currentColor;
        }

        .content {
            padding: 20px;
        }

        .grid2 {
            display: grid;
            grid-template-columns: 1.3fr 0.7fr;
            gap: 20px;
        }

        .panel {
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .1);
            background: rgba(0, 0, 0, .15);
            overflow: hidden;
        }

        .panel-h {
            padding: 12px 15px;
            background: rgba(239, 231, 214, .03);
            border-bottom: 1px solid rgba(239, 231, 214, .08);
        }

        .panel-h strong {
            font-family: var(--serif);
            font-size: 15px;
            color: var(--cream);
        }

        .pill {
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(239, 231, 214, .05);
            border: 1px solid rgba(239, 231, 214, .1);
            color: var(--cream-3);
            font-size: 10px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 12px 15px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            color: var(--cream-3);
            border-bottom: 1px solid rgba(239, 231, 214, .1);
        }

        td {
            padding: 12px 15px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(239, 231, 214, .05);
        }

        .state {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 10px;
            text-transform: uppercase;
            color: var(--cream-2);
            font-weight: 800;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #be9155;
        }

        .dot.green {
            background: #4ade80;
        }

        .dot.red {
            background: #f87171;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .mini-btn {
            height: 28px;
            padding: 0 12px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .15);
            background: transparent;
            color: var(--cream-2);
            font-size: 10px;
            font-weight: 800;
            cursor: pointer;
        }

        .mini-btn.primary {
            background: rgba(239, 231, 214, .05);
        }

        .form {
            padding: 15px;
            display: grid;
            gap: 12px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field label {
            font-size: 9px;
            text-transform: uppercase;
            color: var(--cream-3);
            font-weight: 800;
        }

        .input,
        select,
        textarea {
            width: 100%;
            height: 40px;
            border-radius: 10px;
            background: rgba(0, 0, 0, .2);
            border: 1px solid rgba(239, 231, 214, .08);
            color: #fff;
            padding: 0 12px;
            font-size: 13px;
            outline: none;
        }

        textarea {
            height: 80px;
            padding: 12px;
        }

        .divider {
            height: 1px;
            background: rgba(239, 231, 214, .1);
            margin: 5px 0;
        }

        .btn-danger {
            background: rgba(248, 113, 113, .1);
            color: #f87171;
            border: 1px solid rgba(248, 113, 113, .2);
        }
    </style>
@endpush