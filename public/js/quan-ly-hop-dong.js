/**
 * Quản lý tương tác giao diện Quản lý hợp đồng
 */
document.addEventListener('DOMContentLoaded', function () {
    // 1. Quản lý Modal
    const cacNutDongModal = document.querySelectorAll('[data-dong-modal]');
    const cacModal = document.querySelectorAll('.hop-thoai-modal');

    function dongTatCaModal() {
        cacModal.forEach(function (modal) {
            modal.classList.remove('modal-hien');
            modal.classList.add('modal-an');
        });
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

    // 3. Tự động điền giá thuê và tiền cọc khi chọn phòng trống
    const selectPhong = document.getElementById('chonMaPhong');
    const inputGiaThue = document.getElementById('inputGiaThueChot');
    const inputTienCoc = document.getElementById('inputTienDatCoc');

    if (selectPhong && inputGiaThue) {
        selectPhong.addEventListener('change', function () {
            const luaChon = this.options[this.selectedIndex];
            const donGia = luaChon.getAttribute('data-don-gia');
            if (donGia) {
                inputGiaThue.value = donGia;
                if (inputTienCoc && !inputTienCoc.value) {
                    inputTienCoc.value = donGia;
                }
            }
        });
    }

    // 4. Thêm / Xóa dòng người ở cùng động trong form tạo hợp đồng
    const nutThemDongNguoi = document.querySelector('[data-hanh-dong="them-dong-nguoi-o"]');
    const khungChuaNguoiO = document.getElementById('danhSachDongNguoiO');

    if (nutThemDongNguoi && khungChuaNguoiO) {
        let chiSoDong = 0;

        nutThemDongNguoi.addEventListener('click', function () {
            chiSoDong++;
            const divDong = document.createElement('div');
            divDong.className = 'dong-nguoi-o p-3 bg-zinc-50 border border-zinc-200 rounded-lg space-y-2 relative';
            divDong.innerHTML = `
                <div class="flex items-center justify-between pb-1 border-b border-zinc-200">
                    <span class="text-xs font-semibold text-zinc-700">Người ở cùng #${chiSoDong}</span>
                    <button type="button" class="text-xs text-red-600 hover:text-red-800 font-medium nut-xoa-dong">Xóa</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    <div>
                        <label class="block text-zinc-600 mb-0.5">Họ và tên <span class="text-red-500">*</span></label>
                        <input type="text" name="nguoiOCung[${chiSoDong}][hoTen]" required placeholder="Nguyễn Văn B"
                            class="w-full px-2.5 py-1.5 bg-white border border-zinc-300 rounded text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                    </div>
                    <div>
                        <label class="block text-zinc-600 mb-0.5">Mối quan hệ</label>
                        <input type="text" name="nguoiOCung[${chiSoDong}][quanHe]" placeholder="Vợ/Chồng, Bạn..."
                            class="w-full px-2.5 py-1.5 bg-white border border-zinc-300 rounded text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                    </div>
                    <div>
                        <label class="block text-zinc-600 mb-0.5">Số CCCD (12 số)</label>
                        <input type="text" name="nguoiOCung[${chiSoDong}][soCCCD]" maxlength="12" placeholder="079..."
                            class="w-full px-2.5 py-1.5 bg-white border border-zinc-300 rounded text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                    </div>
                    <div>
                        <label class="block text-zinc-600 mb-0.5">Số điện thoại</label>
                        <input type="text" name="nguoiOCung[${chiSoDong}][soDienThoai]" placeholder="09..."
                            class="w-full px-2.5 py-1.5 bg-white border border-zinc-300 rounded text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                    </div>
                </div>
            `;

            khungChuaNguoiO.appendChild(divDong);

            divDong.querySelector('.nut-xoa-dong').addEventListener('click', function () {
                divDong.remove();
            });
        });
    }

    // 5. Mở Modal kết thúc hợp đồng
    const cacNutKetThuc = document.querySelectorAll('[data-hanh-dong="mo-modal-ket-thuc"]');
    const formKetThuc = document.getElementById('bieuMauKetThucHopDong');
    const spanSoPhongKetThuc = document.getElementById('spanSoPhongKetThuc');

    cacNutKetThuc.forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maHopDong = this.getAttribute('data-ma-hop-dong');
            const soPhong = this.getAttribute('data-so-phong');
            const tienCoc = this.getAttribute('data-tien-coc') || 0;

            if (formKetThuc) {
                formKetThuc.action = '/hop-dong/' + maHopDong + '/ket-thuc';
            }
            if (spanSoPhongKetThuc) {
                spanSoPhongKetThuc.textContent = 'phòng ' + soPhong;
            }
            const inputTienHoanCoc = document.getElementById('inputTienHoanCoc');
            if (inputTienHoanCoc) {
                inputTienHoanCoc.value = tienCoc;
            }

            moModal('modalKetThucHopDong');
        });
    });

    // 6. Mở Modal thêm người ở cùng (trong trang chi tiết)
    const nutMoThemNguoiO = document.querySelector('[data-hanh-dong="mo-modal-them-nguoi-o"]');
    if (nutMoThemNguoiO) {
        nutMoThemNguoiO.addEventListener('click', function () {
            moModal('modalThemNguoiO');
        });
    }
});
