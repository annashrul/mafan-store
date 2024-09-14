@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard</h1>
        <div class="row">
            <div class="col-md-6">
                <canvas id="transactionChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="topProductsChart" ></canvas>
            </div>
        </div>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('topProductsChart').getContext('2d');
            var topProductsData = @json($topProducts);

            var labels = topProductsData.map(function(product) {
                return product.name;
            });
            var data = topProductsData.map(function(product) {
                return product.total_qty;
            });

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Top 10 Products',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });


        const ctx = document.getElementById('transactionChart').getContext('2d');
        const transactionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! $dates !!},
                datasets: [{
                    label: 'Jumlah Transaksi perhari',
                    data: {!! $counts !!},
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jumlah Transaksi'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
