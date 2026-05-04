<template>
  <AppLayout>
    <Head title="Beranda - AMK" />

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-overlay">
        <div class="hero-content">
          <h1>Bangun Koneksi dan Tumbuh Bersama</h1>
          <p>Terhubung dengan individu dari berbagai latar belakang, berbagi ide, dan membangun kolaborasi dalam komunitas inklusif untuk berkembang bersama serta menciptakan dampak nyata.</p>
          <Link :href="route('register')" class="btn-hero">Gabung membership →</Link>
        </div>
      </div>
    </section>

    <!-- Tentang Section -->
    <section class="tentang-section">
      <div class="container tentang-grid">
        <div class="tentang-img">
          <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=500&auto=format" alt="Komunitas AMK" />
        </div>
        <div class="tentang-text">
          <h2 class="section-heading"><span class="heading-bar"></span> Tentang</h2>
          <p>Komunitas ini adalah ruang terbuka bagi siapa saja yang ingin belajar, berkembang, dan saling terhubung dalam lingkungan yang positif dan kolaboratif. Kami menghadirkan berbagai kesempatan untuk bertukar wawasan, membangun relasi, serta berpartisipasi dalam kegiatan yang mendorong pertumbuhan pribadi maupun profesional.</p>
          <p>Dengan semangat kebersamaan, kami percaya bahwa setiap individu memiliki potensi untuk memberikan kontribusi dan menciptakan dampak yang berarti. Di sini, kamu tidak hanya menjadi bagian dari komunitas, tetapi juga bagian dari perjalanan untuk tumbuh dan berkembang bersama.</p>
        </div>
      </div>
    </section>

    <!-- Berita Terbaru -->
    <section class="berita-section">
      <div class="container">
        <div class="berita-header">
          <h2 class="section-heading"><span class="heading-bar"></span> Berita Terbaru</h2>
          <Link :href="route('blog.index')" class="btn-lihat-semua">Lihat semua →</Link>
        </div>
        <div class="posts-grid">
          <article v-for="post in latestPosts" :key="post.id" class="post-card">
            <div class="post-date">{{ post.published_at }}</div>
            <h3 class="post-title">{{ post.title }}</h3>
            <p class="post-excerpt">{{ post.excerpt }}</p>
            <Link :href="route('blog.show', post.slug)" class="post-read-more">Lanjutkan membaca →</Link>
          </article>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
  latestPosts: Array,
});
</script>

<style scoped>
/* Hero */
.hero {
  background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 50%, #2563eb 100%);
  min-height: 340px;
  position: relative;
  overflow: hidden;
}
.hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background: url('https://images.unsplash.com/photo-1531545514256-b1400bc00f31?w=1200&auto=format') center/cover;
  opacity: 0.25;
}
.hero-overlay {
  position: relative;
  z-index: 1;
  max-width: 1200px;
  margin: 0 auto;
  padding: 72px 24px 80px;
}
.hero-content { max-width: 560px; }
.hero-content h1 {
  font-size: 32px;
  font-weight: 700;
  color: #fff;
  line-height: 1.3;
  margin-bottom: 16px;
}
.hero-content p {
  font-size: 14px;
  color: rgba(255,255,255,0.88);
  line-height: 1.7;
  margin-bottom: 28px;
}
.btn-hero {
  display: inline-block;
  background: #fff;
  color: #2563eb;
  font-weight: 600;
  font-size: 14px;
  padding: 10px 24px;
  border-radius: 4px;
  text-decoration: none;
  transition: background 0.2s, transform 0.2s;
}
.btn-hero:hover { background: #eff6ff; transform: translateX(2px); }

/* Container */
.container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }

/* Tentang */
.tentang-section { padding: 64px 0; background: #fff; }
.tentang-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 56px;
  align-items: center;
}
.tentang-img img {
  width: 100%;
  height: 260px;
  object-fit: cover;
  border-radius: 4px;
}
.tentang-text p { font-size: 13.5px; color: #555; line-height: 1.8; margin-bottom: 14px; }

/* Section Heading */
.section-heading {
  font-size: 20px;
  font-weight: 700;
  color: #111;
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 24px;
}
.heading-bar {
  display: inline-block;
  width: 28px;
  height: 3px;
  background: #2563eb;
  border-radius: 2px;
}

/* Berita */
.berita-section { padding: 56px 0; background: #f9fafb; }
.berita-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 32px;
}
.btn-lihat-semua {
  font-size: 13px;
  color: #2563eb;
  border: 1px solid #2563eb;
  padding: 6px 16px;
  border-radius: 4px;
  text-decoration: none;
  transition: background 0.2s, color 0.2s;
}
.btn-lihat-semua:hover { background: #2563eb; color: #fff; }
.posts-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}
.post-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  padding: 18px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  transition: box-shadow 0.2s, transform 0.2s;
}
.post-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); transform: translateY(-2px); }
.post-date {
  background: #2563eb;
  color: #fff;
  font-size: 11px;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 3px;
  display: inline-block;
  width: fit-content;
}
.post-title {
  font-size: 14px;
  font-weight: 700;
  color: #111;
  line-height: 1.4;
}
.post-excerpt {
  font-size: 12.5px;
  color: #666;
  line-height: 1.6;
  flex: 1;
}
.post-read-more {
  font-size: 12.5px;
  color: #2563eb;
  text-decoration: none;
  font-weight: 500;
  margin-top: 8px;
}
.post-read-more:hover { text-decoration: underline; }
</style>
