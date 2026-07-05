# Product Requirements Document (PRD)
## FSTVLIST — Sistem Pemesanan Tiket Konser Pop Indonesia Berbasis Web

**Versi:** 2.0
**Tanggal:** 28 Juni 2026
**Status:** Final Draft

---

## Daftar Isi

1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Tujuan Produk](#2-tujuan-produk)
3. [Pengguna Sasaran (User Personas)](#3-pengguna-sasaran-user-personas)
4. [Fitur dan Persyaratan Fungsional](#4-fitur-dan-persyaratan-fungsional)
5. [Persyaratan Non-Fungsional](#5-persyaratan-non-fungsional)
6. [Alur Pengguna (User Flow)](#6-alur-pengguna-user-flow)
7. [Kriteria Penerimaan (Acceptance Criteria)](#7-kriteria-penerimaan-acceptance-criteria)
8. [Batasan dan Asumsi](#8-batasan-dan-asumsi)
9. [Garis Waktu (Timeline)](#9-garis-waktu-timeline)

---

## 1. Ringkasan Eksekutif

### Latar Belakang

Industri konser musik pop di Indonesia terus berkembang pesat dengan penyelenggaraan acara berskala besar di berbagai kota seperti Jakarta, Surabaya, Bandung, dan Medan. Namun, proses pemesanan tiket yang masih bergantung pada antrean fisik dan sistem manual menimbulkan berbagai permasalahan serius: overbooking, antrean panjang yang memicu frustrasi, kurangnya transparansi dalam pemilihan zona tiket, serta validasi tiket yang lambat dan rawan kecurangan.

### Solusi

**FSTVLIST** adalah platform pemesanan tiket konser berbasis web yang menyelesaikan permasalahan tersebut melalui tiga komponen utama:

- **Customer Portal** — antarmuka pemesanan tiket dengan visualisasi layout venue interaktif berbasis zona dan sistem antrean digital
- **Admin Panel** — dasbor pengelolaan acara, tiket, dan monitoring penjualan secara real-time
- **Gate Validator Dashboard** — antarmuka validasi e-tiket QR Code untuk petugas gerbang di hari pelaksanaan konser

### Proposisi Nilai

| Untuk | Manfaat |
|-------|---------|
| Penonton | Pemesanan kapan saja dan di mana saja, tanpa antrean fisik, dengan informasi zona yang transparan |
| Penyelenggara | Kontrol penuh atas penjualan tiket, monitoring real-time, dan data akurat untuk pengambilan keputusan |
| Petugas Gerbang | Validasi tiket yang cepat (< 5 detik), akurat, dan terintegrasi langsung ke sistem pusat |

---

## 2. Tujuan Produk

### 2.1 Tujuan Bisnis

| Tujuan | Indikator Keberhasilan |
|--------|------------------------|
| Menghilangkan antrean fisik pembelian tiket | 100% transaksi dilakukan secara digital melalui platform |
| Mencegah overbooking | Nol kejadian overbooking berkat sistem lock kuota real-time |
| Mempermudah pemilihan zona tiket | Pelanggan dapat melihat visualisasi venue dan memilih zona sebelum checkout |
| Meningkatkan efisiensi validasi tiket di venue | Waktu validasi per tiket < 5 detik |
| Mendukung pengambilan keputusan penyelenggara | Admin dapat mengakses laporan penjualan real-time kapan saja |

### 2.2 Tujuan Produk (Product Goals)

- Menyediakan platform pemesanan tiket yang terstruktur, transparan, dan mudah digunakan oleh semua kalangan
- Mengotomatiskan seluruh alur transaksi: dari pemilihan tiket hingga pengiriman e-tiket via email
- Membangun sistem manajemen terpusat yang memungkinkan penyelenggara mengelola acara tanpa perlu mengubah kode program
- Memastikan keamanan dan keabsahan tiket melalui kode QR unik yang hanya dapat digunakan satu kali

---

## 3. Pengguna Sasaran (User Personas)

### Persona 1 — Penonton Konser (Pelanggan)

> *"Aku mau beli tiket dengan mudah, tahu persis zona mana yang aku pilih, dan nggak perlu antre berjam-jam."*

| Atribut | Detail |
|---------|--------|
| **Nama Representatif** | Rania, 23 tahun |
| **Pekerjaan** | Mahasiswi / Karyawan muda |
| **Perangkat Utama** | Smartphone |
| **Frekuensi Konser** | 2–4 kali per tahun |
| **Tingkat Tech-Savvy** | Menengah — terbiasa belanja online dan menggunakan aplikasi mobile |

**Pain Points:**
- Bingung menentukan zona tiket karena tidak ada gambaran visual venue
- Frustrasi saat harus antre panjang secara fisik, tiket sudah habis saat tiba giliran
- Khawatir tiket yang dibeli tidak valid atau tertukar

**Kebutuhan Utama:**
- Visualisasi layout venue yang jelas agar bisa memilih zona dengan percaya diri
- Proses pemesanan yang cepat dan dapat dilakukan dari smartphone
- E-tiket yang bisa disimpan dan ditampilkan langsung dari ponsel saat hari acara
- Konfirmasi pesanan yang jelas melalui email

---

### Persona 2 — Penyelenggara Acara (Administrator)

> *"Aku butuh kontrol penuh atas penjualan tiket dan data yang akurat untuk memastikan acara berjalan lancar."*

| Atribut | Detail |
|---------|--------|
| **Nama Representatif** | Dimas, 32 tahun |
| **Pekerjaan** | Event Manager / Tim Operasional Promotor |
| **Perangkat Utama** | Laptop |
| **Frekuensi Pengelolaan Acara** | 6–12 acara per tahun |
| **Tingkat Tech-Savvy** | Menengah-tinggi — terbiasa menggunakan tools manajemen |

**Pain Points:**
- Sulit memantau sisa kuota tiket secara real-time, berpotensi overbooking
- Tidak ada satu platform terpusat untuk mengelola venue, zona, dan tiket sekaligus
- Laporan penjualan sering terlambat dan tidak akurat karena masih dikerjakan manual

**Kebutuhan Utama:**
- Dashboard monitoring penjualan dan kuota tiket secara real-time
- Kemampuan membuka/menutup penjualan dan mengaktifkan mode antrean dengan mudah
- Manajemen data venue, zona, acara, dan kategori tiket dalam satu panel
- Laporan ringkasan penjualan yang dapat diakses kapan saja

---

### Persona 3 — Petugas Gerbang (Validator)

> *"Aku butuh alat yang simpel dan cepat untuk memvalidasi tiket supaya antrean masuk venue tidak menumpuk."*

| Atribut | Detail |
|---------|--------|
| **Nama Representatif** | Agus, 28 tahun |
| **Pekerjaan** | Staf keamanan / Petugas lapangan konser |
| **Perangkat Utama** | Smartphone (disediakan panitia) |
| **Frekuensi Validasi** | Per hari pelaksanaan konser |
| **Tingkat Tech-Savvy** | Rendah-menengah — butuh antarmuka yang sangat sederhana |

**Pain Points:**
- Validasi tiket manual (fisik) lambat dan rawan kecurangan
- Sulit mendeteksi tiket palsu atau tiket yang sudah digunakan orang lain
- Tidak ada umpan balik yang jelas apakah tiket valid atau tidak

**Kebutuhan Utama:**
- Antarmuka pemindaian QR yang sangat sederhana dan responsif di smartphone
- Umpan balik status tiket yang jelas dan instan (hijau = valid, merah = tolak)
- Sistem yang otomatis menolak tiket yang sudah pernah dipindai sebelumnya

---

## 4. Fitur dan Persyaratan Fungsional

### 4.1 Modul Akun Pelanggan

#### FR-01 · Registrasi Akun
- Pelanggan baru dapat membuat akun menggunakan nama lengkap, email, dan password
- Sistem memvalidasi format email dan menolak email yang sudah terdaftar
- Password disimpan dalam bentuk hash (tidak disimpan plaintext)

#### FR-02 · Login & Logout
- Pelanggan dapat masuk menggunakan email dan password yang terdaftar
- Autentikasi menggunakan session management berbasis token
- Logout menghapus sesi aktif dan mengarahkan ke halaman utama

---

### 4.2 Modul Penjelajahan Acara

#### FR-03 · Daftar Acara
- Sistem menampilkan seluruh acara yang berstatus aktif
- Setiap item menampilkan: nama acara, nama venue, kota, tanggal pelaksanaan, dan status penjualan
- Halaman daftar acara dapat diakses tanpa login

#### FR-04 · Detail Acara
- Pelanggan dapat melihat informasi lengkap acara: nama, deskripsi, venue, tanggal dan waktu
- Sistem menampilkan daftar zona tiket beserta harga dan sisa kuota per zona
- Jika penjualan ditutup oleh admin, tombol pemesanan tidak aktif

---

### 4.3 Modul Visualisasi Venue

#### FR-05 · Layout Venue Interaktif
- Sistem menampilkan peta visual venue berbasis zona dengan warna berbeda per zona
- Zona yang masih tersedia (ada kuota) dapat diklik oleh pelanggan
- Zona yang habis tiketnya ditampilkan dengan tampilan tidak aktif (abu-abu)
- Klik pada zona menampilkan popup informasi: nama zona, harga tiket, sisa kuota
- Tampilan responsif di desktop maupun smartphone

---

### 4.4 Modul Pemesanan Tiket

#### FR-06 · Pemilihan Zona & Jumlah Tiket
- Pelanggan hanya dapat memilih satu zona per transaksi
- Jumlah tiket dibatasi maksimal 4 per transaksi
- Sistem memvalidasi ketersediaan kuota sebelum melanjutkan ke checkout
- Jika kuota tidak mencukupi, sistem menampilkan pesan "Kuota Tidak Cukup"

#### FR-07 · Sistem Antrean Digital
- Jika mode antrean diaktifkan admin, pelanggan diarahkan ke halaman antrean digital
- Sistem menampilkan posisi antrean pelanggan secara real-time
- Pelanggan otomatis diarahkan ke halaman pemesanan saat giliran tiba
- Jika mode antrean nonaktif, pelanggan langsung melanjutkan ke proses pemesanan

#### FR-08 · Reservasi Sementara & Timer 15 Menit
- Sistem melakukan reservasi kuota sementara saat pesanan dibuat
- Timer countdown 15 menit ditampilkan kepada pelanggan
- Jika waktu habis sebelum pembayaran selesai, pesanan otomatis kedaluwarsa dan kuota dikembalikan
- Status pesanan berubah menjadi "Kedaluwarsa" secara otomatis via scheduled job

#### FR-09 · Checkout & Simulasi Pembayaran
- Pelanggan mengisi data pembelian dan mengonfirmasi pesanan
- Halaman simulasi pembayaran menampilkan ringkasan dan total yang harus dibayar
- Pembayaran berhasil mengubah status pesanan menjadi "Dibayar"
- Pembayaran tidak selesai menyebabkan pesanan dibatalkan dan kuota dikembalikan

---

### 4.5 Modul E-Tiket

#### FR-10 · Generasi QR Code & PDF E-Tiket
- Sistem menghasilkan kode QR unik per tiket setelah pembayaran berhasil
- Satu transaksi menghasilkan satu file PDF (multi-halaman, satu halaman per tiket)
- Setiap halaman PDF memuat: nama acara, zona, nama pelanggan, nomor tiket, dan kode QR

#### FR-11 · Pengiriman E-Tiket via Email
- E-tiket dikirimkan otomatis ke email pelanggan setelah pembayaran dikonfirmasi
- Sistem menggunakan mekanisme queue (Redis) dan retry otomatis jika pengiriman gagal
- Pelanggan juga dapat mengunduh e-tiket langsung dari halaman riwayat transaksi

---

### 4.6 Modul Riwayat Transaksi

#### FR-12 · Riwayat Pemesanan
- Pelanggan dapat melihat seluruh riwayat transaksi beserta status pesanan
- Pelanggan dapat mengunduh ulang e-tiket dari transaksi yang berstatus "Dibayar"

---

### 4.7 Modul Admin Panel

#### FR-13 · Manajemen Venue & Zona
- Admin dapat membuat, mengedit, dan menghapus data venue (nama, alamat, kota, kapasitas, layout)
- Admin dapat mengelola zona venue (nama, deskripsi, kapasitas, warna, posisi pada canvas)
- Perubahan konfigurasi zona langsung tercermin pada visualisasi di halaman pelanggan

#### FR-14 · Manajemen Acara
- Admin dapat membuat, mengedit, dan menonaktifkan acara
- Admin dapat membuka/menutup penjualan tiket per acara
- Admin dapat mengaktifkan/menonaktifkan mode antrean digital per acara

#### FR-15 · Manajemen Kategori Tiket
- Admin dapat menetapkan harga dan kuota yang berbeda untuk setiap zona per acara
- Sistem mencatat jumlah tiket terjual secara otomatis

#### FR-16 · Monitoring & Pelaporan
- Admin dapat memantau penjualan dan kuota tiket secara real-time melalui dashboard
- Laporan tersedia mencakup: ringkasan pesanan, riwayat transaksi, pendapatan, penjualan per zona, dan data validasi tiket

---

### 4.8 Modul Gate Validator

#### FR-17 · Pemindaian & Validasi QR Code
- Petugas dapat memindai kode QR tiket menggunakan kamera perangkat
- Tiket aktif dan valid → konfirmasi hijau, tiket ditandai "Digunakan", penonton diizinkan masuk
- Tiket sudah digunakan → peringatan merah, akses ditolak
- Tiket tidak dikenali → pesan penolakan, akses ditolak
- Setiap hasil validasi dicatat oleh sistem secara otomatis

---

### 4.9 Status Sistem

**Status Pesanan:**

| Status | Kondisi |
|--------|---------|
| Menunggu Pembayaran | Pesanan dibuat, belum dibayar |
| Dibayar | Pembayaran berhasil dikonfirmasi |
| Kedaluwarsa | Batas waktu 15 menit terlampaui |
| Dibatalkan | Pesanan dibatalkan oleh pelanggan atau sistem |

**Status Tiket:**

| Status | Kondisi |
|--------|---------|
| Aktif | Tiket valid, belum digunakan |
| Digunakan | Tiket telah dipindai di venue |
| Kedaluwarsa | Tiket terkait pesanan yang kedaluwarsa |

---

## 5. Persyaratan Non-Fungsional

### 5.1 Keamanan (Security)

- Data pelanggan, pemesanan, dan tiket hanya dapat diakses oleh pengguna dengan hak akses yang sesuai (role-based access control: `customer`, `admin`, `validator`)
- Password disimpan menggunakan algoritma hashing yang aman (bcrypt)
- Autentikasi dikelola menggunakan session management berbasis token
- Sistem dilindungi dengan Web Application Firewall (WAF), rate limiting, dan security headers
- Seluruh aktivitas kritis dicatat dalam audit log (user, aksi, IP address, timestamp)

### 5.2 Reliabilitas (Reliability)

- Data pemesanan dan tiket tersimpan secara persisten dan dapat diakses kembali kapan pun dibutuhkan
- Mekanisme backup database dilakukan setiap hari
- Recovery Time Objective (RTO): kurang dari 15 menit setelah gangguan terdeteksi
- Sistem memiliki mekanisme retry otomatis untuk pengiriman email yang gagal

### 5.3 Performa (Performance)

| Metrik | Target |
|--------|--------|
| Waktu respons halaman | < 2 detik |
| Waktu proses checkout | < 3 detik |
| Waktu validasi QR Code | < 5 detik |

### 5.4 Kemudahan Penggunaan (Usability)

- Antarmuka dirancang sederhana, intuitif, dan responsif di seluruh jenis perangkat (desktop dan smartphone)
- Pelanggan awam dapat menyelesaikan proses pemesanan tanpa panduan tambahan
- Petugas validator dapat mengoperasikan dashboard validasi dengan pelatihan minimal

### 5.5 Pemeliharaan (Maintainability)

- Data acara, venue, zona, harga, dan kuota dapat diperbarui admin melalui panel admin tanpa mengubah kode program

### 5.6 Kompatibilitas (Compatibility)

- Dapat diakses melalui browser modern (Chrome, Firefox, Safari, Edge) versi terbaru
- Mendukung tampilan responsif di perangkat desktop, tablet, dan smartphone

---

## 6. Alur Pengguna (User Flow)

### 6.1 Alur Pelanggan — Pemesanan Tiket

```
[Mulai]
   |
   v
Registrasi / Login
   |
   v
Pilih Acara dari Daftar
   |
   v
Lihat Detail Acara & Visualisasi Layout Venue
   |
   v
Klik Zona pada Peta Venue --> Lihat Info Zona & Harga
   |
   v
Pilih Jumlah Tiket (1-4)
   |
   v
Sistem cek: Apakah mode antrean aktif?
   |-- YA --> Masuk Antrean Digital --> Tunggu Giliran --> Keluar Antrean
   |                                                              |
   |-- TIDAK ---------------------------------------------------- +
                                                                  |
   +--------------------------------------------------------------+
   |
   v
Sistem cek: Apakah kuota mencukupi?
   |-- TIDAK --> Tampilkan "Kuota Tidak Cukup" --> [Selesai]
   |-- YA
         |
         v
   Reservasi Kuota Sementara + Timer 15 Menit Mulai
         |
         v
   Isi Data Pembelian & Checkout
         |
         v
   Simulasi Pembayaran
         |-- Timeout/Gagal --> Pesanan Kedaluwarsa --> Kuota Dikembalikan --> [Selesai]
         |-- Berhasil
                  |
                  v
             Sistem Generate QR Code Unik per Tiket
                  |
                  v
             Sistem Buat PDF E-Tiket & Kirim via Email
                  |
                  v
             Status Pesanan --> "Dibayar"
                  |
                  v
             Pelanggan Terima & Unduh E-Tiket
                  |
                  v
                [Selesai]
```

---

### 6.2 Alur Pelanggan — Masuk Venue (Hari Acara)

```
[Hari Acara]
   |
   v
Pelanggan Tunjukkan QR Code (HP / Cetakan)
   |
   v
Petugas Pindai QR Code via Dashboard Validator
   |
   v
Sistem Validasi QR Code
   |
   |-- VALID & Belum Digunakan
   |        |
   |        v
   |   Tampilkan Konfirmasi Hijau [OK]
   |   Tiket Ditandai "Digunakan"
   |   Penonton Diizinkan Masuk --> [Selesai]
   |
   |-- SUDAH DIGUNAKAN
   |        |
   |        v
   |   Tampilkan Peringatan Merah [TOLAK]
   |   "Tiket Sudah Digunakan"
   |   Akses Ditolak --> [Selesai]
   |
   +-- TIDAK VALID / TIDAK DIKENALI
            |
            v
       Tampilkan Pesan Penolakan [TOLAK]
       "Tiket Tidak Valid"
       Akses Ditolak --> [Selesai]
```

---

### 6.3 Alur Admin — Membuka Penjualan Tiket Acara Baru

```
[Admin Login ke Panel]
   |
   v
Buat Data Venue + Konfigurasi Zona (nama, warna, posisi, kapasitas)
   |
   v
Buat Data Acara (nama, tanggal, venue, deskripsi)
   |
   v
Buat Kategori Tiket per Zona (harga + kuota)
   |
   v
Aktifkan Status Acara --> "Aktif"
   |
   v
Buka Penjualan Tiket --> Status Penjualan --> "Buka"
   |
   v
(Opsional) Aktifkan Mode Antrean Digital
   |
   v
Acara Tampil di Halaman Pelanggan & Penjualan Dimulai
   |
   v
Monitor Penjualan & Kuota via Dashboard Real-Time
   |
   v
[Tutup Penjualan Saat Hari Acara Berakhir]
```

---

## 7. Kriteria Penerimaan (Acceptance Criteria)

Sistem dinyatakan siap rilis apabila seluruh kondisi berikut terpenuhi dan telah diuji:

### Modul Pelanggan

- [ ] Pelanggan dapat melakukan registrasi akun baru dengan email dan password
- [ ] Pelanggan dapat login dan logout dari sistem
- [ ] Pelanggan dapat melihat daftar acara yang tersedia tanpa perlu login
- [ ] Pelanggan dapat melihat detail acara beserta informasi harga dan kuota per zona
- [ ] Pelanggan dapat melihat visualisasi layout venue yang interaktif berbasis zona
- [ ] Pelanggan dapat memilih zona, melihat sisa kuota, dan memilih jumlah tiket (maks. 4)
- [ ] Sistem menampilkan dan mengelola antrean digital saat mode antrean diaktifkan
- [ ] Sistem melakukan reservasi kuota dan menjalankan countdown timer 15 menit
- [ ] Pesanan otomatis kedaluwarsa dan kuota dikembalikan jika timer habis sebelum pembayaran
- [ ] Pelanggan dapat menyelesaikan simulasi pembayaran
- [ ] Sistem menghasilkan QR Code unik per tiket setelah pembayaran berhasil
- [ ] Sistem mengirimkan e-tiket PDF ke email pelanggan secara otomatis
- [ ] Pelanggan dapat mengunduh e-tiket PDF dari halaman riwayat transaksi

### Modul Admin

- [ ] Admin dapat membuat dan mengelola data venue beserta konfigurasi zona
- [ ] Admin dapat membuat dan mengelola data acara
- [ ] Admin dapat menetapkan harga dan kuota tiket per zona per acara
- [ ] Admin dapat membuka dan menutup penjualan tiket
- [ ] Admin dapat mengaktifkan dan menonaktifkan mode antrean digital
- [ ] Admin dapat memantau penjualan, kuota, dan transaksi secara real-time di dashboard
- [ ] Admin dapat melihat laporan ringkasan penjualan per zona dan per periode

### Modul Gate Validator

- [ ] Petugas validator dapat memindai QR Code menggunakan kamera perangkat
- [ ] Sistem menampilkan konfirmasi dan mengizinkan masuk untuk tiket yang valid
- [ ] Sistem menolak dan menampilkan peringatan untuk tiket yang sudah digunakan
- [ ] Sistem menolak tiket yang tidak dikenali atau tidak valid
- [ ] Satu QR Code tidak dapat digunakan lebih dari satu kali (pemindaian ganda ditolak)

### Performa & Keamanan

- [ ] Waktu respons halaman rata-rata < 2 detik
- [ ] Waktu proses checkout < 3 detik
- [ ] Waktu validasi QR Code < 5 detik
- [ ] Tidak ada kejadian overbooking (kuota tidak bisa bernilai negatif)
- [ ] Data pesanan tersimpan di database dan dapat diakses kembali kapan pun

---

## 8. Batasan dan Asumsi

### 8.1 Batasan (Constraints)

| Batasan | Keterangan |
|---------|------------|
| Simulasi pembayaran | Sistem tidak terintegrasi dengan payment gateway produksi pada fase ini; pembayaran menggunakan simulasi |
| Sistem zona, bukan kursi individual | Platform tidak mendukung pemilihan kursi per individu; menggunakan sistem zona sesuai karakteristik konser pop Indonesia |
| Single organizer | Sistem dirancang untuk satu penyelenggara acara; manajemen multi-organizer tidak termasuk scope |
| Single server environment | Deployment dilakukan pada satu server; multi-server deployment tidak termasuk scope |
| Web-based only | Platform hanya tersedia dalam bentuk web responsif; tidak tersedia sebagai aplikasi mobile native (Android/iOS) |
| Tidak ada sistem akuntansi | Sistem hanya menyediakan pelaporan penjualan dasar; tidak mencakup akuntansi dan pembukuan lengkap |

### 8.2 Asumsi (Assumptions)

- Pelanggan memiliki akses internet yang memadai untuk menggunakan platform saat pemesanan
- Pelanggan memiliki alamat email yang valid dan aktif untuk menerima e-tiket
- Pelanggan membawa perangkat yang dapat menampilkan QR Code (smartphone) atau mencetak e-tiket pada hari acara
- Petugas validator telah mendapatkan pelatihan dasar penggunaan dashboard validasi sebelum hari acara
- Penyelenggara acara bertindak sebagai admin utama dan bertanggung jawab atas keakuratan data yang dimasukkan ke sistem
- Sistem mendukung operasional penjualan tiket dan tidak menggantikan proses pelaksanaan acara secara fisik

---

## 9. Garis Waktu (Timeline)

Estimasi pengembangan sistem FSTVLIST dibagi dalam **5 fase** dengan total durasi sekitar **20 minggu**.

### Ringkasan Milestone

| Milestone | Fase | Target Selesai |
|-----------|------|----------------|
| M1 — Setup & Fondasi selesai | Fase 1 | Akhir Minggu 3 |
| M2 — Customer Portal & Admin Panel (fitur inti) live | Fase 2 | Akhir Minggu 8 |
| M3 — E-Tiket, Antrean & Validasi berfungsi penuh | Fase 3 | Akhir Minggu 13 |
| M4 — Monitoring & Pelaporan lengkap | Fase 4 | Akhir Minggu 16 |
| M5 — Sistem siap produksi (post-UAT) | Fase 5 | Akhir Minggu 20 |

---

### Fase 1 — Fondasi & Setup (Minggu 1–3)

**Tujuan:** Membangun infrastruktur awal dan fondasi sistem sebelum pengembangan fitur dimulai.

| Tugas | Estimasi |
|-------|----------|
| Setup environment: Docker, Nginx, Laravel, MariaDB, Redis | 3 hari |
| Konfigurasi project: Filament v3, Livewire v3, Tailwind CSS v3 | 2 hari |
| Desain dan migrasi skema database (semua tabel) | 3 hari |
| Implementasi autentikasi (registrasi, login, logout, role-based access) | 3 hari |
| Setup Git repository dan version control workflow | 2 hari |
| Desain wireframe antarmuka Customer Portal & Admin Panel | 2 hari |

**Output:** Project siap dikembangkan dengan struktur database lengkap dan autentikasi berfungsi.

---

### Fase 2 — Pengembangan Fitur Inti (Minggu 4–8)

**Tujuan:** Membangun seluruh fitur utama Customer Portal dan Admin Panel.

| Tugas | Estimasi |
|-------|----------|
| Halaman daftar dan detail acara (Customer Portal) | 3 hari |
| Visualisasi layout venue interaktif berbasis zona | 5 hari |
| Manajemen venue, zona, dan acara (Admin Panel – Filament) | 4 hari |
| Manajemen kategori tiket, harga, dan kuota (Admin Panel) | 3 hari |
| Alur pemilihan tiket dan validasi kuota real-time | 3 hari |
| Sistem reservasi sementara + timer countdown 15 menit | 4 hari |
| Halaman checkout & simulasi pembayaran | 3 hari |

**Output:** Pelanggan dapat menelusuri acara, memilih tiket, dan menyelesaikan pemesanan secara end-to-end.

---

### Fase 3 — E-Tiket, Antrean & Validasi (Minggu 9–13)

**Tujuan:** Membangun fitur e-tiket, sistem antrean digital, dan dashboard validator gerbang.

| Tugas | Estimasi |
|-------|----------|
| Generasi QR Code unik per tiket (Endroid QR Code) | 3 hari |
| Generasi PDF e-tiket (Dompdf) | 3 hari |
| Pengiriman email otomatis dengan lampiran e-tiket (SMTP + Redis Queue) | 4 hari |
| Sistem antrean digital + integrasi dengan alur pemesanan | 5 hari |
| Dashboard Gate Validator (pemindaian & validasi QR Code) | 4 hari |
| Penanganan pesanan kedaluwarsa (scheduled job + pengembalian kuota otomatis) | 3 hari |

**Output:** Alur pemesanan berfungsi penuh dari awal hingga akhir; e-tiket terkirim otomatis ke email pelanggan.

---

### Fase 4 — Monitoring, Pelaporan & Penyempurnaan (Minggu 14–16)

**Tujuan:** Melengkapi fitur monitoring admin dan menyempurnakan pengalaman pengguna.

| Tugas | Estimasi |
|-------|----------|
| Dashboard monitoring penjualan real-time (Admin Panel) | 3 hari |
| Halaman laporan: ringkasan pesanan, pendapatan, per zona, validasi tiket | 4 hari |
| Halaman riwayat transaksi pelanggan + unduh ulang e-tiket | 2 hari |
| Implementasi audit log sistem | 2 hari |
| Penyempurnaan UI/UX (responsif mobile, feedback interaksi, aksesibilitas) | 4 hari |

**Output:** Admin memiliki visibilitas penuh atas seluruh aktivitas penjualan dan validasi tiket.

---

### Fase 5 — Testing, Perbaikan & Deployment (Minggu 17–20)

**Tujuan:** Memastikan sistem bebas bug, aman, dan siap digunakan di lingkungan produksi.

| Tugas | Estimasi |
|-------|----------|
| Unit testing dan integration testing seluruh modul | 5 hari |
| User Acceptance Testing (UAT) bersama pengguna nyata | 4 hari |
| Perbaikan bug dan penyesuaian dari hasil UAT | 4 hari |
| Security testing (WAF, rate limiting, penetration test dasar) | 3 hari |
| Dokumentasi teknis dan panduan pengguna | 2 hari |
| Deployment ke server produksi | 2 hari |

**Output:** Sistem FSTVLIST siap digunakan secara penuh di lingkungan produksi.

---

*Dokumen PRD ini disusun berdasarkan Business Requirement Document (BRD) FSTVLIST oleh Mourin Aulia Renata (20240803028). Setiap perubahan signifikan pada dokumen ini harus dikomunikasikan kepada seluruh stakeholder terkait.*
