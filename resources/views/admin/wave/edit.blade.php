@extends('layouts.admin')

@section('title', "Edit Gelombang")

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection
    
@section('content')
<div class="p-10">
    <div class="flex items-center gap-8">
        <a href="{{ route('admin.wave') }}">
            <ion-icon name="arrow-back-outline" class="text-2xl"></ion-icon>
        </a>
        @include('components.breadcrumb', ['items' => [
            [route('admin.dashboard'), 'Dashboard'],
            [route('admin.wave'), 'Gelombang'],
            ['#', 'Edit Gelombang']
        ]])
    </div>

    <form class="bg-white rounded-lg shadow-lg p-10 mt-8" method="POST" action="{{ route('admin.wave.update', $wave->id) }}">
        @csrf
        <div class="text-xs text-slate-500">Nama Gelombang</div>
        <input type="text" name="name" class="w-full outline-0 h-14 border text-sm px-6 mt-2" placeholder="Gelombang I - Jalur Umum {{ date('Y') }}" value="{{ $wave->name }}" required>

        <div class="text-xs text-slate-500 mt-8">Biaya</div>
        <input type="text" id="price_display" name="price_display" class="w-full outline-0 h-14 border text-sm px-6 mt-2" oninput="changePrice(this)" value="{{ currency_encode($wave->price, 'Rp', '.') }}" required>
        <input type="hidden" id="price" name="price" value="{{ $wave->price }}" required>

        <div class="flex items-center gap-4 mt-8">
            <div class="flex flex-col gap-2 grow">
                <div class="text-xs text-slate-500">Tanggal Mulai</div>
                <div class="flex items-center gap-4 px-4 border">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <input type="text" name="start_date" class="w-full outline-0 h-14 text-sm" id="start_date" value="{{ $wave->start_date }}" required readonly>
                </div>
            </div>
            <div class="flex flex-col gap-2 w-6/12" id="endDateArea">
                <div class="text-xs text-slate-500">Tanggal Berakhir</div>
                <div class="flex items-center gap-4 px-4 border">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <input type="text" name="end_date" class="w-full outline-0 h-14 text-sm" id="end_date" value="{{ $wave->end_date }}" required readonly>
                </div>
            </div>
        </div>
        
        <div class="text-xs text-slate-500 mt-8">Kuota</div>
        <div class="flex items-center gap-4">
            <input type="number" min="1" id="qty" name="quantity" class="w-full outline-0 h-14 border text-sm px-6 mt-2" value="{{ $wave->quantity }}" required>
            <div class="cursor-pointer h-14 px-8 border border-primary text-primary text-sm flex items-center justify-center" onclick="qty('dec')">
                <ion-icon name="remove-outline" class="text-lg"></ion-icon>
            </div>
            <div class="cursor-pointer h-14 px-8 border border-primary text-primary text-sm flex items-center justify-center" onclick="qty('inc')">
                <ion-icon name="add-outline" class="text-lg"></ion-icon>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-12">
            <a href="{{ route('admin.wave') }}" class="text-xs p-3 px-6 bg-slate-500 text-white">Kembali</a>
            <button class="text-xs p-3 px-6 font-medium bg-primary text-white">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@section('javascript')
<script>
    const qtyInput = select("input#qty");

    flatpickr("#start_date", {
        onChange: (e, d) => {
            flatpickr("#end_date", {
                minDate: d,
            });
            select("#endDateArea").classList.remove('hidden');
        }
    });

    flatpickr("#end_date", {
        minDate: "{{ $wave->start_date }}",
    });

    const qty = action => {
        let qty = parseInt(qtyInput.value);
        let newQty = 10;
        if (action == 'inc') {
            newQty = qty + 10;
        } else {
            if (qty > 10) {
                newQty = qty + 10;
            }
        }
        qtyInput.value = newQty;
    }

    const changePrice = input => {
        let val = Currency(input.value).decode();
        if (isNaN(val)) {
            val = 0;
        }
        input.value = Currency(val).encode();
        select("#price").value = val;
    
    }
</script>
@endsection