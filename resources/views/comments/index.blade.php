@extends('components.layout')
@section('main')



    <!-- Contenedor principal con estilo de fondo, bordes redondeados y sombra -->
    <div class="bg-gray-100 rounded-lg shadow-md p-6">

        <livewire:tables tableName="comments" />

    </div>
    <x-time_mensage />

@endsection