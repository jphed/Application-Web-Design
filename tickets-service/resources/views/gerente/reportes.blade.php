@extends('layouts.tickets')
@section('title', 'Reportes')
@section('content')
<div class="container py-4">
 <h2 class="mb-4">Reportes</h2>
 <div class="row">
 <div class="col-md-6">
 <div class="card">
 <div class="card-header bg-primary text-white">
 Tickets por Categoría
 </div>
 <div class="card-body">
 <ul class="list-group">
 @foreach($porCategoria as $cat)
 <li class="list-group-item d-flex justify-content-between">
 {{ ucfirst($cat->categoria) }}
 <span class="badge bg-primary">{{ $cat->total }}</span>
 </li>
 @endforeach
 </ul>
 </div>
 </div>
 </div>
 <div class="col-md-6">
 <div class="card">
 <div class="card-header bg-success text-white">
 Tickets por Urgencia
 </div>
 <div class="card-body">
 <ul class="list-group">
 @foreach($porUrgencia as $urg)
 <li class="list-group-item d-flex justify-content-between">
 {{ ucfirst($urg->nivel_urgencia) }}
 <span class="badge bg-success">{{ $urg->total }}</span>
 </li>
 @endforeach
 </ul>
 </div>
 </div>
 </div>
 </div>
</div>
@endsection
