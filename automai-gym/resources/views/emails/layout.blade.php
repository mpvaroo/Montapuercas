<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #0a0a0a;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #EFE7D6;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #0a0a0a;
            padding-bottom: 40px;
        }

        .main {
            background-color: #121212;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(239, 231, 214, 0.1);
            margin-top: 40px;
        }

        .header {
            padding: 40px 0;
            text-align: center;
            background: linear-gradient(180deg, rgba(70, 98, 72, 0.15), transparent);
        }

        .brand {
            font-weight: 700;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            font-size: 14px;
            color: rgba(239, 231, 214, 0.6);
        }

        .brand b {
            display: block;
            font-size: 22px;
            color: #EFE7D6;
            letter-spacing: 0.35em;
            margin-bottom: 4px;
        }

        .content {
            padding: 40px;
            font-size: 16px;
            line-height: 1.6;
            color: rgba(239, 231, 214, 0.85);
        }

        h1 {
            color: #EFE7D6;
            font-size: 28px;
            margin-bottom: 24px;
            font-weight: 500;
            text-align: center;
        }

        .panel {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(239, 231, 214, 0.1);
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }

        .button-container {
            text-align: center;
            margin: 35px 0;
        }

        .button {
            background: linear-gradient(180deg, #466248, #2d3f2e);
            border-radius: 999px;
            color: #EFE7D6 !important;
            display: inline-block;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.06em;
            padding: 18px 45px;
            text-decoration: none;
            text-transform: uppercase;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(239, 231, 214, 0.15);
        }

        .footer {
            padding: 30px;
            text-align: center;
            font-size: 12px;
            color: rgba(239, 231, 214, 0.4);
            letter-spacing: 0.05em;
        }

        .footer a {
            color: rgba(239, 231, 214, 0.6);
            text-decoration: underline;
        }

        @media only screen and (max-width: 600px) {
            .main {
                width: 90% !important;
                margin-top: 20px !important;
            }

            .content {
                padding: 25px !important;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <table class="main" role="presentation">
            <tr>
                <td class="header">
                    <div class="brand">
                        <b>AUTOMAI</b>
                        GYM
                    </div>
                </td>
            </tr>
            <tr>
                <td class="content">
                    @yield('content')
                </td>
            </tr>
            <tr>
                <td class="footer">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.<br>
                    Has recibido este correo porque estás registrado en nuestra plataforma.
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
