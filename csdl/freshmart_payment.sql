-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2026 at 12:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `freshmart_payment`
--

-- --------------------------------------------------------

--
-- Table structure for table `congthanhtoan`
--

CREATE TABLE `congthanhtoan` (
  `ID` char(36) NOT NULL,
  `CuaHang_id` char(36) NOT NULL,
  `LoaiCongThanhToan` varchar(20) NOT NULL,
  `MaNguoiBan` varchar(100) DEFAULT NULL,
  `KhoaAPI_MaHoa` text DEFAULT NULL,
  `KhoaBiMat_MaHoa` text DEFAULT NULL,
  `CauHinhThem` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`CauHinhThem`)),
  `TrangThaiHoatDong` tinyint(1) DEFAULT 1,
  `ThoiGianTao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cuahang`
--

CREATE TABLE `cuahang` (
  `ID` char(36) NOT NULL,
  `NguoiDung_id` char(36) NOT NULL,
  `TenCH` varchar(200) NOT NULL,
  `MoTaCH` text DEFAULT NULL,
  `ThoiGianTao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `giaodich`
--

CREATE TABLE `giaodich` (
  `ID` char(36) NOT NULL,
  `CuaHang_id` char(36) NOT NULL,
  `CongThanhToan_id` char(36) DEFAULT NULL,
  `MaDonHang` varchar(50) NOT NULL,
  `SoTien` bigint(20) NOT NULL,
  `MoTa` varchar(255) DEFAULT NULL,
  `LoaiCongThanhToan` varchar(20) NOT NULL,
  `TrangThai` varchar(20) NOT NULL DEFAULT 'PENDING',
  `DuLieuQR` text DEFAULT NULL,
  `DuongDanThanhToan` text DEFAULT NULL,
  `MaGiaoDichCongThanhToan` varchar(100) DEFAULT NULL,
  `ThoiGianThanhToan` datetime DEFAULT NULL,
  `ThoiGianTao` datetime DEFAULT current_timestamp(),
  `ThoiGianCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nguoidung`
--

CREATE TABLE `nguoidung` (
  `ID` char(36) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `MatKhauMaHoa` varchar(255) NOT NULL,
  `HoTen` varchar(150) DEFAULT NULL,
  `VaiTro` varchar(20) NOT NULL DEFAULT 'OWNER',
  `ThoiGianTao` datetime DEFAULT current_timestamp(),
  `ThoiGianCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhatkywebhook`
--

CREATE TABLE `nhatkywebhook` (
  `ID` char(36) NOT NULL,
  `GiaoDich_id` char(36) DEFAULT NULL,
  `LoaiCongThanhToan` varchar(20) NOT NULL,
  `DuLieuGuiDen` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`DuLieuGuiDen`)),
  `ThongTinHeader` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ThongTinHeader`)),
  `DaXacThuc` tinyint(1) DEFAULT NULL,
  `DaXuLy` tinyint(1) DEFAULT 0,
  `ThoiGianTao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `congthanhtoan`
--
ALTER TABLE `congthanhtoan`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_shop_gateway` (`CuaHang_id`,`LoaiCongThanhToan`);

--
-- Indexes for table `cuahang`
--
ALTER TABLE `cuahang`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `NguoiDung_id` (`NguoiDung_id`);

--
-- Indexes for table `giaodich`
--
ALTER TABLE `giaodich`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_shop_order` (`CuaHang_id`,`MaDonHang`),
  ADD KEY `CongThanhToan_id` (`CongThanhToan_id`),
  ADD KEY `IDX_GiaoDich_CuaHang_TrangThai` (`CuaHang_id`,`TrangThai`),
  ADD KEY `IDX_GiaoDich_ThoiGianTao` (`ThoiGianTao`);

--
-- Indexes for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `nhatkywebhook`
--
ALTER TABLE `nhatkywebhook`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDX_NhatKyWebhook_GiaoDich` (`GiaoDich_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `congthanhtoan`
--
ALTER TABLE `congthanhtoan`
  ADD CONSTRAINT `congthanhtoan_ibfk_1` FOREIGN KEY (`CuaHang_id`) REFERENCES `cuahang` (`ID`);

--
-- Constraints for table `cuahang`
--
ALTER TABLE `cuahang`
  ADD CONSTRAINT `cuahang_ibfk_1` FOREIGN KEY (`NguoiDung_id`) REFERENCES `nguoidung` (`ID`);

--
-- Constraints for table `giaodich`
--
ALTER TABLE `giaodich`
  ADD CONSTRAINT `giaodich_ibfk_1` FOREIGN KEY (`CuaHang_id`) REFERENCES `cuahang` (`ID`),
  ADD CONSTRAINT `giaodich_ibfk_2` FOREIGN KEY (`CongThanhToan_id`) REFERENCES `congthanhtoan` (`ID`);

--
-- Constraints for table `nhatkywebhook`
--
ALTER TABLE `nhatkywebhook`
  ADD CONSTRAINT `nhatkywebhook_ibfk_1` FOREIGN KEY (`GiaoDich_id`) REFERENCES `giaodich` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
