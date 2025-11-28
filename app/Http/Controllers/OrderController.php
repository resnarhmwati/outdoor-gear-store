<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->where('stock', '>', 0)->get();
        $categories = $products->groupBy('category.name');
        $customers = Customer::all();
        
        return view('orders.index', compact('categories', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'paid_amount' => 'required|numeric|min:0',
            'cart' => 'required|array|min:1',
            'cart.*.product_id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Calculate total
            $totalAmount = 0;
            foreach ($validated['cart'] as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            // Create order
            $order = Order::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $validated['customer_id'],
                'total_amount' => $totalAmount,
                'paid_amount' => $validated['paid_amount'],
                'change_amount' => $validated['paid_amount'] - $totalAmount,
                'order_date' => now(),
            ]);

            // Create order items and update stock
            foreach ($validated['cart'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Update stock
                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'order_id' => $order->id,
                'invoice_number' => $invoiceNumber,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function history()
    {
        $orders = Order::with(['customer', 'orderItems.product'])
            ->latest()
            ->paginate(20);
        
        return view('orders.history', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'orderItems.product']);
        return view('orders.show', compact('order'));
    }

    public function invoice(Order $order)
    {
        $order->load(['customer', 'orderItems.product']);
        return view('orders.invoice', compact('order'));
    }

    public function report()
    {
        $today = Order::whereDate('order_date', today())->sum('total_amount');
        $thisMonth = Order::whereMonth('order_date', date('m'))
            ->whereYear('order_date', date('Y'))
            ->sum('total_amount');
        $thisYear = Order::whereYear('order_date', date('Y'))->sum('total_amount');

        $dailyOrders = Order::whereDate('order_date', today())
            ->with(['customer', 'orderItems.product'])
            ->latest()
            ->get();

        return view('orders.report', compact('today', 'thisMonth', 'thisYear', 'dailyOrders'));
    }
    public function monthlyReport()
    {
        $monthlySales = Order::whereMonth('order_date', now()->month)
            ->whereYear('order_date', now()->year)
            ->with(['customer', 'orderItems.product'])
            ->get();

        $totalMonthlySales = $monthlySales->sum('total_amount');

        return view('orders.report.monthly', compact('monthlySales', 'totalMonthlySales'));
    }

    // Metode untuk laporan tahunan
    public function yearlyReport()
    {
        $yearlySales = Order::whereYear('order_date', now()->year)
            ->with(['customer', 'orderItems.product'])
            ->get();

        $totalYearlySales = $yearlySales->sum('total_amount');

        return view('orders.report.yearly', compact('yearlySales', 'totalYearlySales'));
    }
}
