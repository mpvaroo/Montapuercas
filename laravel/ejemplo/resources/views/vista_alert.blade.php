<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <title>Document</title>
</head>
@php
    $color = 'red';
    $tipoComponente = 'alert2';
@endphp

<body>
    <x-alert :colortext="$color" colorbg="blue" class="text-pink-600 bg-pink-600">POOOOOOOOO
        <x-slot:caution>porropo </x-slot>
    </x-alert>
    <x-alert2 colorbg="blue">
        TITULO DE LA ALERTA
        <x-slot:entrada1>ENTRADA 1 </x-slot>
        <x-slot:entrada2>ENTRADA 2 </x-slot>
        <x-slot:entrada3>ENTRADA 3 </x-slot>
    </x-alert2>
    <x-dynamic-component :component="$tipoComponente" :colortext="$color" colorbg="blue">LETS GOOOOO
    </x-dynamic-component>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
