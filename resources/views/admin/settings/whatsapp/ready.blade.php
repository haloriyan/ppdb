@extends('layouts.admin')

@section('title', "Pengaturan WhatsApp")

@section('content')
<input type="hidden" id="init_qr" value="{{ env('WA_CLIENT_NUMBER') == null }}">
<input type="hidden" id="client_id" value="{{ $client_id }}">

<div class="p-10 flex gap-10 mobile:flex-col mobile:gap-8">
    <div class="flex flex-col gap-4 items-center justify-center grow bg-white rounded-lg shadow-lg p-10">
        <div class="h-24 aspect-square rounded-full flex items-center justify-center bg-green-500 text-white text-5xl">
            <ion-icon name="checkmark-circle-outline"></ion-icon>
        </div>
        <h4 class="text-2xl text-slate-700 font-medium">WhatsApp telah terhubung!</h4>
        <button class="bg-red-500 text-white text-sm cursor-pointer font-medium p-3 px-6 mt-4" onclick="toggleHidden('#disconnect')">
            Putuskan Perangkat
        </button>
    </div>
    <div class="flex flex-col gap-4 grow bg-white rounded-lg shadow-lg p-10">
        <h4 class="text-2xl text-slate-700 font-medium">Informasi Perangkat</h4>

        <div class="flex items-center gap-4 mt-4">
            <div class="text-xs text-slate-500 flex grow">Nama</div>
            <div class="text-lg text-slate-700 font-medium">{{ env('WA_CLIENT_NAME') }}</div>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-xs text-slate-500 flex grow">Nomor Perangkat</div>
            <div class="text-lg text-slate-700 font-medium">{{ env('WA_CLIENT_NUMBER') }}</div>
        </div>
    </div>
</div>
@endsection

@section('ModalArea')
<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="disconnect">
    <form class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4" method="POST" action="{{ route('admin.settings.whatsapp.disconnect') }}">
        @csrf
        <input type="hidden" name="id" id="id" required>
        <div class="flex items-center gap-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Putuskan Perangkat</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#disconnect')"></ion-icon>
        </div>

        <div class="text-sm text-slate-600 mt-4">
            Yakin ingin menghapus memutuskan koneksi ke WhatsApp {{ env('WA_CLIENT_NAME') }}?
        </div>

        <div class="mt-6 pt-6 border-t flex items-center justify-end gap-4">
            <button class="bg-gray-200 text-slate-500 text-sm font-medium p-3 px-6" type="button" onclick="toggleHidden('#disconnect')">Batal</button>
            <a href="{{ route('admin.settings.whatsapp.disconnect') }}" class="bg-red-500 text-white text-sm font-medium p-3 px-6">Putuskan</a>
        </div>
    </form>
</div>
@endsection

@section('javascript')

@endsection