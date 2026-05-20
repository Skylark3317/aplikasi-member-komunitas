<template>
  <MemberLayout>
    <Head title="Profil - AMK" />

    <!-- Top Bar -->
    <div class="top-bar">
      <h1 class="page-title">Profil</h1>
      <Link :href="route('member.profil.edit')" class="btn-edit-profil">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="edit-icon-sm">
          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
        <span>Edit profil</span>
      </Link>
    </div>
    <div class="divider" />

    <!-- Content Area -->
    <div class="content-area">
      <div class="profile-header-card">
        <!-- Avatar & Basic info row -->
        <div class="avatar-info-row">
          <div class="profile-avatar-wrapper">
            <img v-if="user.avatar_url" :src="user.avatar_url" alt="Avatar" class="profile-avatar-img" />
            <div v-else class="profile-avatar-initial">
              {{ user.name.charAt(0).toUpperCase() }}
            </div>
          </div>
          
          <div class="basic-details">
            <h2 class="user-fullname">{{ user.name }}</h2>
            <div class="meta-info-list">
              <div class="meta-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="meta-icon">
                  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                  <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <span>{{ user.member_profile?.institution || '-' }}</span>
              </div>
              <div class="meta-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="meta-icon">
                  <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                  <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 4.5 2z"/>
                </svg>
                <span>{{ user.member_profile?.department || '-' }}</span>
              </div>
              <div class="meta-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="meta-icon">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                  <polyline points="22,6 12,13 2,6"/>
                </svg>
                <span>{{ user.email }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- 4 Grid Info Boxes -->
        <div class="stats-boxes-grid">
          <!-- Box 1: No. Anggota -->
          <div class="stat-box">
            <div class="stat-icon-wrapper blue-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="stat-icon">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
            </div>
            <div class="stat-content">
              <span class="stat-value">{{ user.member_profile?.member_number || '-' }}</span>
              <span class="stat-label">No. Anggota</span>
            </div>
          </div>

          <!-- Box 2: Status -->
          <div class="stat-box">
            <div class="stat-icon-wrapper" :class="user.is_premium ? 'green-icon' : 'red-icon'">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="stat-icon">
                <circle cx="12" cy="12" r="10"/>
                <polyline v-if="user.is_premium" points="16 12 12 8 8 12"/>
                <line v-if="user.is_premium" x1="12" y1="16" x2="12" y2="8"/>
                <line v-else x1="15" y1="9" x2="9" y2="15"/>
              </svg>
            </div>
            <div class="stat-content">
              <span class="stat-value">{{ user.is_premium ? 'Aktif' : 'Nonaktif' }}</span>
              <span class="stat-label">Status</span>
            </div>
          </div>

          <!-- Box 3: Bergabung Sejak -->
          <div class="stat-box">
            <div class="stat-icon-wrapper purple-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="stat-icon">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
            </div>
            <div class="stat-content">
              <span class="stat-value">{{ user.created_at || '-' }}</span>
              <span class="stat-label">Bergabung Sejak</span>
            </div>
          </div>

          <!-- Box 4: Sisa Masa Aktif -->
          <div class="stat-box">
            <div class="stat-icon-wrapper orange-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="stat-icon">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
              </svg>
            </div>
            <div class="stat-content">
              <span class="stat-value">
                {{ user.is_premium && user.member_profile?.days_remaining ? Math.round(user.member_profile.days_remaining) + ' hari lagi' : '-' }}
              </span>
              <span class="stat-label">Sisa Masa Aktif Membership</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Informasi Pribadi Section -->
      <div class="info-pribadi-section">
        <h3 class="section-title">Informasi Pribadi</h3>
        
        <div class="info-pribadi-list">
          <div class="info-row">
            <span class="info-label">Nama Lengkap</span>
            <span class="info-value">{{ user.name }}</span>
          </div>

          <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value">{{ user.email }}</span>
          </div>

          <div class="info-row">
            <span class="info-label">Nomor Anggota</span>
            <span class="info-value">{{ user.member_profile?.member_number || '-' }}</span>
          </div>

          <div class="info-row">
            <span class="info-label">Status</span>
            <span class="info-value">
              <span :class="['status-badge', user.is_premium ? 'badge-success' : 'badge-danger']">
                {{ user.is_premium ? 'Aktif' : 'Nonaktif' }}
              </span>
            </span>
          </div>

          <div class="info-row">
            <span class="info-label">Bergabung Sejak</span>
            <span class="info-value">{{ user.created_at || '-' }}</span>
          </div>

          <div class="info-row">
            <span class="info-label">Membership Hingga</span>
            <span class="info-value">{{ user.is_premium ? user.member_profile?.expire_date : '-' }}</span>
          </div>

          <div class="info-row">
            <span class="info-label">Institusi</span>
            <span class="info-value">{{ user.member_profile?.institution || '-' }}</span>
          </div>

          <div class="info-row">
            <span class="info-label">Departemen</span>
            <span class="info-value">{{ user.member_profile?.department || '-' }}</span>
          </div>

          <div class="info-row">
            <span class="info-label">Nomor Telepon</span>
            <span class="info-value">{{ user.telephone || '-' }}</span>
          </div>

          <div class="info-row">
            <span class="info-label">Alamat</span>
            <span class="info-value">{{ user.member_profile?.address || '-' }}</span>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons-wrapper">
          <!-- Button Pratinjau -->
          <button 
            @click="openCardModal" 
            :disabled="!user.is_premium"
            :class="['btn-profil-action', user.is_premium ? 'btn-action-active' : 'btn-action-disabled']"
          >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="btn-icon">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
            <span>Pratinjau kartu member</span>
          </button>

          <!-- Button Surat Keanggotaan -->
          <button 
            @click="printLetter" 
            :disabled="!user.is_premium"
            :class="['btn-profil-action', user.is_premium ? 'btn-action-active' : 'btn-action-disabled']"
          >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="btn-icon">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
              <polyline points="7 10 12 15 17 10"/>
              <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            <span>Unduh surat keanggotaan</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Pratinjau Kartu Member Modal -->
    <div v-if="showCardModal" class="modal-overlay" @click.self="showCardModal = false">
      <div class="modal-card-wrapper">
        <div class="modal-header-row">
          <h3 class="modal-title">Pratinjau Kartu Member</h3>
          <button @click="showCardModal = false" class="btn-close-modal">×</button>
        </div>

        <div class="modal-body">
          <!-- The visual card -->
          <div id="member-card-render" class="member-card-box">
            <div class="card-brand">AMK</div>
            
            <div class="card-body-row">
              <div class="card-avatar-wrapper">
                <img v-if="user.avatar_url" :src="user.avatar_url" alt="Avatar" class="card-avatar-img" />
                <div v-else class="card-avatar-initial">
                  {{ user.name.charAt(0).toUpperCase() }}
                </div>
              </div>
              
              <div class="card-text-details">
                <h4 class="card-user-name">{{ user.name }}</h4>
                <p class="card-user-id">{{ user.member_profile?.member_number || '-' }}</p>
              </div>
            </div>

            <div class="card-footer-row">
              <span class="card-validity">Berlaku hingga: {{ formatShortDate(user.member_profile?.expire_date) }}</span>
              <div class="card-qr-box">
                <!-- QR Code placeholder representation matching mockup -->
                <div class="qr-placeholder">
                  <div class="qr-block"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Download Action -->
          <button @click="downloadCardAsImage" class="btn-download-card">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="download-icon">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
              <polyline points="7 10 12 15 17 10"/>
              <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            <span>Unduh kartu member</span>
          </button>
        </div>
      </div>
    </div>
  </MemberLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MemberLayout from '@/Layouts/MemberLayout.vue';

const props = defineProps({
  user: Object,
});

const showCardModal = ref(false);

function openCardModal() {
  if (props.user.is_premium) {
    showCardModal.value = true;
  }
}

function formatShortDate(dateStr) {
  if (!dateStr) return '31/12/26';
  // Attempt to map from e.g. "31 Desember 2026" to "31/12/26"
  const parts = dateStr.split(' ');
  if (parts.length === 3) {
    const day = parts[0];
    const monthName = parts[1].toLowerCase();
    const year = parts[2].substring(2);
    
    let month = '12';
    if (monthName.startsWith('jan')) month = '01';
    else if (monthName.startsWith('feb')) month = '02';
    else if (monthName.startsWith('mar')) month = '03';
    else if (monthName.startsWith('apr')) month = '04';
    else if (monthName.startsWith('mei')) month = '05';
    else if (monthName.startsWith('jun')) month = '06';
    else if (monthName.startsWith('jul')) month = '07';
    else if (monthName.startsWith('agu')) month = '08';
    else if (monthName.startsWith('sep')) month = '09';
    else if (monthName.startsWith('okt')) month = '10';
    else if (monthName.startsWith('nov')) month = '11';
    else if (monthName.startsWith('des')) month = '12';
    
    return `${day}/${month}/${year}`;
  }
  return dateStr;
}

function printLetter() {
  if (!props.user.is_premium) return;

  const printWindow = window.open('', '_blank', 'width=800,height=900');
  printWindow.document.write('<html><head><title>Surat Keterangan Keanggotaan</title>');
  printWindow.document.write('<style>');
  printWindow.document.write('body { font-family: "Arial", sans-serif; padding: 40px; line-height: 1.6; color: #333; }');
  printWindow.document.write('.kop-surat { text-align: center; border-bottom: 3px double #000; padding-bottom: 20px; margin-bottom: 30px; }');
  printWindow.document.write('.kop-title { font-size: 24px; font-weight: bold; margin: 0; text-transform: uppercase; }');
  printWindow.document.write('.kop-sub { font-size: 14px; color: #666; margin: 5px 0 0; }');
  printWindow.document.write('.letter-title { text-align: center; font-size: 18px; font-weight: bold; text-decoration: underline; margin-bottom: 30px; text-transform: uppercase; }');
  printWindow.document.write('.content { margin-bottom: 40px; }');
  printWindow.document.write('.table-details { width: 100%; border-collapse: collapse; margin: 20px 0; }');
  printWindow.document.write('.table-details td { padding: 8px 0; font-size: 15px; }');
  printWindow.document.write('.table-details td.label { width: 220px; font-weight: bold; color: #555; }');
  printWindow.document.write('.signature-block { display: flex; justify-content: space-between; margin-top: 50px; }');
  printWindow.document.write('.sig-item { text-align: center; width: 250px; }');
  printWindow.document.write('.sig-space { height: 80px; }');
  printWindow.document.write('.sig-name { font-weight: bold; text-decoration: underline; }');
  printWindow.document.write('</style></head><body>');
  printWindow.document.write('<div class="kop-surat">');
  printWindow.document.write('<h1 class="kop-title">Aplikasi Member Komunitas (AMK)</h1>');
  printWindow.document.write('<p class="kop-sub">Email: support@amk.com | Website: www.amk.com</p>');
  printWindow.document.write('</div>');
  printWindow.document.write('<h2 class="letter-title">Surat Keterangan Keanggotaan Premium</h2>');
  printWindow.document.write('<div class="content">');
  printWindow.document.write('<p>Dengan ini menerangkan bahwa data di bawah ini adalah anggota resmi dan terdaftar secara aktif dalam komunitas **Aplikasi Member Komunitas (AMK)**:</p>');
  printWindow.document.write('<table class="table-details">');
  printWindow.document.write('<tr><td class="label">Nama Lengkap</td><td>: ' + props.user.name + '</td></tr>');
  printWindow.document.write('<tr><td class="label">Nomor Anggota</td><td>: ' + (props.user.member_profile?.member_number || '-') + '</td></tr>');
  printWindow.document.write('<tr><td class="label">Email Terdaftar</td><td>: ' + props.user.email + '</td></tr>');
  printWindow.document.write('<tr><td class="label">Status Membership</td><td>: Aktif (Premium)</td></tr>');
  printWindow.document.write('<tr><td class="label">Bergabung Sejak</td><td>: ' + (props.user.created_at || '-') + '</td></tr>');
  printWindow.document.write('<tr><td class="label">Masa Berlaku Hingga</td><td>: ' + (props.user.member_profile?.expire_date || '-') + '</td></tr>');
  printWindow.document.write('<tr><td class="label">Institusi</td><td>: ' + (props.user.member_profile?.institution || '-') + '</td></tr>');
  printWindow.document.write('<tr><td class="label">Departemen / Divisi</td><td>: ' + (props.user.member_profile?.department || '-') + '</td></tr>');
  printWindow.document.write('</table>');
  printWindow.document.write('<p>Demikian surat keterangan keanggotaan ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.</p>');
  printWindow.document.write('</div>');
  printWindow.document.write('<div class="signature-block">');
  printWindow.document.write('<div></div>');
  printWindow.document.write('<div class="sig-item">');
  printWindow.document.write('<p>Surakarta, ' + new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) + '</p>');
  printWindow.document.write('<p><strong>Pengurus Pusat AMK</strong></p>');
  printWindow.document.write('<div class="sig-space"></div>');
  printWindow.document.write('<p class="sig-name">Admin AMK</p>');
  printWindow.document.write('</div>');
  printWindow.document.write('</div>');
  printWindow.document.write('<script>window.onload = function() { window.print(); window.close(); }</' + 'script>');
  printWindow.document.write('</body></html>');
  printWindow.document.close();
}

