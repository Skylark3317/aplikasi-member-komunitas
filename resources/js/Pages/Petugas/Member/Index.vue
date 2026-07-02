<template>
  <PetugasLayout>
    <Head title="Data Member - AMK" />

    <div class="top-bar">
      <div>
        <h1 class="page-title">Data Member</h1>
      </div>
    </div>
    <div class="divider"></div>

    <div class="content-area">
      <!-- Detailed Member Statistics Chart -->
      <div class="stats-chart-wrapper mb-8">
        <StatCard title="Statistik Member"
          class="full-width-card"
          :series="stats.member.series"
          :stats="stats.member.stats"
          :labels1M="labels1M" :labels1Y="monthNames" :labelsMax="maxYears"
          :year="navYear" :month="navMonth"
          :today="today" :thisMonth="thisMonth" :thisYear="thisYear"
          defaultPeriod="1Y"
          variant="stacked-area"
          @prev="navigate('prev', $event)" @next="navigate('next', $event)"
        />
      </div>

      <!-- Filters Bar -->
      <div class="filters-bar">
        <div class="filters-left">
          <!-- Date Range Filter -->
          <div class="filter-group">
            <label class="filter-label">Mulai Tanggal</label>
            <input type="date" v-model="startDateFilter" class="filter-input date-input" />
          </div>
          <div class="filter-group">
            <label class="filter-label">Sampai Tanggal</label>
            <input type="date" v-model="endDateFilter" class="filter-input date-input" />
          </div>

          <!-- Status Filter -->
          <div class="filter-group">
            <label class="filter-label">Status</label>
            <select v-model="statusFilter" class="filter-input select-input">
              <option value="">Semua</option>
              <option value="premium">Premium</option>
              <option value="regular">Regular</option>
            </select>
          </div>

          <!-- Premium Plan Filter -->
          <div class="filter-group">
            <label class="filter-label">Paket Premium</label>
            <select v-model="planIdFilter" class="filter-input select-input" :disabled="statusFilter === 'regular'">
              <option value="">Semua Paket</option>
              <option v-for="plan in plans" :key="plan.id" :value="plan.id">{{ plan.name }}</option>
            </select>
          </div>
        </div>

        <div class="filters-right">
          <!-- Clear Filters Button -->
          <button v-if="hasActiveFilters" class="clear-filters-btn" @click="resetFilters">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="clear-icon"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            Reset Filter
          </button>
        </div>
      </div>

      <!-- Member Table -->
      <div class="table-container">
        <div class="table-header" style="display: flex; justify-content: space-between; align-items: center;">
          <h2 class="table-title">Daftar Member</h2>
          <span class="row-count">{{ members.length }} data</span>
        </div>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th class="th-num">#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Status</th>
                <th>Paket Premium</th>
                <th>Masa Aktif</th>
                <th>Bergabung</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="members.length === 0">
                <td colspan="7" class="empty-state">Belum ada member ditemukan</td>
              </tr>
              <tr v-for="(member, idx) in paginated" :key="member.id">
                <td class="td-num">{{ (page - 1) * perPage + idx + 1 }}</td>
                <td>
                  <div class="font-medium text-gray-900">{{ member.name }}</div>
                </td>
                <td class="text-gray-500">{{ member.email }}</td>
                <td>
                  <span :class="['status-badge', member.status === 'Premium' ? 'premium' : 'regular']">
                    {{ member.status }}
                  </span>
                </td>
                <td>
                  <span v-if="member.status === 'Premium'" class="plan-badge">
                    {{ member.plan_name }}
                  </span>
                  <span v-else class="text-gray-400">-</span>
                </td>
                <td class="text-gray-500">{{ member.expire_date }}</td>
                <td class="text-gray-500">{{ member.joined_at }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="pagination">
          <button class="pg-btn" :disabled="page === 1" @click="page = 1">«</button>
          <button class="pg-btn" :disabled="page === 1" @click="page--">‹</button>
          <button
            v-for="p in pageRange"
            :key="p"
            :class="['pg-btn', p === page ? 'pg-active' : '']"
            @click="page = p"
          >{{ p }}</button>
          <button class="pg-btn" :disabled="page === totalPages" @click="page++">›</button>
          <button class="pg-btn" :disabled="page === totalPages" @click="page = totalPages">»</button>
          <span class="pg-info">Hal {{ page }} dari {{ totalPages }}</span>
        </div>
      </div>
    </div>
  </PetugasLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PetugasLayout from '@/Layouts/PetugasLayout.vue';
import StatCard from '@/Pages/Ketua/StatCard.vue';

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
  plans:        Array,
  filters:      Object,
  members:      Array,
});

