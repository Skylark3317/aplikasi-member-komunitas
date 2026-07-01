# 🧪 Testing Guide: Individual Card Animations

## 🎯 Purpose
Guide untuk testing animasi individual di landing page yang baru saja diimplementasikan.

---

## 🚀 Quick Start

### 1. Start Development Server
```bash
# Method 1: Use composer dev (recommended)
composer dev

# Method 2: Manual
php artisan serve
npm run dev
```

### 2. Open Browser
```
http://localhost:8000
```

### 3. Navigate to Landing Page
- Landing page adalah halaman pertama yang terbuka
- Atau klik logo AMK di header

---

## 📋 Testing Checklist

### ✅ Stats Section (4 Cards)

#### Test 1: Initial Animation
1. Refresh halaman (Ctrl+R)
2. Scroll perlahan ke bawah sampai section "Komunitas Kami dalam Angka"
3. **Observe**:
   - [ ] Card 1 (Member Aktif) muncul dari **KIRI**
   - [ ] Card 2 (Member Pasif) muncul dari **BAWAH** setelah 150ms
   - [ ] Card 3 (Member Company) muncul dari **BAWAH** setelah 300ms
   - [ ] Card 4 (Member Personal) muncul dari **KANAN** setelah 450ms
   - [ ] Semua cards memiliki "bounce" effect (overshoot sedikit)

**Expected Timeline**:
```
0.0s:   Card 1 starts (from left)
0.15s:  Card 2 starts (from bottom)
0.30s:  Card 3 starts (from bottom)
0.45s:  Card 4 starts (from right)
~1.15s: All cards fully revealed
```

#### Test 2: Hover Effects
1. Hover mouse ke setiap card
2. **Observe**:
   - [ ] Card naik sedikit (translateY(-4px))
   - [ ] Shadow bertambah
   - [ ] Transition smooth
   - [ ] Icon tetap pulse

#### Test 3: Repeatable Animation
1. Scroll **DOWN** melewati section
2. Scroll **UP** kembali ke atas (melewati section)
3. **Observe**:
   - [ ] Cards fade out saat keluar viewport
4. Scroll **DOWN** lagi ke section
5. **Observe**:
   - [ ] Cards animate in LAGI dengan pattern yang sama
6. Ulangi 3-5 kali
7. **Observe**:
   - [ ] Animasi tetap smooth
   - [ ] Tidak ada glitch atau stutter
   - [ ] Timing tetap konsisten

---

### ✅ Posts Section (8 Cards)

#### Test 4: Initial Animation
1. Scroll ke section "Postingan Terbaru"
2. **Observe** setiap card satu per satu:

| Card | Expected Animation | Delay |
|------|-------------------|-------|
| 1 | Scale + Rotate LEFT (↺) | 0ms |
| 2 | Slide UP + Scale (↑) | 80ms |
| 3 | Slide UP + Scale (↑) | 160ms |
| 4 | Scale + Rotate RIGHT (↻) | 240ms |
| 5 | Slide from LEFT (←) | 320ms |
| 6 | Slide UP (↑) | 400ms |
| 7 | Slide UP (↑) | 480ms |
| 8 | Slide from RIGHT (→) | 560ms |

**Checklist**:
- [ ] Card 1: Rotates from left while scaling
- [ ] Card 2: Slides up with scale
- [ ] Card 3: Slides up with scale
- [ ] Card 4: Rotates from right while scaling
- [ ] Card 5: Slides from left with scale
- [ ] Card 6: Simple slide up
- [ ] Card 7: Simple slide up
- [ ] Card 8: Slides from right with scale
- [ ] All have bounce easing
- [ ] Timing creates wave effect

#### Test 5: Visual Pattern
1. Observe seluruh section sekaligus
2. **Check Pattern**:
```
Row 1:  ↺     ↑     ↑     ↻
      Card1 Card2 Card3 Card4

Row 2:  ←     ↑     ↑     →
      Card5 Card6 Card7 Card8
```
- [ ] Pattern creates visual balance
- [ ] Animations feel coordinated
- [ ] No cards appear too fast/slow

