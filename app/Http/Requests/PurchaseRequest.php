<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'obat_id' => 'required|exists:obat,id',
            'quantity' => 'required|integer|min:1|max:100',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|regex:/^(\+62|62|0)[0-9]{9,13}$/',
            'shipping_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'postal_code' => 'required|string|regex:/^[0-9]{5}$/',
            'courier_name' => 'required|string|max:50',
            'courier_service' => 'required|string|max:50',
            'shipping_cost' => 'required|numeric|min:0',
            'courier_details' => 'required|json',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'obat_id.required' => 'Produk harus dipilih.',
            'obat_id.exists' => 'Produk tidak ditemukan.',
            'quantity.required' => 'Jumlah pembelian harus diisi.',
            'quantity.integer' => 'Jumlah pembelian harus berupa angka.',
            'quantity.min' => 'Jumlah pembelian minimal 1.',
            'quantity.max' => 'Jumlah pembelian maksimal 100.',
            'recipient_name.required' => 'Nama penerima harus diisi.',
            'recipient_name.max' => 'Nama penerima maksimal 255 karakter.',
            'recipient_phone.required' => 'Nomor telepon harus diisi.',
            'recipient_phone.regex' => 'Format nomor telepon tidak valid. Gunakan format: 08xxxxxxxxxx atau +628xxxxxxxxxx',
            'shipping_address.required' => 'Alamat pengiriman harus diisi.',
            'shipping_address.max' => 'Alamat pengiriman maksimal 500 karakter.',
            'city.required' => 'Kota harus diisi.',
            'city.max' => 'Kota maksimal 100 karakter.',
            'district.required' => 'Kecamatan harus diisi.',
            'district.max' => 'Kecamatan maksimal 100 karakter.',
            'postal_code.required' => 'Kode pos harus diisi.',
            'postal_code.regex' => 'Kode pos harus 5 digit angka.',
            'courier_name.required' => 'Kurir harus dipilih.',
            'courier_service.required' => 'Layanan kurir harus dipilih.',
            'shipping_cost.required' => 'Ongkos kirim harus diisi.',
            'shipping_cost.numeric' => 'Ongkos kirim harus berupa angka.',
            'shipping_cost.min' => 'Ongkos kirim tidak valid.',
            'courier_details.required' => 'Detail kurir harus diisi.',
            'courier_details.json' => 'Format detail kurir tidak valid.',
            'notes.max' => 'Catatan maksimal 500 karakter.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean phone number
        if ($this->has('recipient_phone')) {
            $phone = $this->input('recipient_phone');
            // Remove all non-numeric characters except +
            $phone = preg_replace('/[^0-9+]/', '', $phone);
            $this->merge(['recipient_phone' => $phone]);
        }

        // Clean postal code
        if ($this->has('postal_code')) {
            $postalCode = preg_replace('/[^0-9]/', '', $this->input('postal_code'));
            $this->merge(['postal_code' => $postalCode]);
        }
    }

    /**
     * Get validated data with additional processing
     */
    public function getProcessedData(): array
    {
        $data = $this->validated();
        
        // Decode courier details
        $data['courier_details'] = json_decode($data['courier_details'], true);
        
        return $data;
    }
}