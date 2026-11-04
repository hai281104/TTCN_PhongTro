/**
 * Quản lý tương tác giao diện Quản lý hợp đồng
 */
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

    // Hàm loại bỏ dấu tiếng Việt để tìm kiếm không dấu
    function loaiBoDauTiengViet(chuoi) {
        if (!chuoi) return '';
        return chuoi.normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd')
            .replace(/Đ/g, 'D')
            .toLowerCase();
    }

    // 1. Quản lý Modal
    const cacNutDongModal = document.querySelectorAll('[data-dong-modal]');
    const cacModal = document.querySelectorAll('.hop-thoai-modal');

    function dongTatCaModal() {
        cacModal.forEach(function (modal) {
            modal.classList.remove('modal-hien');
            modal.classList.add('modal-an');
        });
        dongTatCaHopChon();
    }

    function moModal(idModal) {
        const modal = document.getElementById(idModal);
        if (modal) {
            modal.classList.remove('modal-an');
            modal.classList.add('modal-hien');
        }
    }

    cacNutDongModal.forEach(function (nut) {
        nut.addEventListener('click', dongTatCaModal);
    });

    cacModal.forEach(function (modal) {
        modal.addEventListener('click', function (suKien) {
            if (suKien.target === modal) {
                dongTatCaModal();
            }
        });
    });

    document.addEventListener('keydown', function (suKien) {
        if (suKien.key === 'Escape') {
            dongTatCaModal();
        }
    });

    // 2. Mở Modal tạo hợp đồng
    const nutMoModalTao = document.querySelector('[data-hanh-dong="mo-modal-tao-hop-dong"]');
    if (nutMoModalTao) {
        nutMoModalTao.addEventListener('click', function () {
            moModal('modalTaoHopDong');
        });
    }

    // 3. Xử lý các Hộp chọn có tìm kiếm (Searchable Dropdown)
    const cacHopChon = document.querySelectorAll('.hop-chon-tim-kiem');
    const inputGiaThue = document.getElementById('inputGiaThueChot');
    const inputTienCoc = document.getElementById('inputTienDatCoc');
    const inputSoLuongNguoi = document.getElementById('soLuongNguoi');
    const nhanSucChuaPhong = document.getElementById('nhanSucChuaPhong');

    function dongTatCaHopChon() {
        cacHopChon.forEach(function (hop) {
            const bang = hop.querySelector('.bang-lua-chon');
            if (bang) bang.classList.add('hidden');
        });
    }

    cacHopChon.forEach(function (hop) {
        const nutKichHoat = hop.querySelector('.nut-kich-hoat-chon');
        const bangLuaChon = hop.querySelector('.bang-lua-chon');
        const oNhapTimKiem = hop.querySelector('.o-nhap-tim-kiem');
        const danhSachMuc = hop.querySelectorAll('.muc-lua-chon');
        const thongBaoKhongThay = hop.querySelector('.thong-bao-khong-tim-thay');
        const hiddenInput = hop.querySelector('input[type="hidden"]');
        const nhanHienThi = hop.querySelector('.nhan-hien-thi');

        // Bật / tắt bảng chọn khi click nút
        if (nutKichHoat && bangLuaChon) {
            nutKichHoat.addEventListener('click', function (suKien) {
                suKien.stopPropagation();
                const dangMo = !bangLuaChon.classList.contains('hidden');
                dongTatCaHopChon();

                if (!dangMo) {
                    bangLuaChon.classList.remove('hidden');
                    if (oNhapTimKiem) {
                        oNhapTimKiem.value = '';
                        danhSachMuc.forEach(m => m.classList.remove('hidden'));
                        if (thongBaoKhongThay) thongBaoKhongThay.classList.add('hidden');
                        setTimeout(() => oNhapTimKiem.focus(), 50);
                    }
                }
            });
        }

        // Tìm kiếm thời gian thực
        if (oNhapTimKiem) {
            oNhapTimKiem.addEventListener('input', function () {
                const tuKhoa = loaiBoDauTiengViet(this.value.trim());
                let soLuongKhop = 0;

                danhSachMuc.forEach(function (muc) {
                    const duLieuTim = loaiBoDauTiengViet(muc.getAttribute('data-tim-kiem') || '');
                    if (duLieuTim.includes(tuKhoa)) {
                        muc.classList.remove('hidden');
                        soLuongKhop++;
                    } else {
                        muc.classList.add('hidden');
                    }
                });

                if (thongBaoKhongThay) {
                    if (soLuongKhop === 0) {
                        thongBaoKhongThay.classList.remove('hidden');
                    } else {
                        thongBaoKhongThay.classList.add('hidden');
                    }
                }
            });

            oNhapTimKiem.addEventListener('click', function (suKien) {
                suKien.stopPropagation();
            });
        }

        // Chọn một mục
        danhSachMuc.forEach(function (muc) {
            muc.addEventListener('click', function (suKien) {
                suKien.stopPropagation();
                const giaTri = this.getAttribute('data-gia-tri');
                const nhan = this.getAttribute('data-nhan');
                const donGia = this.getAttribute('data-don-gia');
                const soNguoiToiDa = this.getAttribute('data-so-nguoi-toi-da');

                if (hiddenInput) {
                    hiddenInput.value = giaTri;
                }

                if (nhanHienThi) {
                    nhanHienThi.textContent = nhan;
                    nhanHienThi.classList.remove('text-zinc-500');
                    nhanHienThi.classList.add('text-zinc-900', 'font-medium');
                }

                danhSachMuc.forEach(m => m.classList.remove('dang-chon'));
                this.classList.add('dang-chon');

                // Nếu là hộp chọn phòng: cập nhật đơn giá, tiền cọc và sức chứa tối đa
                if (donGia && inputGiaThue) {
                    inputGiaThue.value = dinhDangTien(donGia);
                    if (inputTienCoc && (!inputTienCoc.value || inputTienCoc.value === '0')) {
                        inputTienCoc.value = dinhDangTien(donGia);
                    }
                }

                if (soNguoiToiDa && inputSoLuongNguoi) {
                    inputSoLuongNguoi.max = soNguoiToiDa;
                    if (nhanSucChuaPhong) {
                        nhanSucChuaPhong.textContent = '/ tối đa ' + soNguoiToiDa + ' người';
                    }
                    if (parseInt(inputSoLuongNguoi.value || 0) > parseInt(soNguoiToiDa)) {
                        inputSoLuongNguoi.value = soNguoiToiDa;
                    }
                }

                bangLuaChon.classList.add('hidden');
            });
        });
    });

    document.addEventListener('click', function (suKien) {
        if (!suKien.target.closest('.hop-chon-tim-kiem')) {
            dongTatCaHopChon();
        }
    });

    // 4. Mở Modal kết thúc / thanh lý hợp đồng
    const cacNutKetThuc = document.querySelectorAll('[data-hanh-dong="mo-modal-ket-thuc"]');
    const formKetThuc = document.getElementById('bieuMauKetThucHopDong');
    const nhanSoPhongKetThuc = document.getElementById('nhanSoPhongKetThuc');
    const nhanTienCocGoc = document.getElementById('nhanTienCocGoc');

    cacNutKetThuc.forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maHopDong = this.getAttribute('data-ma-hop-dong');
            const soPhong = this.getAttribute('data-so-phong');
            const tienCoc = this.getAttribute('data-tien-coc') || 0;

            if (formKetThuc) {
                formKetThuc.action = '/hop-dong/' + maHopDong + '/ket-thuc';
            }
            if (nhanSoPhongKetThuc) {
                nhanSoPhongKetThuc.textContent = 'Phòng ' + soPhong;
            }
            if (nhanTienCocGoc) {
                nhanTienCocGoc.textContent = dinhDangTien(tienCoc) + ' đ';
            }
            const inputTienHoanCoc = document.getElementById('inputTienHoanCoc') || document.getElementById('tienHoanCoc');
            if (inputTienHoanCoc) {
                inputTienHoanCoc.value = dinhDangTien(tienCoc);
            }

            moModal('modalKetThucHopDong');
        });
    });
});
