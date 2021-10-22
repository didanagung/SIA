-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Okt 2021 pada 13.13
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siak`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `akun`
--

CREATE TABLE `akun` (
  `no_reff` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_reff` varchar(40) NOT NULL,
  `keterangan` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `akun`
--

INSERT INTO `akun` (`no_reff`, `id_user`, `nama_reff`, `keterangan`) VALUES
(111, 1, 'Kas', 'Kas'),
(112, 1, 'Piutang', 'Piutang Usaha'),
(113, 1, 'Perlengkapan', 'Perlengkapan Perusahaan'),
(121, 1, 'Peralatan', 'Peralatan Perusahaan'),
(122, 1, 'Akumulasi Peralatan', 'Akumulasi Peralatan'),
(211, 1, 'Utang Usaha', 'Utang Usaha'),
(311, 1, 'Modal', 'Modal'),
(312, 1, 'Prive', 'Prive'),
(411, 1, 'Pendapatan', 'Pendapatan'),
(412, 1, 'Pendapatan Jasa', 'Pendapatan Jasa Perusahaan'),
(511, 1, 'Beban Gaji', 'Beban Gaji'),
(512, 1, 'Beban Sewa', 'Beban Sewa'),
(513, 1, 'Beban Penyusutan Peralatan', 'Beban Penyusutan Peralatan'),
(514, 1, 'Beban Lat', 'Beban Lat'),
(515, 1, 'Beban Perlengkapan', 'Beban Perlengkapan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyesuaian`
--

CREATE TABLE `penyesuaian` (
  `id_penyesuaian` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `no_reff` int(11) NOT NULL,
  `tgl_input` datetime NOT NULL,
  `tgl_penyesuaian` date NOT NULL,
  `jenis_saldo` enum('debit','kredit','','') NOT NULL,
  `saldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penyesuaian`
--

INSERT INTO `penyesuaian` (`id_penyesuaian`, `id_user`, `no_reff`, `tgl_input`, `tgl_penyesuaian`, `jenis_saldo`, `saldo`) VALUES
(1, 1, 112, '2021-10-14 21:53:25', '2021-10-14', 'debit', 20000),
(2, 1, 113, '2021-10-14 22:02:47', '2021-10-14', 'kredit', 20000),
(3, 1, 112, '2021-10-18 19:54:35', '2021-09-08', 'kredit', 1000),
(4, 1, 311, '2021-10-15 09:06:12', '2021-10-15', 'debit', 20000),
(7, 1, 514, '2021-10-15 09:28:24', '2021-10-15', 'kredit', 20000),
(8, 1, 514, '2021-10-15 09:46:46', '2021-10-15', 'debit', 10000),
(9, 1, 312, '2021-10-15 09:48:09', '2010-10-15', 'debit', 20000),
(10, 1, 512, '2021-10-19 13:27:48', '2019-10-10', 'kredit', 1000),
(12, 1, 411, '2021-10-19 17:03:47', '2021-09-15', 'kredit', 1000),
(13, 1, 412, '2021-10-19 17:14:39', '2021-09-18', 'kredit', 19000),
(14, 1, 511, '2021-10-19 17:49:21', '2021-10-19', 'debit', 20000),
(15, 1, 511, '2021-10-19 17:49:52', '2021-09-25', 'debit', 10000),
(16, 1, 121, '2021-10-19 18:41:07', '2021-10-13', 'kredit', 20000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_penyesuaian` int(11) DEFAULT NULL,
  `no_reff` int(11) NOT NULL,
  `tgl_input` datetime NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `jenis_saldo` enum('debit','kredit','','') NOT NULL,
  `saldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `id_penyesuaian`, `no_reff`, `tgl_input`, `tgl_transaksi`, `jenis_saldo`, `saldo`) VALUES
(15, 1, NULL, 111, '2018-11-26 17:45:45', '2018-11-03', 'debit', 80000000),
(16, 1, NULL, 311, '2018-11-26 17:45:45', '2018-11-03', 'kredit', 80000000),
(17, 1, NULL, 121, '2018-11-26 17:46:37', '2018-11-03', 'debit', 35000000),
(18, 1, NULL, 311, '2018-11-26 17:46:37', '2018-11-03', 'kredit', 35000000),
(19, 1, NULL, 512, '2018-11-26 17:49:00', '2018-11-04', 'debit', 6000000),
(20, 1, NULL, 111, '2018-11-26 17:49:00', '2018-11-04', 'kredit', 6000000),
(21, 1, NULL, 111, '2018-11-26 17:52:00', '2018-11-05', 'kredit', 1900000),
(22, 1, NULL, 113, '2018-11-26 17:52:00', '2018-11-05', 'debit', 1900000),
(23, 1, NULL, 121, '2018-11-26 17:55:08', '2018-11-08', 'debit', 2000000),
(24, 1, NULL, 211, '2018-11-26 17:55:08', '2018-11-08', 'kredit', 2000000),
(25, 1, NULL, 411, '2018-11-26 17:57:04', '2018-11-10', 'kredit', 950000),
(26, 1, NULL, 112, '2018-11-26 17:57:04', '2018-11-10', 'debit', 950000),
(27, 1, NULL, 111, '2018-11-26 17:57:49', '2018-11-12', 'debit', 2500000),
(28, 1, NULL, 411, '2018-11-26 17:57:49', '2018-11-12', 'kredit', 2500000),
(29, 1, NULL, 211, '2018-11-26 17:59:24', '2018-11-15', 'debit', 200000),
(30, 1, NULL, 111, '2018-11-26 17:59:24', '2018-11-15', 'kredit', 200000),
(31, 1, NULL, 312, '2018-11-26 18:05:40', '2018-11-20', 'debit', 750000),
(32, 1, NULL, 111, '2018-11-26 18:05:40', '2018-11-20', 'kredit', 750000),
(33, 1, NULL, 111, '2018-11-26 18:06:13', '2018-11-28', 'debit', 750000),
(34, 1, NULL, 112, '2018-11-26 18:06:13', '2018-11-28', 'kredit', 750000),
(35, 1, NULL, 511, '2018-11-26 18:10:23', '2018-11-29', 'debit', 900000),
(36, 1, NULL, 111, '2018-11-26 18:10:23', '2018-11-29', 'kredit', 900000),
(37, 1, NULL, 514, '2018-11-26 18:10:57', '2018-11-30', 'debit', 1600000),
(39, 1, NULL, 515, '2018-11-26 18:12:55', '2018-11-30', 'debit', 1150000),
(40, 1, NULL, 113, '2018-11-26 18:12:55', '2018-11-30', 'kredit', 1150000),
(41, 1, NULL, 513, '2018-11-26 18:14:43', '2018-11-30', 'debit', 250000),
(42, 1, NULL, 122, '2018-11-26 18:14:43', '2018-11-30', 'kredit', 250000),
(43, 1, NULL, 512, '2018-11-26 18:15:20', '2018-11-26', 'debit', 500000),
(44, 1, NULL, 111, '2018-11-26 18:15:20', '2018-11-26', 'kredit', 500000),
(45, 1, NULL, 111, '2018-11-28 10:40:25', '2019-11-30', 'debit', 2000000),
(46, 1, NULL, 112, '2018-11-28 10:40:25', '2019-11-30', 'kredit', 2000000),
(47, 1, NULL, 514, '2021-10-15 09:16:56', '2018-10-01', 'debit', 100),
(48, 1, NULL, 111, '2021-10-15 09:16:36', '2018-10-01', 'kredit', 100),
(49, 1, NULL, 112, '2018-11-28 12:15:31', '2018-10-02', 'debit', 2000000),
(50, 1, NULL, 113, '2018-11-28 12:15:31', '2018-10-02', 'kredit', 2000000),
(51, 1, NULL, 121, '2021-10-14 08:40:40', '2021-10-14', 'debit', 1000),
(52, 1, NULL, 111, '2021-10-14 22:14:35', '2021-10-14', 'debit', 20000),
(53, 1, NULL, 111, '2021-10-15 19:59:08', '2021-10-15', 'kredit', 10000),
(55, 1, NULL, 312, '2021-10-16 08:26:24', '2021-09-15', 'debit', 20000),
(56, 1, NULL, 411, '2021-10-19 19:27:43', '2021-09-09', 'kredit', 20000),
(57, 1, NULL, 511, '2021-10-22 18:09:57', '2021-09-15', 'debit', 20000),
(58, 1, NULL, 412, '2021-10-19 20:02:53', '2021-09-24', 'kredit', 1000),
(59, 1, NULL, 411, '2021-10-19 20:04:53', '2021-09-30', 'kredit', 200);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jk` enum('laki-laki','perempuan','','') NOT NULL,
  `alamat` varchar(40) NOT NULL,
  `email` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(60) NOT NULL,
  `last_login` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama`, `jk`, `alamat`, `email`, `username`, `password`, `last_login`) VALUES
(1, 'Hidayat Chandra', 'laki-laki', 'JL.H.B Jassin No.337', 'hidayatchandra08@gmail.com', 'hidayat', '69005bb62e9622ee1de61958aacf0f63', '2021-10-22 18:51:23');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`no_reff`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `penyesuaian`
--
ALTER TABLE `penyesuaian`
  ADD PRIMARY KEY (`id_penyesuaian`),
  ADD KEY `id_user` (`id_user`,`no_reff`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `no_reff` (`no_reff`),
  ADD KEY `id_penyesuaian` (`id_penyesuaian`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `penyesuaian`
--
ALTER TABLE `penyesuaian`
  MODIFY `id_penyesuaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `akun`
--
ALTER TABLE `akun`
  ADD CONSTRAINT `akun_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
