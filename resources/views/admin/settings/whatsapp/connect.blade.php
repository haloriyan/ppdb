@extends('layouts.admin')

@section('title', "Pengaturan WhatsApp")

@section('content')
<input type="hidden" id="init_qr" value="{{ env('WA_CLIENT_NUMBER') == null }}">
<input type="hidden" id="client_id" value="{{ $client_id }}">

@if (env('WA_CLIENT_NUMBER') == null)
    <div class="bg-white p-10 rounded-lg shadow-lg flex mobile:flex-col gap-8 m-10">
        <div class="h-80 aspect-square rounded-lg bg-slate-200" id="qrArea">

            <img src="{{ $init->qr }}" alt="QR Image" class="w-full aspect-square bg-white">
        </div>
        <div class="flex flex-col gap-4">
            <h3 class="text-2xl text-slate-700 font-medium">Cara Menghubungkan Perangkat</h3>
            <ul class="ps-4 mt-4 text-sm flex flex-col gap-2">
                <li class="list-disc text-slate-500">Pastikan koneksi ponsel stabil</li>
                <li class="list-disc text-slate-500">Buka aplikasi whatsapp di ponsel Anda</li>
                <li class="list-disc text-slate-500">Tekan titik tiga di pojok kanan atas</li>
                <li class="list-disc text-slate-500">Pilih <span class="font-bold">Perangkat Tertaut</span></li>
                <li class="list-disc text-slate-500">Klik tombol <span class="font-bold">Tautkan Perangkat</span></li>
                <li class="list-disc text-slate-500">Lakukan scan kode QR</li>
            </ul>

            <div class="flex items-center gap-4">
                <div class="text-sm text-slate-700">Perangkat WhatsApp sudah terhubung?</div>
                <a href="{{ route('admin.settings.whatsapp') }}" class="bg-primary text-white text-sm font-medium p-2 px-4">
                    Muat Ulang
                </a>
            </div>
        </div>
    </div>
@endif
@endsection

@section('javascript')
<script>
    const initQR = select("#init_qr").value;
    const init = async (client_id) => {
        try {
            console.log('init to : ', `{{ env('WA_URL') }}/qr/${client_id}`);
            
            const response = await fetch(`{{ env('WA_URL') }}/qr/${client_id}`);
            const data = await response.json();

            let image = document.createElement('img');
            image.src = data.qr_image;
            image.classList.add('w-full', 'aspect-square', 'bg-white');
            select("#qrArea").appendChild(image);

            console.log(data);
            
        } catch (e) {
            console.error('err conn');
            
        }
    }
    console.log(initQR);
    
    if (initQR == 1) {
        const client_id = select("#client_id").value;
        // init(client_id);
    }
</script>
@endsection