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
        <div class="tab-bar">
          <button v-for="tab in tabs" :key="tab.key"
            :class="['tab-btn', activeTab === tab.key ? 'tab-active' : '']"
            @click="activeTab = tab.key" type="button">
            {{ tab.label }}
          </button>
        </div>
        <form id="settings-form" @submit.prevent="submit" enctype="multipart/form-data">

          <!-- === IDENTITAS === -->
          <div class="form-card" v-show="activeTab === 'identitas'">
            <h3 class="form-card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
              Identitas Komunitas
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
          </div><!-- /form-card identitas -->

          <!-- === KONTAK === -->
          <div class="form-card" v-show="activeTab === 'kontak'">
            <h3 class="form-card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.39 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.83a16 16 0 0 0 6.29 6.29l.96-.96a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              Kontak
            </h3>

          <div class="field-group">
            <label class="field-label">Email</label>
            <input v-model="form.email" type="email" class="field-input" />
            <span v-if="form.errors.email" class="error-msg">{{ form.errors.email }}</span>
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
          </div><!-- /form-card kontak -->

          <!-- === MEDIA SOSIAL === -->
          <div class="form-card" v-show="activeTab === 'kontak'">
            <h3 class="form-card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
              Media Sosial
            </h3>
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
          </div><!-- /form-card media sosial -->

          <!-- === BANK === -->
          <div class="form-card" v-show="activeTab === 'bank'">
            <h3 class="form-card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
              Informasi Bank
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
          </div><!-- /form-card bank -->

          <!-- === KEANGGOTAAN === -->
          <div class="form-card" v-show="activeTab === 'keanggotaan'">
            <h3 class="form-card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
              Keanggotaan
            </h3>
          <div class="membership-grid">
          <div class="field-group">
            <label class="field-label">Biaya Membership</label>
            <input v-model="form.membership_fee" type="number" class="field-input" />
            <span v-if="form.errors.membership_fee" class="error-msg">{{ form.errors.membership_fee }}</span>
          </div>
        <div class="field-group">
          <label class="field-label">Masa Berlaku Membership (bulan)</label>
          <input v-model="form.membership_duration" type="number" class="field-input field-quarter" />
          <span v-if="form.errors.membership_duration" class="error-msg">{{ form.errors.membership_duration }}</span>
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
          </div><!-- /form-card keanggotaan -->

          <!-- === TAMPILAN === -->
          <div class="form-card" v-show="activeTab === 'tampilan'">
            <h3 class="form-card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
              Tampilan &amp; Warna
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

        <!-- Background image -->
        <div class="field-group">
          <label class="field-label">Gambar Latar Belakang</label>
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
          </div><!-- /color-row -->

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
          </div><!-- /form-card tampilan -->

          <!-- === LANDING PAGE === -->
          <div class="form-card" v-show="activeTab === 'landingpage'">
            <h3 class="form-card-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
              Konten Landing Page
            </h3>

          <p class="form-subsection">Hero Section</p>
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

        </form>
      </div>

      <!-- Preview Column -->
      <div class="settings-preview-col">
        <div class="preview-sticky">
          <h2 class="preview-title">Live Preview Landing Page</h2>
          <div class="preview-container">
            <PreviewLandingPage 
              :settings="form"
              :logoPreview="logoPreview"
              :bgPreview="bgPreview"
              :aboutPreview="aboutPreview"
            />
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PreviewLandingPage from './PreviewLandingPage.vue';

const props = defineProps({ settings: Object });
const $page = usePage();

const s = props.settings ?? {};

const form = useForm({
  community_name:      s.community_name      ?? '',
  email:               s.email               ?? '',
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
  membership_fee:      s.membership_fee      ?? '',
  membership_duration: s.membership_duration ?? '',
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
  logo:                null,
  delete_logo:         false,
  bg_image:            null,
  delete_bg_image:     false,
  card_background:     null,
  delete_card_background: false,
  about_image:         null,
  delete_about_image:  false,
});

const activeTab = ref('identitas');
const tabs = [
  { key: 'identitas',   label: 'Identitas' },
  { key: 'kontak',      label: 'Kontak & Sosial' },
  { key: 'bank',        label: 'Bank' },
  { key: 'keanggotaan', label: 'Keanggotaan' },
  { key: 'tampilan',    label: 'Tampilan' },
  { key: 'landingpage', label: 'Landing Page' },
];

// Image previews
const logoPreview  = ref(s.community_logo ? `/storage/${s.community_logo}` : null);
const bgPreview    = ref(s.bg_image       ? `/storage/${s.bg_image}`       : null);
const cardBgPreview = ref(s.card_background ? `/storage/${s.card_background}` : null);
const aboutPreview = ref(s.about_image    ? `/storage/${s.about_image}`    : null);

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
function deleteLogo() {
  form.logo = null;
  form.delete_logo = true;
  logoPreview.value = null;
  form.errors.logo = null;
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
function deleteBg() {
  form.bg_image = null;
  form.delete_bg_image = true;
  bgPreview.value = null;
  form.errors.bg_image = null;
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
function deleteCardBg() {
  form.card_background = null;
  form.delete_card_background = true;
  cardBgPreview.value = null;
  form.errors.card_background = null;
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
function deleteAbout() {
  form.about_image = null;
  form.delete_about_image = true;
  aboutPreview.value = null;
  form.errors.about_image = null;
}

function submit() {
  form.post(route('superadmin.pengaturan.update'), {
    forceFormData: true,
  });
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
  padding: 0 20px;
  overflow-x: auto;
  scrollbar-width: none;
}
.tab-bar::-webkit-scrollbar { display: none; }
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
  scrollbar-color: #cbd5e1 transparent;
}

.content-area { padding: 0; }

/* === FORM CARDS === */
.form-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 18px 20px;
  margin-bottom: 16px;
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
  width: 64px;
  height: 64px;
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
.color-input-wrap { display: flex; align-items: center; gap: 10px; }
.field-color-text { max-width: 180px; }
.color-swatch {
  width: 36px;
  height: 36px;
  border: 1px solid #d1d5db;
  border-radius: 50%;
  cursor: pointer;
  padding: 2px;
  background: none;
}

.section-divider { display: none; }
</style>



