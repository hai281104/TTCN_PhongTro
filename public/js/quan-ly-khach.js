document.addEventListener('DOMContentLoaded', function () {
    const modalThem = document.getElementById('modalThemKhach');
    const nutMoThem = document.getElementById('nutMoThemKhach');
    const cacNutDongModal = document.querySelectorAll('[data-dong-modal]');

    function dongTatCaModal() {
        document.querySelectorAll('.hop-thoai-modal').forEach(function (modal) {
            modal.classList.remove('modal-hien');
            modal.classList.add('modal-an');
        });
    }

    if (nutMoThem && modalThem) {
        nutMoThem.addEventListener('click', function () {
            modalThem.classList.remove('modal-an');
            modalThem.classList.add('modal-hien');
            const oHoTen = document.getElementById('hoTenThem');
            if (oHoTen) oHoTen.focus();
        });
    }

    cacNutDongModal.forEach(function (nut) {
        nut.addEventListener('click', function () {
            dongTatCaModal();
        });
    });

    document.querySelectorAll('.hop-thoai-modal').forEach(function (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                dongTatCaModal();
            }
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            dongTatCaModal();
        }
    });

    const hopTaoTaiKhoan = document.getElementById('taoTaiKhoanThem');
    const vungThongTinTaiKhoan = document.getElementById('vungThongTinTaiKhoanThem');
    const oEmailThem = document.getElementById('emailThem');
    const oMatKhauThem = document.getElementById('matKhauThem');

    if (hopTaoTaiKhoan && vungThongTinTaiKhoan) {
        hopTaoTaiKhoan.addEventListener('change', function () {
            if (this.checked) {
                vungThongTinTaiKhoan.classList.remove('hidden');
                if (oEmailThem) oEmailThem.setAttribute('required', 'required');
                if (oMatKhauThem) oMatKhauThem.setAttribute('required', 'required');
            } else {
                vungThongTinTaiKhoan.classList.add('hidden');
                if (oEmailThem) oEmailThem.removeAttribute('required');
                if (oMatKhauThem) oMatKhauThem.removeAttribute('required');
            }
        });
    }

    // MODAL SỬA KHÁCH THUÊ
    const modalSua = document.getElementById('modalSuaKhach');
    const bieuMauSua = document.getElementById('bieuMauSuaKhach');
    const oHoTenSua = document.getElementById('hoTenSua');
    const oGioiTinhSua = document.getElementById('gioiTinhSua');
    const oNgaySinhSua = document.getElementById('ngaySinhSua');
    const oCCCDSua = document.getElementById('soCCCDSua');
    const oNgayCapCCCDSua = document.getElementById('ngayCapCCCDSua');
    const oNoiCapCCCDSua = document.getElementById('noiCapCCCDSua');
    const oDienThoaiSua = document.getElementById('soDienThoaiSua');
    const oDiaChiSua = document.getElementById('diaChiSua');
    const oBienSoXeSua = document.getElementById('bienSoXeSua');
    const oNgheNghiepSua = document.getElementById('ngheNghiepSua');
    const oNguoiKhanCapSua = document.getElementById('nguoiLienHeKhanCapSua');
    const oSdtKhanCapSua = document.getElementById('sdtKhanCapSua');
    const oQuanHeKhanCapSua = document.getElementById('quanHeKhanCapSua');
    const oTamTruSua = document.getElementById('daDangKyTamTruSua');

    document.querySelectorAll('[data-hanh-dong="sua-khach"]').forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maKhach = this.getAttribute('data-ma-khach');
            const hoTen = this.getAttribute('data-ho-ten');
            const gioiTinh = this.getAttribute('data-gioi-tinh') || 'NAM';
            const ngaySinh = this.getAttribute('data-ngay-sinh') || '';
            const soCCCD = this.getAttribute('data-so-cccd');
            const ngayCapCCCD = this.getAttribute('data-ngay-cap-cccd') || '';
            const noiCapCCCD = this.getAttribute('data-noi-cap-cccd') || 'Cục CSQLHC về TTXH';
            const soDienThoai = this.getAttribute('data-so-dien-thoai');
            const diaChi = this.getAttribute('data-dia-chi') || '';
            const bienSoXe = this.getAttribute('data-bien-so-xe') || '';
            const ngheNghiep = this.getAttribute('data-nghe-nghiep') || '';
            const nguoiKhanCap = this.getAttribute('data-nguoi-khan-cap') || '';
            const sdtKhanCap = this.getAttribute('data-sdt-khan-cap') || '';
            const quanHeKhanCap = this.getAttribute('data-quan-he-khan-cap') || '';
            const tamTru = this.getAttribute('data-tam-tru') === '1';

            if (bieuMauSua && modalSua) {
                bieuMauSua.action = '/khach-thue/' + maKhach;
                if (oHoTenSua) oHoTenSua.value = hoTen;
                if (oGioiTinhSua) oGioiTinhSua.value = gioiTinh;
                if (oNgaySinhSua) oNgaySinhSua.value = ngaySinh;
                if (oCCCDSua) oCCCDSua.value = soCCCD;
                if (oNgayCapCCCDSua) oNgayCapCCCDSua.value = ngayCapCCCD;
                if (oNoiCapCCCDSua) oNoiCapCCCDSua.value = noiCapCCCD;
                if (oDienThoaiSua) oDienThoaiSua.value = soDienThoai;
                if (oDiaChiSua) oDiaChiSua.value = diaChi;
                if (oBienSoXeSua) oBienSoXeSua.value = bienSoXe;
                if (oNgheNghiepSua) oNgheNghiepSua.value = ngheNghiep;
                if (oNguoiKhanCapSua) oNguoiKhanCapSua.value = nguoiKhanCap;
                if (oSdtKhanCapSua) oSdtKhanCapSua.value = sdtKhanCap;
                if (oQuanHeKhanCapSua) oQuanHeKhanCapSua.value = quanHeKhanCap;
                if (oTamTruSua) oTamTruSua.checked = tamTru;

                modalSua.classList.remove('modal-an');
                modalSua.classList.add('modal-hien');
                if (oHoTenSua) oHoTenSua.focus();
            }
        });
    });

    const modalCapTK = document.getElementById('modalCapTaiKhoan');
    const bieuMauCapTK = document.getElementById('bieuMauCapTaiKhoan');
    const nhanTenKhachCapTK = document.getElementById('nhanTenKhachCapTK');

    document.querySelectorAll('[data-hanh-dong="cap-tai-khoan"]').forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maKhach = this.getAttribute('data-ma-khach');
            const hoTen = this.getAttribute('data-ho-ten');

            if (bieuMauCapTK && modalCapTK) {
                bieuMauCapTK.action = '/khach-thue/' + maKhach + '/cap-tai-khoan';
                if (nhanTenKhachCapTK) nhanTenKhachCapTK.textContent = hoTen;

                modalCapTK.classList.remove('modal-an');
                modalCapTK.classList.add('modal-hien');
                const oEmail = document.getElementById('emailCapMoi');
                if (oEmail) oEmail.focus();
            }
        });
    });

    const modalXoa = document.getElementById('modalXacNhanXoaKhach');
    const bieuMauXoa = document.getElementById('bieuMauXoaKhach');
    const nhanTenKhachXoa = document.getElementById('nhanTenKhachXoa');

    document.querySelectorAll('[data-hanh-dong="xoa-khach"]').forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maKhach = this.getAttribute('data-ma-khach');
            const hoTen = this.getAttribute('data-ho-ten');

            if (bieuMauXoa && modalXoa) {
                bieuMauXoa.action = '/khach-thue/' + maKhach;
                if (nhanTenKhachXoa) nhanTenKhachXoa.textContent = hoTen;

                modalXoa.classList.remove('modal-an');
                modalXoa.classList.add('modal-hien');
            }
        });
    });

    // MODAL CHI TIẾT KHÁCH THUÊ
    const modalChiTiet = document.getElementById('modalChiTietKhach');
    const tieuDeChiTiet = document.getElementById('chiTietTenKhach');
    const noiDungChiTiet = document.getElementById('noiDungChiTietKhach');

    document.querySelectorAll('[data-hanh-dong="xem-chi-tiet"]').forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maKhach = this.getAttribute('data-ma-khach');
            if (!modalChiTiet || !noiDungChiTiet) return;

            modalChiTiet.classList.remove('modal-an');
            modalChiTiet.classList.add('modal-hien');
            noiDungChiTiet.innerHTML = '<div class="py-12 text-center text-sm text-zinc-500">Đang tải hồ sơ khách thuê...</div>';

            fetch('/khach-thue/' + maKhach, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function (response) {
                return response.json();
            })
            .then(function (ketQua) {
                if (!ketQua.thanhCong) {
                    noiDungChiTiet.innerHTML = '<div class="py-6 text-center text-sm text-red-600">Không tìm thấy thông tin khách thuê.</div>';
                    return;
                }

                const k = ketQua.duLieu;
                if (tieuDeChiTiet) tieuDeChiTiet.textContent = k.hoTen;

                let htmlTaiKhoan = '';
                if (k.nguoi_dung) {
                    htmlTaiKhoan = '<span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Đã có TK (' + k.nguoi_dung.email + ')</span>';
                } else {
                    htmlTaiKhoan = '<span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">Chưa có tài khoản</span>';
                }

                const tenGioiTinh = k.gioiTinh === 'NU' ? 'Nữ' : (k.gioiTinh === 'KHAC' ? 'Khác' : 'Nam');
                const ngaySinhHienThi = k.ngaySinh || 'Chưa cập nhật';
                const ngayCapHienThi = k.ngayCapCCCD || 'Chưa rõ';
                const noiCapHienThi = k.noiCapCCCD || 'Cục CSQLHC về TTXH';
                const badgeTamTru = k.daDangKyTamTru
                    ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Đã đăng ký tạm trú</span>'
                    : '<span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-red-50 text-red-700 border border-red-200">Chưa đăng ký tạm trú</span>';

                let htmlHopDong = '';
                if (k.hop_dong_hieu_luc) {
                    const hd = k.hop_dong_hieu_luc;
                    const phong = hd.phong_tro ? hd.phong_tro.soPhong : 'Không rõ';
                    const giaThueDinhDang = new Intl.NumberFormat('vi-VN').format(hd.giaThueChot) + ' đ';
                    const tienCocDinhDang = new Intl.NumberFormat('vi-VN').format(hd.tienDatCoc) + ' đ';

                    const loaiPhong = hd.phong_tro && hd.phong_tro.loai_phong ? hd.phong_tro.loai_phong.tenLoaiPhong : 'Chưa phân loại';
                    const sucChua = hd.phong_tro && hd.phong_tro.soNguoiToiDa ? hd.phong_tro.soNguoiToiDa : 1;
                    const soNguoiO = hd.soLuongNguoi || 1;

                    htmlHopDong = '<div class="rounded-lg border border-sky-200 bg-sky-50/40 p-4 mb-4">' +
                        '<div class="flex items-center justify-between mb-3">' +
                        '<div>' +
                        '<div class="text-xs font-semibold text-sky-900 uppercase">Đang thuê phòng ' + phong + '</div>' +
                        '<div class="text-[11px] text-zinc-500">Loại phòng: ' + loaiPhong + '</div>' +
                        '</div>' +
                        '<span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-sky-100 text-sky-800">Hiệu lực</span>' +
                        '</div>' +
                        '<div class="grid grid-cols-2 gap-3 text-xs">' +
                        '<div><span class="text-zinc-500 block">Số người ở:</span><strong class="text-zinc-900">' + soNguoiO + ' / ' + sucChua + ' người</strong></div>' +
                        '<div><span class="text-zinc-500 block">Giá thuê chốt:</span><strong class="text-zinc-900">' + giaThueDinhDang + '/tháng</strong></div>' +
                        '<div><span class="text-zinc-500 block">Tiền đặt cọc:</span><span class="text-zinc-800">' + tienCocDinhDang + '</span></div>' +
                        '<div><span class="text-zinc-500 block">Thời gian thuê:</span><span class="text-zinc-800">' + hd.ngayBatDau + ' ~ ' + (hd.ngayKetThuc || 'Đang thuê') + '</span></div>' +
                        '</div>' +
                        '</div>';
                } else {
                    htmlHopDong = '<div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 text-center text-xs text-zinc-500 mb-4">Khách hiện không có hợp đồng thuê phòng nào đang hiệu lực.</div>';
                }

                let htmlLichSu = '';
                if (k.hop_dong && k.hop_dong.length > 0) {
                    htmlLichSu = '<div class="mt-4"><div class="text-xs font-semibold text-zinc-900 uppercase mb-2">Lịch sử các hợp đồng thuê</div>' +
                        '<div class="overflow-hidden rounded-lg border border-zinc-200">' +
                        '<table class="min-w-full divide-y divide-zinc-200 text-xs">' +
                        '<thead class="bg-zinc-50 text-zinc-500 text-left font-medium"><tr><th class="px-3 py-2">Phòng</th><th class="px-3 py-2">Thời gian</th><th class="px-3 py-2 text-right">Giá chốt</th><th class="px-3 py-2 text-right">Trạng thái</th></tr></thead>' +
                        '<tbody class="divide-y divide-zinc-200 bg-white">';
                    k.hop_dong.forEach(function (hd) {
                        const tenPhong = hd.phong_tro ? hd.phong_tro.soPhong : 'P.?';
                        const giaDinhDang = new Intl.NumberFormat('vi-VN').format(hd.giaThueChot) + ' đ';
                        const badgeHD = hd.trangThaiHD === 'HIEU_LUC'
                            ? '<span class="text-emerald-700 font-medium">Hiệu lực</span>'
                            : '<span class="text-zinc-500">Đã kết thúc</span>';
                        htmlLichSu += '<tr><td class="px-3 py-2 font-medium text-zinc-900">' + tenPhong + '</td><td class="px-3 py-2 text-zinc-600">' + hd.ngayBatDau + ' ~ ' + (hd.ngayKetThuc || 'Hiện tại') + '</td><td class="px-3 py-2 text-right text-zinc-900 font-medium">' + giaDinhDang + '</td><td class="px-3 py-2 text-right">' + badgeHD + '</td></tr>';
                    });
                    htmlLichSu += '</tbody></table></div></div>';
                }

                noiDungChiTiet.innerHTML =
                    '<div class="grid grid-cols-2 gap-3 pb-4 border-b border-zinc-200 mb-4 text-xs">' +
                    '<div><span class="text-zinc-500 block">Giới tính & Ngày sinh:</span><span class="font-semibold text-zinc-900">' + tenGioiTinh + ' - ' + ngaySinhHienThi + '</span></div>' +
                    '<div><span class="text-zinc-500 block">Số điện thoại:</span><span class="font-semibold text-zinc-900">' + k.soDienThoai + '</span></div>' +
                    '<div><span class="text-zinc-500 block">Số CCCD:</span><span class="font-semibold text-zinc-900">' + k.soCCCD + '</span></div>' +
                    '<div><span class="text-zinc-500 block">Cấp ngày & Nơi cấp:</span><span class="text-zinc-800">' + ngayCapHienThi + ' (' + noiCapHienThi + ')</span></div>' +
                    '<div><span class="text-zinc-500 block">Biển số xe máy:</span><strong class="text-zinc-900">' + (k.bienSoXe || 'Chưa đăng ký') + '</strong></div>' +
                    '<div><span class="text-zinc-500 block">Nghề nghiệp:</span><span class="text-zinc-800">' + (k.ngheNghiep || 'Chưa cập nhật') + '</span></div>' +
                    '<div class="col-span-2"><span class="text-zinc-500 block">Tình trạng tạm trú:</span>' + badgeTamTru + '</div>' +
                    (k.nguoiLienHeKhanCap ? '<div class="col-span-2 p-2 bg-zinc-50 rounded border border-zinc-200"><span class="text-zinc-500 block mb-0.5">Liên hệ khẩn cấp:</span><strong>' + k.nguoiLienHeKhanCap + '</strong> (' + (k.quanHeKhanCap || 'Người thân') + ') - SĐT: ' + (k.sdtKhanCap || 'Không có') + '</div>' : '') +
                    '<div class="col-span-2"><span class="text-zinc-500 block">Địa chỉ thường trú / Quê quán:</span><span class="text-zinc-800">' + (k.diaChi || 'Chưa cập nhật') + '</span></div>' +
                    '<div class="col-span-2 pt-1 border-t border-zinc-100"><span class="text-zinc-500 block mb-1">Tài khoản đăng nhập:</span>' + htmlTaiKhoan + '</div>' +
                    '</div>' +
                    htmlHopDong +
                    htmlLichSu;
            })
            .catch(function () {
                noiDungChiTiet.innerHTML = '<div class="py-6 text-center text-sm text-red-600">Lỗi kết nối khi tải hồ sơ khách.</div>';
            });
        });
    });

    const cacThongBao = document.querySelectorAll('.hop-thong-bao');
    cacThongBao.forEach(function (thongBao) {
        setTimeout(function () {
            thongBao.style.transition = 'opacity 0.4s ease-out';
            thongBao.style.opacity = '0';
            setTimeout(function () {
                thongBao.remove();
            }, 400);
        }, 5000);
    });
});
