# FSTVLIST — Master AI Prompt Guide
## Sistem Pemesanan Tiket Konser Pop Indonesia Berbasis Web
**Versi:** 1.0 | **Dibuat:** Juli 2026 | **Untuk:** v0, Cursor, Lovable, Claude, ChatGPT, Bolt

---

> **Cara pakai dokumen ini:**
> Setiap bagian adalah prompt mandiri yang bisa langsung di-copy paste ke AI.
> Selalu sertakan **Blok 0 (Design System Foundation)** di awal setiap prompt
> sebelum menambahkan blok halaman spesifik yang ingin dibangun.

---

## BLOK 0 — DESIGN SYSTEM FOUNDATION
*(Wajib disertakan di setiap prompt)*

```
Kamu adalah senior frontend developer sekaligus UI engineer.
Bangun antarmuka untuk aplikasi bernama FSTVLIST — platform pemesanan
tiket konser pop Indonesia berbasis web.

=== DESIGN SYSTEM ===

REFERENSI VISUAL:
Desain terinspirasi dari estetika website musik Beyoncé — editorial
high-fashion maximalism dengan pendekatan flat design, tanpa shadow,
tanpa glassmorphism, tanpa neumorphism. Semua bidang solid dan tegas.
Gaya ini menggabungkan dua mood dalam satu halaman: dark cinematic
(hero section) dan warm editorial print (content section), seperti
majalah musik premium.

WARNA:
- Background hero/dark section : #000000 (hitam pekat)
- Background content section   : #F5F0E8 (krem hangat)
- Surface card gelap           : #1A1A1A
- Surface card lebih gelap     : #0D0D0D (hanya admin panel)
- Divider/border gelap         : #2C2C2A
- Divider/border terang        : #E0DDD6

- Aksen PRIMER (CTA utama)     : #E8FF00 (kuning neon/electric)
  → Digunakan SANGAT TERBATAS: hanya untuk tombol CTA utama,
    elemen navigasi aktif, harga, dan highlight kritis.
    Disiplin penggunaan aksen ini adalah kunci keeleganan desain.

- Aksen zona VIP               : #E8FF00
- Aksen zona Festival          : #F26B9E (pink koral)
- Aksen zona Tribune           : #B0A0F8 (ungu lembut)
- Aksen zona Reguler           : #FF5733 (oranye-merah)
- Zona habis/disabled          : #9E9E9E (abu-abu, opacity 60%)

- Warna semantik sukses        : #5DCAA5 (hijau)
- Warna semantik error/tolak   : #E24B4A (merah)
- Warna semantik warning       : #F09595 (merah muda)

- Teks primer di dark bg       : #FFFFFF
- Teks sekunder di dark bg     : #9E9E9E
- Teks primer di light bg      : #000000
- Teks sekunder di light bg    : #5F5E5A
- Teks tersier di light bg     : #B4B2A9

TIPOGRAFI:
Font 1 — Fraunces (Google Fonts, serif display)
  → Dipakai untuk: judul hero, nama acara besar, heading section,
    angka harga besar, judul kartu tiket.
  → Selalu uppercase, weight 700–900, letter-spacing -0.01em hingga
    -0.02em (tracking sangat ketat), line-height 0.95–1.1.
  → Ini adalah font "emosional dan dramatis" — hanya untuk elemen
    yang butuh kehadiran visual kuat.

Font 2 — Inter (Google Fonts, sans-serif)
  → Dipakai untuk: semua teks UI, body, label form, navigasi, tabel,
    badge status, tombol, teks di admin panel dan gate validator.
  → Weight: 400 (body), 500 (label/nav), 600 (sub-heading), 700 (bold).
  → Untuk label kecil: uppercase, letter-spacing 0.05em–0.08em.

IMPORT FONT:
@import url('https://fonts.googleapis.com/css2?family=Fraunces:wght@700;900&family=Inter:wght@400;500;600;700&display=swap');

SKALA TIPOGRAFI (8-point grid):
- display-xl  : 96px Fraunces 900 (hero utama)
- display-lg  : 52px Fraunces 700 (judul section)
- display-md  : 28–36px Fraunces 700 (judul card/modal)
- heading     : 20–24px Inter 600
- body-lg     : 16px Inter 400
- body-md     : 14px Inter 400
- body-sm     : 13px Inter 400
- label       : 11–12px Inter 500–600, uppercase, letter-spacing 0.06em
- mono/id     : 10–11px Inter 400 (untuk ID tiket, kode referensi)

KOMPONEN UI — POLA DASAR:
Tombol CTA Primer:
  background #E8FF00, color #000, border-radius 999px (pill),
  padding 14px 28px, font Inter 700 13–14px, uppercase,
  letter-spacing 0.04em. Tidak ada shadow.

Tombol CTA Sekunder (ghost):
  background transparan, border 1.5px solid #000 (di bg terang)
  atau 1.5px solid rgba(255,255,255,0.3) (di bg gelap),
  border-radius 999px, warna teks sesuai background.

Tombol Filled Gelap (di halaman krem):
  background #000, color #E8FF00, border-radius 999px.

Input Field:
  background #fff (di bg krem) atau #1A1A1A (di bg gelap),
  border 1.5px solid #E0DDD6 (terang) atau #2C2C2A (gelap),
  border-radius 999px (pill shape), padding 13px 20px,
  font Inter 14px. Fokus: border berubah ke #000 atau #E8FF00.
  Placeholder: #B4B2A9.

Card di background krem:
  background #fff atau #F5F0E8, border-radius 16–20px,
  border 0.5px solid #E0DDD6, tanpa shadow.

Card di background gelap:
  background #141414 atau #1A1A1A, border-radius 12–16px,
  border 0.5px solid #1F1F1F atau #2C2C2A.

Badge/Pill status:
  border-radius 999px, padding 3px 10px, font Inter 10–11px 600,
  uppercase. Warna: sesuai konteks (lihat warna semantik).

Divider tabel:
  border-bottom: 0.5px solid #E0DDD6 (terang) atau #1F1F1F (gelap).
  Tidak menggunakan background alternating row.

LAYOUT & SPACING:
Sistem 8-point grid. Semua spacing kelipatan 8px.
Max-width konten: 1280px, centered.
Container padding horizontal: 32px (desktop), 16–18px (mobile).
Section padding vertikal: 48–96px.
Gap antar komponen: 8px, 12px, 16px, 24px, 32px.
Border-radius skala: 8px (kecil), 12px (medium), 16px (card),
  20–24px (card besar), 999px (pill/rounded-full).

GAYA VISUAL KESELURUHAN:
- Flat design murni. TIDAK ADA: box-shadow, text-shadow,
  glassmorphism (backdrop-filter blur untuk dekorasi),
  neumorphism, gradient background (kecuali sebagai glow ambient
  di hero yang sangat subtle).
- Kontras sangat tinggi. Hitam-putih-krem-kuning neon.
- Marquee/ticker text: animasi scroll horizontal infinite untuk
  nama-nama acara, diletakkan sebagai separator antar section.
- Foto/gambar: ditampilkan full-bleed atau dalam container tanpa
  border dekoratif. Overlay gradien gelap ke bawah untuk
  legibility teks di atas foto.
- Transition/animation: subtle, max 0.2–0.3s ease. Hanya pada
  hover states dan state changes. Tidak ada animasi berlebihan.
```

