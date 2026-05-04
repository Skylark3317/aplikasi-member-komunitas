<template>
  <AdminLayout>
    <Head title="Statistik - AMK Admin" />

    <div class="statistik-header">
      <h1 class="page-heading">Statistik</h1>
      <button class="btn-print" @click="window.print()">🖨 Cetak statistik</button>
    </div>

    <div class="stats-grid">
      <!-- Member -->
      <div class="stat-card">
        <div class="card-top">
          <h3 class="card-title">Statistik Member Bulan {{ currentMonth }}</h3>
          <div class="toggle-group">
            <button :class="['toggle-btn', memberMode === 'bulanan' ? 'active' : '']" @click="memberMode = 'bulanan'">Bulanan</button>
            <button :class="['toggle-btn', memberMode === 'tahunan' ? 'active' : '']" @click="memberMode = 'tahunan'">Tahunan</button>
          </div>
        </div>
        <div class="stat-numbers">
          <div class="stat-num"><span class="num">{{ stats.member.total }}</span><span class="lbl">Semua</span><small class="trend up">↗ +5 dari bulan lalu</small></div>
          <div class="stat-num"><span class="num">{{ stats.member.aktif }}</span><span class="lbl">Aktif</span><small class="trend up">↗ +13 dari bulan lalu</small></div>
          <div class="stat-num"><span class="num">{{ stats.member.nonaktif }}</span><span class="lbl">Nonaktif</span><small class="trend down">↘ -3 dari bulan lalu</small></div>
        </div>
        <div class="chart-wrap">
          <Bar :data="memberChartData" :options="chartOptions" />
        </div>
        <div class="chart-legend">
          <span class="legend-dot blue"></span> Aktif
          <span class="legend-dot gray"></span> Nonaktif
        </div>
        <div class="card-nav">
          <button class="nav-arrow">‹</button>
          <button class="nav-arrow">›</button>
        </div>
      </div>

      <!-- Konten -->
      <div class="stat-card">
        <div class="card-top">
          <h3 class="card-title">Statistik Konten Bulan {{ currentMonth }}</h3>
          <div class="toggle-group">
            <button class="toggle-btn active">Bulanan</button>
            <button class="toggle-btn">Tahunan</button>
          </div>
        </div>
        <div class="stat-numbers">
          <div class="stat-num"><span class="num">{{ stats.blog.total }}</span><span class="lbl">Semua</span><small class="trend up">↗ +2 dari bulan lalu</small></div>
          <div class="stat-num"><span class="num">{{ Math.floor(stats.blog.total * 0.52) }}</span><span class="lbl">Video</span><small class="trend up">↗ +1 dari bulan lalu</small></div>
          <div class="stat-num"><span class="num">{{ Math.floor(stats.blog.total * 0.48) }}</span><span class="lbl">Ebook</span><small class="trend neutral">= sama dengan bulan lalu</small></div>
        </div>
        <div class="chart-wrap">
          <Bar :data="kontenChartData" :options="chartOptions" />
        </div>
        <div class="chart-legend">
          <span class="legend-dot blue"></span> Video
          <span class="legend-dot gray"></span> Ebook
        </div>
        <div class="card-nav">
          <button class="nav-arrow">‹</button>
          <button class="nav-arrow">›</button>
        </div>
      </div>

      <!-- Blog -->
      <div class="stat-card">
        <div class="card-top">
          <h3 class="card-title">Statistik Blog Bulan {{ currentMonth }}</h3>
          <div class="toggle-group">
            <button class="toggle-btn active">Bulanan</button>
            <button class="toggle-btn">Tahunan</button>
          </div>
        </div>
        <div class="stat-numbers">
          <div class="stat-num"><span class="num">{{ stats.blog.total }}</span><span class="lbl">Total Blog</span><small class="trend down">↘ -2 dari bulan lalu</small></div>
        </div>
        <div class="chart-wrap">
          <Bar :data="blogChartData" :options="chartOptions" />
        </div>
        <div class="card-nav">
          <button class="nav-arrow">‹</button>
          <button class="nav-arrow">›</button>
        </div>
      </div>

      <!-- Pertanyaan -->
      <div class="stat-card">
        <div class="card-top">
          <h3 class="card-title">Statistik Pertanyaan Bulan {{ currentMonth }}</h3>
          <div class="toggle-group">
            <button class="toggle-btn active">Bulanan</button>
            <button class="toggle-btn">Tahunan</button>
          </div>
        </div>
        <div class="stat-numbers">
          <div class="stat-num"><span class="num">100</span><span class="lbl">Semua</span><small class="trend up">↗ +2 dari bulan lalu</small></div>
          <div class="stat-num"><span class="num">92</span><span class="lbl">Dijawab</span><small class="trend up">↗ +1 dari bulan lalu</small></div>
          <div class="stat-num"><span class="num">8</span><span class="lbl">Belum dijawab</span><small class="trend neutral">= sama dengan bulan lalu</small></div>
        </div>
        <div class="chart-wrap">
          <Bar :data="pertanyaanChartData" :options="chartOptions" />
        </div>
        <div class="chart-legend">
          <span class="legend-dot blue"></span> Dijawab
          <span class="legend-dot gray"></span> Belum Dijawab
        </div>
        <div class="card-nav">
          <button class="nav-arrow">‹</button>
          <button class="nav-arrow">›</button>
        </div>
      </div>
    </div>

    <!-- Pendapatan — full width -->
    <div class="stat-card stat-card-wide">
      <div class="card-top">
        <h3 class="card-title">Statistik Pendapatan Bulan {{ currentMonth }}</h3>
        <div class="toggle-group">
          <button class="toggle-btn active">Bulanan</button>
          <button class="toggle-btn">Tahunan</button>
        </div>
      </div>
      <div class="stat-numbers">
        <div class="stat-num">
          <span class="num">Rp{{ formatCurrency(stats.payment.diterima) }}</span>
          <span class="lbl">Diterima</span>
          <small class="trend up">↗ +Rp7jt dari bulan lalu</small>
        </div>
        <div class="stat-num">
          <span class="num">Rp{{ formatCurrency(stats.payment.ditolak) }}</span>
          <span class="lbl">Ditolak</span>
          <small class="trend up">↗ +Rp500rb dari bulan lalu</small>
        </div>
        <div class="stat-num">
          <span class="num">Rp{{ formatCurrency(stats.payment.menunggu) }}</span>
          <span class="lbl">Menunggu Verifikasi</span>
          <small class="trend down">↘ -4,5jt dari bulan lalu</small>
        </div>
      </div>
      <div class="chart-wrap">
        <Bar :data="pendapatanChartData" :options="chartOptions" />
      </div>
      <div class="chart-legend">
        <span class="legend-dot blue"></span> Diterima
        <span class="legend-dot red"></span> Ditolak
        <span class="legend-dot gray"></span> Menunggu Verifikasi
      </div>
      <div class="card-nav">
        <button class="nav-arrow">‹</button>
        <button class="nav-arrow">›</button>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Bar } from 'vue-chartjs';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Tooltip,
  Legend,
} from 'chart.js';
import AdminLayout from '@/Layouts/AdminLayout.vue';

