document.addEventListener('DOMContentLoaded', function () {
    const nutAnHien = document.getElementById('nutAnHienMatKhau');
    const oMatKhau = document.getElementById('matKhau');
    const bieuTuongHien = document.getElementById('bieuTuongHien');
    const bieuTuongAn = document.getElementById('bieuTuongAn');

    if (nutAnHien && oMatKhau && bieuTuongHien && bieuTuongAn) {
        nutAnHien.addEventListener('click', function () {
            if (oMatKhau.type === 'password') {
                oMatKhau.type = 'text';
                bieuTuongHien.classList.add('hidden');
                bieuTuongAn.classList.remove('hidden');
            } else {
                oMatKhau.type = 'password';
                bieuTuongHien.classList.remove('hidden');
                bieuTuongAn.classList.add('hidden');
            }
        });
    }

    const danhSachNutDien = document.querySelectorAll('[data-email][data-mat-khau]');
    danhSachNutDien.forEach(function (nut) {
        nut.addEventListener('click', function () {
            const email = this.getAttribute('data-email');
            const matKhau = this.getAttribute('data-mat-khau');
            dienTaiKhoan(email, matKhau);
        });
    });
});

function dienTaiKhoan(email, matKhau) {
    const oEmail = document.getElementById('email');
    const oMatKhau = document.getElementById('matKhau');
    if (oEmail) oEmail.value = email;
    if (oMatKhau) oMatKhau.value = matKhau;
}
