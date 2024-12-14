<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {!! json_encode(config('tailwind')) !!}
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        div, aside, header { transition: 0.4s; }
        body {
            font-family: "Poppins", sans-serif;
            font-style: normal;
            font-weight: 400;
        }
    </style>
    @yield('head')
</head>
<body>

<div class="fixed top-0 left-0 right-0 h-20 border-b bg-white z-20 px-20 mobile:px-8 flex items-center gap-4">
    {{-- <div class="h-12 aspect-square rounded-lg bg-gray-200"></div> --}}
    {!! logo() !!}
    <div class="desktop:hidden flex grow"></div>
    <div class="text-slate-600 font-medium flex grow mobile:hidden">{{ env('APP_NAME') }}</div>
    <a href="{{ route('student.auth') }}" class="border border-primary rounded-full p-3 px-6 text-sm mobile:text-xs text-primary hover:text-white hover:bg-primary">
        Daftar / Cek Pendaftaran
    </a>
</div>

<div class="absolute top-20 left-0 right-0">
    @yield('content')
</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>