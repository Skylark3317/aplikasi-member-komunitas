<template>
  <AdminLayout>
    <Head title="Pengaturan - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <h1 class="page-title">Pengaturan</h1>
      <button type="submit" form="settings-form" class="btn-primary" :disabled="form.processing">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
          <polyline points="17 21 17 13 7 13 7 21"/>
          <polyline points="7 3 7 8 15 8"/>
        </svg>
        Simpan perubahan
      </button>
    </div>
    <div class="divider" />

    <!-- Flash Message -->
    <div v-if="$page.props.flash?.success" class="flash-success">
      {{ $page.props.flash.success }}
    </div>

    <!-- Main Layout -->
    <div class="settings-layout">
      <!-- Form Column -->
      <div class="settings-form-col content-area">
        <!-- Tab Bar -->
        <div class="tab-bar" ref="tabBarRef">
          <button v-for="tab in tabs" :key="tab.key"
            :class="['tab-btn', activeTab === tab.key ? 'tab-active' : '']"
            @click="activeTab = tab.key" type="button">
            {{ tab.label }}
          </button>
        </div>
        <form id="settings-form" @submit.prevent="submit" enctype="multipart/form-data">

          <!-- === IDENTITAS & KONTAK === -->
          <div class="form-card" v-show="activeTab === 'identitas-kontak'">
            <h3 class="form-card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
              Profil Komunitas
            </h3>

          <div class="field-group">
            <label class="field-label">Nama Komunitas</label>
            <input v-model="form.community_name" type="text" class="field-input" />
            <span v-if="form.errors.community_name" class="error-msg">{{ form.errors.community_name }}</span>
          </div>

        <!-- Logo Komunitas -->
        <div class="field-group">
          <label class="field-label">Logo Komunitas</label>
          <div class="logo-preview">
            <img v-if="logoPreview" :src="logoPreview" alt="Logo" class="preview-img" />
            <span v-else class="logo-text">{{ initials }}</span>
          </div>
          <p class="field-hint">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="8" x2="12" y2="12"/>
              <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            Format JPG atau PNG, ukuran maksimal 1MB
          </p>
          <div class="btn-row">
            <label class="btn-upload">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="17 8 12 3 7 8"/>
                <line x1="12" y1="3" x2="12" y2="15"/>
              </svg>
              Unggah logo komunitas
              <input type="file" accept=".jpg,.jpeg,.png" @change="onLogoChange" hidden />
            </label>
            <button type="button" class="btn-delete" @click="deleteLogo">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6"/><path d="M14 11v6"/>
                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
              </svg>
              Hapus logo komunitas
            </button>
          </div>
          <span v-if="form.errors.logo" class="error-msg">{{ form.errors.logo }}</span>
          </div>

          <p class="form-subsection mt-4">Kontak</p>

          <div class="field-group">
            <label class="field-label">Email</label>
            <input v-model="form.email" type="email" class="field-input" />
            <span v-if="form.errors.email" class="error-msg">{{ form.errors.email }}</span>
          </div>

          <div class="field-group">
            <label class="field-label">Nama Pengirim Email</label>
            <input v-model="form.email_sender_name" type="text" class="field-input" placeholder="Nama aplikasi default jika dikosongkan" />
            <span v-if="form.errors.email_sender_name" class="error-msg">{{ form.errors.email_sender_name }}</span>
          </div>

          <div class="field-group">
            <label class="field-label">Alamat Email Pengirim</label>
            <input v-model="form.email_sender_address" type="email" class="field-input" placeholder="Email SMTP default jika dikosongkan" />
            <span v-if="form.errors.email_sender_address" class="error-msg">{{ form.errors.email_sender_address }}</span>
          </div>

        <!-- Nomor Telepon -->
        <div class="field-group">
          <label class="field-label">Nomor Telepon</label>
          <input v-model="form.phone" type="text" class="field-input field-half" />
          <span v-if="form.errors.phone" class="error-msg">{{ form.errors.phone }}</span>
        </div>

        <!-- Alamat -->
        <div class="field-group">
          <label class="field-label">Alamat</label>
          <textarea v-model="form.address" class="field-textarea" rows="3" />
          <span v-if="form.errors.address" class="error-msg">{{ form.errors.address }}</span>
          </div>

          <p class="form-subsection mt-4">Media Sosial</p>
          <div class="social-grid">
          <div class="field-group">
            <label class="field-label">Tautan Akun X</label>
            <input v-model="form.social_x" type="text" class="field-input" />
          </div>
        <div class="field-group">
          <label class="field-label">Tautan Akun Facebook</label>
          <input v-model="form.social_facebook" type="text" class="field-input" />
        </div>
        <div class="field-group">
          <label class="field-label">Tautan Akun LinkedIn</label>
          <input v-model="form.social_linkedin" type="text" class="field-input" />
        </div>
        <div class="field-group">
          <label class="field-label">Tautan Akun Skype</label>
          <input v-model="form.social_skype" type="text" class="field-input" />
        </div>
        <div class="field-group">
          <label class="field-label">Tautan Akun Instagram</label>
          <input v-model="form.social_instagram" type="text" class="field-input" />
        </div>
        <div class="field-group">
          <label class="field-label">Tautan Akun YouTube</label>
          <input v-model="form.social_youtube" type="text" class="field-input" />
          </div>
          </div><!-- /social-grid -->
          </div><!-- /form-card identitas-kontak -->

          <!-- === PREMIUM & PEMBAYARAN === -->
          <div class="form-card" v-show="activeTab === 'premium-pembayaran'">
            <h3 class="form-card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
              Bank Account
            </h3>
            
          <div class="field-group">
            <label class="field-label">Nama Pemilik Rekening</label>
            <input v-model="form.bank_account_name" type="text" class="field-input field-half" />
          </div>
          <div class="field-group">
            <label class="field-label">Nomor Rekening</label>
            <input v-model="form.bank_account_number" type="text" class="field-input field-half" />
          </div>
          <div class="field-group">
            <label class="field-label">Nama Bank Pemilik Rekening</label>
            <input v-model="form.bank_name" type="text" class="field-input" />
          </div>

          <p class="form-subsection mt-4">Membership Rules</p>
          <p class="relocate-hint">
            Jenis paket premium (harga &amp; masa aktif) kini dikelola di halaman
            <Link :href="route('superadmin.paket-premium.index')" class="relocate-link">Paket Premium</Link>.
          </p>
          <div class="membership-grid">
        <div class="field-group">
          <label class="field-label">Peringatan Expired (hari sebelumnya)</label>
          <input v-model="form.membership_alert_days" type="number" class="field-input field-quarter" />
          <span v-if="form.errors.membership_alert_days" class="error-msg">{{ form.errors.membership_alert_days }}</span>
        </div>
        <div class="field-group">
          <label class="field-label">Countdown Invoice (jam)</label>
          <input v-model="form.invoice_countdown" type="number" class="field-input field-quarter" />
        </div>
        <div class="field-group">
          <label class="field-label">Durasi Penghapusan Akun Member Otomatis (menit)</label>
          <input v-model="form.account_deletion_duration" type="number" class="field-input field-quarter" />
          <span v-if="form.errors.account_deletion_duration" class="error-msg">{{ form.errors.account_deletion_duration }}</span>
        </div>
        </div><!-- /membership-grid -->

        <p class="form-subsection mt-4">Premium Benefits</p>
        <p class="field-hint">Daftar ini akan muncul sebagai opsi (checkbox) saat membuat/mengedit paket premium.</p>
        <div class="field-group">
          <div v-for="(benefit, index) in form.available_benefits" :key="index" class="feature-row" style="margin-bottom: 8px; display: flex; gap: 8px;">
            <input v-model="form.available_benefits[index]" type="text" class="field-input" placeholder="mis. Akses Prioritas" />
            <button type="button" class="btn-delete" style="padding: 6px; flex-shrink: 0;" @click="removeBenefit(index)" v-if="form.available_benefits.length > 1">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>
          <button type="button" @click="addBenefit" style="display: inline-flex; align-items: center; gap: 6px; background: none; border: none; cursor: pointer; font-size: 12.5px; font-weight: 500; color: var(--primary-color); padding: 4px 0; font-family: inherit; margin-top: 4px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;">
              <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Pilihan Benefit
          </button>
        </div>
      </div><!-- /form-card premium-pembayaran -->

          <!-- === LANDING PAGE === -->
          <div class="form-card" v-show="activeTab === 'landing-page'">
            <h3 class="form-card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
              Colors
            </h3>

          <div class="color-row">
            <div class="field-group">
              <label class="field-label">Warna Primer</label>
              <div class="color-input-wrap">
                <input v-model="form.primary_color" type="text" class="field-input field-color-text" />
                <input v-model="form.primary_color" type="color" class="color-swatch" />
              </div>
            </div>
            <div class="field-group">
              <label class="field-label">Warna Permukaan</label>
              <div class="color-input-wrap">
                <input v-model="form.surface_color" type="text" class="field-input field-color-text" />
                <input v-model="form.surface_color" type="color" class="color-swatch" />
              </div>
            </div>
          </div>

          <p class="form-subsection mt-4">Hero Section</p>
          <div class="field-group">
            <label class="field-label">Gambar Latar Belakang (Hero BG)</label>
            <div class="img-preview-box">
              <img v-if="bgPreview" :src="bgPreview" alt="Latar Belakang" />
            </div>
            <p class="field-hint">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
              </svg>
              Format JPG atau PNG, ukuran maksimal 1MB
            </p>
            <div class="btn-row">
              <label class="btn-upload">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="17 8 12 3 7 8"/>
                  <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                Unggah gambar latar belakang
                <input type="file" accept=".jpg,.jpeg,.png" @change="onBgChange" hidden />
              </label>
              <button type="button" class="btn-delete" @click="deleteBg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                </svg>
                Hapus gambar latar belakang
              </button>
            </div>
            <span v-if="form.errors.bg_image" class="error-msg">{{ form.errors.bg_image }}</span>
          </div>
          <div class="field-group">
            <label class="field-label">Judul Hero</label>
            <textarea v-model="form.hero_title" class="field-textarea" rows="2" />
          </div>
          <div class="field-group">
            <label class="field-label">Deskripsi Hero</label>
            <textarea v-model="form.hero_description" class="field-textarea" rows="3" />
          </div>

          <p class="form-subsection">About Section</p>
          <div class="field-group">
            <label class="field-label">Gambar About</label>
          <div class="img-preview-box">
            <img v-if="aboutPreview" :src="aboutPreview" alt="About" />
          </div>
          <p class="field-hint">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="8" x2="12" y2="12"/>
              <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            Format JPG atau PNG, ukuran maksimal 1MB
          </p>
          <div class="btn-row">
            <label class="btn-upload">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="17 8 12 3 7 8"/>
                <line x1="12" y1="3" x2="12" y2="15"/>
              </svg>
              Unggah gambar about section
              <input type="file" accept=".jpg,.jpeg,.png" @change="onAboutChange" hidden />
            </label>
            <button type="button" class="btn-delete" @click="deleteAbout">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
              </svg>
              Hapus gambar about section
            </button>
          </div>
          <span v-if="form.errors.about_image" class="error-msg">{{ form.errors.about_image }}</span>
        </div>

        <div class="field-group">
          <label class="field-label">Judul About Section</label>
          <textarea v-model="form.about_title" class="field-textarea" rows="2" />
        </div>
        <div class="field-group">
          <label class="field-label">Deskripsi About Section</label>
          <textarea v-model="form.about_description" class="field-textarea" rows="5" />
          </div>

          <p class="form-subsection">Statistik Member</p>
          <div class="stats-grid">
            <div class="field-group">
              <label class="field-label">Member Aktif</label>
              <input v-model="form.stat_member_aktif" type="number" min="0" class="field-input" />
            </div>
            <div class="field-group">
              <label class="field-label">Member Pasif</label>
              <input v-model="form.stat_member_pasif" type="number" min="0" class="field-input" />
            </div>
            <div class="field-group">
              <label class="field-label">Member Company</label>
              <input v-model="form.stat_member_company" type="number" min="0" class="field-input" />
            </div>
            <div class="field-group">
              <label class="field-label">Member Personal</label>
              <input v-model="form.stat_member_personal" type="number" min="0" class="field-input" />
            </div>
          </div>

          </div><!-- /form-card landing page -->

          <!-- === MEMBER ASSETS === -->
          <div class="form-card" v-show="activeTab === 'member-assets'">
            <h3 class="form-card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2" ry="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              Kartu Member Design
            </h3>

          <!-- Card Background image -->
          <div class="field-group">
            <label class="field-label">Gambar Latar Belakang Kartu Member</label>
          <div class="img-preview-box">
            <img v-if="cardBgPreview" :src="cardBgPreview" alt="Latar Belakang Kartu" />
          </div>
          <p class="field-hint">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="8" x2="12" y2="12"/>
              <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            Format JPG atau PNG, ukuran maksimal 1MB
          </p>
          <div class="btn-row">
            <label class="btn-upload">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="17 8 12 3 7 8"/>
                <line x1="12" y1="3" x2="12" y2="15"/>
              </svg>
              Unggah background kartu
              <input type="file" accept=".jpg,.jpeg,.png" @change="onCardBgChange" hidden />
            </label>
            <button type="button" class="btn-delete" @click="deleteCardBg">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
              </svg>
              Hapus background kartu
            </button>
          </div>
          <span v-if="form.errors.card_background" class="error-msg">{{ form.errors.card_background }}</span>
          </div>

          <p class="form-subsection mt-4">Template Surat Keterangan</p>
          <p class="form-subsection" style="margin-top: 12px; margin-bottom: 8px;">Informasi Kop Surat</p>
            
            <!-- Community Name for CV -->
            <div class="field-group">
              <label class="field-label">Nama Komunitas (Kop Surat)</label>
              <input v-model="form.cv_community_name" type="text" class="field-input" placeholder="Contoh: Aplikasi Member Komunitas" />
              <span v-if="form.errors.cv_community_name" class="error-msg">{{ form.errors.cv_community_name }}</span>
            </div>

            <!-- Email Community for CV -->
            <div class="field-group">
              <label class="field-label">Email Komunitas (Kop Surat)</label>
              <input v-model="form.cv_email" type="email" class="field-input" placeholder="Contoh: info@komunitasamk.com" />
              <span v-if="form.errors.cv_email" class="error-msg">{{ form.errors.cv_email }}</span>
            </div>

            <!-- Website Community -->
            <div class="field-group">
              <label class="field-label">Website Komunitas</label>
              <input v-model="form.cv_website" type="text" class="field-input" placeholder="Contoh: www.komunitasamk.com" />
              <span v-if="form.errors.cv_website" class="error-msg">{{ form.errors.cv_website }}</span>
            </div>

            <!-- Letter Title -->
            <div class="field-group">
              <label class="field-label">Judul Surat</label>
              <input v-model="form.cv_letter_title" type="text" class="field-input" placeholder="Contoh: Surat Keterangan Keanggotaan Premium" />
              <span v-if="form.errors.cv_letter_title" class="error-msg">{{ form.errors.cv_letter_title }}</span>
            </div>

            <p class="form-subsection mt-4">Konten Surat</p>

            <!-- Introduction Text -->
            <div class="field-group">
              <label class="field-label">Teks Pembuka (Keterangan)</label>
              <textarea v-model="form.cv_introduction" class="field-textarea" rows="4" placeholder="Dengan ini menerangkan bahwa data di bawah ini adalah anggota resmi..." />
              <span v-if="form.errors.cv_introduction" class="error-msg">{{ form.errors.cv_introduction }}</span>
            </div>

            <!-- Closing Text -->
            <div class="field-group">
              <label class="field-label">Teks Penutup</label>
              <textarea v-model="form.cv_closing" class="field-textarea" rows="3" placeholder="Demikian surat keterangan keanggotaan ini dibuat dengan sebenar-benarnya..." />
              <span v-if="form.errors.cv_closing" class="error-msg">{{ form.errors.cv_closing }}</span>
            </div>

            <!-- City -->
            <div class="field-group">
              <label class="field-label">Tempat/Kota TTD</label>
              <input v-model="form.cv_city" type="text" class="field-input" placeholder="Contoh: Jakarta atau Surakarta" />
              <span v-if="form.errors.cv_city" class="error-msg">{{ form.errors.cv_city }}</span>
            </div>

            <!-- Signer Title / Jabatan -->
            <div class="field-group">
              <label class="field-label">Jabatan Penandatangan</label>
              <input v-model="form.cv_signer_title" type="text" class="field-input" placeholder="Contoh: Pengurus Pusat AMK atau Ketua Umum" />
              <span v-if="form.errors.cv_signer_title" class="error-msg">{{ form.errors.cv_signer_title }}</span>
            </div>

            <!-- Signer Name -->
            <div class="field-group">
              <label class="field-label">Nama Penandatangan</label>
              <input v-model="form.cv_signer_name" type="text" class="field-input" placeholder="Contoh: Admin AMK atau Ahmad, M.Kom." />
              <span v-if="form.errors.cv_signer_name" class="error-msg">{{ form.errors.cv_signer_name }}</span>
            </div>

            <!-- Signature Image -->
            <div class="field-group">
              <label class="field-label">Gambar Tanda Tangan (Signature)</label>
              <div class="logo-preview" style="height: 100px; width: 180px;">
                <img v-if="cvSignaturePreview" :src="cvSignaturePreview" alt="Tanda Tangan" class="preview-img" style="object-fit: contain; background: #fff;" />
                <span v-else class="logo-text" style="font-size: 14px; color: #9ca3af; letter-spacing: 0;">Belum ada TTD</span>
              </div>
              <p class="field-hint">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <circle cx="12" cy="12" r="10"/>
                  <line x1="12" y1="8" x2="12" y2="12"/>
                  <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Format JPG atau PNG (disarankan background transparan), max 1MB
              </p>
              <div class="btn-row">
                <label class="btn-upload">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                  </svg>
                  Unggah tanda tangan
                  <input type="file" accept=".jpg,.jpeg,.png" @change="onCvSignatureChange" hidden />
                </label>
                <button type="button" class="btn-delete" @click="deleteCvSignature">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  </svg>
                  Hapus tanda tangan
                </button>
              </div>
              <span v-if="form.errors.cv_signature_image" class="error-msg">{{ form.errors.cv_signature_image }}</span>
            </div>
          </div><!-- /form-card member-assets -->

          <!-- === LEGAL === -->
          <div class="form-card" v-show="activeTab === 'legal'">
            <h3 class="form-card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
              Syarat & Ketentuan
            </h3>
            <p class="field-hint" style="margin-bottom:12px">Konten syarat dan ketentuan untuk form registrasi member.</p>
            <div class="field-group">
              <label class="field-label">Isi Syarat & Ketentuan</label>
              <RichTextEditor v-model="form.terms_and_conditions" placeholder="Masukkan konten syarat dan ketentuan di sini..." />
            </div>
          </div><!-- /form-card legal -->

        </form>
      </div>

      <!-- Preview Column -->
      <div class="settings-preview-col">
        <div class="preview-sticky">
          <h4 class="preview-title">Live Preview</h4>
          <div :class="['preview-container', ['member-assets'].includes(activeTab) ? 'preview-container--full' : '']">
            <PreviewLandingPage
              v-if="['identitas-kontak', 'landing-page'].includes(activeTab)"
              :settings="form"
              :logoPreview="logoPreview"
              :bgPreview="bgPreview"
              :aboutPreview="aboutPreview"
            />
            <PreviewMembership
              v-else-if="activeTab === 'premium-pembayaran'"
              :form="form"
            />
            <div v-else-if="activeTab === 'legal'" style="width: 100%; height: 100%; overflow-y: auto; background-color: #f9fafb; padding: 24px; color: #374151;">
              <h1 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: var(--primary-color);">Syarat dan Ketentuan</h1>
              <div class="ql-editor" style="padding:0 !important; font-family: inherit; font-size: 0.875rem;" v-html="form.terms_and_conditions"></div>
            </div>
            <!-- Member Assets Preview: Kartu Member Card + CV Template -->
            <div v-else-if="activeTab === 'member-assets'" style="width: 100%; height: 100%; display: flex; flex-direction: column; gap: 16px; overflow-y: auto; padding: 16px; background: #f9fafb;">
              <!-- Kartu Member Preview -->
              <div style="background: #fff; border-radius: 12px; padding: 16px; border: 1px solid #e5e7eb;">
                <h5 style="font-size: 0.875rem; font-weight: 600; color: #6b7280; margin: 0 0 12px 0;">Preview Kartu Member</h5>
                <PreviewMemberCard
                  :form="form"
                  :cardBgUrl="cardBgPreview"
                />
              </div>
              
              <!-- CV Template Preview -->
              <div style="background: #fff; border-radius: 12px; padding: 16px; border: 1px solid #e5e7eb; flex: 1; min-height: 0;">
                <h5 style="font-size: 0.875rem; font-weight: 600; color: #6b7280; margin: 0 0 12px 0;">Preview Template Surat</h5>
                <div class="cv-preview-shell" :ref="onCvShellMounted">
              <div class="cv-preview-page">
                <!-- Kop Surat -->
                <div class="cv-kop">
                  <h4 class="cv-kop-title">{{ form.cv_community_name || form.community_name || 'Aplikasi Member Komunitas' }}</h4>
                  <p class="cv-kop-sub">Email: {{ form.cv_email || form.email || 'support@amk.com' }} | Website: {{ form.cv_website || 'www.komunitasamk.com' }}</p>
                </div>
                <h5 class="cv-letter-title">{{ form.cv_letter_title || 'Surat Keterangan Keanggotaan Premium' }}</h5>
                <div class="cv-body">
                  <p class="cv-para">{{ form.cv_introduction }}</p>
                  <table class="cv-table">
                    <tr><td class="cv-td-label">Nama Lengkap</td><td>: Nem Painem</td></tr>
                    <tr><td class="cv-td-label">Nomor Anggota</td><td>: 290620261</td></tr>
                    <tr><td class="cv-td-label">Email Terdaftar</td><td>: nem@amk.com</td></tr>
                    <tr><td class="cv-td-label">Status Membership</td><td>: Aktif (Premium)</td></tr>
                    <tr><td class="cv-td-label">Bergabung Sejak</td><td>: 01 Januari 2025</td></tr>
                    <tr><td class="cv-td-label">Masa Berlaku</td><td>: 01 Januari 2026</td></tr>
                  </table>
                  <p class="cv-para">{{ form.cv_closing }}</p>
                </div>
                <div class="cv-sig-block">
                  <div class="cv-sig-item">
                    <p class="cv-sig-city">{{ form.cv_city || 'Jakarta' }}, {{ new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}</p>
                    <p class="cv-sig-role"><strong>{{ form.cv_signer_title || 'Pengurus Pusat AMK' }}</strong></p>
                    <div class="cv-sig-img-wrap">
                      <img v-if="cvSignaturePreview" :src="cvSignaturePreview" alt="Tanda Tangan" class="cv-sig-img" />
                      <div v-else class="cv-sig-placeholder">(Tanpa TTD)</div>
                    </div>
                    <p class="cv-sig-name">{{ form.cv_signer_name || 'Admin AMK' }}</p>
                  </div>
                </div>
              </div><!-- /cv-preview-page -->
              </div><!-- /cv-preview-shell -->
              </div><!-- /CV Template Preview Card -->
            </div><!-- /member-assets preview container -->
          </div><!-- /preview-container -->
        </div><!-- /preview-sticky -->
      </div><!-- /settings-preview-col -->
    </div><!-- /settings-layout -->
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, getCurrentInstance } from 'vue';
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PreviewLandingPage from './PreviewLandingPage.vue';
import PreviewMemberCard from './PreviewMemberCard.vue';
import PreviewMembership from './PreviewMembership.vue';
import RichTextEditor from '@/Components/RichTextEditor.vue';

