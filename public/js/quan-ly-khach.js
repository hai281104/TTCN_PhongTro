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

    const modalSua = document.getElementById('modalSuaKhach');
    const bieuMauSua = document.getElementById('bieuMauSuaKhach');
    const oHoTenSua = document.getElementById('hoTenSua');
    const oCCCDSua = document.getElementById('soCCCDSua');
    const oDienThoaiSua = document.getElementById('soDienThoaiSua');
    const oDiaChiSua = document.getElementById('diaChiSua');

    document.querySelectorAll('[data-hanh-dong="sua-khach"]').forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maKhach = this.getAttribute('data-ma-khach');
            const hoTen = this.getAttribute('data-ho-ten');
            const soCCCD = this.getAttribute('data-so-cccd');
            const soDienThoai = this.getAttribute('data-so-dien-thoai');
            const diaChi = this.getAttribute('data-dia-chi') || '';

            if (bieuMauSua && modalSua) {
                bieuMauSua.action = '/khach-thue/' + maKhach;
                if (oHoTenSua) oHoTenSua.value = hoTen;
                if (oCCCDSua) oCCCDSua.value = soCCCD;
                if (oDienThoaiSua) oDienThoaiSua.value = soDienThoai;
                if (oDiaChiSua) oDiaChiSua.value = diaChi;

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

                let htmlHopDong = '';
                if (k.hop_dong_hieu_luc) {
                    const hd = k.hop_dong_hieu_luc;
                    const phong = hd.phong_tro ? hd.phong_tro.soPhong : 'Không rõ';
                    const giaThueDinhDang = new Intl.NumberFormat('vi-VN').format(hd.giaThueChot) + ' đ';
                    const tienCocDinhDang = new Intl.NumberFormat('vi-VN').format(hd.tienDatCoc) + ' đ';

                    let danhSachNguoiO = '';
                    if (hd.nguoi_o_cung && hd.nguoi_o_cung.length > 0) {
                        danhSachNguoiO = '<div class="mt-3 pt-3 border-t border-sky-100"><span class="text-xs font-semibold text-sky-900 block mb-1.5">Người ở cùng (' + hd.nguoi_o_cung.length + ' người):</span><div class="space-y-1.5">';
                        hd.nguoi_o_cung.forEach(function (nguoi) {
                            danhSachNguoiO += '<div class="flex items-center justify-between bg-white p-2 rounded border border-sky-200 text-xs"><span>' + nguoi.hoTen + ' (' + (nguoi.quanHe || 'Ở cùng') + ')</span><span class="text-zinc-500">' + (nguoi.soDienThoai || '') + '</span></div>';
                        });
                        danhSachNguoiO += '</div></div>';
                    }

                    htmlHopDong = '<div class="rounded-lg border border-sky-200 bg-sky-50/40 p-4 mb-4">' +
                        '<div class="flex items-center justify-between mb-3"><div class="text-xs font-semibold text-sky-900 uppercase">Đang thuê phòng ' + phong + '</div><span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-sky-100 text-sky-800">Hiệu lực</span></div>' +
                        '<div class="grid grid-cols-2 gap-3 text-xs">' +
                        '<div><span class="text-zinc-500 block">Giá thuê chốt:</span><strong class="text-zinc-900">' + giaThueDinhDang + '/tháng</strong></div>' +
                        '<div><span class="text-zinc-500 block">Tiền đặt cọc:</span><span class="text-zinc-800">' + tienCocDinhDang + '</span></div>' +
                        '<div><span class="text-zinc-500 block">Ngày bắt đầu:</span><span class="text-zinc-800">' + hd.ngayBatDau + '</span></div>' +
                        '<div><span class="text-zinc-500 block">Ngày kết thúc:</span><span class="text-zinc-800">' + (hd.ngayKetThuc || 'Đang thuê') + '</span></div>' +
                        '</div>' +
                        danhSachNguoiO +
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
                    '<div><span class="text-zinc-500 block">Số CCCD:</span><span class="font-semibold text-zinc-900">' + k.soCCCD + '</span></div>' +
                    '<div><span class="text-zinc-500 block">Số điện thoại:</span><span class="font-semibold text-zinc-900">' + k.soDienThoai + '</span></div>' +
                    '<div class="col-span-2"><span class="text-zinc-500 block">Địa chỉ thường trú:</span><span class="text-zinc-800">' + (k.diaChi || 'Chưa cập nhật') + '</span></div>' +
                    '<div class="col-span-2 pt-1"><span class="text-zinc-500 block mb-1">Tài khoản đăng nhập:</span>' + htmlTaiKhoan + '</div>' +
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