---

## BLOK 1 — LANDING PAGE (Customer Portal)

```
[SERTAKAN BLOK 0 DI SINI]

Bangun halaman Landing Page FSTVLIST untuk Customer Portal.
Halaman ini adalah pintu masuk utama platform — harus terasa
seperti halaman depan majalah konser premium sekaligus toko tiket
digital yang dipercaya.

=== STRUKTUR HALAMAN ===

1. NAVBAR (fixed, di atas hero)
   - Kiri: logo "FSTVLIST" dalam font Fraunces serif, putih.
   - Tengah: link navigasi "TOUR", "ALBUM", "SHOP" —
     Inter 12px, uppercase, letter-spacing 0.06em, warna
     rgba(255,255,255,0.7). Hover: putih penuh.
   - Kanan: tombol pill outline kuning neon "LOGIN" —
     border 1.5px solid #E8FF00, color #E8FF00. Hover:
     background #E8FF00, color #000.
   - Background navbar: transparan di atas hero (teks tetap terbaca
     karena hero gelap). Bukan sticky dengan background solid.

2. HERO SECTION (height: 100vh, full-bleed)
   - Background: hitam #000 dengan dua glow radial ambient —
     satu di pojok kiri atas (warna ungu rgba(176,160,248,0.2)),
     satu di pojok kanan bawah (warna pink rgba(242,107,158,0.15)).
     Glow ini bukan gradient background dominan — hanya atmosfer
     sangat subtle di pojok, seperti lampu panggung dari jauh.
   - Konten hero diposisikan di kiri bawah, padding bawah 48px:
     a. Eyebrow text: "Platform tiket konser #1 Indonesia"
        → Inter 11px, #E8FF00, uppercase, letter-spacing 0.12em.
     b. Judul utama: "RASAKAN / KONSER / LANGSUNG."
        → Fraunces 900, 80–96px, putih, uppercase, line-height 0.92,
          letter-spacing -0.02em. Tiga baris terpisah. Titik di
          akhir baris terakhir menggunakan warna #E8FF00.
     c. Deskripsi: "Temukan konser terbaik, pilih zona impianmu,
        dan dapatkan e-tiket instan dengan QR code."
        → Inter 14px, rgba(255,255,255,0.6), max-width 300px,
          line-height 1.7. Margin-top 16px.
     d. Dua tombol CTA berdampingan (margin-top 28px):
        - Primer: "JELAJAHI KONSER" → background #E8FF00, color #000
        - Ghost: "CARA KERJA" → outline putih transparan
   - Mini Music Player (pojok kanan bawah hero, absolute):
     Card kecil dengan backdrop-filter: blur(12px) dan
     background rgba(20,20,20,0.85), border 0.5px solid #2C2C2A,
     border-radius 14px. Berisi: thumbnail acara 36x36px
     (gradient sebagai placeholder), nama acara Inter 12px 600,
     info lokasi/tanggal Inter 10px #9E9E9E, dan tiga tombol
     kontrol playback (⏮ ⏸ ⏭) dengan dot kuning sebagai
     indikator "sedang diputar". Lebar minimum 200px.

3. MARQUEE TICKER STRIP
   - Background: #F5F0E8 (krem).
   - Padding vertikal: 14px.
   - Border-top: 0.5px solid #E0DDD6.
   - Konten: nama-nama acara konser dalam font Fraunces 20px 700,
     #000, uppercase. Dipisahkan oleh titik bulat kuning neon
     berdiameter 10px.
   - Animasi: scroll horizontal infinite ke kiri, duration 20s linear.
   - Isi: "Konser Tropis · Neon Lights Fest · Indie Vibes Vol.3 ·
     Summer Sonic ID · Jakarta Sound Fest" diulang 2x untuk loop.

4. DAFTAR KONSER MENDATANG (background #F5F0E8)
   - Section header (2 kolom, space-between):
     Kiri: judul "KONSER / MENDATANG" → Fraunces 700 36px #000
           uppercase, dua baris.
     Kanan: link "LIHAT SEMUA →" → Inter 12px 600 #000 uppercase,
            border-bottom 1.5px solid #000.
   - Grid 3 kolom dengan gap 12px, di bawah header:
     Tiga kartu event dengan struktur:
     a. Gambar/poster acara (aspect-ratio 4/3, overflow hidden,
        border-radius 12px 12px 0 0). Kartu pertama: gradient
        kuning-ungu. Kedua: gradient pink-oranye. Ketiga:
        gradient ungu-hijau. Ada teks inisial event besar di tengah
        sebagai placeholder.
     b. Body kartu (background #000, padding 14px,
        border-radius 0 0 12px 12px):
        - Nama acara: Fraunces 700 16px #fff uppercase.
        - Info: Inter 11px #9E9E9E (venue, kota, tanggal).
        - Baris bawah (space-between):
          Harga: Inter 12px 700 #E8FF00.
          Badge status: pill #E8FF00/pink. "Hot 🔥" atau "Segera".
     c. Hover: gambar scale(1.04) dengan transition 0.3s ease.

5. SPONSOR STRIP (background #000)
   - Grid 5 kolom equal-width.
   - Setiap sel: padding 20px 0, flex center, border-right
     0.5px solid #1F1F1F, border-top 0.5px solid #1F1F1F.
   - Logo/nama sponsor: Inter 11px 700, rgba(255,255,255,0.35),
     uppercase, letter-spacing 0.08em.
   - Nama: Telkomsel · BCA · Tokopedia · GoJek · Kompas.

PERILAKU RESPONSIF:
- Mobile (<768px): grid event cards menjadi 1 kolom, hero text
  scale down proportionally, navbar collapse ke hamburger menu,
  mini player disembunyikan di bawah 480px.
```

