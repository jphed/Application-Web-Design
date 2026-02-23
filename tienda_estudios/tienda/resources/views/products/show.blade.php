<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Productos</title>
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
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        h1 {
            color: white;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 18px;
        }
        .table-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .table th {
            padding: 20px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table tbody tr {
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s;
        }
        .table tbody tr:hover {
            background-color: #f9fafb;
        }
        .table tbody tr:last-child {
            border-bottom: none;
        }
        .table td {
            padding: 20px;
            color: #374151;
            font-size: 16px;
        }
        .table td:first-child {
            font-weight: 600;
            color: #1f2937;
        }
        .price {
            font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
            color: #059669;
            font-weight: 600;
        }
        .category {
            display: inline-block;
            background: #f3f4f6;
            color: #6b7280;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #6b7280;
        }
        .empty-state h3 {
            font-size: 20px;
            margin-bottom: 8px;
            color: #374151;
        }
        .empty-state p {
            font-size: 16px;
        }
        .actions {
            text-align: center;
            margin-top: 32px;
        }
        .btn {
            display: inline-block;
            background: white;
            color: #667eea;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            border: 2px solid #667eea;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .btn:hover {
            background: #667eea;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .btn:active {
            transform: translateY(0);
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 12px;
        }
        .footer .author {
            font-weight: 600;
            color: white;
        }
        @media (max-width: 768px) {
            .table-container {
                border-radius: 12px;
            }
            .table th,
            .table td {
                padding: 16px 12px;
                font-size: 14px;
            }
            h1 {
                font-size: 28px;
            }
            .subtitle {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Listado de Productos</h1>
            <p class="subtitle">Todos los productos registrados en el sistema</p>
        </div>

        <div class="table-container">
            @if($productos->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Categoría</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                            <tr>
                                <td>{{ $producto->nombre }}</td>
                                <td><span class="price">${{ number_format($producto->precio, 2) }}</span></td>
                                <td><span class="category">{{ $producto->categoria?->nombre ?: 'Sin categoría' }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <h3>No hay productos registrados</h3>
                    <p>Comienza agregando tu primer producto usando el formulario</p>
                </div>
            @endif
        </div>

        <div class="actions">
            <a href="{{ route('products.index') }}" class="btn">← Volver al formulario</a>
        </div>

        <div class="footer">
            <p>Creado por <span class="author">Jorge Parra</span> | ITIT | Matrícula: 13104</p>
        </div>
    </div>
</body>
</html>
