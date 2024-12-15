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
<body class="bg-slate-100">

<div class="fixed top-0 left-0 right-0 z-20 flex items-center">
    <div class="w-72 h-20 flex gap-4 items-center justify-center bg-white" id="LeftHeader">
        {{-- <img src="#" alt="Logo Heaedr" class="h-12 w-12 bg-slate-200 rounded-lg"> --}}
        {!! logo() !!}
        <h1 class="text-slate-700 font-bold text-sm">{{ env('APP_NAME') }}</h1>
    </div>
    <div class="bg-white h-20 flex items-center gap-4 grow px-10 border-b" id="header">
        <div class="h-12 aspect-square flex items-center justify-start cursor-pointer" onclick="toggleSidebar()">
            <ion-icon name="grid-outline"></ion-icon>
        </div>
        <div class="flex flex-col grow">
            <div class="text-xl font-bold text-slate-700">@yield('title')</div>
            @yield('subtitle')
        </div>
        @yield('header.right')
    </div>
</div>

<div class="fixed top-20 left-0 bottom-0 w-72 z-10 bg-white shadow p-4" id="sidebar">
    @php
        $routeName = Route::currentRouteName();
        $routes = explode(".", $routeName);
    @endphp
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 {{ $routeName == 'admin.dashboard' ? 'bg-primary-transparent text-primary' : 'text-slate-500' }}">
        <div class="h-12 w-1 {{ $routeName == 'admin.dashboard' ? 'bg-primary' : 'bg-white' }}"></div>
        <ion-icon name="home-outline"></ion-icon>
        <div class="text-sm flex">Dashboard</div>
    </a>
    <a href="{{ route('admin.news') }}" class="flex items-center gap-4 {{ $routes[1] == 'news' ? 'bg-primary-transparent text-primary' : 'text-slate-500' }}">
        <div class="h-12 w-1 {{ $routes[1] == 'news' ? 'bg-primary' : 'bg-white' }}"></div>
        <ion-icon name="create-outline"></ion-icon>
        <div class="text-sm flex">Berita</div>
    </a>
    <a href="{{ route('admin.wave') }}" class="flex items-center gap-4 {{ $routes[1] == 'wave' ? 'bg-primary-transparent text-primary' : 'text-slate-500' }}">
        <div class="h-12 w-1 {{ $routes[1] == 'wave' ? 'bg-primary' : 'bg-white' }}"></div>
        <ion-icon name="analytics-outline"></ion-icon>
        <div class="text-sm flex">Gelombang</div>
    </a>
    <a href="{{ route('admin.booking') }}" class="flex items-center gap-4 {{ $routes[1] == 'booking' ? 'bg-primary-transparent text-primary' : 'text-slate-500' }}">
        <div class="h-12 w-1 {{ $routes[1] == 'booking' ? 'bg-primary' : 'bg-white' }}"></div>
        <ion-icon name="people-outline"></ion-icon>
        <div class="text-sm flex">Pendaftaran</div>
    </a>
    <a href="{{ route('admin.coupon') }}" class="flex items-center gap-4 {{ $routes[1] == 'coupon' ? 'bg-primary-transparent text-primary' : 'text-slate-500' }}">
        <div class="h-12 w-1 {{ $routes[1] == 'coupon' ? 'bg-primary' : 'bg-white' }}"></div>
        <ion-icon name="pricetags-outline"></ion-icon>
        <div class="text-sm flex">Kupon Diskon</div>
    </a>
    <a href="{{ route('admin.admin') }}" class="flex items-center gap-4 {{ $routes[1] == 'admin' ? 'bg-primary-transparent text-primary' : 'text-slate-500' }}">
        <div class="h-12 w-1 {{ $routes[1] == 'admin' ? 'bg-primary' : 'bg-white' }}"></div>
        <ion-icon name="people-outline"></ion-icon>
        <div class="text-sm flex">Administrator</div>
    </a>
    <div class="group relative">
        <a href="#" class="flex items-center gap-4 text-slate-500 {{ $routes[1] == 'settings' ? 'bg-primary-transparent text-primary' : '' }}">
            <div class="h-12 w-1 {{ $routes[1] == 'settings' ? 'bg-primary' : 'bg-white' }}"></div>
            <ion-icon name="cog-outline" class="{{ $routes[1] == 'settings' ? 'text-primary' : '' }}"></ion-icon>
            <div class="text-sm flex grow {{ $routes[1] == 'settings' ? 'text-primary' : '' }}">Pengaturan</div>
            <ion-icon name="chevron-down-outline" class="me-4"></ion-icon>
        </a>
        <div class="{{ $routes[1] == 'settings' ? 'flex' : 'hidden' }} group-hover:flex flex-col mt-2">
            <a href="{{ route('admin.settings.basic') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <div class="text-sm flex grow {{ @$routes[2] == 'basic' ? 'text-primary' : '' }}">Dasar</div>
            </a>
            <a href="{{ route('admin.settings.counter') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <div class="text-sm flex grow {{ @$routes[2] == 'counter' ? 'text-primary' : '' }}">Counter Angka Home</div>
            </a>
            <a href="{{ route('admin.settings.field') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <div class="text-sm flex grow {{ @$routes[2] == 'field' ? 'text-primary' : '' }}">Isian Form Siswa</div>
            </a>
            <a href="{{ route('admin.settings.whatsapp') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <div class="text-sm flex grow {{ @$routes[2] == 'whatsapp' ? 'text-primary' : '' }}">WhatsApp</div>
            </a>
            <a href="{{ route('admin.settings.midtrans') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <div class="text-sm flex grow {{ @$routes[2] == 'midtrans' ? 'text-primary' : '' }}">Midtrans</div>
            </a>
        </div>
    </div>
