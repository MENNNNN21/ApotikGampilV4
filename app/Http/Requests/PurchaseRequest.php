<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'obat_id' => 'required|exists:obat,id',
            'quantity' => 'required|integer|min:1',
            'shipping_method' => 'required|in:courier,instant,pickup',
            'notes' => 'nullable|string|max:500',
        ];

        // Add conditional validation based on shipping method
        $shippingMethod = $this->input('shipping_method');

        if ($shippingMethod === 'courier') {
            $rules = array_merge($rules, [
                'recipient_name' => 'required|string|max:255',
                'recipient_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string|max:500',
                'postal_code' => 'required|string|size:5',
                'city' => 'required|string|max:100',
                'district' => 'required|string|max:100',
                'courier_name' => 'required|string',
                'courier_service' => 'required|string',
                'shipping_cost' => 'required|numeric|min:0',
            ]);
        } elseif ($shippingMethod === 'instant') {
            $rules = array_merge($rules, [
                'instant_recipient_name' => 'required|string|max:255',
                'instant_recipient_phone' => 'required|string|max:20',
                'instant_shipping_address' => 'required|string|max:500',
                'instant_postal_code' => 'required|string|size:5',
            ]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'obat_id.required' => 'Produk harus dipilih.',
            'obat_id.exists' => 'Produk tidak ditemukan.',
            'quantity.required' => 'Jumlah harus diisi.',
            'quantity.min' => 'Jumlah minimal 1.',
            'shipping_method.required' => 'Metode pengiriman harus dipilih.',
            'shipping_method.in' => 'Metode pengiriman tidak valid.',
            
            // Courier validation messages
            'recipient_name.required' => 'Nama penerima harus diisi.',
            'recipient_phone.required' => 'Nomor telepon harus diisi.',
            'shipping_address.required' => 'Alamat pengiriman harus diisi.',
            'postal_code.required' => 'Kode pos harus diisi.',
            'postal_code.size' => 'Kode pos harus 5 digit.',
            'city.required' => 'Kota harus diisi.',
            'district.required' => 'Kecamatan harus diisi.',
            'courier_name.required' => 'Kurir harus dipilih.',
            'courier_service.required' => 'Layanan kurir harus dipilih.',
            'shipping_cost.required' => 'Ongkos kirim harus ada.',
            
            // Instant validation messages
            'instant_recipient_name.required' => 'Nama penerima untuk pengiriman instan harus diisi.',
            'instant_recipient_phone.required' => 'Nomor telepon untuk pengiriman instan harus diisi.',
            'instant_shipping_address.required' => 'Alamat untuk pengiriman instan harus diisi.',
            'instant_postal_code.required' => 'Kode pos untuk pengiriman instan harus diisi.',
            'instant_postal_code.size' => 'Kode pos harus 5 digit.',
        ];
    }
}