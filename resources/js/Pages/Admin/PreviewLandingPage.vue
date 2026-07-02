<template>
  <div class="lp-root" :style="cssVars">

    <!-- ===== HEADER ===== -->
    <header class="lp-header">
      <nav class="lp-nav-container">
        <!-- Top White Bar -->
        <div class="lp-top-bar">
          <div class="lp-container lp-top-inner">
            <div class="lp-logo-box">
              <img :src="logoUrl" alt="Logo" class="lp-logo-img">
            </div>
            <div class="lp-logo-spacer"></div>
            <div class="lp-contact-bar">
              <span class="lp-contact-item">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                {{ settings.email || '-' }}
              </span>
              <span class="lp-contact-item">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/></svg>
                {{ settings.phone || '-' }}
              </span>
              <span class="lp-contact-item">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor"><path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z"/></svg>
                YouTube
              </span>
              <span class="lp-contact-item">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/></svg>
                Instagram
              </span>
            </div>
          </div>
        </div>
        <!-- Bottom Translucent Bar -->
        <div class="lp-bottom-bar">
          <div class="lp-bottom-bg"></div>
          <div class="lp-container lp-bottom-inner">
            <span class="lp-nav-link">TENTANG</span>
            <span class="lp-nav-link">BLOG</span>
            <span class="lp-nav-link">KONTAK</span>
            <span class="lp-nav-btn">Login</span>
          </div>
        </div>
      </nav>
    </header>

    <!-- ===== HERO ===== -->
    <section class="lp-hero">
      <img class="lp-hero-bg" :src="bgUrl" alt="bg">
      <div class="lp-hero-overlay" />
      <div class="lp-container lp-hero-content">
        <h1 class="lp-hero-title">{{ settings.hero_title || 'Bangun Koneksi dan Tumbuh Bersama' }}</h1>
        <p class="lp-hero-desc">{{ settings.hero_description || 'Terhubung dengan individu dari berbagai latar belakang, berbagi ide, dan membangun kolaborasi dalam komunitas inklusif untuk berkembang bersama serta menciptakan dampak nyata.' }}</p>
        <div class="lp-hero-btn">Gabung Membership →</div>
      </div>
    </section>

    <!-- ===== ABOUT ===== -->
    <section class="lp-about">
      <div class="lp-container lp-about-inner">
        <img class="lp-about-img" :src="aboutUrl" alt="About">
        <div class="lp-about-text">
          <div class="lp-section-label">
            <span class="lp-label-line" />
            <h2 class="lp-about-title">{{ settings.about_title || 'Tentang' }}</h2>
          </div>
          <p class="lp-about-desc">{{ settings.about_description || 'Komunitas ini adalah ruang terbuka bagi siapa saja yang ingin belajar, berkembang, dan saling terhubung dalam lingkungan yang positif dan kolaboratif. Kami menghadirkan berbagai kesempatan untuk bertukar wawasan, membangun relasi, serta berpartisipasi dalam kegiatan yang mendorong pertumbuhan pribadi maupun profesional. Dengan semangat kebersamaan, kami percaya bahwa setiap individu memiliki potensi untuk memberikan kontribusi dan menciptakan dampak yang berarti.' }}</p>
        </div>
      </div>
    </section>

    <!-- ===== STATS ===== -->
    <section class="lp-stats">
      <img class="lp-stats-bg" :src="bgUrl" alt="bg">
      <div class="lp-stats-overlay" />
      <div class="lp-container lp-stats-content">
        <h2 class="lp-stats-title">Komunitas Kami dalam Angka</h2>
        <p class="lp-stats-subtitle">Jadilah bagian dari komunitas yang terus berkembang dan berkolaborasi untuk mencapai dampak nyata.</p>
        <div class="lp-stats-grid">
          <div class="lp-stat-card">
            <div class="lp-stat-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/></svg>
            </div>
            <span class="lp-stat-num">{{ settings.stat_member_aktif || '0' }}</span>
            <span class="lp-stat-label">Member Aktif</span>
          </div>
          <div class="lp-stat-card">
            <div class="lp-stat-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/></svg>
            </div>
            <span class="lp-stat-num">{{ settings.stat_member_pasif || '0' }}</span>
            <span class="lp-stat-label">Member Pasif</span>
          </div>
          <div class="lp-stat-card">
            <div class="lp-stat-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>
            </div>
            <span class="lp-stat-num">{{ settings.stat_member_company || '0' }}</span>
            <span class="lp-stat-label">Member Company</span>
          </div>
          <div class="lp-stat-card">
            <div class="lp-stat-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <span class="lp-stat-num">{{ settings.stat_member_personal || '0' }}</span>
            <span class="lp-stat-label">Member Personal</span>
          </div>
        </div>
        <div class="lp-stats-btn">Mulai perjalananmu →</div>
      </div>
    </section>

    <!-- ===== BLOG ===== -->
    <section class="lp-blog">
      <div class="lp-container">
        <div class="lp-blog-label">
          <span class="lp-label-line" />
          <h2 class="lp-blog-heading">Postingan Terbaru</h2>
        </div>
        <div class="lp-blog-grid">
          <div v-for="n in 8" :key="n" class="lp-blog-card">
            <div class="lp-blog-card-inner">
              <span class="lp-blog-date">04/06/2026</span>
              <h3 class="lp-blog-card-title">Judul Artikel Blog {{ n }} yang Menarik</h3>
              <p class="lp-blog-card-desc">Deskripsi singkat tentang artikel yang informatif untuk para anggota komunitas ini agar selalu terhubung.</p>
            </div>
            <div class="lp-blog-card-footer">
              <hr class="lp-blog-hr">
              <span class="lp-blog-read">Lanjutkan membaca →</span>
            </div>
          </div>
        </div>
        <div class="lp-blog-more-wrap">
          <div class="lp-blog-more">Lihat semua →</div>
        </div>
      </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="lp-footer">
      <div class="lp-container lp-footer-body">
        <div class="lp-footer-brand">
          <div class="lp-footer-logo-box">
            <img :src="logoUrl" alt="Logo" class="lp-footer-logo-img">
          </div>
          <p class="lp-footer-desc">{{ settings.about_description || 'Komunitas ini adalah ruang terbuka bagi siapa saja yang ingin belajar, berkembang, dan saling terhubung.' }}</p>
          <div class="lp-footer-socials">
            <span class="lp-social-dot" v-if="settings.social_x" title="X" />
            <span class="lp-social-dot" v-if="settings.social_facebook" title="Facebook" />
            <span class="lp-social-dot" v-if="settings.social_instagram" title="Instagram" />
            <span class="lp-social-dot" v-if="settings.social_youtube" title="YouTube" />
            <span class="lp-social-dot" v-if="settings.social_linkedin" title="LinkedIn" />
          </div>
        </div>
        <div class="lp-footer-menu">
          <h3 class="lp-footer-heading">Menu</h3>
          <div class="lp-footer-links">
            <span>TENTANG</span>
            <span>BLOG</span>
            <span>KONTAK</span>
            <span>LOGIN</span>
          </div>
        </div>
        <div class="lp-footer-contact">
          <h3 class="lp-footer-heading">Kontak</h3>
          <div class="lp-footer-contact-list">
            <div class="lp-footer-contact-item" v-if="settings.address">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lp-icon-shrink"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
              <span>{{ settings.address }}</span>
            </div>
            <div class="lp-footer-contact-item" v-if="settings.phone">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lp-icon-shrink"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/></svg>
              <span>{{ settings.phone }}</span>
            </div>
            <div class="lp-footer-contact-item" v-if="settings.email">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lp-icon-shrink"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
              <span>{{ settings.email }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="lp-footer-bottom">
        <div class="lp-container">
          <span>&copy; 2026 {{ settings.community_name || 'AMK' }}</span>
        </div>
      </div>
    </footer>

  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
  settings: { type: Object, default: () => ({}) },
  logoPreview: { type: String, default: null },
  bgPreview:   { type: String, default: null },
  aboutPreview:{ type: String, default: null },
});

