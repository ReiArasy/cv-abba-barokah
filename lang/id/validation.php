<?php

return [

    'required' => ':attribute wajib diisi.',
    'numeric' => ':attribute harus berupa angka.',
    'image' => ':attribute harus berupa gambar.',
    'mimes' => ':attribute harus bertipe JPG, JPEG, atau PNG.',
    'max' => [
        'file' => ':attribute maksimal :max KB.',
    ],

    'attributes' => [

        'name' => 'Nama Produk',
        'price' => 'Harga',
        'stock' => 'Stok',
        'category_id' => 'Kategori',
        'description' => 'Deskripsi',
        'image' => 'Gambar Produk',

    ],
];