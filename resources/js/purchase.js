// resources/js/purchase.js (VERSI FINAL YANG SUDAH DIPERBAIKI)

document.addEventListener('DOMContentLoaded', function () {
    // --- (Bagian deklarasi variabel tetap sama) ---
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const districtSelect = document.getElementById('district');
    const courierSelect = document.getElementById('courier');
    const shippingOptionsDiv = document.getElementById('shippingOptions');
    const shippingCostSpinner = document.getElementById('shippingCostSpinner');
    const submitButton = document.getElementById('submitPurchase');

    const productPrice = parseFloat(document.querySelector('.h3.fw-bold.text-primary').textContent.replace(/[^0-9]/g, ''));
    let selectedShippingCost = 0;

    const showAlert = (message, type = 'danger') => {
        const alertPlaceholder = document.getElementById('alertPlaceholder');
        alertPlaceholder.innerHTML = '';
        const wrapper = document.createElement('div');
        wrapper.innerHTML = [
            `<div class="alert alert-${type} alert-dismissible" role="alert">`,
            `   <div>${message}</div>`,
            '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
            '</div>'
        ].join('');
        alertPlaceholder.append(wrapper);
    };
    
    // --- FUNGSI INI KITA PERBAIKI ---
    async function fetchAndPopulate(url, selectElement, defaultOptionText, dataKey, textKey, valueKey) {
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();

            // Pengecekan disesuaikan dengan struktur JSON baru
            if (!data || !data[dataKey]) throw new Error(`Struktur data tidak valid dari ${url}`);
            
            selectElement.innerHTML = `<option selected disabled value="">${defaultOptionText}</option>`;
            data[dataKey].forEach(item => {
                // Menggunakan textKey yang benar (yaitu 'name')
                const option = new Option(item[textKey], item[valueKey]);
                selectElement.add(option);
            });
            selectElement.disabled = false;
        } catch (error) {
            selectElement.innerHTML = `<option selected disabled value="">Gagal memuat</option>`;
            showAlert(`Gagal memuat data untuk ${defaultOptionText}. ${error.message}`);
            console.error(error);
        }
    }

    const purchaseModal = document.getElementById('purchaseModal');
    purchaseModal.addEventListener('shown.bs.modal', () => {
        // Panggil dengan textKey 'name' untuk Provinsi
        fetchAndPopulate('/api/purchase/provinces', provinceSelect, 'Pilih Provinsi', 'value', 'name', 'id');
    });

    provinceSelect.addEventListener('change', () => {
        citySelect.disabled = true;
        citySelect.innerHTML = '<option>Memuat kota...</option>';
        districtSelect.disabled = true;
        districtSelect.innerHTML = '<option>Pilih kota dulu</option>';
        courierSelect.disabled = true;
        resetShipping();
        // Panggil dengan textKey 'name' untuk Kota
        fetchAndPopulate(`/api/purchase/cities/${provinceSelect.value}`, citySelect, 'Pilih Kota/Kabupaten', 'value', 'name', 'id');
    });

    citySelect.addEventListener('change', () => {
        districtSelect.disabled = true;
        districtSelect.innerHTML = '<option>Memuat kecamatan...</option>';
        courierSelect.disabled = true;
        resetShipping();
        // Panggil dengan textKey 'name' untuk Kecamatan
        fetchAndPopulate(`/api/purchase/districts/${citySelect.value}`, districtSelect, 'Pilih Kecamatan', 'value', 'name', 'id');
    });

    // --- (Sisa kode ke bawah tetap sama, tidak perlu diubah) ---

    districtSelect.addEventListener('change', () => {
        courierSelect.disabled = false;
        courierSelect.value = '';
        resetShipping();
    });

    courierSelect.addEventListener('change', calculateShipping);

    async function calculateShipping() {
        if (!districtSelect.value || !courierSelect.value) return;

        shippingCostSpinner.classList.remove('d-none');
        shippingOptionsDiv.innerHTML = '<p class="text-muted">Menghitung ongkos kirim...</p>';
        resetShipping(false);

        try {
            const response = await fetch('/api/purchase/calculate-cost', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    destination: districtSelect.value,
                    weight: document.getElementById('productWeight').value,
                    courier: courierSelect.value
                })
            });
            
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || 'Gagal menghitung ongkir.');
            if (data.status.code !== 200) throw new Error(data.status.description);

            displayShippingOptions(data.results[0].costs);

        } catch (error) {
            shippingOptionsDiv.innerHTML = `<p class="text-danger">Gagal menghitung ongkir: ${error.message}</p>`;
        } finally {
            shippingCostSpinner.classList.add('d-none');
        }
    }

    function displayShippingOptions(costs) {
        shippingOptionsDiv.innerHTML = '';
        if (costs.length === 0) {
            shippingOptionsDiv.innerHTML = '<p class="text-warning">Tidak ada layanan pengiriman untuk tujuan ini.</p>';
            return;
        }
        costs.forEach((service, index) => {
            const cost = service.cost[0];
            const radioId = `service-${index}`;
            const radioWrapper = document.createElement('div');
            radioWrapper.className = 'form-check';
            radioWrapper.innerHTML = `
                <input class="form-check-input" type="radio" name="courier_service_option" id="${radioId}" value="${cost.value}" data-service-name="${service.service}">
                <label class="form-check-label" for="${radioId}">
                    <strong>${service.service}</strong> (${service.description})
                    <br>
                    <small class="text-muted">Estimasi: ${cost.etd} - Rp ${cost.value.toLocaleString('id-ID')}</small>
                </label>`;
            shippingOptionsDiv.appendChild(radioWrapper);
        });
        document.querySelectorAll('input[name="courier_service_option"]').forEach(radio => {
            radio.addEventListener('change', e => {
                selectedShippingCost = parseFloat(e.target.value);
                updateTotalCost();
                checkFormCompleteness();
            });
        });
    }

    function resetShipping(resetCourier = true) {
        shippingOptionsDiv.innerHTML = '<p class="text-muted">Pilih kurir untuk melihat opsi layanan.</p>';
        selectedShippingCost = 0;
        updateTotalCost();
        if (resetCourier) courierSelect.value = '';
        checkFormCompleteness();
    }

    function updateTotalCost() {
        const total = productPrice + selectedShippingCost;
        document.getElementById('shippingCostText').textContent = `Rp ${selectedShippingCost.toLocaleString('id-ID')}`;
        document.getElementById('totalCostText').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }

    const form = document.getElementById('purchaseForm');
    form.addEventListener('input', checkFormCompleteness);

    function checkFormCompleteness() {
        const isComplete = [...form.querySelectorAll('[required]')].every(input => input.value) 
                           && !!document.querySelector('input[name="courier_service_option"]:checked');
        submitButton.disabled = !isComplete;
        submitButton.querySelector('#submitBtnText').textContent = isComplete ? 'Proses Pembayaran' : 'Lengkapi Form';
    }

    submitButton.addEventListener('click', async function(e) {
        e.preventDefault();
        this.disabled = true;
        const btnText = this.querySelector('#submitBtnText');
        const btnSpinner = this.querySelector('#submitBtnSpinner');
        btnText.classList.add('d-none');
        btnSpinner.classList.remove('d-none');

        const formData = new FormData(form);
        const selectedService = document.querySelector('input[name="courier_service_option"]:checked');
        formData.append('shipping_cost', selectedShippingCost);
        formData.append('courier_service', selectedService.dataset.serviceName);
        formData.set('recipient_province', provinceSelect.options[provinceSelect.selectedIndex].text);
        formData.set('recipient_city', citySelect.options[citySelect.selectedIndex].text);
        formData.set('recipient_district', districtSelect.options[districtSelect.selectedIndex].text);

        try {
            const response = await fetch('/api/purchase/process-order', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json' },
                body: JSON.stringify(Object.fromEntries(formData))
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.error || 'Terjadi kesalahan server.');
            
            bootstrap.Modal.getInstance(purchaseModal).hide();
            alert(`Pesanan berhasil! Nomor Pesanan Anda: ${result.order_id}`);
            window.location.reload();
        } catch (error) {
            showAlert(error.message, 'danger');
        } finally {
            this.disabled = false;
            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');
        }
    });
});