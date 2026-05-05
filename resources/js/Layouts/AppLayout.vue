<template>
  <div class="app-wrapper">
    <header class="site-header" :class="{ 'is-home': isHome }">
      <!-- Top Bar -->
      <div class="topbar">
        <div class="topbar-inner">
          <div class="topbar-contacts">
            <a v-if="settings.email" :href="`mailto:${settings.email}`"><span class="icon">✉</span> {{ settings.email }}</a>
            <a v-if="settings.phone" :href="`tel:${settings.phone}`"><span class="icon">📞</span> {{ settings.phone }}</a>
            <a v-if="settings.social_youtube" :href="settings.social_youtube" target="_blank"><span class="icon">▶</span> YouTube</a>
            <a v-if="settings.social_instagram" :href="settings.social_instagram" target="_blank"><span class="icon">📷</span> Instagram</a>
          </div>
        </div>
      </div>

      <!-- Main Navbar -->
      <nav class="navbar">
        <div class="navbar-inner">
          <div class="nav-links-center">
            <Link :href="route('home')" :class="{ active: isActive('home') }">TENTANG</Link>
            <Link :href="route('blog.index')" :class="{ active: isActive('blog') }">BLOG</Link>
            <a href="#kontak">KONTAK</a>
            <Link :href="route('register')" class="btn-membership" :style="{ color: 'var(--primary-color, #2563eb) !important' }">Membership</Link>
          </div>
        </div>
      </nav>

      <!-- Brand Overlapping Box -->
      <div class="brand-absolute-wrapper">
        <div class="brand-absolute-inner">
          <div class="brand-absolute">
            <div v-if="settings.community_logo" class="brand-img">
              <img :src="`/storage/${settings.community_logo}`" alt="Logo" />
            </div>
            <span class="brand-logo">{{ settings.community_name || 'AMK' }}</span>
          </div>
        </div>
      </div>
    </header>

    <!-- Page Content -->
    <main>
      <slot />
    </main>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="footer-top">
        <div class="footer-brand">
          <div v-if="settings.community_logo" class="footer-img">
            <img :src="`/storage/${settings.community_logo}`" alt="Logo" />
          </div>
          <div v-else class="footer-logo">{{ settings.community_name || 'AMK' }}</div>
          <p style="white-space: pre-line;">{{ settings.about_description || 'Komunitas ini adalah ruang terbuka bagi siapa saja yang ingin belajar, berkembang, dan saling terhubung dalam lingkungan yang positif dan kolaboratif.' }}</p>
          <p class="footer-sosmed-label">Media Sosial</p>
          <div class="footer-socials">
            <a v-if="settings.social_x" :href="settings.social_x" title="X">𝕏</a>
            <a v-if="settings.social_facebook" :href="settings.social_facebook" title="Facebook">f</a>
            <a v-if="settings.social_linkedin" :href="settings.social_linkedin" title="LinkedIn">in</a>
            <a v-if="settings.social_skype" :href="settings.social_skype" title="Skype">☎</a>
            <a v-if="settings.social_instagram" :href="settings.social_instagram" title="Instagram">◎</a>
            <a v-if="settings.social_youtube" :href="settings.social_youtube" title="YouTube">▶</a>
          </div>
        </div>
        <div class="footer-menu">
          <h4>Menu</h4>
          <ul>
            <li><Link :href="route('home')">TENTANG</Link></li>
            <li><Link :href="route('blog.index')">BLOG</Link></li>
            <li><a href="#kontak">KONTAK</a></li>
            <li><Link :href="route('register')">MEMBERSHIP</Link></li>
          </ul>
        </div>
        <div class="footer-contact" id="kontak">
          <h4>Kontak</h4>
          <p v-if="settings.phone"><span>📞</span> {{ settings.phone }}</p>
          <p v-if="settings.email"><span>✉</span> {{ settings.email }}</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© {{ new Date().getFullYear() }}</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const settings = computed(() => page.props.settings || {});

const isHome = computed(() => page.component === 'Home');

const isActive = (routeName) => {
  const current = page.url;
  if (routeName === 'home') return current === '/';
  if (routeName === 'blog') return current.startsWith('/blog');
  return false;
};
</script>

<style scoped>
/* ── Header ────────────────────────────────────── */
.site-header {
  position: sticky;
  top: 0;
  z-index: 100;
}
.site-header.is-home {
  margin-bottom: -56px; /* Pulls hero section under the navbar */
}

