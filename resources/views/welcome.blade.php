<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel SPA</title>

    {{-- app css --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <h1>Laravel SPA</h1>

    <div id="app">
        <example-component></example-component>
    </div>

    {{-- app js --}}
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>