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
    ]
];
```

Kemudian, dengan mengakses `HOST/media/[dir]/[dir]/[dir]/[file_name]_[width]x[height].[file_ext]`
dari browser akan menggenerasi image dengan ukuran yang diminta.