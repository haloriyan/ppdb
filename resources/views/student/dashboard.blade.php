@extends('layouts.student')

@section('title', "Dashboard")

@php
    $paymentPayload = json_decode($booking->payment_payload, false);
@endphp
    
@section('content')
<div class="absolute top-32 left-0 right-0 flex justify-center">
    <div class="w-4/12 mobile:w-full mobile:px-8 pb-24">
        <div class="p-8 rounded-lg shadow-lg bg-white">
            <div class="text-xs text-slate-500">Data Diri</div>
            {{-- <div class="text-slate-700 font-medium mt-2">{{ $me }}</div> --}}
            <div class="flex flex-col gap-4 mt-8">
                @foreach (json_decode(base64_decode($me->fields), false) as $field)
                    <div class="flex items-center gap-4">
                        <div class="text-xs text-slate-500 flex grow">{{ $field->label }}</div>
                        @if ($field->type != "FILE")
                            <div class="text-slate-700 font-medium mobile:text-sm">{{ $field->value }}</div>
                        @else
                            <a href="{{ asset('storage/pendaftaran_files/'.$me->id.'/' . $field->value) }}" class="text-primary font-medium mobile:text-sm" target="_blank">LIHAT</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <div class="p-8 rounded-lg shadow-lg bg-white mt-8">
            <div class="text-xs text-slate-500">Nomor Pendaftaran</div>
            <div class="text-slate-700 font-medium mt-2">{{ $booking->id }}</div>
            <div class="text-xs text-slate-500 mt-8">Gelombang Pendaftaran</div>
            <div class="text-slate-700 font-medium mt-2">{{ $booking->wave->name }}</div>
        </div>

        @if ($booking->payment_payload != null)
            <div class="p-8 rounded-lg shadow-lg bg-white mt-8">
                {{-- <div class="text-xs text-slate-500">Pembayaran</div> --}}
                <div class="flex flex-wrap gap-4">
                    <div class="flex flex-col gap-2 grow basis-48">
                        <div class="text-xs text-slate-500">Metode Pembayaran</div>

                        @foreach (config('midtrans') as $item)
                            @if ($item['payment_type'] == $booking->payment_method && $item['key'] == $booking->payment_channel)
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['key'] }}" class="h-10 aspect-square object-cover">
                                    @if ($booking->payment_method == "qris")
                                        <div class="text-slate-700">{{ $item['name'] }}</div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="flex flex-col gap-2 grow basis-48">
                        <div class="text-xs text-slate-500">Nominal Pembayaran</div>
                        <div class="text-lg text-slate-700 font-medium">{{ currency_encode($booking->total_pay, 'Rp', '.') }}</div>
                    </div>
                    <div class="flex flex-col gap-2 grow basis-48">
                        <div class="text-xs text-slate-500">{{ $booking->payment_method == "qris" ? "QRIS" : "No. Rekening Tujuan" }}</div>
                        @if ($booking->payment_method == "qris")
                            <img src="{{ $paymentPayload->actions[0]->url }}" alt="QRIS" class="w-full aspect-square cursor-pointer" onclick="toggleHidden('#showQR')">
                            <div class="text-xs text-center text-slate-500">Klik untuk memperbesar</div>
                        @endif
                        @if ($booking->payment_method == "bank_transfer")
                            <div class="flex items-center gap-4">
                                <div class="text-slate-700">{{ $paymentPayload->va_numbers[0]->va_number }}</div>
                                <ion-icon name="copy-outline" class="cursor-pointer" onclick="copyText('{{ $paymentPayload->va_numbers[0]->va_number }}')"></ion-icon>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col gap-2 grow basis-48">
                        <div class="text-xs text-slate-500">Status</div>
                        <div class="flex">
                            <div class="p-2 px-4 rounded-lg font-medium text-sm bg-{{ config('app')['status_colors'][$booking->payment_status] }}-100 text-{{ config('app')['status_colors'][$booking->payment_status] }}-700">
                                {{ strtoupper($booking->payment_status) }}
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="text-slate-700 font-medium mt-2">{{ json_encode($paymentPayload) }}</div> --}}
            </div>
        @endif


        <div class="fixed bottom-0 left-0 right-0 flex justify-center">
            <div class="w-4/12 mobile:w-full py-8 mobile:px-8">
                @if ($booking->payment_payload == null && $booking->total_pay > 0)
                    <a href="{{ route('student.pembayaran') }}">
                        <button class="w-full h-12 rounded-lg bg-primary text-white text-sm font-medium mt-8">
                            Bayar Pendaftaran
                        </button>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@if ($booking->payment_payload != null && $booking->payment_method == "qris")
<div class="fixed top-0 left-0 right-0 bottom-0 bg-black z-50 bg-opacity-75 flex items-center justify-center hidden" id="showQR">
    <img src="{{ $paymentPayload->actions[0]->url }}" alt="QRIS Full" class="h-96 aspect-square cursor-pointer" onclick="toggleHidden('#showQR')">
</div>
@endif
@endsection

@section('javascript')
<script>
    const copyText = toCopy => {
        navigator.clipboard.writeText(toCopy);
    }
</script>
@endsection