function downloadCardAsImage() {
  const cardElement = document.getElementById('member-card-render');
  if (!cardElement) return;

  // Real canvas draw representation to cleanly download the card as an image!
  const canvas = document.createElement('canvas');
  canvas.width = 800;
  canvas.height = 500;
  const ctx = canvas.getContext('2d');

  // Background
  const gradient = ctx.createLinearGradient(0, 0, 800, 500);
  gradient.addColorStop(0, '#ebf5ff');
  gradient.addColorStop(1, '#f3f4f6');
  ctx.fillStyle = gradient;
  ctx.fillRect(0, 0, 800, 500);

  // Blue Card Base Box
  ctx.fillStyle = '#ebf5ff';
  ctx.beginPath();
  ctx.roundRect(40, 40, 720, 420, 20);
  ctx.fill();
  ctx.lineWidth = 1.5;
  ctx.strokeStyle = '#bfdbfe';
  ctx.stroke();

  // Branding Text
  ctx.fillStyle = '#111827';
  ctx.font = 'bold 36px sans-serif';
  ctx.fillText('AMK', 80, 110);

  // Draw Avatar Placeholder Circle
  ctx.fillStyle = '#d1d5db';
  ctx.beginPath();
  ctx.arc(170, 250, 80, 0, Math.PI * 2);
  ctx.fill();

  // Avatar Initials
  ctx.fillStyle = '#111827';
  ctx.font = 'bold 64px sans-serif';
  ctx.textAlign = 'center';
  ctx.textBaseline = 'middle';
  ctx.fillText(props.user.name.charAt(0).toUpperCase(), 170, 250);

  // User Name and ID Details
  ctx.textAlign = 'left';
  ctx.fillStyle = '#111827';
  ctx.font = 'bold 42px sans-serif';
  ctx.fillText(props.user.name, 290, 230);
  
  ctx.fillStyle = '#4b5563';
  ctx.font = '30px sans-serif';
  ctx.fillText(props.user.member_profile?.member_number || '-', 290, 280);

  // Footer validity
  ctx.fillStyle = '#6b7280';
  ctx.font = '24px sans-serif';
  ctx.fillText('Berlaku hingga: ' + formatShortDate(props.user.member_profile?.expire_date), 80, 410);

  // QR Code Draw Simulation
  ctx.fillStyle = '#111827';
  ctx.fillRect(600, 310, 100, 100);
  ctx.fillStyle = '#fff';
  ctx.fillRect(610, 320, 80, 80);
  ctx.fillStyle = '#111827';
  ctx.fillRect(620, 330, 20, 20);
  ctx.fillRect(650, 330, 20, 20);
  ctx.fillRect(620, 360, 20, 20);
  ctx.fillRect(650, 360, 20, 20);

  // Create link and download
  const url = canvas.toDataURL('image/png');
  const a = document.createElement('a');
  a.href = url;
  a.download = `Kartu_Member_${props.user.name.replace(/\s+/g, '_')}.png`;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
}
</script>

