# Prompt: Fitur Peta Venue Interaktif — FSTVLIST

## Konteks
Bangun komponen "Peta Venue" untuk halaman detail event (`/events/:id`) pada platform tiket FSTVLIST. Komponen ini menggantikan tampilan grid kartu zona biasa dengan **peta spasial top-down** yang merepresentasikan posisi zona relatif terhadap panggung, supaya user bisa langsung memahami "seberapa dekat" suatu zona ke panggung tanpa perlu membaca teks.

## Tujuan Fungsional
1. User melihat denah venue dalam satu SVG/canvas, dengan panggung sebagai titik acuan tetap di bagian atas.
2. Setiap zona digambar sebagai shape (polygon/path) sesuai posisi geometris aslinya — bukan kartu kotak generik yang disusun bebas.
3. Shape antar zona **tidak boleh saling tumpang tindih** kecuali memang disengaja untuk merepresentasikan bentuk venue (misal tribun melingkari area festival).
4. User klik/tap salah satu zona → zona ter-highlight (border tebal) dan panel info di bawah peta menampilkan: nama zona, harga, jumlah kursi tersisa.
5. Tombol CTA di bawah nonaktif (abu-abu, disabled) sampai user memilih satu zona. Setelah dipilih, CTA aktif berwarna kuning brand dan berubah teks jadi "LOGIN UNTUK BELI TIKET".
6. Zona yang stok-nya habis ditampilkan abu-abu, tidak bisa diklik, dan berlabel "habis".
7. Sertakan legenda warna di bawah peta yang memetakan setiap warna ke nama zona.

## Spesifikasi Visual
- **Panggung**: rectangle hitam solid, posisi center-top, label "PANGGUNG" warna kuning brand di dalamnya.
- **Zona VIP**: shape trapesium sempit, langsung menempel di bawah panggung (posisi terdekat).
- **Zona Festival**: shape melebar (general standing), berada di tengah, di bawah VIP.
- **Zona Tribune**: shape melengkung mengikuti sisi kiri dan kanan venue (posisi tribun/kursi elevated), tidak overlap dengan Festival.
- **Zona Regular / disabled**: shape di belakang atau di sisi terluar, warna abu-abu `#9E9E9E` saat statusnya habis.
- Setiap zona memakai warna kategoris berbeda sesuai token warna brand (VIP = kuning `#E8FF00`, Festival = ungu `#B0A0F8`, Tribune = pink `#F26B9E`, Regular/disabled = abu-abu `#9E9E9E`).
- Teks label warna disesuaikan agar tetap kontras terhadap warna fill masing-masing zona (gunakan shade gelap dari warna yang sama, bukan hitam generik).

## Interaksi
- Klik pada shape zona → reset border semua zona lain ke transparan, beri border tebal (misal 3px solid hitam) pada zona yang diklik.
- Update panel info (nama zona, harga format `IDR x.xxx.xxx`, jumlah kursi tersisa) secara real-time saat zona dipilih.
- Zona berstatus habis tidak memiliki event handler klik sama sekali (bukan sekadar didisable secara visual).
- CTA berubah state: `disabled` (abu-abu, "PILIH DULU") → `active` (kuning, "LOGIN UNTUK BELI TIKET") setelah ada zona terpilih.

## Catatan Teknis untuk Fleksibilitas Multi-Event
- Bentuk venue **berbeda-beda per event** (event konser indoor vs outdoor vs teater beda layout). Maka koordinat shape (path/polygon points) setiap zona **harus disimpan sebagai data di database per-event**, bukan di-hardcode di komponen.
- Skema data minimal per zona: `{ id, nama_zona, warna_hex, path_koordinat (SVG path atau array titik polygon), harga, kapasitas_total, kapasitas_tersisa, status }`.
- Sediakan endpoint API untuk mengambil data layout venue per event (`GET /events/:id/venue-map`).
- Untuk kebutuhan admin (lihat requirement FR-05 dan FR-13 di project), sebaiknya turunan dari fitur ini adalah **editor visual sederhana di admin panel** — admin bisa menggambar/edit polygon zona (drag titik-titik), pilih warna, set harga dan kapasitas, tanpa perlu sentuh kode.

## Referensi Desain
Base palette: hitam `#000000` (hero/dark bg), cream `#F5F0E8` (content bg), putih `#FFFFFF` (surface), kuning `#E8FF00` (primary CTA). Ikuti gaya flat, tanpa gradient/shadow dekoratif, border radius konsisten dengan komponen lain di platform.

## Acceptance Criteria
- [ ] Tidak ada shape zona yang saling menutupi secara tidak disengaja.
- [ ] Semua zona aktif bisa diklik dan menampilkan info yang benar.
- [ ] Zona habis tidak bisa diklik dan tampil abu-abu.
- [ ] CTA berubah state sesuai pilihan user.
- [ ] Layout responsif — tetap terbaca di layar mobile (viewBox SVG menyesuaikan lebar container).
- [ ] Data koordinat zona diambil dari API/database, bukan hardcode di frontend.
