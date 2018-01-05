<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}"></script>
</head>
<body>
<div id="app">
    <div class="container">
        <router-view></router-view>
    </div>
</div>
</body>
<script src="{{ mix('js/app.js') }}"></script>
</html>