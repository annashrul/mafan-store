@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h4>Dashboard</h4>
        <hr/>
        <div class="row">
            <div class="col-md-6" >
                <canvas id="transactionChart" ></canvas>
            </div>
            <div class="col-md-6" >
                <canvas id="transactionsByHourChart" ></canvas>
            </div>
            <div class="col-md-12">
                <hr/>
            </div>
            <div class="col-md-6">
                <canvas id="topProductsChart" ></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="userTransactionsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
                // Ambil data dari server
                const transactionsByHour = @json($transactionsByHour);

                // Label jam 0 - 23
                const labels = Array.from({length: 24}, (_, i) => `${i}:00`);

                // Data transaksi
                const data = Array.from({length: 24}, (_, i) => {
                    const transaction = transactionsByHour.find(t => t.hour == i);
                    return transaction ? transaction.total_transactions : 0;
                });

                // Konfigurasi grafik
                const ctx = document.getElementById('transactionsByHourChart').getContext('2d');
                  var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Transactions by Hour (current date)',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: true,

                    }]
                },
                options: {
                    scales: {
                        x: {
                            ticks: {
                                font: {
                                    color:'black',
                                    family: 'Playpen Sans' // Font pada sumbu x
                                }
                            },
                            title: {
                                display: true,
                                text: 'Hour',    
                                font: {
                                    family: 'Playpen Sans' // Font pada sumbu x
                                }
                            }
                            
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    color:'black',
                                    family: 'Playpen Sans' // Font pada sumbu y
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                // This more specific font property overrides the global property
                                font: {
                                    color:'black',
                                    size: 14,
                                    family: 'Playpen Sans', // Ganti dengan font family

                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                // Customize the tooltip label
                                label: function(context) {
                                    let label = context.dataset.label || '';

                                    if (label) {
                                        label += ': ';
                                    }
                                    // Add custom text to the tooltip
                                    label = `total per hour : ${context.raw} transaction`;
                                    return label;
                                },
                                // Customize the tooltip title
                                title: function(context) {
                                    // If you want to customize the title, you can do it here
                                    return `Hour: ${context[0].label}`;
                                }
                            },
                            bodyFont: {
                                family: 'Playpen Sans' // Font family untuk tooltip body
                            },
                            titleFont: {
                                family: 'Playpen Sans' // Font family untuk tooltip title
                            }
                        }
                    }
                }
            });
            });
        document.addEventListener('DOMContentLoaded', function () {
                // Ambil data dari server
                const transactions = @json($transactionsByUser);
                const user = @json($user);

                // Ambil nama dan total transaksi dari semua user
                const allLabels = transactions.map(t => t.name);
                const allData = transactions.map(t => t.total_transactions);

                // Ambil nama dan total transaksi dari user yang login
                // const userLabels = userTransactions.map(t => t.name);
                // const userData = userTransactions.map(t => t.total_transactions);

                // const labels = transactions.map(t => t.name);
                // const data = transactions.map(t => t.total_transactions);

                // Konfigurasi grafik
                const ctx = document.getElementById('userTransactionsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: allLabels,
                        datasets: [{ 
                            label: 'Total Transactions by User',
                            data: allData,
                            backgroundColor: (context) => {
                                const index = context.dataIndex;
                                return user.name.includes(allLabels[index])
                                    ? 'rgba(255, 99, 132, 0.2)' // Warna untuk user yang login
                                    : 'rgba(75, 192, 192, 0.2)'; // Warna untuk semua user
                            },
                            borderColor: (context) => {
                                const index = context.dataIndex;
                                return user.name.includes(allLabels[index])
                                    ? 'rgba(255, 99, 132, 1)' // Border warna untuk user yang login
                                    : 'rgba(75, 192, 192, 1)'; // Border warna untuk semua user
                            },
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                        x: {
                            ticks: {
                                font: {
                                    family: 'Playpen Sans' // Font pada sumbu x
                                }
                            },
                            title: {
                                display: true,
                                text: 'User',    
                                font: {
                                    family: 'Playpen Sans' // Font pada sumbu x
                                }
                            },
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    family: 'Playpen Sans' // Font pada sumbu y
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                // This more specific font property overrides the global property
                                font: {
                                    size: 14,
                                    family: 'Playpen Sans', // Ganti dengan font family

                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                // Customize the tooltip label
                                label: function(context) {
                                    let label = context.dataset.label || '';

                                    if (label) {
                                        label += ': ';
                                    }
                                    // Add custom text to the tooltip
                                    label = `total : ${context.raw} transaction`;
                                    return label;
                                },
                                // Customize the tooltip title
                                title: function(context) {
                                    // If you want to customize the title, you can do it here
                                    return `User : ${context[0].label}`;
                                }
                            },
                            bodyFont: {
                                family: 'Playpen Sans' // Font family untuk tooltip body
                            },
                            titleFont: {
                                family: 'Playpen Sans' // Font family untuk tooltip title
                            }
                        }
                    }
                }
            });
        });

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
                        x: {
                            ticks: {
                                font: {
                                    family: 'Playpen Sans' // Font pada sumbu x
                                }
                            },
                            title: {
                                display: true,
                                text: 'Product',    
                                font: {
                                    family: 'Playpen Sans' // Font pada sumbu x
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    family: 'Playpen Sans' // Font pada sumbu y
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                // This more specific font property overrides the global property
                                font: {
                                    size: 14,
                                    family: 'Playpen Sans', // Ganti dengan font family

                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                // Customize the tooltip label
                                label: function(context) {
                                    let label = context.dataset.label || '';

                                    if (label) {
                                        label += ': ';
                                    }
                                    // Add custom text to the tooltip
                                    label = `total quantity sold :  ${context.raw}`;
                                    return label;
                                },
                                // Customize the tooltip title
                                title: function(context) {
                                    // If you want to customize the title, you can do it here
                                    return `Product: ${context[0].label}`;
                                }
                            },
                            bodyFont: {
                                family: 'Playpen Sans' // Font family untuk tooltip body
                            },
                            titleFont: {
                                family: 'Playpen Sans' // Font family untuk tooltip title
                            }
                        }
                    }
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function (){
            const ctx = document.getElementById('transactionChart').getContext('2d');
                    const transactionChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: {!! $dates !!},
                            datasets: [{
                                label: 'Transactions per Day (current month)',
                                data: {!! $counts !!},
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                fill: true,
                                borderWidth: 2
                            }]
                        },
                        options: {
                            plugins: {
                                
                                legend: {
                                    labels: {
                                        // This more specific font property overrides the global property
                                        font: {
                                            size: 14,
                                            family: 'Playpen Sans', // Ganti dengan font family

                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        // Customize the tooltip label
                                        label: function(context) {
                                            let label = context.dataset.label || '';

                                            if (label) {
                                                label += ': ';
                                            }

                                            // Add custom text to the tooltip
                                            label = `total per day: ${context.raw} transaction`;
                                            return label;
                                        },
                                        // Customize the tooltip title
                                        title: function(context) {
                                            // If you want to customize the title, you can do it here
                                            return `Date: ${context[0].label}`;
                                        }
                                    },
                                    bodyFont: {
                                        family: 'Playpen Sans' // Font family untuk tooltip body
                                    },
                                    titleFont: {
                                        family: 'Playpen Sans' // Font family untuk tooltip title
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        font: {
                                            family: 'Playpen Sans' // Font pada sumbu x
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Date',    
                                        font: {
                                            family: 'Playpen Sans' // Font pada sumbu x
                                        }
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: ''
                                    },
                                    beginAtZero: true,
                                    ticks: {
                                        font: {
                                            family: 'Playpen Sans' // Font pada sumbu x
                                        }
                                    },
                                }
                            }
                        }
                    });
        })
       
    </script>
@endsection
