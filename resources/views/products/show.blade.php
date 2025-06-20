@extends('layouts.app')

@section('title', $medicine->nama)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            {{-- Ganti 'gambar' menjadi 'image' --}}
            <img src="{{ asset('storage/' . $medicine->image) }}" class="img-fluid rounded shadow" alt="{{ $medicine->nama }}">
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
            <form method="POST" action="{{ route('checkout.payment') }}">
                @csrf
                <input type="hidden" name="obat_id" value="{{ $medicine->id }}">
                
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

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Metode Pengiriman</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="shipping_method" id="shipping_courier" value="courier" checked>
                            <label class="form-check-label" for="shipping_courier">
                                <strong>Pengiriman Kurir</strong> (JNE, J&T, SiCepat, dll)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="shipping_method" id="shipping_instant" value="instant">
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

                <div id="courierShippingContainer">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Informasi Pengiriman</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="recipient_name" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('recipient_name') is-invalid @enderror" 
                                           id="recipient_name" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}">
                                    @error('recipient_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="recipient_phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('recipient_phone') is-invalid @enderror" 
                                           id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone') }}" 
                                           placeholder="08xxxxxxxxxx">
                                    @error('recipient_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
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
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="postal_code" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                           id="postal_code" name="postal_code" value="{{ old('postal_code') }}" 
                                           placeholder="12345" maxlength="5">
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
                            <input type="hidden" name="courier_details" id="courier_details">
                            @error('courier_name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div id="instantShippingContainer" style="display: none;">
                    <div class="alert alert-info">
                        <i class="fas fa-motorcycle me-2"></i>
                        <strong>Catatan:</strong> Layanan ini hanya tersedia untuk area <strong>Kota Bandung</strong>. Ongkos kirim (ongkir) ditanggung sepenuhnya oleh pembeli dan dibayarkan langsung kepada driver.
                    </div>
                    <div class="mb-3">
                        <label for="instant_recipient_name" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="instant_recipient_name" name="instant_recipient_name"
                               value="{{ old('instant_recipient_name', auth()->user()->name) }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="instant_recipient_phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="instant_recipient_phone" name="instant_recipient_phone"
                               value="{{ old('instant_recipient_phone', auth()->user()->phone ?? '') }}"
                               placeholder="08xxxxxxxxxx" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="instant_shipping_address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="instant_shipping_address" name="instant_shipping_address" rows="3"
                                  placeholder="Masukkan alamat lengkap..." disabled>{{ old('instant_shipping_address') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="instant_postal_code" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="instant_postal_code" name="instant_postal_code"
                               value="{{ old('instant_postal_code') }}"
                               placeholder="12345" maxlength="5" disabled>
                    </div>
                </div>

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

                <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">

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
    // --- Element References ---
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const shippingMethodRadios = document.querySelectorAll('input[name="shipping_method"]');
        const courierShippingContainer = document.getElementById('courierShippingContainer');
    const instantShippingContainer = document.getElementById('instantShippingContainer');
    const courierSection = document.getElementById('courierSection');
    const courierLoading = document.getElementById('courierLoading');
    const courierOptions = document.getElementById('courierOptions');
    const orderSummary = document.getElementById('orderSummary');
    const submitBtn = document.getElementById('submitBtn');
    const shippingCostInput = document.getElementById('shipping_cost');
    const courierNameInput = document.getElementById('courier_name');
    const courierServiceInput = document.getElementById('courier_service');
    const courierDetailsInput = document.getElementById('courier_details');
    const productPrice = {{ $medicine->harga }};
    const maxStock = {{ $medicine->stock }};
    
    // Courier shipping fields
    const recipientNameInput = document.getElementById('recipient_name');
    const recipientPhoneInput = document.getElementById('recipient_phone');
    const shippingAddressInput = document.getElementById('shipping_address');
    const postalCodeInput = document.getElementById('postal_code');
    const cityInput = document.getElementById('city');
    const districtInput = document.getElementById('district');
    
    // Instant shipping fields
    const instantRecipientName = document.getElementById('instant_recipient_name');
    const instantRecipientPhone = document.getElementById('instant_recipient_phone');
    const instantShippingAddress = document.getElementById('instant_shipping_address');
    const instantPostalCode = document.getElementById('instant_postal_code');
    
    let currentShippingMethod = 'courier';
    
    // --- Event Listeners ---
    shippingMethodRadios.forEach(radio => radio.addEventListener('change', handleShippingMethodChange));
    decreaseBtn.addEventListener('click', () => updateQuantity(-1));
    increaseBtn.addEventListener('click', () => updateQuantity(1));
    quantityInput.addEventListener('change', () => updateQuantity(0));
    
    let postalCodeTimeout;
    postalCodeInput.addEventListener('input', function() {
        clearTimeout(postalCodeTimeout);
        const postalCode = this.value.replace(/\D/g, '');
        this.value = postalCode;
        if (postalCode.length === 5) {
            postalCodeTimeout = setTimeout(() => getAreaByPostalCode(postalCode), 500);
        } else {
            resetAddressAndCourier();
        }
    });
    
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {
        // Prepare data based on shipping method before submission
        prepareFormData();
        
        // Custom validation based on shipping method
        if (currentShippingMethod === 'courier') {
            if (!shippingCostInput.value) {
                e.preventDefault();
                alert('Silakan pilih kurir terlebih dahulu');
                return;
            }
            
            // Validate courier shipping fields
            if (!recipientNameInput.value.trim() || 
                !recipientPhoneInput.value.trim() || 
                !shippingAddressInput.value.trim() || 
                !postalCodeInput.value.trim()) {
                e.preventDefault();
                alert('Mohon lengkapi semua field pengiriman yang diperlukan.');
                return;
            }
        }
        
        // Validate instant shipping fields
        if (currentShippingMethod === 'instant') {
            if (!instantRecipientName.value.trim() ||
                !instantRecipientPhone.value.trim() ||
                !instantShippingAddress.value.trim() ||
                !instantPostalCode.value.trim()) {
                e.preventDefault();
                alert('Mohon lengkapi semua field pengiriman instan yang diperlukan.');
                return;
            }
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    });

    // --- Core Functions ---
    function prepareFormData() {
        if (currentShippingMethod === 'instant') {
            // Copy instant shipping data to main fields and disable courier fields
            recipientNameInput.value = instantRecipientName.value;
            recipientPhoneInput.value = instantRecipientPhone.value;
            shippingAddressInput.value = instantShippingAddress.value;
            postalCodeInput.value = instantPostalCode.value;
            cityInput.value = 'Bandung';
            districtInput.value = 'Bandung';
            
            // Disable instant fields so they don't get submitted
            instantRecipientName.disabled = true;
            instantRecipientPhone.disabled = true;
            instantShippingAddress.disabled = true;
            instantPostalCode.disabled = true;
        } else if (currentShippingMethod === 'pickup') {
            // For pickup, set minimal required data
            recipientNameInput.value = '{{ auth()->user()->name }}';
            recipientPhoneInput.value = '{{ auth()->user()->phone ?? "08123456789" }}';
            shippingAddressInput.value = 'Pickup di Apotek';
            postalCodeInput.value = '40111';
            cityInput.value = 'Bandung';
            districtInput.value = 'Bandung';
            
            // Disable instant fields
            instantRecipientName.disabled = true;
            instantRecipientPhone.disabled = true;
            instantShippingAddress.disabled = true;
            instantPostalCode.disabled = true;
        } else {
            // For courier, disable instant fields
            instantRecipientName.disabled = true;
            instantRecipientPhone.disabled = true;
            instantShippingAddress.disabled = true;
            instantPostalCode.disabled = true;
        }
    }
    
    function handleShippingMethodChange() {
        currentShippingMethod = document.querySelector('input[name="shipping_method"]:checked').value;
        resetState();
        enableDisableFields();
        
        switch (currentShippingMethod) {
            case 'courier':
                courierShippingContainer.style.display = 'block';
                break;
            case 'instant':
                instantShippingContainer.style.display = 'block';
                courierNameInput.value = 'Instant';
                courierServiceInput.value = 'Gojek/GrabExpress';
                shippingCostInput.value = 0; 
                orderSummary.style.display = 'block';
                submitBtn.disabled = false;
                break;
            case 'pickup':
                courierNameInput.value = 'Pickup';
                courierServiceInput.value = 'Ambil di Apotek';
                shippingCostInput.value = 0;
                orderSummary.style.display = 'block';
                submitBtn.disabled = false;
                break;
        }
        updateSummary();
    }
    
    function enableDisableFields() {
        if (currentShippingMethod === 'courier') {
            // Enable courier fields, disable instant fields
            recipientNameInput.disabled = false;
            recipientPhoneInput.disabled = false;
            shippingAddressInput.disabled = false;
            postalCodeInput.disabled = false;
            
            instantRecipientName.disabled = true;
            instantRecipientPhone.disabled = true;
            instantShippingAddress.disabled = true;
            instantPostalCode.disabled = true;
        } else if (currentShippingMethod === 'instant') {
            // Enable instant fields, disable courier fields
            instantRecipientName.disabled = false;
            instantRecipientPhone.disabled = false;
            instantShippingAddress.disabled = false;
            instantPostalCode.disabled = false;
            
            recipientNameInput.disabled = true;
            recipientPhoneInput.disabled = true;
            shippingAddressInput.disabled = true;
            postalCodeInput.disabled = true;
        } else {
            // Pickup - disable all address fields
            recipientNameInput.disabled = true;
            recipientPhoneInput.disabled = true;
            shippingAddressInput.disabled = true;
            postalCodeInput.disabled = true;
            
            instantRecipientName.disabled = true;
            instantRecipientPhone.disabled = true;
            instantShippingAddress.disabled = true;
            instantPostalCode.disabled = true;
        }
    }
    
    function updateQuantity(change) {
        let qty = parseInt(quantityInput.value);
        if (change !== 0) qty += change;
        if (qty < 1) qty = 1;
        if (qty > maxStock) qty = maxStock;
        quantityInput.value = qty;
        
        if (currentShippingMethod === 'courier' && cityInput.value) {
            calculateShipping();
        }
        updateSummary();
    }

    function getAreaByPostalCode(postalCode) {
        fetch('{{ route("api.get-area") }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({ postal_code: postalCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cityInput.value = data.data.city;
                districtInput.value = data.data.district;
                calculateShipping();
            } else {
                alert(data.message || 'Kode pos tidak ditemukan');
                resetAddressAndCourier();
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
        
        fetch('{{ route("api.calculate-shipping") }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({
                obat_id: {{ $medicine->id }},
                quantity: parseInt(quantityInput.value),
                postal_code: postalCodeInput.value
            })
        })
        .then(response => {
             if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
             return response.json();
        })
        .then(data => {
            courierLoading.style.display = 'none';
            if (data.success && data.data.rates.length > 0) {
                displayCourierOptions(data.data.rates);
            } else {
                courierOptions.innerHTML = `<div class="alert alert-warning">${data.message || 'Tidak ada layanan pengiriman yang tersedia untuk area ini.'}</div>`;
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
                    <div class="card courier-option">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="courier_radio" id="courier_${index}" value="${index}" data-rate='${JSON.stringify(rate)}'>
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
                </div>`;
        });
        html += '</div>';
        courierOptions.innerHTML = html;
        
        document.querySelectorAll('input[name="courier_radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) selectCourier(JSON.parse(this.dataset.rate));
            });
        });
    }
    
    function selectCourier(rate) {
        courierNameInput.value = rate.courier_name;
        courierServiceInput.value = rate.service_name;
        shippingCostInput.value = rate.price;
        courierDetailsInput.value = JSON.stringify(rate);
        orderSummary.style.display = 'block';
        submitBtn.disabled = false;
        updateSummary();
    }
    
    function updateSummary() {
        const quantity = parseInt(quantityInput.value);
        const subtotal = productPrice * quantity;
        const shippingCost = parseInt(shippingCostInput.value) || 0;
        // Total tidak termasuk ongkir instan
        const total = subtotal + (currentShippingMethod === 'instant' ? 0 : shippingCost);
        
        document.getElementById('summaryQty').textContent = quantity;
        document.getElementById('summarySubtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
        
        const summaryShippingEl = document.getElementById('summaryShipping');
        if (currentShippingMethod === 'pickup') {
            summaryShippingEl.textContent = 'Gratis';
        } else if (currentShippingMethod === 'instant') {
            summaryShippingEl.innerHTML = `<strong>Ditanggung Pembeli</strong> <small class="d-block">(Bayar di tempat)</small>`;
        } else {
            summaryShippingEl.textContent = shippingCost > 0 ? 'Rp ' + new Intl.NumberFormat('id-ID').format(shippingCost) : '-';
        }
        
        document.getElementById('summaryTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }
    
    function resetState() {
        courierShippingContainer.style.display = 'none';
        instantShippingContainer.style.display = 'none';
        courierSection.style.display = 'none';
        orderSummary.style.display = 'none';
        submitBtn.disabled = true;
        shippingCostInput.value = 0;
        courierNameInput.value = '';
        courierServiceInput.value = '';
        courierDetailsInput.value = '';
                courierOptions.innerHTML = '';
    }

    function resetAddressAndCourier() {
        cityInput.value = '';
        districtInput.value = '';
        resetState();
        updateSummary();
    }

    // Initialize
    handleShippingMethodChange();
});
</script>
@endpush
@endsection

