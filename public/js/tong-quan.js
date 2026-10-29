/**
 * Quản lý tương tác giao diện trang Tổng quan
 */
document.addEventListener('DOMContentLoaded', function () {
    // Xử lý bật/tắt bảng chi tiết các khoản phí trong hóa đơn
    const cacNutXemChiTiet = document.querySelectorAll('[data-hanh-dong="xem-chi-tiet-hoa-don"]');

    cacNutXemChiTiet.forEach(function (nut) {
        nut.addEventListener('click', function () {
            const maHoaDon = this.getAttribute('data-ma-hoa-don');
            const phanTuChiTiet = document.getElementById('chiTietHoaDon-' + maHoaDon);

            if (phanTuChiTiet) {
                const dangMo = !phanTuChiTiet.classList.contains('hidden');
                if (dangMo) {
                    phanTuChiTiet.classList.add('hidden');
                    this.textContent = 'Xem chi tiết';
                } else {
                    phanTuChiTiet.classList.remove('hidden');
                    this.textContent = 'Thu gọn';
                }
            }
        });
    });
});
