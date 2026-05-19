<template>
  <AppLayout>
    <Head :title="isSearchResult ? `Hasil Pencarian: ${filters.q} - AMK` : 'Blog - AMK'" />

    <!-- Page Header -->
    <div class="page-header">
      <div class="page-header-inner">
        <h1 class="page-title">{{ isSearchResult ? `Hasil Pencarian Untuk: ${filters.q}` : 'Blog' }}</h1>
        <div class="breadcrumb" v-if="!isSearchResult">
          <Link :href="route('home')">Beranda</Link> &gt; Blog
        </div>
        <div class="breadcrumb" v-else>
          <Link :href="route('home')">Beranda</Link> &gt;
          <Link :href="route('blog.index')">Blog</Link> &gt;
          Hasil Pencarian Untuk: {{ filters.q }}
        </div>
      </div>
    </div>

    <div class="container blog-wrapper">
      <!-- Sidebar Kategori (only non-search) -->
      <aside class="sidebar" v-if="!isSearchResult">
        <div class="sidebar-card">
          <h3 class="sidebar-title">Kategori</h3>
          <ul class="cat-list">
            <li v-for="cat in categories" :key="cat.id">
              <Link
                :href="route('blog.index', { kategori: cat.slug })"
                :class="{ 'cat-active': filters.kategori === cat.slug || (cat.slug === 'semua' && !filters.kategori) }"
              >{{ cat.name }}</Link>
            </li>
          </ul>
        </div>
      </aside>

      <!-- Posts -->
      <main class="posts-main" :class="{ 'full-width': isSearchResult }">
        <!-- Search Bar -->
        <form @submit.prevent="doSearch" class="search-bar">
          <label class="search-label">Cari blog</label>
          <div class="search-row">
            <input v-model="searchQuery" type="text" placeholder="Cari blog..." class="search-input" />
            <button type="submit" class="btn-search">Cari</button>
          </div>
        </form>

        <!-- Post list non-search -->
        <template v-if="!isSearchResult">
          <article v-for="post in posts.data" :key="post.id" class="post-list-item">
            <div class="post-date-badge">{{ post.published_at }}</div>
            <h2 class="post-list-title">
              <Link :href="route('blog.show', post.slug)">{{ post.title }}</Link>
            </h2>
            <p class="post-list-excerpt">{{ post.excerpt }}</p>
          </article>
        </template>

        <!-- Search result list -->
        <template v-else>
          <article v-for="post in posts.data" :key="post.id" class="post-search-item">
            <h2 class="post-search-title">
              <Link :href="route('blog.show', post.slug)">{{ post.title }}</Link>
            </h2>
            <p class="post-search-excerpt">{{ post.excerpt }}</p>
          </article>
        </template>

        <!-- Pagination -->
        <div class="pagination" v-if="posts.last_page > 1">
          <component
            :is="posts.prev_page_url ? Link : 'span'"
            :href="posts.prev_page_url"
            class="page-nav-btn"
            :class="{ 'disabled': !posts.prev_page_url }"
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
            Sebelumnya
          </component>
          
          <div class="page-numbers">
            <Link
              v-for="page in posts.last_page"
              :key="page"
              :href="posts.links[page]?.url || '#'"
              class="page-num-btn"
              :class="{ 'active': page === posts.current_page }"
            >{{ page }}</Link>
          </div>

          <component
            :is="posts.next_page_url ? Link : 'span'"
            :href="posts.next_page_url"
            class="page-nav-btn"
            :class="{ 'disabled': !posts.next_page_url }"
          >
            Berikutnya
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
          </component>
        </div>
      </main>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  posts: Object,
  categories: Array,
  filters: Object,
  isSearchResult: Boolean,
});

const searchQuery = ref(props.filters?.q || '');

const doSearch = () => {
  router.get(route('blog.index'), { q: searchQuery.value }, { preserveState: true });
};
</script>

<style scoped>
.page-header {
  background: var(--primary-color);
  padding: 110px 0 32px; /* Increased top padding for overlay navbar */
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
.breadcrumb a:hover { color: #fff; }

.container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.blog-wrapper { display: grid; grid-template-columns: 260px 1fr; gap: 32px; padding: 32px 24px; }

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
.cat-list li a:hover, .cat-active { color: var(--primary-color) !important; font-weight: 600; }

/* Search */
.search-label { font-size: 14px; font-weight: 600; color: #111; display: block; margin-bottom: 8px; }
.search-row { display: flex; gap: 0; margin-bottom: 24px; }
.search-input {
  flex: 1;
  border: 1px solid #d1d5db;
  border-right: none;
  padding: 8px 14px;
  font-size: 14px;
  border-radius: 4px 0 0 4px;
  outline: none;
}
.search-input:focus { border-color: var(--primary-color); }
.btn-search {
  background: var(--primary-color);
  color: #fff;
  border: none;
  padding: 8px 20px;
  font-size: 14px;
  font-weight: 600;
  border-radius: 0 4px 4px 0;
  cursor: pointer;
  transition: background 0.2s;
}
.btn-search:hover { background: var(--primary-color); }

/* Post list */
.post-list-item, .post-search-item {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  padding: 20px;
  margin-bottom: 16px;
}
.post-date-badge {
  background: var(--primary-color);
  color: #fff;
  font-size: 11px;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 3px;
  display: inline-block;
  margin-bottom: 10px;
}
.post-list-title, .post-search-title {
  font-size: 16px;
  font-weight: 700;
  color: #111;
  margin-bottom: 10px;
  border-bottom: 1px solid #f3f4f6;
  padding-bottom: 10px;
}
.post-list-title a, .post-search-title a { color: inherit; text-decoration: none; }
.post-list-title a:hover, .post-search-title a:hover { color: var(--primary-color); }
.post-list-excerpt, .post-search-excerpt { font-size: 13.5px; color: #666; line-height: 1.7; }

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 16px;
  margin-top: 24px;
  width: 100%;
}

.page-numbers {
  display: flex;
  align-items: center;
  gap: 8px;
}

.page-nav-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  background: transparent;
  border: none;
  font-size: 14px;
  font-weight: 500;
  color: var(--primary-color);
  cursor: pointer;
  padding: 8px 12px;
  border-radius: 8px;
  transition: all 0.2s ease;
  text-decoration: none;
}

.page-nav-btn:hover:not(:disabled):not(.disabled) {
  background: #eff6ff;
}

.page-nav-btn:disabled, .page-nav-btn.disabled {
  color: #9ca3af;
  cursor: not-allowed;
}

.page-num-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  background: transparent;
  border: 1px solid transparent;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  color: #4b5563;
  cursor: pointer;
  transition: all 0.2s ease;
  text-decoration: none;
}

.page-num-btn:hover:not(.active) {
  background: #f3f4f6;
  color: #111;
}

.page-num-btn.active {
  background: var(--primary-color);
  color: #fff;
  font-weight: 600;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.page-num-btn.active:hover {
  filter: brightness(1.1);
}

.full-width { grid-column: 1 / -1; }
</style>



