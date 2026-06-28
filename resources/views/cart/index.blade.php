@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body {
        background-color: #f8fafc;
        font-family: 'Inter', sans-serif;
    }
    .cart-container {
        max-width: 960px;
        margin: 40px auto;
        background: #ffffff;
        padding: 40px;
        border-radius: 8px;
    }
    .back-arrow {
        color: #1e293b;
        font-size: 1.25rem;
        text-decoration: none;
    }
    .cart-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }
    .table-header-text {
        font-size: 0.9rem;
        font-weight: 600;
        color: #475569;
    }
    .cart-item-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 24px 20px;
        margin-bottom: 16px;
    }
    .custom-checkbox {
        width: 18px;
        height: 18px;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        cursor: pointer;
    }
    .img-placeholder-box {
        width: 64px;
        height: 64px;
        background-color: #f1f5f9;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 1.5rem;
    }
    .product-name {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }
    .product-category {
        font-size: 0.75rem;
        color: #94a3b8;
    }
    .price-text {
        font-size: 1.15rem;
        font-weight: 700;
        color: #14b8a6;
    }
    .btn-qty-minus, .btn-qty-plus {
        background-color: #6366f1;
        color: #ffffff;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }
    .btn-qty-minus:disabled, .btn-qty-plus:disabled {
        background-color: #c7d2fe;
        cursor: not-allowed;
    }
    .qty-display-number {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        width: 45px;
        text-align: center;
    }
    .cart-footer-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 24px 20px;
        margin-top: 24px;
    }
    .btn-action-delete {
        color: #ef4444;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        transition: color 0.2s ease-in-out;
    }
    .btn-action-delete:hover {
        color: #b91c1c;
    }
    .total-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }
    .btn-checkout-submit {
        background-color: #6366f1;
        color: #ffffff;
        border: none;
        padding: 10px 36px;
        border-radius: 6px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .btn-checkout-submit:hover {
        background-color: #4f46e5;
    }

    .swal2-confirm-custom      { background-color: #ef4444 !important; color: white !important; border-radius: 6px !important; padding: 10px 24px !important; font-weight: 600 !important; }
    .swal2-cancel-custom       { background-color: #f1f5f9 !important; color: #475569 !important; border-radius: 6px !important; padding: 10px 24px !important; font-weight: 600 !important; margin-right: 10px !important; }
    .swal2-confirm-checkout    { background-color: #6366f1 !important; color: white !important; border-radius: 6px !important; padding: 10px 24px !important; font-weight: 600 !important; }
    .swal2-confirm-stock-error { background-color: #6366f1 !important; color: white !important; border-radius: 6px !important; padding: 10px 24px !important; font-weight: 600 !important; }
</style>

<div class="container">
    <div class="cart-container shadow-sm border border-light">

        {{-- Header --}}
        <div class="position-relative d-flex align-items-center justify-content-center mb-5">
            <a href="{{ route('products.index') }}" class="back-arrow position-absolute start-0">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div class="cart-title">Keranjang</div>
        </div>

        {{-- ===== POP-UP SESSION SUCCESS ===== --}}
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                });
            </script>
        @endif

        {{-- ===== POP-UP SESSION ERROR (dari controller) ===== --}}
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Stok Tidak Mencukupi',
                        text: '{{ session('error') }}',
                        confirmButtonText: 'Mengerti',
                        customClass: { confirmButton: 'swal2-confirm-stock-error' },
                        buttonsStyling: false,
                    });
                });
            </script>
        @endif

        {{-- ===== HEADER TABEL ===== --}}
        <div class="row mb-3 px-3 text-center d-none d-md-flex align-items-center">
            <div class="col-md-4 text-start" style="padding-left: 52px;">
                <span class="table-header-text">Produk</span>
            </div>
            <div class="col-md-3">
                <span class="table-header-text">Harga Satuan</span>
            </div>
            <div class="col-md-2">
                <span class="table-header-text">Kuantitas</span>
            </div>
            <div class="col-md-3 text-end" style="padding-right: 25px;">
                <span class="table-header-text">Total Harga</span>
            </div>
        </div>

        {{-- ===== DAFTAR ITEM KERANJANG ===== --}}
        @if($cart && !$cart->items->isEmpty())
            @foreach($cart->items as $item)
                <div class="cart-item-box row g-0 align-items-center text-center">

                    {{-- Produk --}}
                    <div class="col-md-4 text-start d-flex align-items-center gap-3">
                        <input type="checkbox" class="form-check-input custom-checkbox m-0">

                        <div class="img-placeholder-box flex-shrink-0">
                            @php
                                $img = is_array($item->product->image)
                                    ? ($item->product->image[0] ?? null)
                                    : $item->product->image;
                            @endphp
                            @if($img)
                                <img src="{{ asset('storage/' . $img) }}" alt=""
                                     style="width:100%;height:100%;object-fit:cover;border-radius:4px;">
                            @else
                                <i class="fa-regular fa-image"></i>
                            @endif
                        </div>

                        <div>
                            <div class="product-name">{{ $item->product->name }}</div>
                            <div class="product-category">{{ $item->product->category->name ?? 'Kategori Umum' }}</div>
                        </div>
                    </div>

                    {{-- Harga Satuan --}}
                    <div class="col-md-3">
                        <div class="price-text">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                    </div>

                    {{-- Kuantitas --}}
                    <div class="col-md-2 d-flex flex-column align-items-center justify-content-center">
                        <form action="{{ route('cart.update', $item->product_id) }}" method="POST"
                              class="d-flex align-items-center justify-content-center m-0">
                            @csrf
                            @method('PATCH')

                            {{--
                                data-stock  = stok asli dari DB (tidak pernah disentuh cart)
                                data-in-cart = qty item ini yang sudah ada di keranjang
                            --}}
                            <button type="button"
                                    class="btn-qty-minus"
                                    onclick="changeQtyValue(this, -1)"
                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                <i class="fa-solid fa-minus"></i>
                            </button>

                            <input type="hidden" name="quantity"
                                   value="{{ $item->quantity }}"
                                   class="qty-hidden-input">

                            <span class="qty-display-number mx-2"
                                  style="width:30px;display:inline-block;">
                                {{ $item->quantity }}
                            </span>

                            <button type="button"
                                    class="btn-qty-plus"
                                    onclick="changeQtyValue(this, 1)"
                                    data-stock="{{ $item->product->stock }}"
                                    data-product-name="{{ $item->product->name }}"
                                    {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </form>

                        {{-- Label stok tersisa di bawah tombol qty --}}
                        <small class="text-muted mt-1" style="font-size:0.72rem;">
                            Stok tersedia: <strong>{{ $item->product->stock }}</strong>
                        </small>
                    </div>

                    {{-- Total Harga + Hapus --}}
                    <div class="col-md-3 text-end" style="padding-right:5px;">
                        <div class="price-text">
                            Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                        </div>

                        <form action="{{ route('cart.remove', $item->product_id) }}" method="POST"
                              class="mt-2 m-0">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    onclick="confirmDelete(this, '{{ $item->product->name }}')"
                                    class="btn-action-delete"
                                    style="font-size:0.85rem;">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach
        @else
            <div class="text-center py-5 border rounded-3"
                 style="border-color:#cbd5e1;border-style:dashed;border-width:2px;">
                <i class="fa-solid fa-basket-shopping text-muted mb-3"
                   style="font-size:3rem;color:#94a3b8;opacity:0.5;"></i>
                <p class="text-secondary mb-0" style="color:#64748b;">
                    Belum ada barang di dalam keranjang Anda.
                </p>
            </div>
        @endif

        {{-- ===== FOOTER CART ===== --}}
        @if($cart && !$cart->items->isEmpty())
            <div class="cart-footer-box row g-0 align-items-center">

                <div class="col-sm-5 d-flex align-items-center gap-3">
                    <input type="checkbox" class="form-check-input custom-checkbox m-0">
                    <span class="fw-semibold text-dark" style="font-size:0.95rem;">
                        Pilih Semua ({{ $cart->items->count() }})
                    </span>
                </div>

                <div class="col-sm-7 d-flex align-items-center justify-content-sm-end gap-4 mt-3 mt-sm-0">
                    <div class="text-end">
                        @php
                            $totalCheckoutPrice = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);
                        @endphp
                        <div class="total-label">Total ({{ $cart->items->sum('quantity') }} Produk)</div>
                        <div class="price-text" style="font-size:1.35rem;">
                            Rp {{ number_format($totalCheckoutPrice, 0, ',', '.') }}
                        </div>
                    </div>

                    <form action="{{ route('checkout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="button" class="btn-checkout-submit" onclick="confirmCheckout(this)">
                            Checkout Sekarang
                        </button>
                    </form>
                </div>

            </div>
        @endif

    </div>
