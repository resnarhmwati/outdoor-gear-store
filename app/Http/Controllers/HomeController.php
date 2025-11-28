<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Cek apakah ada data Order
        try {
            $todaySales = Order::whereDate('created_at', today())->sum('total_amount');
            $todayOrders = Order::whereDate('created_at', today())->count();
            $recentOrders = Order::with('customer')->orderBy('created_at', 'desc')->limit(5)->get();
        } catch (\Exception $e) {
            $todaySales = 0;
            $todayOrders = 0;
            $recentOrders = collect();
        }
        
        // Total data
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        
        // Produk stok menipis (kurang dari 10)
        $lowStockProducts = Product::where('stock', '<', 10)->orderBy('stock', 'asc')->limit(5)->get();
        
        // Produk terlaris bulan ini - PERBAIKAN DI SINI
        try {
            $topProducts = DB::table('products')
                ->select(
                    'products.id',
                    'products.name',
                    'products.price',
                    'products.stock',
                    DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'),
                    DB::raw('COALESCE(SUM(order_items.quantity * order_items.price), 0) as total_revenue')
                )
                ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
                ->leftJoin('orders', function($join) {
                    $join->on('order_items.order_id', '=', 'orders.id')
                         ->whereMonth('orders.created_at', now()->month)
                         ->whereYear('orders.created_at', now()->year);
                })
                ->groupBy('products.id', 'products.name', 'products.price', 'products.stock')
                ->orderBy('total_sold', 'desc')
                ->having('total_sold', '>', 0)  // Hanya tampilkan yang pernah terjual
                ->limit(5)
                ->get();
                
            // Kalau tidak ada data, ambil 5 produk random untuk contoh
            if ($topProducts->isEmpty()) {
                $topProducts = Product::inRandomOrder()
                    ->limit(5)
                    ->get()
                    ->map(function($product) {
                        $product->total_sold = 0;
                        $product->total_revenue = 0;
                        return $product;
                    });
            }
        } catch (\Exception $e) {
            // Fallback: ambil 5 produk pertama
            $topProducts = Product::limit(5)->get()->map(function($product) {
                $product->total_sold = 0;
                $product->total_revenue = 0;
                return $product;
            });
        }
        
        // Data chart 7 hari terakhir
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->isoFormat('dd, D MMM');
            
            // Ambil total penjualan per hari
            $dailySales = Order::whereDate('created_at', $date)->sum('total_amount');
            $chartData[] = (float) $dailySales;
        }
        
        return view('home', compact(
            'todaySales',
            'todayOrders',
            'totalProducts',
            'totalCustomers',
            'lowStockProducts',
            'recentOrders',
            'topProducts',
            'chartLabels',
            'chartData'
        ));
    }
}