const props = defineProps({ settings: Object });
const $page = usePage();
const { proxy } = getCurrentInstance();

const s = props.settings ?? {};

const defaultTerms = `<p>Selamat datang di Aplikasi Member Komunitas. Dengan mendaftar dan menggunakan aplikasi ini, Anda menyatakan setuju untuk terikat dan mematuhi Syarat dan Ketentuan di bawah ini. Harap membaca dengan cermat.</p>
<h2>1. Ketentuan Umum</h2>
<ul>
  <li>Layanan ini disediakan untuk memudahkan pengelolaan dan komunikasi antar anggota komunitas.</li>
  <li>Setiap member diwajibkan memberikan data diri yang benar, akurat, dan dapat dipertanggungjawabkan pada saat pendaftaran.</li>
  <li>Pihak pengelola berhak untuk menonaktifkan atau menghapus akun jika ditemukan pelanggaran atau penyalahgunaan.</li>
</ul>
<h2>2. Keanggotaan dan Akun</h2>
<ul>
  <li>Setiap individu hanya diperbolehkan memiliki satu akun aktif.</li>
  <li>Anda bertanggung jawab penuh atas kerahasiaan kata sandi (password) dan semua aktivitas yang dilakukan melalui akun Anda.</li>
  <li>Segera laporkan kepada admin jika Anda mencurigai adanya akses tidak sah ke akun Anda.</li>
</ul>
<h2>3. Privasi dan Data Pribadi</h2>
<ul>
  <li>Kami menghargai privasi Anda dan akan menjaga kerahasiaan data pribadi sesuai dengan kebijakan privasi yang berlaku.</li>
  <li>Data pribadi yang dikumpulkan hanya akan digunakan untuk keperluan administratif komunitas dan tidak akan diperjualbelikan kepada pihak ketiga.</li>
</ul>
<h2>4. Perubahan Syarat dan Ketentuan</h2>
<p>Pengelola komunitas berhak untuk memperbarui atau mengubah Syarat dan Ketentuan ini sewaktu-waktu. Perubahan akan diinformasikan kepada member melalui platform, dan kelanjutan penggunaan layanan setelah perubahan menandakan persetujuan Anda atas Syarat dan Ketentuan yang baru.</p>`;

