@extends('layouts.app')

@section('title', $obat->nama)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Gambar Produk -->
        <div class="col-md-6 mb-4">
            <div class="border rounded shadow-sm bg-white p-3">
                <img src="{{ asset('storage/' . $obat->image) }}" alt="{{ $obat->nama }}" class="img-fluid rounded w-100">
            </div>
        </div>

        <!-- Info Produk -->
        <div class="col-md-6">
            <h2 class="fw-bold mb-3">{{ $obat->nama }}</h2>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="text-muted mb-1">Harga</h6>
                    <h4 class="text-primary fw-bold">Rp {{ number_format($obat->harga, 0, ',', '.') }}</h4>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Stok</h6>
                    <span class="badge {{ $obat->stock <= 5 ? 'bg-danger' : 'bg-success' }} fs-6">
                        {{ $obat->stock }} tersedia
                    </span>
                </div>
            </div>

            @if($obat->stock > 0)
            <form action="{{ route('checkout.show', ['product' => $obat->id]) }}" method="get" id="purchaseForm" class="bg-light p-4 rounded shadow-sm">
                @csrf
                <input type="hidden" name="obat_id" value="{{ $obat->id }}">

                <!-- Jumlah -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Jumlah</label>
                    <div class="input-group" style="max-width: 200px;">
                        <button type="button" class="btn btn-outline-secondary" id="decreaseQty">-</button>
                        <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="{{ $obat->stock }}" required>
                        <button type="button" class="btn btn-outline-secondary" id="increaseQty">+</button>
                    </div>
                    @error('quantity')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Metode Pengiriman -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white fw-semibold">Metode Pengiriman</div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="shipping_method" id="shipping_instant" value="instant" checked>
                            <label class="form-check-label" for="shipping_instant">
                                <strong>Pengiriman Instan</strong> (Gojek/GrabExpress)
                            </label>
                        </div>
                    
                    </div>
                </div>

                <!-- Form Pengiriman Instan -->
                <div id="instantShippingContainer" style="display: none;">
                    <div class="alert alert-info">
                        <i class="fas fa-motorcycle me-2"></i>
                        <strong>Catatan:</strong> Hanya tersedia untuk <strong>Kota Bandung</strong>. Ongkir dibayar ke driver.
                    </div>

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white fw-semibold">Informasi Pengiriman Instan</div>
                        <div class="card-body">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('recipient_name') is-invalid @enderror" id="recipient_name" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}">
                                <label for="recipient_name">Nama Penerima</label>
                                @error('recipient_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('recipient_phone') is-invalid @enderror" id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone', auth()->user()->phone ?? '') }}" placeholder="08xxxxxxxxxx">
                                <label for="recipient_phone">Nomor Telepon</label>
                                @error('recipient_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" placeholder="Alamat lengkap" style="height: 100px">{{ old('shipping_address') }}</textarea>
                                <label for="shipping_address">Alamat Lengkap</label>
                                @error('shipping_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" placeholder="40111" maxlength="5">
                                <label for="postal_code">Kode Pos</label>
                                @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Pesanan -->
                <div id="orderSummary" class="card shadow-sm mb-4" style="display: none;">
                    <div class="card-header bg-white fw-semibold">Ringkasan Pesanan</div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <span>Subtotal (<span id="summaryQty">1</span> item)</span>
                            <span id="summarySubtotal">Rp {{ number_format($obat->harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Ongkos Kirim</span>
                            <span id="summaryShipping">-</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span class="text-primary" id="summaryTotal">-</span>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                <input type="hidden" name="courier_name" id="courier_name">
                <input type="hidden" name="courier_service" id="courier_service">

                <!-- Catatan -->
                <div class="form-floating mb-4">
                    <textarea class="form-control" id="notes" name="notes" style="height: 80px">{{ old('notes') }}</textarea>
                    <label for="notes">Catatan untuk penjual (opsional)</label>
                </div>

                <!-- Tombol -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                        <i class="fas fa-shopping-cart me-2"></i>Beli Sekarang
                    </button>
                </div>
            </form>
            @else
            <div class="alert alert-warning mt-4">
                <i class="fas fa-exclamation-triangle me-2"></i> Produk ini sedang tidak tersedia.
            </div>
            @endif
        </div>
    </div>

    <!-- Detail Produk -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Detail Produk</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="fw-semibold">Deskripsi</h6>
                            <p>{{ $obat->deskripsi }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold">Dosis</h6>
                            <p>{{ $obat->dosis }}</p>
                        </div>
                    </div>

                    @if($obat->efek_samping)
                    <div class="mb-3">
                        <h6 class="fw-semibold">Efek Samping</h6>
                        <p>{{ $obat->efek_samping }}</p>
                    </div>
                    @endif

                    @if($obat->kontraindikasi)
                    <div>
                        <h6 class="fw-semibold">Kontraindikasi</h6>
                        <p>{{ $obat->kontraindikasi }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Element References ---
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const shippingMethodRadios = document.querySelectorAll('input[name="shipping_method"]');
    const instantShippingContainer = document.getElementById('instantShippingContainer');
    const orderSummary = document.getElementById('orderSummary');
    const submitBtn = document.getElementById('submitBtn');
    const shippingCostInput = document.getElementById('shipping_cost');
    const courierNameInput = document.getElementById('courier_name');
    const courierServiceInput = document.getElementById('courier_service');
    const productPrice = {{ $obat->harga }};
    const maxStock = {{ $obat->stock }};

    // Instant/Pickup shipping fields (now the main fields)
    const recipientNameInput = document.getElementById('recipient_name');
    const recipientPhoneInput = document.getElementById('recipient_phone');
    const shippingAddressInput = document.getElementById('shipping_address');
    const postalCodeInput = document.getElementById('postal_code');
    
    let currentShippingMethod = 'instant';
    
    // --- Event Listeners ---
    shippingMethodRadios.forEach(radio => radio.addEventListener('change', handleShippingMethodChange));
    decreaseBtn.addEventListener('click', () => updateQuantity(-1));
    increaseBtn.addEventListener('click', () => updateQuantity(1));
    quantityInput.addEventListener('change', () => updateQuantity(0));
    
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {
        // Custom validation for instant shipping
        if (currentShippingMethod === 'instant') {
            if (!recipientNameInput.value.trim() ||
                !recipientPhoneInput.value.trim() ||
                !shippingAddressInput.value.trim() ||
                !postalCodeInput.value.trim()) {
                
                e.preventDefault();
                alert('Mohon lengkapi semua field informasi pengiriman instan.');
                return;
            }
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    });

    // --- Core Functions ---
    function handleShippingMethodChange() {
        currentShippingMethod = document.querySelector('input[name="shipping_method"]:checked').value;
        resetState();
        
        switch (currentShippingMethod) {
            case 'instant':
                instantShippingContainer.style.display = 'block';
                courierNameInput.value = 'Instant';
                courierServiceInput.value = 'Gojek/GrabExpress';
                shippingCostInput.value = 0; 
                orderSummary.style.display = 'block';
                submitBtn.disabled = false;
                enableDisableInstantFields(true);
                break;
            case 'pickup':
                courierNameInput.value = 'Pickup';
                courierServiceInput.value = 'Ambil di Apotek';
                shippingCostInput.value = 0;
                orderSummary.style.display = 'block';
                submitBtn.disabled = false;
                 enableDisableInstantFields(false);
                break;
        }
        updateSummary();
    }
    
    function enableDisableInstantFields(enabled) {
        recipientNameInput.disabled = !enabled;
        recipientPhoneInput.disabled = !enabled;
        shippingAddressInput.disabled = !enabled;
        postalCodeInput.disabled = !enabled;
    }
    
    function updateQuantity(change) {
        let qty = parseInt(quantityInput.value);
        if (change !== 0) qty += change;
        if (qty < 1) qty = 1;
        if (qty > maxStock) qty = maxStock;
        quantityInput.value = qty;
        
        updateSummary();
    }

    function updateSummary() {
        const quantity = parseInt(quantityInput.value);
        const subtotal = productPrice * quantity;
        const shippingCost = 0; // Cost is always 0 for payment, as instant is paid to driver
        const total = subtotal + shippingCost;
        
        document.getElementById('summaryQty').textContent = quantity;
        document.getElementById('summarySubtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
        
        const summaryShippingEl = document.getElementById('summaryShipping');
        if (currentShippingMethod === 'pickup') {
            summaryShippingEl.textContent = 'Gratis';
        } else if (currentShippingMethod === 'instant') {
            summaryShippingEl.innerHTML = `<strong>Ditanggung Pembeli</strong> <small class="d-block">(Bayar di tempat)</small>`;
        }
        
        document.getElementById('summaryTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }
    
    function resetState() {
        instantShippingContainer.style.display = 'none';
        orderSummary.style.display = 'none';
        submitBtn.disabled = true;
        shippingCostInput.value = 0;
        courierNameInput.value = '';
        courierServiceInput.value = '';
    }

    // Initialize
    handleShippingMethodChange();
});
</script>
@endpush
@endsection
```