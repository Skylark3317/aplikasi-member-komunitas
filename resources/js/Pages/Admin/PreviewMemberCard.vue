<template>
  <div class="preview-member-card-wrapper">
    <div 
      class="member-card-box"
      :class="{ 'has-bg': cardBgUrl }"
      :style="cardBgUrl ? { backgroundImage: `url(${cardBgUrl})`, backgroundSize: 'cover', backgroundPosition: 'center' } : {}"
    >
      <div class="card-brand"></div>
      
      <div class="card-body-row">
        <div class="card-avatar-wrapper">
          <div class="card-avatar-initial">A</div>
        </div>
        
        <div class="card-text-details">
          <h4 class="card-user-name">Anggota Komunitas</h4>
          <p class="card-user-id">AMK-123456</p>
        </div>
      </div>

      <div class="card-footer-row">
        <span class="card-validity">Berlaku hingga: 31/12/26</span>
        <div class="card-qr-box">
          <div class="mock-qr">QR</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  form: Object,
  cardBgUrl: String,
});
</script>

<style scoped>
.preview-member-card-wrapper {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f8fafc;
  overflow: hidden;
}

.member-card-box {
  position: relative;
  width: 400px;
  height: 250px;
  background: linear-gradient(135deg, #ebf5ff 0%, #f3f4f6 100%);
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  border: 2px solid #bfdbfe;
  overflow: hidden;
  transform: scale(0.9);
}

.member-card-box.has-bg {
  border: none;
  box-shadow: inset 0 0 0 1px rgba(255,255,255,0.2), 0 10px 25px -5px rgba(0,0,0,0.3);
}

.member-card-box::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  border-radius: 10px;
  pointer-events: none;
}

.card-brand {
  height: 20px;
}

.card-body-row {
  display: flex;
  align-items: center;
  gap: 20px;
  z-index: 1;
}

.card-avatar-wrapper {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: #fff;
  border: 3px solid #bfdbfe;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}

.card-avatar-initial {
  font-size: 28px;
  font-weight: 700;
  color: #4b5563;
}

.card-text-details {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.card-user-name {
  margin: 0;
  font-size: 18px;
  font-weight: 700;
  color: #111827;
}
.has-bg .card-user-name { color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,0.5); }

.card-user-id {
  margin: 0;
  font-size: 13px;
  font-weight: 600;
  color: #4b5563;
}
.has-bg .card-user-id { color: #f3f4f6; text-shadow: 0 1px 2px rgba(0,0,0,0.5); }

.card-footer-row {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  z-index: 1;
}

.card-validity {
  font-size: 11px;
  font-weight: 500;
  color: #6b7280;
}
.has-bg .card-validity { color: #e5e7eb; text-shadow: 0 1px 2px rgba(0,0,0,0.5); }

.card-qr-box {
  width: 54px;
  height: 54px;
  background: #fff;
  border-radius: 6px;
  padding: 4px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  display: flex;
  align-items: center;
  justify-content: center;
}

.mock-qr {
  width: 100%;
  height: 100%;
  background: #e5e7eb;
  color: #9ca3af;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 10px;
  font-weight: bold;
}
</style>
