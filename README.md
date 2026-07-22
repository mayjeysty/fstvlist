Nama: Mourin Aulia Renata
NIM: 20240803028

FSTVLIST – Sistem Pemesanan Tiket Konser Pop Indonesia Berbasis Web

FSTVLIST merupakan platform pemesanan tiket konser berbasis web yang dilengkapi dengan visualisasi venue interaktif, sistem antrean digital, e-ticket QR Code, serta dashboard administrasi secara real-time.

Tentang Proyek

Industri konser musik pop di Indonesia terus berkembang, namun proses pemesanan tiket masih menghadapi berbagai kendala seperti overbooking, antrean panjang, kurangnya transparansi ketersediaan tiket, dan proses validasi tiket yang masih lambat.

FSTVLIST dikembangkan untuk mengatasi permasalahan tersebut melalui tiga komponen utama:

- Customer Portal sebagai antarmuka bagi pelanggan untuk melihat daftar konser, memilih zona tempat duduk, melakukan pemesanan tiket, dan menerima e-ticket.
- Admin Panel sebagai pusat pengelolaan venue, event, zona, tiket, serta pemantauan penjualan secara real-time.
- Gate Validator Dashboard sebagai antarmuka petugas untuk melakukan validasi QR Code ketika pengunjung memasuki area konser.

Proyek ini dikembangkan sebagai Tugas Projek Akhir oleh Mourin Aulia Renata (20240803028).

Fitur Utama

Customer Portal

- Visualisasi venue interaktif menggunakan layout berbasis SVG.
- Sistem antrean digital (waiting room) ketika trafik tinggi.
- Booking timer selama 15 menit untuk menjaga kuota tiket.
- Integrasi pembayaran menggunakan Midtrans Snap API.
- Pembuatan e-ticket dalam bentuk PDF yang dilengkapi QR Code unik.
- Pengiriman e-ticket secara otomatis melalui email.

Admin Panel

- Manajemen data venue beserta kapasitasnya.
- Manajemen zona beserta harga dan kuota setiap event.
- Manajemen event konser.
- Dashboard monitoring penjualan tiket secara real-time.
- Monitoring transaksi dan aktivitas validasi tiket.

Gate Validator

- Pemindaian QR Code menggunakan kamera perangkat.
- Validasi tiket secara instan.
- Pencegahan penggunaan tiket lebih dari satu kali.
- Statistik hasil pemindaian tiket setiap hari.

Sistem Anti-Overbooking

- Pessimistic locking menggunakan transaksi database.
- Scheduled jobs untuk mengelola antrean, pembayaran, dan pemesanan yang telah kedaluwarsa.

Teknologi

- Laravel 12 (PHP 8.3)
- Blade dan Livewire v3
- Filament v3
- Tailwind CSS
- MariaDB
- Redis
- Nginx
- Docker dan Docker Compose
- Vite
- Midtrans Snap API
- Simple QR Code
- Laravel DomPDF
- Laravel Breeze
- Google OAuth (Socialite)
- Spatie Laravel Permission
- html5-qrcode

Alur Pemesanan Tiket

1. Pengguna memilih event yang tersedia.
2. Pengguna memilih zona melalui visualisasi venue.
3. Jika sistem antrean aktif, pengguna akan masuk ke waiting room.
4. Pengguna memilih jumlah tiket.
5. Sistem mengunci kuota tiket selama 15 menit.
6. Pengguna melakukan pembayaran.
7. Sistem menghasilkan QR Code dan e-ticket.
8. E-ticket dikirim melalui email.
9. Pada hari konser, QR Code dipindai oleh petugas untuk proses validasi.

Struktur Proyek

```
fstvlist/
├── docker-compose.yml
├── nginx/
├── php/
├── db/
└── src/
```

Prasyarat

- Docker
- Docker Compose

Instalasi

```bash
git clone https://github.com/your-username/fstvlist.git

cd fstvlist

cp src/.env.example .env

docker compose up -d

docker compose exec php composer install

docker compose exec php npm install

docker compose exec php php artisan key:generate

docker compose exec php php artisan migrate --seed

docker compose exec php npm run build
```

Akses Aplikasi

- Customer Portal: https://localhost
- Admin Panel: https://localhost/admin
- Gate Validator: https://localhost/gate

Hak Akses

- Super Admin memiliki akses penuh terhadap seluruh sistem.
- Admin mengelola venue, event, zona, tiket, dan transaksi.
- Validator melakukan validasi tiket saat konser berlangsung.
- Customer melakukan pemesanan tiket dan mengunduh e-ticket

Kontributor

Mourin Aulia Renata (20240803028)
