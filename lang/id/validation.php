<?php

return [

    'required' => ':attribute wajib diisi.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'unique' => ':attribute sudah terdaftar.',
    'min' => [
        'string' => ':attribute minimal :min karakter.',
    ],
    'max' => [
        'file' => ':attribute maksimal :max KB.',
        'string' => ':attribute maksimal :max karakter.',
    ],
    'numeric' => ':attribute harus berupa angka.',
    'image' => ':attribute harus berupa gambar.',
    'mimes' => ':attribute harus bertipe JPG, JPEG, atau PNG.',

    'attributes' => [
        'name' => 'Nama Produk',
        'price' => 'Harga',
        'stock' => 'Stok',
        'category_id' => 'Kategori',
        'description' => 'Deskripsi',
        'image' => 'Gambar Produk',
        'email' => 'Alamat Email',
        'password' => 'Kata Sandi',
    ],
];