</div>

<script>
    
    function changeQtyValue(button, direction) {
        const form          = button.closest('form');
        const hiddenInput   = form.querySelector('.qty-hidden-input');
        const displaySpan   = form.querySelector('.qty-display-number');
        const minusBtn      = form.querySelector('.btn-qty-minus');
        const plusBtn       = form.querySelector('.btn-qty-plus');

        const stock       = parseInt(plusBtn.getAttribute('data-stock'))  1;
        const productName = plusBtn.getAttribute('data-product-name') 'produk ini';
        let   currentVal  = parseInt(hiddenInput.value)1;
        const newVal      = currentVal + direction;

        if (newVal < 1) return;
        if (newVal > stock) {
            Swal.fire({
                icon: 'error',
                title: 'Stok Tidak Mencukupi',
                html:
                    `Stok <b>${productName}</b> hanya tersisa <b>${stock}</b>.<br>` +
                    `Anda sudah memasukkan <b>${currentVal}</b> ke keranjang.<br><br>` +
                    `Tidak bisa menambah lebih dari <b>${stock}</b>.`,
                confirmButtonText: 'Mengerti',
                customClass: { confirmButton: 'swal2-confirm-stock-error' },
                buttonsStyling: false,
            });
            return; 
        }

        hiddenInput.value   = newVal;
        displaySpan.innerText = newVal;

        // Disable tombol minus jika sudah di angka 1
        minusBtn.disabled = (newVal <= 1);
        // Disable tombol plus jika sudah menyentuh stok maksimal
        plusBtn.disabled  = (newVal >= stock);

        form.submit();
    }
    function confirmDelete(button, productName) {
        Swal.fire({
            title: 'Hapus Produk?',
            html: `Apakah Anda yakin ingin menghapus <b>${productName}</b> dari keranjang?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'swal2-confirm-custom',
                cancelButton:  'swal2-cancel-custom',
            },
            buttonsStyling: false,
        }).then((result) => {
            if (result.isConfirmed) button.closest('form').submit();
        });
    }

    function confirmCheckout(button) {
        if (button.disabled) return;

        Swal.fire({
            title: 'Konfirmasi Pesanan',
            text: 'Apakah Anda sudah yakin dengan pesanan ini dan ingin melanjutkan ke pembayaran?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjut Checkout',
            cancelButtonText: 'Cek Lagi',
            reverseButtons: true,
            customClass: {
                confirmButton: 'swal2-confirm-checkout',
                cancelButton:  'swal2-cancel-custom',
            },
            buttonsStyling: false,
        }).then((result) => {
            if (result.isConfirmed) button.closest('form').submit();
        });
    }
</script>
@endsection