const navYear  = ref(props.currentYear);
const navMonth = ref(props.currentMonth);

// Filters
const statusFilter = ref(props.filters.status || '');
const planIdFilter = ref(props.filters.plan_id || '');
const startDateFilter = ref(props.filters.start_date || '');
const endDateFilter = ref(props.filters.end_date || '');

// Day labels: "1", "2", ..., "31"
const labels1M = computed(() =>
  Array.from({ length: props.daysInMonth }, (_, i) => String(i + 1))
);

const hasActiveFilters = computed(() => {
  return !!(
    statusFilter.value ||
    planIdFilter.value ||
    startDateFilter.value ||
    endDateFilter.value
  );
});

function resetFilters() {
  statusFilter.value = '';
  planIdFilter.value = '';
  startDateFilter.value = '';
  endDateFilter.value = '';
}

function applyFilters() {
  router.get(
    route('petugas.member.index'),
    {
      year: navYear.value,
      month: navMonth.value,
      status: statusFilter.value || undefined,
      plan_id: planIdFilter.value || undefined,
      start_date: startDateFilter.value || undefined,
      end_date: endDateFilter.value || undefined,
    },
    {
      preserveState: true,
      replace: true,
    }
  );
}

// Watch filters to trigger update
watch([statusFilter, planIdFilter, startDateFilter, endDateFilter], () => {
  // Reset plan filter if regular is selected
  if (statusFilter.value === 'regular') {
    planIdFilter.value = '';
  }
  applyFilters();
});

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
    route('petugas.member.index'),
    {
      year: navYear.value,
      month: navMonth.value,
      status: statusFilter.value || undefined,
      plan_id: planIdFilter.value || undefined,
      start_date: startDateFilter.value || undefined,
      end_date: endDateFilter.value || undefined,
    },
    { preserveState: true, preserveScroll: true, replace: true }
  );
}

// Pagination
const page = ref(1);
const perPage = 20;

const totalPages = computed(() => Math.max(1, Math.ceil(props.members.length / perPage)));
const paginated = computed(() => props.members.slice((page.value - 1) * perPage, page.value * perPage));

const pageRange = computed(() => {
  const total = totalPages.value;
  const cur   = page.value;
  const delta = 2;
  const start = Math.max(1, cur - delta);
  const end   = Math.min(total, cur + delta);
  return Array.from({ length: end - start + 1 }, (_, i) => start + i);
});
</script>

<style scoped>
.top-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 24px 32px 16px;
  background: #fff;
}

.page-title {
  font-size: 22px;
  font-weight: 700;
  color: #111;
  margin: 0;
}

.divider {
  height: 1px;
  background: #e5e7eb;
  margin: 0;
}

.content-area {
  padding: 32px;
}

.stats-chart-wrapper {
  margin-bottom: 32px;
}

.full-width-card {
  width: 100%;
}

.mb-8 {
  margin-bottom: 32px;
}

/* ── Filters Bar ── */
.filters-bar {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 16px;
  background: #fff;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  padding: 16px;
  margin-bottom: 24px;
  flex-wrap: wrap;
}
.filters-left {
  display: flex;
  align-items: center;
  gap: 14px;
  flex-wrap: wrap;
  flex-grow: 1;
}
.filters-right {
  display: flex;
  align-items: center;
}
.filter-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.filter-label {
  font-size: 11px;
  font-weight: 700;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}
