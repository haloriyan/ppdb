@extends('layouts.page')

@section('title', "Home")

@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp
    
@section('content')
<div class="p-10 flex items-center justify-center py-[180px]">
    <div class="w-5/12 mobile:w-full flex flex-col items-center justify-center gap-8">
        <div class="h-2 bg-primary rounded-full w-2/12"></div>
        <h1 class="text-3xl text-center text-slate-700 font-medium leading-[48px]">{{ env('JUMBO_TITLE') }}</h1>
        <div class="flex mobile:flex-col gap-8 items-center">
            <a href="{{ route('student.auth') }}" class="p-3 px-6 rounded-full border border-primary font-medium text-sm text-primary">
                Cek Pendaftaran
            </a>
            <a href="{{ route('student.auth') }}" class="p-3 px-6 rounded-full border border-primary font-medium text-sm bg-primary text-white">
                Daftar Sekarang
            </a>
        </div>
    </div>
</div>

{{-- ABOUT AREA --}}
<div class="p-20 mobile:p-10 flex mobile:flex-col gap-20 mobile:gap-10 items-center">
    <div class="flex flex-col grow gap-4">
        <h3 class="text-xl font-medium text-slate-700">Tentang</h3>
        <div class="w-24 h-1 bg-primary mb-6"></div>
        <div class="text-sm text-slate-500 leading-8">{{ env('ABOUT') }}</div>
    </div>
    {!! logo(256, '5xl') !!}
</div>

{{-- NUMBERS AREA --}}
<div class="p-20 mobile:p-10 flex mobile:flex-col gap-10 mobile:gap-6">
    @foreach ($counters as $c => $counter)
        <div class="flex flex-col gap-4 grow items-center justify-center mobile:pb-10 {{ $c != count($counters) ? 'desktop:border-e' : ''}}">
            <div class="text-4xl text-primary font-bold">{{ $counter->value }}</div>
            <div class="text-sm text-slate-600">{{ $counter->label }}</div>
        </div>
    @endforeach
</div>

{{-- WAVES AREA --}}
<div class="p-20 mobile:p-10 flex flex-col gap-8">
    <h3 class="text-xl font-medium text-slate-700">Gelombang</h3>
    <div class="w-24 h-1 bg-primary mb-6"></div>
    <div class="flex mobile:flex-col gap-10 mobile:gap-6">
        @foreach ($waves as $wave)
            <div class="flex flex-col grow desktop:basis-80 border p-10 rounded-lg">
                <div class="text-lg font-medium text-slate-700">{{ $wave->name }}</div>
                <div class="flex items-center gap-4 mt-4">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <div class="text-xs text-slate-500">{{ Carbon::parse($wave->start_date)->isoFormat('DD MMM Y') }}</div>
                </div>
                <div class="flex items-center gap-4 mt-4">
                    <ion-icon name="people-outline"></ion-icon>
                    <div class="text-xs text-slate-500">Sisa kuota : {{ $wave->quantity }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- NEWS AREA --}}
<div class="p-20 mobile:p-10 flex flex-col items-center gap-8">
    <h3 class="text-xl font-medium text-slate-700">Berita & Pengumuman</h3>
    <div class="w-24 h-1 bg-primary mb-6"></div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($news as $item)
            <a href="{{ route('news.read', $item->slug) }}" class="flex flex-col gap-2 p-4 border rounded-lg relative">
                <img 
                    src="{{ asset('storage/news_images/' . $item->featured_image) }}" 
                    alt="{{ $item->title}}"
                    class="w-full object-cover aspect-video rounded-lg bg-gray-100"
                >
                <h4 class="font-medium text-sm text-slate-600">{{ $item->title }}</h4>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <div>{{ Carbon::parse($item->created_at)->diffForHumans() }}</div>
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <ion-icon name="person-outline"></ion-icon>
                    <div>{{ $item->admin->name }}</div>
                </div>
                <div class="absolute top-2 right-2 flex items-center gap-4">
                    <div class="flex items-center gap-2 bg-primary text-white p-1 px-2 rounded">
                        <ion-icon name="calendar-outline"></ion-icon>
                        <div class="text-xs">{{ Carbon::parse($item->created_at)->isoFormat('DD MMMM Y') }}</div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>    
</div>

@include('components.footer')
@endsection