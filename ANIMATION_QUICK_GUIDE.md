# Quick Animation Implementation Guide

## 🚀 5-Minute Animation Setup

### Step 1: Import Composable (jika butuh scroll animations)

```vue
<script setup>
import { useScrollReveal, useStaggeredAnimation } from '@/composables/useScrollReveal';

// Reveal sections saat scroll
useScrollReveal('.scroll-reveal');

// Animate cards dengan stagger
useStaggeredAnimation('.card-item', 'animate-fade-in-up', 150);
</script>
```

### Step 2: Add Classes ke Template

```vue
<template>
  <!-- Hero / Top Section -->
  <div class="hero">
    <h1 class="animate-fade-in-up animate-delay-200 animate-fill-both">
      Main Title
    </h1>
    <p class="animate-fade-in-up animate-delay-400 animate-fill-both">
      Description
    </p>
    <button class="animate-fade-in-up animate-delay-600 animate-fill-both hover-lift">
      CTA Button
    </button>
  </div>

  <!-- Content that reveals on scroll -->
  <section class="scroll-reveal">
    <h2>About Us</h2>
    <p>Some content...</p>
  </section>

  <!-- Card Grid with Stagger -->
  <div class="grid">
    <div 
      v-for="card in cards" 
      class="card-item hover-lift" 
      style="opacity: 0;"
    >
      Card content
    </div>
  </div>
</template>
```

---

## 🎯 Common Patterns - Copy & Paste

### Pattern: Page Header

```html
<div class="page-header">
  <h1 class="animate-fade-in-up">Dashboard</h1>
  <button class="animate-fade-in-up animate-delay-200 animate-fill-both hover-scale">
    Add New
  </button>
</div>
```

### Pattern: Stats Row

```html
<div class="stats-row">
  <div class="stat-card animate-fade-in-up animate-delay-100 animate-fill-both hover-lift">
    <svg class="animate-pulse-scale"><!-- Icon --></svg>
    <span>1,234</span>
  </div>
  <div class="stat-card animate-fade-in-up animate-delay-200 animate-fill-both hover-lift">
    <svg class="animate-pulse-scale"><!-- Icon --></svg>
    <span>5,678</span>
  </div>
  <div class="stat-card animate-fade-in-up animate-delay-300 animate-fill-both hover-lift">
    <svg class="animate-pulse-scale"><!-- Icon --></svg>
    <span>9,012</span>
  </div>
</div>
```

### Pattern: Table Rows (dengan composable)

```vue
<script setup>
import { useStaggeredAnimation } from '@/composables/useScrollReveal';

useStaggeredAnimation('.table-row', 'animate-fade-in-left', 50);
</script>

<template>
  <table>
    <tbody>
      <tr v-for="row in data" class="table-row hover-lift" style="opacity: 0;">
        <td>{{ row.name }}</td>
        <td>{{ row.email }}</td>
      </tr>
    </tbody>
  </table>
</template>
```

### Pattern: Modal/Dialog

```html
<!-- Overlay -->
<div class="modal-overlay animate-fade-in">
  <!-- Dialog -->
  <div class="modal animate-scale-in">
    <h2>Confirm Action</h2>
    <p>Are you sure?</p>
    <div class="buttons">
      <button class="hover-scale">Cancel</button>
      <button class="hover-glow">Confirm</button>
    </div>
  </div>
</div>
```

### Pattern: Form dengan Stagger Fields

```vue
<script setup>
import { useStaggeredAnimation } from '@/composables/useScrollReveal';

useStaggeredAnimation('.form-field', 'animate-fade-in-up', 80);
</script>

<template>
  <form>
    <div class="form-field" style="opacity: 0;">
      <label>Name</label>
      <input type="text" />
    </div>
    <div class="form-field" style="opacity: 0;">
      <label>Email</label>
      <input type="email" />
    </div>
    <div class="form-field" style="opacity: 0;">
      <label>Message</label>
      <textarea></textarea>
    </div>
    <button class="form-field hover-lift" style="opacity: 0;">
      Submit
    </button>
  </form>
</template>
```

### Pattern: Card Grid

```vue
<script setup>
import { useStaggeredAnimation } from '@/composables/useScrollReveal';

useStaggeredAnimation('.product-card', 'animate-scale-in', 100);
</script>

<template>
  <div class="grid">
    <div 
      v-for="product in products" 
      class="product-card hover-lift" 
      style="opacity: 0;"
    >
      <img :src="product.image" class="hover-brightness" />
      <h3>{{ product.name }}</h3>
      <p>{{ product.price }}</p>
      <button class="hover-scale">Add to Cart</button>
    </div>
  </div>
</template>
```

### Pattern: Sidebar Navigation

