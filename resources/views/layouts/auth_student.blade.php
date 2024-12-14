<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
    
<div class="fixed top-0 right-0 left-0 bottom-0 flex">
    <div class="bg-white p-20 mobile:p-10 w-5/12 mobile:w-full flex flex-col gap-4 justify-center">
        @yield('content')
    </div>
    <div class="flex flex-col grow bg-primary mobile:hidden"></div>
</div>

<script>
    const select = dom => document.querySelector(dom);
    const selectAll = dom => document.querySelectorAll(dom);
</script>
@yield('javascript')

</body>
</html>