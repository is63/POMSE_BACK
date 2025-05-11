<div class="flex justify-center items-center mb-6 mt-4 space-x-4" id="search">
    <!-- Barra de búsqueda -->
    <input type="text" id="searchInput" placeholder="Buscar..."
           class="px-4 py-2 border border-gray-300 rounded w-1/3"
           oninput="filterTable()">

    <select id="searchField" class="px-4 py-2 border border-gray-300 rounded" onchange="clearInput();">
        @foreach($fields as $key)
            <option value="{{ $key }}">{{ ucfirst($key) }}</option>
        @endforeach
    </select>
</div>
<script>
    function filterTable() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let field = document.getElementById('searchField').value;
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            // Encontrar la celda que corresponde al campo seleccionado
            let cell = row.querySelector(`td[data-field="${field}"]`);
            if (cell) {
                // Guardar el texto de la celda y convertirlo a minúsculas sin espacios
                const cellText = cell.textContent.trim().toLowerCase();
                // Comprobar si el texto de la celda incluye el texto de busqueda
                if (cellText.includes(input)) {
                    row.style.display = ''; // Mostrar la fila si coincide
                } else {
                    row.style.display = 'none'; // Ocultar la fila si no coincide
                }
            } else {
                row.style.display = 'none'; // Ocultar la fila si no se encuentra una coincidencia
            }
        });
    }

    function clearInput() {
        document.getElementById('searchInput').value = '';
        rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.style.display = ''; // Mostrar todas las filas
        });
    }
</script>
