@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Laporan Penjualan</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Summary Cards -->
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>Rp {{ number_format($today, 0, ',', '.') }}</h3>
                        <p>Penjualan Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Rp {{ number_format($thisMonth, 0, ',', '.') }}</h3>
                        <p>Penjualan Bulan Ini</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>Rp {{ number_format($thisYear, 0, ',', '.') }}</h3>
                        <p>Penjualan Tahun Ini</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Orders Table -->
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">Transaksi Hari Ini ({{ $dailyOrders->count() }} transaksi)</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th width="50">#</th>
                            <th>No. Invoice</th>
                            <th>Waktu</th>
                            <th>Customer</th>
                            <th>Item</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dailyOrders as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order->invoice_number }}</td>
                            <td>{{ $order->order_date->format('H:i') }}</td>
                            <td>{{ $order->customer ? $order->customer->name : 'Umum' }}</td>
                            <td>{{ $order->orderItems->count() }} item</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada transaksi hari ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($dailyOrders->count() > 0)
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="5" class="text-right">Total:</th>
                            <th>Rp {{ number_format($today, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            <div class="card-footer">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>
            </div>
        </div>
    </div>
</section>

<style>
    @media print {
        .main-header, .main-sidebar, .content-header, .card-footer, .no-print {
            display: none !important;
        }
        .content-wrapper {
            margin: 0 !important;
        }
    }
</style>
@endsection
