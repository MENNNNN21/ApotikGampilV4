<div class="row">
    <div class="col-md-6 mb-3">
        <label for="recipient_name" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('recipient_name') is-invalid @enderror" 
               id="recipient_name" name="recipient_name" 
               value="{{ old('recipient_name', auth()->user()->name) }}" required>
        @error('recipient_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="recipient_phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('recipient_phone') is-invalid @enderror" 
               id="recipient_phone" name="recipient_phone" 
               value="{{ old('recipient_phone') }}" placeholder="08xxxxxxxxxx" required>
        @error('recipient_phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label for="shipping_address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
        <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                  id="shipping_address" name="shipping_address" rows="3" 
                  placeholder="Masukkan alamat lengkap..." required>{{ old('shipping_address') }}</textarea>
        @error('shipping_address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

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
