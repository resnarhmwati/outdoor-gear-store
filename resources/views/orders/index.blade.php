@extends('layouts.app')

@section('title', 'POS - Kasir')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">POS - Kasir</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Products Section -->
            <div class="col-md-8">
                @foreach($categories as $categoryName => $products)
                <div class="mb-4">
                    <h4 class="mb-3">
                        <i class="fas fa-hiking text-success"></i> {{ $categoryName }}
                    </h4>
                    <div class="row">
                        @foreach($products as $product)
                        <div class="col-md-4 mb-3">
                            <div class="card product-card" onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->stock }})">
                                <div class="card-body text-center">
                                    @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid mb-2" style="max-height: 100px;">
                                    @else
                                    <i class="fas fa-box fa-3x text-muted mb-2"></i>
                                    @endif
                                    <h6 class="card-title font-weight-bold">{{ $product->name }}</h6>
                                    <p class="card-text text-muted mb-0">Stok: {{ $product->stock }}</p>
                                    <h5 class="text-success mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</h5>
                                    <button class="btn btn-success btn-sm">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                @if($categories->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Tidak ada produk tersedia.
                </div>
                @endif
            </div>

            <!-- Cart Section -->
            <div class="col-md-4">
                <div class="cart-section">
                    <h4 class="mb-3">
                        <i class="fas fa-shopping-cart"></i> Keranjang
                    </h4>

                    <div class="form-group">
                        <label>Customer (Opsional)</label>
                        <select class="form-control" id="customer_id">
                            <option value="">-- Pilih Customer --</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="cart-items" class="mb-3">
                        <p class="text-center text-muted">Keranjang kosong</p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Total:</strong>
                            <strong class="text-success" id="total-amount">Rp 0</strong>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Bayar</label>
                        <input type="number" class="form-control form-control-lg" id="paid_amount" placeholder="0" min="0">
                    </div>

                    <div class="form-group">
                        <label>Kembalian</label>
                        <input type="text" class="form-control form-control-lg" id="change_amount" readonly value="Rp 0">
                    </div>

                    <button class="btn btn-primary btn-lg btn-block" id="submit-order">
                        <i class="fas fa-check"></i> Submit Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
let cart = [];

function addToCart(productId, productName, price, stock) {
    const existingItem = cart.find(item => item.product_id === productId);
    
    if (existingItem) {
        if (existingItem.quantity < stock) {
            existingItem.quantity++;
        } else {
            alert('Stok tidak mencukupi!');
            return;
        }
    } else {
        cart.push({
            product_id: productId,
            name: productName,
            price: price,
            quantity: 1,
            stock: stock
        });
    }
    
    renderCart();
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.product_id !== productId);
    renderCart();
}

function updateQuantity(productId, newQuantity) {
    const item = cart.find(item => item.product_id === productId);
    
    if (item) {
        if (newQuantity > 0 && newQuantity <= item.stock) {
            item.quantity = parseInt(newQuantity);
        } else if (newQuantity > item.stock) {
            alert('Stok tidak mencukupi!');
            return;
        } else {
            removeFromCart(productId);
        }
    }
    
    renderCart();
}

function renderCart() {
    const cartItems = document.getElementById('cart-items');
    const totalAmount = document.getElementById('total-amount');
    
    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="text-center text-muted">Keranjang kosong</p>';
        totalAmount.textContent = 'Rp 0';
        return;
    }
    
    let html = '';
    let total = 0;
    
    cart.forEach(item => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        
        html += `
            <div class="cart-item">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <strong>${item.name}</strong>
                    <button class="btn btn-sm btn-danger" onclick="removeFromCart(${item.product_id})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <input type="number" class="form-control form-control-sm" 
                               style="width: 70px; display: inline-block;" 
                               value="${item.quantity}" 
                               min="1" 
                               max="${item.stock}"
                               onchange="updateQuantity(${item.product_id}, this.value)">
                        <small class="text-muted">x Rp ${formatRupiah(item.price)}</small>
                    </div>
                    <strong>Rp ${formatRupiah(subtotal)}</strong>
                </div>
            </div>
        `;
    });
    
    cartItems.innerHTML = html;
    totalAmount.textContent = 'Rp ' + formatRupiah(total);
    
    calculateChange();
}

function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function calculateChange() {
    const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const change = paidAmount - total;
    
    document.getElementById('change_amount').value = 'Rp ' + formatRupiah(Math.max(0, change));
}

document.getElementById('paid_amount').addEventListener('input', calculateChange);

document.getElementById('submit-order').addEventListener('click', function() {
    if (cart.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }
    
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
    
    if (paidAmount < total) {
        alert('Jumlah bayar tidak mencukupi!');
        return;
    }
    
    const customerId = document.getElementById('customer_id').value;
    
    // Prepare data
    const orderData = {
        customer_id: customerId || null,
        paid_amount: paidAmount,
        cart: cart.map(item => ({
            product_id: item.product_id,
            quantity: item.quantity,
            price: item.price
        }))
    };
    
    // Send to server
    fetch('{{ route("orders.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pesanan berhasil dibuat!\nNo Invoice: ' + data.invoice_number);
            
            // Open invoice in new tab
            window.open(`/orders/${data.order_id}/invoice`, '_blank');
            
            // Reset cart
            cart = [];
            renderCart();
            document.getElementById('paid_amount').value = '';
            document.getElementById('customer_id').value = '';
            
            // Reload page to refresh stock
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan: ' + error.message);
    });
});
</script>
@endpush
