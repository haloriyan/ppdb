@extends('layouts.page')

@section('title', $news->title)

@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp

@section('content')
<div class="flex flex-col gap-4 items-center">
    <div class="w-7/12 mobile:w-full p-20 mobile:p-10 flex flex-col gap-4">
        <img 
            src="{{ asset('storage/news_images/' . $news->featured_image) }}" 
            alt="{{ $news->title}}"
            class="w-full aspect-video object-cover rounded-lg bg-gray-100"
        >
        <h1 class="text-3xl font-bold text-slate-700">{{ $news->title }}</h1>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 text-slate-500">
                <ion-icon name="person-outline"></ion-icon>
                <div class="text-xs">{{ $news->admin->name }}</div>
            </div>
            <div class="flex items-center gap-2 text-slate-500 grow justify-center">
                <ion-icon name="eye-outline"></ion-icon>
                <div class="text-xs">{{ $news->hit }} tayangan</div>
            </div>
            <div class="flex items-center gap-2 text-slate-500 ">
                <ion-icon name="calendar-outline"></ion-icon>
                <div class="text-xs">{{ Carbon::parse($news->created_at)->isoFormat('DD MMMM Y') }}</div>
            </div>
        </div>

        <div class="mt-4 leading-8 text-slate-600">{!! $news->content !!}</div>
    </div>
</div>
@endsection