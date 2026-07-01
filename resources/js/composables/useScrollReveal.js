import { onMounted, onUnmounted } from 'vue';

/**
 * useScrollReveal - Composable for scroll-triggered animations
 * 
 * Usage in component:
 * import { useScrollReveal } from '@/composables/useScrollReveal';
 * 
 * setup() {
 *   useScrollReveal('.scroll-reveal');
 *   useScrollReveal('.scroll-reveal-repeat', { repeat: true }); // Repeatable
 * }
 */
export function useScrollReveal(selector = '.scroll-reveal', options = {}) {
  const defaultOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px',
    repeat: false, // Set to true for repeatable animations
    ...options
  };

  let observer = null;

  const observeElements = () => {
    const elements = document.querySelectorAll(selector);
    
    if (!elements.length) return;

    observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          
          // If not repeat mode, unobserve after reveal
          if (!defaultOptions.repeat) {
            observer.unobserve(entry.target);
          }
        } else if (defaultOptions.repeat) {
          // Remove revealed class when element exits viewport (for repeat)
          entry.target.classList.remove('revealed');
        }
      });
    }, defaultOptions);

    elements.forEach((el) => {
      observer.observe(el);
    });
  };

  onMounted(() => {
    // Small delay to ensure DOM is ready
    setTimeout(observeElements, 100);
  });

  onUnmounted(() => {
    if (observer) {
      observer.disconnect();
    }
  });

  return {
    observeElements
  };
}

/**
 * useStaggeredAnimation - Add staggered entrance animations to list items
 * 
 * Usage:
 * import { useStaggeredAnimation } from '@/composables/useScrollReveal';
 * 
 * setup() {
 *   useStaggeredAnimation('.card-item', 'animate-fade-in-up', 100);
 * }
 */
export function useStaggeredAnimation(selector, animationClass = 'animate-fade-in-up', delayStep = 100) {
  onMounted(() => {
    setTimeout(() => {
      const elements = document.querySelectorAll(selector);
      elements.forEach((el, index) => {
        el.style.animationDelay = `${index * delayStep}ms`;
        el.classList.add(animationClass, 'animate-fill-both');
      });
    }, 100);
  });
}

/**
 * useParallax - Simple parallax effect
 * 
 * Usage:
 * import { useParallax } from '@/composables/useScrollReveal';
 * 
 * setup() {
 *   useParallax('.parallax-element', 0.5);
 * }
 */
export function useParallax(selector, speed = 0.5) {
  const handleScroll = () => {
    const elements = document.querySelectorAll(selector);
    const scrolled = window.pageYOffset;
    
    elements.forEach((el) => {
      const offset = el.offsetTop;
      const distance = scrolled - offset;
      const translate = distance * speed;
      el.style.transform = `translateY(${translate}px)`;
    });
  };

  onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
  });

  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
  });
}
