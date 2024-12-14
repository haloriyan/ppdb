@extends('layouts.student')

@section('title', "Pilih Gelombang")

@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp
    
@section('content')
<div class="p-20 mobile:p-10">
    <h1 class="text-3xl text-slate-700 font-bold">Pilih Gelombang</h1>

    @if ($errors->count() > 0)
        @foreach ($errors->all() as $err)
            <div class="bg-red-100 text-red-500 text-sm rounded-lg p-4 mt-8">
                {{ $err }}
            </div>
        @endforeach
    @endif

    <div class="flex flex-wrap gap-10 mobile:gap-4 mt-10">
        @foreach ($waves as $wave)
            <div class="p-10 rounded-lg shadow-lg bg-white gelombang-item cursor-pointer flex flex-col grow basis-96" onclick="chooseGelombang(this, '{{ $wave->id }}')">
                <h3 class="text-xl text-slate-500 font-medium">{{ $wave->name }}</h3>
                <div class="flex flex-col gap-4 mt-6">
                    <div class="flex items-center gap-4">
                        <ion-icon name="cash-outline" class="text-lg"></ion-icon>
                        <div class="text-xs text-slate-600">{{ currency_encode($wave->price, 'Rp', '.') }}</div>
                    </div>
                    <div class="flex items-center gap-4">
                        <ion-icon name="calendar-outline" class="text-lg"></ion-icon>
                        <div class="text-xs text-slate-600">
                            {{ Carbon::parse($wave->start_date)->isoFormat('DD MMM Y') }} -
                            {{ Carbon::parse($wave->end_date)->isoFormat('DD MMM Y') }}
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <ion-icon name="people-outline" class="text-lg"></ion-icon>
                        <div class="text-xs text-slate-600">{{ $wave->quantity }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <form action="{{ route('student.pilihGelombang') }}" class="flex justify-end mt-10 hidden" method="POST">
        @csrf
        <input type="hidden" name="wave_id" id="wave_id" required>
        <button class="font-medium text-white text-sm bg-primary p-4 px-8">Pilih</button>
    </form>
</div>
@endsection

@section('javascript')
<script>
const chooseGelombang = (item, waveID) => {
    selectAll(".gelombang-item").forEach(gel => {
        gel.classList.remove('bg-primary-transparent');
    })
    item.classList.toggle('bg-primary-transparent');
    select("form input#wave_id").value = waveID;
    select("form").classList.remove('hidden');
}
</script>
@endsection