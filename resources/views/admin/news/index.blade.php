@extends('layouts.admin')

@section('title', "Berita")
    
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
                ['#', 'Berita'],
            ]])
        </div>
        <a href="{{ route('admin.news.create') }}" class="bg-primary text-white p-3 px-6 rounded font-medium flex items-center gap-4">
            <ion-icon name="add-outline"></ion-icon>
            <div class="text-sm">Berita Baru</div>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-10 mt-10">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="w-20 p-1">
                        <ion-icon name="image-outline"></ion-icon>
                    </th>
                    <th class="p-1">Judul</th>
                    <th class="p-1"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td class="p-1">
                            <img 
                                src="{{ asset('storage/news_images/' . $post->featured_image) }}" 
                                alt="{{ $post->title}}"
                                class="h-20 aspect-video rounded object-cover"
                            >
                        </td>
                        <td class="p-1">
                            <div>{{ $post->title }}</div>
                            <a href="{{ route('news.read', $post->slug) }}" target="_blank" class="text-xs text-slate-500 underline">{{ route('news.read', $post->slug) }}</a>
                        </td>
                        <td class="p-1 flex items-center gap-2">
                            <a href="{{ route('admin.news.edit', $post->id) }}" class="cursor-pointer text-white bg-primary p-2 px-4 text-sm mt-4">
                                <ion-icon name="create-outline"></ion-icon>
                            </a>
                            <span class="cursor-pointer text-white bg-red-500 p-2 px-4 text-sm mt-4" onclick="del({{ $post->id }})">
                                <ion-icon name="trash-outline"></ion-icon>
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
</div>

<div class="fixed top-0 left-0 right-0 bottom-0 bg-white bg-opacity-75 flex items-center justify-center hidden" id="deleteModal">
    <form class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12" method="POST" action="{{ route('admin.news.delete') }}">
        @csrf
        <input type="hidden" name="id" id="id">
        <h3 class="text-lg text-slate-700 font-medium">Hapus Berita</h3>
        <div class="text-sm text-slate-500 mt-2">Yakin ingin menghapus berita ini?</div>
        <div class="border-t pt-6 mt-6 flex items-center justify-end gap-4">
            <button class="bg-slate-200 p-2 px-4 text-slate-600 text-sm" type="button" onclick="toggleHidden('#deleteModal')">Batal</button>
            <button class="bg-red-500 p-2 px-4 text-white text-sm">Hapus</button>
        </div>
    </form>
</div>
@endsection

@section('javascript')
<script>
    const del = id => {
        select("#deleteModal #id").value = id;
        select("#deleteModal").classList.toggle('hidden');
    }
</script>
@endsection