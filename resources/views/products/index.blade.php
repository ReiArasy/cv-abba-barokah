
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV ABBA BAROKAH</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: Arial, sans-serif;
        }

        body{
            background:#f5f5f5;
            color:#1f2937;
        }

        a{
            text-decoration:none;
        }

        /* NAVBAR */
        .navbar{
            position:absolute;
            top:0;
            left:0;
            width:100%;
            padding:20px 60px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            z-index:100;
        }

        .logo{
            width:60px;
            height:60px;
            background:white;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:4px;
        }

        .nav-links{
            display:flex;
            gap:30px;
        }

        .nav-links a{
            color:white;
            font-size:14px;
            font-weight:bold;
        }

        /* HERO */
        .hero{
            height:100vh;
            background:
                linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                url('https://images.unsplash.com/photo-1565008447742-97f6f38c985c?q=80&w=1470&auto=format&fit=crop');
            background-size:cover;
            background-position:center;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
            padding:20px;
        }

        .hero-content{
            color:white;
            max-width:800px;
        }

        .hero h1{
            font-size:20px;
            margin-bottom:10px;
            letter-spacing:2px;
        }

        .hero h2{
            font-size:48px;
            line-height:1.3;
            margin-bottom:30px;
            font-weight:bold;
        }

        .search-box{
            margin-top:20px;
        }

        .search-box input{
            width:350px;
            max-width:100%;
            padding:14px;
            border:none;
            border-radius:4px;
        }

        /* PRODUCTS */
        .products-section{
            padding:80px 60px;
            background:white;
        }

        .section-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:40px;
        }

        .section-header h2{
            font-size:40px;
        }

        .btn{
            background:#19c5a5;
            color:white;
            padding:12px 24px;
            border-radius:5px;
            font-weight:bold;
        }

        .product-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));
            gap:25px;
        }

        .product-card{
            background:#f9f9f9;
            border:2px solid #d1d5db;
            padding:20px;
            transition:0.3s;
        }

        .product-card:hover{
            transform:translateY(-5px);
            border-color:#19c5a5;
        }

        .product-image{
            width:100%;
            height:220px;
            object-fit:cover;
            background:#e5e7eb;
            margin-bottom:15px;
        }

        .product-card h3{
            font-size:18px;
            margin-bottom:10px;
        }

        .category{
            color:#6b7280;
            margin-bottom:10px;
        }

        .price{
            color:#19c5a5;
            font-size:20px;
            font-weight:bold;
            margin-bottom:15px;
        }

        /* FEATURE */
        .feature-section{
            padding:80px 60px;
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:40px;
            align-items:center;
            background:#f3f4f6;
        }

        .feature-image img{
            width:100%;
            border-radius:10px;
        }

        .feature-content h4{
            color:#19c5a5;
            margin-bottom:10px;
        }

        .feature-content h2{
            font-size:42px;
            margin-bottom:20px;
        }

        .feature-content p{
            line-height:1.8;
            margin-bottom:30px;
        }

        /* CTA */
        .cta{
            background:#e5e7eb;
            padding:100px 20px;
            text-align:center;
        }

        .cta h2{
            font-size:50px;
            margin:20px 0;
        }

        .cta p{
            max-width:700px;
            margin:auto;
            line-height:1.8;
            margin-bottom:30px;
        }

        /* FOOTER */
        footer{
            background:#cbd5e1;
            padding:60px;
        }

        .footer-grid{
            display:grid;
            grid-template-columns:2fr 1fr 1fr;
            gap:40px;
        }

        footer h3{
            margin-bottom:20px;
        }

        footer p,
        footer a{
            color:#374151;
            margin-bottom:10px;
            display:block;
        }

        @media(max-width:768px){

            .navbar{
                padding:20px;
                flex-direction:column;
                gap:20px;
            }

            .hero h2{
                font-size:32px;
            }

            .products-section,
            .feature-section,
            footer{
                padding:40px 20px;
            }

            .feature-section{
                grid-template-columns:1fr;
            }

            .footer-grid{
                grid-template-columns:1fr;
            }
        }

    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">

        <div class="logo">
            <strong>AB</strong>
        </div>

        <div class="nav-links">
            <a href="#">Home</a>
            <a href="#products">Product</a>
            <a href="#about">About Us</a>
            <a href="#contact">Contact</a>
        </div>

    </nav>


    <!-- HERO -->
    <section class="hero">

        <div class="hero-content">

            <h1>CV ABBA BAROKAH</h1>

            <h2>
                Solusi terbaik untuk proyek Anda! <br>
                menyediakan berbagai produk berkualitas
                dengan pelayanan terpercaya
            </h2>

            <div class="search-box">
                <input type="text" placeholder="Search product...">
            </div>

        </div>

    </section>


    <!-- PRODUCTS -->
    <section class="products-section" id="products">

        <div class="section-header">

            <h2>Products</h2>

            <a href="{{ route('products.index') }}" class="btn">
                View All
            </a>

        </div>

        <div class="product-grid">

            @forelse($products as $product)

                <div class="product-card">

                    @if($product->image)
                        <img 
                            src="{{ asset('storage/' . $product->image) }}" 
                            class="product-image"
                            alt="{{ $product->name }}"
                        >
                    @else
                        <img 
                            src="https://via.placeholder.com/300x220"
                            class="product-image"
                            alt="product"
                        >
                    @endif

                    <h3>{{ $product->name }}</h3>

                    <p class="category">
                        {{ $product->category->name ?? 'No Category' }}
                    </p>

                    <p class="price">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <a 
                        href="{{ route('products.show', $product->id) }}"
                        class="btn"
                    >
                        Detail Produk
                    </a>

                </div>

            @empty

                <p>Belum ada produk tersedia.</p>

            @endforelse

        </div>

    </section>


    <!-- FEATURE -->
    <section class="feature-section" id="about">

        <div class="feature-image">

            <img 
                src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=1470&auto=format&fit=crop"
                alt="feature"
            >

        </div>

        <div class="feature-content">

            <h4>Exclusive Product</h4>

            <h2>
                Produk Berkualitas untuk
                kebutuhan perusahaan Anda
            </h2>

            <p>
                Kami menyediakan berbagai produk souvenir,
                perlengkapan kantor, dan kebutuhan proyek
                dengan kualitas terbaik dan harga kompetitif.
            </p>

            <a href="#products" class="btn">
                Order Produk
            </a>

        </div>

    </section>


    <!-- CTA -->
    <section class="cta">

        <p>Why choose ABBA Barokah?</p>

        <h2>
            Solusi terbaik untuk proyek Anda!
        </h2>

        <p>
            Kami berkomitmen memberikan produk berkualitas,
            pelayanan terpercaya, dan pengalaman terbaik
            untuk setiap pelanggan.
        </p>

        <a href="#products" class="btn">
            Shop Now
        </a>

    </section>
</body>
</html>