#### Test 6: Hover Effects
1. Hover ke setiap post card
2. **Observe**:
   - [ ] Card lifts up
   - [ ] Shadow increases
   - [ ] Smooth transition
   - [ ] "Lanjutkan membaca" link spacing increases

#### Test 7: Repeatable Animation
1. Scroll **DOWN** melewati section posts
2. Scroll **UP** kembali ke atas
3. **Observe**:
   - [ ] Cards fade out smoothly
4. Scroll **DOWN** lagi
5. **Observe**:
   - [ ] Cards animate in LAGI
   - [ ] Pattern tetap sama (1-8)
6. Ulangi 5 kali
7. **Check**:
   - [ ] Smooth performance
   - [ ] No lag or stutter
   - [ ] Consistent timing

---

## 🎨 Visual Quality Tests

### Test 8: Animation Smoothness
1. Scroll dengan berbagai kecepatan:
   - [ ] Very slow scroll → animations smooth
   - [ ] Normal scroll → animations smooth
   - [ ] Fast scroll → animations smooth
   - [ ] Very fast scroll → no glitches
2. Test di different sections:
   - [ ] Stats section smooth
   - [ ] Posts section smooth

### Test 9: Bounce Easing
1. Focus pada 1 card saja
2. Scroll to trigger animation
3. **Watch carefully**:
   - [ ] Card slightly overshoots final position
   - [ ] Card bounces back to correct position
   - [ ] Effect is subtle but noticeable
   - [ ] Looks playful and energetic

### Test 10: Staggered Timing
**Stats Cards**:
1. Watch all 4 cards simultaneously
2. **Observe**:
   - [ ] Cards appear in sequence, not all at once
   - [ ] Delay between cards is ~150ms
   - [ ] Creates nice "wave" effect

**Post Cards**:
1. Watch all 8 cards simultaneously
2. **Observe**:
   - [ ] Cards appear in sequence
   - [ ] Delay between cards is ~80ms
   - [ ] Faster than stats (more dynamic)

---

## ⚡ Performance Tests

### Test 11: Frame Rate
1. Open DevTools (F12)
2. Go to Performance tab
3. Start recording
4. Scroll through entire landing page
5. Stop recording
6. **Check**:
   - [ ] FPS consistently 60fps or close
   - [ ] No frame drops during animations
   - [ ] No long tasks blocking

### Test 12: Memory Usage
1. Open DevTools → Performance tab
2. Record page load + scroll
3. **Check**:
   - [ ] Memory stable (no leaks)
   - [ ] No continuous growth
   - [ ] Observer cleanup working

### Test 13: Network Impact
1. DevTools → Network tab
2. Reload page (Ctrl+Shift+R)
3. **Check**:
   - [ ] app.css loaded (+150KB total, +~0.45KB new)
   - [ ] Home.vue JS (+~1KB)
   - [ ] Total impact minimal
   - [ ] Page loads quickly

---

## ♿ Accessibility Tests

### Test 14: Reduced Motion
1. **Windows**: Settings → Accessibility → Visual effects → Animation effects: OFF
2. Atau in DevTools: Cmd/Ctrl+Shift+P → "Emulate CSS prefers-reduced-motion: reduce"
3. Refresh page
4. Scroll through landing page
5. **Observe**:
   - [ ] Animations extremely brief (0.01ms)
   - [ ] Cards still visible
   - [ ] No motion sickness trigger
   - [ ] Content still accessible

### Test 15: Keyboard Navigation
1. Use Tab key to navigate
2. **Check**:
   - [ ] Can tab through all links/buttons
   - [ ] Focus visible
   - [ ] Animations don't interfere
   - [ ] Content still readable

### Test 16: Screen Reader
1. If available, test with screen reader (NVDA/JAWS/VoiceOver)
2. **Check**:
   - [ ] Content announced correctly
   - [ ] Animations don't cause confusion
   - [ ] All content accessible

---

## 📱 Responsive Tests

### Test 17: Mobile View (DevTools)
1. DevTools → Toggle device toolbar (Ctrl+Shift+M)
2. Test different sizes:
   - **iPhone SE (375px)**:
     - [ ] Stats stack vertically
     - [ ] Animations work
     - [ ] Timing appropriate
   - **iPhone 12 Pro (390px)**:
     - [ ] Layout correct
     - [ ] Animations smooth
   - **iPad (768px)**:
     - [ ] Grid layout
     - [ ] Animations work
   - **iPad Pro (1024px)**:
     - [ ] Full layout
     - [ ] All animations work

