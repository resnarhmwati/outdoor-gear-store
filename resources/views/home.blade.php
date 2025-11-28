@extends('layouts.app')

@section('title', 'Home - Resn Outdoor Gear')

@section('content')
<section class="content">
    <div class="container-fluid">
        <!-- Welcome Banner -->
        <div class="row">
            <div class="col-12">
                <div class="card" style="background: linear-gradient(135deg, #2d5f3f 0%, #3d7a54 100%); color: white;">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2><i class="fas fa-mountain"></i> Selamat Datang di Resn Outdoor Gear</h2>
                                <p class="mb-0">Sistem Point of Sale untuk Toko Perlengkapan Outdoor</p>
                                <small>{{ now()->isoFormat('dddd, D MMMM YYYY') }}</small>
                            </div>
                            <div class="col-md-4 text-right">
                                <i class="fas fa-hiking" style="font-size: 80px; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <!-- Total Penjualan Hari Ini -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Rp {{ number_format($todaySales ?? 0, 0, ',', '.') }}</h3>
                        <p>Penjualan Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <a href="{{ route('orders.report') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Total Transaksi -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $todayOrders ?? 0 }}</h3>
                        <p>Transaksi Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <a href="{{ route('orders.history') }}" class="small-box-footer">
                        Lihat Riwayat <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Total Produk -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $totalProducts ?? 0 }}</h3>
                        <p>Total Produk</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <a href="{{ route('products.index') }}" class="small-box-footer">
                        Kelola Produk <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Total Customer -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $totalCustomers ?? 0 }}</h3>
                        <p>Total Customer</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('customers.index') }}" class="small-box-footer">
                        Lihat Customer <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Recent Orders -->
        <div class="row">
            <!-- Quick Actions -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header" style="background-color: #2d5f3f; color: white;">
                        <h3 class="card-title"><i class="fas fa-bolt"></i> Aksi Cepat</h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('orders.index') }}" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-cash-register"></i> Buat Transaksi Baru
                        </a>
                        <a href="{{ route('products.create') }}" class="btn btn-success btn-block mb-2">
                            <i class="fas fa-plus"></i> Tambah Produk Baru
                        </a>
                        <a href="{{ route('customers.create') }}" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-user-plus"></i> Tambah Customer Baru
                        </a>
                        <a href="{{ route('orders.report') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-chart-line"></i> Lihat Laporan
                        </a>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                <div class="card">
                    <div class="card-header bg-warning">
                        <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Stok Menipis</h3>
                    </div>
                    <div class="card-body p-0">
                        @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($lowStockProducts as $product)
                            <li class="list-group-item">
                                <strong>{{ $product->name }}</strong>
                                <span class="badge badge-danger float-right">{{ $product->stock }} unit</span>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-center p-3 mb-0">Semua stok aman</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Orders & Top Products -->
            <div class="col-md-8">
                <!-- Recent Orders -->
                <div class="card">
                    <div class="card-header" style="background-color: #2d5f3f; color: white;">
                        <h3 class="card-title"><i class="fas fa-history"></i> Transaksi Terbaru</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No. Invoice</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($recentOrders) && $recentOrders->count() > 0)
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->invoice_number }}</td>
                                        <td>{{ $order->customer->name ?? 'Umum' }}</td>
                                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge badge-success">Selesai</span>
                                        </td>
                                        <td>{{ $order->created_at->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada transaksi hari ini</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title"><i class="fas fa-star"></i> Produk Terlaris Bulan Ini</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Terjual</th>
                                    <th>Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($topProducts) && $topProducts->count() > 0)
                                    @foreach($topProducts as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name ?? '-' }}</td>
                                        <td>{{ $product->total_sold ?? 0 }} unit</td>
                                        <td>Rp {{ number_format($product->total_revenue ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada data penjualan</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Chart (Optional) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="background-color: #2d5f3f; color: white;">
                        <h3 class="card-title"><i class="fas fa-chart-area"></i> Grafik Penjualan 7 Hari Terakhir</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!},
            datasets: [{
                label: 'Penjualan (Rp)',
                data: {!! json_encode($chartData ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                backgroundColor: 'rgba(45, 95, 63, 0.2)',
                borderColor: 'rgba(45, 95, 63, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush