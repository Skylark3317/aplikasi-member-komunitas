<template>
  <div class="stat-card">
    <!-- Title + Period Buttons -->
    <div class="card-top">
      <button v-if="detailUrl" class="card-title card-title-link" @click="goDetail">
        {{ title }}
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      </button>
      <span v-else class="card-title">{{ title }}</span>
      <div class="period-group">
        <button v-for="p in PERIODS" :key="p.key"
          :class="['pd-btn', activePeriod === p.key ? 'active' : '']"
          @click="setPeriod(p.key)">{{ p.label }}</button>
      </div>
    </div>

    <!-- Live / Hover / Pinned Value -->
    <div class="live-block">
      <div class="live-val">{{ displayValue }}</div>
      <div class="live-lbl">{{ displayLabel }}</div>
      <div v-if="trendDiff !== 0" :class="['trend-pill', trendDiff >= 0 ? 'up' : 'dn']">
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

    <!-- SVG Line Chart -->
    <div class="chart-box" ref="chartEl"
      @mousemove="onMove" @mouseleave="onLeave" @click="onClickChart">
      <svg class="chart-svg" viewBox="0 0 600 130" preserveAspectRatio="none">
        <defs>
          <linearGradient :id="gid" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" :stop-color="series[0]?.color ?? '#3b82f6'" stop-opacity="0.25"/>
            <stop offset="100%" :stop-color="series[0]?.color ?? '#3b82f6'" stop-opacity="0.01"/>
          </linearGradient>
        </defs>
        <!-- Area fill: only for first series -->
        <path v-if="areaPath(0)" :d="areaPath(0)" :fill="`url(#${gid})`"/>
        <!-- Lines: one per series -->
        <polyline
          v-for="(s, si) in series" :key="si"
          :points="linePoints(si)"
          fill="none"
          :stroke="s.color"
          :stroke-width="si === 0 ? 2.2 : 1.8"
          :stroke-dasharray="si > 0 ? '0' : '0'"
          stroke-linecap="round"
          stroke-linejoin="round"
          :opacity="si === 0 ? 1 : 0.75"
        />
        <!-- Live marker dots — one per series -->
        <template v-if="activeIdx === null">
          <circle
            v-for="(s, si) in series" :key="'live-'+si"
            v-if="liveCoords[si]"
            :cx="liveCoords[si].x" :cy="liveCoords[si].y"
            :r="si === 0 ? 5 : 4"
            :fill="s.color"
            stroke="white" stroke-width="2"
          />
        </template>
        <!-- Hover / pinned dots — one per series -->
        <template v-if="activeIdx !== null">
          <circle
            v-for="(s, si) in series" :key="'hover-'+si"
            v-if="allCoords[si]?.[activeIdx]"
            :cx="allCoords[si][activeIdx].x" :cy="allCoords[si][activeIdx].y"
            :r="si === 0 ? 6 : 4.5"
            :fill="s.color"
            stroke="white" :stroke-width="si === 0 ? 2.5 : 2"
          />
        </template>
        <!-- Hover vertical line -->
        <line
          v-if="activeIdx !== null && allCoords[0]?.[activeIdx]"
          :x1="allCoords[0][activeIdx].x" y1="0"
          :x2="allCoords[0][activeIdx].x" y2="130"
          stroke="#9ca3af" stroke-width="1" stroke-dasharray="4 3"
        />
        <!-- Live vertical line (when not hovering) -->
        <line
          v-if="activeIdx === null && liveCoords[0]"
          :x1="liveCoords[0].x" y1="0"
          :x2="liveCoords[0].x" y2="130"
          stroke="#9ca3af" stroke-width="1" stroke-dasharray="4 3"
        />
      </svg>

      <!-- Tooltip -->
      <div v-if="activeIdx !== null" class="tip" :style="tipStyle">
        <div v-for="(s, si) in series" :key="si" class="tip-row">
          <span class="tip-dot" :style="{ background: s.color }"/>
          <span class="tip-lbl">{{ s.label }}:</span>
          <span class="tip-val">{{ formatVal(activeSeriesData(si)[activeIdx]) }}</span>
        </div>
        <div class="tip-date">{{ activeLabels[activeIdx] }}</div>
        <div v-if="isPinned" class="tip-pin"><i class="bi bi-pin-angle-fill"></i> Klik lagi untuk lepas</div>
      </div>
    </div>

    <!-- X-axis labels -->
    <div class="x-row">
      <span v-for="(l, i) in xAxisLabels" :key="i" class="x-lbl"
        :style="{ left: xPct(i) + '%' }">{{ l }}</span>
    </div>

    <!-- Legend -->
    <div class="legend-row">
      <div v-for="s in series" :key="s.label" class="lg-item">
        <span class="lg-dot" :style="{ background: s.color }"/>
        <span class="lg-lbl">{{ s.label }}</span>
      </div>
    </div>

    <!-- Nav (prev/next) -->
    <div v-if="activePeriod !== 'Max'" class="nav-row">
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

const MONTH_FULL = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const PERIODS = [{ key:'1M', label:'1B' }, { key:'1Y', label:'1T' }, { key:'Max', label:'Maks' }];
const W = 600, H = 130, PT = 12, PB = 12;

const props = defineProps({
  title:     { type: String, default: '' },
  stats:     { type: Array,  default: () => [] },
  series:    { type: Array,  default: () => [] }, // [{label, color, data1M, data1Y, dataMax}]
  labels1M:  { type: Array,  default: () => [] },
  labels1Y:  { type: Array,  default: () => [] },
  labelsMax: { type: Array,  default: () => [] },
  year:      { type: Number, default: 2026 },
  month:     { type: Number, default: 1 },
  today:     { type: Number, default: 1 },
  thisMonth: { type: Number, default: 1 },
  thisYear:  { type: Number, default: 2026 },
  detailUrl:     { type: String,  default: '' },
  unit:          { type: String, default: '' },
  defaultPeriod: { type: String, default: '1Y' },
});

const emit = defineEmits(['prev', 'next']);

function goDetail() {
  if (props.detailUrl) router.visit(props.detailUrl);
}

const activePeriod = ref(props.defaultPeriod);
function setPeriod(k) { activePeriod.value = k; }

// Active data per series
function activeSeriesData(si) {
  const s = props.series[si];
  if (!s) return [];
  if (activePeriod.value === '1M') return s.data1M ?? [];
  if (activePeriod.value === '1Y') return s.data1Y ?? [];
  return s.dataMax ?? [];
}

const activeLabels = computed(() => {
  if (activePeriod.value === '1M') return props.labels1M;
  if (activePeriod.value === '1Y') return props.labels1Y;
  return props.labelsMax;
});

// Live time index
const liveIdx = computed(() => {
  const n = activeSeriesData(0).length;
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
  // Max: find thisYear in labelsMax
  const idx = props.labelsMax.indexOf(String(props.thisYear));
  return idx >= 0 ? idx : n - 1;
});

// Y scale across all series
const globalMax = computed(() => {
  let m = 0.01;
  for (let si = 0; si < props.series.length; si++) {
    const d = activeSeriesData(si);
    const mx = Math.max(...d.map(v => Number(v) || 0));
    if (mx > m) m = mx;
  }
  return m;
});
const globalMin = computed(() => {
  let mn = 0;
  for (let si = 0; si < props.series.length; si++) {
    const d = activeSeriesData(si);
    const mx = Math.min(...d.map(v => Number(v) || 0));
    if (mx < mn) mn = mx;
  }
  return mn;
});
const valRange = computed(() => globalMax.value - globalMin.value || 0.01);

function toX(i, n) { return n <= 1 ? W / 2 : (i / (n - 1)) * W; }
function toY(v) {
  return PT + (1 - (Number(v) - globalMin.value) / valRange.value) * (H - PT - PB);
}

// Coords per series
const allCoords = computed(() =>
  props.series.map((_, si) => {
    const d = activeSeriesData(si);
    return d.map((v, i) => ({ x: toX(i, d.length), y: toY(v) }));
  })
);

const liveCoords = computed(() =>
  allCoords.value.map(c => c[liveIdx.value] ?? null)
);

function linePoints(si) {
  const c = allCoords.value[si] ?? [];
  return c.map(p => `${p.x},${p.y}`).join(' ');
}

function areaPath(si) {
  const c = allCoords.value[si] ?? [];
  if (!c.length) return '';
  const bot = H - PB;
  return `M${c[0].x},${bot} L${c[0].x},${c[0].y} ` +
    c.slice(1).map(p => `L${p.x},${p.y}`).join(' ') +
    ` L${c[c.length - 1].x},${bot} Z`;
}

// Hover + pinned
const chartEl  = ref(null);
const hoverIdx = ref(null);
const pinnedIdx = ref(null);
const isPinned  = computed(() => pinnedIdx.value !== null);
const activeIdx = computed(() => pinnedIdx.value ?? hoverIdx.value);

const hoverX = ref(0);

function idxFromEvent(e) {
  const rect = chartEl.value.getBoundingClientRect();
  const pct  = (e.clientX - rect.left) / rect.width;
  const n    = activeSeriesData(0).length;
  return Math.max(0, Math.min(n - 1, Math.round(pct * (n - 1))));
}
function onMove(e) {
  if (!isPinned.value) {
    hoverIdx.value = idxFromEvent(e);
    hoverX.value = e.clientX - chartEl.value.getBoundingClientRect().left;
  }
}
function onLeave() { if (!isPinned.value) hoverIdx.value = null; }
function onClickChart(e) {
  const idx = idxFromEvent(e);
  if (isPinned.value && pinnedIdx.value === idx) {
    pinnedIdx.value = null; // unpin same spot
  } else {
    pinnedIdx.value = idx; // pin new spot
  }
  hoverX.value = e.clientX - chartEl.value.getBoundingClientRect().left;
}

