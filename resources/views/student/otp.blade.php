@extends('layouts.auth_student')

@section('title', "Memulai")
    
@section('content')
<h1 class="text-3xl text-slate-700 font-medium">Verifikasi</h1>
<div class="text-slate-500 text-sm">Masukkan 4 Digit Kode yang Telah dikirim</div>
<form method="POST" action="{{ route('student.otp') }}">
    @csrf
    <div class="flex flex-wrap items-center justify-center gap-8 mobile:gap-4">
        @for ($i = 0; $i < 4; $i++)
            <input type="text" name="code[]" max="1" id="code_{{ $i }}" class="code border text-center h-16 w-16" oninput="typeCode(this)" required />
        @endfor
    </div>

    @if ($message != "")
        <div class="bg-green-100 text-green-500 p-4 rounded mt-8 text-xs">
            {{ $message }}
        </div>
    @endif
    @if ($errors->count() > 0)
        @foreach ($errors->all() as $err)
            <div class="bg-red-100 text-red-500 p-4 rounded mt-8 text-xs">
                {{ $err }}
            </div>
        @endforeach
    @endif

    <div class="text-slate-500 text-xs flex items-center justify-center gap-2 mt-8">
        <div>Belum menerima kode?</div>
        <div id="resend_area">
            Tunggu 10 detik
        </div>
    </div>
</form>
@endsection

@section('javascript')
<script>
    let isSubmitting = false;
    let countdownValue = 10;

    const typeCode = input => {
        let val = input.value;
        let currentIndex = parseInt(input.getAttribute('id').split('_')[1]);
        
        if (val.length > 1) {
            input.value = val[0];
            select(`#code_${currentIndex + 1}`).focus();
        } else {
            if (isNaN(val)) {
                input.value = "";
            } else {
                select(`#code_${currentIndex + 1}`).focus();
            }
        }
    }

    setInterval(() => {
        let codes = [];
        let counter = 0;
        selectAll(".code").forEach(input => {
            codes.push(input.value);
            if (input.value !== "") {
                counter++;
            }
        });
        
        if (!isSubmitting && counter >= 4) {
            select("form").submit();
            isSubmitting = true;
        }
        
    }, 400);

    const countdownInterval = setInterval(() => {
        // Decrement the countdown value
        countdownValue--;

        // Upda#te the display with the new countdown value
        select("#resend_area").innerHTML = `Tunggu ${countdownValue} detik`;

        // Stop the countdown when it reaches 0
        if (countdownValue <= 0) {
            clearInterval(countdownInterval);
            select("#resend_area").innerHTML = "<a href='{{ route('student.resendOtp') }}' class='text-primary'>Kirim Ulang</a>"
        }
    }, 1000);
</script>
@endsection