const form = useForm({
  community_name:      s.community_name      ?? '',
  email:               s.email               ?? '',
  email_sender_name:   s.email_sender_name   ?? '',
  email_sender_address: s.email_sender_address ?? '',
  phone:               s.phone               ?? '',
  address:             s.address             ?? '',
  social_x:            s.social_x            ?? '',
  social_facebook:     s.social_facebook     ?? '',
  social_linkedin:     s.social_linkedin     ?? '',
  social_skype:        s.social_skype        ?? '',
  social_instagram:    s.social_instagram    ?? '',
  social_youtube:      s.social_youtube      ?? '',
  bank_account_name:   s.bank_account_name   ?? '',
  bank_account_number: s.bank_account_number ?? '',
  bank_name:           s.bank_name           ?? '',
  membership_alert_days: s.membership_alert_days ?? '7',
  invoice_countdown:   s.invoice_countdown   ?? '',
  account_deletion_duration: s.account_deletion_duration ?? '10080',
  primary_color:       s.primary_color       ?? 'var(--primary-color)',
  surface_color:       s.surface_color       ?? '#ffffff',
  hero_title:          s.hero_title          ?? '',
  hero_description:    s.hero_description    ?? '',
  about_title:         s.about_title         ?? '',
  about_description:   s.about_description   ?? '',
  stat_member_aktif:   s.stat_member_aktif   ?? '',
  stat_member_pasif:   s.stat_member_pasif   ?? '',
  stat_member_company: s.stat_member_company ?? '',
  stat_member_personal: s.stat_member_personal ?? '',
  terms_and_conditions: s.terms_and_conditions ?? defaultTerms,
  available_benefits:  (s.available_benefits && s.available_benefits.length) ? s.available_benefits : [''],
  cv_community_name:   s.cv_community_name   ?? '',
  cv_email:            s.cv_email            ?? '',
  cv_website:          s.cv_website          ?? '',
  cv_letter_title:     s.cv_letter_title     ?? '',
  cv_introduction:     s.cv_introduction     ?? '',
  cv_closing:          s.cv_closing          ?? '',
  cv_city:             s.cv_city             ?? '',
  cv_signer_title:     s.cv_signer_title     ?? '',
  cv_signer_name:      s.cv_signer_name      ?? '',
  logo:                null,
  delete_logo:         false,
  bg_image:            null,
  delete_bg_image:     false,
  card_background:     null,
  delete_card_background: false,
  about_image:         null,
  delete_about_image:  false,
  cv_signature_image:   null,
  delete_cv_signature_image: false,
});