ChartJS.register(CategoryScale, LinearScale, BarElement, Tooltip, Legend);

const props = defineProps({
  months: Array,
  currentMonth: String,
  stats: Object,
});

const memberMode = ref('bulanan');

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { display: false } },
  scales: {
    x: { grid: { display: false } },
    y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
  },
};

const memberChartData = computed(() => ({
  labels: props.months,
  datasets: [
    {
      label: 'Aktif',
      data: props.stats.member.aktifData,
      backgroundColor: '#2563eb',
      borderRadius: 3,
    },
    {
      label: 'Nonaktif',
      data: props.stats.member.nonaktifData,
      backgroundColor: '#e5e7eb',
      borderRadius: 3,
    },
  ],
}));

const kontenChartData = computed(() => ({
  labels: props.months,
  datasets: [
    { label: 'Video', data: props.months.map(() => Math.floor(Math.random() * 15) + 5), backgroundColor: '#2563eb', borderRadius: 3 },
    { label: 'Ebook', data: props.months.map(() => Math.floor(Math.random() * 10) + 3), backgroundColor: '#e5e7eb', borderRadius: 3 },
  ],
}));

const blogChartData = computed(() => ({
  labels: props.months,
  datasets: [
    { label: 'Blog', data: props.stats.blog.data, backgroundColor: '#2563eb', borderRadius: 3 },
  ],
}));

