<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Catálogo de obras artísticas') -Museo</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #26312e;
            --muted: #65716b;
            --accent: #295f4b;
            --line: #dce3dd;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f5f0;
            color: var(--ink);
            font: 16px/1.6 system-ui, sans-serif;
        }

        a {
            color: var(--accent);
            text-underline-offset: 3px;
        }

        header {
            background: #fff;
            border-bottom: 1px solid var(--line);
        }

        .header-inner,
        main {
            max-width: 1100px;
            margin: auto;
            padding: 24px;
        }

        .brand {
            font-weight: 750;
            text-decoration: none;
            font-size: 1.1rem;
        }

        .eyebrow {
            display: block;
            font-size: .75rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--muted);
        }

        main {
            padding-top: 40px;
        }

        h1 {
            font: 500 clamp(1.9rem, 4vw, 2.7rem)/1.2 Georgia, serif;
            margin: 0 0 12px;
        }

        p {
            margin: 0 0 20px;
        }

        .muted,
        .help {
            color: var(--muted);
        }

        .page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 26px;
        }

        .page-heading p {
            margin: 0;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 10px;
        }

        .table-scroll {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        caption {
            text-align: left;
            padding: 16px 22px;
            font-weight: 600;
        }

        th {
            color: var(--muted);
            font-size: .8rem;
            font-weight: 650;
            background: #f9faf7;
        }

        th,
        td {
            padding: 16px 22px;
            border-top: 1px solid var(--line);
            vertical-align: middle;
        }

        .thumbnail {
            display: block;
            width: 88px;
            height: 72px;
            object-fit: contain;
            background: #f4f5f0;
            border-radius: 4px;
        }

        .empty {
            text-align: center;
            padding: 56px 24px;
            color: var(--muted);
        }

        .button {
            display: inline-block;
            border: 1px solid var(--accent);
            border-radius: 5px;
            padding: 10px 18px;
            background: var(--accent);
            color: #fff;
            font: inherit;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .button:hover {
            filter: brightness(.92);
        }

        .button-secondary {
            background: #fff;
            color: var(--accent);
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .actions form {
            margin: 0;
        }

        .delete {
            padding: 0;
            border: 0;
            background: none;
            color: #a12e32;
            font: inherit;
            text-decoration: underline;
            text-underline-offset: 3px;
            cursor: pointer;
        }

        .form-panel {
            max-width: 720px;
            padding: 28px;
        }

        .field {
            margin-bottom: 24px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
        }

        input,
        select {
            width: 100%;
            border: 1px solid #aebbb1;
            border-radius: 5px;
            background: #fff;
            color: var(--ink);
            padding: 11px 12px;
            font: inherit;
        }

        input[type="file"] {
            padding: 9px;
        }

        input[aria-invalid="true"],
        select[aria-invalid="true"] {
            border-color: #a12e32;
        }

        :focus-visible {
            outline: 3px solid #9cc8ae;
            outline-offset: 3px;
        }

        .help,
        .field-error {
            display: block;
            font-size: .88rem;
            margin: 6px 0 0;
        }

        .field-error {
            color: #a12e32;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            padding-top: 8px;
            flex-wrap: wrap;
        }

        .notice {
            padding: 16px 20px;
            border: 1px solid;
            border-radius: 6px;
            margin-bottom: 24px;
        }

        .success {
            border-color: #a0c2a8;
            background: #edf7ee;
        }

        .errors {
            border-color: #dcb4b4;
            background: #fff1ef;
            color: #80252b;
        }

        .errors ul {
            margin: 6px 0 0;
            padding-left: 24px;
        }

        .current-image {
            margin: 0 0 24px;
        }

        .current-image img {
            display: block;
            max-width: 100%;
            max-height: 260px;
            border-radius: 5px;
            background: #f4f5f0;
        }

        .current-image figcaption {
            margin-top: 6px;
            color: var(--muted);
            font-size: .9rem;
        }

        footer {
            max-width: 1100px;
            margin: 16px auto 0;
            padding: 24px;
            color: var(--muted);
            font-size: .85rem;
        }

        @media (max-width: 640px) {

            .header-inner,
            main,
            footer {
                padding: 20px 16px;
            }

            .page-heading {
                align-items: flex-start;
                flex-direction: column;
                gap: 16px;
            }

            .form-panel {
                padding: 20px;
            }

            th,
            td {
                padding: 12px;
            }
        }

    </style>
</head>
<body>
    <header>
        <div class="header-inner">
            <span class="eyebrow">Museo - Gestión de la colección</span>
            <a class="brand" href="{{  }}">Catálogo de obras</a>
        </div>
    </header>

    <main id="contenido">

        @if ($errors->any())
        <div class="notice errors" role="alert">
            <strong>Revisa los siguientes campos:</strong>
            <ul>
                @foreach ()
                <li>{{ }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>

    <footer>Catálogo del museo - Desarrollo Web en Entorno Servidor - UT2</footer>
</body>
</html>
