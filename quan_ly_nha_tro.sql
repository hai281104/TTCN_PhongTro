-- =====================================================================
-- CƠ SỞ DỮ LIỆU: HỆ THỐNG QUẢN LÝ NHÀ TRỌ
-- Hệ quản trị: MySQL 5.7 / MySQL 8.0+
-- Bảng mã: utf8mb4 (hỗ trợ đầy đủ tiếng Việt có dấu)
-- =====================================================================

DROP DATABASE IF EXISTS quan_ly_nha_tro;
CREATE DATABASE quan_ly_nha_tro
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE quan_ly_nha_tro;
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

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
-- 2. THỂ LOẠI PHÒNG TRỌ (phân loại phòng)
-- =====================================================================
CREATE TABLE LoaiPhong (
    maLoaiPhong   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenLoaiPhong  VARCHAR(100) NOT NULL UNIQUE COMMENT 'Tên thể loại phòng, vd: Phòng đơn, Gác lửng, Studio',
    soNguoiToiDa  TINYINT UNSIGNED NOT NULL DEFAULT 2 COMMENT 'Sức chứa tối đa (số người) theo thể loại phòng',
    dienTich      DECIMAL(6,2) NULL COMMENT 'Diện tích trung bình (m2)',
    donGiaGoiY    DECIMAL(12,0) NOT NULL DEFAULT 0 COMMENT 'Đơn giá tham khảo',
    moTa          VARCHAR(255) NULL,
    dangHoatDong  TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB COMMENT='Danh mục thể loại phòng trọ';

-- =====================================================================
-- 3. PHÒNG TRỌ
-- =====================================================================
CREATE TABLE PhongTro (
    maPhong        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    soPhong        VARCHAR(20) NOT NULL UNIQUE,
    maLoaiPhong    INT UNSIGNED NULL DEFAULT NULL COMMENT 'Thể loại phòng',
    tang           VARCHAR(20) NOT NULL DEFAULT 'Tầng 1' COMMENT 'Vị trí tầng (Trệt, Tầng 1, Tầng 2...)',
    dienTich       DECIMAL(6,2) NULL COMMENT 'Diện tích thực tế của phòng (m2)',
    tienCocMacDinh DECIMAL(12,0) NOT NULL DEFAULT 0 COMMENT 'Tiền cọc gợi ý mặc định',
    tienNghi       TEXT NULL COMMENT 'JSON lưu danh sách tiện nghi nội thất có sẵn trong phòng',
    soNguoiToiDa   TINYINT UNSIGNED NOT NULL DEFAULT 2 COMMENT 'Số người tối đa được phép ở phòng này',
    donGiaMacDinh  DECIMAL(12,0) NOT NULL CHECK (donGiaMacDinh >= 0),
    trangThaiPhong ENUM('TRONG','DANG_THUE','DANG_SUA_CHUA') NOT NULL DEFAULT 'TRONG',
    ghiChu         VARCHAR(255) NULL,
    dangHoatDong   TINYINT(1) NOT NULL DEFAULT 1 COMMENT '0 = đã xóa mềm (ẩn)',
    CONSTRAINT fk_phong_loaiphong FOREIGN KEY (maLoaiPhong) REFERENCES LoaiPhong(maLoaiPhong)
) ENGINE=InnoDB COMMENT='Danh sách phòng trọ';

-- =====================================================================
-- 4. KHÁCH THUÊ
-- =====================================================================
CREATE TABLE KhachThue (
    maKhach            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    maNguoiDung        INT UNSIGNED NULL UNIQUE COMMENT 'NULL nếu khách chưa có tài khoản',
    hoTen              VARCHAR(100) NOT NULL,
    ngaySinh           DATE NULL COMMENT 'Ngày sinh của khách thuê',
    gioiTinh           ENUM('NAM','NU','KHAC') NOT NULL DEFAULT 'NAM' COMMENT 'Giới tính',
    soCCCD             VARCHAR(12)  NOT NULL UNIQUE,
    ngayCapCCCD        DATE NULL COMMENT 'Ngày cấp thẻ CCCD',
    noiCapCCCD         VARCHAR(150) NULL DEFAULT 'Cục CSQLHC về TTXH' COMMENT 'Nơi cấp thẻ CCCD',
    soDienThoai        VARCHAR(15)  NOT NULL,
    diaChi             VARCHAR(255) NULL COMMENT 'Địa chỉ thường trú / quê quán',
    ngheNghiep         VARCHAR(100) NULL COMMENT 'Nghề nghiệp / Trường học / Cơ quan',
    bienSoXe           VARCHAR(30)  NULL COMMENT 'Biển số xe máy quản lý nhà xe',
    nguoiLienHeKhanCap VARCHAR(100) NULL COMMENT 'Họ tên người liên hệ khi có sự cố khẩn cấp',
    sdtKhanCap         VARCHAR(15)  NULL COMMENT 'Số điện thoại người liên hệ khẩn cấp',
    quanHeKhanCap      VARCHAR(50)  NULL COMMENT 'Quan hệ với người liên hệ khẩn cấp (Bố/Mẹ/Anh/Chị/Bạn...)',
    daDangKyTamTru     TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '0 = Chưa đăng ký, 1 = Đã đăng ký tạm trú',
    dangHoatDong       TINYINT(1)   NOT NULL DEFAULT 1,
    CONSTRAINT fk_khach_nguoidung FOREIGN KEY (maNguoiDung)
        REFERENCES NguoiDung(maNguoiDung) ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Hồ sơ khách thuê (người đại diện hợp đồng)';

-- =====================================================================
-- 5. HỢP ĐỒNG THUÊ PHÒNG (1 người đại diện ký hợp đồng, ghi nhận số người ở)
-- =====================================================================
CREATE TABLE HopDong (
    maHopDong    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    maPhong      INT UNSIGNED NOT NULL,
    maKhach      INT UNSIGNED NOT NULL COMMENT 'Người đại diện duy nhất ký hợp đồng',
    soLuongNguoi TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Số người ở thực tế theo hợp đồng',
    ngayBatDau   DATE NOT NULL,
    ngayKetThuc  DATE NULL COMMENT 'Ngày khách dọn đi / hết hợp đồng',
    giaThueChot  DECIMAL(12,0) NOT NULL CHECK (giaThueChot >= 0),
    tienDatCoc   DECIMAL(12,0) NOT NULL DEFAULT 0 CHECK (tienDatCoc >= 0),
    tienHoanCoc  DECIMAL(12,0) NULL COMMENT 'Số tiền cọc đã hoàn khi kết thúc hợp đồng',
    trangThaiHD  ENUM('HIEU_LUC','DA_KET_THUC') NOT NULL DEFAULT 'HIEU_LUC',
    ghiChu       VARCHAR(255) NULL,
    -- Cột phụ để đảm bảo mỗi phòng chỉ có tối đa 1 hợp đồng đang hiệu lực
    phongDangThue INT UNSIGNED GENERATED ALWAYS AS
        (IF(trangThaiHD = 'HIEU_LUC', maPhong, NULL)) STORED,
    UNIQUE KEY uq_mot_hopdong_hieuluc (phongDangThue),
    CONSTRAINT fk_hopdong_phong FOREIGN KEY (maPhong) REFERENCES PhongTro(maPhong),
    CONSTRAINT fk_hopdong_khach FOREIGN KEY (maKhach) REFERENCES KhachThue(maKhach),
    CONSTRAINT ck_hopdong_ngay CHECK (ngayKetThuc IS NULL OR ngayKetThuc >= ngayBatDau),
    CONSTRAINT ck_hopdong_songuoi CHECK (soLuongNguoi > 0)
) ENGINE=InnoDB COMMENT='Hợp đồng gắn khách thuê đại diện với phòng';

-- =====================================================================
-- 5B. NGƯỜI Ở CÙNG TRONG PHÒNG (Khách thuê ở cùng phòng với người đại diện)
-- =====================================================================
CREATE TABLE NguoiOCung (
    maNguoiOCung INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    maHopDong    INT UNSIGNED NOT NULL COMMENT 'Hợp đồng phòng đang ở',
    maKhach      INT UNSIGNED NOT NULL COMMENT 'Khách thuê ở cùng trong phòng',
    ngayVaoO     DATE NULL COMMENT 'Ngày bắt đầu vào ở cùng',
    ghiChu       VARCHAR(255) NULL COMMENT 'Ghi chú / Mối quan hệ với người đại diện',
    dangHoatDong TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: Đang ở cùng, 0: Đã chuyển đi',
    UNIQUE KEY uq_hopdong_khach_ocung (maHopDong, maKhach),
    CONSTRAINT fk_nguoiocung_hopdong FOREIGN KEY (maHopDong) REFERENCES HopDong(maHopDong) ON DELETE CASCADE,
    CONSTRAINT fk_nguoiocung_khachthue FOREIGN KEY (maKhach) REFERENCES KhachThue(maKhach) ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Danh sách khách thuê ở cùng phòng trong hợp đồng';

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
CREATE INDEX idx_hopdong_khach    ON HopDong(maKhach);
CREATE INDEX idx_hoadon_trangthai ON HoaDon(trangThaiThanhToan, thangNam);
CREATE INDEX idx_phong_trangthai  ON PhongTro(trangThaiPhong);
CREATE INDEX idx_phong_loaiphong  ON PhongTro(maLoaiPhong);

-- =====================================================================
-- TRIGGER: TỰ ĐỘNG CẬP NHẬT TRẠNG THÁI PHÒNG, RÀNG BUỘC SỨC CHỨA & TỔNG TIỀN HÓA ĐƠN
-- =====================================================================
DELIMITER $$

-- Kiểm tra phòng ngưng hoạt động, phòng đang sửa chữa, hoặc vượt quá sức chứa tối đa
CREATE TRIGGER trg_hopdong_truoc_them
BEFORE INSERT ON HopDong
FOR EACH ROW
BEGIN
    DECLARE v_trangthai VARCHAR(20);
    DECLARE v_hoatdong  TINYINT;
    DECLARE v_succhua   TINYINT;
    SELECT trangThaiPhong, dangHoatDong, soNguoiToiDa INTO v_trangthai, v_hoatdong, v_succhua
    FROM PhongTro WHERE maPhong = NEW.maPhong;

    IF v_hoatdong = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Phòng này đã ngưng hoạt động, không thể lập hợp đồng';
    END IF;
    IF v_trangthai = 'DANG_SUA_CHUA' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Phòng đang sửa chữa, không thể lập hợp đồng';
    END IF;
    IF NEW.soLuongNguoi > v_succhua THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Số lượng người ở vượt quá sức chứa tối đa của phòng';
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

-- Tự động gán ngày lập hóa đơn nếu để trống (tương thích MySQL 5.7)
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

-- Danh sách phòng trống kèm thể loại phòng, vị trí tầng, diện tích và sức chứa
CREATE VIEW v_PhongTrong AS
SELECT p.maPhong, p.soPhong, p.tang, p.dienTich, lp.tenLoaiPhong, p.soNguoiToiDa, p.donGiaMacDinh, p.tienCocMacDinh
FROM PhongTro p
JOIN LoaiPhong lp ON lp.maLoaiPhong = p.maLoaiPhong
WHERE p.trangThaiPhong = 'TRONG' AND p.dangHoatDong = 1;

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
-- DỮ LIỆU MẪU BAN ĐẦU
-- =====================================================================
INSERT INTO NguoiDung (email, matKhau, vaiTro) VALUES
('chutro@example.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'CHU_TRO'),
('nguyenvana@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'KHACH_THUE');

-- 1. Thể loại phòng trọ
INSERT INTO LoaiPhong (maLoaiPhong, tenLoaiPhong, soNguoiToiDa, dienTich, donGiaGoiY, moTa) VALUES
(1, 'Phòng đơn tiêu chuẩn',  1, 15.00, 2000000, 'Phòng khép kín dành cho 1 người ở, đầy đủ tiện nghi cơ bản'),
(2, 'Phòng đôi có gác lửng', 2, 22.00, 2500000, 'Phòng có gác lửng đúc kiên cố, ban công thoáng mát'),
(3, 'Phòng gia đình / nhóm', 4, 32.00, 3500000, 'Phòng diện tích lớn, sức chứa tối đa 4 người ở thoải mái'),
(4, 'Căn hộ Studio mini',    3, 28.00, 4000000, 'Căn hộ dịch vụ mini full nội thất: máy lạnh, tủ lạnh, giường nệm');

-- 2. Phòng trọ
INSERT INTO PhongTro (soPhong, maLoaiPhong, tang, dienTich, tienCocMacDinh, tienNghi, soNguoiToiDa, donGiaMacDinh, trangThaiPhong, ghiChu) VALUES
('P101', 2, 'Tầng 1', 22.00, 2500000, '["dieu_hoa","nong_lanh","gac_lung","ke_bep"]', 2, 2500000, 'TRONG', 'Phòng gần cửa ra vào, có gác lửng cao'),
('P102', 1, 'Tầng 1', 16.00, 2000000, '["dieu_hoa","nong_lanh","giuong_nem"]', 1, 2000000, 'TRONG', 'Phòng yên tĩnh, thoáng mát'),
('P201', 3, 'Tầng 2', 32.00, 3500000, '["dieu_hoa","nong_lanh","tu_lanh","giuong_nem","tu_quan_ao","ban_cong"]', 4, 3500000, 'TRONG', 'Phòng ban công hướng Đông, đón gió mát'),
('P202', 4, 'Tầng 2', 28.00, 4000000, '["dieu_hoa","nong_lanh","tu_lanh","giuong_nem","tu_quan_ao","ke_bep","may_giat"]', 3, 4000000, 'DANG_SUA_CHUA', 'Đang sơn lại tường và kiểm tra đường nước');

-- 3. Dịch vụ
INSERT INTO DichVu (tenDichVu, loaiDichVu, donGia, donViTinh, cachTinh) VALUES
('Điện',  'DIEN', 3500,  'kWh',   'THEO_CHI_SO'),
('Nước',  'NUOC', 15000, 'm3',    'THEO_CHI_SO'),
('Rác',   'KHAC', 20000, 'phòng', 'CO_DINH_PHONG'),
('Wifi',  'KHAC', 50000, 'phòng', 'CO_DINH_PHONG');

-- 4. Khách thuê (Người đại diện & Người ở cùng)
INSERT INTO KhachThue (maNguoiDung, hoTen, ngaySinh, gioiTinh, soCCCD, ngayCapCCCD, noiCapCCCD, soDienThoai, diaChi, ngheNghiep, bienSoXe, nguoiLienHeKhanCap, sdtKhanCap, quanHeKhanCap, daDangKyTamTru) VALUES
(2, 'Nguyễn Văn A', '2001-08-15', 'NAM', '079012345678', '2021-05-10', 'Cục CSQLHC về TTXH', '0901234567', 'Quận 7, TP. Hồ Chí Minh', 'Lập trình viên phần mềm', '59-X1 123.45', 'Nguyễn Văn Ba (Bố)', '0912345678', 'Bố', 1),
(NULL, 'Lê Thị Thảo', '2002-11-20', 'NU', '079198765432', '2022-03-15', 'Cục CSQLHC về TTXH', '0988776655', 'Bình Định', 'Kế toán viên', '77-F1 888.99', 'Lê Văn Mẹ', '0977112233', 'Mẹ', 1);

-- 5. Lập hợp đồng: 1 người đại diện ký, quy mô 2 người ở -> trigger tự chuyển P101 sang DANG_THUE
INSERT INTO HopDong (maPhong, maKhach, soLuongNguoi, ngayBatDau, giaThueChot, tienDatCoc)
VALUES (1, 1, 2, '2026-09-01', 2500000, 2500000);

-- 5B. Gán người ở cùng vào hợp đồng phòng P101
INSERT INTO NguoiOCung (maHopDong, maKhach, ngayVaoO, ghiChu, dangHoatDong)
VALUES (1, 2, '2026-09-01', 'Bạn cùng phòng với Nguyễn Văn A', 1);

-- 6. Chỉ số điện nước tháng 10/2026 cho P101
INSERT INTO ChiSoDienNuoc (maPhong, thangGhiNhan, chiSoDienCu, chiSoDienMoi, chiSoNuocCu, chiSoNuocMoi)
VALUES (1, '2026-10-01', 100, 160, 20, 28);

-- 7. Hóa đơn tháng 10/2026
INSERT INTO HoaDon (maHopDong, maChiSo, thangNam, hanThanhToan)
VALUES (1, 1, '2026-10-01', '2026-11-05');

INSERT INTO ChiTietHoaDon (maHoaDon, maDichVu, noiDung, soLuong, donGia) VALUES
(1, NULL, 'Tiền phòng tháng 10/2026', 1,  2500000),
(1, 1,    'Tiền điện (60 kWh)',       60, 3500),
(1, 2,    'Tiền nước (8 m3)',         8,  15000),
(1, 3,    'Phí rác',                  1,  20000),
(1, 4,    'Phí wifi',                 1,  50000);
