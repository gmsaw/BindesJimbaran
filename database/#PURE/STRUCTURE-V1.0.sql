CREATE DATABASE  IF NOT EXISTS `db_kependudukan` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_kependudukan`;
-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: db_kependudukan
-- ------------------------------------------------------
-- Server version	8.0.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `add_for_krama_tamiu`
--

DROP TABLE IF EXISTS `add_for_krama_tamiu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `add_for_krama_tamiu` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `master_individu_fk` bigint DEFAULT NULL COMMENT 'for master Individu',
  `desa_adat` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kabupaten` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `add_for_krama_tamiu_n_master_individu_fk` (`master_individu_fk`),
  CONSTRAINT `add_for_krama_tamiu_n_master_individu_fk` FOREIGN KEY (`master_individu_fk`) REFERENCES `master_individu` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='add for tamiu and krama tamiu';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `add_for_tamiu`
--

DROP TABLE IF EXISTS `add_for_tamiu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `add_for_tamiu` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `master_individu_fk` bigint DEFAULT NULL COMMENT 'for master Individu',
  `desa` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kabupaten` varchar(255) DEFAULT NULL,
  `provinsi` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `add_for_tamiu_n_master_individu_fk` (`master_individu_fk`),
  CONSTRAINT `add_for_tamiu_n_master_individu_fk` FOREIGN KEY (`master_individu_fk`) REFERENCES `master_individu` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='add for tamiu and krama tamiu';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `anggota_kk_adat`
--

