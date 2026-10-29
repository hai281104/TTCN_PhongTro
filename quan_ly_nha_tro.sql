-- =====================================================================
-- CƠ SỞ DỮ LIỆU: HỆ THỐNG QUẢN LÝ NHÀ TRỌ
-- Hệ quản trị: MySQL 8.0.16 trở lên (cần cho ràng buộc CHECK)
-- Bảng mã: utf8mb4 (hỗ trợ đầy đủ tiếng Việt có dấu)
-- =====================================================================

DROP DATABASE IF EXISTS quan_ly_nha_tro;
CREATE DATABASE quan_ly_nha_tro
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE quan_ly_nha_tro;

-- =====================================================================
-- 1. NGƯỜI DÙNG (đăng nhập + phân quyền)
-- =====================================================================
CREATE TABLE NguoiDung (
    maNguoiDung   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email         VARCHAR(150) NOT NULL UNIQUE COMMENT 'Dùng làm tên đăng nhập',
    matKhau       VARCHAR(255) NOT NULL COMMENT 'Lưu mã băm (bcrypt/argon2), KHÔNG lưu mật khẩu thô',
    vaiTro        ENUM('CHU_TRO','KHACH_THUE') NOT NULL DEFAULT 'KHACH_THUE',
    dangHoatDong  TINYINT(1) NOT NULL DEFAULT 1,
    ngayTao       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB COMMENT='Tài khoản đăng nhập: chủ trọ hoặc khách thuê';

-- =====================================================================
-- 2. PHÒNG TRỌ
-- =====================================================================
CREATE TABLE PhongTro (
    maPhong        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    soPhong        VARCHAR(20) NOT NULL UNIQUE,
    donGiaMacDinh  DECIMAL(12,0) NOT NULL CHECK (donGiaMacDinh >= 0),
    trangThaiPhong ENUM('TRONG','DANG_THUE','DANG_SUA_CHUA') NOT NULL DEFAULT 'TRONG',
    ghiChu         VARCHAR(255) NULL,
    dangHoatDong   TINYINT(1) NOT NULL DEFAULT 1 COMMENT '0 = đã "xóa" (ẩn), giữ lại để không mất lịch sử'
) ENGINE=InnoDB COMMENT='Danh sách phòng trọ';

-- =====================================================================
-- 3. KHÁCH THUÊ
-- =====================================================================
CREATE TABLE KhachThue (
    maKhach       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    maNguoiDung   INT UNSIGNED NULL UNIQUE COMMENT 'NULL nếu khách chưa có tài khoản',
    hoTen         VARCHAR(100) NOT NULL,
    soCCCD        VARCHAR(12)  NOT NULL UNIQUE,
    soDienThoai   VARCHAR(15)  NOT NULL,
    diaChi        VARCHAR(255) NULL COMMENT 'Địa chỉ thường trú / thông tin liên hệ',
    dangHoatDong  TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT fk_khach_nguoidung FOREIGN KEY (maNguoiDung)
        REFERENCES NguoiDung(maNguoiDung) ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Hồ sơ khách thuê (người đại diện hợp đồng)';

-- =====================================================================
-- 4. HỢP ĐỒNG THUÊ PHÒNG
-- =====================================================================
CREATE TABLE HopDong (
    maHopDong    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    maPhong      INT UNSIGNED NOT NULL,
    maKhach      INT UNSIGNED NOT NULL COMMENT 'Người chịu trách nhiệm chính',
    ngayBatDau   DATE NOT NULL,
    ngayKetThuc  DATE NULL COMMENT 'Ngày khách dọn đi / hết hợp đồng',
    giaThueChot  DECIMAL(12,0) NOT NULL CHECK (giaThueChot >= 0),
    tienDatCoc   DECIMAL(12,0) NOT NULL DEFAULT 0 CHECK (tienDatCoc >= 0),
    tienHoanCoc  DECIMAL(12,0) NULL COMMENT 'Số tiền cọc đã hoàn khi kết thúc hợp đồng',
    trangThaiHD  ENUM('HIEU_LUC','DA_KET_THUC') NOT NULL DEFAULT 'HIEU_LUC',
    -- Cột phụ để đảm bảo mỗi phòng chỉ có tối đa 1 hợp đồng đang hiệu lực
    phongDangThue INT UNSIGNED GENERATED ALWAYS AS
        (IF(trangThaiHD = 'HIEU_LUC', maPhong, NULL)) STORED,
    UNIQUE KEY uq_mot_hopdong_hieuluc (phongDangThue),
    CONSTRAINT fk_hopdong_phong FOREIGN KEY (maPhong) REFERENCES PhongTro(maPhong),
    CONSTRAINT fk_hopdong_khach FOREIGN KEY (maKhach) REFERENCES KhachThue(maKhach),
    CONSTRAINT ck_hopdong_ngay CHECK (ngayKetThuc IS NULL OR ngayKetThuc >= ngayBatDau)
) ENGINE=InnoDB COMMENT='Hợp đồng gắn khách thuê với phòng';

-- =====================================================================
-- 5. NGƯỜI Ở CÙNG (quản lý nhân khẩu trong phòng)
-- =====================================================================
CREATE TABLE NguoiOCung (
    maNguoiO     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    maHopDong    INT UNSIGNED NOT NULL,
    hoTen        VARCHAR(100) NOT NULL,
    soCCCD       VARCHAR(12)  NULL,
    soDienThoai  VARCHAR(15)  NULL,
    quanHe       VARCHAR(50)  NULL COMMENT 'Quan hệ với người đại diện (vợ/chồng, bạn, anh/chị/em...)',
    CONSTRAINT fk_nguoio_hopdong FOREIGN KEY (maHopDong)
        REFERENCES HopDong(maHopDong) ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Những người ở cùng ngoài người đại diện hợp đồng';

-- =====================================================================
-- 6. DỊCH VỤ & ĐƠN GIÁ (điện, nước, rác, wifi...)
-- =====================================================================
CREATE TABLE DichVu (
    maDichVu     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenDichVu    VARCHAR(100) NOT NULL UNIQUE,
    loaiDichVu   ENUM('DIEN','NUOC','KHAC') NOT NULL DEFAULT 'KHAC',
    donGia       DECIMAL(12,0) NOT NULL CHECK (donGia >= 0),
    donViTinh    VARCHAR(30) NOT NULL COMMENT 'kWh, m3, phòng, người...',
    cachTinh     ENUM('THEO_CHI_SO','CO_DINH_PHONG','THEO_DAU_NGUOI') NOT NULL,
    dangApDung   TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB COMMENT='Danh mục dịch vụ và đơn giá hiện hành';

-- =====================================================================
-- 7. CHỈ SỐ ĐIỆN NƯỚC HÀNG THÁNG
-- =====================================================================
CREATE TABLE ChiSoDienNuoc (
    maChiSo        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    maPhong        INT UNSIGNED NOT NULL,
    thangGhiNhan   DATE NOT NULL COMMENT 'Lưu ngày 01 của tháng, ví dụ 2026-10-01',
    chiSoDienCu    INT UNSIGNED NOT NULL,
    chiSoDienMoi   INT UNSIGNED NOT NULL,
    chiSoNuocCu    INT UNSIGNED NOT NULL,
    chiSoNuocMoi   INT UNSIGNED NOT NULL,
    soDienTieuThu  INT UNSIGNED GENERATED ALWAYS AS (chiSoDienMoi - chiSoDienCu) STORED,
    soNuocTieuThu  INT UNSIGNED GENERATED ALWAYS AS (chiSoNuocMoi - chiSoNuocCu) STORED,
    ngayGhi        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_phong_thang (maPhong, thangGhiNhan),
    CONSTRAINT fk_chiso_phong FOREIGN KEY (maPhong) REFERENCES PhongTro(maPhong),
    CONSTRAINT ck_chiso_dien CHECK (chiSoDienMoi >= chiSoDienCu),
    CONSTRAINT ck_chiso_nuoc CHECK (chiSoNuocMoi >= chiSoNuocCu)
) ENGINE=InnoDB COMMENT='Mỗi phòng có 1 bản ghi chỉ số cho mỗi tháng';

-- =====================================================================
-- 8. HÓA ĐƠN
-- =====================================================================
CREATE TABLE HoaDon (
    maHoaDon           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    maHopDong          INT UNSIGNED NOT NULL,
    maChiSo            INT UNSIGNED NULL COMMENT 'Chỉ số điện nước dùng để tính hóa đơn này',
    thangNam           DATE NOT NULL COMMENT 'Lưu ngày 01 của tháng thu tiền',
    ngayLap            DATE NULL,
    hanThanhToan       DATE NULL,
    tongTien           DECIMAL(14,0) NOT NULL DEFAULT 0,
    trangThaiThanhToan ENUM('CON_NO','DA_THANH_TOAN') NOT NULL DEFAULT 'CON_NO',
    ngayThanhToan      DATE NULL,
    ghiChu             VARCHAR(255) NULL,
    UNIQUE KEY uq_hopdong_thang (maHopDong, thangNam),
    CONSTRAINT fk_hoadon_hopdong FOREIGN KEY (maHopDong) REFERENCES HopDong(maHopDong),
    CONSTRAINT fk_hoadon_chiso   FOREIGN KEY (maChiSo)   REFERENCES ChiSoDienNuoc(maChiSo)
) ENGINE=InnoDB COMMENT='Hóa đơn hàng tháng của mỗi hợp đồng';

-- =====================================================================
-- 9. CHI TIẾT HÓA ĐƠN (tiền phòng, điện, nước, rác, wifi...)
-- =====================================================================
CREATE TABLE ChiTietHoaDon (
    maChiTiet  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    maHoaDon   INT UNSIGNED NOT NULL,
    maDichVu   INT UNSIGNED NULL COMMENT 'NULL nếu là dòng tiền phòng',
    noiDung    VARCHAR(150) NOT NULL,
    soLuong    DECIMAL(10,2) NOT NULL DEFAULT 1,
    donGia     DECIMAL(12,0) NOT NULL,
    thanhTien  DECIMAL(14,0) GENERATED ALWAYS AS (ROUND(soLuong * donGia, 0)) STORED,
    CONSTRAINT fk_cthd_hoadon  FOREIGN KEY (maHoaDon) REFERENCES HoaDon(maHoaDon) ON DELETE CASCADE,
    CONSTRAINT fk_cthd_dichvu  FOREIGN KEY (maDichVu) REFERENCES DichVu(maDichVu)
) ENGINE=InnoDB COMMENT='Các khoản thu trong từng hóa đơn (lưu đơn giá tại thời điểm lập)';

-- =====================================================================
-- CHỈ MỤC HỖ TRỢ TRUY VẤN
-- =====================================================================
CREATE INDEX idx_hopdong_khach   ON HopDong(maKhach);
CREATE INDEX idx_hoadon_trangthai ON HoaDon(trangThaiThanhToan, thangNam);
CREATE INDEX idx_phong_trangthai  ON PhongTro(trangThaiPhong);

-- =====================================================================
-- TRIGGER: TỰ ĐỘNG CẬP NHẬT TRẠNG THÁI PHÒNG & TỔNG TIỀN HÓA ĐƠN
-- =====================================================================
DELIMITER $$

-- Không cho lập hợp đồng trên phòng đang sửa chữa hoặc đã bị ẩn
CREATE TRIGGER trg_hopdong_truoc_them
BEFORE INSERT ON HopDong
FOR EACH ROW
BEGIN
    DECLARE v_trangthai VARCHAR(20);
    DECLARE v_hoatdong  TINYINT;
    SELECT trangThaiPhong, dangHoatDong INTO v_trangthai, v_hoatdong
    FROM PhongTro WHERE maPhong = NEW.maPhong;

    IF v_hoatdong = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Phòng này đã ngưng hoạt động, không thể lập hợp đồng';
    END IF;
    IF v_trangthai = 'DANG_SUA_CHUA' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Phòng đang sửa chữa, không thể lập hợp đồng';
    END IF;
END$$

-- Lập hợp đồng hiệu lực -> phòng chuyển sang "đang thuê"
CREATE TRIGGER trg_hopdong_sau_them
AFTER INSERT ON HopDong
FOR EACH ROW
BEGIN
    IF NEW.trangThaiHD = 'HIEU_LUC' THEN
        UPDATE PhongTro SET trangThaiPhong = 'DANG_THUE' WHERE maPhong = NEW.maPhong;
    END IF;
END$$

-- Kết thúc hợp đồng -> tự điền ngày kết thúc nếu để trống
CREATE TRIGGER trg_hopdong_truoc_sua
BEFORE UPDATE ON HopDong
FOR EACH ROW
BEGIN
    IF NEW.trangThaiHD = 'DA_KET_THUC' AND OLD.trangThaiHD = 'HIEU_LUC'
       AND NEW.ngayKetThuc IS NULL THEN
        SET NEW.ngayKetThuc = CURRENT_DATE;
    END IF;
END$$

-- Kết thúc hợp đồng -> phòng tự trả về "trống"
CREATE TRIGGER trg_hopdong_sau_sua
AFTER UPDATE ON HopDong
FOR EACH ROW
BEGIN
    IF NEW.trangThaiHD = 'DA_KET_THUC' AND OLD.trangThaiHD = 'HIEU_LUC' THEN
        UPDATE PhongTro SET trangThaiPhong = 'TRONG' WHERE maPhong = NEW.maPhong;
    END IF;
END$$

-- Tự tính lại tổng tiền hóa đơn khi chi tiết thay đổi
CREATE TRIGGER trg_cthd_sau_them
AFTER INSERT ON ChiTietHoaDon
FOR EACH ROW
BEGIN
    UPDATE HoaDon
    SET tongTien = (SELECT COALESCE(SUM(thanhTien),0) FROM ChiTietHoaDon WHERE maHoaDon = NEW.maHoaDon)
    WHERE maHoaDon = NEW.maHoaDon;
END$$

CREATE TRIGGER trg_cthd_sau_sua
AFTER UPDATE ON ChiTietHoaDon
FOR EACH ROW
BEGIN
    UPDATE HoaDon
    SET tongTien = (SELECT COALESCE(SUM(thanhTien),0) FROM ChiTietHoaDon WHERE maHoaDon = NEW.maHoaDon)
    WHERE maHoaDon = NEW.maHoaDon;
END$$

CREATE TRIGGER trg_cthd_sau_xoa
AFTER DELETE ON ChiTietHoaDon
FOR EACH ROW
BEGIN
    UPDATE HoaDon
    SET tongTien = (SELECT COALESCE(SUM(thanhTien),0) FROM ChiTietHoaDon WHERE maHoaDon = OLD.maHoaDon)
    WHERE maHoaDon = OLD.maHoaDon;
END$$

-- Tu dong gan ngay lap hoa don neu de trong
CREATE TRIGGER trg_hoadon_truoc_them
BEFORE INSERT ON HoaDon
FOR EACH ROW
BEGIN
    IF NEW.ngayLap IS NULL THEN
        SET NEW.ngayLap = CURRENT_DATE;
    END IF;
END$$

DELIMITER ;

-- =====================================================================
-- VIEW PHỤC VỤ THỐNG KÊ & BÁO CÁO
-- =====================================================================

-- Danh sách phòng trống để tìm khách
CREATE VIEW v_PhongTrong AS
SELECT maPhong, soPhong, donGiaMacDinh
FROM PhongTro
WHERE trangThaiPhong = 'TRONG' AND dangHoatDong = 1;

-- Doanh thu theo từng tháng (đã lập / đã thu / còn nợ)
CREATE VIEW v_DoanhThuThang AS
SELECT
    DATE_FORMAT(thangNam, '%Y-%m')                                            AS thang,
    SUM(tongTien)                                                             AS tongDaLap,
    SUM(CASE WHEN trangThaiThanhToan = 'DA_THANH_TOAN' THEN tongTien ELSE 0 END) AS tongDaThu,
    SUM(CASE WHEN trangThaiThanhToan = 'CON_NO'        THEN tongTien ELSE 0 END) AS tongConNo
FROM HoaDon
GROUP BY DATE_FORMAT(thangNam, '%Y-%m');

-- Các phòng đang nợ tiền
CREATE VIEW v_PhongNoTien AS
SELECT
    p.soPhong,
    k.hoTen,
    k.soDienThoai,
    COUNT(h.maHoaDon) AS soHoaDonNo,
    SUM(h.tongTien)   AS tongTienNo
FROM HoaDon h
JOIN HopDong hd ON hd.maHopDong = h.maHopDong
JOIN PhongTro p ON p.maPhong    = hd.maPhong
JOIN KhachThue k ON k.maKhach   = hd.maKhach
WHERE h.trangThaiThanhToan = 'CON_NO'
GROUP BY p.soPhong, k.hoTen, k.soDienThoai;

-- =====================================================================
-- DỮ LIỆU MẪU
-- (Mật khẩu chỉ là chỗ giữ chỗ, hãy thay bằng mã băm thật từ ứng dụng)
-- =====================================================================
INSERT INTO NguoiDung (email, matKhau, vaiTro) VALUES
('chutro@example.com',   'DOI_BANG_MA_BAM_THAT', 'CHU_TRO'),
('nguyenvana@example.com','DOI_BANG_MA_BAM_THAT', 'KHACH_THUE');

INSERT INTO PhongTro (soPhong, donGiaMacDinh, trangThaiPhong) VALUES
('P101', 2500000, 'TRONG'),
('P102', 2500000, 'TRONG'),
('P201', 3000000, 'TRONG'),
('P202', 3000000, 'DANG_SUA_CHUA');

INSERT INTO DichVu (tenDichVu, loaiDichVu, donGia, donViTinh, cachTinh) VALUES
('Điện',  'DIEN', 3500,  'kWh',   'THEO_CHI_SO'),
('Nước',  'NUOC', 15000, 'm3',    'THEO_CHI_SO'),
('Rác',   'KHAC', 20000, 'phòng', 'CO_DINH_PHONG'),
('Wifi',  'KHAC', 50000, 'phòng', 'CO_DINH_PHONG');

INSERT INTO KhachThue (maNguoiDung, hoTen, soCCCD, soDienThoai, diaChi) VALUES
(2, 'Nguyễn Văn A', '079012345678', '0901234567', 'Quận 7, TP. Hồ Chí Minh');

-- Lập hợp đồng: trigger sẽ tự chuyển phòng P101 sang "đang thuê"
INSERT INTO HopDong (maPhong, maKhach, ngayBatDau, giaThueChot, tienDatCoc)
VALUES (1, 1, '2026-09-01', 2500000, 2500000);

-- Chỉ số tháng 10/2026 cho phòng P101
INSERT INTO ChiSoDienNuoc (maPhong, thangGhiNhan, chiSoDienCu, chiSoDienMoi, chiSoNuocCu, chiSoNuocMoi)
VALUES (1, '2026-10-01', 100, 160, 20, 28);

-- Hóa đơn tháng 10/2026 (tổng tiền được trigger tự cộng từ chi tiết)
INSERT INTO HoaDon (maHopDong, maChiSo, thangNam, hanThanhToan)
VALUES (1, 1, '2026-10-01', '2026-11-05');

INSERT INTO ChiTietHoaDon (maHoaDon, maDichVu, noiDung, soLuong, donGia) VALUES
(1, NULL, 'Tiền phòng tháng 10/2026', 1,  2500000),
(1, 1,    'Tiền điện (60 kWh)',       60, 3500),
(1, 2,    'Tiền nước (8 m3)',         8,  15000),
(1, 3,    'Phí rác',                  1,  20000),
(1, 4,    'Phí wifi',                 1,  50000);