<style scoped>
/* Top bar */
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 32px;
  background: #fff;
}
.page-title {
  font-size: 20px;
  font-weight: 600;
  color: #111;
}

.btn-edit-profil {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #007bff;
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  transition: background 0.15s ease;
}

.btn-edit-profil:hover {
  background: #0056b3;
}

.edit-icon-sm {
  width: 14px;
  height: 14px;
}

.divider { 
  height: 1px; 
  background: #e5e7eb; 
  margin: 0;
}

/* Content Area */
.content-area {
  padding: 32px;
  background: #f9fafb;
  min-height: calc(100vh - 65px);
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  gap: 24px;
}

/* Profile Header Card */
.profile-header-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 32px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
  display: flex;
  flex-direction: column;
  gap: 32px;
}

.avatar-info-row {
  display: flex;
  align-items: center;
  gap: 24px;
}

@media (max-width: 600px) {
  .avatar-info-row {
    flex-direction: column;
    text-align: center;
    align-items: center;
  }
}

.profile-avatar-wrapper {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  overflow: hidden;
  background: #e5e7eb;
  border: 1px solid #d1d5db;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.profile-avatar-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.profile-avatar-initial {
  font-size: 48px;
  font-weight: 800;
  color: #4b5563;
}

.basic-details {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.user-fullname {
  font-size: 24px;
  font-weight: 700;
  color: #111827;
  margin: 0;
}

.meta-info-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13.5px;
  color: #4b5563;
  font-weight: 500;
}

