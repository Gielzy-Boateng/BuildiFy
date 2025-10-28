{{-- resources/views/analytics/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Analytics')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Analytics</h1>
            <p class="mt-2 text-gray-600">Track your page performance</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('analytics.export') }}" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Export CSV
            </a>
        </div>
    </div>

    {{-- Period Selector --}}
    <div class="mb-6">
        <div class="inline-flex rounded-md shadow-sm">
            <a href="{{ route('analytics.index', ['period' => 'daily']) }}" 
                class="px-4 py-2 text-sm font-medium rounded-l-lg border {{ $period === 'daily' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                Daily
            </a>
            <a href="{{ route('analytics.index', ['period' => 'weekly']) }}" 
                class="px-4 py-2 text-sm font-medium border-t border-b {{ $period === 'weekly' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                Weekly
            </a>
            <a href="{{ route('analytics.index', ['period' => 'monthly']) }}" 
                class="px-4 py-2 text-sm font-medium rounded-r-lg border {{ $period === 'monthly' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                Monthly
            </a>
        </div>
    </div>

    {{-- Chart --}}
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Page Views Over Time</h3>
        <canvas id="analyticsChart" height="80"></canvas>
    </div>

    {{-- Data Table --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Views</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unique Views</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return Rate</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($stats as $stat)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($period === 'daily')
                            {{ $stat->date }}
                        @elseif($period === 'weekly')
                            Week {{ $stat->week }}
                        @else
                            {{ $stat->year }}-{{ str_pad($stat->month, 2, '0', STR_PAD_LEFT) }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($stat->total_views) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($stat->unique_views) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $stat->total_views > 0 ? round((($stat->total_views - $stat->unique_views) / $stat->total_views) * 100, 1) : 0 }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('analyticsChart').getContext('2d');
const stats = @json($stats);

let labels, totalViews, uniqueViews;

@if($period === 'daily')
    labels = stats.map(s => s.date);
@elseif($period === 'weekly')
    labels = stats.map(s => 'Week ' + s.week);
@else
    labels = stats.map(s => s.year + '-' + String(s.month).padStart(2, '0'));
@endif

totalViews = stats.map(s => s.total_views);
uniqueViews = stats.map(s => s.unique_views);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Total Views',
            data: totalViews,
            backgroundColor: 'rgba(79, 70, 229, 0.8)',
        }, {
            label: 'Unique Views',
            data: uniqueViews,
            backgroundColor: 'rgba(16, 185, 129, 0.8)',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