const activeTab = ref('identitas-kontak');
const tabs = [
  { key: 'identitas-kontak', label: 'Identitas & Kontak' },
  { key: 'premium-pembayaran', label: 'Premium & Pembayaran' },
  { key: 'landing-page', label: 'Landing Page' },
  { key: 'member-assets', label: 'Member Assets' },
  { key: 'legal', label: 'Legal' },
];

// Image previews
const logoPreview  = ref(s.community_logo ? `/storage/${s.community_logo}` : null);
const bgPreview    = ref(s.bg_image       ? `/storage/${s.bg_image}`       : null);
const cardBgPreview = ref(s.card_background ? `/storage/${s.card_background}` : null);
const aboutPreview = ref(s.about_image    ? `/storage/${s.about_image}`    : null);
const cvSignaturePreview = ref(s.cv_signature_image ? `/storage/${s.cv_signature_image}` : null);

// Dynamically scale the A4 preview page to fit the shell container
const cvShellRef = ref(null);
const tabBarRef = ref(null);
let cvResizeObserver = null;

function updateCvScale() {
  if (!cvShellRef.value) return;
  const shellW = cvShellRef.value.offsetWidth - 24; // subtract 12px padding each side
  const scale = Math.min(shellW / 595, 1);
  cvShellRef.value.style.setProperty('--cv-scale', scale);
}