```html
<nav class="sidebar animate-fade-in-left">
  <a href="#" class="nav-item animate-fade-in-left animate-delay-100 animate-fill-both">
    Dashboard
  </a>
  <a href="#" class="nav-item animate-fade-in-left animate-delay-200 animate-fill-both">
    Users
  </a>
  <a href="#" class="nav-item animate-fade-in-left animate-delay-300 animate-fill-both">
    Settings
  </a>
</nav>
```

### Pattern: Notification Toast

```html
<div class="toast animate-slide-in-up">
  <svg class="icon animate-pulse-scale"><!-- Icon --></svg>
  <p>Success! Your changes have been saved.</p>
  <button>×</button>
</div>
```

### Pattern: Loading State

```html
<div class="loading-container">
  <div class="loading-shimmer" style="width: 100%; height: 20px; margin-bottom: 10px;"></div>
  <div class="loading-shimmer" style="width: 80%; height: 20px; margin-bottom: 10px;"></div>
  <div class="loading-shimmer" style="width: 90%; height: 20px;"></div>
</div>
```

---

## 🎨 Animation Cheat Sheet

### Entrance (pilih 1)
```
animate-fade-in          // Simple fade
animate-fade-in-up       // ⭐ BEST for most content
animate-scale-in         // ⭐ BEST for cards/modals
animate-slide-in-up      // Modals from bottom
animate-rotate-in        // Fun for icons
```

### Stagger Delays (add setelah animation class)
```
animate-delay-100 animate-fill-both
animate-delay-200 animate-fill-both
animate-delay-300 animate-fill-both
... dst
```

### Hover (pilih 1)
```
hover-lift       // ⭐ BEST for cards
hover-scale      // Buttons, images
hover-glow       // Premium CTAs
hover-brightness // Images only
```

### Continuous (optional)
```
animate-pulse-scale  // ⭐ BEST for icons
animate-float        // Floating elements
animate-glow         // Premium badges
```

---

## ⚡ Quick Decision Tree

**Animating a page header?**
→ Use `animate-fade-in-up` + stagger delays

**Animating cards/grid?**
→ Use `useStaggeredAnimation()` with `animate-scale-in`

**Animating a modal?**
→ Use `animate-scale-in` on dialog, `animate-fade-in` on backdrop

**Want hover effect on cards?**
→ Add `hover-lift`

**Want scroll reveal?**
→ Add `scroll-reveal` class + `useScrollReveal()` in setup

**Want icon to pulse?**
→ Add `animate-pulse-scale`

---

## 🚨 Common Mistakes

### ❌ Wrong
```html
<!-- Missing animate-fill-both on delayed animations -->
<h1 class="animate-fade-in-up animate-delay-200">Title</h1>
<!-- Element is visible before animation! -->
```

### ✅ Correct
```html
<h1 class="animate-fade-in-up animate-delay-200 animate-fill-both">Title</h1>
```

---

### ❌ Wrong
```html
<!-- Forgot to set opacity: 0 for staggered items -->
<div v-for="item in items" class="card-item">
  {{ item }}
</div>
```

### ✅ Correct
```html
<div v-for="item in items" class="card-item" style="opacity: 0;">
  {{ item }}
</div>
```

---

### ❌ Wrong
```html
<!-- Too many animations at once -->
<div class="animate-fade-in animate-scale-in animate-rotate-in">
  Overanimated!
</div>
```

### ✅ Correct
```html
<!-- Pick ONE entrance animation -->
<div class="animate-fade-in-up">
  Just right!
</div>
```

---

## 🎬 Before You Start

1. **Decide what to animate**: Don't animate everything!
2. **Choose pattern**: Hero? Cards? Table? Form?
3. **Copy pattern from this guide**
4. **Adjust delays**: Keep under 600ms total
5. **Test on mobile**: Ensure smooth performance

---

## ✅ Animation Checklist

For each new page/component:

- [ ] **Hero/Header**: Entrance with stagger delays
- [ ] **Main Content**: Add scroll-reveal to sections
- [ ] **Cards/Lists**: Staggered entrance using composable
- [ ] **Buttons**: Add hover-lift or hover-scale
- [ ] **Interactive Links**: Add gap transition or hover effect
- [ ] **Icons (optional)**: Add animate-pulse-scale
- [ ] **Test**: Smooth on desktop and mobile

---

## 💡 Pro Tips

1. **Keep it subtle** - Less is more
2. **Stagger wisely** - 100-150ms between items
3. **Group animations** - Don't go past 600ms total delay
4. **Test reduced motion** - Auto-handled, but worth checking
5. **Profile performance** - Use Chrome DevTools

---

## 📚 Full Documentation

For complete reference, see: `ANIMATION_SYSTEM.md`

