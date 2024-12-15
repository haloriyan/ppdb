@extends('layouts.admin')

@section('title', "Dashboard")

@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
    
@section('content')
<div class="p-10">
    <div class="flex gap-6">
        <div class="flex flex-col gap-2 grow basis-80 bg-white rounded-lg shadow-lg p-6">
            <div class="text-xs text-slate-500">Siswa Terdata</div>
            <div class="text-4xl text-slate-600 font-bold">{{ $students->count() }}</div>
        </div>
        <div class="flex flex-col gap-2 grow basis-80 bg-white rounded-lg shadow-lg p-6">
            <div class="text-xs text-slate-500">Siswa Mendaftar</div>
            <div class="text-4xl text-slate-600 font-bold">{{ $bookings->count() }}</div>
        </div>
        <div class="flex flex-col gap-2 grow basis-80 bg-white rounded-lg shadow-lg p-6">
            <div class="text-xs text-slate-500">Pendapatan</div>
            <div class="text-4xl text-slate-600 font-bold">{{ currency_encode($revenue, 'Rp', '.', 'Rp 0') }}</div>
        </div>
    </div>

    <div class="flex gap-6 mt-10">
        <div class="flex flex-col grow p-8 bg-white rounded-lg shadow-lg">
            <h4 class="text-lg text-slate-700 font-medium mb-6">Siswa Terbaru</h4>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="text-sm text-slate-700 bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left">
                            <ion-icon name="time-outline"></ion-icon>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($latestStudents as $student)
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $student->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ Carbon::parse($student->created_at)->isoFormat('DD MMM - HH:mm') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex flex-col grow p-8 bg-white rounded-lg shadow-lg max-w-96">
            <canvas id="gelombangChartCanvas"></canvas>
        </div>
    </div>
</div>

<input type="hidden" id="gelombangChart" value="{{ json_encode($gelombangChart) }}">
@endsection

@section('javascript')
<script>
    const gelombangChart = JSON.parse(select("#gelombangChart").value);
    console.log(gelombangChart);
    new Chart(
        select("#gelombangChartCanvas").getContext('2d'),
        {
            type: 'doughnut',
            data: gelombangChart,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',  // Position of the legend
                    },
                    tooltip: {
                        enabled: true,  // Display tooltips on hover
                    }
                },
            }
        }
    )
</script>
@endsection