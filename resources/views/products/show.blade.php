Of course. Based on your request, I have removed the "Pengiriman Kurir" (Courier Shipping) option from both the HTML structure and the corresponding JavaScript logic.

Here are the key changes made:

1.  **Removed HTML Elements**: The radio button for "Pengiriman Kurir" and the entire form section for courier address details (`courierShippingContainer`, `courierSection`) have been deleted.
2.  **Default Selection**: "Pengiriman Instan" (Instant Shipping) is now the default selected shipping method.
3.  **Simplified JavaScript**:
      * All JavaScript functions and variables related to courier shipping (`calculateShipping`, `getAreaByPostalCode`, `displayCourierOptions`, etc.) have been completely removed.
      * The logic has been streamlined to only handle the "Pengiriman Instan" and "Ambil di Apotek" (Pickup at Pharmacy) options.
      * The form submission logic and validations have been adjusted to work without the courier option.

Here is the modified and cleaned-up code:

```php
@extends('layouts.app')

@section('title', $obat->nama)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $obat->image) }}" alt="{{ $obat->name }}" class="w-full h-auto object-cover rounded-lg">
        </div>
        <div class="col-md-6">
            <h2 class="mb-3">{{ $obat->nama }}</h2>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5 class="text-success">Harga</h5>
                    <h4 class="text-primary fw-bold">Rp {{ number_format($obat->harga, 0, ',', '.') }}</h4>
                </div>
                <div class="col-md-6">
                    <h5>Stok</h5>
                    <p class="badge bg-success fs-6">{{ $obat->stock }} tersedia</p>
                </div>
            </div>

            @if($obat->stock > 0)
            <form action="{{ route('checkout.show', ['product' => $obat->id]) }}" method="get" id="purchaseForm">
                @csrf
                <input type="hidden" name="obat_id" value="{{ $obat->id }}">
                
                <div class="mb-4">
                    <label for="quantity" class="form-label fw-bold">Jumlah</label>
                    <div class="input-group" style="max-width: 200px;">
                        <button type="button" class="btn btn-outline-secondary" id="decreaseQty">-</button>
                        <input type="number" class="form-control text-center" id="quantity" name="quantity" 
                               value="1" min="1" max="{{ $obat->stock }}" required>
                        <button type="button" class="btn btn-outline-secondary" id="increaseQty">+</button>
                    </div>
                    @error('quantity')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Metode Pengiriman</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="shipping_method" id="shipping_instant" value="instant" checked>
                            <label class="form-check-label" for="shipping_instant">
                                <strong>Pengiriman Instan</strong> (Gojek/GrabExpress)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shipping_method" id="shipping_pickup" value="pickup">
                            <label class="form-check-label" for="shipping_pickup">
                                <strong>Ambil di Apotek</strong> (Gratis)
                            </label>
                        </div>
                    </div>
                </div>

                <div id="instantShippingContainer" style="display: none;">
                     <div class="alert alert-info">
                        <i class="fas fa-motorcycle me-2"></i>
                        <strong>Catatan:</strong> Layanan ini hanya tersedia untuk area <strong>Kota Bandung</strong>. Ongkos kirim (ongkir) ditanggung sepenuhnya oleh pembeli dan dibayarkan langsung kepada driver.
                    </div>
                    <div class="card mb-4">
                         <div class="card-header bg-light">
                            <h5 class="mb-0">Informasi Pengiriman Instan</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="recipient_name" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('recipient_name') is-invalid @enderror" 
                                       id="recipient_name" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}">
                                @error('recipient_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="recipient_phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('recipient_phone') is-invalid @enderror" 
                                       id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone', auth()->user()->phone ?? '') }}" 
                                       placeholder="08xxxxxxxxxx">
                                @error('recipient_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                          id="shipping_address" name="shipping_address" rows="3" 
                                          placeholder="Masukkan alamat lengkap...">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="postal_code" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                       id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                                       placeholder="Contoh: 40111" maxlength="5">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4" id="orderSummary" style="display: none;">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal (<span id="summaryQty">1</span> item)</span>
                            <span id="summarySubtotal">Rp {{ number_format($obat->harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
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

                <div class="mb-4">
                    <label for="notes" class="form-label">Catatan (Opsional)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2" 
                              placeholder="Catatan untuk penjual...">{{ old('notes') }}</textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" >
                        <i class="fas fa-shopping-cart me-2"></i>Beli Sekarang
                    </button>
                </div>
            </form>
            @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Produk ini sedang tidak tersedia.
            </div>
            @endif
        </div>
    </div>

    {{-- Detail Produk Section --}}
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Detail Produk</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Deskripsi</h6>
                            <p>{{ $obat->deskripsi }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Dosis</h6>
                            <p>{{ $obat->dosis }}</p>
                        </div>
                    </div>
                    
                    @if($obat->efek_samping)
                    <div class="mt-3">
                        <h6>Efek Samping</h6>
                        <p>{{ $obat->efek_samping }}</p>
                    </div>
                    @endif

                    @if($obat->kontraindikasi)
                    <div class="mt-3">
                        <h6>Kontraindikasi</h6>
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