/* ── Top Bar ───────────────────────────────────── */
.topbar {
  background: #fff;
  border-bottom: 1px solid #eee;
  height: 36px;
  display: flex;
  align-items: center;
}
.topbar-inner {
  max-width: 1200px;
  width: 100%;
  margin: 0 auto;
  padding: 0 24px;
  display: flex;
  justify-content: flex-end;
}
.topbar-contacts {
  display: flex;
  gap: 20px;
}
.topbar-contacts a {
  font-size: 13px;
  color: #444;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 4px;
}
.topbar-contacts a:hover { color: var(--primary-color); }

/* ── Navbar ────────────────────────────────────── */
.navbar {
  height: 56px;
  display: flex;
  align-items: center;
}
.site-header.is-home .navbar {
  background: rgba(0, 0, 0, 0.43);
}
.site-header:not(.is-home) .navbar {
  background: var(--primary-color);
}

.navbar-inner {
  max-width: 1200px;
  width: 100%;
  margin: 0 auto;
  padding: 0 24px;
  display: flex;
  justify-content: center;
}
.nav-links-center {
  display: flex;
  align-items: center;
  gap: 32px;
}
.nav-links-center a:not(.btn-membership) {
  color: #fff;
  font-size: 14px;
  font-weight: 500;
  letter-spacing: 0.5px;
  text-decoration: none;
  opacity: 100%;
  transition: opacity 0.2s;
}
.nav-links-center a:not(.btn-membership):hover, .nav-links-center a.active {
  opacity: 1;
}

.btn-membership {
  background: #fff;
  color: var(--primary-color) !important;
  padding: 6px 18px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 13px;
  text-decoration: none;
  transition: background 0.2s, color 0.2s;
}
.btn-membership:hover {
  background: var(--primary-color);
  color: #fff !important;
}

/* ── Brand Overlapping Box ─────────────────────── */
.brand-absolute-wrapper {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 0; /* Ensures it doesn't block clicks */
}
.brand-absolute-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
  position: relative;
}
.brand-absolute {
  position: absolute;
  top: 0;
  left: 24px;
  background: #fff;
  height: 92px; /* Covers both topbar (36px) and navbar (56px) */
  padding: 0 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border-bottom-right-radius: 8px;
  border-bottom-left-radius: 8px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  gap: 4px;
  pointer-events: auto; /* Re-enable clicks inside the logo box */
}
.brand-img img {
  height: 60px;
  width: auto;
  object-fit: contain;
}
.brand-logo {
  font-weight: 800;
  font-size: 13px;
  letter-spacing: 0.5px;
  color: var(--primary-color, #111);
  text-align: center;
}

/* ── Footer ────────────────────────────────────── */
.site-footer {
  background: #111;
  color: #aaa;
  margin-top: auto;
}
.footer-top {
  max-width: 1200px;
  margin: 0 auto;
  padding: 48px 24px 32px;
  display: grid;
  grid-template-columns: 2fr 1fr 1fr;
  gap: 48px;
}
.footer-img img {
  height: 48px;
  width: auto;
  object-fit: contain;
  margin-bottom: 16px;
}
.footer-logo {
  font-weight: 700;
  font-size: 18px;
  border: 2px solid #fff;
  color: #fff;
  padding: 6px 14px;
  display: inline-block;
  margin-bottom: 16px;
}
.footer-brand p { font-size: 13px; line-height: 1.7; margin-bottom: 10px; }
.footer-sosmed-label { color: #fff; font-weight: 600; margin-top: 12px; margin-bottom: 8px !important; }
.footer-socials { display: flex; gap: 12px; }
.footer-socials a {
  color: #aaa;
  font-size: 16px;
  text-decoration: none;
  transition: color 0.2s;
}
.footer-socials a:hover { color: #fff; }
.footer-menu h4, .footer-contact h4 {
  color: #fff;
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 16px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.footer-menu ul { list-style: none; padding: 0; }
.footer-menu ul li { margin-bottom: 10px; }
.footer-menu ul li a {
  color: #aaa;
  text-decoration: none;
  font-size: 13px;
  transition: color 0.2s;
}
.footer-menu ul li a:hover { color: #fff; }
.footer-contact p { font-size: 13px; line-height: 1.8; display: flex; gap: 8px; }
.footer-bottom {
  border-top: 1px solid #222;
  text-align: center;
  padding: 16px;
  font-size: 12px;
  color: #555;
}
</style>



