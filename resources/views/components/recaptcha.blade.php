@if (! Auth::check())
    <script src="https://www.google.com/recaptcha/api.js"></script>

    <script>
        function onSubmit(token) {
            document.getElementById('{{ $formId }}').submit();
        }
    </script>
@endif
