@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Pesanan</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('orders.history') }}" class="btn btn-secondary float-right">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">Informasi Pesanan</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">No. Invoice</th>
                                <td>{{ $order->invoice_number }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>{{ $order->order_date->format('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Customer</th>
                                <td>{{ $order->customer ? $order->customer->name : 'Umum' }}</td>
                            </tr>
                        </table>

                        <h5 class="mt-4 mb-3">Item Pesanan</h5>
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title">Ringkasan Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Total</th>
                                <td class="text-right"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <th>Bayar</th>
                                <td class="text-right">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Kembalian</th>
                                <td class="text-right">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('orders.invoice', $order) }}" class="btn btn-success btn-block" target="_blank">
                            <i class="fas fa-print"></i> Cetak Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