---

## BLOK 2 — HALAMAN LOGIN & REGISTRASI

```
[SERTAKAN BLOK 0 DI SINI]

Bangun halaman Login dan Registrasi FSTVLIST dalam satu halaman
dengan tab toggle. Gunakan layout split dua panel (desktop):
panel kiri hitam editorial, panel kanan krem interaktif.

=== STRUKTUR ===

PANEL KIRI (background #000, min-width 320px, flex 1):
- Glow radial ambient di pojok kiri atas (ungu, radius 350px,
  opacity sangat rendah) dan kanan bawah (pink, opacity sangat
  rendah). Sama seperti hero landing page.
- Logo "FSTVLIST" di kiri atas: Fraunces 22px 700 putih.
- Konten tengah (vertikal centered, padding 32px):
  a. Eyebrow: "Selamat datang kembali" → Inter 11px #E8FF00
     uppercase letter-spacing 0.1em.
  b. Judul tiga baris: "MASUK. / PILIH. / RASAKAN."
     → Fraunces 900, besar (clamp 36px–52px), uppercase,
       line-height 0.95. Baris terakhir "RASAKAN." dalam #E8FF00.
  c. Deskripsi: "Ribuan konser menunggumu. Login untuk pesan tiket,
     lihat riwayat, dan simpan kursi favoritmu."
     → Inter 13px rgba(255,255,255,0.5) line-height 1.7 max-width 280px.
  d. Tiga preview event card kecil (row, gap 8px, margin-top 28px):
     Setiap card: background #1A1A1A, border 0.5px solid #2C2C2A,
     border-radius 10px, padding 10px 12px. Isi:
     - Dot warna (8px bulat, sesuai warna aksen zona).
     - Nama event: Inter 10px 700 uppercase putih.
     - Tanggal: Inter 9px #9E9E9E.
     Tiga event: "Konser Tropis" (dot kuning), "Neon Lights"
     (dot pink), "Indie Vibes" (dot ungu).
- Footer kiri bawah: "© 2026 FSTVLIST" Inter 10px
  rgba(255,255,255,0.2).

PANEL KANAN (background #F5F0E8, width 400px, padding 40px 36px):
- Judul: "MASUK KE / AKUN KAMU" → Fraunces 700 30px #000
  uppercase line-height 1.05.
- Sub: "Belum punya akun? Daftar gratis dalam 30 detik."
  → Inter 13px #5F5E5A, margin-bottom 32px.

- TAB TOGGLE (Masuk / Daftar):
  Container: background #E0DDD6, border-radius 999px, padding 4px.
  Tab aktif: background #000, color #fff, border-radius 999px.
  Tab inaktif: color #5F5E5A, cursor pointer.
  Transition: 0.2s ease. Margin-bottom 24px.

- FORM MASUK (tampil default):
  a. Field Email:
     Label: Inter 11px 600 #5F5E5A uppercase letter-spacing 0.06em.
     Input: pill, background #fff, border 1.5px #E0DDD6,
     border-radius 999px, padding 13px 20px, font Inter 14px #000.
     Placeholder: "kamu@email.com" warna #B4B2A9.
     Focus: border-color #000.
  b. Field Password: sama, type password,
     placeholder "••••••••".
  c. Link "Lupa password?": Inter 11px #5F5E5A, text-align right,
     margin-top -8px margin-bottom 20px. Hover: #000.
  d. Tombol MASUK SEKARANG: full-width, background #000,
     color #E8FF00, pill, padding 15px, Inter 14px 700 uppercase.
  e. Divider "atau": garis tipis kiri-kanan, teks Inter 11px #9E9E9E.
  f. Tombol Google: full-width, background #fff, border 1.5px #E0DDD6,
     pill. Isi: dot gradient kuning-pink 14px + teks "Lanjutkan
     dengan Google" Inter 13px 600 #000.
  g. Footer form: "Belum punya akun? Daftar gratis"
     → Inter 12px #9E9E9E, link #000 600, centered.

- FORM DAFTAR (tampil saat tab "Daftar" diklik):
  Sama strukturnya tapi tambahkan field:
  - Nama Lengkap (di atas email)
  - Konfirmasi Password (di bawah password)
  Tombol berubah jadi "DAFTAR SEKARANG".
  Footer: "Sudah punya akun? Masuk di sini".

MOBILE (<768px):
Panel kiri disembunyikan. Hanya tampil panel kanan full-screen
dengan logo FSTVLIST di atas form.
```

---

## BLOK 3 — VISUALISASI VENUE (Stadium Zone Map)

```
[SERTAKAN BLOK 0 DI SINI]

Bangun halaman pemilihan zona tiket FSTVLIST dengan visualisasi
layout venue berbentuk denah stadion konser sesungguhnya.
Ini adalah fitur inti dan paling unik dari platform.

=== HEADER HALAMAN ===
Background #000, padding 14px 18px.
- Kiri: tombol back bulat (diameter 32px, background #1A1A1A,
  ikon panah kiri putih).
- Kanan: nama event "KONSER TROPIS" dalam Fraunces 700 17px putih
  uppercase, dan sub-info "GBK Senayan, Jakarta · 14 Sep 2026"
  dalam Inter 11px #9E9E9E di bawahnya.

=== KARTU VENUE (background #F5F0E8, margin 6px 16px,
    border-radius 18px, padding 14px) ===

LABEL: "PILIH ZONA ANDA" → Inter 11px 600 #5F5E5A uppercase
letter-spacing 0.06em. Margin-bottom 10px.

PETA STADION (SVG, width 100%, viewBox "0 0 360 260"):
Bentuk: oval/elips memanjang horizontal, tampak atas (bird's-eye view).
Susun lapisan dari luar ke dalam dengan z-index yang benar:

Lapisan 1 (terluar) — Shell stadion:
  Ellipse cx=180 cy=130 rx=175 ry=122, fill #E0DDD6.
  Ini adalah bingkai terluar stadion.

Lapisan 2 — Zona REGULER (paling luar):
  Ellipse cx=180 cy=130 rx=160 ry=108, fill #FF5733.
  Kursor pointer. Data-zone="reguler".
  Label "REGULER" di posisi atas oval (y sekitar 35),
  dan "Rp 350.000" di bawahnya. Putih, Inter 9px.

Lapisan 3 — Zona TRIBUNE:
  Ellipse cx=180 cy=130 rx=136 ry=88, fill #B0A0F8.
  Kursor pointer. Data-zone="tribune".
  Label di sisi kiri (x sekitar 44) dan kanan (x sekitar 316):
  "TRIBUNE" Inter 8px 700 #26215C dan harga di bawahnya.

Lapisan 4 — Zona FESTIVAL:
  Ellipse cx=180 cy=130 rx=108 ry=66, fill #F26B9E.
  Kursor pointer. Data-zone="festival".
  Label di sisi kiri (x sekitar 96) dan kanan (x sekitar 264):
  "FESTIVAL" Inter 9px 700 #fff.

Lapisan 5 — Zona VIP (paling dalam, paling dekat panggung):
  Ellipse cx=180 cy=118 rx=76 ry=44, fill #E8FF00.
  Posisi sedikit lebih ke atas (cy=118) untuk memberi ruang panggung.
  Kursor pointer. Data-zone="vip".
  Label "VIP" Inter 10px 700 #2C2C2A dan "Rp 1.850.000" di bawahnya.

Lapisan 6 — Panggung (paling bawah peta):
  Rect x=140 y=192 width=80 height=36 rx=6, fill #000.
  Teks "PANGGUNG" di tengah rect: fill #E8FF00, Inter 9px 700,
  letter-spacing 1px, text-anchor middle.

Indikator seleksi:
  Untuk setiap zona, siapkan ellipse tersembunyi (display:none)
  dengan ukuran sama + offset 3px, fill:none, stroke:#000,
  stroke-width:3. Tampilkan saat zona diklik.

INTERAKSI KLIK ZONA:
Saat zona diklik:
1. Semua selection ring disembunyikan.
2. Selection ring zona yang diklik ditampilkan.
3. Popup info zona di bawah peta diupdate dan ditampilkan.
4. Tombol CTA di paling bawah diaktifkan dan teksnya diupdate.

ZONA HABIS (contoh: satu zona Reguler atau Tribune bisa dibuat
sebagai sold-out demo):
  Ubah fill ke #9E9E9E, opacity 0.6, kursor not-allowed,
  tambahkan teks "HABIS" di atas label zona.
  Klik tidak memicu interaksi apapun.

LEGENDA WARNA (di bawah peta, flex row wrap, gap 8px):
Setiap item: dot bulat 10px warna zona + label Inter 10px #5F5E5A.
Items: VIP (kuning) · Festival (pink) · Tribune (ungu) ·
Reguler (oranye) · Habis (abu-abu).

=== POPUP INFO ZONA (card hitam, margin 10px 16px,
    border-radius 16px, padding 14px 16px) ===
Tersembunyi secara default, muncul setelah zona diklik.

Konten:
- Nama zona: Fraunces 700 20px #fff.
- Baris harga (border-top 0.5px #2C2C2A, padding-top 10px):
  Label "Harga per tiket" Inter 12px #9E9E9E, dan harga dalam
  Fraunces 22px #E8FF00 di kanan.
- Baris sisa kuota: label + angka Inter 13px 600 #fff.
- Baris jumlah tiket: label "Jumlah tiket" + stepper.
  Stepper: tombol minus dan plus (lingkaran 28px, background
  #1A1A1A, warna teks putih), angka di tengah Inter 16px 700.
  Min: 1, Maks: 4 (sesuai FR-06).

=== TOMBOL CTA (padding 12px 16px di bawah popup) ===
Disabled state (default sebelum pilih zona):
  Background #2C2C2A, color #9E9E9E, text "Pilih zona dahulu".
  Tidak bisa diklik.
Enabled state (setelah zona dipilih):
  Background #E8FF00, color #000, pill, full-width.
  Teks dinamis: "Pesan {qty} tiket · {nama zona}"
  Contoh: "Pesan 2 tiket · Zona VIP"
```

---

## BLOK 4 — WAITING ROOM (Antrean Digital)

```
[SERTAKAN BLOK 0 DI SINI]

Bangun halaman Waiting Room FSTVLIST yang ditampilkan saat traffic
tinggi dan mode antrean diaktifkan admin (FR-07). Halaman ini harus
mengurangi kecemasan pengguna dengan memberikan informasi posisi
antrean yang transparan dan visualisasi kemajuan yang menenangkan.

=== LAYOUT ===
Background: #000 (hitam penuh).
Centered content, max-width 380px, padding 20px.
Logo "FSTVLIST" Fraunces serif 18px di paling atas, center.

=== KARTU UTAMA (background #F5F0E8, border-radius 24px,
    padding 32px 28px, margin-top 20px) ===

1. ANIMASI VINYL (centered, margin-bottom 24px):
   Lingkaran diameter 100px dengan background conic-gradient
   yang menciptakan efek alur piringan hitam (alternating dark grey).
   Animasi: berputar (rotate 360deg) secara infinite, 4s linear.
   Di tengah: lingkaran kecil 28px background #E8FF00 (label tengah
   vinyl), dengan titik hitam 8px di pusatnya.

2. LABEL SECTION: "RUANG TUNGGU"
   → Inter 12px 600 #9E9E9E uppercase letter-spacing 0.08em.

3. JUDUL: "HAMPIR / GILIRANMU!"
   → Fraunces 700 28px #000 uppercase line-height 1.1. Dua baris.

4. SUB-INFO ACARA: "Konser Tropis · GBK Senayan"
   → Inter 13px #5F5E5A, margin-bottom 24px.

5. KOTAK NOMOR ANTREAN (background #000, border-radius 16px,
   padding 20px, text-align center):
   - Label: "Nomor antreanmu" Inter 10px #9E9E9E uppercase.
   - Angka antrean: "#4.821" → Fraunces 900 48px #E8FF00 line-height 1.
   - Sub: "dari 6.200 pengguna aktif" Inter 11px #5F5E5A margin-top 6px.

6. PROGRESS BAR (margin-top 16px):
   Header (space-between):
     "Progres antrean" Inter 11px #5F5E5A · "63%" Inter 11px 700 #000.
   Bar: background #E0DDD6, height 6px, border-radius 999px.
   Fill: background #000, width 63%, border-radius 999px.
   Animasi fill: ease-in dari 0% ke 63% selama 1.5s on load.

7. DUA STAT KOTAK (grid 2 kolom, gap 10px, margin-top 12px):
   Setiap kotak: background #fff, border-radius 12px, padding 12px,
   border 0.5px solid #E0DDD6, text-align center.
   - Kotak kiri: angka "1.379" Fraunces 22px #000, label "Di depanmu"
     Inter 10px #9E9E9E uppercase.
   - Kotak kanan: angka "3.442" Fraunces 22px #000, label "Sudah masuk".

8. ESTIMASI WAKTU (background #E8FF00, border-radius 12px,
   padding 12px 16px, display flex, space-between, margin-top 10px):
   - Label "Estimasi giliran" Inter 12px 600 #000.
   - Waktu "~12 menit" Fraunces 22px 700 #000.

=== COUNTDOWN TIMER (di luar kartu utama, margin-top 12px,
    background #1A1A1A, border-radius 12px, padding 14px 20px,
    display flex space-between, max-width 380px) ===
- Teks "Jangan tutup halaman ini" Inter 12px #9E9E9E.
- Timer "12:00" Fraunces 20px 700 #E8FF00.
- Timer berjalan mundur secara real-time (JavaScript setInterval).
- Saat sisa waktu < 60 detik: warna timer berubah ke #F26B9E.

=== CATATAN DI BAWAH ===
Inter 11px rgba(255,255,255,0.4) line-height 1.6 text-center:
"Posisimu akan tersimpan selama 15 menit jika browser ditutup.
Refresh halaman untuk memperbarui posisi antrean."
```

---

## BLOK 5 — E-TIKET QR CODE

```
[SERTAKAN BLOK 0 DI SINI]

Bangun halaman konfirmasi pembayaran dan tampilan e-tiket FSTVLIST.
Ini adalah halaman paling personal dan harus membangun rasa
"kepemilikan" — dirancang menyerupai tiket konser fisik premium
secara visual (skeuomorphic-light approach).

=== STRUKTUR HALAMAN ===
Background: #F5F0E8 (krem, berbeda dari mayoritas halaman lain).
Max-width 380px, centered, mobile-first.

1. SUCCESS HEADER STRIP (background #000, padding 18px,
   text-align center):
   - Ikon centang dalam lingkaran kuning neon:
     Lingkaran 40px background #E8FF00, ikon ✓ hitam di tengah,
     centered, margin-bottom 8px.
   - Teks "Pembayaran berhasil" Inter 13px 600 #fff.
   - Sub "E-tiket telah dikirim ke email anda" Inter 11px #9E9E9E.

2. KARTU TIKET UTAMA (background #fff, border-radius 20px,
   overflow hidden, border 0.5px solid #E0DDD6,
   margin 18px 16px):

   BAGIAN ATAS KARTU (background #000, padding 20px 20px 16px):
   - Nama event: "KONSER TROPIS" → Fraunces 700 24px #fff uppercase
     line-height 1.1.
   - Info venue: "GBK Senayan, Jakarta · 14 Sep 2026, 19.00 WIB"
     → Inter 12px #9E9E9E margin-top 6px.
   - Badge zona: "ZONA VIP" → pill, background #E8FF00, color #000,
     Inter 11px 700, padding 4px 12px, border-radius 999px,
     display inline-block, margin-top 12px.

   GARIS PERFORASI (memisahkan bagian atas dan bawah kartu):
   Simulasi sobekan tiket fisik:
   - Border-top: 2px dashed #D3D1C7 pada elemen horizontal divider.
   - Di ujung kiri dan kanan divider: dua lingkaran diameter 20px,
     background #F5F0E8 (sama dengan background halaman),
     positioned absolute dengan transform translateY(-50%).
     Efek ini menciptakan ilusi "lubang" di sisi kartu seperti
     tiket konser fisik yang akan disobek.

   BAGIAN BAWAH KARTU (background #fff, padding 24px 20px 20px,
   text-align center):
   - QR CODE BOX (centered, margin-bottom 16px):
     Container 180x180px, background #000, border-radius 12px,
     padding 12px. Di dalamnya: area QR code 156x156px.
     Untuk placeholder visual QR: buat pola grid berulang dengan
     CSS atau SVG yang mensimulasikan tampilan QR code hitam-putih.
     Di produksi, ini diganti dengan gambar QR code sungguhan.

   - METADATA (border-top 0.5px #E0DDD6, margin-top 16px,
     padding-top 16px, display flex space-between):
     Kiri: label "Atas nama" Inter 10px #9E9E9E uppercase + value
           "Rania Putri" Inter 13px 600 #1A1A1A.
     Kanan (text-right): label "Tiket" + value "2 dari 2".

   - ID TIKET: "ID FSTV-2026-0914-VIP-00231"
     → Inter 10px #B4B2A9, font-variant: tabular-nums,
       letter-spacing 0.05em, margin-top 14px, centered.

3. DUA TOMBOL AKSI (flex row, gap 10px, padding 0 16px 20px):
   - Primer "UNDUH PDF": flex 1, background #000, color #E8FF00,
     pill, padding 14px, Inter 13px 700 uppercase.
   - Sekunder "RIWAYAT": flex 1, background transparan,
     border 1.5px solid #1A1A1A, color #1A1A1A, pill, padding 13px.
```

---

## BLOK 6 — GATE VALIDATOR DASHBOARD

```
[SERTAKAN BLOK 0 DI SINI]

Bangun halaman Gate Validator FSTVLIST untuk petugas gerbang
konser. Prioritas utama: kecepatan baca, kejelasan status,
dan kemudahan penggunaan satu tangan di smartphone.
Target: petugas bisa membaca hasil validasi dalam < 2 detik.

=== LAYOUT ===
Background: #000. Mobile-first, max-width 400px.

1. TOP BAR (padding 16px 18px, flex space-between):
   - Label "GATE VALIDATOR" Inter 13px 700 #fff uppercase
     letter-spacing 0.05em.
   - Info petugas "Gerbang A · Agus" Inter 11px #9E9E9E.

2. AREA SCANNER QR (margin 8px 18px, aspect-ratio 1:1,
   background #111, border-radius 20px, overflow hidden,
   position relative):
   - Di tengah: viewfinder QR — persegi 200x200px dengan
     EMPAT SUDUT berbentuk "bracket" siku-siku (bukan border penuh).
     Setiap sudut: dua garis siku 24px, ketebalan 4px,
     warna #E8FF00 (kuning neon), border-radius 12px di sudut luar.
     Efek ini adalah UI scanner standar yang langsung dikenali.
   - Hint text di bawah viewfinder: "Arahkan kamera ke kode QR"
     Inter 11px #B4B2A9, absolute bottom 16px centered.
   - Opsional: animasi garis scan horizontal (linear gradient
     kuning tipis) yang bergerak atas-bawah infinite di dalam
     viewfinder untuk menunjukkan scanner aktif.

3. CARD HASIL VALIDASI (margin 14px 18px, border-radius 18px,
   padding 18px):

   STATE VALID (background #5DCAA5 — hijau solid):
   - Baris atas (flex, align-center, gap 10px):
     Lingkaran 36px background rgba(0,0,0,0.15), ikon ✓ besar
     warna #04342C (hijau tua) di dalam.
     Teks "Tiket valid" → Fraunces 700 18px #04342C.
   - Detail (border-top 1px solid rgba(0,0,0,0.12), margin-top 10px,
     padding-top 10px, Inter 12px #085041):
     Tiga baris key-value (flex space-between):
     "Nama" → "Rania Putri" bold
     "Zona" → "VIP" bold
     "ID tiket" → "FSTV-...-00231" bold

   STATE TOLAK/SUDAH DIGUNAKAN (background #E24B4A — merah solid):
   - Ikon ✕ dalam lingkaran, warna #501313 (merah sangat gelap).
   - Teks "Tiket ditolak" → Fraunces 700 18px #501313.
   - Detail alasan penolakan:
     "Alasan" → "Tiket sudah digunakan" bold
     "Dipindai pada" → "14 Sep 2026, 18.42" bold
     "Zona" → "VIP" bold

   STATE ERROR/TIDAK DIKENAL (background #1A1A1A, border 1px
   solid #E24B4A):
   - Ikon ⚠ kuning.
   - Teks "QR tidak dikenal" → Fraunces 700 18px #fff.
   - Sub: "Kode QR tidak terdaftar dalam sistem." Inter 12px #9E9E9E.

   PENTING: Tiga state ini harus sangat berbeda secara visual —
   hijau solid vs merah solid vs card gelap. Tidak ada ambiguitas.
   Petugas harus bisa membaca status dari jarak lengan, sekilas.

4. STATISTIK HARIAN (flex row, gap 8px, margin 0 18px 18px):
   Tiga kotak (flex 1 masing-masing):
   Setiap kotak: background #1A1A1A, border-radius 12px,
   padding 10px 12px.
   - Kotak 1: angka "1.204" Fraunces 18px 800 #5DCAA5 (hijau),
     label "Diterima" Inter 10px #9E9E9E uppercase.
   - Kotak 2: angka "18" Fraunces 18px 800 #E24B4A (merah),
     label "Ditolak".
   - Kotak 3: angka "1.222" Fraunces 18px 800 #fff,
     label "Total Scan".
```

---

## BLOK 7 — ADMIN PANEL DASHBOARD

