/**
 * Tương tác giao diện màn hình Quản lý khách ở cùng
 */
document.addEventListener('DOMContentLoaded', function () {
    const modalThem = document.getElementById('modalThemNguoiOCung');
    const modalSua = document.getElementById('modalSuaNguoiOCung');
    const selectHopDong = document.getElementById('maHopDongOCung');
    const nutMoModal = document.getElementById('nutMoModalThemOCung');
    const cacNutThemTheoHopDong = document.querySelectorAll('[data-them-o-cung-hop-dong]');
    const cacNutDong = document.querySelectorAll('[data-dong-modal]');

    function moModal(maHopDongMacDinh) {
        if (!modalThem) return;
        if (selectHopDong && maHopDongMacDinh) {
            selectHopDong.value = maHopDongMacDinh;
        }
        modalThem.classList.remove('modal-an');
        modalThem.classList.add('modal-hien');
        const oHoTen = document.getElementById('hoTenOCungThem');
        if (oHoTen) oHoTen.focus();
    }

    function dongTatCaModal() {
        document.querySelectorAll('.hop-thoai-modal').forEach(function (modal) {
            modal.classList.remove('modal-hien');
            modal.classList.add('modal-an');
        });
    }

    if (nutMoModal) {
        nutMoModal.addEventListener('click', function () {
            moModal('');
        });
    }

    cacNutThemTheoHopDong.forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maHopDong = this.getAttribute('data-them-o-cung-hop-dong');
            moModal(maHopDong);
        });
    });

    // Mở modal sửa thông tin khách ở cùng
    const bieuMauSua = document.getElementById('bieuMauSuaOCung');
    const oHoTenSua = document.getElementById('hoTenOCungSua');
    const oSdtSua = document.getElementById('soDienThoaiOCungSua');
    const oCccdSua = document.getElementById('soCCCDOCungSua');
    const oQuanHeSua = document.getElementById('quanHeOCungSua');
    const oNgayVaoSua = document.getElementById('ngayVaoOOCungSua');

    document.querySelectorAll('[data-hanh-dong="sua-o-cung"]').forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maOCung = this.getAttribute('data-ma-o-cung');
            const hoTen = this.getAttribute('data-ho-ten') || '';
            const sdt = this.getAttribute('data-sdt') || '';
            const cccd = this.getAttribute('data-cccd') || '';
            const quanHe = this.getAttribute('data-quan-he') || '';
            const ngayVao = this.getAttribute('data-ngay-vao') || '';

            if (bieuMauSua && modalSua) {
                bieuMauSua.action = '/khach-o-cung/' + maOCung;
                if (oHoTenSua) oHoTenSua.value = hoTen;
                if (oSdtSua) oSdtSua.value = sdt;
                if (oCccdSua) oCccdSua.value = cccd;
                if (oQuanHeSua) oQuanHeSua.value = quanHe;
                if (oNgayVaoSua) oNgayVaoSua.value = ngayVao;

                modalSua.classList.remove('modal-an');
                modalSua.classList.add('modal-hien');
                if (oHoTenSua) oHoTenSua.focus();
            }
        });
    });

    cacNutDong.forEach(function (nut) {
        nut.addEventListener('click', dongTatCaModal);
    });

    document.querySelectorAll('.hop-thoai-modal').forEach(function (modal) {
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
});