.meta-icon {
  width: 16px;
  height: 16px;
  color: #9ca3af;
  flex-shrink: 0;
}

/* 4 Grid Info Boxes */
.stats-boxes-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}

@media (max-width: 900px) {
  .stats-boxes-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 600px) {
  .stats-boxes-grid {
    grid-template-columns: 1fr;
  }
}

.stat-box {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 16px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.01);
}

.stat-icon-wrapper {
  width: 44px;
  height: 44px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.stat-icon {
  width: 20px;
  height: 20px;
}

/* Color Classes for Icons */
.blue-icon { background: #eff6ff; color: #007bff; }
.green-icon { background: #def7ec; color: #28a745; }
.red-icon { background: #fde8e8; color: #ef4444; }
.purple-icon { background: #f3e8ff; color: #9333ea; }
.orange-icon { background: #fff7ed; color: #f97316; }

.stat-content {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.stat-value {
  font-size: 15px;
  font-weight: 700;
  color: #111827;
}

.stat-label {
  font-size: 11px;
  font-weight: 600;
  color: #9ca3af;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Informasi Pribadi Section */
.info-pribadi-section {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 32px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
}

.info-pribadi-list {
  display: flex;
  flex-direction: column;
  margin-bottom: 32px;
}

.info-row {
  display: grid;
  grid-template-columns: 240px 1fr;
  align-items: center;
  padding: 14px 0;
  border-bottom: 1px solid #f3f4f6;
  font-size: 13.5px;
}

@media (max-width: 600px) {
  .info-row {
    grid-template-columns: 1fr;
    gap: 4px;
  }
}

.info-row:last-child {
  border-bottom: none;
}

.info-label {
  color: #6b7280;
  font-weight: 500;
}

.info-value {
  color: #111827;
  font-weight: 600;
}

/* Badges */
.status-badge {
  display: inline-block;
  padding: 4px 10px;
  font-size: 11px;
  font-weight: 700;
  border-radius: 6px;
  text-align: center;
}

.badge-danger {
  background: #fde8e8;
  color: #e53e3e;
}

.badge-success {
  background: #def7ec;
  color: #03543f;
}

/* Action Buttons */
.action-buttons-wrapper {
  display: flex;
  gap: 16px;
  border-top: 1px solid #f3f4f6;
  padding-top: 24px;
}

.btn-profil-action {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 10px 20px;
  border-radius: 6px;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s ease;
  box-sizing: border-box;
}

.btn-action-active {
  background: #fff;
  border: 1px solid #007bff;
  color: #007bff;
}

.btn-action-active:hover {
  background: #eff6ff;
}

.btn-action-disabled {
  background: #f3f4f6;
  border: 1px solid #e5e7eb;
  color: #9ca3af;
  cursor: not-allowed;
}

.btn-icon {
  width: 16px;
  height: 16px;
}

/* Modal Overlay */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-card-wrapper {
  background: #fff;
  border-radius: 12px;
  width: 100%;
  max-width: 440px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
  overflow: hidden;
}

.modal-header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 24px;
  border-bottom: 1px solid #e5e7eb;
}

.modal-title {
  font-size: 16px;
  font-weight: 700;
  color: #111827;
  margin: 0;
}

.btn-close-modal {
  background: transparent;
  border: none;
  font-size: 24px;
  font-weight: 300;
  cursor: pointer;
  color: #9ca3af;
  line-height: 1;
  padding: 0;
}

.modal-body {
  padding: 24px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
}

/* Member Card Box */
.member-card-box {
  width: 100%;
  background: #ebf5ff;
  border: 1px solid #bfdbfe;
  border-radius: 12px;
  padding: 24px;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  gap: 20px;
  position: relative;
  overflow: hidden;
}

.card-brand {
  font-size: 16px;
  font-weight: 800;
  color: #111827;
  text-transform: uppercase;
}

.card-body-row {
  display: flex;
  align-items: center;
  gap: 16px;
}

.card-avatar-wrapper {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  overflow: hidden;
  background: #fff;
  border: 1px solid #bfdbfe;
  display: flex;
  align-items: center;
  justify-content: center;
}

.card-avatar-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.card-avatar-initial {
  font-size: 24px;
  font-weight: 800;
  color: #4b5563;
}

.card-text-details {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.card-user-name {
  font-size: 18px;
  font-weight: 700;
  color: #111827;
  margin: 0;
}

.card-user-id {
  font-size: 13px;
  font-weight: 600;
  color: #4b5563;
  margin: 0;
}

.card-footer-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-top: 12px;
}

.card-validity {
  font-size: 11px;
  color: #6b7280;
  font-weight: 500;
}

.card-qr-box {
  width: 44px;
  height: 44px;
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
  padding: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.qr-placeholder {
  width: 100%;
  height: 100%;
  background: #111827;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2px;
  box-sizing: border-box;
}

.qr-block {
  width: 100%;
  height: 100%;
  background: #fff;
  border: 4px solid #111827;
}

.btn-download-card {
  width: 100%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: #007bff;
  color: #fff;
  border: none;
  padding: 12px;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.15s ease;
}

.btn-download-card:hover {
  background: #0056b3;
}

.download-icon {
  width: 14px;
  height: 14px;
}
</style>
