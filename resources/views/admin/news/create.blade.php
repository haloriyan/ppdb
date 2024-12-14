@extends('layouts.admin')

@section('title', "Tulis Berita")

@section('head')
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.css" />
<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.3.1/"
        }
    }
</script>
@endsection
    
@section('content')
<form class="flex gap-8 p-10" method="POST" enctype="multipart/form-data" action="{{ route('admin.news.store') }}">
    @csrf
    <div class="flex flex-col w-7/12 gap-4 bg-white rounded-lg shadow-lg p-10 pb-10">
        <div class="text-xs text-slate-500">Judul</div>
        <input type="text" name="title" class="w-full h-14 border px-6 outline-0" required>

        <div>
            <div id="editor" class="h-96"></div>
        </div>
        <textarea name="content" id="content_body" class="hidden"></textarea>
    </div>
    <div class="flex flex-col gap-8 grow">
        <div class="bg-white shadow-lg rounded-lg flex items-center justify-end gap-4 p-10">
            <button class="bg-primary text-white font-medium p-3 px-6 text-sm">
                Terbitkan
            </button>
        </div>
        <div class="bg-white shadow-lg rounded-lg flex flex-col gap-4 p-10">
            <div class="text-sm text-slate-500">Gambar Utama</div>
            <div class="w-full aspect-video bg-gray-100 rounded-lg flex flex-col gap-4 items-center justify-center cursor-pointer" id="imagePreview" onclick="clickOnImage()">
                <ion-icon name="images-outline" class="text-3xl"></ion-icon>
                <div class="text-xs text-slate-500">Klik untuk memilih Gambar</div>
            </div>
            <input type="file" name="featured_image" class="hidden" id="image" onchange="onChangeImage(this)" required>
        </div>
    </div>
</form>
@endsection

@section('javascript')
<script type="module">
    import {
        ClassicEditor,
        Essentials,
        Bold,
        Italic,
        Font,
        Paragraph
    } from 'ckeditor5';

    let editor;

    ClassicEditor
        .create( document.querySelector( '#editor' ), {
            plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
            ]
        } )
        .then( (newEditor) => {
            editor = newEditor;
        } )
        .catch( /* ... */ );

    let content = select("#content_body");
    setInterval(() => {
        let editorData = editor.getData();
        content.value = editorData;
    }, 300);
</script>
<script>
    const inputImage = select("#image");
    const imagePreview = select("#imagePreview");

    const clickOnImage = () => {
        inputImage.click();
    }
    const onChangeImage = input => {
        let file = input.files[0];
        let reader = new FileReader();

        reader.onload = function () {
            let source = reader.result;
            imagePreview.style.backgroundImage = `url(${source})`;
            imagePreview.style.backgroundSize = "cover";
            imagePreview.style.backgroundPosition = "center center";
            imagePreview.innerHTML = "";
        }

        reader.readAsDataURL(file);
    }

  </script>
@endsection