@extends('layouts.admin')

@section('title', "Kupon Diskon")

@php
    use Carbon\Carbon;
@endphp

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

@section('header.right')
<form class="border flex items-center pe-4 desktop:w-5/12">
    <input type="text" name="q" class="w-full h-12 outline-0 px-4 text-xs text-slate-600" placeholder="Cari kode kupon atau gelombang" value="{{ $request->q }}">
    @if ($request->q == "")
        <ion-icon name="search-outline"></ion-icon>
    @else
        <a href="{{ route('admin.coupon') }}">
            <ion-icon name="close-outline" class="text-red-500"></ion-icon>
        </a>
    @endif
</form>
@endsection
    
@section('content')
<div class="p-10 flex flex-col gap-10">
    <div class="flex items-center gap-4">
        <div class="flex flex-col gap-2 grow">
            @include('components.breadcrumb', ['items' => [
                [route('admin.dashboard'), 'Dashboard'],
                ['#', 'Kupon Diskon']
            ]])
        </div>
        <button class="font-medium bg-primary text-white p-3 px-6 flex items-center gap-4" onclick="toggleHidden('#create')">
            <ion-icon name="add-outline"></ion-icon>
            <div class="text-xs">Buat Kupon</div>
        </button>
    </div>

    <div class="flex mobile:flex-col gap-10 mobile:gap-6">
        <div class="bg-white rounded-lg shadow-lg p-10 flex flex-col grow gap-4">
            <div class="text-4xl text-slate-700 font-bold">{{ $bookings_with_coupon->count() }}</div>
            <div class="text-xs text-slate-600">Kupon Digunakan</div>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-10 flex flex-col grow gap-4">
            <div class="text-4xl text-slate-700 font-bold">{{ currency_encode($usage_amount, 'Rp', '.', 'Rp 0') }}</div>
            <div class="text-xs text-slate-600">Nominal Penggunaan Kupon</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-10">
        <div class="min-w-full overflow-hidden overflow-x-auto p-5">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="text-sm text-slate-700 bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left"></th>
                        <th scope="col" class="px-6 py-3 text-left">Kode</th>
                        <th scope="col" class="px-6 py-3 text-left">Gelombang</th>
                        <th scope="col" class="px-6 py-3 text-left">Nominal</th>
                        <th scope="col" class="px-6 py-3 text-left">Penggunaan</th>
                        <th scope="col" class="px-6 py-3 text-left">Berlaku Hingga</th>
                        <th scope="col" class="px-6 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($coupons as $coupon)
                        <tr class="bg-white border-b {{ $coupon->is_active ? '' : 'opacity-50' }}">
                            <td class="px-6 py-4 text-sm text-slate-700">
                                <a href="{{ route('admin.coupon.toggle', $coupon->id) }}" class="rounded-full w-14 p-1 flex {{ $coupon->is_active ? 'bg-green-500 justify-end' : 'bg-slate-200 justify-start' }}">
                                    <div class="h-6 aspect-square rounded-full bg-white"></div>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $coupon->code }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $coupon->wave_id == null ? '-' : $coupon->wave->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $coupon->type == "percentage" ? $coupon->amount . "%" : currency_encode($coupon->amount, 'Rp', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                <div class="w-full h-1 bg-slate-200 rounded-full mb-2">
                                    <div class="bg-primary h-1 rounded-full" style="width: {{ ($coupon->start_quantity - $coupon->quantity) / $coupon->start_quantity * 100 }}%"></div>
                                </div>
                                <div class="text-xs text-slate-500">{{ $coupon->start_quantity - $coupon->quantity }} dari {{ $coupon->start_quantity }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ Carbon::parse($coupon->valid_until)->isoFormat('D MMM Y - HH:mm') }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $coupons->links() }}</div>
    </div>
</div>
@endsection

@section('ModalArea')
<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="create">
    <form class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4" method="POST" action="{{ route('admin.coupon.store') }}">
    @csrf
        <div class="flex items-center gap-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Buat Kupon Diskon</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#create')"></ion-icon>
        </div>

        <div class="mt-4 text-slate-500 text-xs">Kode Kupon :</div>
        <div class="border flex items-center pe-4">
            <input type="text" name="code" id="code" class="w-full h-14 mobile:h-12 outline-0 text-sm text-slate-700 px-4" required>
            <ion-icon name="dice-outline" class="cursor-pointer" onclick="randomizeCode()"></ion-icon>
        </div>

        <div class="mt-4 text-slate-500 text-xs">Gelombang :</div>
        <select name="wave_id" class="border w-full h-14 px-4 text-sm text-slate-700 outline-0">
            <option value="">Semua Gelombang</option>
            @foreach ($waves as $wave)
                <option value="{{ $wave->id }}">{{ $wave->name }}</option>
            @endforeach
        </select>

        <div class="flex mobile:flex-col items-center gap-4 mobile:gap-2 mobile:items-start mt-4">
            <div class="mobile:w-full flex grow text-xs text-slate-500">Tipe Diskon</div>
            <select name="type" class="border h-14 px-4 text-sm text-slate-700 outline-0 mobile:w-full" onchange="changeType(this.value)">
                <option value="percentage">Persentase</option>
                <option value="amount">Nominal Uang</option>
            </select>
        </div>

        <div class="flex mobile:flex-col items-center gap-4 mobile:gap-2 mobile:items-start mt-4">
            <div class="mobile:w-full flex grow text-xs text-slate-500">Nominal Potongan</div>
            <div class="flex items-center gap-4 border px-4 mobile:w-full" id="AmountLabelArea">
                <input type="number" name="amount" class="h-14 mobile:h-12 outline-0 text-sm text-slate-700 mobile:w-full" required>
                <div class="text-slate-700 text-sm" id="AmountLabel">%</div>
            </div>
        </div>

        <div class="flex mobile:flex-col items-center gap-4 mobile:gap-2 mobile:items-start mt-4">
            <div class="mobile:w-full flex grow text-xs text-slate-500">Jumlah Ketersediaan</div>
            <input type="number" name="quantity" min="1" class="h-14 mobile:h-12 outline-0 text-sm text-slate-700 border px-4 mobile:w-full" required>
        </div>

        <div class="flex flex-col grow gap-2">
            <div class="text-slate-500 text-xs">Berlaku Hingga :</div>
            <div class="border flex items-center pe-4">
                <input type="text" name="valid_until" id="EndDate" class="w-full h-14 mobile:h-12 outline-0 text-sm text-slate-700 px-4" required>
                <ion-icon name="calendar-outline"></ion-icon>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t flex items-center gap-4 justify-end">
            <button class="text-sm bg-slate-200 p-3 px-6" type="button" onclick="toggleHidden('#create')">Batal</button>
            <button class="text-sm bg-primary p-3 px-6 text-white">Tambahkan</button>
        </div>
    </form>
</div>
@endsection

@section('javascript')
<script>
    const changeType = val => {
        if (val === "percentage") {
            select("#AmountLabelArea").classList.remove('flex-row-reverse');
            select("#AmountLabel").innerHTML = "%";
        } else {
            select("#AmountLabelArea").classList.add('flex-row-reverse');
            select("#AmountLabel").innerHTML = "Rp";
        }
    }

    flatpickr("#EndDate", {
        minDate: "{{ date('Y-m-d H:i:s') }}"
    });

    const randomizeCode = (target = '#create') => {
        let code = randomString(6);
        select(`${target} #code`).value = code.toUpperCase();
    }
</script>
@endsection