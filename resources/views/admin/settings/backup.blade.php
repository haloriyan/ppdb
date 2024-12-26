@extends('layouts.admin')

@section('title', "Backup / Restore Data")
    
@section('content')
<div class="p-10 flex gap-10">
    <div class="bg-white rounded-lg shadow-lg p-10 flex flex-col grow gap-4 basis-72">
        <h2 class="text-xl text-slate-700 font-medium">Cadangkan Data</h2>
        <div class="text-sm text-slate-600">Unduh semua data yang terekam pada database dan Anda dapat memulihkannya sehingga dapat mencegah kehilangan data.</div>

        <div class="flex justify-end mt-4">
            <a href="{{ route('admin.backup') }}">
                <button class="bg-primary text-white text-sm font-medium p-3 px-6">Unduh Data</button>
            </a>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-lg p-10 flex flex-col grow gap-4 basis-72">
        <h2 class="text-xl text-slate-700 font-medium">Pulihkan Data</h2>
        <div class="text-sm text-slate-600">Unggah berkas ZIP yang diunduh saat pencadangan data.</div>
        <form action="{{ route('admin.restore') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" class="border rounded p-2 w-full text-sm" required>
            <div class="flex justify-end mt-4">
                <button class="bg-primary text-white text-sm font-medium p-3 px-6">Pulihkan</button>
            </div>
        </form>
    </div>
</div>
@endsection