### Test 18: Touch Interaction
1. Use mobile device or DevTools touch mode
2. **Check**:
   - [ ] Scroll works smoothly
   - [ ] Animations trigger correctly
   - [ ] No touch lag
   - [ ] Hover effects replaced by tap

---

## 🌐 Cross-Browser Tests

### Test 19: Chrome/Edge
- [ ] Animations work
- [ ] Smooth 60fps
- [ ] Bounce easing correct
- [ ] Repeatable works

### Test 20: Firefox
- [ ] Animations work
- [ ] Smooth performance
- [ ] No visual glitches
- [ ] Repeatable works

### Test 21: Safari (if available)
- [ ] Animations work
- [ ] Webkit prefix not needed
- [ ] Performance good
- [ ] Repeatable works

---

## 🐛 Edge Cases

### Test 22: Very Fast Scroll
1. Scroll very quickly up and down
2. **Observe**:
   - [ ] No broken animations
   - [ ] No stuck states
   - [ ] Cards reset properly
   - [ ] No visual artifacts

### Test 23: Multiple Triggers
1. Scroll down slowly
2. Before animation completes, scroll up
3. Scroll down again immediately
4. **Check**:
   - [ ] No animation conflicts
   - [ ] Clean state transitions
   - [ ] No visual glitches

### Test 24: Browser Resize
1. Start animation
2. Resize browser window during animation
3. **Check**:
   - [ ] Animation continues smoothly
   - [ ] Layout adjusts correctly
   - [ ] No broken states

### Test 25: Tab Switch
1. Start animation
2. Switch to another tab (Ctrl+Tab)
3. Wait 5 seconds
4. Switch back
5. **Check**:
   - [ ] Animation state correct
   - [ ] Can trigger again
   - [ ] No stuck states

---

## 📊 Results Template

### ✅ Pass Criteria
- All animations work as expected
- Smooth 60fps performance
- No console errors
- Repeatable works unlimited times
- Accessible with reduced motion
- Works on all screen sizes

### 🔴 Fail Criteria
- Animations don't trigger
- Severe performance issues (<30fps)
- Console errors
- Animations don't repeat
- Accessibility issues
- Broken on mobile

---

## 🎬 Screen Recording Checklist

If recording demo:
1. [ ] Start on landing page top
2. [ ] Scroll slowly to stats section
3. [ ] Wait for all 4 cards to animate
4. [ ] Scroll to posts section
5. [ ] Wait for all 8 cards to animate
6. [ ] Scroll back up past sections
7. [ ] Scroll down again to show repeatability
8. [ ] Hover over a few cards
9. [ ] Show smooth transitions

---

## 📝 Bug Report Template

If you find issues:

```markdown
### Bug: [Short description]

**Expected Behavior**:
[What should happen]

**Actual Behavior**:
[What actually happens]

**Steps to Reproduce**:
1. 
2. 
3. 

**Environment**:
- Browser: [Chrome 120 / Firefox 121 / etc.]
- OS: [Windows 11 / MacOS / etc.]
- Screen Size: [1920x1080 / etc.]

**Screenshots/Video**:
[If available]

**Console Errors**:
```
[Paste errors here]
```
```

---

## ✅ Sign-Off

After completing all tests:

**Tested By**: _______________
**Date**: _______________
**Browser(s)**: _______________
**Result**: [ ] PASS  [ ] FAIL

**Notes**:
_______________________________________________
_______________________________________________
_______________________________________________

---

## 🎉 Success Indicators

You'll know the feature is working perfectly when:

1. ✨ All 4 stat cards slide in from different directions
2. ✨ All 8 post cards have unique entrance animations
3. 🔄 Animations repeat EVERY time you scroll
4. ⚡ Smooth 60fps performance
5. 🎯 Bounce easing clearly visible
6. 🌊 Wave pattern apparent in both sections
7. 😊 You think "Wow, this looks amazing!"

---

**Happy Testing!** 🧪✨
