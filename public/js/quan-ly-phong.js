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
    const modalLoaiPhong = document.getElementById('modalQuanLyLoaiPhong');
    const nutMoLoaiPhong = document.getElementById('nutMoQuanLyLoaiPhong');
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

    if (nutMoLoaiPhong && modalLoaiPhong) {
        nutMoLoaiPhong.addEventListener('click', function () {
            modalLoaiPhong.classList.remove('modal-an');
            modalLoaiPhong.classList.add('modal-hien');
            const oTenLoai = document.getElementById('tenLoaiPhongNhap');
            if (oTenLoai) oTenLoai.focus();
        });
    }

    // Xử lý chế độ Sửa loại phòng ngay trong modal Quản lý loại phòng
    const bieuMauLoaiPhong = document.getElementById('bieuMauLoaiPhong');
    const phuongThucLoaiPhong = document.getElementById('phuongThucLoaiPhong');
    const tieuDeFormLoaiPhong = document.getElementById('tieuDeFormLoaiPhong');
    const nutLuuLoaiPhong = document.getElementById('nutLuuLoaiPhong');
    const nutHuySuaLoaiPhong = document.getElementById('nutHuySuaLoaiPhong');
    const oTenLoaiNhap = document.getElementById('tenLoaiPhongNhap');
    const oDonGiaLoaiNhap = document.getElementById('donGiaGoiYNhap');
    const oDienTichLoaiNhap = document.getElementById('dienTichLoaiNhap');
    const oSoNguoiLoaiNhap = document.getElementById('soNguoiLoaiNhap');
    const oMoTaLoaiNhap = document.getElementById('moTaLoaiNhap');

    document.querySelectorAll('[data-hanh-dong="sua-loai-phong"]').forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maLoai = this.getAttribute('data-ma-loai');
            const tenLoai = this.getAttribute('data-ten-loai') || '';
            const donGia = this.getAttribute('data-don-gia') || '';
            const dienTich = this.getAttribute('data-dien-tich') || '';
            const soNguoi = this.getAttribute('data-so-nguoi') || '2';
            const moTa = this.getAttribute('data-mo-ta') || '';

            document.querySelectorAll('.dong-loai-phong').forEach(function (dong) {
                dong.classList.remove('dong-dang-sua-loai');
            });
            const dongHienTai = this.closest('.dong-loai-phong');
            if (dongHienTai) {
                dongHienTai.classList.add('dong-dang-sua-loai');
            }

            if (bieuMauLoaiPhong) {
                bieuMauLoaiPhong.action = '/phong-tro/loai-phong/' + maLoai;
                if (phuongThucLoaiPhong) phuongThucLoaiPhong.value = 'PUT';
                if (tieuDeFormLoaiPhong) tieuDeFormLoaiPhong.textContent = '✎ Đang sửa loại phòng: ' + tenLoai;
                if (nutLuuLoaiPhong) nutLuuLoaiPhong.textContent = 'Cập nhật loại phòng';
                if (nutHuySuaLoaiPhong) nutHuySuaLoaiPhong.classList.remove('hidden');

                if (oTenLoaiNhap) oTenLoaiNhap.value = tenLoai;
                if (oDonGiaLoaiNhap) oDonGiaLoaiNhap.value = dinhDangTien(donGia);
                if (oDienTichLoaiNhap) oDienTichLoaiNhap.value = dienTich;
                if (oSoNguoiLoaiNhap) oSoNguoiLoaiNhap.value = soNguoi;
                if (oMoTaLoaiNhap) oMoTaLoaiNhap.value = moTa;

                bieuMauLoaiPhong.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                if (oTenLoaiNhap) oTenLoaiNhap.focus();
            }
        });
    });

    if (nutHuySuaLoaiPhong && bieuMauLoaiPhong) {
        nutHuySuaLoaiPhong.addEventListener('click', function () {
            document.querySelectorAll('.dong-loai-phong').forEach(function (dong) {
                dong.classList.remove('dong-dang-sua-loai');
            });
            bieuMauLoaiPhong.action = '/phong-tro/loai-phong';
            if (phuongThucLoaiPhong) phuongThucLoaiPhong.value = 'POST';
            if (tieuDeFormLoaiPhong) tieuDeFormLoaiPhong.textContent = '+ Thêm loại phòng mới';
            if (nutLuuLoaiPhong) nutLuuLoaiPhong.textContent = 'Lưu loại phòng';
            nutHuySuaLoaiPhong.classList.add('hidden');

            if (oTenLoaiNhap) oTenLoaiNhap.value = '';
            if (oDonGiaLoaiNhap) oDonGiaLoaiNhap.value = '';
            if (oDienTichLoaiNhap) oDienTichLoaiNhap.value = '';
            if (oSoNguoiLoaiNhap) oSoNguoiLoaiNhap.value = '2';
            if (oMoTaLoaiNhap) oMoTaLoaiNhap.value = '';
        });
    }

    // Tự động gợi ý đơn giá, tiền cọc, diện tích và sức chứa khi chọn thể loại phòng (modal thêm)
    const chonLoaiPhongThem = document.getElementById('maLoaiPhongThem');
    const oDonGiaThem = document.getElementById('donGiaThem');
    const oTienCocThem = document.getElementById('tienCocThem');
    const oSoNguoiToiDaThem = document.getElementById('soNguoiToiDaThem');
    const oDienTichThem = document.getElementById('dienTichThem');

    if (chonLoaiPhongThem) {
        chonLoaiPhongThem.addEventListener('change', function () {
            const luaChon = this.options[this.selectedIndex];
            if (luaChon && luaChon.value) {
                const donGia = luaChon.getAttribute('data-don-gia');
                const soNguoi = luaChon.getAttribute('data-so-nguoi');
                const dienTich = luaChon.getAttribute('data-dien-tich');

                if (donGia && oDonGiaThem) {
                    oDonGiaThem.value = dinhDangTien(donGia);
                    if (oTienCocThem && !oTienCocThem.value) {
                        oTienCocThem.value = dinhDangTien(donGia);
                    }
                }
                if (soNguoi && oSoNguoiToiDaThem) {
                    oSoNguoiToiDaThem.value = soNguoi;
                }
                if (dienTich && oDienTichThem && !oDienTichThem.value) {
                    oDienTichThem.value = dienTich;
                }
            }
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

    // MODAL SỬA PHÒNG
    const modalSua = document.getElementById('modalSuaPhong');
    const bieuMauSua = document.getElementById('bieuMauSuaPhong');
    const oSoPhongSua = document.getElementById('soPhongSua');
    const oMaLoaiPhongSua = document.getElementById('maLoaiPhongSua');
    const oTangSua = document.getElementById('tangSua');
    const oDienTichSua = document.getElementById('dienTichSua');
    const oTienCocSua = document.getElementById('tienCocSua');
    const oSoNguoiToiDaSua = document.getElementById('soNguoiToiDaSua');
    const oDonGiaSua = document.getElementById('donGiaSua');
    const oGhiChuSua = document.getElementById('ghiChuSua');

    document.querySelectorAll('[data-hanh-dong="sua-phong"]').forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maPhong = this.getAttribute('data-ma-phong');
            const soPhong = this.getAttribute('data-so-phong');
            const maLoaiPhong = this.getAttribute('data-ma-loai-phong');
            const tang = this.getAttribute('data-tang') || 'Tầng 1';
            const dienTich = this.getAttribute('data-dien-tich') || '';
            const tienCoc = this.getAttribute('data-tien-coc') || '';
            const tienNghiRaw = this.getAttribute('data-tien-nghi') || '[]';
            const soNguoiToiDa = this.getAttribute('data-so-nguoi-toi-da');
            const donGia = this.getAttribute('data-don-gia');
            const ghiChu = this.getAttribute('data-ghi-chu') || '';

            if (bieuMauSua && modalSua) {
                bieuMauSua.action = '/phong-tro/' + maPhong;
                if (oSoPhongSua) oSoPhongSua.value = soPhong;
                if (oMaLoaiPhongSua && maLoaiPhong) oMaLoaiPhongSua.value = maLoaiPhong;
                if (oTangSua) oTangSua.value = tang;
                if (oDienTichSua) oDienTichSua.value = dienTich;
                if (oTienCocSua) oTienCocSua.value = dinhDangTien(tienCoc);
                if (oSoNguoiToiDaSua && soNguoiToiDa) oSoNguoiToiDaSua.value = soNguoiToiDa;
                if (oDonGiaSua) oDonGiaSua.value = dinhDangTien(donGia);
                if (oGhiChuSua) oGhiChuSua.value = ghiChu;

                // Nạp trạng thái các checkbox tiện nghi
                let danhSachTienNghiDaChon = [];
                try {
                    danhSachTienNghiDaChon = JSON.parse(tienNghiRaw);
                    if (!Array.isArray(danhSachTienNghiDaChon)) danhSachTienNghiDaChon = [];
                } catch (e) {
                    danhSachTienNghiDaChon = [];
                }

                document.querySelectorAll('.hop-tien-nghi-sua').forEach(function (cb) {
                    cb.checked = danhSachTienNghiDaChon.includes(cb.value);
                });

                modalSua.classList.remove('modal-an');
                modalSua.classList.add('modal-hien');
                if (oSoPhongSua) oSoPhongSua.focus();
            }
        });
    });

    // MODAL XÓA PHÒNG
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

    // MODAL CHI TIẾT PHÒNG
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
                const dmTienNghi = ketQua.danhSachTienNghi || {};
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
                const tienCocDinhDang = new Intl.NumberFormat('vi-VN').format(p.tienCocMacDinh || p.donGiaMacDinh) + ' đ';
                const tenLoaiPhong = (p.loai_phong && p.loai_phong.tenLoaiPhong) ? p.loai_phong.tenLoaiPhong : 'Chưa phân loại';
                const dienTichHienThi = p.dienTich ? (p.dienTich + ' m²') : ((p.loai_phong && p.loai_phong.dienTich) ? (p.loai_phong.dienTich + ' m²') : '—');
                const sucChua = (p.soNguoiToiDa || 2) + ' người';
                const viTriTang = p.tang || 'Tầng 1';

                // Render danh sách tiện nghi
                let htmlTienNghi = '';
                let mangTienNghi = [];
                if (Array.isArray(p.tienNghi)) {
                    mangTienNghi = p.tienNghi;
                } else if (typeof p.tienNghi === 'string') {
                    try { mangTienNghi = JSON.parse(p.tienNghi); } catch (e) { mangTienNghi = []; }
                }

                if (mangTienNghi && mangTienNghi.length > 0) {
                    htmlTienNghi = '<div class="pt-3 border-t border-zinc-200/80"><span class="text-zinc-500 block mb-1.5 font-medium">Tiện nghi nội thất trang bị sẵn:</span><div class="flex flex-wrap gap-1.5">';
                    mangTienNghi.forEach(function (tnKey) {
                        const item = dmTienNghi[tnKey];
                        if (item) {
                            htmlTienNghi += '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-[11px] bg-white text-zinc-800 border border-zinc-200 font-medium"><span>' + item.ten + '</span></span>';
                        }
                    });
                    htmlTienNghi += '</div></div>';
                } else {
                    htmlTienNghi = '<div class="pt-3 border-t border-zinc-200/80"><span class="text-zinc-500 block mb-1 font-medium">Tiện nghi nội thất:</span><span class="text-zinc-400 italic">Phòng cơ bản (chưa gắn tiện nghi)</span></div>';
                }

                let htmlKhach = '';
                if (p.hop_dong_hieu_luc && p.hop_dong_hieu_luc.khach_thue) {
                    const k = p.hop_dong_hieu_luc.khach_thue;
                    const hd = p.hop_dong_hieu_luc;
                    const tienCocHD = new Intl.NumberFormat('vi-VN').format(hd.tienDatCoc) + ' đ';
                    const giaThueChotDinhDang = new Intl.NumberFormat('vi-VN').format(hd.giaThueChot) + ' đ';
                    const soLuongNguoiO = (hd.soLuongNguoi || 1) + ' người';

                    htmlKhach = '<div class="rounded-xl border border-sky-200 bg-sky-50/40 p-4">' +
                        '<div class="flex items-center justify-between mb-3 pb-2 border-b border-sky-200/60"><div class="text-xs font-semibold text-sky-900 uppercase">Hợp đồng đang hiệu lực (#' + hd.maHopDong + ')</div><span class="text-xs font-semibold text-sky-700">Giá chốt: ' + giaThueChotDinhDang + '/tháng</span></div>' +
                        '<div class="grid grid-cols-2 gap-3 text-xs">' +
                        '<div><span class="text-zinc-500 block">Người đại diện ký hợp đồng:</span><strong class="text-zinc-900">' + k.hoTen + '</strong></div>' +
                        '<div><span class="text-zinc-500 block">Số điện thoại liên hệ:</span><strong class="text-zinc-800 tabular-nums">' + k.soDienThoai + '</strong></div>' +
                        '<div><span class="text-zinc-500 block">Số người đang ở:</span><strong class="text-sky-700">' + soLuongNguoiO + '</strong> <span class="text-zinc-500">(Tối đa ' + sucChua + ')</span></div>' +
                        '<div><span class="text-zinc-500 block">Tiền đặt cọc hợp đồng:</span><strong class="text-zinc-900 tabular-nums">' + tienCocHD + '</strong></div>' +
                        '</div>' +
                        '</div>';
                } else {
                    htmlKhach = '<div class="rounded-xl border border-zinc-200 bg-zinc-50 p-5 text-center text-xs text-zinc-500">Hiện chưa có hợp đồng thuê nào đang hiệu lực tại phòng này.</div>';
                }

                let htmlDienNuoc = '';
                if (p.chi_so_dien_nuoc && p.chi_so_dien_nuoc.length > 0) {
                    htmlDienNuoc = '<div><div class="text-xs font-semibold text-zinc-900 uppercase mb-2">Chỉ số điện nước gần nhất</div>' +
                        '<div class="overflow-hidden rounded-xl border border-zinc-200">' +
                        '<table class="min-w-full divide-y divide-zinc-200 text-xs">' +
                        '<thead class="bg-zinc-50 text-zinc-500 text-left font-medium"><tr><th class="px-3 py-2">Tháng</th><th class="px-3 py-2 text-right">Điện mới (kWh)</th><th class="px-3 py-2 text-right">Nước mới (m³)</th></tr></thead>' +
                        '<tbody class="divide-y divide-zinc-200 bg-white">';
                    p.chi_so_dien_nuoc.forEach(function (cs) {
                        htmlDienNuoc += '<tr><td class="px-3 py-2 font-medium text-zinc-900">' + cs.thangGhiNhan + '</td><td class="px-3 py-2 text-right text-zinc-700 tabular-nums">' + cs.chiSoDienMoi + '</td><td class="px-3 py-2 text-right text-zinc-700 tabular-nums">' + cs.chiSoNuocMoi + '</td></tr>';
                    });
                    htmlDienNuoc += '</tbody></table></div></div>';
                } else {
                    htmlDienNuoc = '<div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 text-center text-xs text-zinc-400 italic">Chưa có bản ghi chỉ số điện nước nào.</div>';
                }

                noiDungChiTiet.innerHTML =
                    '<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 text-xs">' +
                        '<div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-3.5">' +
                            '<div class="flex items-center justify-between pb-2.5 border-b border-zinc-200">' +
                                '<span class="font-semibold text-zinc-800 uppercase tracking-wider text-[11px]">1. Thông tin phòng & Đơn giá</span>' +
                                badgeTrangThai +
                            '</div>' +
                            '<div class="grid grid-cols-3 gap-3">' +
                                '<div class="bg-white p-2.5 rounded-lg border border-zinc-200"><span class="text-zinc-500 block text-[11px]">Thể loại phòng</span><strong class="text-zinc-900">' + tenLoaiPhong + '</strong></div>' +
                                '<div class="bg-white p-2.5 rounded-lg border border-zinc-200"><span class="text-zinc-500 block text-[11px]">Vị trí & Diện tích</span><strong class="text-zinc-900">' + viTriTang + ' (' + dienTichHienThi + ')</strong></div>' +
                                '<div class="bg-white p-2.5 rounded-lg border border-zinc-200"><span class="text-zinc-500 block text-[11px]">Sức chứa tối đa</span><strong class="text-zinc-900">' + sucChua + '</strong></div>' +
                            '</div>' +
                            '<div class="grid grid-cols-2 gap-3">' +
                                '<div class="bg-white p-2.5 rounded-lg border border-zinc-200"><span class="text-zinc-500 block text-[11px]">Đơn giá niêm yết</span><strong class="text-sm text-zinc-900 tabular-nums">' + donGiaDinhDang + '</strong></div>' +
                                '<div class="bg-white p-2.5 rounded-lg border border-zinc-200"><span class="text-zinc-500 block text-[11px]">Tiền cọc mặc định</span><strong class="text-sm text-zinc-900 tabular-nums">' + tienCocDinhDang + '</strong></div>' +
                            '</div>' +
                            htmlTienNghi +
                            '<div class="pt-2.5 border-t border-zinc-200/80"><span class="text-zinc-500 block mb-0.5 font-medium">Ghi chú đặc điểm phòng:</span><span class="text-zinc-700">' + (p.ghiChu || 'Chưa có ghi chú') + '</span></div>' +
                        '</div>' +
                        '<div class="space-y-4 flex flex-col justify-between">' +
                            htmlKhach +
                            htmlDienNuoc +
                        '</div>' +
                    '</div>';
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
