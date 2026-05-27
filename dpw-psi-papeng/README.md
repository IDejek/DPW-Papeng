DPW PSI Papua Pegunungan — WordPress Theme & Plugin
Informasi Sistem
Theme: DPW PSI Papua Pegunungan (dpw-psi-papeng)
Plugin: PSI Papeng Premium (psi-papeng-premium)
Developer: Iqbal Tombinawa (tombinawaiqbal@gmail.com)
Domain: https://psipapeng.id
Versi: 1.0.0
PHP Minimum: 8.0
WordPress Minimum: 6.0
Instalasi
1. Upload Theme
Buka folder /wp-content/themes/
Buat folder dpw-psi-papeng
Salin semua file theme ke dalam folder tersebut
Atau, kompres folder menjadi dpw-psi-papeng.zip
Di WordPress Admin → Appearance → Themes → Add New → Upload Theme
Pilih file ZIP, lalu klik Install Now
Klik Activate
2. Upload Plugin
Buka folder /wp-content/plugins/
Buat folder psi-papeng-premium
Salin semua file plugin ke dalam folder tersebut
Atau, kompres folder menjadi psi-papeng-premium.zip
Di WordPress Admin → Plugins → Add New → Upload Plugin
Pilih file ZIP, lalu klik Install Now
Klik Activate
Catatan: Tabel database akan otomatis dibuat saat aktivasi plugin.

3. Konfigurasi Menu
Buka Appearance → Menus
Buat menu baru:
Menu Name: Menu Utama
Display Location: Primary Menu
Tambahkan halaman:
Beranda
Profil (sebagai parent)
Sejarah PSI
Visi & Misi
Struktur Organisasi
DPD Kabupaten
Berita
Galeri
Video
Kontak
Buat menu kedua:
Menu Name: Menu Mobile
Display Location: Mobile Menu
Isi sama dengan Menu Utama
Buat menu ketiga:
Menu Name: Menu Footer
Display Location: Footer Menu
4. Konfigurasi Homepage
Buka Settings → Reading
Pilih A static page
Homepage: pilih "Home" (atau buat halaman baru berjudul "Home")
Posts page: pilih "Berita" (atau buat halaman baru)
Simpan
5. Konfigurasi Topbar & Jam
Buka Appearance → Customize → Topbar & Jam
Aktifkan/nonaktifkan topbar
Edit teks topbar
Jam WIT (Papua) dan WIB (Jakarta) akan tampil otomatis
6. Kelola Slider
Buka menu Slider di sidebar admin
Klik Add New
Isi:
Title: Judul slide (muncul di hero)
Subtitle: Teks tambahan
Featured Image: Gambar slide (rekomendasi 1920×800px)
Teks Tombol: Contoh "Selengkapnya"
URL Tombol: Link tujuan
Urutan: Angka untuk mengurutkan slide
Publish
7. Kelola Pimpinan
Buka menu Pimpinan di sidebar admin
Klik Add New
Isi:
Title: Nama lengkap (contoh: Yotam Wonda, S.H., M.Si)
Content: Biografi lengkap
Featured Image: Foto (rekomendasi 400×500px)
Jabatan: Ketua DPW / Sekretaris / Bendahara
Tampilkan di halaman utama: Centang untuk pimpinan utama
Social Links: Facebook, Instagram, Twitter/X
Publish
Data pimpinan utama yang direkomendasikan:

