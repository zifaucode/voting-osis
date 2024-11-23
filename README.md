<p align="center"><a href="https://github.com/zifaucode/cek-lulus" target="_blank"><img src="https://user-images.githubusercontent.com/33486013/164989084-586c08af-43ea-4f59-93dd-54f25f22c830.png" width="900"></a></p>

<p align="center">
<a href="https://trakteer.id/zifau"><img src="https://img.shields.io/static/v1?label=Trakteer&message=zifaucode&color=C02433"></a>
</p>

# Index

-   **[BAHASA INDONESIA](#bahasa-indonesia)**
-   **[ENGLISH LANGUAGE](#English-language)**

## BAHASA INDONESIA

Dokumentasi Bahasa Indonesia

<br>

<b>Fork dan Star ⭐ jika ini membantu ♥️</b>

# Index

-   **[Tentang VOTING OSIS](#tentang-voting-osis)**
-   **[CARA INSTALL](#cara-install)**
-   **[USER PASSWORD](#user-password)**
-   **[NOTED](#noted)**
-   **[TRAKTIR KOPI](#traktir-kopi)**

<br>
<br>

## Tentang VOTING OSIS

Aplikasi Pemilihan Ketua osis online berbasis web. Website ini dibuat dengan laravel 10.10 (PHP 8.1), vuejs 2 cdn, Axios cdn, dengan template ADMIN LTE (Dashboard admin). VOTING OSIS memiliki fitur sebagai berikut:

-   Terdapat Fitur upload pemilih (siswa) dengan file Excel yang format templatenya sudah disediakan tanpa ribet harus input 1 per 1 siswa
-   Terdapat juga Fitur Edit web seperti nama web, title web, waktu hitung mundur, dan masih banyak lagi

<br>
<br>

## CARA INSTALL

Untuk Menginstall Secara Lokal Pastikan PHP anda diatas sama dengan atau > 8.1

-   Clone Repository ini Diterminal kesayangan anda `git clone https://github.com/zifaucode/voting-osis`
-   Ketikan `composer install`
-   Rename .env-lokal menjadi .env dan edit sesuai konfigurasi database anda
-   Buat database pada dbms anda (ex: phpmyadmin) dengan nama sesuai konfigurasi DB_DATABASE pada .env
-   Migrate databasenya : `php artisan migrate`
-   Jalankan Seeder database : `php artisan db:seed`

Jalankan Diterminal

-   `php artisan optimize:clear` dan `php artisan serve`

<br>
<br>

opsi selain jika tidak ingin menjalankan migrate database dan seeder:

-   File SQL terletak pada folder DB VOTING , import Database ke dalam mysql yang sudah anda buat pada dbms anda (contoh dbms: phpmyadmin, navicat, dll)
-   Misal anda memakai PhpMyadmin, buat buat database baru dengan nama voting_osis, jika sudah dibuat, lalu upload file sql yang teletak di Folder DB VOTING

## USER PASSWORD

-   U: admin
-   P: 12345678

<br>
<br>

## TRAKTIR KOPI

Traktir kopi agar melek terus saat ngoding : <a href="https://trakteer.id/zifau"><img src="https://img.shields.io/static/v1?label=Trakteer&message=zifaucode&color=C02433"></a>

<br>
<br>

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
