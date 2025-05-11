<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Verifica si hay un mensaje de éxito
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');
        if (successMessage) {
            // Después de 3 segundos, ocultar el mensaje
            setTimeout(function () {
                successMessage.style.display = 'none';
            }, 3000);
        }
        if (errorMessage) {
            // Después de 10 segundos, ocultar el mensaje
            setTimeout(function () {
                errorMessage.style.display = 'none';
            }, 10000);
        }
    });
</script>
