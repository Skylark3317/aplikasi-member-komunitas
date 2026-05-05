<template>
  <AppLayout>
    <Head title="Beranda - AMK" />

    <!-- Hero Section -->
    <section class="hero" :style="heroStyle">
      <div class="hero-overlay">
        <div class="hero-content">
          <h1>{{ settings.hero_title || 'Bangun Koneksi dan Tumbuh Bersama' }}</h1>
          <p>{{ settings.hero_description || 'Terhubung dengan individu dari berbagai latar belakang, berbagi ide, dan membangun kolaborasi dalam komunitas inklusif untuk berkembang bersama serta menciptakan dampak nyata.' }}</p>
          <Link :href="route('register')" class="btn-hero">Gabung membership →</Link>
        </div>
      </div>
    </section>

    <!-- Tentang Section -->
    <section class="tentang-section">
      <div class="container tentang-grid">
        <div class="tentang-img">
          <img :src="aboutImage" alt="Tentang Komunitas" />
        </div>
        <div class="tentang-text">
          <h2 class="section-heading"><span class="heading-bar"></span> {{ settings.about_title || 'Tentang' }}</h2>
          <p style="white-space: pre-line;">{{ settings.about_description || 'Komunitas ini adalah ruang terbuka bagi siapa saja yang ingin belajar, berkembang, dan saling terhubung dalam lingkungan yang positif dan kolaboratif.' }}</p>
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
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
  latestPosts: Array,
});

const $page = usePage();
const settings = computed(() => $page.props.settings || {});

const heroStyle = computed(() => {
  if (settings.value.bg_image) {
    return {
      '--hero-bg': `url('/storage/${settings.value.bg_image}')`
    };
  }
  return {};
});

const aboutImage = computed(() => {
  if (settings.value.about_image) {
    return `/storage/${settings.value.about_image}`;
  }
  return 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=500&auto=format';
});
</script>

<style scoped>
/* Hero */
.hero {
  background: var(--primary-color, var(--primary-color));
  min-height: 340px;
  position: relative;
  overflow: hidden;
}
.hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background: var(--hero-bg, url('https://images.unsplash.com/photo-1531545514256-b1400bc00f31?w=1200&auto=format')) center/cover;
  opacity: 0.25;
}
.hero-overlay {
  position: relative;
  z-index: 1;
  max-width: 1200px;
  margin: 0 auto;
  padding: 110px 24px 80px;
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
  color: var(--primary-color);
  font-weight: 600;
  font-size: 14px;
  padding: 10px 24px;
  border-radius: 4px;
  text-decoration: none;
  transition: background 0.2s, transform 0.2s;
}
.btn-hero:hover { background: #f3f4f6; transform: translateX(2px); }

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
.tentang-img {
  display: flex;
  justify-content: center;
}
.tentang-img img {
  width: 80%;
  max-width: 340px;
  aspect-ratio: 1 / 1;
  object-fit: cover;
  border-radius: 8px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.05);
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
  background: var(--primary-color);
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
  color: var(--primary-color);
  border: 1px solid var(--primary-color);
  padding: 6px 16px;
  border-radius: 4px;
  text-decoration: none;
  transition: background 0.2s, color 0.2s;
}
.btn-lihat-semua:hover { background: var(--primary-color); color: #fff; }
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
  background: var(--primary-color);
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
  color: var(--primary-color);
  text-decoration: none;
  font-weight: 500;
  margin-top: 8px;
}
.post-read-more:hover { text-decoration: underline; }
</style>



