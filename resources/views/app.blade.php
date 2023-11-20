<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="asset-url" content="{{ env('APP_URL') }}">
    <title>Murah Mewah</title>
    <link id="theme-css" rel="stylesheet" type="text/css" href="/themes/lara-light-purple/theme.css">
    @routes()
    @vite('resources/js/app.js')
    @inertiaHead
</head>

<body>
    <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    @inertia
</body>

</html>
