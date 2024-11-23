-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Nov 2024 pada 16.49
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `voting_osis`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_08_29_135113_add_role_in_users', 1),
(7, '2024_10_26_134150_create_osis_chairman_candidates_table', 2),
(8, '2024_10_28_123105_add_nisn_in_users', 3),
(9, '2024_10_29_011127_drop_email_in_users', 4),
(10, '2024_11_03_101747_create_total_vote_users_table', 5),
(11, '2024_11_03_134644_add_is_vote_in_users', 6),
(12, '2024_11_10_025221_create_web_settings_table', 7),
(13, '2024_11_16_145910_add_status_in_web_settings', 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `osis_chairman_candidates`
--

CREATE TABLE `osis_chairman_candidates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `vice_name` varchar(255) DEFAULT NULL,
  `sequence_number` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `vote_total` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `osis_chairman_candidates`
--

INSERT INTO `osis_chairman_candidates` (`id`, `name`, `vice_name`, `sequence_number`, `class`, `visi`, `misi`, `image`, `vote_total`, `created_at`, `updated_at`) VALUES
(2, 'PRABOWO SUBIANTO', NULL, '01', '12 IPA 1', 'SAYA AKAN MENJADI\r\n1. AHAHAHAHAHAHAH\r\n2. HEHEHEHEHEHEHE\r\n3. HIHIHIHIHIHH', 'SAYA SELALU KEREN\r\nasikkk\r\nokeee', 'PRABOWO_logo-osis.png', NULL, '2024-10-26 19:44:30', '2024-11-13 07:57:31'),
(3, 'FAUZI AGUSTIAN KEREN BANGET', NULL, '02', '12 IPA 2', 'SAYA AKAN TERBANG\r\n1. MENUJU KOREA UTARA\r\n2. KOREA SELATAN', 'SEBAGAI MEDIA MASA\r\n1.OK\r\n2.OKE\r\n3.OKEDEH', 'FAUZI AGUSTIAN_DJIFAW STORE.JPG', NULL, '2024-10-26 23:53:29', '2024-11-13 07:57:53'),
(4, 'JOKOWI', NULL, '03', '12 IPA 3', 'YO NDAK TAU KO TANYA SAYA', 'AYO KITA KERJA, KERJA, KERJA', 'JOKOWI_TARI KURA.JPG', NULL, '2024-10-26 23:55:44', '2024-10-26 23:55:44');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `total_vote_users`
--

CREATE TABLE `total_vote_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `total_vote_users`
--

INSERT INTO `total_vote_users` (`id`, `user_id`, `candidate_id`, `time`, `created_at`, `updated_at`) VALUES
(4, 2, 3, '13:17:13', '2024-11-17 06:17:13', '2024-11-17 06:17:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `nisn` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `vote_status` tinyint(1) DEFAULT 0,
  `is_vote` int(11) DEFAULT NULL,
  `code_access` int(11) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `role`, `username`, `name`, `email_verified_at`, `password`, `nisn`, `class`, `vote_status`, `is_vote`, `code_access`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', 'ADMIN', NULL, '$2y$12$yTYujAoM4HBp7C3Xe2AFBeWR8NX0nG8vh4lS6M2GULfw/IGzUbAmK', NULL, NULL, 0, NULL, NULL, NULL, '2024-11-09 05:57:16', '2024-11-09 05:57:16'),
(2, 2, NULL, 'UDIN', NULL, '$2y$12$UELZRvTIjRoPLF/ulFjsEOuWajE1USU8zpaCfM39qnh5TYAEtpWLK', '12345611', '12 IPA 1', 1, 3, 123456, NULL, '2024-11-09 06:51:20', '2024-11-17 06:17:14'),
(6, 1, 'adminosis', 'ADMIN OSIS', NULL, '$2y$12$izd3ySPCFXiKb9GjmTylp.d6pfx4nRCv5sT4jXI639iaGyPS1dYVe', NULL, NULL, 0, NULL, NULL, NULL, '2024-11-09 22:22:10', '2024-11-09 22:22:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `web_settings`
--

CREATE TABLE `web_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `web_title` varchar(255) DEFAULT NULL,
  `school_name` varchar(255) DEFAULT NULL,
  `year_period` year(4) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `web_settings`
--

INSERT INTO `web_settings` (`id`, `web_title`, `school_name`, `year_period`, `start_date`, `start_time`, `end_time`, `status`, `created_at`, `updated_at`) VALUES
(1, 'WEB VOTING OSIS', 'SMAN 1 PARUNG', 2024, '2024-11-18', '08:28:00', '09:00:00', 'dibuka', '2024-11-09 21:04:18', '2024-11-17 18:27:18');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `osis_chairman_candidates`
--
ALTER TABLE `osis_chairman_candidates`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `total_vote_users`
--
ALTER TABLE `total_vote_users`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `web_settings`
--
ALTER TABLE `web_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `osis_chairman_candidates`
--
ALTER TABLE `osis_chairman_candidates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `total_vote_users`
--
ALTER TABLE `total_vote_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `web_settings`
--
ALTER TABLE `web_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
