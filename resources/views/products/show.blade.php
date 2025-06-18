@extends('layouts.app')

@section('title', $medicine->nama)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $medicine->gambar) }}" class="img-fluid rounded shadow" alt="{{ $medicine->nama }}">
        </div>
        <div class="col-md-6">
            <h2 class="mb-3">{{ $medicine->nama }}</h2>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5 class="text-success">Harga</h5>
                    <h4 class="text-primary fw-bold">Rp {{ number_format($medicine->harga, 0, ',', '.') }}</h4>
                </div>
                <div class="col-md-6">
                    <h5>Stok</h5>
                    <p class="badge bg-success fs-6">{{ $medicine->stock }} tersedia</p>
                </div>
            </div>

            @if($medicine->stock > 0)
            <!-- Purchase Form -->
            <form id="purchaseForm" method="POST" action="{{ route('orders.purchase') }}">
                @csrf
                <input type="hidden" name="obat_id" value="{{ $medicine->id }}">
                
                <!-- Quantity Selection -->
                <div class="mb-4">
                    <label for="quantity" class="form-label fw-bold">Jumlah</label>
                    <div class="input-group" style="max-width: 200px;">
                        <button type="button" class="btn btn-outline-secondary" id="decreaseQty">-</button>
                        <input type="number" class="form-control text-center" id="quantity" name="quantity" 
                               value="1" min="1" max="{{ $medicine->stock }}" required>
                        <button type="button" class="btn btn-outline-secondary" id="increaseQty">+</button>
                    </div>
                    @error('quantity')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Shipping Information -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Informasi Pengiriman</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="recipient_name" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('recipient_name') is-invalid @enderror" 
                                       id="recipient_name" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" required>
                                @error('recipient_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="recipient_phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('recipient_phone') is-invalid @enderror" 
                                       id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone') }}" 
                                       placeholder="08xxxxxxxxxx" required>
                                @error('recipient_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                      id="shipping_address" name="shipping_address" rows="3" 
                                      placeholder="Masukkan alamat lengkap..." required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="postal_code" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" value="{{ old('postal_code') }}" 
                                       placeholder="12345" maxlength="5" required>
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city') }}" readonly>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="district" class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('district') is-invalid @enderror" 
                                       id="district" name="district" value="{{ old('district') }}" readonly>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Courier Selection -->
                <div class="card mb-4" id="courierSection" style="display: none;">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Pilih Kurir</h5>
                    </div>
                    <div class="card-body">
                        <div id="courierLoading" class="text-center" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Menghitung ongkir...</p>
                        </div>
                        
                        <div id="courierOptions"></div>
                        
                        <input type="hidden" name="courier_name" id="courier_name">
                        <input type="hidden" name="courier_service" id="courier_service">
                        <input type="hidden" name="shipping_cost" id="shipping_cost">
                        <input type="hidden" name="courier_details" id="courier_details">
                        
                        @error('courier_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="card mb-4" id="orderSummary" style="display: none;">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal (<span id="summaryQty">1</span> item)</span>
                            <span id="summarySubtotal">Rp {{ number_format($medicine->harga, 0, ',', '.') }}</span>
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

                <!-- Notes -->
                <div class="mb-4">
                    <label for="notes" class="form-label">Catatan (Opsional)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2" 
                              placeholder="Catatan untuk penjual...">{{ old('notes') }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
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

    <!-- Product Details -->
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
                            <p>{{ $medicine->deskripsi }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Dosis</h6>
                            <p>{{ $medicine->dosis }}</p>
                        </div>
                    </div>
                    
                    @if($medicine->efek_samping)
                    <div class="mt-3">
                        <h6>Efek Samping</h6>
                        <p>{{ $medicine->efek_samping }}</p>
                    </div>
                    @endif

                    @if($medicine->kontraindikasi)
                    <div class="mt-3">
                        <h6>Kontraindikasi</h6>
                        <p>{{ $medicine->kontraindikasi }}</p>
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
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const postalCodeInput = document.getElementById('postal_code');
    const cityInput = document.getElementById('city');
    const districtInput = document.getElementById('district');
    const courierSection = document.getElementById('courierSection');
    const courierLoading = document.getElementById('courierLoading');
    const courierOptions = document.getElementById('courierOptions');
    const orderSummary = document.getElementById('orderSummary');
    const submitBtn = document.getElementById('submitBtn');
    
    const productPrice = {{ $medicine->harga }};
    const maxStock = {{ $medicine->stock }};
    
    // Quantity controls
    decreaseBtn.addEventListener('click', function() {
        let qty = parseInt(quantityInput.value);
        if (qty > 1) {
            quantityInput.value = qty - 1;
            updateSummary();
            if (cityInput.value) calculateShipping();
        }
    });
    
    increaseBtn.addEventListener('click', function() {
        let qty = parseInt(quantityInput.value);
        if (qty < maxStock) {
            quantityInput.value = qty + 1;
            updateSummary();
            if (cityInput.value) calculateShipping();
        }
    });
    
    quantityInput.addEventListener('change', function() {
        let qty = parseInt(this.value);
        if (qty < 1) this.value = 1;
        if (qty > maxStock) this.value = maxStock;
        updateSummary();
        if (cityInput.value) calculateShipping();
    });
    
    // Postal code lookup
    let postalCodeTimeout;
    postalCodeInput.addEventListener('input', function() {
        clearTimeout(postalCodeTimeout);
        const postalCode = this.value.replace(/\D/g, '');
        this.value = postalCode;
        
        if (postalCode.length === 5) {
            postalCodeTimeout = setTimeout(() => {
                getAreaByPostalCode(postalCode);
            }, 500);
        } else {
            cityInput.value = '';
            districtInput.value = '';
            courierSection.style.display = 'none';
            orderSummary.style.display = 'none';
            submitBtn.disabled = true;
        }
    });
    
    function getAreaByPostalCode(postalCode) {
        fetch('{{ route("api.get-area") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ postal_code: postalCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cityInput.value = data.data.city;
                districtInput.value = data.data.district;
                calculateShipping();
            } else {
                alert('Kode pos tidak ditemukan');
                cityInput.value = '';
                districtInput.value = '';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal mendapatkan informasi area');
        });
    }
    
    function calculateShipping() {
        courierLoading.style.display = 'block';
        courierOptions.innerHTML = '';
        courierSection.style.display = 'block';
        orderSummary.style.display = 'none';
        submitBtn.disabled = true;
        
        const formData = {
            obat_id: {{ $medicine->id }},
            quantity: parseInt(quantityInput.value),
            postal_code: postalCodeInput.value
        };
        
        fetch('{{ route("api.calculate-shipping") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            courierLoading.style.display = 'none';
            
            if (data.success && data.data.rates.length > 0) {
                displayCourierOptions(data.data.rates);
            } else {
                courierOptions.innerHTML = '<div class="alert alert-warning">Tidak ada layanan pengiriman yang tersedia untuk area ini.</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            courierLoading.style.display = 'none';
            courierOptions.innerHTML = '<div class="alert alert-danger">Gagal menghitung ongkir. Silakan coba lagi.</div>';
        });
    }
    
    function displayCourierOptions(rates) {
        let html = '<div class="row">';
        
        rates.forEach((rate, index) => {
            const etd = rate.minimum_day === rate.maximum_day 
                ? `${rate.minimum_day} hari` 
                : `${rate.minimum_day}-${rate.maximum_day} hari`;
                
            html += `
                <div class="col-md-6 mb-3">
                    <div class="card courier-option" data-index="${index}">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="courier_radio" id="courier_${index}" value="${index}">
                                <label class="form-check-label w-100" for="courier_${index}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">${rate.courier_name}</h6>
                                            <small class="text-muted">${rate.service_name}</small>
                                            <br><small class="text-success">Est. ${etd}</small>
                                        </div>
                                        <div class="text-end">
                                            <strong>Rp ${new Intl.NumberFormat('id-ID').format(rate.price)}</strong>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        courierOptions.innerHTML = html;
        
        // Add event listeners to courier options
        document.querySelectorAll('input[name="courier_radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    const rate = rates[this.value];
                    selectCourier(rate);
                }
            });
        });
    }
    
    function selectCourier(rate) {
        document.getElementById('courier_name').value = rate.courier_name;
        document.getElementById('courier_service').value = rate.service_name;
        document.getElementById('shipping_cost').value = rate.price;
        document.getElementById('courier_details').value = JSON.stringify(rate);
        
        updateSummary();
        orderSummary.style.display = 'block';
        submitBtn.disabled = false;
    }
    
    function updateSummary() {
        const quantity = parseInt(quantityInput.value);
        const subtotal = productPrice * quantity;
        const shippingCost = parseInt(document.getElementById('shipping_cost').value) || 0;
        const total = subtotal + shippingCost;
        
        document.getElementById('summaryQty').textContent = quantity;
        document.getElementById('summarySubtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
        
        if (shippingCost > 0) {
            document.getElementById('summaryShipping').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(shippingCost);
            document.getElementById('summaryTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }
    }
    
    // Form submission
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {
        if (!document.getElementById('shipping_cost').value) {
            e.preventDefault();
            alert('Silakan pilih kurir terlebih dahulu');
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    });
});
</script>
@endpush
@endsection