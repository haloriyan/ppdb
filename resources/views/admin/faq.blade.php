@extends('layouts.admin')

@section('title', "Pertanyaan Umum")
    
@section('content')
<div class="p-10">
    <div class="flex items-center gap-4">
        <div class="flex flex-col gap-2 grow">
            @include('components.breadcrumb', ['items' => [
                [route('admin.dashboard'), 'Dashboard'],
                ['#', 'Pertanyaan Umum']
            ]])
        </div>
        <button class="font-medium bg-primary text-white p-3 px-6 flex items-center gap-4" onclick="toggleHidden('#create')">
            <ion-icon name="add-outline"></ion-icon>
            <div class="text-xs">Buat Pertanyaan</div>
        </button>
    </div>

    @if ($message != "")
        <div class="bg-green-100 text-green-500 text-sm p-4 rounded mt-4 mb-4">
            {{ $message }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-10 mt-10">
        <div class="min-w-full overflow-hidden overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="text-sm text-slate-700 bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">Pertanyaan</th>
                        <th scope="col" class="px-6 py-3 text-left">Jawaban</th>
                        <th scope="col" class="px-6 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($faqs as $faq)
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $faq->question }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $faq->answer }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700 flex gap-4">
                                <span class="cursor-pointer text-white bg-primary p-2 px-4 text-sm" onclick="edit('{{ $faq }}')">
                                    <ion-icon name="create-outline"></ion-icon>
                                </span>
                                <span class="cursor-pointer text-white bg-red-500 p-2 px-4 text-sm" onclick="del('{{ $faq }}')">
                                    <ion-icon name="trash-outline"></ion-icon>
                                </span>
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
    <form class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4" method="POST" action="{{ route('admin.faq.store') }}">
        @csrf
        <div class="flex items-center gap-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Tambah Pertanyaan</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#create')"></ion-icon>
        </div>

        <div class="text-xs text-slate-500 mt-4">Pertanyaan :</div>
        <input type="text" name="question" id="question" class="w-full h-14 outline-0 border text-sm text-slate-700 px-4" required>
        <div class="text-xs text-slate-500 mt-4">Jawaban :</div>
        <textarea name="answer" id="answer" rows="6" class="w-full outline-0 border text-sm text-slate-700 p-4" required></textarea>

        <div class="mt-6 pt-6 border-t flex items-center gap-4 justify-end">
            <button class="text-sm bg-slate-200 p-3 px-6" type="button" onclick="toggleHidden('#create')">Batal</button>
            <button class="text-sm bg-primary p-3 px-6 text-white">Tambahkan</button>
        </div>
    </form>
</div>

<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="edit">
    <form class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4" method="POST" action="{{ route('admin.faq.update') }}">
        @csrf
        <input type="hidden" name="id" id="id">

        <div class="flex items-center gap-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Edit Pertanyaan</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#edit')"></ion-icon>
        </div>

        <div class="text-xs text-slate-500 mt-4">Pertanyaan :</div>
        <input type="text" name="question" id="question" class="w-full h-14 outline-0 border text-sm text-slate-700 px-4" required>
        <div class="text-xs text-slate-500 mt-4">Jawaban :</div>
        <textarea name="answer" id="answer" rows="6" class="w-full outline-0 border text-sm text-slate-700 p-4" required></textarea>

        <div class="mt-6 pt-6 border-t flex items-center gap-4 justify-end">
            <button class="text-sm bg-slate-200 p-3 px-6" type="button" onclick="toggleHidden('#edit')">Batal</button>
            <button class="text-sm bg-green-500 p-3 px-6 text-white">Simpan Perubahan</button>
        </div>
    </form>
</div>

<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="delete">
    <form class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4" method="POST" action="{{ route('admin.faq.delete') }}">
        @csrf
        <input type="hidden" name="id" id="id">

        <div class="flex items-center gap-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Hapus Pertanyaan</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#delete')"></ion-icon>
        </div>

        <div class="text-sm text-slate-700 mt-2">Yakin ingin menghapus pertanyaan ini?</div>

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
        select("#edit #question").value = data.question;
        select("#edit #answer").value = data.answer;
    }
    const del = data => {
        data = JSON.parse(data);
        toggleHidden('#delete');
        select("#delete #id").value = data.id;
    }
</script>
@endsection