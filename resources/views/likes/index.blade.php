@extends('components.layout')
@section('main')
    @props(['$table_name', '$table_data'])

    <!-- Contenedor principal con estilo de fondo, bordes redondeados y sombra -->
    <div class="bg-gray-100 rounded-lg shadow-md p-6">

        <livewire:tables tableName="likes" />

    </div>
    <x-time_mensage />
@endsection