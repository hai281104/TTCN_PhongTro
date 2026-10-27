# QUY TẮC DỰ ÁN: HỆ THỐNG QUẢN LÝ NHÀ TRỌ (PROJECT RULES)

> CSDL: MySQL `quan_ly_nha_tro` (File cấu trúc: `quan_ly_nha_tro.sql`)

---

## 1. QUY TẮC ĐẶT TÊN (100% TIẾNG VIỆT KHÔNG DẤU)
Tuyệt đối không pha trộn tiếng Anh và tiếng Việt trong định danh code (Ví dụ: KHÔNG dùng `getDanhSachPhong`, PHẢI DÙNG `layDanhSachPhong`). Toàn bộ UI, thông báo và chú thích phải là Tiếng Việt có dấu chuẩn chỉnh.

### 1.1. Biến & Hàm (`camelCase`)
- **Biến:** Khớp chính xác với tên cột trong CSDL MySQL.
  - Ví dụ: `$maPhong`, `$soPhong`, `$donGiaMacDinh`, `$danhSachKhachThue`, `$tongTienNo`, `$chiSoDienMoi`.
- **Biến Boolean:** Bắt đầu bằng các tiền tố trạng thái: `dang`, `da`, `la`, `co`, `choPhep`.
  - Ví dụ: `$dangHoatDong`, `$daThanhToan`, `$laChuTro`, `$coTaiKhoan`.
- **Hàm / Phương thức:** Bắt đầu bằng động từ tiếng Việt (`lay`, `tao`, `capNhat`, `xoa`, `tinh`, `kiemTra`, `xuat`, `xuLy`).
  - Ví dụ: `layDanhSachPhong()`, `taoHopDongMoi()`, `tinhTienDienNuoc()`, `capNhatTrangThaiThanhToan()`, `xuatHoaDonPDF()`.

### 1.2. Model, Controller & Component (`PascalCase`)
- **Thực thể (Models):** `NguoiDung`, `PhongTro`, `KhachThue`, `HopDong`, `NguoiOCung`, `DichVu`, `ChiSoDienNuoc`, `HoaDon`, `ChiTietHoaDon`.
- **Controllers & Services:** `NguoiDungController`, `PhongTroController`, `KhachThueController`, `HopDongController`, `DichVuController`, `ChiSoDienNuocController`, `HoaDonController`, `ThongKeController`.
- **Components:** `BangDanhSachPhong`, `BieuMauHopDong`, `ModalChiTietHoaDon`.

### 1.3. Hằng số & Enum (`UPPER_SNAKE_CASE`)
- Vai trò: `CHU_TRO`, `KHACH_THUE`
- Trạng thái phòng: `TRONG`, `DANG_THUE`, `DANG_SUA_CHUA`
- Trạng thái hợp đồng: `HIEU_LUC`, `DA_KET_THUC`
- Trạng thái thanh toán: `CON_NO`, `DA_THANH_TOAN`
- Cách tính dịch vụ: `THEO_CHI_SO`, `CO_DINH_PHONG`, `THEO_DAU_NGUOI`

### 1.4. File View, Thư mục, Route (`kebab-case`)
- **Route Web:** `/phong-tro`, `/khach-thue`, `/hop-dong`, `/chi-so-dien-nuoc`, `/hoa-don`, `/thong-ke`
- **Route API:** `/api/phong-tro`, `/api/khach-thue`, `/api/hop-dong`, `/api/hoa-don`...
- **View Blade:** `resources/views/quan-ly-phong/danh-sach.blade.php`, `resources/views/quan-ly-hop-dong/tao-moi.blade.php`...

---

## 2. QUY TẮC THIẾT KẾ GIAO DIỆN (UI/UX MONOCHROME TRẮNG & XÁM)
Phong cách chủ đạo: **Tối giản (Minimalist), Sang trọng, Hiện đại với tông màu Trắng & Xám (Monochrome White & Gray/Zinc)**.

### 2.1. Bảng màu (Color Palette)
- **Nền trang chính:** `bg-zinc-50`
- **Thẻ (Card), Khung Bảng (Table), Modal:** Nền trắng `bg-white`, viền mảnh `border border-zinc-200`, bo góc `rounded-lg`, bóng nhẹ `shadow-xs`.
- **Màu chữ:**
  - Tiêu đề & nội dung chính: `text-zinc-900`
  - Nhãn & nội dung phụ: `text-zinc-600` hoặc `text-zinc-500`
  - Placeholder & gợi ý: `text-zinc-400`
- **Nút bấm:**
  - Nút chính (Primary Action): `bg-zinc-900 text-white hover:bg-zinc-800`
  - Nút phụ (Secondary Action): `bg-white border border-zinc-300 text-zinc-700 hover:bg-zinc-100`
- **Nhãn trạng thái (Status Badges) - Pastel thanh lịch:**
  - `TRONG`, `DA_THANH_TOAN`, `HIEU_LUC`: `bg-emerald-50 text-emerald-700 border border-emerald-200`
  - `DANG_THUE`: `bg-sky-50 text-sky-700 border border-sky-200`
  - `DANG_SUA_CHUA`: `bg-amber-50 text-amber-700 border border-amber-200`
  - `CON_NO`: `bg-red-50 text-red-700 border border-red-200`
  - `DA_KET_THUC`: `bg-zinc-100 text-zinc-600 border border-zinc-200`

### 2.2. Định dạng dữ liệu (Formatting Standards)
- **Tiền tệ:** `number_format($soTien, 0, ',', '.') . ' đ'` (Căn phải: `text-right tabular-nums`).
- **Ngày tháng:** Chuẩn Việt Nam `d/m/Y` (Ví dụ: `27/10/2026`), tháng năm thu phí `m/Y` (Ví dụ: `10/2026`).
- **Điện / Nước:** `160 kWh`, `28 m³`.

---

## 3. QUY TẮC NGHIỆP VỤ & RÀNG BUỘC CSDL
1. **Xóa mềm (Soft Delete):** Không xóa cứng `PhongTro`, `KhachThue`, `NguoiDung`. Khi xóa chỉ cập nhật `dangHoatDong = 0`.
2. **Hợp đồng & Phòng:**
   - Mỗi phòng chỉ có tối đa 1 hợp đồng đang hiệu lực (`HIEU_LUC`).
   - Phòng `DANG_SUA_CHUA` hoặc `dangHoatDong = 0` không được phép tạo hợp đồng mới (được trigger chặn).
   - Cột `phongDangThue` trong bảng `HopDong` là `GENERATED ALWAYS`, không thêm vào `$fillable`.
3. **Chỉ số điện nước:**
   - Cột `soDienTieuThu` và `soNuocTieuThu` là `GENERATED ALWAYS`, không thêm vào `$fillable`.
   - Mỗi phòng duy nhất 1 bản ghi mỗi tháng (`thangGhiNhan` là ngày 01 của tháng).
4. **Hóa đơn & Chi tiết hóa đơn:**
   - Cột `thanhTien` trong `ChiTietHoaDon` là `GENERATED ALWAYS`, không thêm vào `$fillable`.
   - `tongTien` trong `HoaDon` tự động tính từ trigger CSDL.
5. **Phân quyền (RBAC):**
   - `CHU_TRO`: Quản lý toàn bộ hệ thống.
   - `KHACH_THUE`: Chỉ xem dữ liệu phòng mình đang thuê.
