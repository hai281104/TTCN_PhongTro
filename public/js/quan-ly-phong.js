document.addEventListener('DOMContentLoaded', function () {
    // Hàm định dạng tiền tệ với dấu chấm phân cách hàng nghìn
    function dinhDangTien(chuoi) {
        if (!chuoi && chuoi !== 0) return '';
        let chuoiSo = chuoi.toString().replace(/\D/g, '');
        if (!chuoiSo) return '';
        chuoiSo = chuoiSo.replace(/^0+(?=\d)/, '');
        return chuoiSo.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Khởi tạo các ô nhập tiền tệ và định dạng khi gõ
    function khoiTaoDinhDangTienTe() {
        document.querySelectorAll('.dinh-dang-tien').forEach(function (oNhap) {
            if (oNhap.dataset.daGanDinhDang) return;
            oNhap.dataset.daGanDinhDang = 'true';

            oNhap.type = 'text';
            oNhap.inputMode = 'numeric';
            oNhap.autocomplete = 'off';

            if (oNhap.value) {
                oNhap.value = dinhDangTien(oNhap.value);
            }

            oNhap.addEventListener('input', function () {
                const viTriHienTai = this.selectionStart;
                const giaTriHienTai = this.value;
                const chuSoTruoc = (giaTriHienTai.slice(0, viTriHienTai).match(/\d/g) || []).length;

                const giaTriMoi = dinhDangTien(giaTriHienTai);
                this.value = giaTriMoi;

                let chuSoDem = 0;
                let viTriMoi = 0;
                for (let i = 0; i < giaTriMoi.length; i++) {
                    if (/\d/.test(giaTriMoi[i])) {
                        chuSoDem++;
                    }
                    if (chuSoDem === chuSoTruoc) {
                        viTriMoi = i + 1;
                        break;
                    }
                }
                if (chuSoTruoc === 0) viTriMoi = 0;
                this.setSelectionRange(viTriMoi, viTriMoi);
            });
        });

        // Tự động bỏ dấu chấm trước khi submit form để gửi số nguyên chuẩn
        document.querySelectorAll('form').forEach(function (form) {
            if (form.dataset.daGanSubmitTien) return;
            form.dataset.daGanSubmitTien = 'true';

            form.addEventListener('submit', function () {
                form.querySelectorAll('.dinh-dang-tien').forEach(function (oNhap) {
                    oNhap.value = oNhap.value.replace(/\D/g, '');
                });
            });
        });
    }

    khoiTaoDinhDangTienTe();

    const modalThem = document.getElementById('modalThemPhong');
    const nutMoThem = document.getElementById('nutMoThemPhong');
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
            const oSoPhong = document.getElementById('soPhongThem');
            if (oSoPhong) oSoPhong.focus();
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

    const modalSua = document.getElementById('modalSuaPhong');
    const bieuMauSua = document.getElementById('bieuMauSuaPhong');
    const oSoPhongSua = document.getElementById('soPhongSua');
    const oDonGiaSua = document.getElementById('donGiaSua');
    const oGhiChuSua = document.getElementById('ghiChuSua');

    document.querySelectorAll('[data-hanh-dong="sua-phong"]').forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maPhong = this.getAttribute('data-ma-phong');
            const soPhong = this.getAttribute('data-so-phong');
            const donGia = this.getAttribute('data-don-gia');
            const ghiChu = this.getAttribute('data-ghi-chu') || '';

            if (bieuMauSua && modalSua) {
                bieuMauSua.action = '/phong-tro/' + maPhong;
                if (oSoPhongSua) oSoPhongSua.value = soPhong;
                if (oDonGiaSua) oDonGiaSua.value = dinhDangTien(donGia);
                if (oGhiChuSua) oGhiChuSua.value = ghiChu;

                modalSua.classList.remove('modal-an');
                modalSua.classList.add('modal-hien');
                if (oSoPhongSua) oSoPhongSua.focus();
            }
        });
    });

    const modalXoa = document.getElementById('modalXacNhanXoa');
    const bieuMauXoa = document.getElementById('bieuMauXoaPhong');
    const nhanSoPhongXoa = document.getElementById('nhanSoPhongXoa');

    document.querySelectorAll('[data-hanh-dong="xoa-phong"]').forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maPhong = this.getAttribute('data-ma-phong');
            const soPhong = this.getAttribute('data-so-phong');

            if (bieuMauXoa && modalXoa) {
                bieuMauXoa.action = '/phong-tro/' + maPhong;
                if (nhanSoPhongXoa) nhanSoPhongXoa.textContent = soPhong;

                modalXoa.classList.remove('modal-an');
                modalXoa.classList.add('modal-hien');
            }
        });
    });

    const modalChiTiet = document.getElementById('modalChiTietPhong');
    const tieuDeChiTiet = document.getElementById('chiTietSoPhong');
    const noiDungChiTiet = document.getElementById('noiDungChiTietPhong');

    document.querySelectorAll('[data-hanh-dong="xem-chi-tiet"]').forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maPhong = this.getAttribute('data-ma-phong');
            if (!modalChiTiet || !noiDungChiTiet) return;

            modalChiTiet.classList.remove('modal-an');
            modalChiTiet.classList.add('modal-hien');
            noiDungChiTiet.innerHTML = '<div class="py-12 text-center text-sm text-zinc-500">Đang tải dữ liệu phòng...</div>';

            fetch('/phong-tro/' + maPhong, {
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
                    noiDungChiTiet.innerHTML = '<div class="py-6 text-center text-sm text-red-600">Không tìm thấy thông tin phòng.</div>';
                    return;
                }

                const p = ketQua.duLieu;
                if (tieuDeChiTiet) tieuDeChiTiet.textContent = 'Phòng ' + p.soPhong;

                let badgeTrangThai = '';
                if (p.trangThaiPhong === 'TRONG') {
                    badgeTrangThai = '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Trống</span>';
                } else if (p.trangThaiPhong === 'DANG_THUE') {
                    badgeTrangThai = '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-sky-50 text-sky-700 border border-sky-200">Đang thuê</span>';
                } else {
                    badgeTrangThai = '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">Đang sửa chữa</span>';
                }

                const donGiaDinhDang = new Intl.NumberFormat('vi-VN').format(p.donGiaMacDinh) + ' đ';

                let htmlKhach = '';
                if (p.hop_dong_hieu_luc && p.hop_dong_hieu_luc.khach_thue) {
                    const k = p.hop_dong_hieu_luc.khach_thue;
                    const hd = p.hop_dong_hieu_luc;
                    const tienCocDinhDang = new Intl.NumberFormat('vi-VN').format(hd.tienDatCoc) + ' đ';
                    const giaThueChotDinhDang = new Intl.NumberFormat('vi-VN').format(hd.giaThueChot) + ' đ';

                    let danhSachNguoiO = '';
                    if (hd.nguoi_o_cung && hd.nguoi_o_cung.length > 0) {
                        danhSachNguoiO = '<div class="mt-3 pt-3 border-t border-zinc-100">' +
                            '<div class="text-xs font-medium text-zinc-500 mb-2">Danh sách người ở cùng (' + hd.nguoi_o_cung.length + ' người):</div>' +
                            '<div class="overflow-x-auto rounded-lg border border-zinc-200">' +
                            '<table class="min-w-full divide-y divide-zinc-200 text-xs">' +
                            '<thead class="bg-zinc-50 text-zinc-500 font-medium text-left"><tr><th class="px-3 py-1.5">Họ tên</th><th class="px-3 py-1.5">Quan hệ</th><th class="px-3 py-1.5 text-right">SĐT</th></tr></thead>' +
                            '<tbody class="divide-y divide-zinc-200 bg-white">';
                        hd.nguoi_o_cung.forEach(function (nguoi) {
                            danhSachNguoiO += '<tr class="hover:bg-zinc-50/50"><td class="px-3 py-1.5 font-medium text-zinc-900">' + nguoi.hoTen + '</td><td class="px-3 py-1.5 text-zinc-600">' + (nguoi.quanHe || 'Ở cùng') + '</td><td class="px-3 py-1.5 text-right tabular-nums text-zinc-700">' + (nguoi.soDienThoai || '—') + '</td></tr>';
                        });
                        danhSachNguoiO += '</tbody></table></div></div>';
                    }

                    htmlKhach = '<div class="rounded-lg border border-sky-200 bg-sky-50/40 p-4 mb-4">' +
                        '<div class="flex items-center justify-between mb-3"><div class="text-xs font-semibold text-sky-900 uppercase">Hợp đồng đang hiệu lực</div><span class="text-xs font-medium text-sky-700">Giá chốt: ' + giaThueChotDinhDang + '</span></div>' +
                        '<div class="grid grid-cols-2 gap-3 text-xs">' +
                        '<div><span class="text-zinc-500 block">Người đại diện:</span><strong class="text-zinc-900">' + k.hoTen + '</strong></div>' +
                        '<div><span class="text-zinc-500 block">Số điện thoại:</span><span class="text-zinc-800">' + k.soDienThoai + '</span></div>' +
                        '<div><span class="text-zinc-500 block">Số CCCD:</span><span class="text-zinc-800">' + k.soCCCD + '</span></div>' +
                        '<div><span class="text-zinc-500 block">Tiền đặt cọc:</span><span class="text-zinc-800">' + tienCocDinhDang + '</span></div>' +
                        '</div>' +
                        danhSachNguoiO +
                        '</div>';
                } else {
                    htmlKhach = '<div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 text-center text-xs text-zinc-500 mb-4">Hiện chưa có hợp đồng thuê nào đang hiệu lực tại phòng này.</div>';
                }

                let htmlDienNuoc = '';
                if (p.chi_so_dien_nuoc && p.chi_so_dien_nuoc.length > 0) {
                    htmlDienNuoc = '<div class="mt-4"><div class="text-xs font-semibold text-zinc-900 uppercase mb-2">Chỉ số điện nước gần nhất</div>' +
                        '<div class="overflow-hidden rounded-lg border border-zinc-200">' +
                        '<table class="min-w-full divide-y divide-zinc-200 text-xs">' +
                        '<thead class="bg-zinc-50 text-zinc-500 text-left font-medium"><tr><th class="px-3 py-2">Tháng</th><th class="px-3 py-2 text-right">Điện mới (kWh)</th><th class="px-3 py-2 text-right">Nước mới (m³)</th></tr></thead>' +
                        '<tbody class="divide-y divide-zinc-200 bg-white">';
                    p.chi_so_dien_nuoc.forEach(function (cs) {
                        htmlDienNuoc += '<tr><td class="px-3 py-2 font-medium text-zinc-900">' + cs.thangGhiNhan + '</td><td class="px-3 py-2 text-right text-zinc-700">' + cs.chiSoDienMoi + '</td><td class="px-3 py-2 text-right text-zinc-700">' + cs.chiSoNuocMoi + '</td></tr>';
                    });
                    htmlDienNuoc += '</tbody></table></div></div>';
                }

                noiDungChiTiet.innerHTML =
                    '<div class="grid grid-cols-2 gap-4 pb-4 border-b border-zinc-200 mb-4">' +
                    '<div><span class="text-xs text-zinc-500 block">Trạng thái</span>' + badgeTrangThai + '</div>' +
                    '<div class="text-right"><span class="text-xs text-zinc-500 block">Đơn giá niêm yết</span><span class="text-base font-semibold text-zinc-900">' + donGiaDinhDang + '</span></div>' +
                    '<div class="col-span-2"><span class="text-xs text-zinc-500 block">Ghi chú phòng</span><span class="text-xs text-zinc-700">' + (p.ghiChu || 'Chưa có ghi chú') + '</span></div>' +
                    '</div>' +
                    htmlKhach +
                    htmlDienNuoc;
            })
            .catch(function () {
                noiDungChiTiet.innerHTML = '<div class="py-6 text-center text-sm text-red-600">Lỗi kết nối khi tải thông tin phòng.</div>';
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

