@extends('layouts.adminlte')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Editar Dominio</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Errores encontrados!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $dominio) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="dominio" class="form-label">Nombre del Dominio</label>
            <input type="text" name="dominio" class="form-control" value="{{ old('dominio', $dominio->dominio) }}" required>
        </div>

        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select name="cliente_id" class="form-select" required>
                <option value="">Seleccione un cliente</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ $dominio->cliente_id == $cliente->id ? 'selected' : '' }}>
                        {{ $cliente->clientes }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="plan_id" class="form-label">Plan</label>
            <select name="plan_id" class="form-select" required>
                <option value="">Seleccione un plan</option>
                @foreach($planes as $plan)
                    <option value="{{ $plan->id }}" {{ $dominio->plan_id == $plan->id ? 'selected' : '' }}>
                        {{ $plan->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="servidores_id" class="form-label">Servidor</label>
            <select name="servidor_id" class="form-select" required>
                <option value="">Seleccione un servidor</option>
                @foreach($servidores as $servidor)
                    <option value="{{ $servidor->id }}" {{ old('servidor_id') == $servidor->id ? 'selected' : '' }}>
                        {{ $servidor->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Actualizar
        </button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