const page = usePage();
const appUrl = computed(() => page.props.appUrl || '');

const logoUrl  = computed(() => props.logoPreview  || `${appUrl.value}/images/community-logo.svg`);
const bgUrl    = computed(() => props.bgPreview    || `${appUrl.value}/images/background.jpg`);
const aboutUrl = computed(() => props.aboutPreview || `${appUrl.value}/images/about.jpg`);

const primaryColor = computed(() => {
  const c = props.settings?.primary_color || '';
  return c.startsWith('#') || c.startsWith('rgb') ? c : '#007FFF';
});

const cssVars = computed(() => ({
  '--lp-primary': primaryColor.value,
}));
</script>

<style scoped>
/* === ROOT === */
.lp-root {
  font-family: 'Fira Sans', sans-serif;
  background: #fff;
  width: 100%;
  height: 100%;
  overflow-y: auto;
  overflow-x: hidden;
  font-size: 11px;
  line-height: 1.5;
  color: #111;
}
.lp-root > * { pointer-events: none !important; }
.lp-root::-webkit-scrollbar { width: 3px; }
.lp-root::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

/* === SHARED CONTAINER === */
.lp-container {
  width: 100%;
  max-width: 680px; /* Constrains all content to the same width */
  margin: 0 auto;
  padding: 0 24px;
  box-sizing: border-box;
}

