<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <title>Lista de Productos</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">
</head>
<body class="p-5">
 <div class="container">
 <h1 class="mb-4">Lista de Productos</h1>

 <!-- Botón "Agregar Producto" que lleva a la vista create -->
 <a href="{{ route('products.create') }}" class="btn btn-success mb-3">
 Agregar Producto
 </a>
 <p>Aquí irá la tabla de productos (puedes agregarla después).</p>
 </div>
</body>
</html>