// Handle horizontal scroll with mouse wheel on tab bar
function handleTabBarScroll(e) {
  if (!tabBarRef.value) return;
  // Prevent vertical scroll and scroll horizontally instead
  if (e.deltaY !== 0) {
    e.preventDefault();
    tabBarRef.value.scrollLeft += e.deltaY;
  }
}

onMounted(() => {
  if (typeof ResizeObserver !== 'undefined') {
    cvResizeObserver = new ResizeObserver(updateCvScale);
  }
  
  // Add wheel event listener to tab bar for horizontal scrolling on next tick
  setTimeout(() => {
    if (tabBarRef.value) {
      tabBarRef.value.addEventListener('wheel', handleTabBarScroll, { passive: false });
    }
  }, 0);
});

onUnmounted(() => {
  if (cvResizeObserver) cvResizeObserver.disconnect();
  
  // Remove wheel event listener
  if (tabBarRef.value) {
    tabBarRef.value.removeEventListener('wheel', handleTabBarScroll);
  }
});
function onCvShellMounted(el) {
  if (!el) { if (cvResizeObserver && cvShellRef.value) cvResizeObserver.unobserve(cvShellRef.value); return; }
  cvShellRef.value = el;
  updateCvScale();
  if (cvResizeObserver) cvResizeObserver.observe(el);
}

const initials = computed(() =>
  (s.community_name ?? 'AMK').split(' ').slice(0, 3).map(w => w[0]).join('').toUpperCase()
);

function validateFile(file, field) {
  if (file.size > 1024 * 1024) {
    form.errors[field] = 'Ukuran file tidak boleh lebih dari 1MB.';
    return false;
  }
  form.errors[field] = null;
  return true;
}

function onLogoChange(e) {
  const file = e.target.files[0];
  if (file && validateFile(file, 'logo')) {
    form.logo = file;
    form.delete_logo = false;
    logoPreview.value = URL.createObjectURL(file);
  } else {
    e.target.value = '';
  }
}
async function deleteLogo() {
  try {
    const confirmed = await proxy.$dialog.confirm({
      title: 'Hapus Logo Komunitas',
      message: 'Apakah Anda yakin ingin menghapus logo komunitas?',
      variant: 'warning',
      confirmText: 'Hapus',
      cancelText: 'Batal'
    });
    
    if (confirmed) {
      form.logo = null;
      form.delete_logo = true;
      logoPreview.value = null;
      form.errors.logo = null;
    }
  } catch {
    // User cancelled
  }
}

function onBgChange(e) {
  const file = e.target.files[0];
  if (file && validateFile(file, 'bg_image')) {
    form.bg_image = file;
    form.delete_bg_image = false;
    bgPreview.value = URL.createObjectURL(file);
  } else {
    e.target.value = '';
  }
}
async function deleteBg() {
  try {
    const confirmed = await proxy.$dialog.confirm({
      title: 'Hapus Gambar Background',
      message: 'Apakah Anda yakin ingin menghapus gambar latar belakang hero section?',
      variant: 'warning',
      confirmText: 'Hapus',
      cancelText: 'Batal'
    });
    
    if (confirmed) {
      form.bg_image = null;
      form.delete_bg_image = true;
      bgPreview.value = null;
      form.errors.bg_image = null;
    }
  } catch {
    // User cancelled
  }
}

function onCardBgChange(e) {
  const file = e.target.files[0];
  if (file && validateFile(file, 'card_background')) {
    form.card_background = file;
    form.delete_card_background = false;
    cardBgPreview.value = URL.createObjectURL(file);
  } else {
    e.target.value = '';
  }
}
async function deleteCardBg() {
  try {
    const confirmed = await proxy.$dialog.confirm({
      title: 'Hapus Background Kartu Member',
      message: 'Apakah Anda yakin ingin menghapus gambar latar belakang kartu member?',
      variant: 'warning',
      confirmText: 'Hapus',
      cancelText: 'Batal'
    });
    
    if (confirmed) {
      form.card_background = null;
      form.delete_card_background = true;
      cardBgPreview.value = null;
      form.errors.card_background = null;
    }
  } catch {
    // User cancelled
  }
}

function onAboutChange(e) {
  const file = e.target.files[0];
  if (file && validateFile(file, 'about_image')) {
    form.about_image = file;
    form.delete_about_image = false;
    aboutPreview.value = URL.createObjectURL(file);
  } else {
    e.target.value = '';
  }
}
async function deleteAbout() {
  try {
    const confirmed = await proxy.$dialog.confirm({
      title: 'Hapus Gambar About Section',
      message: 'Apakah Anda yakin ingin menghapus gambar about section?',
      variant: 'warning',
      confirmText: 'Hapus',
      cancelText: 'Batal'
    });
    
    if (confirmed) {
      form.about_image = null;
      form.delete_about_image = true;
      aboutPreview.value = null;
      form.errors.about_image = null;
    }
  } catch {
    // User cancelled
  }
}

function onCvSignatureChange(e) {
  const file = e.target.files[0];
  if (file && validateFile(file, 'cv_signature_image')) {
    form.cv_signature_image = file;
    form.delete_cv_signature_image = false;
    cvSignaturePreview.value = URL.createObjectURL(file);
  } else {
    e.target.value = '';
  }
}
async function deleteCvSignature() {
  try {
    const confirmed = await proxy.$dialog.confirm({
      title: 'Hapus Tanda Tangan',
      message: 'Apakah Anda yakin ingin menghapus gambar tanda tangan untuk template surat?',
      variant: 'warning',
      confirmText: 'Hapus',
      cancelText: 'Batal'
    });
    
    if (confirmed) {
      form.cv_signature_image = null;
      form.delete_cv_signature_image = true;
      cvSignaturePreview.value = null;
      form.errors.cv_signature_image = null;
    }
  } catch {
    // User cancelled
  }
}

async function submit() {
  try {
    const confirmed = await proxy.$dialog.confirm({
      title: 'Simpan Perubahan Pengaturan',
      message: 'Apakah Anda yakin ingin menyimpan semua perubahan pengaturan?',
      variant: 'info',
      confirmText: 'Simpan',
      cancelText: 'Batal'
    });
    
    if (!confirmed) return;
    
    form.post(route('superadmin.pengaturan.update'), {
      forceFormData: true,
      onSuccess: () => {
        const s = $page.props.settings;
        logoPreview.value = s.community_logo ? `/storage/${s.community_logo}` : null;
        bgPreview.value = s.bg_image ? `/storage/${s.bg_image}` : null;
        cardBgPreview.value = s.card_background ? `/storage/${s.card_background}` : null;
        aboutPreview.value = s.about_image ? `/storage/${s.about_image}` : null;
        cvSignaturePreview.value = s.cv_signature_image ? `/storage/${s.cv_signature_image}` : null;
        
        form.logo = null;
        form.bg_image = null;
        form.card_background = null;
        form.about_image = null;
        form.cv_signature_image = null;
      }
    });
  } catch {
    // User cancelled
  }
}

function addBenefit() {
  form.available_benefits.push('');
}

function removeBenefit(index) {
  form.available_benefits.splice(index, 1);
}
</script>

<style scoped>
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 32px;
}
.page-title { font-size: 20px; font-weight: 700; color: #111; }

.btn-primary {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-family: inherit;
  line-height: 1;
  height: 38px;
  font-weight: 500;
  cursor: pointer;
  text-decoration: none;
  transition: filter 0.2s;
  border: 1px solid transparent;
  box-sizing: border-box;
  background: var(--primary-color);
  color: #fff;
  border-color: var(--primary-color);
}
.btn-primary:hover:not(:disabled) { filter: brightness(0.9); }
.btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
.btn-primary svg { width: 15px; height: 15px; }

.divider { height: 1px; background: #e5e7eb; }

.flash-success {
  margin: 12px 32px 0;
  background: #d1fae5;
  color: #065f46;
  padding: 10px 16px;
  border-radius: 8px;
  font-size: 13.5px;
}

/* === TAB BAR === */
.tab-bar {
  position: sticky;
  top: 0;
  z-index: 10;
  background: #fff;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  gap: 0;
  margin: -16px -20px 16px;
  padding: 0 20px 0;
  overflow-x: auto;
  overflow-y: hidden;
  scrollbar-width: thin;
  scrollbar-color: #cbd5e1 #f1f5f9;
}
.tab-bar::-webkit-scrollbar {
  height: 8px;
}
.tab-bar::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}
.tab-bar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
  transition: background 0.2s;
}
.tab-bar::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
.tab-btn {
  flex-shrink: 0;
  padding: 10px 14px;
  font-size: 12.5px;
  font-weight: 500;
  color: #6b7280;
  background: none;
  border: none;
  border-bottom: 2px solid transparent;
  cursor: pointer;
  font-family: inherit;
  transition: color 0.15s, border-color 0.15s;
  margin-bottom: -1px;
  white-space: nowrap;
}
.tab-btn:hover { color: var(--primary-color); }
.tab-active {
  color: var(--primary-color) !important;
  border-bottom-color: var(--primary-color) !important;
  font-weight: 600;
}

.settings-layout {
  display: flex;
  height: calc(100vh - 76px);
  overflow: hidden;
  padding: 0;
  gap: 0;
}

.settings-form-col {
  width: 440px;
  flex-shrink: 0;
  overflow-y: auto;
  overflow-x: hidden;
  height: 100%;
  padding: 16px 20px;
  border-right: 1px solid #e5e7eb;
  background: #fff;
  scrollbar-width: thin;
  scrollbar-color: #cbd5e1 #f1f5f9;
}

.settings-form-col::-webkit-scrollbar {
  width: 8px;
}
.settings-form-col::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}
.settings-form-col::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
  transition: background 0.2s;
}
.settings-form-col::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

.content-area { padding: 0; }

/* === FORM CARDS === */
.form-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  padding: 18px 20px;
  margin: 16px;
  margin-top:32px;
  border-radius: 10px;
}
.form-card-title {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  font-weight: 600;
  color: #374151;
  margin: 0 0 16px;
  padding-bottom: 12px;
  border-bottom: 1px solid #f3f4f6;
}
.form-card-title svg { width: 15px; height: 15px; color: var(--primary-color); }
.form-subsection {
  font-size: 11px;
  font-weight: 600;
  color: #9ca3af;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  margin: 14px 0 10px;
}
.form-subsection:first-of-type { margin-top: 0; }
.social-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 12px; }
.membership-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 12px; }
.color-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0 12px; }
.stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 12px; }

.settings-preview-col {
  flex: 1;
  min-width: 0;
  height: 100%;
  overflow: hidden;
  padding: 16px 20px;
  background: #f8fafc;
  display: flex;
  flex-direction: column;
}

.preview-sticky {
  display: flex;
  flex-direction: column;
  flex: 1;
  min-height: 0;
  justify-content: center;
}

.preview-title {
  font-size: 11px;
  font-weight: 600;
  color: #9ca3af;
  margin-bottom: 10px;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  display: flex;
  align-items: center;
  gap: 6px;
  flex-shrink: 0;
}
.preview-title::before {
  content: '';
  display: inline-block;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #22c55e;
  box-shadow: 0 0 0 2px rgba(34,197,94,0.25);
}

.preview-container {
  width: 100%;
  aspect-ratio: 16 / 9;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 20px 40px -8px rgba(0,0,0,0.15), 0 0 0 1px rgba(0,0,0,0.06);
  background: #fff;
  flex-shrink: 0;
  transition: aspect-ratio 0.25s ease;
}
.preview-container--full {
  aspect-ratio: auto;
  flex: 1;
  min-height: 0;
  background: transparent;
  box-shadow: none;
  border-radius: 0;
}

