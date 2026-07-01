/**
 * Universal Animation Plugin
 * Automatically adds animations to all pages
 */

export default {
  install(app) {
    // Global mixin to add animations on every page mount
    app.mixin({
      mounted() {
        // Only run on client side
        if (typeof window === 'undefined') return;

        // Initialize animations after a small delay to ensure DOM is ready
        setTimeout(() => {
          this.initializeUniversalAnimations();
        }, 50);
      },
      methods: {
        initializeUniversalAnimations() {
          // 1. Setup Scroll Reveal (Repeatable)
          this.setupScrollReveal();

          // 2. Add hover effects to cards
          this.setupCardHoverEffects();

          // 3. Add hover effects to buttons
          this.setupButtonHoverEffects();

          // 4. Setup table row animations
          this.setupTableAnimations();

          // 5. Setup staggered animations
          this.setupStaggeredElements();
        },

        setupScrollReveal() {
          const scrollElements = document.querySelectorAll('.scroll-reveal:not(.observed)');
          
          if (scrollElements.length === 0) return;

          const observer = new IntersectionObserver(
            (entries) => {
              entries.forEach((entry) => {
                if (entry.isIntersecting) {
                  entry.target.classList.add('revealed');
                } else {
                  // Remove revealed class when element exits viewport (repeatable)
                  entry.target.classList.remove('revealed');
                }
              });
            },
            {
              threshold: 0.1,
              rootMargin: '0px 0px -100px 0px'
            }
          );

          scrollElements.forEach((el) => {
            el.classList.add('observed');
            observer.observe(el);
          });
        },

        setupCardHoverEffects() {
          const cards = document.querySelectorAll(`
            .card:not(.hover-animated),
            .form-card:not(.hover-animated),
            .stat-card:not(.hover-animated),
            .content-card:not(.hover-animated),
            [class*="card-"]:not(.hover-animated)
          `);

          cards.forEach((card) => {
            card.classList.add('hover-lift', 'hover-animated');
          });
        },

        setupButtonHoverEffects() {
          const buttons = document.querySelectorAll(`
            button:not(.hover-animated):not(.no-animate),
            .btn:not(.hover-animated),
            .btn-primary:not(.hover-animated),
            .btn-secondary:not(.hover-animated),
            .btn-upload:not(.hover-animated)
          `);

          buttons.forEach((btn) => {
            btn.classList.add('hover-scale', 'hover-animated');
          });
        },

        setupTableAnimations() {
          const rows = document.querySelectorAll(`
            tbody tr:not(.table-animated),
            .table-row:not(.table-animated)
          `);

          rows.forEach((row, index) => {
            if (index < 20) { // Only animate first 20 rows for performance
              row.style.animationDelay = `${index * 30}ms`;
              row.classList.add('animate-fade-in-left', 'animate-fill-both', 'table-animated');
            }
          });
        },

        setupStaggeredElements() {
          // Stagger stat cards
          const statCards = document.querySelectorAll('.stat-card:not(.stagger-animated)');
          statCards.forEach((card, index) => {
            if (!card.classList.contains('animate-fade-in-up')) {
              card.style.animationDelay = `${index * 150}ms`;
              card.classList.add('animate-fade-in-up', 'animate-fill-both', 'stagger-animated');
            }
          });

          // Stagger nav items in sidebar
          const navItems = document.querySelectorAll('.sidebar .nav-item:not(.stagger-animated)');
          navItems.forEach((item, index) => {
            item.style.animationDelay = `${index * 80}ms`;
            item.classList.add('animate-fade-in-left', 'animate-fill-both', 'stagger-animated');
          });

          // Stagger form fields
          const formFields = document.querySelectorAll('.field-group:not(.stagger-animated)');
          formFields.forEach((field, index) => {
            if (index < 15) { // Don't animate too many at once
              field.style.animationDelay = `${index * 60}ms`;
              field.classList.add('animate-fade-in-up', 'animate-fill-both', 'stagger-animated');
            }
          });
        }
      }
    });
  }
};
