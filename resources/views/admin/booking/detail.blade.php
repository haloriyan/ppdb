@extends('layouts.admin')

@section('title', "Detail Pendaftaran")
    
@section('content')
<div class="p-10">
    <div class="flex gap-10 items-start">
        <div class="flex gap-8 grow bg-white rounded-lg shadow-lg p-10">
            <div class="h-20 bg-primary text-white text-2xl font-bold aspect-square rounded-full flex items-center justify-center">
                {{ initial($student->name) }}
            </div>
            <div class="flex flex-col gap-1 grow">
                <div class="text-xs text-slate-500">Nama Lengkap</div>
                <div class="text-slate-700 font-medium">{{ $student->name }}</div>

                <div class="text-xs text-slate-500 mt-4">No. WhatsApp</div>
                <a href="https://wa.me/62{{ $student->phone }}" target="_blank" class="text-primary underline font-medium">+62{{ $student->phone }}</a>

                @foreach ($tableCols as $col)
                    <div class="text-xs text-slate-500 mt-4">{{ $col['label'] }}</div>
                    @foreach ($fields as $item)
                        @if ($item->key == $col['key'])
                            @if ($item->type == "FILE")
                                <a href="{{ asset('storage/pendaftaran_files/'.$student->id.'/' . $item->value) }}" target="_blank" class="text-primary underline font-medium">{{ $item->value }}</a>
                            @else
                                <div class="text-slate-700 font-medium">{{ $item->value }}</div>
                            @endif
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
        <div class="flex flex-col w-5/12 gap-2 bg-white rounded-lg shadow-lg p-10">
            <div class="text-xs text-slate-500">Nomor Pendaftaran</div>
            @if ($student->booking == null)
                <div class="text-slate-700 font-medium">
                    Belum Mendaftar
                </div>
            @else
                <div class="text-slate-700 font-medium">
                    {{ $student->booking->id }}
                </div>
                <div class="text-xs text-slate-500 mt-4">Gelombang</div>
                <div class="text-slate-700 font-medium">
                    {{ $student->booking->wave->name }}
                </div>
                <div class="text-xs text-slate-500 mt-4">Status Pembayaran</div>
                <div class="flex">
                    <div class="p-2 px-4 rounded-lg font-medium text-sm bg-{{ config('app')['status_colors'][$student->booking->payment_status] }}-100 text-{{ config('app')['status_colors'][$student->booking->payment_status] }}-700">
                        {{ strtoupper($student->booking->payment_status) }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection