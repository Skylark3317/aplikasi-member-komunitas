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
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 10px;
  background: transparent;
  box-sizing: border-box;
  pointer-events: none !important;
}

.member-card-box {
  container-type: inline-size;
  position: relative;
  width: 100%;
  max-width: 1000px;
  /* Standard CR80 card ratio: 85.6mm x 53.98mm = 1.586:1 */
  aspect-ratio: 85.6 / 53.98;
  background: linear-gradient(135deg, #ebf5ff 0%, #f3f4f6 100%);
  border-radius: clamp(8px, 3cqw, 16px);
  padding: clamp(16px, 5cqw, 24px);
  box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1) !important;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  overflow: hidden;
  box-sizing: border-box;
  pointer-events: none !important;
  cursor: default !important;
  transform: none !important;
  transition: none !important;
}

.member-card-box.has-bg {
  border: none;
  box-shadow: inset 0 0 0 1px rgba(255,255,255,0.2), 0 10px 25px -5px rgba(0,0,0,0.3);
}

.card-brand {
  height: clamp(8px, 2cqw, 12px);
}

.card-body-row {
  display: flex;
  align-items: center;
  gap: clamp(12px, 4cqw, 20px);
  z-index: 1;
}

.card-avatar-wrapper {
  width: clamp(48px, 16cqw, 72px);
  height: clamp(48px, 16cqw, 72px);
  flex-shrink: 0;
  border-radius: 50%;
  background: #fff;
  border: clamp(2px, 0.5cqw, 3px) solid #bfdbfe;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}

.card-avatar-initial {
  font-size: clamp(20px, 6cqw, 32px);
  font-weight: 700;
  color: #4b5563;
}

.card-text-details {
  display: flex;
  flex-direction: column;
  gap: clamp(2px, 1cqw, 4px);
  min-width: 0;
  flex: 1;
}

.card-user-name {
  margin: 0;
  font-size: clamp(14px, 4.5cqw, 20px);
  font-weight: 700;
  color: #111827;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.2;
}
.has-bg .card-user-name { color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,0.5); }

.card-user-id {
  margin: 0;
  font-size: clamp(11px, 3cqw, 14px);
  font-weight: 600;
  color: #4b5563;
  line-height: 1.2;
}
.has-bg .card-user-id { color: #f3f4f6; text-shadow: 0 1px 2px rgba(0,0,0,0.5); }

.card-footer-row {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  z-index: 1;
  gap: clamp(8px, 2cqw, 12px);
}

.card-validity {
  font-size: clamp(9px, 2.5cqw, 12px);
  font-weight: 500;
  color: #6b7280;
  line-height: 1.2;
  white-space: nowrap;
}
.has-bg .card-validity { color: #e5e7eb; text-shadow: 0 1px 2px rgba(0,0,0,0.5); }

.card-qr-box {
  width: clamp(40px, 13cqw, 60px);
  height: clamp(40px, 13cqw, 60px);
  flex-shrink: 0;
  background: #fff;
  border-radius: clamp(4px, 1.5cqw, 8px);
  padding: clamp(3px, 1cqw, 5px);
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
  font-size: clamp(8px, 2cqw, 12px);
  font-weight: bold;
}
</style>