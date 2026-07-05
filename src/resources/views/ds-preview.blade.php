<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FSTVLIST — Design System Preview</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,700;0,9..144,900&family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700&display=swap" rel="stylesheet">

    <style>
        /* ═══ Inline Variables (same as design-system.css) ═══ */{{ file_get_contents(resource_path('css/design-system.css')) }}
    </style>
</head>
<body class="ds ds-bg-primary ds-text-primary" x-data="previewApp()" style="font-family: 'Inter', var(--font-primary);">

    <!-- ═══════════ HEADER ═══════════ -->
    <header class="ds-bg-secondary ds-border-b ds-sticky ds-z-50" style="position:sticky;top:0;z-index:50;padding:var(--space-4) var(--space-6);display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:var(--space-3);">
            <span style="font-family:'Fraunces',serif;font-size:1.5rem;font-weight:900;">FSTV<span style="display:inline-block;width:6px;height:6px;background:var(--color-brand);margin-left:2px;vertical-align:middle;"></span>LIST</span>
            <span class="ds-badge ds-badge--brand">DESIGN SYSTEM v1</span>
        </div>
        <div style="display:flex;align-items:center;gap:var(--space-4);">
            <span class="ds-text-small ds-text-secondary">🌗 Toggle Dark Mode</span>
            <button @click="toggleDark()" style="position:relative;width:48px;height:26px;border-radius:var(--radius-pill);background:var(--color-bg-tertiary);border:1px solid var(--color-border);cursor:pointer;transition:all var(--transition-fast);" :style="dark ? 'background:var(--color-brand);border-color:var(--color-brand)' : ''">
                <span style="position:absolute;top:2px;width:20px;height:20px;border-radius:50%;background:var(--color-text-primary);transition:transform var(--transition-fast);" :style="dark ? 'transform:translateX(22px);background:var(--color-brand-text)' : 'left:2px'"></span>
            </button>
        </div>
    </header>

    <!-- ═══════════ SIDEBAR + CONTENT LAYOUT ═══════════ -->
    <div style="display:flex;min-height:calc(100vh - 60px);">
        <!-- Sidebar -->
        <aside class="ds-sidebar" style="position:relative;flex-shrink:0;" :class="sidebarOpen && 'ds-sidebar--open'">
            <div class="ds-sidebar__header" style="padding:var(--space-5) var(--space-4);">
                <span class="ds-sidebar__logo">FSTV<span class="ds-sidebar__logo-dot"></span>LIST</span>
            </div>
            <ul class="ds-sidebar__nav">
                <li class="ds-sidebar__nav-item">
                    <a href="#ds-buttons" class="ds-sidebar__nav-link ds-sidebar__nav-link--active">
                        <span class="ds-sidebar__nav-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/></svg>
                        </span> Tombol
                    </a>
                </li>
                <li class="ds-sidebar__nav-item">
                    <a href="#ds-stepper" class="ds-sidebar__nav-link">
                        <span class="ds-sidebar__nav-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4" stroke-width="2"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke-width="2"/></svg>
                        </span> Stepper
                    </a>
                </li>
                <li class="ds-sidebar__nav-item">
                    <a href="#ds-badges" class="ds-sidebar__nav-link">
                        <span class="ds-sidebar__nav-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M12 6v6l4 2" stroke-width="2"/></svg>
                        </span> Badge
                    </a>
                </li>
                <li class="ds-sidebar__nav-item">
                    <a href="#ds-form" class="ds-sidebar__nav-link">
                        <span class="ds-sidebar__nav-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke-width="2"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke-width="2"/></svg>
                        </span> Form
                    </a>
                </li>
                <li class="ds-sidebar__nav-item">
                    <a href="#ds-card" class="ds-sidebar__nav-link">
                        <span class="ds-sidebar__nav-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" stroke-width="2"/><path d="M1 10h22" stroke-width="2"/></svg>
                        </span> Ringkasan
                    </a>
                </li>
                <li class="ds-sidebar__nav-item">
                    <a href="#ds-success" class="ds-sidebar__nav-link">
                        <span class="ds-sidebar__nav-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke-width="2"/><polyline points="22 4 12 14.01 9 11.01" stroke-width="2"/></svg>
                        </span> Sukses
                    </a>
                </li>
            </ul>
        </aside>

        <!-- CONTENT AREA -->
        <main class="ds-content" style="flex:1;padding:var(--space-8) var(--space-6);max-width:960px;">

            <!-- ═══════════ SECTION: BUTTONS ═══════════ -->
            <section id="ds-buttons" style="margin-bottom:var(--space-16);">
                <h2 style="font-family:'Fraunces',serif;font-weight:900;font-size:var(--text-heading-1);margin-bottom:var(--space-2);">🎛 Tombol (Button)</h2>
                <p class="ds-text-small ds-text-secondary" style="margin-bottom:var(--space-6);">Semua varian tombol dengan state hover, active, disabled, dan fokus.</p>

                <!-- Variants -->
                <div class="ds-card-summary ds-card-summary--elevated" style="margin-bottom:var(--space-6);">
                    <p class="ds-card-summary__title">Varian Tombol</p>
                    <div style="display:flex;flex-wrap:wrap;gap:var(--space-3);">
                        <button class="ds-btn ds-btn--primary">Primary</button>
                        <button class="ds-btn ds-btn--secondary">Secondary</button>
                        <button class="ds-btn ds-btn--accent">Accent / CTA</button>
                        <button class="ds-btn ds-btn--danger">Danger</button>
                        <button class="ds-btn ds-btn--ghost">Ghost</button>
                        <button class="ds-btn ds-btn--upgrade">⭐ Upgrade</button>
                    </div>
                </div>

                <!-- States -->
                <div class="ds-card-summary ds-card-summary--elevated" style="margin-bottom:var(--space-6);">
                    <p class="ds-card-summary__title">State Tombol Primary</p>
                    <div style="display:flex;flex-wrap:wrap;gap:var(--space-3);align-items:center;">
                        <button class="ds-btn ds-btn--primary">Default (hover me)</button>
                        <button class="ds-btn ds-btn--primary" disabled>Disabled</button>
                        <span class="ds-text-small ds-text-secondary">↑ Hover untuk lihat efek</span>
                    </div>
                </div>

                <!-- Sizes -->
                <div class="ds-card-summary ds-card-summary--elevated" style="margin-bottom:var(--space-6);">
                    <p class="ds-card-summary__title">Ukuran Tombol</p>
                    <div style="display:flex;flex-wrap:wrap;gap:var(--space-3);align-items:center;">
                        <button class="ds-btn ds-btn--primary ds-btn--sm">Small</button>
                        <button class="ds-btn ds-btn--primary">Default</button>
                        <button class="ds-btn ds-btn--primary ds-btn--lg">Large</button>
                    </div>
                </div>

                <!-- With Icons -->
                <div class="ds-card-summary ds-card-summary--elevated" style="margin-bottom:var(--space-6);">
                    <p class="ds-card-summary__title">Tombol + Ikon</p>
                    <div style="display:flex;flex-wrap:wrap;gap:var(--space-3);">
                        <button class="ds-btn ds-btn--primary">
                            <svg class="ds-btn__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                            Beli Tiket
                        </button>
                        <button class="ds-btn ds-btn--secondary">
                            <svg class="ds-btn__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Unduh PDF
                        </button>
                        <button class="ds-btn ds-btn--danger">
                            <svg class="ds-btn__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </button>
                    </div>
                </div>

                <!-- Block (full width) -->
                <div class="ds-card-summary ds-card-summary--elevated">
                    <p class="ds-card-summary__title">Tombol Full-Width</p>
                    <button class="ds-btn ds-btn--accent ds-btn--lg ds-btn--block ds-mb-2">🚀 Bayar Sekarang — IDR 2.500.000</button>
                    <button class="ds-btn ds-btn--secondary ds-btn--block">Kembali ke Halaman Utama</button>
                </div>
            </section>

            <!-- ═══════════ SECTION: STEPPER ═══════════ -->
            <section id="ds-stepper" style="margin-bottom:var(--space-16);">
                <h2 style="font-family:'Fraunces',serif;font-weight:900;font-size:var(--text-heading-1);margin-bottom:var(--space-2);">Stepper / Breadcrumb Checkout</h2>
                <p class="ds-text-small ds-text-secondary" style="margin-bottom:var(--space-6);">Lingkaran bernomor terhubung garis progress — klik langkah selesai untuk kembali.</p>

                <!-- Static Demo: All States -->
                <div class="ds-card-summary ds-card-summary--elevated" style="margin-bottom:var(--space-4);">
                    <p class="ds-card-summary__title ds-mb-3">State Statis: Semua Kondisi</p>
                    <div class="ds-stepper">
                        <a href="#" class="ds-stepper__step ds-stepper__step--completed ds-stepper__step--clickable">
                            <span class="ds-stepper__circle">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <span class="ds-stepper__label">Pilih Zona</span>
                        </a>
                        <span class="ds-stepper__connector ds-stepper__connector--completed"></span>
                        <a href="#" class="ds-stepper__step ds-stepper__step--completed ds-stepper__step--clickable">
                            <span class="ds-stepper__circle">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <span class="ds-stepper__label">Data Diri</span>
                        </a>
                        <span class="ds-stepper__connector ds-stepper__connector--completed"></span>
                        <span class="ds-stepper__step ds-stepper__step--active">
                            <span class="ds-stepper__circle">3</span>
                            <span class="ds-stepper__label">Pembayaran</span>
                        </span>
                        <span class="ds-stepper__connector"></span>
                        <span class="ds-stepper__step ds-stepper__step--inactive">
                            <span class="ds-stepper__circle">4</span>
                            <span class="ds-stepper__label">Selesai</span>
                        </span>
                    </div>
                    <p class="ds-text-xs ds-text-tertiary ds-mt-3">
                        <span style="display:inline-flex;align-items:center;gap:4px;margin-right:12px;"><span style="width:12px;height:12px;border-radius:50%;background:var(--stepper-completed-bg);display:inline-block;"></span> Completed</span>
                        <span style="display:inline-flex;align-items:center;gap:4px;margin-right:12px;"><span style="width:12px;height:12px;border-radius:50%;background:var(--stepper-active-bg);display:inline-block;"></span> Active</span>
                        <span style="display:inline-flex;align-items:center;gap:4px;"><span style="width:12px;height:12px;border-radius:50%;border:2px solid var(--stepper-inactive-border);display:inline-block;"></span> Inactive</span>
                    </p>
                </div>

                <!-- All Completed -->
                <div class="ds-card-summary ds-card-summary--elevated" style="margin-bottom:var(--space-4);">
                    <p class="ds-card-summary__title ds-mb-3">Semua Selesai</p>
                    <div class="ds-stepper">
                        <a href="#" class="ds-stepper__step ds-stepper__step--completed ds-stepper__step--clickable">
                            <span class="ds-stepper__circle">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <span class="ds-stepper__label">Pilih Zona</span>
                        </a>
                        <span class="ds-stepper__connector ds-stepper__connector--completed"></span>
                        <a href="#" class="ds-stepper__step ds-stepper__step--completed ds-stepper__step--clickable">
                            <span class="ds-stepper__circle">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <span class="ds-stepper__label">Data Diri</span>
                        </a>
                        <span class="ds-stepper__connector ds-stepper__connector--completed"></span>
                        <a href="#" class="ds-stepper__step ds-stepper__step--completed ds-stepper__step--clickable">
                            <span class="ds-stepper__circle">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <span class="ds-stepper__label">Pembayaran</span>
                        </a>
                        <span class="ds-stepper__connector ds-stepper__connector--completed"></span>
                        <a href="#" class="ds-stepper__step ds-stepper__step--completed ds-stepper__step--clickable">
                            <span class="ds-stepper__circle">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <span class="ds-stepper__label">Selesai</span>
                        </a>
                    </div>
                </div>

                <!-- 🧪 Interactive Demo -->
                <div class="ds-card-summary ds-card-summary--elevated" style="margin-bottom:var(--space-4);">
                    <p class="ds-card-summary__title ds-mb-3">Interactive Demo — Klik langkah selesai / Lanjut-Mundur</p>

                    <div class="ds-stepper" style="margin-bottom:var(--space-4);">
                        <template x-for="(step, i) in steps" :key="i">
                            <!-- Connector before step (skip first) -->
                            <template x-if="i > 0">
                                <span :class="{
                                    'ds-stepper__connector': true,
                                    'ds-stepper__connector--completed': i <= currentStep
                                }"></span>
                            </template>
                            <!-- Step circle + label -->
                            <a href="#"
                               @click.prevent="i < currentStep ? currentStep = i : (i === currentStep && i < steps.length - 1 ? null : null)"
                               :class="{
                                   'ds-stepper__step': true,
                                   'ds-stepper__step--inactive': i > currentStep,
                                   'ds-stepper__step--active': i === currentStep,
                                   'ds-stepper__step--completed': i < currentStep,
                                   'ds-stepper__step--clickable': i < currentStep
                               }">
                                <span class="ds-stepper__circle">
                                    <svg x-show="i < currentStep" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3" style="display:none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span x-show="i >= currentStep" x-text="i + 1"></span>
                                </span>
                                <span class="ds-stepper__label" x-text="step"></span>
                            </a>
                        </template>
                    </div>

                    <p class="ds-text-small ds-text-secondary ds-mb-3">
                        Langkah saat ini: <strong x-text="steps[currentStep]"></strong>
                        &nbsp;(<span x-text="currentStep + 1"></span>/<span x-text="steps.length"></span>)
                    </p>

                    <div style="display:flex;gap:var(--space-2);">
                        <button @click="currentStep = Math.max(0, currentStep - 1)"
                                :disabled="currentStep === 0"
                                class="ds-btn ds-btn--secondary ds-btn--sm">
                            <svg class="ds-btn__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Mundur
                        </button>
                        <button @click="currentStep = Math.min(steps.length - 1, currentStep + 1)"
                                :disabled="currentStep === steps.length - 1"
                                class="ds-btn ds-btn--primary ds-btn--sm">
                            Lanjut
                            <svg class="ds-btn__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <span class="ds-text-xs ds-text-secondary" style="display:flex;align-items:center;margin-left:var(--space-2);">
                            Klik lingkaran hijau untuk kembali
                        </span>
                    </div>
                </div>

                <!-- Vertical Variant -->
                <div class="ds-card-summary ds-card-summary--elevated">
                    <p class="ds-card-summary__title ds-mb-3">Variasi: Vertikal Timeline (Mobile-friendly)</p>
                    <div style="max-width:320px;">
                        <div style="display:flex;gap:var(--space-3);">
                            <div style="display:flex;flex-direction:column;align-items:center;">
                                <span style="width:28px;height:28px;border-radius:50%;background:var(--stepper-completed-bg);display:flex;align-items:center;justify-content:center;">
                                    <svg width="14" height="14" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span style="width:2px;flex:1;min-height:16px;background:var(--stepper-line-completed);margin:4px 0;"></span>
                            </div>
                            <div style="padding-bottom:var(--space-4);">
                                <p style="font-weight:600;font-size:14px;color:var(--stepper-completed-text);">Pilih Zona</p>
                                <p style="font-size:11px;color:var(--color-text-tertiary);">VIP — IDR 2.500.000</p>
                            </div>
                        </div>
                        <div style="display:flex;gap:var(--space-3);">
                            <div style="display:flex;flex-direction:column;align-items:center;">
                                <span style="width:28px;height:28px;border-radius:50%;background:var(--stepper-active-bg);display:flex;align-items:center;justify-content:center;color:var(--stepper-active-number);font-size:12px;font-weight:700;box-shadow:0 0 0 4px rgba(245,158,11,0.15);">2</span>
                                <span style="width:2px;flex:1;min-height:16px;background:var(--stepper-line-color);margin:4px 0;"></span>
                            </div>
                            <div style="padding-bottom:var(--space-4);">
                                <p style="font-weight:700;font-size:14px;color:var(--stepper-active-text);">Data Diri</p>
                                <p style="font-size:11px;color:var(--color-text-tertiary);">Isi nama, email, nomor HP</p>
                            </div>
                        </div>
                        <div style="display:flex;gap:var(--space-3);">
                            <div style="display:flex;flex-direction:column;align-items:center;">
                                <span style="width:28px;height:28px;border-radius:50%;border:2px solid var(--stepper-inactive-border);display:flex;align-items:center;justify-content:center;color:var(--stepper-inactive-text);font-size:12px;font-weight:600;">3</span>
                            </div>
                            <div>
                                <p style="font-weight:400;font-size:14px;color:var(--stepper-inactive-text);">Pembayaran</p>
                                <p style="font-size:11px;color:var(--color-text-tertiary);">Pilih metode & bayar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══════════ SECTION: BADGES ═══════════ -->
            <section id="ds-badges" style="margin-bottom:var(--space-16);">
                <h2 style="font-family:'Fraunces',serif;font-weight:900;font-size:var(--text-heading-1);margin-bottom:var(--space-2);">🏷 Badge Status</h2>
                <p class="ds-text-small ds-text-secondary" style="margin-bottom:var(--space-6);">Label kecil untuk menandai status, tipe tiket, atau kategori.</p>

                <div class="ds-card-summary ds-card-summary--elevated">
                    <div style="display:flex;flex-wrap:wrap;gap:var(--space-2);align-items:center;margin-bottom:var(--space-4);">
                        <span class="ds-badge ds-badge--primary">Primary</span>
                        <span class="ds-badge ds-badge--success">✓ Paid</span>
                        <span class="ds-badge ds-badge--error">✕ Cancelled</span>
                        <span class="ds-badge ds-badge--warning">⚠ Pending</span>
                        <span class="ds-badge ds-badge--info">ℹ Info</span>
                        <span class="ds-badge ds-badge--brand">BRAND</span>
                        <span class="ds-badge ds-badge--neutral">Neutral</span>
                        <span class="ds-badge ds-badge--vip">★ VIP</span>
                        <span class="ds-badge ds-badge--premium">👑 PREMIUM</span>
                        <span class="ds-badge ds-badge--free">FREE</span>
                    </div>
                    <div style="display:flex;gap:var(--space-4);align-items:center;">
                        <span style="display:flex;align-items:center;gap:var(--space-2);">
                            <span class="ds-badge-dot ds-badge-dot--success"></span> Online
                        </span>
                        <span style="display:flex;align-items:center;gap:var(--space-2);">
                            <span class="ds-badge-dot ds-badge-dot--error"></span> Offline
                        </span>
                        <span style="display:flex;align-items:center;gap:var(--space-2);">
                            <span class="ds-badge-dot ds-badge-dot--warning"></span> Maintenance
                        </span>
                        <span style="display:flex;align-items:center;gap:var(--space-2);">
                            <span class="ds-badge-dot ds-badge-dot--brand"></span> Live
                        </span>
                    </div>
                </div>
            </section>

            <!-- ═══════════ SECTION: FORM ═══════════ -->
            <section id="ds-form" style="margin-bottom:var(--space-16);">
                <h2 style="font-family:'Fraunces',serif;font-weight:900;font-size:var(--text-heading-1);margin-bottom:var(--space-2);">📝 Form Data Diri</h2>
                <p class="ds-text-small ds-text-secondary" style="margin-bottom:var(--space-6);">Form dengan label, input, radio group, checkbox, states validasi, dan input group.</p>

                <div class="ds-card-summary ds-card-summary--elevated" style="margin-bottom:var(--space-4);">
                    <p class="ds-card-summary__title">Form Pemesanan</p>
                    <div class="ds-form">
                        <!-- Radio Group -->
                        <div class="ds-form__group">
                            <label class="ds-form__label">Gelar</label>
                            <div class="ds-form__radio-group">
                                <label class="ds-form__radio">
                                    <input type="radio" name="title" value="Tuan" checked>
                                    <span class="ds-form__radio-label">Tuan</span>
                                </label>
                                <label class="ds-form__radio">
                                    <input type="radio" name="title" value="Nyonya">
                                    <span class="ds-form__radio-label">Nyonya</span>
                                </label>
                                <label class="ds-form__radio">
                                    <input type="radio" name="title" value="Nona">
                                    <span class="ds-form__radio-label">Nona</span>
                                </label>
                            </div>
                        </div>

                        <!-- Text Input -->
                        <div class="ds-form__group">
                            <label class="ds-form__label ds-form__label--required">Nama Lengkap</label>
                            <input type="text" class="ds-form__input" placeholder="Nama sesuai KTP" value="Budi Santoso">
                        </div>

                        <!-- Error state -->
                        <div class="ds-form__group">
                            <label class="ds-form__label ds-form__label--required">Email</label>
                            <input type="email" class="ds-form__input ds-form__input--error" placeholder="kamu@email.com" value="bukan-email">
                            <span class="ds-form__error">
                                <svg class="ds-form__error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M12 8v4m0 4h.01" stroke-width="2"/></svg>
                                Format email tidak valid
                            </span>
                        </div>

                        <!-- Input Group -->
                        <div class="ds-form__group">
                            <label class="ds-form__label">Nomor Ponsel</label>
                            <div class="ds-input-group">
                                <span class="ds-input-group__prefix">+62</span>
                                <input type="tel" class="ds-input-group__input" placeholder="812-3456-7890" value="81234567890">
                            </div>
                            <span class="ds-form__hint">Nomor akan digunakan untuk pengiriman e-tiket via WhatsApp</span>
                        </div>

                        <!-- Checkbox -->
                        <label class="ds-form__checkbox">
                            <input type="checkbox" checked>
                            <span class="ds-form__checkbox-box"></span>
                            <span class="ds-form__checkbox-text">Saya setuju dengan Syarat & Ketentuan yang berlaku</span>
                        </label>
                    </div>
                </div>

                <!-- Input Group Variants -->
                <div class="ds-card-summary ds-card-summary--elevated">
                    <p class="ds-card-summary__title ds-mb-3">Variasi Input Group</p>
                    <div style="display:flex;flex-direction:column;gap:var(--space-3);">
                        <div class="ds-input-group">
                            <span class="ds-input-group__prefix">IDR</span>
                            <input type="text" class="ds-input-group__input" placeholder="0" value="2.500.000">
                            <span class="ds-input-group__suffix">,00</span>
                        </div>
                        <div class="ds-input-group ds-input-group--error">
                            <span class="ds-input-group__prefix">🔗</span>
                            <input type="text" class="ds-input-group__input" placeholder="https://..." value="invalid-url">
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══════════ SECTION: CARD RINGKASAN ═══════════ -->
            <section id="ds-card" style="margin-bottom:var(--space-16);">
                <h2 style="font-family:'Fraunces',serif;font-weight:900;font-size:var(--text-heading-1);margin-bottom:var(--space-2);">📋 Card Ringkasan Pesanan</h2>
                <p class="ds-text-small ds-text-secondary" style="margin-bottom:var(--space-6);">Menampilkan ringkasan zona, tiket, subtotal, biaya, dan total.</p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-6);">
                    <!-- Order Summary -->
                    <div class="ds-card-summary">
                        <p class="ds-card-summary__title">Ringkasan Pesanan</p>
                        <div class="ds-card-summary__row">
                            <span class="ds-card-summary__label">Event</span>
                            <span class="ds-card-summary__value">Sound of Downtown 2026</span>
                        </div>
                        <div class="ds-card-summary__row">
                            <span class="ds-card-summary__label">Lokasi & Tanggal</span>
                            <span class="ds-card-summary__value">JIExpo Kemayoran<br>15 Agt 2026, 19:00</span>
                        </div>
                        <div class="ds-card-summary__row">
                            <span class="ds-card-summary__label">Zona</span>
                            <span class="ds-card-summary__value">VIP — <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--color-brand);margin-right:4px;vertical-align:middle;"></span> Festival A</span>
                        </div>
                        <div class="ds-card-summary__row">
                            <span class="ds-card-summary__label">Jumlah</span>
                            <span class="ds-card-summary__value">2 Tiket</span>
                        </div>
                        <div class="ds-card-summary__row">
                            <span class="ds-card-summary__label">Harga per Tiket</span>
                            <span class="ds-card-summary__value">IDR 2.500.000</span>
                        </div>
                        <div class="ds-card-summary__row">
                            <span class="ds-card-summary__label">Subtotal</span>
                            <span class="ds-card-summary__value">IDR 5.000.000</span>
                        </div>
                        <hr class="ds-card-summary__divider">
                        <div class="ds-card-summary__total-row">
                            <span class="ds-card-summary__total-label">Total</span>
                            <span class="ds-card-summary__total-value">IDR 5.250.000</span>
                        </div>
                    </div>

                    <!-- Timer -->
                    <div>
                        <div class="ds-timer" style="margin-bottom:var(--space-4);">
                            <p class="ds-timer__label">⏱ Selesaikan Dalam</p>
                            <p class="ds-timer__time">14:59</p>
                        </div>
                        <div class="ds-timer ds-timer--danger">
                            <p class="ds-timer__label">⏱ Waktu Hampir Habis!</p>
                            <p class="ds-timer__time">00:42</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══════════ SECTION: SUCCESS PAGE ═══════════ -->
            <section id="ds-success" style="margin-bottom:var(--space-16);">
                <h2 style="font-family:'Fraunces',serif;font-weight:900;font-size:var(--text-heading-1);margin-bottom:var(--space-2);">✅ Halaman Sukses</h2>
                <p class="ds-text-small ds-text-secondary" style="margin-bottom:var(--space-6);">Tampilan setelah pembayaran berhasil — ikon animasi, pesan, dan detail pesanan.</p>

                <div class="ds-card-summary ds-card-summary--elevated">
                    <div class="ds-success" style="padding:var(--space-8) 0;">
                        <div class="ds-success__icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h2 class="ds-success__title">Pembayaran Berhasil!</h2>
                        <p class="ds-success__message">E-Ticket telah dikirim ke <strong>budi@email.com</strong></p>

                        <div class="ds-success__detail-card">
                            <div class="ds-success__detail-row">
                                <span class="ds-success__detail-label">Event</span>
                                <span class="ds-success__detail-value">Sound of Downtown 2026</span>
                            </div>
                            <div class="ds-success__detail-row">
                                <span class="ds-success__detail-label">Zona / Jumlah</span>
                                <span class="ds-success__detail-value">VIP · 2 Tiket</span>
                            </div>
                            <div class="ds-success__detail-row">
                                <span class="ds-success__detail-label">Total</span>
                                <span class="ds-success__detail-value">IDR 5.250.000</span>
                            </div>
                            <div class="ds-success__detail-row">
                                <span class="ds-success__detail-label">Metode</span>
                                <span class="ds-success__detail-value">Transfer Bank</span>
                            </div>
                        </div>

                        <div class="ds-success__actions">
                            <button class="ds-btn ds-btn--primary">Lihat E-Ticket</button>
                            <button class="ds-btn ds-btn--secondary">Kembali ke Beranda</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══════════ SECTION: PROGRESS BAR ═══════════ -->
            <section style="margin-bottom:var(--space-16);">
                <h2 style="font-family:'Fraunces',serif;font-weight:900;font-size:var(--text-heading-1);margin-bottom:var(--space-2);">📊 Progress Bar</h2>
                <div class="ds-card-summary ds-card-summary--elevated" style="display:flex;flex-direction:column;gap:var(--space-4);">
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:var(--text-xs);margin-bottom:var(--space-1);">
                            <span>Kuota Tersisa</span><span>65%</span>
                        </div>
                        <div class="ds-progress">
                            <div class="ds-progress__fill" style="width:65%"></div>
                        </div>
                    </div>
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:var(--text-xs);margin-bottom:var(--space-1);">
                            <span>Hampir Habis</span><span>92%</span>
                        </div>
                        <div class="ds-progress ds-progress--error">
                            <div class="ds-progress__fill" style="width:92%"></div>
                        </div>
                    </div>
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:var(--text-xs);margin-bottom:var(--space-1);">
                            <span>Queue Position</span><span>#42 dari 156</span>
                        </div>
                        <div class="ds-progress ds-progress--success">
                            <div class="ds-progress__fill" style="width:27%"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══════════ SECTION: EMPTY STATE ═══════════ -->
            <section style="margin-bottom:var(--space-16);">
                <h2 style="font-family:'Fraunces',serif;font-weight:900;font-size:var(--text-heading-1);margin-bottom:var(--space-2);">📭 Empty State</h2>
                <div class="ds-card-summary ds-card-summary--elevated">
                    <div class="ds-empty-state">
                        <svg class="ds-empty-state__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                        <p class="ds-empty-state__title">Belum Ada Tiket</p>
                        <p class="ds-empty-state__description">Kamu belum membeli tiket event apapun. Yuk, jelajahi event seru!</p>
                        <button class="ds-btn ds-btn--primary">Jelajahi Event</button>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <!-- ═══════════ TOAST DEMO ═══════════ -->
    <div x-show="toast.show" x-transition style="position:fixed;top:24px;right:24px;z-index:999;">
        <div :class="'ds-toast ds-toast--' + toast.type" x-text="toast.message"></div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>
    <script>
        function previewApp() {
            return {
                dark: false,
                sidebarOpen: false,
                currentStep: 1,
                steps: ['Pilih Zona', 'Data Diri', 'Pembayaran', 'Selesai'],
                toast: { show: false, message: '', type: 'success' },
                toggleDark() {
                    this.dark = !this.dark;
                    document.documentElement.classList.toggle('dark-mode', this.dark);
                },
                showToast(msg, type = 'success') {
                    this.toast = { show: true, message: msg, type };
                    setTimeout(() => this.toast.show = false, 3000);
                }
            }
        }
    </script>
</body>
</html>