const tipStyle = computed(() => {
  if (!chartEl.value || activeIdx.value === null) return {};
  const w = chartEl.value.getBoundingClientRect().width;
  const x = activeIdx.value !== null
    ? (allCoords.value[0]?.[activeIdx.value]?.x ?? 0) / W * w
    : hoverX.value;
  const left = x > w / 2 ? x - 160 : x + 14;
  return { left: `${Math.max(0, left)}px`, top: '6px' };
});

// Display value (top big number) = SUM of all series at displayIdx
const displayIdx = computed(() => activeIdx.value ?? liveIdx.value);
const displayValue = computed(() => {
  const total = props.series.reduce((sum, _, si) => {
    const d = activeSeriesData(si);
    return sum + Number(d[displayIdx.value] ?? 0);
  }, 0);
  return formatVal(total);
});
const displayLabel = computed(() => activeLabels.value[displayIdx.value] ?? '');

// Trend: sum of all series at liveIdx vs sum at first index
const trendDiff = computed(() => {
  const liveSum  = props.series.reduce((s, _, si) => s + Number(activeSeriesData(si)[liveIdx.value]  ?? 0), 0);
  const firstSum = props.series.reduce((s, _, si) => s + Number(activeSeriesData(si)[0] ?? 0), 0);
  return Math.round((liveSum - firstSum) * 100) / 100;
});
const trendPct = computed(() => {
  const firstSum = props.series.reduce((s, _, si) => s + Number(activeSeriesData(si)[0] ?? 0), 0);
  if (!firstSum) return '0.00';
  return ((trendDiff.value / firstSum) * 100).toFixed(2);
});

// Nav label
const navLabel = computed(() => {
  if (activePeriod.value === '1M') return `${MONTH_FULL[props.month - 1]} ${props.year}`;
  return `${props.year}`;
});

// X-axis labels (5 evenly spaced)
const xAxisLabels = computed(() => {
  const src = activeLabels.value;
  if (!src.length) return [];
  const target = Math.min(5, src.length);
  return Array.from({ length: target }, (_, i) =>
    src[Math.round(i * (src.length - 1) / (target - 1))]
  );
});
function xPct(i) {
  const n = xAxisLabels.value.length;
  return n <= 1 ? 50 : (i / (n - 1)) * 100;
}

const gid = computed(() => `g-${props.title.replace(/\s+/g,'')}`);

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

/* Chart */
.chart-box { position: relative; height: 140px; cursor: crosshair; user-select: none; }
.chart-svg { width: 100%; height: 100%; display: block; }

/* Tooltip */
.tip {
  position: absolute; background: #fff; border: 1px solid #e5e7eb;
  border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,.12);
  padding: 10px 14px; pointer-events: none; min-width: 140px; z-index: 20;
}
.tip-row { display: flex; align-items: center; gap: 6px; margin-bottom: 3px; }
.tip-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.tip-lbl { font-size: 11px; color: #6b7280; flex: 1; }
.tip-val { font-size: 13px; font-weight: 700; color: #111; }
.tip-date { font-size: 11px; color: #9ca3af; margin-top: 4px; border-top: 1px solid #f3f4f6; padding-top: 4px; }
.tip-pin { font-size: 10px; color: #9ca3af; margin-top: 2px; }

/* X-axis */
.x-row { position: relative; height: 16px; }
.x-lbl { position: absolute; transform: translateX(-50%); font-size: 10.5px; color: #9ca3af; white-space: nowrap; }

/* Legend */
.legend-row { display: flex; gap: 12px; flex-wrap: wrap; }
.lg-item { display: flex; align-items: center; gap: 5px; }
.lg-dot { width: 8px; height: 8px; border-radius: 50%; }
.lg-lbl { font-size: 11.5px; color: #6b7280; }

/* Nav */
.nav-row { display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #f3f4f6; padding-top: 8px; }
.nav-btn { display: flex; align-items: center; gap: 4px; background: none; border: none; cursor: pointer; font-size: 12px; color: #6b7280; padding: 4px 8px; border-radius: 6px; transition: background .15s; }
.nav-btn:hover { background: #f3f4f6; color: #111; }
.nav-btn svg { width: 14px; height: 14px; }
.nav-center { font-size: 12px; font-weight: 700; color: #374151; }

@media print {
  .stat-card { break-inside: avoid; border: 1px solid #ccc; }
  .period-group, .nav-row { display: none; }
  .chart-box { height: 120px; }
}
</style>
