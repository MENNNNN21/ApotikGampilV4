@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Checkout</h2>
    <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
        @csrf
        {{-- Ringkasan Pesanan --}}
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-2">
                    <img src="{{ asset('storage/' . $obat->gambar) }}" class="img-fluid rounded-start" alt="{{ $obat->nama }}">
                </div>
                <div class="col-md-10">
                    <div class="card-body">
                        <h5 class="card-title">{{ $obat->nama }}</h5>
                        <p class="card-text">Harga: Rp{{ number_format($obat->harga, 0, ',', '.') }}</p>
                        <p class="card-text">Jumlah: {{ $quantity }}</p>
                        <p class="card-text">Subtotal: <span id="subtotal">Rp{{ number_format($obat->harga * $quantity, 0, ',', '.') }}</span></p>
                        <p class="card-text">Biaya Pengiriman: <span id="shipping_cost">Rp0</span></p>
                        <p class="card-text">Total: <span id="total">Rp{{ number_format($obat->harga * $quantity, 0, ',', '.') }}</span></p>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="obat_id" value="{{ $obat->id }}">
        <input type="hidden" name="quantity" value="{{ $quantity }}">

        {{-- Pilihan Metode Pengiriman --}}
        <div class="mb-3">
            <label class="form-label">Metode Pengiriman:</label><br>
            <input type="radio" name="shipping_method" value="pickup" id="pickup" checked> 
            <label for="pickup">Ambil di Apotek</label>
            &nbsp;
            <input type="radio" name="shipping_method" value="delivery" id="delivery"> 
            <label for="delivery">Kirim ke Alamat</label>
        </div>

        {{-- Formulir Alamat (hanya jika delivery) --}}
        <div id="addressFields" style="display: none;">
            <div class="mb-2">
                <label>Nama Penerima</label>
                <input type="text" name="recipient_name" class="form-control">
            </div>
            <div class="mb-2">
                <label>Telepon Penerima</label>
                <input type="text" name="recipient_phone" class="form-control">
            </div>
            <div class="mb-2">
                <label>Alamat Pengiriman</label>
                <textarea name="shipping_address" class="form-control"></textarea>
            </div>
            <div class="mb-2">
                <label>Kode Pos</label>
                <input type="text" name="postal_code" class="form-control" id="postal_code">
            </div>
            <div class="mb-2">
                <label>Kota</label>
                <input type="text" name="city" class="form-control" id="city">
            </div>
            <div class="mb-2">
                <label>Kecamatan</label>
                <input type="text" name="district" class="form-control" id="district">
            </div>
            {{-- Opsi Kurir --}}
            <div class="mb-2">
                <label>Kurir</label>
                <select name="courier" class="form-control">
                    <option value="instant">Instan</option>
                    <option value="regular">Reguler</option>
                </select>
            </div>
        </div>

        {{-- Pilihan Metode Pembayaran --}}
        <div class="mb-3">
            <label class="form-label">Metode Pembayaran:</label><br>
            <div id="payment-methods-pickup">
                <input type="radio" name="payment_method" value="cod" id="cod" checked>
                <label for="cod">Bayar di Tempat</label>
            </div>
            <div id="payment-methods-delivery" style="display: none;">
                <input type="radio" name="payment_method" value="qris" id="qris">
                <label for="qris">QRIS</label>
                <input type="radio" name="payment_method" value="transfer" id="transfer">
                <label for="transfer">Transfer Bank</label>
            </div>
        </div>

        {{-- Upload Bukti Pembayaran (hanya jika qris/transfer) --}}
        <div id="payment-proof-section" style="display: none;">
            <div class="mb-2" id="qris-info" style="display: none;">
                <img src="{{ asset('images/qris.png') }}" alt="QRIS" style="max-width:200px;">
            </div>
            <div class="mb-2" id="transfer-info" style="display: none;">
                <p>Transfer ke: BCA 123456789 a.n. Apotik Gampil</p>
                <p>Transfer ke: Mandiri 987654321 a.n. Apotik Gampil</p>
            </div>
            <div class="mb-2">
                <label>Upload Bukti Pembayaran</label>
                <input type="file" name="payment_proof" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-success">Proses Pesanan</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateShippingFields() {
        const shippingMethod = document.querySelector('input[name="shipping_method"]:checked').value;
        if (shippingMethod === 'delivery') {
            document.getElementById('addressFields').style.display = '';
            document.getElementById('payment-methods-pickup').style.display = 'none';
            document.getElementById('payment-methods-delivery').style.display = '';
            // Set default payment method to qris
            document.getElementById('qris').checked = true;
            updatePaymentProofSection();
            document.getElementById('shipping_cost').innerText = 'Rp15.000';
            document.getElementById('total').innerText = 'Rp' + ({{ $obat->harga * $quantity }} + 15000).toLocaleString('id-ID');
        } else {
            document.getElementById('addressFields').style.display = 'none';
            document.getElementById('payment-methods-pickup').style.display = '';
            document.getElementById('payment-methods-delivery').style.display = 'none';
            document.getElementById('cod').checked = true;
            document.getElementById('payment-proof-section').style.display = 'none';
            document.getElementById('shipping_cost').innerText = 'Rp0';
            document.getElementById('total').innerText = 'Rp' + ({{ $obat->harga * $quantity }}).toLocaleString('id-ID');
        }
    }
    function updatePaymentProofSection() {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        if (paymentMethod === 'qris') {
            document.getElementById('payment-proof-section').style.display = '';
            document.getElementById('qris-info').style.display = '';
            document.getElementById('transfer-info').style.display = 'none';
        } else if (paymentMethod === 'transfer') {
            document.getElementById('payment-proof-section').style.display = '';
            document.getElementById('qris-info').style.display = 'none';
            document.getElementById('transfer-info').style.display = '';
        } else {
            document.getElementById('payment-proof-section').style.display = 'none';
        }
    }
    document.querySelectorAll('input[name="shipping_method"]').forEach(el => {
        el.addEventListener('change', updateShippingFields);
    });
    document.querySelectorAll('input[name="payment_method"]').forEach(el => {
        el.addEventListener('change', updatePaymentProofSection);
    });
    updateShippingFields();
    updatePaymentProofSection();

    // Integrasi Biteship (dummy, ganti sesuai implementasi Anda)
    document.getElementById('postal_code').addEventListener('blur', function() {
        // Panggil API Biteship di sini, lalu isi city dan district
        // Contoh dummy:
        document.getElementById('city').value = 'Bandung';
        document.getElementById('district').value = 'Sadang Serang';
    });
});
</script>
@endsection