```
[SERTAKAN BLOK 0 DI SINI]

Bangun Admin Panel Dashboard FSTVLIST untuk penyelenggara acara
(Dimas, event manager). Desktop-first. Layout: sidebar kiri +
main content kanan. Dark mode penuh — background #0D0D0D.

=== SIDEBAR (width 180px, background #000,
    border-right 0.5px solid #1F1F1F, height 100vh) ===

Header sidebar (padding 0 16px 20px, border-bottom 0.5px #1F1F1F):
- Logo "FSTVLIST" Fraunces 700 18px #fff.
- Sub "Admin Panel" Inter 9px #9E9E9E uppercase letter-spacing 0.08em.

Navigasi (margin-top 16px):
Setiap item: padding 9px 16px, Inter 12px 500, flex align-center
gap 10px. Border-left 3px solid transparan.
- AKTIF: color #fff, border-left-color #E8FF00,
  background rgba(232,255,0,0.05).
- HOVER: color #fff, background rgba(255,255,255,0.04).
- INAKTIF: color #9E9E9E.
Items: Dashboard (aktif) · Acara · Venue & Zona · Tiket ·
Transaksi · Laporan · Pengguna.

Footer sidebar (margin-top auto, padding 0 16px,
border-top 0.5px #1F1F1F):
- Avatar "D" (28px bulat, background #E8FF00, Inter 11px 700 #000)
  + nama "Dimas (Admin)" Inter 11px #9E9E9E.

=== MAIN CONTENT (flex 1, background #0D0D0D, overflow-y auto) ===

TOP BAR (padding 14px 20px, border-bottom 0.5px #1F1F1F,
flex space-between):
- Judul "Dashboard" Fraunces 700 20px #fff.
- Kanan: badge "● LIVE" (background #E8FF00, color #000,
  Inter 10px 700 uppercase, border-radius 4px) +
  tombol "+ Tambah Acara" (pill, background #E8FF00, color #000,
  Inter 12px 700).

STAT CARDS (grid 4 kolom, gap 12px, padding 16px 20px):
Setiap card: background #141414, border-radius 12px, padding 14px,
border 0.5px solid #1F1F1F.
- Card 1: label "Total tiket terjual", nilai "3.842"
  Fraunces 24px #E8FF00 (kuning neon), delta "↑ +128 hari ini"
  Inter 10px #5DCAA5 (hijau).
- Card 2: label "Pendapatan", nilai "Rp 2,4M" Fraunces #fff,
  delta "+Rp 48jt hari ini" #5DCAA5.
- Card 3: label "Transaksi tertunda", nilai "14" Fraunces #F09595
  (merah muda — sinyal peringatan), delta "↑ +3 sejak kemarin"
  #E24B4A.
- Card 4: label "Kapasitas terisi", nilai "76%" Fraunces #fff,
  delta "3.842 / 5.000 kursi" Inter 10px #9E9E9E.

TABEL ACARA AKTIF (padding 0 20px 16px):
Header section (flex space-between, margin-bottom 10px):
"DAFTAR ACARA AKTIF" Inter 12px 600 #9E9E9E uppercase +
"Lihat semua →" Inter 11px #E8FF00.

Table: width 100%, border-collapse collapse.
Thead: Inter 10px 600 #5F5E5A uppercase letter-spacing 0.06em,
border-bottom 0.5px #1F1F1F.
Kolom: Nama Acara · Venue · Tanggal · Terjual · Status · Aksi.
Tbody: Inter 12px #fff, border-bottom 0.5px #1A1A1A.
Row hover: background rgba(255,255,255,0.02).

Badge status di kolom Status:
- "Aktif" → background rgba(93,202,165,0.15), color #5DCAA5.
- "Segera" → background rgba(232,255,0,0.12), color #E8FF00.
- "Draft" → background #1A1A1A, color #9E9E9E.
Semua badge: Inter 10px 600 uppercase, padding 3px 8px,
border-radius 4px.

Link "Edit →" di kolom Aksi: Inter 12px #E8FF00, cursor pointer.

TABEL OKUPANSI ZONA (padding 0 20px 20px):
Header: "OKUPANSI ZONA · KONSER TROPIS".
Kolom: Zona (dengan dot warna) · Kapasitas · Terjual · Sisa · Terisi.

Kolom "Terisi" berisi progress bar inline:
- Container flex align-center gap 8px.
- Bar background: #2C2C2A, height 5px, border-radius 999px, flex 1.
- Fill: warna sesuai zona (kuning/pink/ungu/oranye), height 100%.
- Persentase: Inter 10px #9E9E9E, min-width 28px text-right.

Data contoh:
VIP: 200 kapasitas, 158 terjual, 42 sisa, 79%.
Festival: 1500, 1372, 128, 91%.
Tribune: 1300, 1213, 87, 93%.
Reguler: 2000, 1099, 901, 55%.
```

---

## BLOK 8 — SISTEM NOTIFIKASI & POP-UP

