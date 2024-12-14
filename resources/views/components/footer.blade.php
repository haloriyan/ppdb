@php
    use Carbon\Carbon;
@endphp

<div class="p-20 mobile:p-10 border-t flex mobile:flex-col gap-10">
    <div class="flex flex-col gap-2 grow">
        <div class="flex">
            {!! logo(64) !!}
        </div>
        <div class="text-slate-700 font-medium mt-4">{{ env('APP_NAME') }}</div>
        <div class="text-sm text-slate-500">{{ env('ADDRESS') }}</div>
    </div>
    <div class="flex flex-col gap-2">
        <div class="text-slate-700 font-medium">Kontak</div>
        <div class="flex flex-col gap-4 mt-4">
            <a href="tel:{{ env('EMAIL') }}" class="flex items-center gap-4">
                <div class="h-8 aspect-square border border-slate-300 rounded-lg flex items-center justify-center">
                    <ion-icon name="call-outline"></ion-icon>
                </div>
                <div class="text-sm text-slate-500">{{ env('PHONE') }}</div>
            </a>
            <a href="mailto:{{ env('EMAIL') }}" class="flex items-center gap-4">
                <div class="h-8 aspect-square border border-slate-300 rounded-lg flex items-center justify-center">
                    <ion-icon name="mail-outline"></ion-icon>
                </div>
                <div class="text-sm text-slate-500">{{ env('EMAIL') }}</div>
            </a>
        </div>
    </div>
</div>