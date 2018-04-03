# media

Adalah modul yang menyediakan image lazy image resizer hanya dengan menamambahkan
ukuran gambar yang dibutuhkan di akhir sebelum ekstensi.

Modul ini membutuhkan tambahan konfigurasi gate dengan `media` seperti di bawah:

```php
// ./etc/config.php

return [
    'name' => 'Phun',
    ...
    '_gates' => [
        'media' => [
            // 'host' => 'cdn.cms.phu', // optional
            'path' => '/media'
        ]
    ],
    
    'media' => [
        'live' => 'https://www.google.com',
        'webp' => false
    ]
];
```

Kemudian, dengan mengakses `HOST/media/[dir]/[dir]/[dir]/[file_name]_[width]x[height].[file_ext]`
dari browser akan menggenerasi image dengan ukuran yang diminta.

Konfigurasi `media.live` adalah optional untuk men-download media dari live server.
Hal ini mungkin perlu ketika database lokal development diambil dari live server,
sementara file media tidak terdownload, opsi ini memungkinkan system mendownload
media dari live server dan menyimpannya di lokal agar bisa digunakan pada environment
lokal. Pastikan tidak ada trailing slash (/) di akhir url.

Konfigurasi `media.webp` adalah option untuk menentukan apakah system mendukung konversi
gambar menjadi `webp` atau tidak.

## Lisensi

Modul ini menggunakan library dari [gumlet/php-image-resize](https://github.com/gumlet/php-image-resize).
Silahkan mengacu pada library tersebut untuk lisensi.