<form action="/registro" method="POST">
    @csrf
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Registrar</button>
</form>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach
@endif
