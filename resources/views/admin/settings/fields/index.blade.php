@extends('layouts.admin')

@section('title', "Isian Form Siswa")

@section('content')
<div class="p-10">
    @if ($message != "")
        <div class="bg-green-100 text-green-500 text-sm p-4 rounded-lg mb-4">
            {{ $message }}
        </div>
    @endif
    <div class="flex items-center gap-4">
        <div class="flex grow">
            @include('components.breadcrumb', ['items' => [
                [route('admin.dashboard'), 'Dashboard'],
                ['#', 'Pengaturan'],
                ['#', 'Isian Form'],
            ]])
        </div>
        <span class="bg-primary text-white p-3 px-6 rounded font-medium flex items-center gap-4 cursor-pointer" onclick="toggleHidden('#create')">
            <ion-icon name="add-outline"></ion-icon>
            <div class="text-sm">Isian Baru</div>
        </span>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-10 mt-10">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="p-2">Label</th>
                    <th class="p-2">Tipe</th>
                    <th class="p-2">Wajib Diisi?</th>
                    <th class="p-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fields as $item)
                    @php
                        $isRequired = $item->required == 1;
                    @endphp
                    <tr>
                        <td class="p-2 text-slate-700">
                            <div>{{ $item->label }}</div>
                        </td>
                        <td class="p-2 text-slate-700">
                            {{ config('fieldTypes')[$item->type] }}
                            @if ($item->options != "")
                                <div class="text-xs text-slate-500 mt-1">{{ implode(", ", explode("||", $item->options)) }}</div>
                            @endif
                        </td>
                        <td class="p-2 text-slate-700">
                            {{-- {{ $item->required == 1 ? 'Ya' : 'Tidak' }} --}}
                            <div class="flex items-center gap-4">
                                <div class="text-xs {{ !$isRequired ? 'text-primary font-medium' : 'text-slate-500' }}">Tidak</div>
                                <a href="{{ route('admin.settings.field.required', $item->id) }}" class="rounded-full w-14 p-1 flex {{ $isRequired ? 'bg-green-500 justify-end' : 'bg-slate-200 justify-start' }}">
                                    <div class="h-6 aspect-square rounded-full bg-white"></div>
                                </a>
                                <div class="text-xs {{ $isRequired ? 'text-primary font-medium' : 'text-slate-500' }}">Ya</div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('ModalArea')
<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="create">
    <form class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4" method="POST" action="{{ route('admin.settings.field.store') }}">
        @csrf
        <h3 class="text-lg text-slate-700 font-medium">Kolom Isian Baru</h3>

        <div class="text-xs text-slate-500 mt-2">Label</div>
        <input type="text" name="label" id="label" class="w-full h-12 outline-0 border px-4 text-sm" required>
        
        <div class="text-xs text-slate-500 mt-2">Jenis</div>
        <div class="w-full border px-4">
            <select name="type" id="type" class="w-full text-sm h-12 outline-0 bg-white" onchange="changeType(this)">
                @foreach (config('fieldTypes') as $key => $item)
                    <option value="{{ $key }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>

        <div class="hidden" id="OptionArea">
            <div id="OptionAreaRender">
                <div class="text-xs text-slate-500">Opsi</div>
                <input type="text" name="options[]" class="w-full outline-0 h-12 border px-6 mt-2">
            </div>
            <div class="flex justify-end mt-4">
                <div class="text-xs cursor-pointer text-primary" onclick="addOption()">Tambahkan Opsi</div>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-2">
            <div class="text-slate-500 text-sm flex grow">Wajib diisi?</div>
            <select name="required" id="required" class="border outline-0 h-12 px-4">
                <option value="1">YA</option>
                <option value="0">TIDAK</option>
            </select>
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
    const changeType = input => {
        let val = input.value;
        if (val === "SELECT") {
            select("#OptionArea").classList.remove('hidden');
        } else {
            select("#OptionArea").classList.add('hidden');
        }
    }
    const addOption = () => {
        let input = document.createElement('input');
        input.setAttribute('name', 'options[]');
        input.classList.add('w-full', 'outline-0', 'h-12', 'border', 'px-6', 'mt-2');
        select("#OptionAreaRender").appendChild(input);
    }
</script>
@endsection