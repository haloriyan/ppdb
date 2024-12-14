@extends('layouts.admin')

@section('title', 'Counter Angka Home')
    
@section('content')
<div class="p-10">
    <div class="bg-white rounded-lg shadow-lg p-10">
        <div class="flex items-center gap-4">
            <div class="flex grow">
                @include('components.breadcrumb', ['items' => [
                    [route('admin.dashboard'), 'Dashboard'],
                    ['#', 'Pengaturan'],
                    ['#', 'Counter Angka Home'],
                ]])
            </div>
            <span class="bg-primary text-white p-3 px-6 rounded font-medium flex items-center gap-4 cursor-pointer" onclick="toggleHidden('#create')">
                <ion-icon name="add-outline"></ion-icon>
                <div class="text-sm">Counter</div>
            </span>
        </div>

        @if ($message != "")
            <div class="bg-green-100 text-green-600 text-sm p-4 rounded-lg mt-8">
                {{ $message }}
            </div>
        @endif

        <div class="min-w-full overflow-hidden overflow-x-auto p-5 mt-8">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="text-sm text-slate-700 bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">Label</th>
                        <th scope="col" class="px-6 py-3 text-left">Angka</th>
                        <th scope="col" class="px-6 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($counters as $counter)
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $counter->label }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $counter->value }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700 flex gap-2">
                                <div class="bg-green-500 text-white text-sm p-3 px-5 cursor-pointer hover:bg-green-600" onclick="edit('{{ $counter }}')">
                                    <ion-icon name="create-outline"></ion-icon>
                                </div>
                                <div class="bg-red-500 text-white text-sm p-3 px-5 cursor-pointer hover:bg-red-600" onclick="del('{{ $counter }}')">
                                    <ion-icon name="trash-outline"></ion-icon>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('ModalArea')
<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="create">
    <form class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4" method="POST" action="{{ route('admin.settings.counter.store') }}">
        @csrf
        <div class="flex items-center gap-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Tambah Counter</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#create')"></ion-icon>
        </div>

        <div class="text-xs text-slate-500 mt-4">Label :</div>
        <input type="text" name="label" class="w-full h-14 border px-4 text-sm outline-0" placeholder="Contoh : Siswa Terdaftar" required>
        <div class="text-xs text-slate-500 mt-4">Angka :</div>
        <input type="number" name="value" class="w-full h-14 border px-4 text-sm outline-0" min="1" required>

        <div class="mt-6 pt-6 border-t flex items-center justify-end gap-4">
            <button class="bg-gray-200 text-slate-500 text-sm font-medium p-3 px-6" type="button" onclick="toggleHidden('#create')">Batal</button>
            <button class="bg-green-500 text-white text-sm font-medium p-3 px-6">Tambahkan</button>
        </div>
    </form>
</div>

<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="edit">
    <form class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4" method="POST" action="{{ route('admin.settings.counter.update') }}">
        @csrf
        <input type="hidden" name="id" id="id" required>
        <div class="flex items-center gap-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Edit Counter</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#edit')"></ion-icon>
        </div>

        <div class="text-xs text-slate-500 mt-4">Label :</div>
        <input type="text" id="label" name="label" class="w-full h-14 border px-4 text-sm outline-0" placeholder="Contoh : Siswa Terdaftar" required>
        <div class="text-xs text-slate-500 mt-4">Angka :</div>
        <input type="number" id="value" name="value" class="w-full h-14 border px-4 text-sm outline-0" min="1" required>

        <div class="mt-6 pt-6 border-t flex items-center justify-end gap-4">
            <button class="bg-gray-200 text-slate-500 text-sm font-medium p-3 px-6" type="button" onclick="toggleHidden('#edit')">Batal</button>
            <button class="bg-green-500 text-white text-sm font-medium p-3 px-6">Simpan Perubahan</button>
        </div>
    </form>
</div>

<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="delete">
    <form class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4" method="POST" action="{{ route('admin.settings.counter.delete') }}">
        @csrf
        <input type="hidden" name="id" id="id" required>
        <div class="flex items-center gap-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Hapus Counter</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#delete')"></ion-icon>
        </div>

        <div class="text-sm text-slate-600 mt-4">
            Yakin ingin menghapus counter <span id="name"></span>?
        </div>

        <div class="mt-6 pt-6 border-t flex items-center justify-end gap-4">
            <button class="bg-gray-200 text-slate-500 text-sm font-medium p-3 px-6" type="button" onclick="toggleHidden('#delete')">Batal</button>
            <button class="bg-red-500 text-white text-sm font-medium p-3 px-6">Hapus</button>
        </div>
    </form>
</div>
@endsection

@section('javascript')
<script>
    const del = data => {
        data = JSON.parse(data);
        toggleHidden('#delete');
        select("#delete #id").value = data.id;
        select("#delete #name").innerHTML = data.label;
    }
    const edit = data => {
        data = JSON.parse(data);
        toggleHidden('#edit');
        select("#edit #id").value = data.id;
        select("#edit #label").value = data.label;
        select("#edit #value").value = data.value;
    }
</script>
@endsection