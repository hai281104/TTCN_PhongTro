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

### 2.3. Quy tắc mã nguồn Frontend (Tách riêng file CSS & JS)
- **Tách riêng biệt:** Tuyệt đối không viết CSS nội tuyến (`<style>`) hoặc JavaScript trực tiếp (`<script>`) bên trong file Blade View (ngoại trừ các thẻ `<link rel="stylesheet">` hoặc `<script src="...">` liên kết tệp ngoài).
- **Thư mục lưu trữ:**
  - File CSS: Lưu trữ tại thư mục `public/css/` (hoặc `resources/css/`), ví dụ: `public/css/dang-nhap.css`.
  - File JS: Lưu trữ tại thư mục `public/js/` (hoặc `resources/js/`), ví dụ: `public/js/dang-nhap.js`.
- **Quy tắc đặt tên (`kebab-case`):** Tên file CSS và JS tương ứng đồng bộ với tên màn hình / view chức năng (Ví dụ: view `xac-thuc/dang-nhap.blade.php` sẽ nạp `public/css/dang-nhap.css` và `public/js/dang-nhap.js`).

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

---

## 4. QUY TẮC VIẾT COMMENT (CHÚ THÍCH MÃ NGUỒN)
Viết comment (chú thích) hiệu quả giúp đồng đội và chính bạn đọc hiểu mã nguồn nhanh chóng mà không bị rối.

### 4.1. Nguyên tắc vàng: Giải thích "Tại sao" thay vì "Cái gì"
- **Không giải thích "Cái gì":** Tránh viết lại những gì đoạn code đã thể hiện rõ ràng.
  - *Sai:* `// Tăng i lên 1 đơn vị -> i++;`
- **Giải thích "Tại sao" và "Ý đồ":** Nêu rõ lý do bạn phải viết đoạn code đó (ví dụ giải quyết bug kỳ lạ, tối ưu hiệu năng, hoặc tuân theo một quy tắc nghiệp vụ đặc thù).
  - *Đúng:* `// Tăng thêm 2s timeout vì cổng thanh toán bên thứ 3 thường phản hồi chậm vào giờ cao điểm.`

### 4.2. Những nội dung nên có trong Comment
- **Ghi chú định hướng (Director's notes):** Chia sẻ thông tin về hiệu năng hoặc thuật toán đặc biệt (Ví dụ: `"Dùng cây nhị phân ở đây giúp xử lý nhanh hơn 40%"`).
- **Đánh dấu (Markers):** Sử dụng các từ khóa chuẩn để dễ tìm kiếm lại sau này:
  - `TODO:` Việc cần làm nhưng chưa hoàn thành.
  - `FIXME:` Code đang có lỗi hoặc cần sửa gấp.
  - `HACK:` Giải pháp tạm thời (workaround) chưa tối ưu.
  - `XXX:` Cảnh báo một đoạn code nguy hiểm hoặc dễ gây hiểu nhầm.
- **Tài liệu hóa API / Hàm (Documentation Comments):** Dùng chuẩn như phpDoc (PHP), JSDoc (JS) để mô tả tham số đầu vào, kiểu dữ liệu trả về và ngoại lệ (`@param`, `@return`, `@throws`).
- **Quy ước nhóm (Teamwork):** Khi sửa hoặc xóa các đoạn code chung (AppConstants, Utils, BaseResponse...), ghi rõ lý do, tên dev và ngày tháng trong commit/comment.

### 4.3. Những điều cần tránh khi viết Comment
- **Code tồi thì đừng chữa bằng comment:** Nếu tên biến/hàm khó hiểu, hãy đổi tên biến/hàm thay vì viết comment giải thích nó làm gì.
- **Comment lỗi thời (Outdated comments):** Code đã sửa nhưng quên sửa comment sẽ khiến người đọc hiểu sai hoàn toàn. Tuyệt đối cập nhật hoặc xóa comment khi thay đổi logic code.
- **Comment vô bổ hoặc rác:** Tránh viết những câu đùa cợt vô nghĩa hoặc comment dài dòng chiếm diện tích màn hình.

---

## 5. QUY TRÌNH PHÁT TRIỂN & QUẢN LÝ GIT (GIT WORKFLOW)

### 5.1. Bắt buộc đồng bộ code mới nhất trước khi làm tính năng mới
- **Nguyên tắc cốt lõi:** Tuyệt đối không bao giờ tạo nhánh mới từ một nhánh cũ chưa được cập nhật. Luôn luôn lấy code mới nhất từ GitHub về máy trước khi bắt đầu bất kỳ chức năng nào.
- **Quy trình 4 bước bắt buộc khi bắt đầu làm chức năng mới:**
  ```bash
  # Bước 1: Chuyển về nhánh cơ sở (main hoặc dev)
  git checkout main

  # Bước 2: BẮT BUỘC kéo commit mới nhất từ GitHub
  git pull origin main

  # Bước 3: Tạo nhánh mới cho chức năng từ nền tảng mới nhất
  git checkout -b feature/<ten-chuc-nang>

  # Bước 4: Viết code, kiểm thử, sau đó commit và đẩy lên GitHub
  git push -u origin feature/<ten-chuc-nang>
  ```

### 5.2. Quy tắc đặt tên nhánh (`kebab-case`)
- Tính năng mới: `feature/<ten-chuc-nang>` (Ví dụ: `feature/dang-nhap`, `feature/quan-ly-phong`, `feature/tinh-hoa-don`).
- Sửa lỗi: `fix/<ten-loi-can-sua>` (Ví dụ: `fix/tinh-sai-tien-dien`, `fix/loi-khoa-ngoai`).
- Cập nhật tài liệu: `docs/<noi-dung-cap-nhat>`.

### 5.3. Quy tắc viết thông điệp Commit (Conventional Commits)
- `feat:` Thêm một tính năng mới (Ví dụ: `feat: Xay dung chuc nang dang nhap va phan quyen`).
- `fix:` Sửa một lỗi (Ví dụ: `fix: Sua loi dong bo trang thai phong tro khi het hop dong`).
- `docs:` Thay đổi hoặc bổ sung tài liệu, quy tắc (Ví dụ: `docs: Cap nhat quy tac git workflow`).
- `refactor:` Tái cấu trúc mã nguồn nhưng không đổi chức năng bên ngoài.
- `chore:` Các tác vụ phụ trợ, cài đặt package, dọn dẹp file.