Yotam Wonda, S.H., M.Si — Ketua DPW
Yotias Kobak, S.Sos — Sekretaris
Almina Wakur, S.IP — Bendahara
8. Kelola Bidang Organisasi
Buka menu Bidang Organisasi
Tambahkan 8 bidang:
Hubungan Antar Lembaga Hukum dan HAM
UMKM, Koperasi dan Kepariwisataan
Media, Teknologi dan Informatika
Pemuda, Olahraga, Seni & Budaya
Buruh, Petani, Nelayan dan SDA
Kesehatan dan Lingkungan Hidup
Keagamaan
Perempuan dan Anak
Setiap bidang: isi nama Ketua Bidang dan deskripsi singkat
9. Kelola DPD Kabupaten
Buka menu DPD Kabupaten
Tambahkan 8 kabupaten:
Kabupaten Jayawijaya
Kabupaten Pegunungan Bintang
Kabupaten Tolikara
Kabupaten Yahukimo
Kabupaten Yalimo
Kabupaten Mamberamo Tengah
Kabupaten Nduga
Kabupaten Lanny Jaya
Setiap DPD: isi Nama Kabupaten, Nama Ketua DPD, Foto, Alamat, dll
10. Kelola Berita
Buka Posts → Add New
Buat kategori: "Berita", "Aspirasi Masyarakat"
Isi berita seperti biasa
Set featured image (rekomendasi 600×400px)
11. Kelola Video
Buka menu Video
Klik Add New
Isi judul dan deskripsi
Di Detail Video: masukkan URL YouTube
Centang "Tampilkan sebagai video unggulan" jika diinginkan
Set thumbnail atau akan otomatis dari YouTube
12. Kelola Galeri
Buat kategori: Buka Galeri → Kategori Galeri
Tambahkan kategori (contoh: "Kegiatan", "Kunjungan")
Buka Galeri → Add New
Set featured image
Pilih kategori
13. Kelola Anggota
Buka PSI Papeng → Anggota
Lihat daftar anggota yang mendaftar
Klik tombol ✓ untuk verifikasi
Klik tombol ✗ untuk menolak
Klik tombol 🗑 untuk menghapus
Gunakan filter untuk mencari berdasarkan nama/email/status/kabupaten
Klik Ekspor CSV untuk mengunduh data
14. Konfigurasi SMTP
Buka PSI Papeng → SMTP
Isi pengaturan:
SMTP Host (contoh: smtp.gmail.com)
SMTP Port (contoh: 587)
Enkripsi (TLS/SSL)
Username SMTP
Password SMTP
Email Pengirim
Nama Pengirim
Klik Simpan Pengaturan
Kirim email tes untuk memastikan berfungsi
15. Konfigurasi WhatsApp
Buka Appearance → Customize → Informasi Kontak
Isi Nomor WhatsApp (tanpa +, contoh: 6282267218125)
Tombol floating WhatsApp akan otomatis tampil di frontend
16. Konfigurasi Kontak
Buka Appearance → Customize → Informasi Kontak
Isi telepon, email, alamat
Untuk Google Maps: buka Google Maps → Share → Embed → salin kode iframe
Tempel kode di field Google Maps Embed Code
Buat halaman "Kontak", pilih template "Halaman Kontak"
Tempel shortcode [psi_contact_form] di konten halaman jika perlu
17. Konfigurasi SEO
Sitemap: Otomatis di https://psipapeng.id/sitemap.xml
robots.txt: Otomatis dioptimasi
Schema Markup: Otomatis untuk Organization
Open Graph: Otomatis di setiap halaman
Canonical URL: Otomatis di halaman singular
Disarankan install plugin Yoast SEO atau Rank Math untuk kontrol lebih lanjut
18. Halaman Profil
Buat halaman-halaman berikut:

Sejarah PSI — Template: "Profil", parent: "Profil"
Visi & Misi — Template: "Profil", parent: "Profil"
Struktur Organisasi — Template: "Struktur Organisasi"
19. Halaman Keanggotaan
Buat halaman "Keanggotaan"
Pilih template "Keanggotaan"
Otomatis redirect ke https://psi.id/menjadi-anggota
20. Shortcodes Tersedia
Shortcode	Fungsi
[psi_leaders limit="3" primary="true"]	Tampilkan kartu pimpinan
[psi_dpd_list per_page="8"]	Tampilkan daftar DPD
[psi_contact_form]	Formulir kontak
[psi_member_form]	Formulir pendaftaran anggota
[psi_member_dashboard]	Cek status keanggotaan
Struktur File
Theme (/wp-content/themes/dpw-psi-papeng/)
style.css
functions.php
header.php
footer.php
front-page.php
page.php
single.php
archive.php
404.php
search.php
sidebar.php
searchform.php
comments.php
page-kontak.php
page-profil.php
page-struktur-organisasi.php
page-keanggotaan.php
single-leadership.php
single-dpd.php
taxonomy-gallery_category.php
taxonomy-video_category.php
inc/
helpers.php
post-types.php
meta-boxes.php
customizer.php
shortcodes.php
template-parts/
hero-slider.php
welcome-section.php
leadership-section.php
divisions-section.php
news-section.php
video-section.php
dpd-section.php
membership-cta.php
assets/
css/
theme.css
admin-meta.css
js/
theme.js
clock.js
admin-meta.js
languages/
dpw-psi-papeng.pot

### Plugin (`/wp-content/plugins/psi-papeng-premium/`)
psi-papeng-premium.php
includes/
class-member-management.php
class-member-dashboard.php
class-whatsapp.php
class-email.php
class-seo.php
class-performance.php
class-activity-log.php
class-admin-panel.php
class-member-statistics.php
class-bootstrap-hooks.php
assets/
css/
admin.css
admin-login.css
js/
admin.js
languages/
psi-papeng-premium.pot

---

## Dukungan Teknis

Untuk bantuan teknis, hubungi:
- **Email:** tombinawaiqbal@gmail.com
- **WhatsApp:** +62 822 6721 8125

---

© 2026 DPW PSI Papua Pegunungan. Dikembangkan oleh Iqbal Tombinawa.