</div>

<div class="absolute top-20 left-72 right-0 z-10" id="content">
    @yield('content')
</div>

@yield('ModalArea')

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script>
    const select = dom => document.querySelector(dom);
    const header = select("#header");
    const LeftHeader = select("#LeftHeader");
    const sidebar = select("#sidebar");
    const content = select("#content");
    // const ProfileMenu = select("#ProfileMenu");

    // const randomString = (length) => Array.from({ length }, () => Math.random().toString(36)[2]).join('');
    const randomString = (length) => Array.from({ length }, (_, i) => i < length / 2 ? String.fromCharCode(97 + Math.floor(Math.random() * 26)) : Math.floor(Math.random() * 10)).join('');

    const toggleSidebar = () => {
        LeftHeader.classList.toggle('w-0');
        
        if (sidebar.classList.contains('w-72')) {
            // close
            sidebar.classList.add('w-0');
            sidebar.classList.remove('w-72');
            content.classList.add('left-0');
            content.classList.remove('left-72');
            setTimeout(() => {
                sidebar.classList.add('hidden');
            }, 210);
        } else  {
            sidebar.classList.remove('hidden');
            sidebar.classList.add('w-72');
            content.classList.remove('left-0');
            content.classList.add('left-72');
            setTimeout(() => {
                sidebar.classList.remove('w-0');
            }, 10)
        }
    }
    const toggleHidden = target => {
        select(target).classList.toggle('hidden');
    }
    const Currency = (amount) => {
        let props = {};
        props.encode = (prefix = 'Rp') => {                                                               
            let result = '';                                                                              
            let amountRev = amount.toString().split('').reverse().join('');
            for (let i = 0; i < amountRev.length; i++) {
                if (i % 3 === 0) {
                    result += amountRev.substr(i,3)+'.';
                }
            }
            return prefix + ' ' + result.split('',result.length-1).reverse().join('');
        }
        props.decode = () => {
            return parseInt(amount.replace(/,.*|[^0-9]/g, ''), 10);
        }

        return props;
    }
</script>
@yield('javascript')

</body>
</html>