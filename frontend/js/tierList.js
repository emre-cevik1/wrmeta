/**
 * WR META — Tier List Module
 * Renders tier-grouped champion cards with filtering and search
 */
const TierList = {
  currentRole: '',
  currentSearch: '',
  searchTimeout: null,
  tierData: null,

  /**
   * Tier display configuration
   */
  tiers: [
    { key: 'S+', label: 'S+ Tier — Overpowered', cssClass: 'splus', color: '#ff4444' },
    { key: 'S',  label: 'S Tier — Çok Güçlü',     cssClass: 's',     color: '#ff8c00' },
    { key: 'A',  label: 'A Tier — Güçlü',          cssClass: 'a',     color: '#c89b3c' },
    { key: 'B',  label: 'B Tier — Dengeli',         cssClass: 'b',     color: '#0ac8b9' },
    { key: 'C',  label: 'C Tier — Zayıf',           cssClass: 'c',     color: '#5b5ecf' },
    { key: 'D',  label: 'D Tier — Düşük Performans', cssClass: 'd',    color: '#8b8fa3' },
  ],

  /**
   * Role display names
   */
  roleNames: {
    baron: 'Baron',
    jungle: 'Orman',
    mid: 'Orta',
    dragon: 'Ejder',
    support: 'Destek',
    '': 'Tümü',
  },

  /**
   * Initialize tier list
   */
  async init() {
    this.bindEvents();
    await this.fetchAndRender();
    await this.fetchOverview();
  },

  /**
   * Bind filter and search events
   */
  bindEvents() {
    // Role filter buttons
    const filterButtons = document.querySelectorAll('.role-filter');
    filterButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        filterButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        this.currentRole = btn.dataset.role;
        this.fetchAndRender();
      });
    });

    // Search input with debounce
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
      searchInput.addEventListener('input', (e) => {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
          this.currentSearch = e.target.value.trim();
          this.filterDisplayedChampions();
        }, 250);
      });
    }
  },

  /**
   * Fetch tier list data and render
   */
  async fetchAndRender() {
    const container = document.getElementById('tierListContent');
    if (!container) return;

    // Show loading skeletons
    this.showLoadingState(container);

    try {
      const data = await API.getTierList(this.currentRole);
      this.tierData = data;
      this.renderTierList(container, data);
    } catch (error) {
      console.error('Tier list fetch error:', error);
      container.innerHTML = `
        <div class="error-message">
          ⚠️ ${error.message || 'Veriler yüklenirken bir hata oluştu.'}
        </div>
      `;
    }
  },

  /**
   * Fetch overview stats
   */
  async fetchOverview() {
    try {
      const overview = await API.getOverview();
      const totalEl = document.getElementById('statTotalChampions');
      const patchEl = document.getElementById('statPatch');
      const updatedEl = document.getElementById('statLastUpdated');

      if (totalEl && overview.total_champions !== undefined) {
        totalEl.textContent = overview.total_champions;
      }
      if (patchEl && overview.current_patch) {
        patchEl.textContent = overview.current_patch;
      }
      if (updatedEl && overview.last_updated) {
        const date = new Date(overview.last_updated);
        updatedEl.textContent = date.toLocaleDateString('tr-TR', {
          day: 'numeric',
          month: 'long',
          year: 'numeric',
        });
      }
    } catch (error) {
      console.warn('Overview fetch error:', error);
    }
  },

  /**
   * Show loading skeleton placeholders
   */
  showLoadingState(container) {
    let html = '';
    for (let i = 0; i < 3; i++) {
      html += `
        <div class="tier-section">
          <div class="tier-header">
            <div class="loading-skeleton skeleton-circle" style="width:52px;height:52px;"></div>
            <div class="loading-skeleton skeleton-text skeleton-text--md" style="width:200px;height:20px;"></div>
          </div>
          <div class="champion-grid">
            ${Array(6).fill('<div class="loading-skeleton skeleton-card"></div>').join('')}
          </div>
        </div>
      `;
    }
    container.innerHTML = html;
  },

  /**
   * Render the tier list grouped by tier
   */
  renderTierList(container, data) {
    // data can be an object with tier keys or an array
    // Normalize: if it's an array of champions with .tier field, group them
    let grouped;
    if (Array.isArray(data)) {
      grouped = {};
      data.forEach(champ => {
        const tier = champ.tier || 'B';
        if (!grouped[tier]) grouped[tier] = [];
        grouped[tier].push(champ);
      });
    } else {
      grouped = data;
    }

    let html = '';
    let hasAnyChampions = false;

    this.tiers.forEach(tierInfo => {
      const champions = grouped[tierInfo.key];
      if (!champions || champions.length === 0) return;

      hasAnyChampions = true;

      html += `
        <section class="tier-section" data-tier="${tierInfo.key}">
          <div class="tier-header">
            <div class="tier-badge tier-badge--${tierInfo.cssClass}">${tierInfo.key}</div>
            <span class="tier-label">${tierInfo.label}</span>
            <span class="tier-count">${champions.length} şampiyon</span>
          </div>
          <div class="champion-grid">
            ${champions.map((champ, index) => this.renderChampionCard(champ, tierInfo, index)).join('')}
          </div>
        </section>
      `;
    });

    if (!hasAnyChampions) {
      html = `
        <div class="empty-state">
          <div class="empty-state__icon">🏆</div>
          <div class="empty-state__title">Şampiyon bulunamadı</div>
          <div class="empty-state__desc">Bu filtreler için veri bulunmuyor.</div>
        </div>
      `;
    }

    container.innerHTML = html;

    // Staggered card animations
    requestAnimationFrame(() => {
      const cards = container.querySelectorAll('.champion-card');
      cards.forEach((card, i) => {
        card.style.animationDelay = `${Math.min(i * 0.03, 0.8)}s`;
        card.classList.add('animate-in');
      });
    });
  },

  /**
   * Render a single champion card
   */
  renderChampionCard(champ, tierInfo, index) {
    const name = champ.name || 'Unknown';
    const slug = champ.slug || name.toLowerCase().replace(/\s+/g, '-');
    const winRate = champ.win_rate !== undefined ? champ.win_rate : null;
    const role = champ.role || '';
    const iconName = champ.icon_name || champ.champion_name || name;
    const iconUrl = `https://ddragon.leagueoflegends.com/cdn/14.1.1/img/champion/${iconName}.png`;

    // Win rate class
    let winRateClass = 'winrate--neutral';
    let winRateDisplay = '—';
    if (winRate !== null) {
      winRateDisplay = `${winRate.toFixed(1)}%`;
      if (winRate > 51) winRateClass = 'winrate--high';
      else if (winRate < 49) winRateClass = 'winrate--low';
    }

    // Role display
    const roleDisplay = this.roleNames[role] || role || '';

    return `
      <a class="champion-card" href="champion.html?slug=${encodeURIComponent(slug)}" data-name="${name.toLowerCase()}" data-slug="${slug}">
        <div class="champion-card__tier-indicator tier-badge--${tierInfo.cssClass}" style="background: ${tierInfo.color};">${tierInfo.key}</div>
        <div class="champion-card__icon-wrapper">
          <img
            class="champion-card__icon"
            src="${iconUrl}"
            alt="${name}"
            loading="lazy"
            onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 48 48%22><rect fill=%22%23111827%22 width=%2248%22 height=%2248%22/><text x=%2224%22 y=%2230%22 text-anchor=%22middle%22 fill=%22%238b8fa3%22 font-size=%2216%22>?</text></svg>'"
          >
        </div>
        <span class="champion-card__name">${name}</span>
        ${roleDisplay ? `<span class="champion-card__role">${roleDisplay}</span>` : ''}
        <span class="champion-card__winrate ${winRateClass}">${winRateDisplay}</span>
      </a>
    `;
  },

  /**
   * Filter displayed champion cards by search term (client-side)
   */
  filterDisplayedChampions() {
    const query = this.currentSearch.toLowerCase();
    const cards = document.querySelectorAll('.champion-card');
    const sections = document.querySelectorAll('.tier-section');

    if (!query) {
      // Show all
      cards.forEach(card => card.style.display = '');
      sections.forEach(section => section.style.display = '');
      return;
    }

    cards.forEach(card => {
      const name = card.dataset.name || '';
      if (name.includes(query)) {
        card.style.display = '';
      } else {
        card.style.display = 'none';
      }
    });

    // Hide empty tier sections
    sections.forEach(section => {
      const visibleCards = section.querySelectorAll('.champion-card:not([style*="display: none"])');
      section.style.display = visibleCards.length === 0 ? 'none' : '';
    });
  },
};
