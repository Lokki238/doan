-- ========================================================
-- FILE: csdl/upgrade_tidb_cloud.sql
-- Mục đích: Script cập nhật CSDL trên TiDB Cloud & phpMyAdmin
-- Giải quyết:
--   1. Đổi ON DELETE CASCADE sang RESTRICT trên bảng donhang
--   2. Xóa UNIQUE(CuaHang_id, MaDonHang) trên bảng giaodich
--   3. Chuyển kiểu tiền tệ sang DECIMAL(15, 2)
--   4. Thêm các INDEX quan trọng
--   5. Bổ sung cột DonHang_id và khóa ngoại vào bảng giaodich
-- ========================================================

USE freshmart_payment;

-- --------------------------------------------------------
-- BƯỚC 1: SỬA ON DELETE CASCADE THÀNH RESTRICT (TRÁNH MẤT DOANH THU)
-- --------------------------------------------------------
ALTER TABLE donhang DROP FOREIGN KEY donhang_ibfk_nguoidung;
ALTER TABLE donhang ADD CONSTRAINT donhang_ibfk_nguoidung FOREIGN KEY (NguoiDung_id) REFERENCES nguoidung (ID) ON DELETE RESTRICT;

ALTER TABLE donhang DROP FOREIGN KEY donhang_ibfk_cuahang;
ALTER TABLE donhang ADD CONSTRAINT donhang_ibfk_cuahang FOREIGN KEY (CuaHang_id) REFERENCES cuahang (ID) ON DELETE RESTRICT;

-- --------------------------------------------------------
-- BƯỚC 2: XÓA RÀNG BUỘC UNIQUE Ở BẢNG GIAODICH (CHO PHÉP THANH TOÁN LẠI ĐƠN HÀNG)
-- --------------------------------------------------------
-- Nếu bảng giaodich có index unique_shop_order thì xóa:
ALTER TABLE giaodich DROP INDEX unique_shop_order;

-- --------------------------------------------------------
-- BƯỚC 3: CHUYỂN TOÀN BỘ CỘT TIỀN TỆ TỪ BIGINT SANG DECIMAL(15, 2)
-- --------------------------------------------------------
ALTER TABLE sanpham MODIFY GiaGoc DECIMAL(15, 2) NOT NULL;
ALTER TABLE sanpham MODIFY GiaBan DECIMAL(15, 2) NOT NULL;

ALTER TABLE magiamgia MODIFY GiaTriGiam DECIMAL(15, 2) NOT NULL;
ALTER TABLE magiamgia MODIFY DonHangToiThieu DECIMAL(15, 2) DEFAULT 0.00;

ALTER TABLE donhang MODIFY TienHang DECIMAL(15, 2) NOT NULL;
ALTER TABLE donhang MODIFY TienPhiVanChuyen DECIMAL(15, 2) DEFAULT 30000.00;
ALTER TABLE donhang MODIFY TienGiamGia DECIMAL(15, 2) DEFAULT 0.00;
ALTER TABLE donhang MODIFY TongTien DECIMAL(15, 2) NOT NULL;

ALTER TABLE chitietdonhang MODIFY DonGia DECIMAL(15, 2) NOT NULL;
ALTER TABLE chitietdonhang MODIFY ThanhTien DECIMAL(15, 2) NOT NULL;

ALTER TABLE giaodich MODIFY SoTien DECIMAL(15, 2) NOT NULL;

-- --------------------------------------------------------
-- BƯỚC 4: THÊM CÁC INDEX TỐI ƯU TRUY VẤN
-- --------------------------------------------------------
ALTER TABLE sanpham ADD INDEX IDX_SanPham_DanhMuc_TrangThai (DanhMuc_id, TrangThai);
ALTER TABLE donhang ADD INDEX IDX_DonHang_NguoiDung_NgayDat (NguoiDung_id, NgayDat DESC);
ALTER TABLE giaodich ADD INDEX IDX_GiaoDich_MaDonHang (MaDonHang);

-- --------------------------------------------------------
-- BƯỚC 5: BỔ SUNG CỘT DonHang_id VÀ KHÓA NGOẠI VÀO BẢNG giaodich
-- (Lưu ý: TiDB Cloud yêu cầu chạy lệnh thêm cột trước, sau đó mới tạo khóa ngoại)
-- --------------------------------------------------------
ALTER TABLE giaodich ADD COLUMN DonHang_id char(36) DEFAULT NULL AFTER CongThanhToan_id;
ALTER TABLE giaodich ADD CONSTRAINT giaodich_ibfk_donhang FOREIGN KEY (DonHang_id) REFERENCES donhang (ID) ON DELETE SET NULL;
