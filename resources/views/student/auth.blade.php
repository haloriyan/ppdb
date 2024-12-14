@extends('layouts.auth_student')

@section('title', "Memulai")
    
@section('content')
<h1 class="text-3xl text-slate-700 font-medium">Masuk</h1>
<div class="text-slate-500 text-sm">Isi Nomor WhatsApp untuk Melanjutkan</div>
<form method="POST" action="{{ route('student.auth') }}">
    @csrf
    {{-- <input type="text" name="phone" class="w-full border px-4 h-14 rounded outline-0" required> --}}
    <div class="flex items-center gap-1 border rounded px-4 text-sm text-slate-600">
        <div>+62</div>
        <input type="text" name="phone" class="w-full h-14 outline-0" value="85159772902">
    </div>
    <div class="flex items-center gap-4">
        <div class="flex grow"></div>
        <button class="h-12 text-sm text-white bg-primary mt-4 px-6">Masuk</button>
    </div>
</form>
@endsection