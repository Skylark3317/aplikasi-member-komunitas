import { onMounted } from 'vue';
import { useScrollReveal, useStaggeredAnimation } from './useScrollReveal';

/**
 * usePageAnimations - One-stop setup for all page animations
 * 
 * Usage in any page:
 * import { usePageAnimations } from '@/composables/usePageAnimations';
 * 
 * setup() {
 *   usePageAnimations({
 *     enableScrollReveal: true,
 *     repeatAnimations: true,
 *     staggerCards: true,
 *     staggerRows: true
 *   });
 * }
 */
export function usePageAnimations(options = {}) {
  const config = {
    enableScrollReveal: true,
    repeatAnimations: true,
    staggerCards: true,
    staggerRows: true,
    staggerButtons: true,
    ...options
  };

  onMounted(() => {
    // Scroll reveal for sections (repeatable)
    if (config.enableScrollReveal) {
      useScrollReveal('.scroll-reveal', { repeat: config.repeatAnimations });
      useScrollReveal('.scroll-reveal-once', { repeat: false });
    }

    // Staggered cards
    if (config.staggerCards) {
      useStaggeredAnimation('.card-animate', 'animate-scale-in', 100);
      useStaggeredAnimation('.stat-card', 'animate-fade-in-up', 150);
    }

    // Staggered table rows
    if (config.staggerRows) {
      useStaggeredAnimation('.table-row-animate', 'animate-fade-in-left', 50);
    }

    // Staggered buttons/actions
    if (config.staggerButtons) {
      useStaggeredAnimation('.btn-animate', 'animate-fade-in-up', 100);
    }

    // Add entrance animations to common elements
    addEntranceAnimations();
  });
}

/**
 * Add entrance animations to common page elements
 */
function addEntranceAnimations() {
  // Page headers
  const headers = document.querySelectorAll('.page-header, .top-bar');
  headers.forEach((header) => {
    if (!header.classList.contains('animate-fade-in-down')) {
      header.classList.add('animate-fade-in-down');
    }
  });

  // Content areas
  const contentAreas = document.querySelectorAll('.content-area');
  contentAreas.forEach((area) => {
    if (!area.classList.contains('animate-fade-in-up')) {
      area.classList.add('animate-fade-in-up');
    }
  });
}

/**
 * useCardHoverEffects - Add hover effects to all cards
 */
export function useCardHoverEffects() {
  onMounted(() => {
    const cards = document.querySelectorAll('.card, .form-card, .stat-card, [class*="card-"]');
    cards.forEach((card) => {
      if (!card.classList.contains('hover-lift')) {
        card.classList.add('hover-lift');
      }
    });
  });
}

/**
 * useButtonAnimations - Add hover effects to all buttons
 */
export function useButtonAnimations() {
  onMounted(() => {
    const buttons = document.querySelectorAll('button:not(.no-animate), .btn, .btn-primary, .btn-secondary');
    buttons.forEach((btn) => {
      if (!btn.classList.contains('hover-scale')) {
        btn.classList.add('hover-scale');
      }
    });
  });
}