const pertanyaanChartData = computed(() => ({
  labels: props.months,
  datasets: [
    { label: 'Dijawab', data: props.months.map(() => Math.floor(Math.random() * 40) + 50), backgroundColor: '#2563eb', borderRadius: 3 },
    { label: 'Belum Dijawab', data: props.months.map(() => Math.floor(Math.random() * 5) + 1), backgroundColor: '#e5e7eb', borderRadius: 3 },
  ],
}));

const pendapatanChartData = computed(() => ({
  labels: props.months,
  datasets: [
    { label: 'Diterima', data: props.stats.payment.diterimaData, backgroundColor: '#2563eb', borderRadius: 3 },
    { label: 'Ditolak', data: props.stats.payment.ditolakData, backgroundColor: '#ef4444', borderRadius: 3 },
    { label: 'Menunggu', data: props.stats.payment.menungguData, backgroundColor: '#e5e7eb', borderRadius: 3 },
  ],
}));

const formatCurrency = (val) => {
  if (val >= 1_000_000) return (val / 1_000_000).toFixed(1) + 'jt';
  if (val >= 1_000) return (val / 1_000).toFixed(0) + 'rb';
  return val;
};
</script>

<style scoped>
.statistik-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}
.page-heading { font-size: 22px; font-weight: 700; color: #111; }
.btn-print {
  background: #2563eb; color: #fff; border: none;
  padding: 8px 18px; border-radius: 20px; font-size: 13px;
  font-weight: 600; cursor: pointer; transition: background 0.2s;
  display: flex; align-items: center; gap: 6px;
}
.btn-print:hover { background: #1d4ed8; }

.stats-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 20px;
}
.stat-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  padding: 20px 22px;
}
.stat-card-wide { margin-bottom: 20px; }

.card-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}
.card-title { font-size: 13.5px; font-weight: 600; color: #111; }

.toggle-group { display: flex; border-radius: 20px; overflow: hidden; border: 1px solid #e5e7eb; }
.toggle-btn {
  padding: 4px 14px;
  font-size: 12px;
  border: none;
  background: #fff;
  color: #555;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
}
.toggle-btn.active { background: #2563eb; color: #fff; }

.stat-numbers { display: flex; gap: 32px; margin-bottom: 16px; }
.stat-num { display: flex; flex-direction: column; gap: 2px; }
.num { font-size: 26px; font-weight: 700; color: #111; }
.lbl { font-size: 12px; color: #6b7280; }
.trend { font-size: 11px; }
.trend.up { color: #16a34a; }
.trend.down { color: #dc2626; }
.trend.neutral { color: #6b7280; }

.chart-wrap { height: 180px; margin-bottom: 8px; }

.chart-legend { display: flex; gap: 16px; font-size: 12px; color: #666; align-items: center; margin-bottom: 12px; }
.legend-dot {
  display: inline-block;
  width: 8px; height: 8px;
  border-radius: 50%;
}
.legend-dot.blue { background: #2563eb; }
.legend-dot.gray { background: #e5e7eb; }
.legend-dot.red { background: #ef4444; }

.card-nav { display: flex; justify-content: space-between; }
.nav-arrow {
  background: none; border: 1px solid #e5e7eb; border-radius: 50%;
  width: 28px; height: 28px; display: flex; align-items: center;
  justify-content: center; cursor: pointer; font-size: 16px; color: #555;
  transition: background 0.2s;
}
.nav-arrow:hover { background: #f3f4f6; }
</style>
