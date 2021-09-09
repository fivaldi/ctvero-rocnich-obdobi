    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js"></script>
    <script>
        function onSubmit(token) {
            document.getElementById('{{ $formId }}').submit();
        }
    </script>
