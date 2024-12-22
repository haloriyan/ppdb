@extends('layouts.admin')

@section('title', "SMTP Email")

@php
    $toSave = ['MAIL_HOST', 'MAIL_PORT', 'MAIL_ENCRYPTION', 'MAIL_USERNAME', 'MAIL_PASSWORD'];
@endphp
    
@section('content')
<div class="p-10">
    @include('components.breadcrumb', ['items' => [
        [route('admin.dashboard'), 'Dashboard'],
        [route('admin.settings.basic'), 'Pengaturan'],
        ['#', 'SMTP Email'],
    ]])

    <form class="bg-white rounded-lg shadow-lg p-8 mt-8 flex flex-col gap-8" method="POST" action="{{ route('admin.settings.basic.save') }}">
        @csrf
        @foreach ($toSave as $item)
            <input type="hidden" name="to_save[]" value="{{ $item }}">
        @endforeach
        
        <div class="flex items-center gap-8">
            <div class="flex flex-col gap-1 grow">
                <div class="text-xs text-slate-500">SMTP Server Host</div>
                <input type="text" name="MAIL_HOST" class="h-14 w-full outline-0 px-4 text-sm text-slate-700 border" value="{{ env('MAIL_HOST') }}" required>
            </div>
            <div class="flex flex-col gap-1">
                <div class="text-xs text-slate-500">Port</div>
                <input type="text" name="MAIL_PORT" class="h-14 w-full outline-0 px-4 text-sm text-slate-700 border" value="{{ env('MAIL_PORT') }}" required>
            </div>
            <div class="flex flex-col gap-1">
                <div class="text-xs text-slate-500">Enkripsi</div>
                <input type="text" name="MAIL_ENCRYPTION" class="h-14 w-full outline-0 px-4 text-sm text-slate-700 border" value="{{ env('MAIL_ENCRYPTION') }}" required>
            </div>
        </div>
        <div class="flex items-center gap-8">
            <div class="flex flex-col gap-1 grow">
                <div class="text-xs text-slate-500">Alamat Email / Username</div>
                <input type="text" name="MAIL_USERNAME" class="h-14 w-full outline-0 px-4 text-sm text-slate-700 border" value="{{ env('MAIL_USERNAME') }}" required>
            </div>
            <div class="flex flex-col gap-1 grow">
                <div class="text-xs text-slate-500">Password</div>
                <input type="text" name="MAIL_PASSWORD" class="h-14 w-full outline-0 px-4 text-sm text-slate-700 border" value="{{ env('MAIL_PASSWORD') }}" required>
            </div>
        </div>

        <div class="flex justify-end">
            <button class="bg-green-500 text-white text-sm font-medium p-3 px-6">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection