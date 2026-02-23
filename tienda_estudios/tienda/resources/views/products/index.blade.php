<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrar Producto</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 40px;
            width: 100%;
            max-width: 480px;
        }
        h1 {
            color: #1f2937;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            text-align: center;
        }
        .subtitle {
            color: #6b7280;
            text-align: center;
            margin-bottom: 32px;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            color: #374151;
            font-weight: 500;
            margin-bottom: 6px;
            font-size: 14px;
        }
        input, select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.2s;
            background-color: #f9fafb;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 8px;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .btn:active {
            transform: translateY(0);
        }
        .errors {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .errors ul {
            color: #dc2626;
            font-size: 14px;
            list-style-position: inside;
        }
        .link-container {
            text-align: center;
            margin-top: 24px;
        }
        .link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.2s;
        }
        .link:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        .footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }
        .footer .author {
            font-weight: 600;
            color: #374151;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registrar Producto</h1>
        <p class="subtitle">Completa el formulario para agregar un nuevo producto</p>

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('products.store') }}">
            @csrf

            <div class="form-group">
                <label for="nombre">Nombre del producto</label>
                <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required placeholder="Ej: Laptop ASUS">
            </div>

            <div class="form-group">
                <label for="precio">Precio</label>
                <input id="precio" name="precio" type="number" step="0.01" value="{{ old('precio') }}" required placeholder="0.00">
            </div>

            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select id="categoria_id" name="categoria_id" required>
                    <option value="" disabled selected>Selecciona una categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn">Guardar Producto</button>
        </form>

        <div class="link-container">
            <a href="{{ route('products.show') }}" class="link">Ver listado de productos →</a>
        </div>

        <div class="footer">
            <p>Creado por <span class="author">Jorge Parra</span> | ITIT | Matrícula: 13104</p>
        </div>
    </div>
</body>
</html>
