{{-- resources/views/products/show.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Detail Produk</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <style>
        body{
            background:#f4f6f8;
            font-family: 'Segoe UI', sans-serif;
        }

        .product-wrapper{
            background:white;
            border-radius:12px;
            padding:40px;
            margin-top:40px;
            box-shadow:0 4px 12px rgba(0,0,0,0.08);
        }

        .back-btn{
            color:#333;
            text-decoration:none;
            font-size:20px;
        }

        .product-title{
            font-weight:700;
            font-size:32px;
        }

        .category-badge{
            background:#eef1f5;
            padding:8px 14px;
            border-radius:8px;
            font-size:14px;
            color:#666;
        }

        .product-image{
            width:100%;
            height:420px;
            background:#e9edf2;
            border-radius:12px;
            display:flex;
            align-items:center;
            justify-content:center;
            position:relative;
            overflow:hidden;
        }

        .product-image img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        .slider-btn{
            position:absolute;
            top:50%;
            transform:translateY(-50%);
            width:45px;
            height:45px;
            border:none;
            border-radius:50%;
            background:white;
            box-shadow:0 2px 10px rgba(0,0,0,0.15);
        }

        .slider-btn.left{
            left:20px;
        }

        .slider-btn.right{
            right:20px;
        }

        .description-box{
            background:#f6f8fa;
            padding:25px;
            border-radius:10px;
            height:100%;
        }

        .description-box h5{
            color:#1fb5a9;
            font-weight:700;
            margin-bottom:20px;
        }

        .stock-box,
        .qty-box{
            border:1px solid #dcdcdc;
            border-radius:8px;
            height:55px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:20px;
            font-weight:600;
            background:white;
        }

        .qty-box button{
            border:none;
            background:none;
            font-size:22px;
            width:40px;
        }

        /* Mengatur agar input number bawaan tidak terlihat seperti kolom input biasa */
        .qty-input-hidden {
            width: 50px;
            border: none;
            text-align: center;
            font-weight: 600;
            font-size: 20px;
            background: transparent;
            outline: none;
        }
        
        /* Menghilangkan arrow spinner bawaan browser pada input number */
        .qty-input-hidden::-webkit-outer-spin-button,
        .qty-input-hidden::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .price-box{
            border:1px solid #dcdcdc;
            border-radius:10px;
            padding:20px;
            margin-top:20px;
            background:white;
        }

        .price-label{
            color:#777;
            font-size:14px;
        }

        .price{
            font-size:38px;
            color:#16c79a;
            font-weight:800;
        }

        .btn-buy{
            background:#111827; /* Warna gelap Charcoal sesuai prototype */
            color:white;
            border:none;
            padding:12px 22px;
            border-radius:8px;
            font-weight:600;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-cart{
            border:2px solid #111827; /* Outline Charcoal menyesuaikan prototype */
            color:#111827;
            background:white;
            padding:10px 22px;
            border-radius:8px;
            font-weight:600;
        }

        footer{
            background:#dfe5ea;
            margin-top:70px;
            padding:50px 0;
        }

        .footer-title{
            font-weight:800;
            font-size:22px;
        }

        .footer-menu a{
            display:block;
            text-decoration:none;
            color:#444;
            margin-bottom:12px;
        }

        @media(max-width:768px){
            .product-title{
                font-size:24px;
            }

            .product-wrapper{
                padding:20px;
            }

            .price{
                font-size:28px;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <div class="product-wrapper">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('products.index') }}" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i>
            </a>

            <h2 class="fw-bold m-0">Details</h2>

            <div></div>
        </div>

        {{-- Flash Notification Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-xmark me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Product Title Dinamis --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="product-title m-0">{{ $product->name }}</h1>

            <span class="category-badge">
                {{ $product->category->name ?? 'Peralatan Kantor' }}
            </span>
        </div>

        {{-- Product Image Dinamis (Aman dari Array to String Error) --}}
        <div class="product-image mb-5">
            @php
                $displayImage = null;
                if (is_array($product->image) && count($product->image) > 0) {
                    if (!empty($product->image[0]) && trim($product->image[0]) !== '') {
                        $displayImage = $product->image[0];
                    }
                } elseif (is_string($product->image) && !empty($product->image) && trim($product->image) !== '') {
                    $displayImage = $product->image;
                }
            @endphp

            @if($displayImage)
                <img src="{{ asset('storage/' . $displayImage) }}" alt="{{ $product->name }}">
            @else
                <div class="text-center">
                    <i class="fa-regular fa-image text-muted" style="font-size: 5rem;"></i>
                    <p class="text-secondary small mt-2 mb-0">Gambar produk belum tersedia</p>
                </div>
            @endif

            <button class="slider-btn left">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <button class="slider-btn right">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        {{-- Form Manajemen Order / Keranjang --}}
        <form action="{{ route('cart.add', $product->id) }}" method="POST">
            @csrf
            
            {{-- Product Content Layout --}}
            <div class="row g-4">

                {{-- Description Box --}}
                <div class="col-lg-6">
                    <div class="description-box">
                        <h5>Deskripsi Produk</h5>
                        <p class="text-secondary lh-base" style="text-align: justify;">
                            {{ $product->description ?? 'Tidak ada deskripsi tertulis untuk produk ini.' }}
                        </p>
                    </div>
                </div>

                {{-- Detail Transaksi Panel --}}
                <div class="col-lg-6">

                    <div class="row mb-4">
                        {{-- Sektor Info Stok Gudang --}}
                        <div class="col-6">
                            <label class="mb-2 fw-semibold">Stok</label>
                            <div class="stock-box">
                                {{ $product->stock }}
                            </div>
                        </div>

                        {{-- Sektor Interaktif Quantity Counter --}}
                        <div class="col-6">
                            <label class="mb-2 fw-semibold">Kuantitas</label>
                            <div class="qty-box">
                                <button type="button" onclick="decreaseQty()">-</button>
                                
                                {{-- Input Tersembunyi Berbentuk Teks agar Nilai Masuk ke Request POST --}}
                                <input type="number" 
                                       id="quantity" 
                                       name="quantity" 
                                       class="qty-input-hidden" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->stock }}" 
                                       readonly 
                                       required>

                                <button type="button" onclick="increaseQty()">+</button>
                            </div>
                        </div>
                    </div>

                    {{-- Price & Action Buttons Box --}}
                    <div class="price-box">

                        <div class="price-label">
                            Satuan Harga
                        </div>

                        <div class="price mb-4">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>

                        <div class="d-flex gap-3 flex-wrap">
                            @if($product->stock > 0 && $product->is_active)
                                <button type="submit" class="btn-buy">
                                    Masukkan Keranjang
                                </button>
                                
                                <a href="{{ route('products.index') }}" class="btn-cart">
                                    Order
                                </a>
                            @else
                                <div class="alert alert-light border w-100 py-2 text-center text-muted mb-0" role="alert" style="font-size: 0.95rem;">
                                    <i class="fa-solid fa-slash-circle me-2"></i> Produk Tidak Tersedia
                                </div>
                            @endif
                        </div>

                    </div>

                </div>

            </div>
        </form>

    </div>

</div>

{{-- Footer Instansi --}}
<footer>
    <div class="container">
        <div class="row">

            <div class="col-lg-4 mb-4">
                <div class="bg-white rounded p-3 d-inline-block mb-3">
                    <i class="fa-regular fa-image"></i>
                </div>

                <h5 class="footer-title">
                    MEMBANGUN KUALITAS DAN MENYEDIAKAN KEPERCAYAAN
                </h5>

                <p class="mt-3">
                    Jl MH Thamrin 3/10, Desa Tlogobendung, Gresik, Jawa Timur
                </p>

                <p>+62 882-1712-6768</p>

                <p>ABBABAROKAH@gmail.com</p>
            </div>

            <div class="col-lg-4 footer-menu ps-lg-5">
                <a href="#">Home</a>
                <a href="#">About us</a>
                <a href="#">Purchase</a>
                <a href="#">Contact</a>
            </div>

            <div class="col-lg-4 footer-menu">
                <a href="#">Product</a>
                <a href="#">Peralatan Kantor</a>
                <a href="#">Souvenir</a>
            </div>

        </div>
    </div>
</footer>

{{-- Script JS Counter Quantity Menyesuaikan Limitasi Stok Database --}}
<script>
    function increaseQty(){
        const qtyInput = document.getElementById('quantity');
        const maxStock = parseInt(qtyInput.getAttribute('max')) || 1;
        let currentQty = parseInt(qtyInput.value) || 1;

        if(currentQty < maxStock) {
            qtyInput.value = currentQty + 1;
        }
    }

    function decreaseQty(){
        const qtyInput = document.getElementById('quantity');
        let currentQty = parseInt(qtyInput.value) || 1;

        if(currentQty > 1){
            qtyInput.value = currentQty - 1;
        }
    }
</script>

{{-- Bootstrap JS Script dependency --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>