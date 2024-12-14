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

@if (@$isUsingHeader == true)
    <div class="fixed top-0 left-0 right-0 flex items-center justify-center bg-white border-b z-20">
        <div class="flex items-center gap-4 h-20 w-4/12 mobile:w-full mobile:px-8 bg-white">
            <div class="h-12 aspect-square rounded-full bg-primary text-white flex items-center justify-center">
                {{ initial($me->name) }}
            </div>
            <div class="flex flex-col grow">
                <div class="text-sm text-slate-700 font-medium">{{ $me->name }}</div>
                <div class="text-xs text-slate-500 mt-1">+62{{ $me->phone }}</div>
            </div>
            <a href="{{ route('student.logout') }}" class="text-primary text-xl">
                <ion-icon name="log-out-outline"></ion-icon>
            </a>
        </div>
    </div>
@endif
    
@yield('content')

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script>
    const select = dom => document.querySelector(dom);
    const selectAll = dom => document.querySelectorAll(dom);
    const toggleHidden = target => {
        select(target).classList.toggle('hidden');
    }
</script>
@yield('javascript')

</body>
</html>