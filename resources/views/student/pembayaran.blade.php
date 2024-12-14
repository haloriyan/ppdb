@extends('layouts.student')

@section('title', "Pembayaran")
    
@section('content')
<div class="fixed top-0 left-0 right-0 flex items-center justify-center bg-white border-b z-20">
    <div class="flex items-center gap-4 h-20 w-4/12 mobile:w-full mobile:px-8 bg-white">
        <a href="{{ route('student.dashboard') }}" class="h-12 aspect-square rounded-full bg-white text-slate-700 flex items-center justify-center">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
        <div class="flex flex-col grow">
            <div class="text-sm text-slate-700 font-medium">Pembayaran</div>
            <div class="text-xs text-slate-500 mt-1">{{ $booking->wave->name }}</div>
        </div>
    </div>
</div>

<div class="absolute top-32 left-0 right-0 flex justify-center">
    <div class="w-4/12 mobile:w-full mobile:px-8 pb-36">
        @if ($errors->count() > 0)
            @foreach ($errors->all() as $err)
            <div class="bg-red-100 text-red-500 text-sm p-4 rounded-lg mb-8">
                {{ $err }}
            </div>
            @endforeach
        @endif

        @if ($message != "")
            <div class="bg-green-100 text-green-500 text-sm p-4 rounded-lg mb-8">
                {{ $message }}
            </div>
        @endif

        <div class="p-8 rounded-lg shadow-lg bg-white flex items-center gap-4">
            <div class="flex flex-col grow gap-1">
                <div class="text-xs text-slate-500">Nominal</div>
                <div class="text-lg text-primary font-medium">{{ currency_encode($booking->total_pay, 'Rp', '.') }}</div>

                @if ($booking->coupon_id != null)
                    <div>
                        <div class="flex items-center gap-4 mt-2">
                            <div class="text-xs text-slate-500 fkex grow">Kode promo <b>{{ $booking->coupon->code }}</b> digunakan</div>
                            <div class="text-slate-700 text-xs font-bold">
                                @if ($booking->coupon->type == "percentage")
                                    -{{ $booking->coupon->amount }}% (
                                        {{ currency_encode(
                                            $booking->coupon->amount / 100 * $booking->wave->price, 'Rp', '.'
                                        ) }}
                                    )
                                @else
                                    -{{ currency_encode($booking->coupon->amount, 'Rp', '.') }}
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <a href="{{ route('student.discardCoupon') }}" class="text-xs text-red-500 underline">Jangan gunakan kupon</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if ($booking->coupon_id == null)
            <div class="flex justify-end mt-4 text-xs text-slate-500 gap-2">
                Punya kode promo?
                <span class="cursor-pointer text-primary" onclick="toggleHidden('#useCoupon')">Gunakan</span>
            </div>
        @endif
        
        <div class="text-xs text-slate-500 mt-10">Metode Pembayaran</div>
        <div class="flex flex-col gap-4 mt-4">
            @foreach (config('midtrans') as $item)
                @if ($item['enable'])
                    <div class="p-8 mobile:p-6 rounded-lg shadow-lg bg-white flex items-center gap-4">
                        <img src="{{ asset($item['image']) }}" alt="{{ $item['key']}}" class="h-12 mobile:h-14 object-cover">
                        <div class="flex flex-col gap-1 grow">
                            <div class="text-slate-700 font-medium mobile:text-sm">{{ $item['name'] }}</div>
                            <div class="text-slate-500 text-xs mobile:hidden">{{ $item['description'] }}</div>
                        </div>
                        <a href="{{ route('student.pay', $item['key']) }}" class="bg-primary text-white text-sm mobile:text-xs font-medium rounded-lg p-3 mobile:p-2 px-6 mobile:px-4">Bayar</a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="useCoupon">
    <form class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4" method="POST" action="{{ route('student.useCoupon') }}">
        @csrf
        <div class="flex items-center gap-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Gunakan Kupon Potongan Harga</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#useCoupon')"></ion-icon>
        </div>

        <div class="border rounded p-2 flex items-center gap-4 mt-8">
            <input type="text" name="code" class="w-full h-10 text-sm outline-0" placeholder="Kode kupon">
            <button class="text-white text-xs font-medium bg-primary p-3 px-6 rounded">Gunakan</button>
        </div>
        <div class="text-xs text-slate-500 mt-4">Masukkan Kode Kupon untuk mendapatkan potongan harga</div>
    </form>
</div>
@endsection