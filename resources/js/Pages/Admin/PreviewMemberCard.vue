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
  box-sizing: border-box;
  container-type: inline-size; /* aktifkan container query */
}

.member-card-box {
  container-type: inline-size; /* card jadi container untuk cqw */
  position: relative;
  width: 100%;
  aspect-ratio: 8 / 5;
  background: linear-gradient(135deg, #ebf5ff 0%, #f3f4f6 100%);
  border-radius: 3cqw;
  padding: 5cqw;
  box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  overflow: hidden;
  box-sizing: border-box;
}

.member-card-box.has-bg {
  border: none;
  box-shadow: inset 0 0 0 1px rgba(255,255,255,0.2), 0 10px 25px -5px rgba(0,0,0,0.3);
}

.card-brand {
  height: 2cqw;
}

.card-body-row {
  display: flex;
  align-items: center;
  gap: 4cqw;
  z-index: 1;
}

.card-avatar-wrapper {
  width: 16cqw;
  height: 16cqw;
  flex-shrink: 0;
  border-radius: 50%;
  background: #fff;
  border: 0.5cqw solid #bfdbfe;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}

.card-avatar-initial {
  font-size: 6cqw;
  font-weight: 700;
  color: #4b5563;
}

.card-text-details {
  display: flex;
  flex-direction: column;
  gap: 1cqw;
  min-width: 0;
}

.card-user-name {
  margin: 0;
  font-size: 4.5cqw;
  font-weight: 700;
  color: #111827;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.has-bg .card-user-name { color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,0.5); }

.card-user-id {
  margin: 0;
  font-size: 3cqw;
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
  font-size: 2.5cqw;
  font-weight: 500;
  color: #6b7280;
}
.has-bg .card-validity { color: #e5e7eb; text-shadow: 0 1px 2px rgba(0,0,0,0.5); }

.card-qr-box {
  width: 13cqw;
  height: 13cqw;
  flex-shrink: 0;
  background: #fff;
  border-radius: 1.5cqw;
  padding: 1cqw;
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
  font-size: 2cqw;
  font-weight: bold;
}
</style>