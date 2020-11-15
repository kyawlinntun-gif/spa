<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel SPA</title>

    {{-- app css --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>

    <div id="app">
        <product-component></product-component>

        <!-- set progressbar -->
        <vue-progress-bar></vue-progress-bar>
    </div>

    {{-- app js --}}
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>