.filter-input {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 7px 12px;
  font-size: 13px;
  color: #374151;
  background-color: #fff;
  outline: none;
  transition: all 0.15s ease;
  min-width: 130px;
  height: 36px;
}
.filter-input:focus {
  border-color: var(--primary-color, #007bff);
  box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
}
.date-input {
  min-width: 145px;
}
.select-input {
  cursor: pointer;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23374151' stroke-width='2.5'%3e%3cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5'/%3e%3c/svg%3e");
  background-position: right 12px center;
  background-repeat: no-repeat;
  background-size: 12px;
  padding-right: 32px;
}
.clear-filters-btn {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 8px 12px;
  border-radius: 8px;
  border: 1px solid #fee2e2;
  background: #fef2f2;
  color: #ef4444;
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.15s ease;
  height: 36px;
}
.clear-filters-btn:hover {
  background: #fee2e2;
  color: #dc2626;
}
.clear-icon {
  width: 14px;
  height: 14px;
}

.row-count {
  font-size: 12px;
  color: #9ca3af;
  background: #f3f4f6;
  padding: 2px 8px;
  border-radius: 20px;
}

/* Table */
.table-container {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  border: 1px solid #e5e7eb;
  overflow: hidden;
}

.table-header {
  padding: 20px 24px;
  border-bottom: 1px solid #e5e7eb;
}

.table-title {
  font-size: 16px;
  font-weight: 600;
  color: #111;
  margin: 0;
}

.table-responsive {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th {
  background: #f9fafb;
  font-size: 12px;
  font-weight: 600;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 12px 24px;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
}

.data-table td {
  padding: 16px 24px;
  border-bottom: 1px solid #f3f4f6;
  font-size: 14px;
  color: #374151;
  vertical-align: middle;
}

.data-table tr:last-child td {
  border-bottom: none;
}

.font-medium { font-weight: 500; }
.text-gray-900 { color: #111827; }
.text-gray-500 { color: #6b7280; }
.text-gray-400 { color: #9ca3af; }

.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 4px 10px;
  border-radius: 9999px;
  font-size: 12px;
  font-weight: 600;
}

.status-badge.premium {
  background: #dcfce3;
  color: #16a34a;
}

.status-badge.regular {
  background: #f3f4f6;
  color: #4b5563;
}

.plan-badge {
  display: inline-flex;
  align-items: center;
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 500;
  background: #fdf4ff;
  color: #c026d3;
  border: 1px solid #f5d0fe;
}

.empty-state {
  text-align: center;
  padding: 48px;
  color: #6b7280;
}

/* Pagination */
.pagination {
  display: flex; align-items: center; gap: 4px;
  padding: 14px 24px; border-top: 1px solid #f3f4f6; flex-wrap: wrap;
  background: #fff;
}
.pg-btn {
  min-width: 32px; height: 32px; border-radius: 6px; border: 1px solid #e5e7eb;
  background: #fff; font-size: 13px; color: #374151; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background .15s; padding: 0 8px;
}
.pg-btn:hover:not(:disabled) { background: #f3f4f6; }
.pg-btn:disabled { opacity: .4; cursor: not-allowed; }
.pg-btn.pg-active { background: var(--primary-color, #007bff); color: #fff; border-color: var(--primary-color, #007bff); font-weight: 700; }
.pg-info { font-size: 12px; color: #9ca3af; margin-left: 8px; }

.th-num, .td-num {
  width: 48px; text-align: center;
}

/* ── Responsive ── */
@media (max-width: 1024px) {
  .top-bar { padding: 16px 20px 12px; }
  .page-title { font-size: 18px; }
  .content-area { padding: 16px; }
  
  .filters-bar {
    padding: 12px;
    gap: 12px;
  }
  .filter-input {
    min-width: 100px;
    height: 32px;
    padding: 4px 10px;
    font-size: 12px;
  }
  .date-input {
    min-width: 120px;
  }
  .clear-filters-btn {
    height: 32px;
    padding: 4px 10px;
    font-size: 12px;
  }
  
  .table-header { padding: 16px; }
  .table-title { font-size: 14px; }
  .data-table th, .data-table td {
    padding: 12px 16px;
    font-size: 13px;
  }
  .status-badge, .plan-badge {
    font-size: 11px;
    padding: 3px 8px;
  }
  .pagination {
    padding: 12px 8px;
    flex-wrap: nowrap;
    justify-content: center;
  }
  .pg-btn {
    min-width: 24px;
    height: 24px;
    font-size: 11px;
    padding: 0 4px;
    flex-shrink: 0;
  }
  .pg-info {
    font-size: 10px;
    white-space: nowrap;
  }
}
</style>
