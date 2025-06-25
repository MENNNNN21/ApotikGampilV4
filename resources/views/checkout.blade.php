{{-- resources/views/checkout.blade.php --}}
@extends('layouts.app')

@section('title', 'Ringkasan Checkout')

@section('content')
<div class="container py-5">
    <h2>Ringkasan Pesanan Anda</h2>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    {{-- Form utama untuk proses pembayaran --}}
                    <form action="{{ route('payment.process') }}" method="POST" id="checkoutForm">
                        @csrf

                        <h5 class="card-title">Detail Produk</h5>
                        <p><strong>Nama Obat:</strong> {{ $data['product']->name }}</p>
                        <p><strong>Jumlah:</strong> {{ $data['quantity'] }}</p>
                        <p><strong>Harga Satuan:</strong> Rp {{ number_format($data['product']->harga, 0, ',', '.') }}</p>
                        <hr>

                        <h5 class="card-title mt-4">Detail Pengiriman</h5>
                        <p><strong>Metode:</strong> {{ $data['shipping_method'] == 'instant' ? 'Pengiriman Instan' : 'Ambil di Apotek' }}</p>
                        <p><strong>Nama Penerima:</strong> {{ $data['recipient_name'] }}</p>
                        <p><strong>Nomor Telepon:</strong> {{ $data['recipient_phone'] }}</p>
                        <p><strong>Alamat:</strong> {{ $data['shipping_address'] }}</p>
                        <p><strong>Catatan:</strong> {{ $data['notes'] ?: '-' }}</p>
                        <hr>

                        {{-- ====================================================== --}}
                        {{-- ============ BAGIAN METODE PEMBAYARAN (BARU) ========== --}}
                        {{-- ====================================================== --}}
                     {{-- Ganti bagian "Pilih Metode Pembayaran" di resources/views/checkout.blade.php --}}

<h5 class="card-title mt-4">Pilih Metode Pembayaran</h5>
<div class="payment-methods">
    {{-- Opsi QRIS --}}
    <div class="form-check card-radio">
        <input class="form-check-input" type="radio" name="payment_method" id="payment_qris" value="qris" checked>
        <label class="form-check-label" for="payment_qris">
            <i class="fas fa-qrcode me-2"></i>
            <span>QRIS</span>
            <small class="d-block text-muted">Mendukung semua E-Wallet & Mobile Banking.</small>
        </label>
    </div>

    {{-- Opsi Transfer Bank (BARU) --}}
    <div class="form-check card-radio">
        <input class="form-check-input" type="radio" name="payment_method" id="payment_transfer" value="transfer">
        <label class="form-check-label" for="payment_transfer">
            <i class="fas fa-university me-2"></i>
            <span>Transfer Bank</span>
            <small class="d-block text-muted">Transfer manual ke rekening virtual/bank.</small>
        </label>
    </div>

    {{-- Opsi Cash (Hanya untuk pickup) --}}
    @if($data['shipping_method'] == 'pickup')
    <div class="form-check card-radio">
        <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash">
        <label class="form-check-label" for="payment_cash">
            <i class="fas fa-money-bill-wave me-2"></i>
            <span>Cash / Tunai</span>
            <small class="d-block text-muted">Bayar di kasir saat pengambilan barang.</small>
        </label>
    </div>
    @endif
</div>
@error('payment_method')
    <div class="text-danger small mt-2">{{ $message }}</div>
@enderror

                        {{-- ====================================================== --}}
                        {{-- ================= END BAGIAN BARU ==================== --}}
                        {{-- ====================================================== --}}


                        {{-- Kirim semua data yang diperlukan sebagai input hidden --}}
                        <input type="hidden" name="product_id" value="{{ $data['product']->id }}">
                        <input type="hidden" name="quantity" value="{{ $data['quantity'] }}">
                        <input type="hidden" name="shipping_method" value="{{ $data['shipping_method'] }}">
                        <input type="hidden" name="recipient_name" value="{{ $data['recipient_name'] }}">
                        <input type="hidden" name="recipient_phone" value="{{ $data['recipient_phone'] }}">
                        <input type="hidden" name="shipping_address" value="{{ $data['shipping_address'] }}">
                        <input type="hidden" name="postal_code" value="{{ $data['postal_code'] }}">
                        <input type="hidden" name="notes" value="{{ $data['notes'] }}">
                        <input type="hidden" name="subtotal" value="{{ $data['subtotal'] }}">
                        <input type="hidden" name="total" value="{{ $data['total'] }}">

                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h5 class="card-title">Ringkasan Biaya</h5>
                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($data['subtotal'], 0, ',', '.') }}</span>
                    </div>
                     <div class="d-flex justify-content-between">
                        <span>Ongkos Kirim</span>
                        <span>
                             @if($data['shipping_method'] == 'instant')
                                Ditanggung Pembeli
                            @else
                                Gratis
                            @endif
                        </span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold h5">
                        <span>Total</span>
                        <span>Rp {{ number_format($data['total'], 0, ',', '.') }}</span>
                    </div>

                    <div class="d-grid mt-4">
                         {{-- Tombol submit sekarang berada di luar form, tapi terhubung via 'form' attribute --}}
                        <button type="submit" class="btn btn-success btn-lg" id="submitBtn" form="checkoutForm">
                            <i class="fas fa-shield-alt me-2"></i>
                            <span>Lanjutkan Pembayaran</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Tambahkan sedikit style agar pilihan pembayaran lebih menarik --}}
<style>
    .card-radio {
        border: 1px solid #ddd;
        border-radius: .5rem;
        padding: 1rem;
        margin-bottom: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .card-radio:hover {
        background-color: #f8f9fa;
    }
    .card-radio input[type="radio"] {
        display: none;
    }
    .card-radio input[type="radio"]:checked + .form-check-label {
        border-color: var(--bs-success);
        box-shadow: 0 0 0 2px rgba(25, 135, 84, 0.25);
    }
    .form-check-label {
        width: 100%;
        border: 2px solid transparent;
        border-radius: .5rem;
        padding: 1rem;
    }
    .form-check-label span {
        font-weight: 600;
    }
</style>
@endpush



{{-- Ganti script di dalam @push('scripts') --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnIcon = submitBtn.querySelector('i');
    const submitBtnText = submitBtn.querySelector('span');

    function updateSubmitButton() {
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;

        if (selectedPayment === 'qris') {
            submitBtnIcon.className = 'fas fa-qrcode me-2';
            submitBtnText.textContent = 'Lanjutkan & Tampilkan QRIS';
        } else if (selectedPayment === 'transfer') {
            submitBtnIcon.className = 'fas fa-university me-2';
            submitBtnText.textContent = 'Lanjutkan & Lihat No. Rekening';
        } else if (selectedPayment === 'cash') {
            submitBtnIcon.className = 'fas fa-cash-register me-2';
            submitBtnText.textContent = 'Konfirmasi & Bayar di Kasir';
        }
    }

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', updateSubmitButton);
        radio.closest('.card-radio').addEventListener('click', function() {
            this.querySelector('input[type="radio"]').checked = true;
            updateSubmitButton();
        });
    });

    updateSubmitButton();
});
</script>

