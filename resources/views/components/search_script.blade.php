<script>
    function filterOptions(inputId, selectId) {
        const input = document.getElementById(inputId).value.toLowerCase();
        const select = document.getElementById(selectId);
        const options = select.options;
        let hasVisibleOptions = false;
        for (let i = 1; i < options.length; i++) {
            const optionText = options[i].text.toLowerCase();
            if (input === '' || optionText.includes(input)) {
                options[i].style.display = '';
                hasVisibleOptions = true;
            } else {
                options[i].style.display = 'none';
            }
        }
        if (!hasVisibleOptions) {
            select.value = '';
        }
    }
</script>
