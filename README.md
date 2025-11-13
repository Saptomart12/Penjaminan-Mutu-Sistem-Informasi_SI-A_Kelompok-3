# Sistem Informasi Manajemen Kinerja Akademik Personal (SIM_KAP)

## 📜 Deskripsi Singkat

*SIM_KAP* adalah aplikasi web yang dirancang untuk membantu mahasiswa memantau, mengelola, dan mengevaluasi kinerja akademik mereka secara sistematis. Aplikasi ini berfungsi sebagai asisten pribadi untuk mencatat tugas, melacak nilai, menghitung IP semester, dan memberikan evaluasi agar mahasiswa dapat meningkatkan prestasi di semester berikutnya.

---

## ✨ Fitur Utama

Berikut adalah fitur-fitur utama yang ditawarkan oleh SIM_KAP:

| Fitur | Deskripsi |
| :--- | :--- |
| 👤 *Login Pengguna* | Mahasiswa masuk menggunakan email/NIM dan kata sandi yang terverifikasi. |
| 📅 *Manajemen Semester* | Membuat, mengedit, dan menghapus data semester (Nomor Semester, Status Aktif/Selesai, IP Final). |
| 📚 *Manajemen Mata Kuliah*| Menambah, mengubah, atau menghapus mata kuliah per semester (Nama MK, Kode MK, SKS). |
| ✍ *Manajemen Tugas* | Menambah tugas baru per mata kuliah, mengatur tenggat waktu, deskripsi, dan menandai status penyelesaian (Selesai/Belum Selesai/Dinilai). |
| 💯 *Input Nilai* | Memasukkan nilai untuk setiap tugas, UTS, dan UAS yang disimpan untuk analisis. |
| 📊 *Perhitungan & Evaluasi* | Otomatis menghitung IP semester berdasarkan nilai yang dimasukkan. Menampilkan evaluasi kinerja dan distribusi nilai tiap semester. |
| 📄 *Laporan Kinerja* | Menampilkan riwayat IP per semester dalam bentuk tabel dan grafik. Laporan dapat diunduh dalam format PDF. |
| 🚪 *Logout* | Keluar dari sistem dengan aman, mengakhiri sesi pengguna. |

---

## 🖼 Tampilan Aplikasi
---
## Login
![Tampilan Login SIM_KAP](https://github.com/Saptomart12/Penjaminan-Mutu-Sistem-Informasi_SI-A_Kelompok-3/blob/c37eca88038019437c22fc587132a2116a2c2d36/public/assets/images/tampilan-login.png)
---
## Register
![Tampilan Register SIM_KAP](https://github.com/Saptomart12/Penjaminan-Mutu-Sistem-Informasi_SI-A_Kelompok-3/blob/edca3cca40ad140338061540e08dac5487ba5e87/public/assets/images/tampilan-register.png)

## 💻 Teknologi yang Digunakan

* *Backend:* PHP, Laravel Framework
* *Frontend:* HTML, CSS, JavaScript, Bootstrap 5
* *Template Admin:* SB Admin 2
* *Database:* MySQL
* *Charting:* Chart.js

---

## 🚀 Cara Instalasi & Setup

Berikut langkah-langkah untuk menjalankan proyek ini di komputer lokal:

1.  *Clone Repositori:*
    bash
    git clone [https://github.com/Saptomart12/Penjaminan-Mutu-Sistem-Informasi-Kelompok-Kelompok-3-SIM_KAP.git](https://github.com/Saptomart12/Penjaminan-Mutu-Sistem-Informasi-Kelompok-Kelompok-3-SIM_KAP.git)
    cd Penjaminan-Mutu-Sistem-Informasi-Kelompok-Kelompok-3-SIM_KAP
    

2.  *Install Dependencies:*
    Pastikan Anda sudah menginstal Composer dan Node.js/NPM.
    bash
    composer install
    npm install 
    npm run build 
    
    *(Jika Anda tidak menggunakan Vite/NPM untuk aset frontend, lewati npm install dan npm run build)*

3.  *Setup Environment File:*
    Salin file .env.example menjadi .env.
    bash
    cp .env.example .env
    
    Buka file .env dan konfigurasikan koneksi database Anda (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

4.  *Generate Application Key:*
    bash
    php artisan key:generate
    

5.  *Jalankan Migrasi Database:*
    Perintah ini akan membuat tabel-tabel yang diperlukan di database Anda.
    bash
    php artisan migrate
    
    (Opsional: Jika Anda punya data *seeder, jalankan php artisan db:seed)*

6.  *Jalankan Development Server:*
    bash
    php artisan serve
    

7.  *Buka Aplikasi:*
    Buka browser Anda dan kunjungi alamat yang ditampilkan (biasanya http://127.0.0.1:8000).

---

## 👥 Anggota Kelompok

Proyek ini dikembangkan oleh:

| NIM | Nama |
| :--- | :--- |
| F52123012 | Gilang Aldiansyah |
| F52123016 | Salsabila Ramadhani Zen |
| F52123017 | Zharnativa Al Adiyah Nurba |
| F52123019 | Panji Angga Saputra |
| F52123028 | Sapto Mart Saputra Wicaksono |

---
