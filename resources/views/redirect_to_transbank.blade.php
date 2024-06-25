<!DOCTYPE html>
<html>
<head>
    <title>Redireccionando a Transbank...</title>
</head>
<body>
    <form id="transbank_form" action="{{ $url }}" method="POST">
        <input type="hidden" name="token_ws" value="{{ $token }}">
    </form>
    <script type="text/javascript">
        document.getElementById('transbank_form').submit();
    </script>
</body>
</html>
