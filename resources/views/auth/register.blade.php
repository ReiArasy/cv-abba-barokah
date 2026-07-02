@extends('layouts.app')

@section('content')
<!-- Tambahkan CDN Select2 & jQuery di dalam section content agar langsung aktif -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Custom styling agar Select2 serasi dengan desain Tailwind Anda -->
<style>
    .select2-container--default .select2-selection--single {
        border-color: #e5e7eb !important;
        height: 38px !important;
        padding: 4px 6px !important;
        font-size: 0.875rem !important;
        border-radius: 0.375rem !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 6px !important;
    }
    .select2-dropdown {
        border-color: #e5e7eb !important;
        font-size: 0.875rem !important;
    }
    /* Membatasi tinggi maksimum box pencarian agar tidak memanjang */
    .select2-results__options {
        max-height: 200px !important; 
        overflow-y: auto !important;
    }
</style>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
    <div class="max-w-5xl w-full bg-white shadow-lg rounded-xl overflow-hidden flex flex-col md:flex-row">
        
        <div
            x-data="{
                current: 0,
                total: 7,
                start() {
                    setInterval(() => {
                        this.current = (this.current + 1) % this.total;
                    }, 3000);
                }
            }"
            x-init="start()"
            class="hidden md:block md:w-1/2 relative overflow-hidden">

            <div
                class="flex transition-transform duration-700 ease-in-out"
                :style="'transform: translateX(-' + (current * 100) + '%)'">

                <img src="{{ asset('storage/products/proses1.jpg') }}" class="w-full flex-none h-[600px] object-cover">
                <img src="{{ asset('storage/products/proses2.jpg') }}" class="w-full flex-none h-[600px] object-cover">
                <img src="{{ asset('storage/products/proses3.jpg') }}" class="w-full flex-none h-[600px] object-cover">
                <img src="{{ asset('storage/products/proses4.jpg') }}" class="w-full flex-none h-[600px] object-cover">
                <img src="{{ asset('storage/products/proses5.jpg') }}" class="w-full flex-none h-[600px] object-cover">
                <img src="{{ asset('storage/products/proses6.jpg') }}" class="w-full flex-none h-[600px] object-cover">
                <img src="{{ asset('storage/products/proses7.jpg') }}" class="w-full flex-none h-[600px] object-cover">

            </div>

            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/40"></div>

        </div>

        <!-- Sisi Kanan: Form Registrasi -->
        <div class="w-full md:w-2/3 p-8">
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Registrasi</h2>
                <br>
                <p class="text-gray-500 text-sm">Halo! Dimohon Masukkan Data yang Valid</p>
            </div>

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Baris 1 -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Username</label>
                        <input type="text" name="username" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm" placeholder="Masukkan Username" value="{{ old('username') }}">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm" placeholder="Masukkan Nama Lengkap Anda" value="{{ old('name') }}">
                    </div>

                    <!-- Baris 2 -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password</label>
                        <input type="password" name="password" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm" placeholder="Password Minimal 8 Karakter">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Alamat Lengkap</label>
                        <input type="text" name="alamat_lengkap" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm" placeholder="Enter a valid Address" value="{{ old('alamat_lengkap') }}">
                    </div>

                    <!-- Baris 3 -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email</label>
                        <input type="email" name="email" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm" placeholder="Masukkan Email Yang Terdaftar" value="{{ old('email') }}">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Provinsi</label>
                        <select id="provinsi" name="provinsi" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm bg-white">
                            <option value="" disabled selected>-- Pilih Provinsi --</option>
                            <!-- Pulau Sumatra -->
                            <option value="Aceh">Aceh</option>
                            <option value="Sumatera Utara">Sumatera Utara</option>
                            <option value="Sumatera Barat">Sumatera Barat</option>
                            <option value="Riau">Riau</option>
                            <option value="Kepulauan Riau">Kepulauan Riau</option>
                            <option value="Jambi">Jambi</option>
                            <option value="Bengkulu">Bengkulu</option>
                            <option value="Sumatera Selatan">Sumatera Selatan</option>
                            <option value="Kepulauan Bangka Belitung">Kepulauan Bangka Belitung</option>
                            <option value="Lampung">Lampung</option>
                            
                            <!-- Pulau Jawa -->
                            <option value="DKI Jakarta">DKI Jakarta</option>
                            <option value="Banten">Banten</option>
                            <option value="Jawa Barat">Jawa Barat</option>
                            <option value="Jawa Tengah">Jawa Tengah</option>
                            <option value="DI Yogyakarta">DI Yogyakarta</option>
                            <option value="Jawa Timur">Jawa Timur</option>
                            
                            <!-- Kepulauan Nusa Tenggara & Bali -->
                            <option value="Bali">Bali</option>
                            <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                            <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
                            
                            <!-- Pulau Kalimantan -->
                            <option value="Kalimantan Barat">Kalimantan Barat</option>
                            <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                            <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                            <option value="Kalimantan Timur">Kalimantan Timur</option>
                            <option value="Kalimantan Utara">Kalimantan Utara</option>
                            
                            <!-- Pulau Sulawesi -->
                            <option value="Sulawesi Utara">Sulawesi Utara</option>
                            <option value="Gorontalo">Gorontalo</option>
                            <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                            <option value="Sulawesi Barat">Sulawesi Barat</option>
                            <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                            <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                            
                            <!-- Kepulauan Maluku -->
                            <option value="Maluku">Maluku</option>
                            <option value="Maluku Utara">Maluku Utara</option>
                            
                            <!-- Pulau Papua -->
                            <option value="Papua">Papua</option>
                            <option value="Papua Barat">Papua Barat</option>
                            <option value="Papua Selatan">Papua Selatan</option>
                            <option value="Papua Tengah">Papua Tengah</option>
                            <option value="Papua Pegunungan">Papua Pegunungan</option>
                            <option value="Papua Barat Daya">Papua Barat Daya</option>
                        </select>
                    </div>

                    <!-- Baris 4 -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nomor Telepon</label>
                        <input type="text" name="phone" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm" placeholder="Masukkan Nomor Telepon" value="{{ old('phone') }}">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kota</label>
                        <select id="kota" name="kota" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm bg-white" disabled>
                            <option value="" disabled selected>Silahkan pilih provinsi terlebih dahulu</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex flex-col items-center">
                    <button type="submit" class="w-1/2 bg-teal-500 text-white font-bold py-2 rounded-lg hover:bg-teal-600 transition shadow-md">
                        Registrasi
                    </button>
                    <p class="mt-4 text-sm text-gray-600">
                        Sudah Punya Akun? <a href="{{ route('login') }}" class="text-blue-500 font-bold">Login</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // SweetAlert Error handling bawaan Anda
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Registrasi Gagal',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#14b8a6',
        });
    @endif

    $(document).ready(function() {
        // 1. Inisialisasi Select2 pada dropdown Provinsi agar mendukung Search & Ber-scroll pendek
        $('#provinsi').select2({
            placeholder: "-- Pilih Provinsi --",
            allowClear: true,
            language: {
                noResults: function() { return "Data tidak ditemukan"; },
                searching: function() { return "Mencari..."; }
            }
        });

        // 2. Inisialisasi Select2 pada dropdown Kota
        $('#kota').select2({
            placeholder: "Cari dan pilih kota...",
            allowClear: true,
            language: {
                noResults: function() { return "Data tidak ditemukan"; },
                searching: function() { return "Mencari..."; }
            }
        });

        // Data simulasi kota sesuai value Provinsi
        const dataKota = {
            "Aceh": ["Banda Aceh", "Sabang", "Lhokseumawe", "Langsa", "Subulussalam", "Aceh Besar", "Pidie", "Aceh Barat"],
            "Sumatera Utara": ["Medan", "Binjai", "Tebing Tinggi", "Pematangsiantar", "Tanjungbalai", "Sibolga", "Padangsidimpuan", "Gunungsitoli", "Deli Serdang", "Karo"],
            "Sumatera Barat": ["Padang", "Solok", "Sawahlunto", "Padang Panjang", "Bukittinggi", "Payakumbuh", "Pariaman", "Pesisir Selatan"],
            "Riau": ["Pekanbaru", "Dumai", "Kampar", "Bengkalis", "Indragiri Hilir", "Indragiri Hulu", "Pelalawan", "Siak"],
            "Kepulauan Riau": ["Batam", "Tanjungpinang", "Bintan", "Karimun", "Natuna", "Lingga", "Kepulauan Anambas"],
            "Jambi": ["Jambi", "Sungai Penuh", "Muaro Jambi", "Batanghari", "Tanjung Jabung Barat", "Merangin", "Bungo"],
            "Bengkulu": ["Bengkulu", "Rejang Lebong", "Muko-Muko", "Seluma", "Kaur", "Bengkul Utara"],
            "Sumatera Selatan": ["Palembang", "Pagar Alam", "Lubuklinggau", "Prabumulih", "Ogan Komering Ilir", "Muara Enim", "Banyuasin"],
            "Kepulauan Bangka Belitung": ["Pangkalpinang", "Bangka", "Bangka Barat", "Bangka Tengah", "Bangka Selatan", "Belitung", "Belitung Timur"],
            "Lampung": ["Bandar Lampung", "Metro", "Lampung Selatan", "Lampung Tengah", "Lampung Utara", "Pringsewu", "Pesawaran"],
            "DKI Jakarta": ["Jakarta Pusat", "Jakarta Barat", "Jakarta Selatan", "Jakarta Timur", "Jakarta Utara", "Kepulauan Seribu"],
            "Banten": ["Tangerang", "Serang", "Cilegon", "Tangerang Selatan", "Lebak", "Pandeglang"],
            "Jawa Barat": ["Bandung", "Bekasi", "Bogor", "Depok", "Cirebon", "Sukabumi", "Tasikmalaya", "Cimahi", "Banjar", "Karawang"],
            "Jawa Tengah": ["Semarang", "Surakarta", "Magelang", "Tegal", "Purwokerto", "Salatiga", "Pekalongan", "Kudus", "Banyumas"],
            "DI Yogyakarta": ["Yogyakarta", "Sleman", "Bantul", "Kulon Progo", "Gunungkidul"],
            "Jawa Timur": ["Surabaya", "Malang", "Sidoarjo", "Gresik", "Mojokerto", "Banyuwangi", "Kediri", "Madiun", "Pasuruan", "Probolinggo", "Bangkalan", "Sampang", "Pamekasan", "Sumenep"],
            "Bali": ["Denpasar", "Badung", "Gianyar", "Buleleng", "Tabanan", "Karangasem", "Klungkung", "Bangli", "Jembrana"],
            "Nusa Tenggara Barat": ["Mataram", "Bima", "Lombok Barat", "Lombok Tengah", "Lombok Timur", "Sumbawa", "Dompu"],
            "Nusa Tenggara Timur": ["Kupang", "Ende", "Sikka", "Manggarai", "Alor", "Sumba Timur", "Sumba Barat", "Belu"],
            "Kalimantan Barat": ["Pontianak", "Singkawang", "Kubu Raya", "Mempawah", "Sambas", "Sintang", "Ketapang"],
            "Kalimantan Tengah": ["Palangkaraya", "Kotawaringin Timur", "Kotawaringin Barat", "Kapuas", "Barito Utara"],
            "Kalimantan Selatan": ["Banjarmasin", "Banjarbaru", "Banjar", "Tanah Bumbu", "Kotabaru", "Tabalong", "Barito Kuala"],
            "Kalimantan Timur": ["Samarinda", "Balikpapan", "Bontang", "Kutai Kartanegara", "Kutai Timur", "Berau", "Paser"],
            "Kalimantan Utara": ["Tarakan", "Bulungan", "Malinau", "Nunukan", "Tana Tidung"],
            "Sulawesi Utara": ["Manado", "Bitung", "Tomohon", "Kotamobagu", "Minahasa", "Bolaang Mongondow"],
            "Gorontalo": ["Gorontalo", "Limboto", "Boalemo", "Bone Bolango", "Pohuwato", "Gorontalo Utara"],
            "Sulawesi Tengah": ["Palu", "Donggala", "Poso", "Banggai", "Toli-Toli", "Parigi Moutong", "Morowali"],
            "Sulawesi Barat": ["Mamuju", "Majene", "Polewali Mandar", "Mamasa", "Pasangkayu"],
            "Sulawesi Selatan": ["Makassar", "Parepare", "Palopo", "Gowa", "Maros", "Bone", "Bulukumba", "Toraja Utara"],
            "Sulawesi Tenggara": ["Kendari", "Bau-Bau", "Konawe", "Kolaka", "Muna", "Buton", "Wakatobi"],
            "Maluku": ["Ambon", "Tual", "Maluku Tengah", "Maluku Tenggara", "Kepulauan Tanimbar", "Buru"],
            "Maluku Utara": ["Ternate", "Tidore Kepulauan", "Halmahera Utara", "Halmahera Barat", "Halmahera Selatan"],
            "Papua": ["Jayapura", "Keerom", "Sarmi", "Mamberamo Raya", "Kepulauan Yapen", "Biak Numfor"],
            "Papua Barat": ["Manokwari", "Fakfak", "Kaimana", "Teluk Wondama", "Teluk Bintuni", "Pegunungan Arfak"],
            "Papua Selatan": ["Merauke", "Boven Digoel", "Mappi", "Asmat"],
            "Papua Tengah": ["Nabire", "Mimika", "Paniai", "Dogiyai", "Deiyai", "Intan Jaya", "Puncak", "Puncak Jaya"],
            "Papua Pegunungan": ["Jayawijaya", "Lanny Jaya", "Tolikara", "Nduga", "Yalimo", "Yahukimo", "Pegunungan Bintang"],
            "Papua Barat Daya": ["Sorong", "Raja Ampat", "Sorong Selatan", "Maybrat", "Tambrauw"]
        };

        // Aksi ketika pilihan Provinsi berubah
        $('#provinsi').on('change', function() {
            const provinsiTerpilih = $(this).val();
            const $kotaSelect = $('#kota');

            // Kosongkan daftar kota sebelumnya
            $kotaSelect.empty();

            if (provinsiTerpilih && dataKota[provinsiTerpilih]) {
                // Aktifkan kembali dropdown kota
                $kotaSelect.prop('disabled', false);
                
                // Set default placeholder kota
                $kotaSelect.append('<option value="" disabled selected>-- Pilih Kota --</option>');
                
                // Masukkan data kota baru ke dalam dropdown
                dataKota[provinsiTerpilih].forEach(function(kota) {
                    $kotaSelect.append(new Option(kota, kota));
                });
            } else {
                // Jika tidak ada provinsi yang valid, kunci kembali dropdown kota
                $kotaSelect.prop('disabled', true);
                $kotaSelect.append('<option value="" disabled selected>Silahkan pilih provinsi terlebih dahulu</option>');
            }

            // Trigger perubahan agar Select2 memperbarui tampilannya
            $kotaSelect.trigger('change');
        });
    });
</script>
@endsection