@extends('layouts.student')

@section('title', "Formulir Pendaftaran")
    
@section('content')
<div class="fixed top-0 left-0 right-0 h-72 mobile:h-56 bg-primary"></div>

<div class="absolute top-20 mobile:top-10 left-0 right-0 flex flex-col items-center">
    <div class="w-5/12 mobile:w-11/12 p-10 rounded-xl bg-white shadow-lg" style="border-bottom: 5px solid #2196f3">
        <h1 class="text-3xl text-slate-700 font-medium">Formulir Pendaftaran</h1>
        <form action="{{ route('student.pendaftaran') }}" class="mt-8 flex flex-col gap-2" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="text-xs text-slate-500">Nama :</div>
            <input type="text" name="name" class="w-full h-14 border outline-0 px-8" required>

            @foreach ($fields as $field)
                <div class="text-xs text-slate-500 mt-4">{{ $field->label }} :</div>
                @if ($field->type == "TEXT")
                    <input 
                        type="text" 
                        name="{{ $field->key}}" 
                        class="w-full h-14 border outline-0 text-slate-700 text-sm px-8" 
                        {{ $field->required ? 'required' : ''}}
                    >
                @endif

                @if ($field->type == "SELECT")
                    <div class="border px-8">
                        <select 
                            name="{{ $field->key }}"
                            {{ $field->required ? 'required' : ''}}
                            class="w-full h-14 outline-0 text-slate-700 text-sm"
                        >
                            <option value="">Pilih...</option>
                            @foreach (explode("||", $field->options) as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                
                @if ($field->type == "FILE")
                <input 
                    type="file"
                    name="{{ $field->key }}"
                    {{ $field->required ? 'required' : ''}}
                    class="cursor-pointer text-sm"
                >
                @endif
            @endforeach

            <button class="bg-primary text-white text-sm font-medium h-14 w-full mt-8">Proses Pendaftaran</button>
        </form>
    </div>

    <div class="h-20"></div>
</div>
@endsection