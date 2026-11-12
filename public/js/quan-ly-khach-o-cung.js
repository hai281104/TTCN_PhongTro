/**
 * Tương tác giao diện màn hình Quản lý khách ở cùng (Bước 4)
 */
document.addEventListener('DOMContentLoaded', function () {
    const modalThem = document.getElementById('modalThemNguoiOCung');
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
    }

    function dongModal() {
        if (!modalThem) return;
        modalThem.classList.remove('modal-hien');
        modalThem.classList.add('modal-an');
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

    cacNutDong.forEach(function (nut) {
        nut.addEventListener('click', dongModal);
    });

    if (modalThem) {
        modalThem.addEventListener('click', function (suKien) {
            if (suKien.target === modalThem) {
                dongModal();
            }
        });
    }

    document.addEventListener('keydown', function (suKien) {
        if (suKien.key === 'Escape') {
            dongModal();
        }
    });
});

