<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> {{ env('APP_NAME') }} </title>
    <link rel="icon" href="#" type="image/x-icon">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @stack(config('optionbuilder.style_var'))
</head>

<body class="lara-admin">
    @yield(config('optionbuilder.section'))

    @stack(config('optionbuilder.script_var'))

</body>

</html>