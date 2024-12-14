@extends('layouts.auth')

@section('title', "Login Administrator -")
    
@section('content')
<form action="{{ route('admin.login') }}" method="POST" class="w-4/12 bg-white p-10 flex flex-col gap-4">
    @csrf
    <input type="hidden" name="r" value="{{ $request->r }}">
    <h1 class="text-2xl font-bold text-slate-700 w-full text-left mb-4">Login Administrator</h1>
    <div class="text-xs text-slate-500">Username</div>
    <input type="text" name="username" class="w-full border px-5 h-14 outline-0" value="admin" required>
    <div class="text-xs text-slate-500">Password</div>
    <input type="password" name="password" class="w-full border px-5 h-14 outline-0" value="123456" required>

    @if ($errors->count() > 0)
        @foreach ($errors->all() as $err)
            <div class="bg-red-100 text-red-500 text-sm p-4 mt-2">
                {{ $err }}
            </div>
        @endforeach
    @endif

    @if ($message != "")
    <div class="bg-green-100 text-green-500 text-sm p-4 mt-2">
        {{ $message }}
    </div>
    @endif

    <button class="bg-primary text-white font-medium w-full h-14 mt-4">Login</button>
</form>
@endsection