DROP TABLE IF EXISTS `anggota_kk_adat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anggota_kk_adat` (
  `id_keanggotaan` bigint NOT NULL AUTO_INCREMENT,
  `id_identitas_adat_fk` bigint NOT NULL,
  `npk_fk` varchar(255) NOT NULL,
  `status_hubungan_adat` enum('kepala_keluarga','istri','anak','orang_tua','cucu','saudara','famili_lain','lainnya','tidak_diketahui') NOT NULL,
  `tanggal_bergabung_kk` date DEFAULT NULL,
  `tanggal_keluar_kk` date DEFAULT NULL,
  `status_keanggotaan` enum('aktif','pindah_kk_sama_banjar','pindah_kk_beda_banjar','keluar_adat','meninggal','lainnya') NOT NULL,
  PRIMARY KEY (`id_keanggotaan`),
  UNIQUE KEY `uq_anggota_aktif_unik` (`id_identitas_adat_fk`,`npk_fk`,`status_keanggotaan`),
  KEY `fk_anggota_kk_to_kk_adat` (`npk_fk`),
  CONSTRAINT `fk_anggota_kk_to_kk_adat` FOREIGN KEY (`npk_fk`) REFERENCES `kartu_keluarga_adat` (`npk`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_anggota_kk_to_master_adat` FOREIGN KEY (`id_identitas_adat_fk`) REFERENCES `master_adat` (`id_identitas_adat`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4498 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `banjar`
--

DROP TABLE IF EXISTS `banjar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banjar` (
  `kode_banjar` varchar(255) NOT NULL,
  `nama_banjar` varchar(255) NOT NULL,
  `kelihan_banjar` varchar(255) DEFAULT NULL,
  `alamat_sekretariat` text,
  PRIMARY KEY (`kode_banjar`),
  UNIQUE KEY `uq_nama_banjar` (`nama_banjar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bendesa_adat`
--

DROP TABLE IF EXISTS `bendesa_adat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bendesa_adat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_bendesa` varchar(255) NOT NULL,
  `id_individu_fk` bigint DEFAULT NULL,
  `periode_mulai` date DEFAULT NULL,
  `periode_selesai` date DEFAULT NULL,
  `status_jabatan` enum('aktif','nonaktif','selesai_jabatan') DEFAULT 'aktif',
  PRIMARY KEY (`id`),
  KEY `fk_bendesa_to_master_individu` (`id_individu_fk`),
  CONSTRAINT `fk_bendesa_to_master_individu` FOREIGN KEY (`id_individu_fk`) REFERENCES `master_individu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dadia`
--

DROP TABLE IF EXISTS `dadia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dadia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_dadia` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_nama_dadia` (`nama_dadia`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dadia_penatahan`
--

DROP TABLE IF EXISTS `dadia_penatahan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dadia_penatahan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_dadia` varchar(255) DEFAULT NULL,
  `nama_penatahan` varchar(255) DEFAULT NULL,
  `kelihan_natah` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_nama_dadia_penatahan` (`nama_dadia`,`nama_penatahan`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kartu_keluarga_adat`
--

DROP TABLE IF EXISTS `kartu_keluarga_adat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kartu_keluarga_adat` (
  `npk` varchar(255) NOT NULL,
  `nkk` varchar(255) DEFAULT NULL,
  `kode_klasifikasi_krama_fk` varchar(255) DEFAULT NULL,
  `kode_banjar_fk` varchar(255) DEFAULT NULL,
  `no_telp` varchar(30) DEFAULT NULL,
  `status_adat` enum('krama_adat','krama_tamiu','tamiu','pindah_keluar_adat','lainnya','tidak_diketahui') DEFAULT 'tidak_diketahui',
  `alamat` text,
  PRIMARY KEY (`npk`),
  KEY `fk_kk_adat_to_banjar` (`kode_banjar_fk`),
  KEY `fk_kk_adat_to_klasifikasi_krama` (`kode_klasifikasi_krama_fk`),
  CONSTRAINT `fk_kk_adat_to_banjar` FOREIGN KEY (`kode_banjar_fk`) REFERENCES `banjar` (`kode_banjar`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_kk_adat_to_klasifikasi_krama` FOREIGN KEY (`kode_klasifikasi_krama_fk`) REFERENCES `klasifikasi_krama` (`kode_krama`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `keterangan_keluarga`
--

DROP TABLE IF EXISTS `keterangan_keluarga`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keterangan_keluarga` (
  `npk_fk` varchar(255) NOT NULL,
  `id_dadia_penatahan_fk` int DEFAULT NULL,
  `keterangan_tambahan` text,
  PRIMARY KEY (`npk_fk`),
  KEY `fk_keterangan_to_dadia_penatahan_idx` (`id_dadia_penatahan_fk`),
  CONSTRAINT `fk_keterangan_to_dadia_penatahan` FOREIGN KEY (`id_dadia_penatahan_fk`) REFERENCES `penatahan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_keterangan_to_kk_adat` FOREIGN KEY (`npk_fk`) REFERENCES `kartu_keluarga_adat` (`npk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `klasifikasi_krama`
--

DROP TABLE IF EXISTS `klasifikasi_krama`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `klasifikasi_krama` (
  `kode_krama` varchar(255) NOT NULL,
  `krama` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`kode_krama`),
  UNIQUE KEY `uq_krama_nama` (`krama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `master_adat`
--

DROP TABLE IF EXISTS `master_adat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `master_adat` (
  `id_identitas_adat` bigint NOT NULL AUTO_INCREMENT,
  `id_individu_fk` bigint NOT NULL,
  `kode_banjar_fk` varchar(255) NOT NULL,
  `nika` varchar(255) NOT NULL,
  `tanggal_catat_nika` date DEFAULT NULL,
  `status_di_banjar` enum('aktif','pindah_keluar_banjar','meninggal','nonaktif_sementara','lainnya') NOT NULL DEFAULT 'aktif',
  PRIMARY KEY (`id_identitas_adat`),
  UNIQUE KEY `uq_master_adat_nika` (`nika`),
  UNIQUE KEY `uq_master_adat_individu_banjar` (`id_individu_fk`,`kode_banjar_fk`),
  KEY `fk_master_adat_to_banjar` (`kode_banjar_fk`),
  CONSTRAINT `fk_master_adat_to_banjar` FOREIGN KEY (`kode_banjar_fk`) REFERENCES `banjar` (`kode_banjar`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_master_adat_to_master_individu` FOREIGN KEY (`id_individu_fk`) REFERENCES `master_individu` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4500 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `master_individu`
--

DROP TABLE IF EXISTS `master_individu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `master_individu` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nik_nasional` varchar(25) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('perempuan','laki-laki','p','l','tidak_diketahui') DEFAULT NULL,
  `agama` enum('islam','kristen protestan','katolik','hindu','buddha','khonghucu','kristen','lainnya') DEFAULT NULL,
  `pendidikan` enum('belum/tidak_sekolah','diploma_1','diploma_2','diploma_3','diploma_4','sarjana_terapan','strata_1','sarjana','strata_2','magister','strata_3','doktor','tk','sd','smp','sma','d1','d2','d3','d4','s1','s2','s3','lainnya','tidak_diketahui') DEFAULT NULL,
  `pekerjaan` enum('tidak_bekerja','belum/tidak_sekolah','pelajar/mahasiswa','ibu_rumah_tangga','pensiunan','pegawai_negeri_sipil','pegawai_swasta','wiraswasta','pengusaha','petani','peternak','nelayan','buruh','tni/polri','karyawan_bumn','karyawan_bumd','profesional','tenaga_medis','guru/dosen','seniman/artis','ojol/driver_online','pekerja_lepas','pekerja_serabutan','sopir','lainnya','tidak_diketahui') DEFAULT NULL,
  `status_perkawinan` enum('belum_kawin','kawin_tercatat','kawin_tidak_tercatat','kawin_siri','cerai_hidup_tercatat','cerai_hidup_tidak_tercatat','cerai_mati_tidak_tercatat','duda','janda','hidup_berdampingan','kawin','cerai_hidup','cerai_mati','single','married','divorced','widowed','separated','cohabitation','tidak_diketahui') DEFAULT NULL,
  `tanggal_catat_kawin` date DEFAULT NULL,
  `kewarganegaraan` enum('wni','wna','wni_keturunan','wni_naturalisasi','wna_tinggal_tetap','wna_kerja','wna_diplomatik','bipatride','apatride','stateless','tidak_diketahui') DEFAULT 'tidak_diketahui',
  `status_hubungan` enum('kepala_keluarga','istri','anak','orang_tua','cucu','saudara','famili_lain','lainnya','tidak_diketahui') DEFAULT 'tidak_diketahui',
  `golongan_darah` enum('A','B','AB','O','A+','A-','B+','B-','AB+','AB-','O+','O-','tidak_diketahui') DEFAULT NULL,
  `nama_ayah` varchar(100) DEFAULT NULL,
  `nama_ibu` varchar(100) DEFAULT NULL,
  `alamat` text,
  `path_foto` varchar(255) DEFAULT NULL COMMENT 'Path relatif atau URL ke file foto individu',
  `add_for_tamiu` int DEFAULT NULL COMMENT 'kolom_untuk_tambahan_value_krama_tamiu_dan_tamiu',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_nik_nasional` (`nik_nasional`)
) ENGINE=InnoDB AUTO_INCREMENT=4535 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nomor_surat_counter_cerai`
--

DROP TABLE IF EXISTS `nomor_surat_counter_cerai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nomor_surat_counter_cerai` (
  `kode_surat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` year NOT NULL,
  `bulan` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_terakhir` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nomor_surat_counter_ilikita_utsaha`
--

DROP TABLE IF EXISTS `nomor_surat_counter_ilikita_utsaha`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nomor_surat_counter_ilikita_utsaha` (
  `kode_surat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` year NOT NULL,
  `bulan` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_terakhir` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nomor_surat_counter_kawin`
--

DROP TABLE IF EXISTS `nomor_surat_counter_kawin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nomor_surat_counter_kawin` (
  `kode_surat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bulan` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` year NOT NULL,
  `nomor_terakhir` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nomor_surat_counter_pengumuman`
--

DROP TABLE IF EXISTS `nomor_surat_counter_pengumuman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nomor_surat_counter_pengumuman` (
  `kode_surat` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` year NOT NULL,
  `bulan` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_terakhir` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `npk_counters`
--

DROP TABLE IF EXISTS `npk_counters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `npk_counters` (
  `kode_banjar_fk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nomor_terakhir` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_banjar_fk`),
  CONSTRAINT `npk_counters_kode_banjar_fk_foreign` FOREIGN KEY (`kode_banjar_fk`) REFERENCES `banjar` (`kode_banjar`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `penatahan`
--

DROP TABLE IF EXISTS `penatahan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penatahan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_dadia_fk` int DEFAULT NULL,
  `nama_penatahan` varchar(255) DEFAULT NULL,
  `id_kelihan_adat_fk` varchar(45) DEFAULT NULL,
  `kelihan_natah` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penatahan_dadia_fk_idx` (`id_dadia_fk`),
  CONSTRAINT `penatahan_dadia_fk` FOREIGN KEY (`id_dadia_fk`) REFERENCES `dadia` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `surat_cerai`
--

DROP TABLE IF EXISTS `surat_cerai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `surat_cerai` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomor_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_surat` date NOT NULL,
  `hari_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_surat_dibuat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lingkungan_banjar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_nika` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_ttl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_agama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_pekerjaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_nama_ayah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_nama_ibu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_alamat_orang_tua` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_nika` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_ttl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_agama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_pekerjaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_nama_ayah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_nama_ibu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_alamat_orang_tua` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pemuput_karya` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `saksi_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `saksi_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kepala_lingkungan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelihan_adat_banjar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lurah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `camat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_bendesa_fk` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tanggal_cerai` date DEFAULT NULL,
  `purusa_banjar_orangtua` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_banjar_orangtua` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_banjar` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_banjar` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surat_cerai_nomor_surat_unique` (`nomor_surat`),
  KEY `fk_surat_cerai_to_bendesa` (`id_bendesa_fk`),
  CONSTRAINT `fk_surat_cerai_to_bendesa` FOREIGN KEY (`id_bendesa_fk`) REFERENCES `bendesa_adat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `surat_ilikita_kawin`
--

DROP TABLE IF EXISTS `surat_ilikita_kawin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `surat_ilikita_kawin` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomor_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_surat` date DEFAULT NULL,
  `hari_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lokasi_surat_dibuat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lingkungan_banjar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_nika` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_ttl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_agama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_pekerjaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_nama_ayah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_nama_ibu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_alamat_orang_tua` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_nika` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_ttl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_agama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_pekerjaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_nama_ayah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_nama_ibu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_alamat_orang_tua` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pemuput_karya` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `saksi_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `saksi_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kepala_lingkungan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelihan_adat_banjar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lurah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `camat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_bendesa_fk` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tanggal_kawin` date DEFAULT NULL,
  `path_foto_gandeng` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_banjar` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_banjar` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_banjar_orangtua` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_banjar_orangtua` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_surat_ilikita_kawin_to_bendesa` (`id_bendesa_fk`),
  CONSTRAINT `fk_surat_ilikita_kawini_to_bendesa` FOREIGN KEY (`id_bendesa_fk`) REFERENCES `bendesa_adat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `surat_ilikita_mautsaha`
--

DROP TABLE IF EXISTS `surat_ilikita_mautsaha`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `surat_ilikita_mautsaha` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `no_surat_final` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_surat` date NOT NULL,
  `nomor_pararem` int DEFAULT NULL,
  `tahun_pararem` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_awig` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik_nika` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ttl` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tempat dan Tanggal Lahir',
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `agama` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_krama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_asal` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_adat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_perusahaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `akta_pendirian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bidang_usaha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_usaha` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_bendesa_fk` int DEFAULT NULL,
  `path_foto_mautsaha` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path ke foto usaha atau pemohon',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_surat_final_unique` (`no_surat_final`),
  KEY `bendesa_adat_ilikita_mautsaha_idx` (`id_bendesa_fk`),
  CONSTRAINT `bendesa_adat_fk_ilikita_mautsaha` FOREIGN KEY (`id_bendesa_fk`) REFERENCES `bendesa_adat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `surat_kawin`
--

DROP TABLE IF EXISTS `surat_kawin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `surat_kawin` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomor_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_kawin` date DEFAULT NULL,
  `tanggal_surat` date NOT NULL,
  `hari_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_surat_dibuat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lingkungan_banjar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_nika` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_ttl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_agama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_pekerjaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_banjar` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_nama_ayah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_nama_ibu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_alamat_orang_tua` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_nika` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_ttl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_agama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_pekerjaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_banjar` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_nama_ayah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_nama_ibu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_alamat_orang_tua` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pemuput_karya` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `saksi_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `saksi_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_bendesa_fk` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `purusa_banjar_orangtua` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_banjar_orangtua` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surat_kawin_nomor_surat_unique` (`nomor_surat`),
  KEY `fk_surat_kawin_to_bendesa` (`id_bendesa_fk`),
  CONSTRAINT `fk_surat_kawin_to_bendesa` FOREIGN KEY (`id_bendesa_fk`) REFERENCES `bendesa_adat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `surat_pengumuman_kawin`
--

DROP TABLE IF EXISTS `surat_pengumuman_kawin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `surat_pengumuman_kawin` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomor_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_surat` date NOT NULL,
  `hari_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_surat_dibuat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lingkungan_banjar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_nika` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_ttl` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_agama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_nama_ayah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_nama_ibu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purusa_alamat_orang_tua` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_nika` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_ttl` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_agama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_nama_ayah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_nama_ibu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pradana_alamat_orang_tua` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pemuput_karya` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_bendesa_fk` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pradana_banjar` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_banjar` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pradana_banjar_orangtua` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purusa_banjar_orangtua` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surat_pengumuman_kawin_nomor_surat_unique` (`nomor_surat`),
  KEY `fk_surat_pengumuman_to_bendesa` (`id_bendesa_fk`),
  CONSTRAINT `fk_surat_pengumuman_to_bendesa` FOREIGN KEY (`id_bendesa_fk`) REFERENCES `bendesa_adat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-16 16:08:58
CREATE DATABASE  IF NOT EXISTS `db_laravel` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_laravel`;
-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: db_laravel
-- ------------------------------------------------------
-- Server version	8.0.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `indonesia_cities`
--

DROP TABLE IF EXISTS `indonesia_cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `indonesia_cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `indonesia_cities_code_unique` (`code`),
  KEY `indonesia_cities_province_code_foreign` (`province_code`),
  CONSTRAINT `indonesia_cities_province_code_foreign` FOREIGN KEY (`province_code`) REFERENCES `indonesia_provinces` (`code`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=515 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `indonesia_districts`
--

DROP TABLE IF EXISTS `indonesia_districts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `indonesia_districts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` char(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_code` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `indonesia_districts_code_unique` (`code`),
  KEY `indonesia_districts_city_code_foreign` (`city_code`),
  CONSTRAINT `indonesia_districts_city_code_foreign` FOREIGN KEY (`city_code`) REFERENCES `indonesia_cities` (`code`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7267 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `indonesia_provinces`
--

DROP TABLE IF EXISTS `indonesia_provinces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `indonesia_provinces` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `indonesia_provinces_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `indonesia_villages`
--

DROP TABLE IF EXISTS `indonesia_villages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `indonesia_villages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district_code` char(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `indonesia_villages_code_unique` (`code`),
  KEY `indonesia_villages_district_code_foreign` (`district_code`),
  CONSTRAINT `indonesia_villages_district_code_foreign` FOREIGN KEY (`district_code`) REFERENCES `indonesia_districts` (`code`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=83810 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('submitted','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `role_id` bigint unsigned NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-16 16:08:58
