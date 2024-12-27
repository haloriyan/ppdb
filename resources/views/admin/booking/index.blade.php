@extends('layouts.admin')

@section('title', "Pendaftaran")
    
@section('content')
<div class="p-10 flex flex-col gap-10">
    <div class="bg-white rounded-lg shadow-lg p-10 flex items-end gap-4">
        <form class="flex items-end grow gap-4" id="filterForm">
            <div class="flex flex-col grow gap-2">
                <div class="text-xs text-slate-500">Cari nama siswa :</div>
                <div class="flex items-center gap-4 border pe-4">
                    <input type="text" name="q" class="w-full h-12 px-4 outline-0" value="{{ $request->q }}">
                    @if ($request->q != "")
                        <a href="{{ route('admin.booking') }}" class="text-red-500">
                            <ion-icon name="close-outline"></ion-icon>
                        </a>
                    @endif
                </div>
            </div>

            <select class="px-4 h-12 border text-sm text-slate-700" name="wave_id" onchange="select('#filterForm').submit()">
                <option value="">Semua Gelombang</option>
                @foreach ($waves as $wave)
                    <option value="{{ $wave->id }}" {{ $request->wave_id == $wave->id ? 'selected="selected"' : '' }}>{{ $wave->name }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.export.student') }}" class="bg-green-500 text-white text-sm font-medium h-12 p-3 px-6">
            Download Excel
        </a>
    </div>
    <div class="bg-white rounded-lg shadow-lg p-10">
        <div class="min-w-full overflow-hidden overflow-x-auto p-5">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="text-sm text-slate-700 bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">No. Pendaftaran</th>
                        <th scope="col" class="px-6 py-3 text-left">Nama</th>
                        @foreach ($tableCols as $col)
                        <th scope="col" class="px-6 py-3 text-left">{{ $col['label'] }}</th>
                        @endforeach
                        <th scope="col" class="px-6 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($students as $student)
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4 text-sm">
                                @if ($student->booking != null)
                                    {{ $student->booking->id }}
                                @else
                                    <div class="text-xs text-slate-500">Belum mendaftar</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.booking.detail', $student->id) }}" class="text-primary underline">
                                    {{ $student->name }}
                                </a>
                            </td>
                            @foreach ($tableCols as $col)
                                @php
                                    $fields = json_decode(base64_decode($student->fields), false);
                                @endphp
                                @foreach ($fields as $field)
                                    @if ($field->key == $col['key'] && $field->type != "FILE")
                                        <td class="px-6 py-4 text-sm">{{ $field->value }}</td>
                                    @endif
                                @endforeach
                            @endforeach
                            <th scope="col" class="px-6 py-3 text-left flex gap-2">
                                <a href="{{ route('admin.booking.detail', $student->id) }}" class="bg-primary text-white p-2 px-4 text-sm">
                                    <ion-icon name="eye-outline"></ion-icon>
                                </a>
                                @if ($student->booking != null && $student->booking->is_accepted == false)
                                    <a href="{{ route('admin.booking.accept', $student->id) }}" class="bg-green-500 text-white p-2 px-4 text-sm">
                                        <ion-icon name="checkmark-outline"></ion-icon>
                                    </a>
                                @endif
                            </th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection