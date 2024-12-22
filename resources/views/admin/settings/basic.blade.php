@extends('layouts.admin')

@section('title', "Pengaturan Dasar")

@php
    $toSave = ['APP_NAME', 'ABOUT', 'JUMBO_TITLE'];
@endphp

@section('content')
<div class="p-10">
    @include('components.breadcrumb', ['items' => [
        [route('admin.dashboard'), 'Dashboard'],
        [route('admin.settings.basic'), 'Pengaturan'],
        ['#', 'Dasar'],
    ]])

    <div class="bg-white rounded-lg shadow-lg p-8 mt-8">
        <form action="{{ route('admin.settings.basic.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @foreach ($toSave as $item)
                <input type="hidden" name="to_save[]" value="{{ $item }}">
            @endforeach

            <div class="flex items-center gap-4">
                <div class="text-xs text-slate-500 flex grow">Logo</div>
                <div class="relative flex flex-col items-center justify-center gap-2">
                    {!! logo(96, 'xl', 'logoPreview') !!}
                    <input type="file" id="logo" name="logo" class="absolute top-0 left-0 right-0 bottom-0 cursor-pointer opacity-0" onchange="onChangeImage(this)">
                    <div class="text-xs text-slate-500 text-center">Klik gambar<br />untuk mengganti logo</div>
                </div>
            </div>
            <div class="text-slate-500 text-xs mt-6">Nama Sekolah :</div>
            <input type="text" name="APP_NAME" value="{{ env('APP_NAME') }}" class="h-14 w-full outline-0 border px-4 mt-2" required>
            <div class="text-slate-500 text-xs mt-6">Tentang Sekolah :</div>
            <textarea name="ABOUT" class="w-full border outline-0 p-4 mt-2" rows="8">{{ env('ABOUT') }}</textarea>
            <div class="text-slate-500 text-xs mt-6">Jumbo Title :</div>
            <input type="text" name="JUMBO_TITLE" value="{{ env('JUMBO_TITLE') }}" class="h-14 w-full outline-0 border px-4 mt-2" required>

            <div class="flex items-center gap-4 justify-end pt-8 mt-8 border-t">
                <button class="text-sm text-white bg-green-500 p-3 px-6 font-medium">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('javascript')
<script>
    const onChangeImage = input => {
        let file = input.files[0];
        let reader = new FileReader();
        let imagePreview = select("#logoPreview");

        reader.onload = function () {
            let source = reader.result;
            if (imagePreview.tagName === "DIV") {
                imagePreview.style.backgroundImage = `url(${source})`;
                imagePreview.style.backgroundSize = "cover";
                imagePreview.style.backgroundPosition = "center center";
                imagePreview.innerHTML = "";
            } else {
                imagePreview.setAttribute('src', source);
            }
        }

        reader.readAsDataURL(file);
    }
</script>
@endsection