@extends('layouts.admin')

@section('title', "Pengaturan Midtrans")

@section('subtitle')
    <div class="text-xs text-slate-500 mt-1">Untuk transaksi pembayaran online, virtual account, dan QRIS</div>
@endsection

@php
    $modes = ['SANDBOX', 'PRODUCTION'];
@endphp

@section('content')
<form action="{{ route('admin.settings.midtrans') }}" class="p-10" method="POST">
    @csrf

    <div class="flex items-center gap-4">
        <div class="flex grow">
            @include('components.breadcrumb', ['items' => [
                [route('admin.dashboard'), 'Dashboard'],
                [route('admin.settings.basic'), 'Pengaturan'],
                ['#', 'Midtrans'],
            ]])
        </div>
        <button class="bg-green-500 text-white p-3 px-6 rounded font-medium flex items-center gap-4 cursor-pointer {{ $request->tab == 'channel' ? 'hidden' : '' }}">
            <div class="text-sm">Simpan Perubahan</div>
        </button>
    </div>

    @if ($message != "")
        <div class="bg-green-100 text-green-500 text-sm p-4 rounded-lg mb-4 mt-4">
            {{ $message }}
        </div>
    @endif

    <input type="hidden" name="tab" value="{{ $request->tab }}">

    @if ($request->tab == "channel")
        <div class="bg-white shadow-lg rounded-lg p-10 flex flex-col gap-4 mt-10">
            <div class="bg-slate-100 rounded-lg p-2 flex">
                <a href="{{ route('admin.settings.midtrans') }}" class="flex grow py-4 rounded-lg text-sm justify-center {{ $request->tab == 'channel' ? '' : 'bg-white text-primary shadow font-medium'}}">
                    Konfigurasi
                </a>
                <a href="{{ route('admin.settings.midtrans', ['tab' => 'channel']) }}" class="flex grow py-4 rounded-lg text-sm justify-center {{ $request->tab == 'channel' ? 'bg-white text-primary shadow font-medium' : ''}}">
                    Channel Pembayaran
                </a>
            </div>

            <div class="flex flex-col gap-4">
                @foreach (config('midtrans') as $midtrans)
                    <div class="flex items-center gap-4">
                        <img src="{{ asset($midtrans['image']) }}" alt="{{ $midtrans['key'] }}" class="h-16 aspect-square rounded object-cover">
                        <div class="flex flex-col grow gap-1">
                            <div class="text-slate-700 font-medium text-sm">{{ $midtrans['name'] }}</div>
                            <div class="text-slate-500 font-medium text-xs">{{ $midtrans['description'] }}</div>
                        </div>
                        <a href="{{ route('admin.settings.midtrans.toggle', $midtrans['key']) }}" class="rounded-full w-14 p-1 flex {{ $midtrans['enable'] == true ? 'bg-green-500 justify-end' : 'bg-slate-200 justify-start' }}">
                            <div class="h-6 aspect-square rounded-full bg-white"></div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white shadow-lg rounded-lg p-10 flex flex-col gap-4 mt-10">
            <div class="bg-slate-100 rounded-lg p-2 flex">
                <a href="{{ route('admin.settings.midtrans') }}" class="flex grow py-4 rounded-lg text-sm justify-center {{ $request->tab == 'channel' ? '' : 'bg-white text-primary shadow font-medium'}}">
                    Konfigurasi
                </a>
                <a href="{{ route('admin.settings.midtrans', ['tab' => 'channel']) }}" class="flex grow py-4 rounded-lg text-sm justify-center {{ $request->tab == 'channel' ? 'bg-white text-primary shadow font-medium' : ''}}">
                    Channel Pembayaran
                </a>
            </div>
            <div class="flex items-center gap-10">
                <div class="text-xs text-slate-500 flex grow">Mode</div>
                <select name="mode" class="h-14 px-4 border rounded-lg text-sm outline-0 basis-56" required>
                    @foreach ($modes as $mode)
                        <option value="{{ $mode }}" {{ $mode == env('MIDTRANS_MODE') ? 'selected="selected"' : '' }}>{{ $mode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-10">
                <div class="text-xs text-slate-500 flex grow">Merchant ID</div>
                <input type="text" name="merchant_id" class="h-14 px-4 border rounded-lg text-sm bg-white outline-0 basis-96" value="{{ env('MIDTRANS_MERCHANT_ID') }}" required>
            </div>
            <div class="flex items-center gap-10">
                <div class="text-xs text-slate-500 flex grow basis-96">Server Key</div>
                <input type="text" name="server_key" class="h-14 px-4 border rounded-lg text-sm bg-white outline-0 w-full" value="{{ env('MIDTRANS_SERVER_KEY') }}" required>
            </div>
            <div class="flex items-center gap-10">
                <div class="text-xs text-slate-500 flex grow basis-96">Client Key</div>
                <input type="text" name="client_key" class="h-14 px-4 border rounded-lg text-sm bg-white outline-0 w-full" value="{{ env('MIDTRANS_CLIENT_KEY') }}" required>
            </div>
        </div>
    @endif
</div>
@endsection