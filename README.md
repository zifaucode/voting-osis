<p align="center"><a href="https://github.com/zifaucode/voting-osis" target="_blank"><img src="https://github.com/user-attachments/assets/0046b74d-130f-4662-8da0-6b6e1df3ed15" width="900"></a></p>

<p align="center">
<a href="https://trakteer.id/zifau"><img src="https://img.shields.io/static/v1?label=Trakteer&message=zifaucode&color=C02433"></a>
</p>

# Index

-   **[BAHASA INDONESIA](#bahasa-indonesia)**
-   **[ENGLISH LANGUAGE](#English-language)**

<br>
<br>
<br>
<br>

## BAHASA INDONESIA

Dokumentasi Bahasa Indonesia

<b>Fork dan Star ⭐ jika ini membantu ♥️</b>

# Index

-   **[Tentang VOTING OSIS](#tentang-voting-osis)**
-   **[CARA INSTALL](#cara-install)**
-   **[USER PASSWORD](#user-password)**
-   **[TRAKTIR KOPI](#traktir-kopi)**
-   **[LISENSI](#lisensi)**

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

-   File SQL terletak pada folder DB VOTING , import file SQL yang ada kedalam Database yang anda buat pada dbms anda (contoh dbms: phpmyadmin, navicat, dll).
-   Misal anda memakai PhpMyadmin, buat database baru dengan nama voting_osis, jika sudah dibuat, lalu upload/import file sql yang teletak di Folder DB VOTING tersebut.

<br>
<br>

## USER PASSWORD

-   U: admin
-   P: 12345678

<br>
<br>

## TRAKTIR KOPI

Traktir kopi agar melek terus saat ngoding : <a href="https://trakteer.id/zifau"><img src="https://img.shields.io/static/v1?label=Trakteer&message=zifaucode&color=C02433"></a>

<br>
<br>

## LISENSI

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

<br>
<br>
<br>
<br>

## ENGLISH LANGUAGE

Documentation in English

<br>

# Index

-   **[About VOTING OSIS](#about-voting-osis)**
-   **[HOW TO INSTALL](#how-to-install)**
-   **[USER AND PASSWORD](#user-and-password)**
-   **[License](#License)**

<br>
<br>

## About VOTING OSIS

Web-based online osis chairman election application. This website is made with laravel 10.10 (PHP 8.1), vuejs 2 cdn, Axios cdn, with LTE ADMIN template (Dashboard admin). Student Council VOTING has the following features:

-   There is a feature to upload voters (students) with Excel files whose template format has been provided without the hassle of having to input 1 by 1 students.
-   There are also web editing features such as web name, web title, countdown time, and many more

<br>
<br>

## HOW TO INSTALL

To Install Locally Make sure your PHP above is equal to or > 8.1

-   Clone this Repository in your favorite terminal `git clone https://github.com/zifaucode/voting-osis`
-   Type in the terminal `composer install`
-   Rename .env-lokal to .env and edit according to your database configuration
-   Create a database on your dbms (ex: phpmyadmin) with the name according to the configuration DB_DATABASE in the .env
-   Migrate database : `php artisan migrate`
-   Run the Seeder database : `php artisan db:seed`

Run in Terminal

-   `php artisan optimize:clear` AND `php artisan serve`

<br>
<br>

option if you do not want to run the database migrate and seeder:

-   The SQL file is located in the DB VOTING folder, import the existing SQL file into the Database that you created on your dbms (example dbms: phpmyadmin, navicat, etc.).
-   For example, if you use PhpMyadmin, create a new database with the name voting_osis, if it has been created, then upload/import the sql file located in the DB VOTING Folder.

<br>
<br>

## USER AND PASSWORD

-   U: admin
-   P: 12345678

<br>
<br>

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
