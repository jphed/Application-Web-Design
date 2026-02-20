<h1>Usuarios Registrados</h1>
<table>
    @foreach($usuarios as $u)
        <tr>
            <td>{{ $u->nombre }}</td>
            <td>{{ $u->email }}</td>
        </tr>
    @endforeach
</table>
<a href="/registro">Registrar otro</a>