```
[SERTAKAN BLOK 0 DI SINI]

Bangun sistem notifikasi FSTVLIST yang mencakup empat tipe:
Toast (muncul sementara di pojok), Modal (overlay konfirmasi),
Banner inline (di dalam halaman), dan State kosong/error.

=== A. TOAST NOTIFICATIONS ===
Posisi: fixed, top 20px, right 20px, z-index 9999.
Stack secara vertikal dengan gap 10px (toast terbaru di atas).
Animasi masuk: translateX dari +120% ke 0, opacity 0→1, 0.3s ease.
Animasi keluar: translateX ke +120%, opacity 1→0, 0.3s ease.
Auto-dismiss: 5 detik setelah muncul.

Struktur setiap toast:
- Container: padding 14px 16px, border-radius 14px, min-width 280px,
  max-width 340px, border 0.5px solid (warna sesuai tipe).
- Ikon (kiri): lingkaran 28px dengan background semi-transparan.
- Body (tengah, flex 1): title Inter 13px 700 + message 11px #9E9E9E.
- Tombol × (kanan): Inter 16px #5F5E5A, hover #fff, cursor pointer.

Empat tipe toast:
1. SUKSES: background #0D1F1A, border #1D9E75.
   Ikon ✓ dalam lingkaran rgba(29,158,117,0.2), warna #5DCAA5.
   Contoh: "Tiket berhasil dipesan! — E-tiket dikirim ke email kamu."

2. PERINGATAN: background #1F180D, border #E8FF00.
   Ikon ⚡ dalam lingkaran rgba(232,255,0,0.15), warna #E8FF00.
   Contoh: "Sisa waktu 3 menit — Segera selesaikan pembayaran."

3. ERROR: background #1F0D0D, border #E24B4A.
   Ikon ✕ dalam lingkaran rgba(226,75,74,0.2), warna #E24B4A.
   Contoh: "Pembayaran gagal — Transaksi tidak dapat diproses."

4. INFO: background #0D0D1F, border #B0A0F8.
   Ikon ℹ dalam lingkaran rgba(176,160,248,0.2), warna #B0A0F8.
   Contoh: "Pembaruan antrean — Posisimu naik ke #3.201."

=== B. MODAL OVERLAY ===
Overlay: position fixed, inset 0, background rgba(0,0,0,0.8),
backdrop-filter blur(4px), z-index 100.
Modal box: background #F5F0E8, border-radius 24px, padding 32px 28px,
max-width 400px, centered (flex align+justify center).
Animasi: scale dari 0.94→1, opacity 0→1, 0.25s ease.
Klik di luar box: menutup modal.

Tiga varian modal:

1. MODAL KONFIRMASI BATALKAN:
   Ikon: lingkaran 52px background rgba(226,75,74,0.15), ikon 🗑 22px.
   Judul: "BATALKAN PESANAN?" Fraunces 700 24px #000 uppercase centered.
   Pesan: "Kamu akan membatalkan pemesanan 2 tiket Zona VIP — Konser
   Tropis. Tindakan ini tidak bisa dibatalkan."
   Inter 13px #5F5E5A centered line-height 1.7.
   Dua tombol (flex row, gap 10px):
   - Kiri "Kembali": ghost, border #000, color #000.
   - Kanan "Ya, batalkan": background #E24B4A, color #fff.
   Keduanya pill, full flex.

2. MODAL TIMER PEMBAYARAN HABIS:
   Angka countdown besar: Fraunces 36px 700 #000 centered.
   Animasi berjalan real-time mundur dari 10:00.
   Saat < 60 detik: warna angka berubah ke #E24B4A.
   Progress bar drain: background #E0DDD6, fill #000 dari 100%→0%
   selama durasi timer, border-radius 999px, height 6px.
   Sub teks: "Selesaikan pembayaran sebelum waktu habis."
   Judul: "WAKTU HAMPIR HABIS!" Fraunces 20px #000 uppercase.
   Dua tombol: "Batalkan" (ghost) + "Bayar sekarang" (filled hitam).

3. MODAL SUKSES PEMBAYARAN:
   Ikon: lingkaran 52px background rgba(232,255,0,0.15), ikon 🎉.
   Judul: "PEMBAYARAN / BERHASIL!" Fraunces 700 24px #000 dua baris.
   Pesan: "E-tiket untuk Konser Tropis · Zona VIP telah dikirim ke
   email kamu. Tunjukkan QR code saat masuk venue."
   Dua tombol: "Tutup" (ghost) + "Lihat e-tiket" (filled hitam).

=== C. BANNER INLINE ===
Muncul di dalam halaman (bukan overlay). Untuk kondisi sistem.
Setiap banner: padding 12px 16px, border-radius 12px, border 0.5px.
Flex align-center gap 12px.

1. BANNER HIGH DEMAND (background #1F180D, border #E8FF00):
   Ikon 🔥, judul "Tiket hampir habis!" Inter 13px 700 #fff,
   deskripsi "Zona VIP tersisa 42 tiket dari 200. Pesan sekarang."
   Inter 11px #9E9E9E, tombol "Pesan →" Inter 11px 700 #E8FF00.

2. BANNER ANTREAN AKTIF (background #0D0D1F, border #B0A0F8):
   Ikon 🕐, judul "Traffic tinggi — antrean aktif",
   deskripsi "Kamu masuk ruang tunggu. Estimasi ~12 menit."
   Tombol "Pantau →" warna #B0A0F8.

3. BANNER ZONA HABIS (background #1F0D0D, border #E24B4A):
   Ikon ✕, judul "Zona Festival telah habis",
   deskripsi "Coba zona Tribune atau Reguler yang masih tersedia."
   Tombol "Lihat zona →" warna #E24B4A.

=== D. EMPTY STATE ===
Saat tidak ada data (riwayat kosong, tidak ada acara aktif, dll):
Container: text-align center, padding 48px.
Ikon: karakter emoji atau SVG sederhana (🎵 untuk tiket, 📋 untuk data).
Judul: Fraunces 22px #000 uppercase (di bg terang) atau #fff (bg gelap).
Sub: Inter 13px #9E9E9E line-height 1.7.
CTA: tombol pill sesuai konteks.
Contoh: "BELUM ADA TIKET" · "Mulai jelajahi konser dan beli tiket
pertamamu sekarang." · Tombol "Jelajahi Konser".
```

---

## CATATAN TEKNIS UNTUK DEVELOPER

```
STACK YANG DIREKOMENDASIKAN:
- Frontend  : Next.js 14+ (App Router) atau Laravel Blade + Alpine.js
- Styling   : Tailwind CSS dengan custom config untuk design tokens,
              atau plain CSS custom properties.
- Admin     : Laravel Filament (sesuai PRD)
- State     : Zustand (React) atau Alpine.js stores (Laravel)
- Animasi   : CSS transitions/animations native (tidak perlu library
              berat — desain ini flat dan minimal animasi)

CSS CUSTOM PROPERTIES (tempatkan di :root):
--color-black     : #000000;
--color-cream     : #F5F0E8;
--color-yellow    : #E8FF00;
--color-surface-1 : #1A1A1A;
--color-surface-2 : #141414;
--color-surface-3 : #0D0D0D;
--color-border-d  : #2C2C2A;
--color-border-l  : #E0DDD6;
--color-text-muted: #9E9E9E;
--color-vip       : #E8FF00;
--color-festival  : #F26B9E;
--color-tribune   : #B0A0F8;
--color-reguler   : #FF5733;
--color-success   : #5DCAA5;
--color-error     : #E24B4A;
--font-display    : 'Fraunces', serif;
--font-ui         : 'Inter', sans-serif;
--radius-pill     : 999px;
--radius-card     : 16px;
--radius-card-lg  : 20px;

AKSESIBILITAS:
- Semua tombol interaktif harus visible focus ring.
- Kontras teks minimum 4.5:1 (WCAG AA).
- Animasi: tambahkan @media (prefers-reduced-motion: reduce)
  untuk menonaktifkan semua animasi bagi pengguna yang membutuhkan.
- Zona venue SVG harus memiliki aria-label dan role="button"
  pada setiap zona yang dapat diklik.
- State valid/tolak Gate Validator TIDAK boleh hanya mengandalkan
  warna — tambahkan ikon dan teks yang jelas sebagai backup
  untuk pengguna dengan color blindness.
```

---

*Dokumen ini mencakup 8 blok prompt + catatan teknis untuk
seluruh halaman FSTVLIST. Gunakan Blok 0 sebagai header wajib
di setiap prompt individu.*