/* === HEADER === */
.lp-header { position: sticky; top: 0; z-index: 20; background: transparent; }
.lp-nav-container { display: flex; flex-direction: column; position: relative; }
.lp-top-bar { height: 36px; background: #fff; display: flex; justify-content: center; }
.lp-top-inner { display: flex; justify-content: space-between; align-items: stretch; height: 100%; position: relative; }
.lp-logo-box {
  position: absolute;
  left: 24px;
  top: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fff;
  z-index: 10;
  width: 70px;
  height: 70px;
  border-bottom-left-radius: 6px;
  border-bottom-right-radius: 6px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
.lp-logo-img { height: 100%; width: 100%; object-fit: contain; padding: 4px; }
.lp-logo-spacer { width: 70px; }
.lp-contact-bar { display: flex; align-items: center; flex: 1; justify-content: flex-end; }
.lp-contact-item {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 0 10px;
  font-size: 10px;
  font-weight: 500;
  border-left: 1px solid #e5e7eb;
  height: 100%;
  white-space: nowrap;
}
.lp-bottom-bar { position: relative; height: 36px; display: flex; justify-content: center; background: rgba(0,0,0,0.2); }
.lp-bottom-bg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: var(--lp-primary); z-index: -1; }
.lp-bottom-inner { display: flex; justify-content: center; align-items: center; gap: 20px; height: 100%; }
.lp-nav-link { color: #fff; font-size: 10px; font-weight: 500; cursor: pointer; }
.lp-nav-btn {
  color: #fff;
  font-size: 10px;
  font-weight: 500;
  border: 1px solid rgba(255,255,255,0.8);
  border-radius: 20px;
  padding: 2px 10px;
  cursor: pointer;
}

/* === HERO === */
.lp-hero {
  position: relative;
  height: 220px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}
.lp-hero-bg {
  position: absolute; inset: 0;
  width: 100%; height: 100%;
  object-fit: cover;
  filter: saturate(0);
  z-index: 0;
}
.lp-hero-overlay {
  position: absolute; inset: 0;
  background: var(--lp-primary);
  opacity: 0.8;
  z-index: 1;
}
.lp-hero-content {
  position: relative;
  z-index: 2;
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 8px;
  align-items: flex-start; /* Ensures content is left aligned within container */
}
.lp-hero-title { font-size: 15px; font-weight: 600; color: #fff; line-height: 1.35; margin: 0; max-width: 480px; }
.lp-hero-desc { font-size: 10px; color: rgba(255,255,255,0.9); line-height: 1.6; margin: 0; max-width: 480px; }
.lp-hero-btn {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  background: #000;
  color: #fff;
  font-size: 10px;
  font-weight: 500;
  padding: 5px 12px;
  border-radius: 20px;
  width: fit-content;
  cursor: pointer;
}

/* === ABOUT === */
.lp-about {
  background: #fff;
  padding: 32px 0;
}
.lp-about-inner {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 32px;
}
.lp-about-inner > * { max-width: 50%; }
.lp-about-img {
  width: 110px;
  height: 110px;
  object-fit: cover;
  border-radius: 6px;
  flex-shrink: 0;
  max-width: none;
}
.lp-about-text { flex: 1; display: flex; flex-direction: column; gap: 8px; }
.lp-section-label { display: flex; align-items: center; gap: 6px; }
.lp-label-line { width: 16px; height: 2px; background: var(--lp-primary); flex-shrink: 0; }
.lp-about-title { font-size: 13px; font-weight: 600; margin: 0; }
.lp-about-desc { font-size: 10px; color: #666; line-height: 1.6; margin: 0; }

/* === STATS === */
.lp-stats {
  position: relative;
  padding: 32px 0;
  display: flex;
  justify-content: center;
  overflow: hidden;
}
.lp-stats-bg {
  position: absolute; inset: 0;
  width: 100%; height: 100%;
  object-fit: cover;
  filter: saturate(0);
  z-index: 0;
}
.lp-stats-overlay {
  position: absolute; inset: 0;
  background: var(--lp-primary);
  opacity: 0.8;
  z-index: 1;
}
.lp-stats-content {
  position: relative;
  z-index: 2;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
}
.lp-stats-title { font-size: 13px; font-weight: 600; color: #fff; text-align: center; margin: 0; }
.lp-stats-subtitle { font-size: 10px; color: rgba(255,255,255,0.85); text-align: center; margin: 0; max-width: 320px; }
.lp-stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2px; width: 100%; }
.lp-stat-card {
  background: rgba(255,255,255,0.2);
  backdrop-filter: blur(4px);
  padding: 12px 8px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 5px;
}
.lp-stat-icon {
  width: 28px; height: 28px;
  background: rgba(255,255,255,0.2);
  border-radius: 6px;
  display: flex; align-items: center; justify-content: center;
  color: #fff;
}
.lp-stat-num { font-size: 16px; font-weight: 700; color: #fff; }
.lp-stat-label { font-size: 9px; font-weight: 500; color: #fff; text-align: center; }
.lp-stats-btn {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  background: #fff;
  color: var(--lp-primary);
  font-size: 10px;
  font-weight: 500;
  padding: 5px 14px;
  border-radius: 20px;
  cursor: pointer;
}

/* === FOOTER === */
.lp-footer { background: #121212; color: #fff; }
.lp-footer-body {
  display: flex;
  gap: 0;
}
.lp-footer-brand { 
  flex: 1.2; 
  padding: 16px 16px 16px 0; 
  display: flex; 
  flex-direction: column; 
  gap: 8px; 
  border-top: 4px solid var(--lp-primary); 
}
.lp-footer-logo-box {
  width: 36px; height: 36px;
  background: #fff;
  border-radius: 4px;
  display: flex; align-items: center; justify-content: center;
}
.lp-footer-logo-img { height: 26px; width: auto; object-fit: contain; }
.lp-footer-desc { font-size: 9.5px; color: #999; line-height: 1.5; margin: 0; }
.lp-footer-socials { display: flex; gap: 4px; flex-wrap: wrap; }
.lp-social-dot {
  width: 18px; height: 18px;
  background: #1a1a1a;
  border-radius: 2px;
  display: inline-block;
}
.lp-footer-menu { flex: 0.8; padding: 16px 12px; }
.lp-footer-contact { flex: 1.2; padding: 16px 0 16px 12px; }
.lp-footer-heading { font-size: 11px; font-weight: 600; margin: 0 0 8px; }
.lp-footer-links { display: flex; flex-direction: column; gap: 5px; font-size: 10px; color: #999; }
.lp-footer-contact-list { display: flex; flex-direction: column; gap: 5px; }
.lp-footer-contact-item {
  display: flex; gap: 5px; align-items: flex-start;
  font-size: 9.5px; color: #999;
}
.lp-icon-shrink { flex-shrink: 0; margin-top: 1px; }
.lp-footer-bottom {
  padding: 8px 0;
  background: #0a0a0a;
  text-align: left;
  font-size: 9px;
  color: #666;
}

/* === BLOG === */
.lp-blog { background: #f3f4f6; padding: 32px 0; }
.lp-blog-label { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
.lp-blog-heading { font-size: 14px; font-weight: 700; color: #111827; margin: 0; }
.lp-blog-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 16px; }
.lp-blog-card {
  background: #fff;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 14px;
  gap: 12px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.lp-blog-card-inner { display: flex; flex-direction: column; gap: 8px; }
.lp-blog-date {
  background: var(--lp-primary);
  color: #fff;
  font-size: 8px;
  font-weight: 600;
  padding: 3px 6px;
  display: inline-block;
  width: fit-content;
  border-radius: 2px;
}
.lp-blog-card-title { font-size: 10px; font-weight: 700; color: #111827; margin: 0; line-height: 1.4; }
.lp-blog-card-desc { font-size: 9px; color: #6b7280; margin: 0; line-height: 1.5; }
.lp-blog-card-footer { display: flex; flex-direction: column; gap: 8px; margin-top: 4px; }
.lp-blog-hr { border: none; border-top: 1px solid #e5e7eb; margin: 0; }
.lp-blog-read { font-size: 9px; font-weight: 700; color: #374151; display: flex; align-items: center; gap: 2px; }
.lp-blog-more-wrap { display: flex; justify-content: flex-start; }
.lp-blog-more {
  display: inline-flex;
  align-items: center;
  border: 1px solid var(--lp-primary);
  color: var(--lp-primary);
  background: transparent;
  padding: 4px 14px;
  border-radius: 20px;
  font-size: 9.5px;
  font-weight: 600;
  cursor: pointer;
}
</style>
