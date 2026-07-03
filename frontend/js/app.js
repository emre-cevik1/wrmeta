/**
 * WR META — Main Application
 * Page detection, module initialization, global event handling
 */
const App = {
  /**
   * Detect current page and initialize
   */
  init() {
    const page = this.detectPage();

    switch (page) {
      case 'index':
        if (typeof TierList !== 'undefined') {
          TierList.init();
        }
        break;

      case 'champion':
        if (typeof ChampionDetail !== 'undefined') {
          ChampionDetail.init();
        }
        this.bindChampionPageSearch();
        break;

      default:
        console.warn('Unknown page:', page);
    }

    this.initGlobalUI();
  },

  /**
   * Detect which page we're on based on the URL
   */
  detectPage() {
    const path = window.location.pathname.toLowerCase();
    if (path.includes('champion.html') || path.includes('champion')) {
      return 'champion';
    }
    return 'index';
  },

  /**
   * Initialize global UI behaviors
   */
  initGlobalUI() {
    // Fade-in body on load
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.4s ease';
    requestAnimationFrame(() => {
      document.body.style.opacity = '1';
    });

    // Active nav link highlight
    this.highlightActiveLogo();
  },

  /**
   * On champion detail page, search input navigates back to index with query
   */
  bindChampionPageSearch() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;

    searchInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && searchInput.value.trim()) {
        window.location.href = `index.html?search=${encodeURIComponent(searchInput.value.trim())}`;
      }
    });
  },

  /**
   * Highlight logo link
   */
  highlightActiveLogo() {
    const logo = document.querySelector('.logo');
    if (logo) {
      logo.addEventListener('mouseenter', () => {
        const dot = logo.querySelector('.logo-dot');
        if (dot) {
          dot.style.transform = 'scale(1.3)';
          dot.style.transition = 'transform 0.3s ease';
        }
      });
      logo.addEventListener('mouseleave', () => {
        const dot = logo.querySelector('.logo-dot');
        if (dot) {
          dot.style.transform = 'scale(1)';
        }
      });
    }
  },
};

// ── Boot ──
document.addEventListener('DOMContentLoaded', () => {
  App.init();

  // If landing on index with a search param, populate the input
  const params = new URLSearchParams(window.location.search);
  const searchQuery = params.get('search');
  if (searchQuery) {
    const input = document.getElementById('searchInput');
    if (input) {
      input.value = searchQuery;
      // Trigger filtering after tier list loads
      setTimeout(() => {
        if (typeof TierList !== 'undefined') {
          TierList.currentSearch = searchQuery;
          TierList.filterDisplayedChampions();
        }
      }, 1500);
    }
  }
});
