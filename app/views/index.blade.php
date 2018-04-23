<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Shopping List</title>
        <link rel="stylesheet" type="text/css" href="dist/app.css">
        
    </head>
    <body ng-app="mainApp">
        <ng-view></ng-view>
        <script src="dist/app.js"></script>
    </body>
</html>