.field-group { margin-bottom: 14px; }
.field-label {
  display: block;
  font-size: 13.5px;
  font-weight: 500;
  color: #111;
  margin-bottom: 7px;
}
.field-input {
  width: 100%;
  border: 1px solid #d1d5db;
  border-radius: 7px;
  padding: 9px 13px;
  font-size: 13.5px;
  color: #111;
  outline: none;
  transition: border 0.2s;
  box-sizing: border-box;
}
.field-input:focus { border-color: var(--primary-color); }
.field-input.error { border-color: #ef4444; }
.field-half    { max-width: 260px; }
.field-quarter { max-width: 160px; }
.error-msg { font-size: 12px; color: #ef4444; margin-top: 4px; display: block; }

.field-textarea {
  width: 100%;
  border: 1px solid #d1d5db;
  border-radius: 7px;
  padding: 9px 13px;
  font-size: 13.5px;
  color: #111;
  outline: none;
  resize: vertical;
  font-family: inherit;
  transition: border 0.2s;
  box-sizing: border-box;
}
.field-textarea:focus { border-color: var(--primary-color); }

.field-hint {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 12px;
  color: #9ca3af;
  margin: 6px 0 10px;
}
.field-hint svg { width: 13px; height: 13px; flex-shrink: 0; }

/* Logo preview */
.logo-preview {
  width: 92px;
  height: 92px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  margin-bottom: 8px;
  background: #f9fafb;
}
.logo-preview .preview-img { width: 100%; height: 100%; object-fit: cover; }
.logo-text { font-size: 20px; font-weight: 800; color: var(--primary-color); letter-spacing: 2px; }

/* Image preview box */
.img-preview-box {
  width: 140px;
  height: 100px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 8px;
  background: #f9fafb;
}
.img-preview-box img { width: 100%; height: 100%; object-fit: cover; }

/* Button row */
.btn-row { display: flex; gap: 10px; flex-wrap: wrap; }

.btn-upload, .btn-delete {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-family: inherit;
  line-height: 1;
  height: 38px;
  font-weight: 500;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s;
  border: 1px solid transparent;
  box-sizing: border-box;
}

.btn-upload { background: var(--primary-color); color: #fff; border-color: var(--primary-color); }
.btn-delete { background: #fff; color: #ef4444; border-color: #fca5a5; }

.btn-upload:hover { filter: brightness(0.9); }
.btn-delete:hover { background: #fef2f2; border-color: #ef4444; }

.btn-upload svg, .btn-delete svg { width: 16px; height: 16px; }

/* Color input */
.color-input-wrap {
  position: relative;
  display: inline-flex;
  align-items: center;
  width:100%;
}
.field-color-text {
  width: 100%;
  padding-right: 40px;
}
.color-swatch {
  position: absolute;
  right: 6px;
  width: 26px;
  height: 26px;
  border: 1px solid #d1d5db;
  border-radius: 50%;
  cursor: pointer;
  padding: 0;
  background: none;
  overflow: hidden;
}
.color-swatch::-webkit-color-swatch-wrapper {
  padding: 0;
}
.color-swatch::-webkit-color-swatch {
  border: none;
  border-radius: 50%;
}
.color-swatch::-moz-color-swatch {
  border: none;
  border-radius: 50%;
}

.section-divider { display: none; }

/* ===== CV LETTER PREVIEW ===== */
.cv-preview-shell {
  width: 100%;
  height: 100%;
  overflow-y: auto;
  overflow-x: hidden;
  background: #fff;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding: 0 12px;
  box-sizing: border-box;
}

.cv-preview-shell::-webkit-scrollbar { width: 3px; }
.cv-preview-shell::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

.cv-preview-page {
  width: 595px;
  min-height: 842px;
  flex-shrink: 0;
  background: #fff;
  padding: 52px 56px;
  box-sizing: border-box;
  font-family: 'Arial', sans-serif;
  font-size: 13px;
  color: #222;
  line-height: 1.6;
  transform-origin: top center;
  transform: scale(var(--cv-scale, 0.52));
  margin-bottom: calc((842px * var(--cv-scale, 0.52) - 842px));
}

.cv-kop {
  text-align: center;
  border-bottom: 2.5px double #000;
  padding-bottom: 14px;
  margin-bottom: 18px;
}
.cv-kop-title {
  font-size: 17px;
  font-weight: 700;
  margin: 0;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.cv-kop-sub {
  font-size: 11px;
  color: #666;
  margin: 4px 0 0;
}

.cv-letter-title {
  text-align: center;
  font-size: 14px;
  font-weight: 700;
  text-decoration: underline;
  text-transform: uppercase;
  margin: 0 0 22px;
}

.cv-body { margin-bottom: 20px; }

.cv-para {
  margin: 0 0 14px;
  white-space: pre-wrap;
  text-align: justify;
}

.cv-table {
  width: 100%;
  border-collapse: collapse;
  margin: 16px 0;
  font-size: 13px;
}
.cv-table td { padding: 5px 0; vertical-align: top; }
.cv-td-label { width: 180px; font-weight: 600; color: #444; }

.cv-sig-block {
  display: flex;
  justify-content: flex-end;
  margin-top: 32px;
}
.cv-sig-item { text-align: center; width: 200px; }
.cv-sig-city  { margin: 0 0 4px; font-size: 13px; }
.cv-sig-role  { margin: 0 0 6px; font-size: 13px; }
.cv-sig-img-wrap {
  height: 72px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 8px 0;
}
.cv-sig-img { max-height: 100%; max-width: 100%; object-fit: contain; }
.cv-sig-placeholder {
  width: 100%;
  height: 60px;
  border: 1px dashed #cbd5e1;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #94a3b8;
  font-size: 11px;
}
.cv-sig-name {
  font-weight: 700;
  text-decoration: underline;
  margin: 0;
  font-size: 13px;
}

</style>



