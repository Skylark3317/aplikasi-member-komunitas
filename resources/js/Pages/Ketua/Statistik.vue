<template>
  <KetuaLayout>
    <div class="page-header">
      <h1 class="page-title">Statistik</h1>
      <button class="btn-cetak" @click="handleCetak">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <polyline points="6 9 6 2 18 2 18 9"/>
          <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
          <rect x="6" y="14" width="12" height="8"/>
        </svg>
        Cetak statistik
      </button>
    </div>

    <div class="stats-grid">

      <StatCard title="Statistik Member"
        :series="stats.member.series"
        :stats="stats.member.stats"
        :labels1M="labels1M" :labels1Y="monthNames" :labelsMax="maxYears"
        :year="navYear" :month="navMonth"
        :today="today" :thisMonth="thisMonth" :thisYear="thisYear"
        defaultPeriod="1Y"
        :detailUrl="route('ketua.statistik.detail', { type: 'member' })"
        @prev="navigate('prev', $event)" @next="navigate('next', $event)"
      />

      <StatCard title="Statistik Konten"
        :series="stats.konten.series"
        :stats="stats.konten.stats"
        :labels1M="labels1M" :labels1Y="monthNames" :labelsMax="maxYears"
        :year="navYear" :month="navMonth"
        :today="today" :thisMonth="thisMonth" :thisYear="thisYear"
        defaultPeriod="1Y"
        :detailUrl="route('ketua.statistik.detail', { type: 'konten' })"
        @prev="navigate('prev', $event)" @next="navigate('next', $event)"
      />

      <StatCard title="Statistik Blog"
        :series="stats.blog.series"
        :stats="stats.blog.stats"
        :labels1M="labels1M" :labels1Y="monthNames" :labelsMax="maxYears"
        :year="navYear" :month="navMonth"
        :today="today" :thisMonth="thisMonth" :thisYear="thisYear"
        defaultPeriod="1Y"
        :detailUrl="route('ketua.statistik.detail', { type: 'blog' })"
        @prev="navigate('prev', $event)" @next="navigate('next', $event)"
      />

      <StatCard title="Statistik Pertanyaan"
        :series="stats.pertanyaan.series"
        :stats="stats.pertanyaan.stats"
        :labels1M="labels1M" :labels1Y="monthNames" :labelsMax="maxYears"
        :year="navYear" :month="navMonth"
        :today="today" :thisMonth="thisMonth" :thisYear="thisYear"
        defaultPeriod="1Y"
        :detailUrl="route('ketua.statistik.detail', { type: 'pertanyaan' })"
        @prev="navigate('prev', $event)" @next="navigate('next', $event)"
      />

      <StatCard title="Statistik Pendapatan"
        :series="stats.payment.series"
        :stats="stats.payment.stats"
        :labels1M="labels1M" :labels1Y="monthNames" :labelsMax="maxYears"
        :year="navYear" :month="navMonth"
        :today="today" :thisMonth="thisMonth" :thisYear="thisYear"
        unit="jt" defaultPeriod="1M"
        class="pendapatan-card"
        :detailUrl="route('ketua.statistik.detail', { type: 'payment' })"
        @prev="navigate('prev', $event)" @next="navigate('next', $event)"
      />

    </div>
  </KetuaLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import KetuaLayout from '@/Layouts/KetuaLayout.vue';
import StatCard from './StatCard.vue';

const props = defineProps({
  currentYear:  Number,
  currentMonth: Number,
  monthNames:   Array,
  daysInMonth:  Number,
  maxYears:     Array,
  today:        Number,
  thisMonth:    Number,
  thisYear:     Number,
  stats:        Object,
});

const navYear  = ref(props.currentYear);
const navMonth = ref(props.currentMonth);

// Day labels: "1", "2", ..., "31"
const labels1M = computed(() =>
  Array.from({ length: props.daysInMonth }, (_, i) => String(i + 1))
);

function navigate(dir, period) {
  if (period === '1Y') {
    navYear.value += dir === 'prev' ? -1 : 1;
  } else {
    if (dir === 'prev') {
      if (navMonth.value === 1) { navMonth.value = 12; navYear.value--; }
      else navMonth.value--;
    } else {
      if (navMonth.value === 12) { navMonth.value = 1; navYear.value++; }
      else navMonth.value++;
    }
  }
  router.get(
    route('ketua.statistik'),
    { year: navYear.value, month: navMonth.value },
    { preserveState: true, preserveScroll: true, replace: true }
  );
}

function handleCetak() { window.print(); }
</script>

<style scoped>
.page-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 20px 0; border-bottom: 1px solid #e5e7eb; margin-bottom: 24px;
}
.page-title { font-size: 18px; font-weight: 700; color: #111; margin: 0; }
.btn-cetak {
  display: flex; align-items: center; gap: 8px;
  background: #2563eb; color: #fff; border: none;
  border-radius: 8px; padding: 9px 18px; font-size: 13.5px;
  font-weight: 500; cursor: pointer; transition: background .15s;
}
.btn-cetak:hover { background: #1d4ed8; }
.btn-cetak svg { width: 16px; height: 16px; }

.stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.pendapatan-card { grid-column: 2 / 3; }

@media print {
  .btn-cetak { display: none; }
  .stats-grid { grid-template-columns: 1fr 1fr !important; gap: 12px; }
}
</style>
