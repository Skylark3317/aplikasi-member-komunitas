<template>
  <div class="stat-card">
    <!-- Title + Period Buttons -->
    <div class="card-top">
      <button v-if="detailUrl" class="card-title card-title-link" @click="goDetail">
        {{ title }}
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      </button>
      <span v-else class="card-title">{{ title }}</span>
      <div v-if="variant !== 'donut'" class="period-group">
        <button v-for="p in PERIODS" :key="p.key"
          :class="['pd-btn', activePeriod === p.key ? 'active' : '']"
          @click="setPeriod(p.key)">{{ p.label }}</button>
      </div>
    </div>

    <!-- Live / Total Value -->
    <div class="live-block">
      <div class="live-val">{{ displayValue }}</div>
      <div class="live-lbl">{{ displayLabel }}</div>
      <div v-if="variant !== 'donut' && trendDiff !== 0" :class="['trend-pill', trendDiff >= 0 ? 'up' : 'dn']">
        {{ trendDiff >= 0 ? '▲' : '▼' }} {{ Math.abs(trendDiff) }}
        <span class="tp-pct">({{ trendPct }}%)</span>
      </div>
    </div>

    <!-- Stats Row (overall DB totals) -->
    <div class="stats-row">
      <div v-for="s in stats" :key="s.label" class="st-it">
        <span class="st-val">{{ s.value }}</span>
        <span class="st-lbl">{{ s.label }}</span>
      </div>
    </div>

    <!-- Vue Chart.js Charts -->
    <div class="chart-container" :class="variant">
      
      <!-- Main Chart Area -->
      <div v-if="variant !== 'donut'" class="chart-wrap main-chart">
        <Line v-if="isLine" :data="chartData" :options="chartOptions" />
        <Bar v-else-if="isBar" :data="chartData" :options="chartOptions" />
      </div>

      <!-- Donut Chart -->
      <div v-if="variant === 'donut' || variant === 'stacked-area'" class="chart-wrap donut-chart" :class="{ 'side-donut': variant === 'stacked-area' }">
        <Doughnut :data="donutData" :options="donutOptions" />
        <div v-if="variant === 'donut'" class="donut-center-text">
          <div class="pct-val">{{ donutPercentage }}%</div>
          <div class="pct-lbl">Selesai</div>
        </div>
      </div>

    </div>

    <!-- Nav (prev/next) -->
    <div v-if="variant !== 'donut' && activePeriod !== 'Max'" class="nav-row">
      <button class="nav-btn" @click="$emit('prev', activePeriod)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        {{ activePeriod === '1M' ? 'Bulan sebelumnya' : 'Tahun sebelumnya' }}
      </button>
      <span class="nav-center">{{ navLabel }}</span>
      <button class="nav-btn" @click="$emit('next', activePeriod)">
        {{ activePeriod === '1M' ? 'Bulan berikutnya' : 'Tahun berikutnya' }}
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, BarElement, ArcElement, Title, Tooltip, Legend, Filler } from 'chart.js';
import { Line, Bar, Doughnut } from 'vue-chartjs';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, ArcElement, Title, Tooltip, Legend, Filler);

const MONTH_FULL = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const PERIODS = [{ key:'1M', label:'1B' }, { key:'1Y', label:'1T' }, { key:'Max', label:'Maks' }];

const props = defineProps({
  title:     { type: String, default: '' },
  stats:     { type: Array,  default: () => [] },
  series:    { type: Array,  default: () => [] },
  labels1M:  { type: Array,  default: () => [] },
  labels1Y:  { type: Array,  default: () => [] },
  labelsMax: { type: Array,  default: () => [] },
  year:      { type: Number, default: 2026 },
  month:     { type: Number, default: 1 },
  today:     { type: Number, default: 1 },
  thisMonth: { type: Number, default: 1 },
  thisYear:  { type: Number, default: 2026 },
  detailUrl: { type: String,  default: '' },
  unit:      { type: String, default: '' },
  defaultPeriod: { type: String, default: '1Y' },
  variant: {
    type: String,
    default: 'smooth-area', // stacked-area, stacked-column, grouped-bar, donut, smooth-area
  },
});

const emit = defineEmits(['prev', 'next']);

function goDetail() {
  if (props.detailUrl) router.visit(props.detailUrl);
}

const activePeriod = ref(props.defaultPeriod);
function setPeriod(k) { activePeriod.value = k; }

function activeSeriesData(s) {
  if (activePeriod.value === '1M') return s.data1M ?? [];
  if (activePeriod.value === '1Y') return s.data1Y ?? [];
  return s.dataMax ?? [];
}

const activeLabels = computed(() => {
  if (activePeriod.value === '1M') return props.labels1M;
  if (activePeriod.value === '1Y') return props.labels1Y;
  return props.labelsMax;
});

const isLine = computed(() => props.variant === 'stacked-area' || props.variant === 'smooth-area');
const isBar = computed(() => props.variant === 'stacked-column' || props.variant === 'grouped-bar');
const isDonut = computed(() => props.variant === 'donut');

// -- Chart Data --
const chartData = computed(() => {
  return {
    labels: activeLabels.value,
    datasets: props.series.map((s, i) => {
      let typeConfig = {};
      let bg = s.color;

      if (props.variant === 'stacked-area') {
        typeConfig = { fill: true, tension: 0.1 };
        bg = s.color + '40'; // 25% opacity
      } else if (props.variant === 'smooth-area') {
        typeConfig = { fill: true, tension: 0.4 };
        bg = s.color + '40';
      }

      return {
        label: s.label,
        backgroundColor: bg,
        borderColor: s.color,
        borderWidth: isBar.value ? 0 : 2,
        pointRadius: 1,
        pointHoverRadius: 4,
        data: activeSeriesData(s),
        ...typeConfig
      };
    })
  };
});

const chartOptions = computed(() => {
  const isStacked = props.variant === 'stacked-area' || props.variant === 'stacked-column';
  return {
    responsive: true,
    maintainAspectRatio: false,
    resizeDelay: 0,
    plugins: {
      legend: { display: true, position: 'bottom', labels: { boxWidth: 10, font: { size: 11, family: 'Inter' } } },
      tooltip: { 
        mode: 'index', 
        intersect: false,
        callbacks: {
          label: function(context) {
            let label = context.dataset.label || '';
            if (label) label += ': ';
            let val = context.parsed.y;
            if (props.unit === 'jt') {
              label += `Rp${val.toFixed(2).replace('.', ',')}jt`;
            } else {
              label += val.toLocaleString('id-ID');
            }
            return label;
          }
        }
      }
    },
    scales: {
      x: { stacked: isStacked, grid: { display: false } },
      y: { 
        stacked: isStacked, 
        border: { display: false }, 
        grid: { color: '#f3f4f6' },
        ticks: {
          callback: function(value) {
            if (props.unit === 'jt') return value + 'jt';
            return value;
          }
        }
      }
    },
    interaction: { mode: 'nearest', axis: 'x', intersect: false }
  };
});

// -- Donut Data --
const donutData = computed(() => {
  // Use stats array (skip 'Total' usually at index 0)
  const items = props.stats.length > 2 ? props.stats.slice(1) : props.stats;
  
  // Extract number from string if formatted like "Rp1.5jt"
  const parseVal = (v) => {
    if (typeof v === 'number') return v;
    const n = parseFloat(String(v).replace(/[^0-9.]/g, ''));
    return isNaN(n) ? 0 : n;
  };

  return {
    labels: items.map(s => s.label),
    datasets: [{
      data: items.map(s => parseVal(s.value)),
      backgroundColor: props.series.map(s => s.color).concat(['#e5e7eb']),
      borderWidth: 0,
      hoverOffset: 4
    }]
  };
});

const donutOptions = computed(() => ({
  responsive: true,
  maintainAspectRatio: false,
  resizeDelay: 0,
  cutout: '75%',
  plugins: {
    legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11, family: 'Inter' } } },
    tooltip: {
      callbacks: {
        label: function(context) {
          let label = context.label || '';
          if (label) label += ': ';
          
          let val = context.parsed;
          if (props.unit === 'jt') {
             label += `Rp${val.toFixed(2).replace('.', ',')}jt`;
          } else {
             label += val.toLocaleString('id-ID');
          }

          const dataset = context.dataset;
          const total = dataset.data.reduce((acc, curr) => acc + curr, 0);
          if (total > 0) {
            const percentage = Math.round((val / total) * 100);
            label += ` (${percentage}%)`;
          }
          
          return label;
        }
      }
    }
  }
}));

const donutPercentage = computed(() => {
  if (props.stats.length >= 3) {
    const total = props.stats[0].value;
    const answered = props.stats[1].value;
    if (total > 0) return Math.round((answered / total) * 100);
  }
  return 0;
});

// -- Live Block Calculations --
const liveIdx = computed(() => {
  const s = props.series[0];
  if (!s) return 0;
  const d = activeSeriesData(s);
  const n = d.length;
  if (!n) return 0;

  if (activePeriod.value === '1Y') {
    if (props.year === props.thisYear) return Math.min(props.thisMonth - 1, n - 1);
    return n - 1;
  }
  if (activePeriod.value === '1M') {
    if (props.year === props.thisYear && props.month === props.thisMonth)
      return Math.min(props.today - 1, n - 1);
    return n - 1;
  }
  // Max
  const idx = props.labelsMax.indexOf(String(props.thisYear));
  return idx >= 0 ? idx : n - 1;
});

const displayValue = computed(() => {
  if (props.variant === 'donut') {
    return props.stats[0]?.value || 0; // Total
  }
  const total = props.series.reduce((sum, s) => {
    return sum + Number(activeSeriesData(s)[liveIdx.value] ?? 0);
  }, 0);
  return formatVal(total);
});

const displayLabel = computed(() => {
  if (props.variant === 'donut') return 'Total Keseluruhan';
  return activeLabels.value[liveIdx.value] ?? '';
});

const trendDiff = computed(() => {
  const liveSum  = props.series.reduce((s, sObj) => s + Number(activeSeriesData(sObj)[liveIdx.value]  ?? 0), 0);
  const firstSum = props.series.reduce((s, sObj) => s + Number(activeSeriesData(sObj)[0] ?? 0), 0);
  return Math.round((liveSum - firstSum) * 100) / 100;
});
const trendPct = computed(() => {
  const firstSum = props.series.reduce((s, sObj) => s + Number(activeSeriesData(sObj)[0] ?? 0), 0);
  if (!firstSum) return '0.00';
  return ((trendDiff.value / firstSum) * 100).toFixed(2);
});

const navLabel = computed(() => {
  if (activePeriod.value === '1M') return `${MONTH_FULL[props.month - 1]} ${props.year}`;
  return `${props.year}`;
});

function formatVal(v) {
  if (v === undefined || v === null) return '—';
  const n = Number(v);
  if (props.unit === 'jt') return `Rp${n.toFixed(2).replace('.', ',')}jt`;
  return n.toLocaleString('id-ID');
}
</script>

<style scoped>
.stat-card {
  background: #fff; border-radius: 14px; border: 1px solid #e5e7eb;
  padding: 18px 18px 12px; display: flex; flex-direction: column; gap: 10px;
  font-family: 'Inter', sans-serif;
  overflow: hidden;
}
.card-top { display: flex; justify-content: space-between; align-items: center; }
.card-title { font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: .05em; }

/* Period buttons */
.period-group { display: flex; gap: 2px; }
.pd-btn { padding: 4px 10px; border-radius: 20px; border: none; font-size: 12px; font-weight: 500; cursor: pointer; background: transparent; color: #6b7280; transition: background .15s; }
.card-title-link {
  background: none; border: none; padding: 0; cursor: pointer;
  display: flex; align-items: center; gap: 5px; text-decoration: none;
  transition: color .15s;
}
.card-title-link:hover { color: var(--primary-color); }
.card-title-link svg { width: 11px; height: 11px; opacity: .7; }
.pd-btn.active { background: #dbeafe; color: var(--primary-color); font-weight: 700; }

/* Live block */
.live-block { display: flex; flex-direction: column; gap: 2px; }
.live-val { font-size: 28px; font-weight: 800; color: #111; line-height: 1.1; }
.live-lbl { font-size: 12px; color: #9ca3af; }
.trend-pill { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 600; padding: 2px 8px; border-radius: 20px; width: fit-content; }
.trend-pill.up { background: #dcfce7; color: #16a34a; }
.trend-pill.dn { background: #fee2e2; color: #dc2626; }
.tp-pct { font-weight: 400; opacity: .75; }

/* Stats row */
.stats-row { display: flex; gap: 16px; flex-wrap: wrap; padding: 8px 0; border-top: 1px solid #f3f4f6; border-bottom: 1px solid #f3f4f6; }
.st-it { display: flex; flex-direction: column; gap: 1px; }
.st-val { font-size: 15px; font-weight: 700; color: #111; }
.st-lbl { font-size: 11px; color: #9ca3af; }

/* Chart Container */
.chart-container {
  display: flex;
  gap: 16px;
  margin-top: 4px;
}

/* chart-wrap must be position:relative with a FIXED height — required by Chart.js ResizeObserver */
.chart-wrap {
  position: relative;
}
.main-chart {
  flex: 1;
  min-width: 0;
  position: relative;
  height: 200px;
  overflow: hidden;
}
.donut-chart {
  position: relative;
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}
.side-donut {
  width: 150px;
  flex-shrink: 0;
}
.donut-center-text {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  pointer-events: none;
}
.pct-val {
  font-size: 28px;
  font-weight: 800;
  color: #111;
  line-height: 1;
}
.pct-lbl {
  font-size: 11px;
  color: #6b7280;
  margin-top: 4px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-weight: 600;
}

/* Nav */
.nav-row { display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #f3f4f6; padding-top: 8px; margin-top: auto; }
.nav-btn { display: flex; align-items: center; gap: 4px; background: none; border: none; cursor: pointer; font-size: 12px; color: #6b7280; padding: 4px 8px; border-radius: 6px; transition: background .15s; }
.nav-btn:hover { background: #f3f4f6; color: #111; }
.nav-btn svg { width: 14px; height: 14px; }
.nav-center { font-size: 12px; font-weight: 700; color: #374151; }

@media print {
  .stat-card { break-inside: avoid; border: 1px solid #ccc; width: 100% !important; max-width: 100% !important; box-sizing: border-box; }
  .period-group, .nav-row { display: none !important; }
  .chart-container, .main-chart, .donut-chart { width: 100% !important; max-width: 100% !important; }
  canvas { max-width: 100% !important; height: auto !important; }
}
</style>
