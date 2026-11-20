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
  `SoTien` decimal(15,2) NOT NULL,
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
  ADD KEY `IDX_GiaoDich_MaDonHang` (`MaDonHang`),
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

-- --------------------------------------------------------
--
-- PHẦN BỔ SUNG: BẢNG DANH MỤC, SẢN PHẨM, ĐƠN HÀNG VÀ MÃ GIẢM GIÁ
-- (Hệ thống thực phẩm sạch FreshMart)
--
-- --------------------------------------------------------

-- 1. Bảng Danh mục sản phẩm
CREATE TABLE IF NOT EXISTS `danhmuc` (
  `ID` char(36) NOT NULL,
  `TenDanhMuc` varchar(150) NOT NULL,
  `Slug` varchar(150) NOT NULL,
  `Icon` varchar(100) DEFAULT NULL,
  `ThuTu` int(11) DEFAULT 0,
  `TrangThai` tinyint(1) DEFAULT 1,
  `ThoiGianTao` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UQ_DanhMuc_Slug` (`Slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- 2. Bảng Sản phẩm thực phẩm sạch
CREATE TABLE IF NOT EXISTS `sanpham` (
  `ID` char(36) NOT NULL,
  `DanhMuc_id` char(36) NOT NULL,
  `CuaHang_id` char(36) NOT NULL,
  `TenSanPham` varchar(255) NOT NULL,
  `Slug` varchar(255) NOT NULL,
  `GiaGoc` decimal(15,2) NOT NULL,
  `GiaBan` decimal(15,2) NOT NULL,
  `DonViTinh` varchar(50) DEFAULT 'kg',
  `SoLuongTon` int(11) DEFAULT 0,
  `HinhAnh` varchar(500) DEFAULT NULL,
  `TieuChuan` varchar(100) DEFAULT 'VietGAP',
  `MoTa` text DEFAULT NULL,
  `TrangThai` tinyint(1) DEFAULT 1,
  `ThoiGianTao` datetime DEFAULT current_timestamp(),
  `ThoiGianCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UQ_SanPham_Slug` (`Slug`),
  KEY `IDX_SanPham_DanhMuc_TrangThai` (`DanhMuc_id`, `TrangThai`),
  CONSTRAINT `sanpham_ibfk_danhmuc` FOREIGN KEY (`DanhMuc_id`) REFERENCES `danhmuc` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `sanpham_ibfk_cuahang` FOREIGN KEY (`CuaHang_id`) REFERENCES `cuahang` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- 3. Bảng Mã giảm giá (Vouchers)
CREATE TABLE IF NOT EXISTS `magiamgia` (
  `ID` char(36) NOT NULL,
  `MaCode` varchar(50) NOT NULL,
  `TieuDe` varchar(200) NOT NULL,
  `LoaiGiam` varchar(20) NOT NULL DEFAULT 'CO_DINH',
  `GiaTriGiam` decimal(15,2) NOT NULL,
  `DonHangToiThieu` decimal(15,2) DEFAULT 0.00,
  `SoLuongMa` int(11) DEFAULT 100,
  `NgayKetThuc` datetime NOT NULL,
  `TrangThai` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UQ_MaGiamGia_Code` (`MaCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- 4. Bảng Sổ địa chỉ người nhận
CREATE TABLE IF NOT EXISTS `diachigiaohang` (
  `ID` char(36) NOT NULL,
  `NguoiDung_id` char(36) NOT NULL,
  `TenNguoiNhan` varchar(150) NOT NULL,
  `SoDienThoai` varchar(20) NOT NULL,
  `TinhThanh` varchar(100) NOT NULL,
  `QuanHuyen` varchar(100) NOT NULL,
  `DiaChiChiTiet` varchar(255) NOT NULL,
  `LaMacDinh` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`ID`),
  CONSTRAINT `diachigiaohang_ibfk_nguoidung` FOREIGN KEY (`NguoiDung_id`) REFERENCES `nguoidung` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- 5. Bảng Đơn hàng (Orders)
CREATE TABLE IF NOT EXISTS `donhang` (
  `ID` char(36) NOT NULL,
  `MaDonHang` varchar(50) NOT NULL,
  `NguoiDung_id` char(36) NOT NULL,
  `CuaHang_id` char(36) NOT NULL,
  `MaGiamGia_id` char(36) DEFAULT NULL,
  `HoTenNguoiNhan` varchar(150) NOT NULL,
  `SoDienThoaiNhan` varchar(20) NOT NULL,
  `DiaChiGiaoHang` varchar(500) NOT NULL,
  `TienHang` decimal(15,2) NOT NULL,
  `TienPhiVanChuyen` decimal(15,2) DEFAULT 30000.00,
  `TienGiamGia` decimal(15,2) DEFAULT 0.00,
  `TongTien` decimal(15,2) NOT NULL,
  `PhuongThucThanhToan` varchar(30) NOT NULL DEFAULT 'VIETQR',
  `TrangThaiDonHang` varchar(30) NOT NULL DEFAULT 'CHO_XAC_NHAN',
  `TrangThaiThanhToan` varchar(20) NOT NULL DEFAULT 'CHUA_THANH_TOAN',
  `NgayDat` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UQ_DonHang_Ma` (`MaDonHang`),
  KEY `IDX_DonHang_NguoiDung_NgayDat` (`NguoiDung_id`, `NgayDat` DESC),
  CONSTRAINT `donhang_ibfk_nguoidung` FOREIGN KEY (`NguoiDung_id`) REFERENCES `nguoidung` (`ID`) ON DELETE RESTRICT,
  CONSTRAINT `donhang_ibfk_cuahang` FOREIGN KEY (`CuaHang_id`) REFERENCES `cuahang` (`ID`) ON DELETE RESTRICT,
  CONSTRAINT `donhang_ibfk_magiamgia` FOREIGN KEY (`MaGiamGia_id`) REFERENCES `magiamgia` (`ID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- 6. Bảng Chi tiết đơn hàng
CREATE TABLE IF NOT EXISTS `chitietdonhang` (
  `ID` char(36) NOT NULL,
  `DonHang_id` char(36) NOT NULL,
  `SanPham_id` char(36) NOT NULL,
  `TenSanPham` varchar(255) NOT NULL,
  `DonGia` decimal(15,2) NOT NULL,
  `SoLuong` int(11) NOT NULL DEFAULT 1,
  `ThanhTien` decimal(15,2) NOT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `chitietdonhang_ibfk_donhang` FOREIGN KEY (`DonHang_id`) REFERENCES `donhang` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `chitietdonhang_ibfk_sanpham` FOREIGN KEY (`SanPham_id`) REFERENCES `sanpham` (`ID`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- 7. Cập nhật bảng Giao dịch để liên kết với Đơn hàng
ALTER TABLE `giaodich` 
  ADD COLUMN `DonHang_id` char(36) DEFAULT NULL AFTER `CongThanhToan_id`;

ALTER TABLE `giaodich` 
  ADD CONSTRAINT `giaodich_ibfk_donhang` FOREIGN KEY (`DonHang_id`) REFERENCES `donhang` (`ID`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
