<template>
  <div class="app-wrapper">
    <!-- Top Bar -->
    <div class="topbar">
      <div class="topbar-inner">
        <span class="brand-logo">AMK</span>
        <div class="topbar-contacts">
          <a href="mailto:amk@mail.com"><span class="icon">✉</span> amk@mail.com</a>
          <a href="tel:08120000000"><span class="icon">📞</span> 0812 0000 0000</a>
          <a href="#" target="_blank"><span class="icon">▶</span> YouTube</a>
          <a href="#" target="_blank"><span class="icon">📷</span> Instagram</a>
        </div>
      </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar">
      <div class="navbar-inner">
        <div class="nav-links">
          <Link :href="route('home')" :class="{ active: isActive('home') }">TENTANG</Link>
          <Link :href="route('blog.index')" :class="{ active: isActive('blog') }">BLOG</Link>
          <a href="#kontak">KONTAK</a>
        </div>
        <div class="nav-actions">
          <Link :href="route('register')" class="btn-membership">Membership</Link>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main>
      <slot />
    </main>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="footer-top">
        <div class="footer-brand">
          <div class="footer-logo">AMK</div>
          <p>Komunitas ini adalah ruang terbuka bagi siapa saja yang ingin belajar, berkembang, dan saling terhubung dalam lingkungan yang positif dan kolaboratif. Kami menghadirkan berbagai kesempatan untuk bertukar wawasan, membangun relasi, serta berpartisipasi dalam kegiatan yang mendorong pertumbuhan pribadi maupun profesional.</p>
          <p>Dengan semangat kebersamaan, kami percaya bahwa setiap individu memiliki potensi untuk memberikan kontribusi dan menciptakan dampak yang berarti. Di sini, kamu tidak hanya menjadi bagian dari komunitas, tetapi juga bagian dari perjalanan untuk tumbuh dan berkembang bersama.</p>
          <p class="footer-sosmed-label">Media Sosial</p>
          <div class="footer-socials">
            <a href="#" title="X">𝕏</a>
            <a href="#" title="Facebook">f</a>
            <a href="#" title="LinkedIn">in</a>
            <a href="#" title="Skype">☎</a>
            <a href="#" title="Instagram">◎</a>
            <a href="#" title="YouTube">▶</a>
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
          <p><span>📍</span> Gedung Cipta Karya, Jl. Pahlawan Sejahtera No. 5, Surabaya</p>
          <p><span>📞</span> 0812 0000 0000</p>
          <p><span>✉</span> amk@mail.com</p>
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
const isActive = (routeName) => {
  const current = page.url;
  if (routeName === 'home') return current === '/';
  if (routeName === 'blog') return current.startsWith('/blog');
  return false;
};
</script>

<style scoped>
/* ── Top Bar ───────────────────────────────────── */
.topbar {
  background: #fff;
  border-bottom: 1px solid #e5e7eb;
  padding: 6px 0;
}
.topbar-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.brand-logo {
  font-weight: 700;
  font-size: 18px;
  letter-spacing: 2px;
  border: 2px solid #111;
  padding: 2px 10px;
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
.topbar-contacts a:hover { color: #2563eb; }

/* ── Navbar ────────────────────────────────────── */
.navbar {
  background: #2563eb;
  position: sticky;
  top: 0;
  z-index: 100;
}
.navbar-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 48px;
}
.nav-links {
  display: flex;
  gap: 32px;
}
.nav-links a, .nav-links .active {
  color: #fff;
  font-size: 14px;
  font-weight: 500;
  letter-spacing: 0.5px;
  text-decoration: none;
  opacity: 0.85;
  transition: opacity 0.2s;
}
.nav-links a:hover, .nav-links a.active { opacity: 1; }
.btn-membership {
  background: #fff;
  color: #2563eb !important;
  padding: 6px 18px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 13px;
  text-decoration: none;
  transition: background 0.2s, color 0.2s;
}
.btn-membership:hover {
  background: #1d4ed8;
  color: #fff !important;
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
