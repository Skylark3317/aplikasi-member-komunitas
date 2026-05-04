<template>
  <AppLayout>
    <Head :title="`${post.title} - AMK`" />

    <!-- Page Header -->
    <div class="page-header">
      <div class="page-header-inner">
        <h1 class="page-title">Blog</h1>
        <div class="breadcrumb">
          <Link :href="route('home')">Beranda</Link> &gt;
          <Link :href="route('blog.index')">Blog</Link> &gt;
          <Link :href="route('blog.index', { kategori: post.category_slug })">{{ post.category }}</Link> &gt;
          {{ post.title }}
        </div>
      </div>
    </div>

    <div class="container blog-detail-wrapper">
      <!-- Article -->
      <main class="article-main">
        <div class="article-card">
          <div class="post-date-badge">{{ post.published_at }}</div>
          <h2 class="article-title">{{ post.title }}</h2>
          <div class="article-body" v-html="post.content"></div>
          <div class="article-meta">
            <span class="meta-bar"></span>
            oleh <strong>{{ post.author }}</strong> dalam
            <Link :href="route('blog.index', { kategori: post.category_slug })" class="cat-link">{{ post.category }}</Link>
          </div>
        </div>
      </main>

      <!-- Sidebar -->
      <aside class="sidebar">
        <div class="sidebar-card">
          <h3 class="sidebar-title">Kategori</h3>
          <ul class="cat-list">
            <li v-for="cat in categories" :key="cat.id">
              <Link
                :href="route('blog.index', { kategori: cat.slug })"
                :class="{ 'cat-active': post.category_slug === cat.slug }"
              >{{ cat.name }}</Link>
            </li>
          </ul>
        </div>
      </aside>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
  post: Object,
  categories: Array,
});
</script>

<style scoped>
.page-header {
  background: #2563eb;
  padding: 20px 0;
}
.page-header-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.page-title { color: #fff; font-size: 22px; font-weight: 700; }
.breadcrumb { color: rgba(255,255,255,0.8); font-size: 13px; }
.breadcrumb a { color: rgba(255,255,255,0.8); text-decoration: none; }

.container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.blog-detail-wrapper {
  display: grid;
  grid-template-columns: 1fr 280px;
  gap: 32px;
  padding: 32px 24px;
}

/* Article */
.article-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  padding: 28px;
}
.post-date-badge {
  background: #2563eb;
  color: #fff;
  font-size: 11px;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 3px;
  display: inline-block;
  margin-bottom: 14px;
}
.article-title {
  font-size: 22px;
  font-weight: 700;
  color: #111;
  margin-bottom: 20px;
  line-height: 1.4;
}
.article-body {
  font-size: 14px;
  color: #444;
  line-height: 1.8;
}
.article-body :deep(p) { margin-bottom: 14px; }
.article-meta {
  margin-top: 24px;
  padding-top: 16px;
  border-top: 1px solid #e5e7eb;
  font-size: 13px;
  color: #666;
  display: flex;
  align-items: center;
  gap: 6px;
}
.meta-bar {
  display: inline-block;
  width: 24px;
  height: 2px;
  background: #2563eb;
}
.cat-link { color: #2563eb; text-decoration: none; font-weight: 600; }

/* Sidebar */
.sidebar-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 6px; padding: 20px; }
.sidebar-title { font-size: 15px; font-weight: 700; color: #111; margin-bottom: 16px; }
.cat-list { list-style: none; padding: 0; }
.cat-list li a {
  display: block;
  padding: 8px 0;
  font-size: 14px;
  color: #555;
  text-decoration: none;
  border-bottom: 1px solid #f3f4f6;
  transition: color 0.2s;
}
.cat-list li a:hover, .cat-active { color: #2563eb !important; font-weight: 600; }
</style>
