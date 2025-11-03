@extends('layouts.app')

@section('title', 'Earnings')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Earnings (Last 7 days)</h5>
                    <div>
                        <small class="text-muted">Total: ₹{{ number_format($earnings['total'] ?? 0, 2) }}</small>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="earningsChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6>Summary</h6>
                    <p>Today: <strong>₹{{ number_format($earnings['today'] ?? 0, 2) }}</strong></p>
                    <p>This week: <strong>₹{{ number_format($earnings['week'] ?? 0, 2) }}</strong></p>
                    <p>This month: <strong>₹{{ number_format($earnings['month'] ?? 0, 2) }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function(){
            const ctx = document.getElementById('earningsChart').getContext('2d');
            const data = {
                labels: [
                    @php
                        for($i=6;$i>=0;$i--) { echo "'".\Carbon\Carbon::now()->subDays($i)->format('D')."',"; }
                    @endphp
                ],
                datasets: [{
                    label: 'Earnings',
                    data: {{ json_encode($earnings_last_7) }},
                    backgroundColor: 'rgba(226,55,68,0.08)',
                    borderColor: '#E23744',
                    tension: 0.3,
                    fill: true
                }]
            };

            new Chart(ctx, { type: 'line', data: data, options: { responsive: true, plugins: { legend: { display: false } } } });
        })();
    </script>
    @endpush

@endsection
