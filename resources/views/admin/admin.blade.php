@extends('layouts.admin')

@section('title', "User Administrator")
    
@section('header.right')
<form class="border flex items-center pe-4 desktop:w-5/12">
    <input type="text" name="q" class="w-full h-12 outline-0 px-4 text-xs text-slate-600" placeholder="Cari nama administrator" value="{{ $request->q }}">
    @if ($request->q == "")
        <ion-icon name="search-outline"></ion-icon>
    @else
        <a href="{{ route('admin.admin') }}">
            <ion-icon name="close-outline" class="text-red-500"></ion-icon>
        </a>
    @endif
</form>
@endsection

@section('content')
<div class="p-10">
    <div class="flex items-center gap-4">
        <div class="flex flex-col gap-2 grow">
            @include('components.breadcrumb', ['items' => [
                [route('admin.dashboard'), 'Dashboard'],
                ['#', 'Kupon Diskon']
            ]])
        </div>
        <button class="font-medium bg-primary text-white p-3 px-6 flex items-center gap-4" onclick="toggleHidden('#create')">
            <ion-icon name="add-outline"></ion-icon>
            <div class="text-xs">Admin Baru</div>
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-10 mt-10">
        @if ($message != "")
            <div class="bg-green-100 text-green-500 text-sm p-4 rounded-lg mb-4">
                {{ $message }}
            </div>
        @endif
        <div class="min-w-full overflow-hidden overflow-x-auto p-5">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="text-sm text-slate-700 bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left">Username</th>
                        <th scope="col" class="px-6 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($admins as $adm)
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $adm->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $adm->username }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700 flex gap-4">
                                <div class="bg-green-500 text-white p-2 px-4 cursor-pointer" onclick="edit('{{ $adm }}')">
                                    <ion-icon name="create-outline"></ion-icon>
                                </div>
                                @if (me()->id != $adm->id)
                                    <div class="bg-red-500 text-white p-2 px-4 cursor-pointer" onclick="del('{{ $adm }}')">
                                        <ion-icon name="trash-outline"></ion-icon>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-10">
                {{ $admins->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('ModalArea')
<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="create">
    <form class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4" method="POST" action="{{ route('admin.admin.store') }}">
        @csrf
        <div class="flex items-center gap-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Tambah Administrator</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#create')"></ion-icon>
        </div>

        <div class="text-xs text-slate-500 mt-4">Nama :</div>
        <input type="text" name="name" id="name" class="w-full h-14 text-sm px-8 border outline-0" required>
        <div class="text-xs text-slate-500 mt-2">Username :</div>
        <input type="text" name="username" id="username" class="w-full h-14 text-sm px-8 border outline-0" required>
        <div class="text-xs text-slate-500 mt-2">Password :</div>
        <input type="password" name="password" id="password" class="w-full h-14 text-sm px-8 border outline-0" required>

        <div class="mt-6 pt-6 border-t flex items-center gap-4 justify-end">
            <button class="text-sm bg-slate-200 p-3 px-6" type="button" onclick="toggleHidden('#create')">Batal</button>
            <button class="text-sm bg-primary p-3 px-6 text-white">Tambahkan</button>
        </div>
    </form>
</div>

<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="edit">
    <form class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4" method="POST" action="{{ route('admin.admin.update') }}">
        @csrf
        <input type="hidden" name="id" id="id">
        <div class="flex items-center gap-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Edit Administrator</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#edit')"></ion-icon>
        </div>

        <div class="text-xs text-slate-500 mt-4">Nama :</div>
        <input type="text" name="name" id="name" class="w-full h-14 text-sm px-8 border outline-0" required>
        <div class="text-xs text-slate-500 mt-2">Username :</div>
        <input type="text" name="username" id="username" class="w-full h-14 text-sm px-8 border outline-0" required>
        <div class="text-xs text-slate-500 mt-2">Password :</div>
        <input type="password" name="password" id="password" class="w-full h-14 text-sm px-8 border outline-0">
        <div class="text-xs text-slate-500">Kosongkan jika tidak ingin mengganti password</div>

        <div class="mt-6 pt-6 border-t flex items-center gap-4 justify-end">
            <button class="text-sm bg-slate-200 p-3 px-6" type="button" onclick="toggleHidden('#edit')">Batal</button>
            <button class="text-sm bg-green-500 p-3 px-6 text-white">Simpan Perubahan</button>
        </div>
    </form>
</div>

<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="delete">
    <form class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4" method="POST" action="{{ route('admin.admin.delete') }}">
        @csrf
        <input type="hidden" name="id" id="id">
        <div class="flex items-center gap-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Hapus Administrator</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#delete')"></ion-icon>
        </div>

        <div class="text-slate-700 mt-2">
            Yakin ingin menghapus admin <span id="name"></span>?
        </div>

        <div class="mt-6 pt-6 border-t flex items-center gap-4 justify-end">
            <button class="text-sm bg-slate-200 p-3 px-6" type="button" onclick="toggleHidden('#delete')">Batal</button>
            <button class="text-sm bg-red-500 p-3 px-6 text-white">Hapus</button>
        </div>
    </form>
</div>
@endsection

@section('javascript')
<script>
    const edit = data => {
        data = JSON.parse(data);
        toggleHidden('#edit');
        select("#edit #id").value = data.id;
        select("#edit #name").value = data.name;
        select("#edit #username").value = data.username;
    }
    const del = data => {
        data = JSON.parse(data);
        toggleHidden('#delete');
        select("#delete #id").value = data.id;
        select("#delete #name").innerHTML = data.name;
    }
</script>
@endsection