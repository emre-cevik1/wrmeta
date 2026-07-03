/**
 * WR META — Champion Detail Module
 * Renders champion stats, circular progress indicators, and counter matchups
 */
const ChampionDetail = {
  slug: null,

  /**
   * Tier display config
   */
  tierConfig: {
    'S+': { cssClass: 'splus', color: '#ff4444', label: 'S+ Tier' },
    'S':  { cssClass: 's',     color: '#ff8c00', label: 'S Tier' },
    'A':  { cssClass: 'a',     color: '#c89b3c', label: 'A Tier' },
    'B':  { cssClass: 'b',     color: '#0ac8b9', label: 'B Tier' },
    'C':  { cssClass: 'c',     color: '#5b5ecf', label: 'C Tier' },
    'D':  { cssClass: 'd',     color: '#8b8fa3', label: 'D Tier' },
  },

  roleNames: {
    baron: 'Baron',
    jungle: 'Orman',
    mid: 'Orta',
    dragon: 'Ejder',
    support: 'Destek',
  },

  /**
   * Initialize
   */
  async init() {
    this.slug = new URLSearchParams(window.location.search).get('slug');

    if (!this.slug) {
      this.showError('Şampiyon belirtilmedi.');
      return;
    }

    await this.fetchAndRender();
  },

  /**
   * Fetch champion data and counters, then render
   */
  async fetchAndRender() {
    const container = document.getElementById('championDetailContent');
    if (!container) return;

    this.showLoadingState(container);

    try {
      // Fetch champion and counters in parallel
      const [championData, counters] = await Promise.all([
        API.getChampion(this.slug),
        API.getCounters(this.slug).catch(() => null),
      ]);
      const champion = championData.champion || championData;

      // Update page title
      document.title = `${champion.name || this.slug} — WR META`;

      this.render(container, champion, counters);
    } catch (error) {
      console.error('Champion detail fetch error:', error);
      container.innerHTML = `
        <div class="error-message" style="margin-top: 32px;">
          ⚠️ ${error.message || 'Şampiyon verileri yüklenirken bir hata oluştu.'}
        </div>
      `;
    }
  },

  /**
   * Show loading placeholders
   */
  showLoadingState(container) {
    container.innerHTML = `
      <div class="champion-hero" style="opacity: 0.4;">
        <div class="loading-skeleton skeleton-circle" style="width:120px;height:120px;"></div>
        <div style="flex:1;">
          <div class="loading-skeleton skeleton-text skeleton-text--md" style="height:32px;width:250px;margin-bottom:12px;"></div>
          <div class="loading-skeleton skeleton-text skeleton-text--sm" style="height:18px;width:180px;"></div>
        </div>
      </div>
      <div class="stats-grid">
        <div class="loading-skeleton skeleton-stat"></div>
        <div class="loading-skeleton skeleton-stat"></div>
        <div class="loading-skeleton skeleton-stat"></div>
      </div>
      <div class="counters-grid">
        <div>
          <div class="loading-skeleton skeleton-text" style="height:24px;width:200px;margin-bottom:16px;"></div>
          ${Array(3).fill('<div class="loading-skeleton skeleton-counter" style="margin-bottom:8px;"></div>').join('')}
        </div>
        <div>
          <div class="loading-skeleton skeleton-text" style="height:24px;width:200px;margin-bottom:16px;"></div>
          ${Array(3).fill('<div class="loading-skeleton skeleton-counter" style="margin-bottom:8px;"></div>').join('')}
        </div>
      </div>
    `;
  },

  /**
   * Main render method
   */
  render(container, champion, counters) {
    const name = champion.name || this.slug;
    const iconName = champion.icon_name || champion.champion_name || name;
    const iconUrl = `https://ddragon.leagueoflegends.com/cdn/14.1.1/img/champion/${iconName}.png`;
    const role = champion.role || '';
    const tier = champion.tier || 'B';
    const title = champion.title || '';
    const winRate = champion.win_rate ?? 0;
    const pickRate = champion.pick_rate ?? 0;
    const banRate = champion.ban_rate ?? 0;

    const tierCfg = this.tierConfig[tier] || this.tierConfig['B'];
    const roleName = this.roleNames[role] || role;

    let html = '';

    // ── Hero Section ──
    html += `
      <div class="champion-hero">
        <div class="champion-hero__icon-wrapper">
          <img
            class="champion-hero__icon"
            src="${iconUrl}"
            alt="${name}"
            onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 120 120%22><rect fill=%22%23111827%22 width=%22120%22 height=%22120%22/><text x=%2260%22 y=%2270%22 text-anchor=%22middle%22 fill=%22%238b8fa3%22 font-size=%2232%22>?</text></svg>'"
          >
        </div>
        <div class="champion-hero__info">
          <h1 class="champion-hero__name">${name}</h1>
          ${title ? `<p class="champion-hero__title">${title}</p>` : ''}
          <div class="champion-hero__badges">
            ${roleName ? `<span class="badge badge--role">${roleName}</span>` : ''}
            <span class="badge badge--tier tier-badge--${tierCfg.cssClass}" style="background:${tierCfg.color};">${tierCfg.label}</span>
          </div>
        </div>
      </div>
    `;

    // ── Stats Grid ──
    html += `
      <div class="stats-grid">
        ${this.renderStatCard('Kazanma Oranı', winRate, 'winrate')}
        ${this.renderStatCard('Seçilme Oranı', pickRate, 'pickrate')}
        ${this.renderStatCard('Ban Oranı', banRate, 'banrate')}
      </div>
    `;

    // ── Counter Matchups ──
    if (counters) {
      html += this.renderCounters(counters);
    }

    container.innerHTML = html;

    // Animate circular progress after DOM insert
    requestAnimationFrame(() => {
      this.animateProgressCircles();
      this.animateCountUp();
    });
  },

  /**
   * Render a stat card with SVG circular progress
   */
  renderStatCard(label, value, type) {
    const radius = 54;
    const circumference = 2 * Math.PI * radius;
    const percentage = Math.min(Math.max(value, 0), 100);

    return `
      <div class="stat-card stat-card--${type}">
        <div class="stat-card__circle">
          <svg viewBox="0 0 130 130">
            <circle class="stat-card__circle-bg" cx="65" cy="65" r="${radius}" />
            <circle
              class="stat-card__circle-progress"
              cx="65" cy="65" r="${radius}"
              stroke-dasharray="${circumference}"
              stroke-dashoffset="${circumference}"
              data-target-offset="${circumference - (circumference * percentage / 100)}"
            />
          </svg>
          <div class="stat-card__value" data-target="${value.toFixed(1)}">0%</div>
        </div>
        <span class="stat-card__label">${label}</span>
      </div>
    `;
  },

  /**
   * Animate SVG circle progress indicators
   */
  animateProgressCircles() {
    const circles = document.querySelectorAll('.stat-card__circle-progress');
    circles.forEach((circle, index) => {
      const targetOffset = parseFloat(circle.dataset.targetOffset);
      setTimeout(() => {
        circle.style.strokeDashoffset = targetOffset;
      }, 100 + index * 150);
    });
  },

  /**
   * Animate count-up on stat values
   */
  animateCountUp() {
    const valueEls = document.querySelectorAll('.stat-card__value');
    valueEls.forEach((el, index) => {
      const target = parseFloat(el.dataset.target);
      const duration = 1200;
      const startTime = performance.now();
      const delay = 100 + index * 150;

      setTimeout(() => {
        const animate = (currentTime) => {
          const elapsed = currentTime - startTime - delay;
          const progress = Math.min(elapsed / duration, 1);

          // Ease out cubic
          const eased = 1 - Math.pow(1 - progress, 3);
          const current = (target * eased).toFixed(1);

          el.textContent = `${current}%`;

          if (progress < 1) {
            requestAnimationFrame(animate);
          } else {
            el.textContent = `${target.toFixed(1)}%`;
          }
        };
        requestAnimationFrame(animate);
      }, delay);
    });
  },

  /**
   * Render counter matchups section
   */
  renderCounters(counters) {
    const strongAgainst = counters.strong_against || counters.strongAgainst || [];
    const weakAgainst = counters.weak_against || counters.weakAgainst || [];

    if (strongAgainst.length === 0 && weakAgainst.length === 0) {
      return '';
    }

    return `
      <section class="counters-section">
        <h2 class="section-title">
          <span class="section-title__accent"></span>
          Karşı Eşleşmeler
        </h2>
        <div class="counters-grid">
          <div class="counter-column">
            <h3 class="counter-column__title counter-column__title--strong">
              ✅ Güçlü Karşı
            </h3>
            <div class="counter-list">
              ${strongAgainst.length > 0
                ? strongAgainst.map(c => this.renderCounterCard(c, 'positive')).join('')
                : '<p style="color: var(--text-muted); font-size: 0.875rem;">Veri yok</p>'
              }
            </div>
          </div>
          <div class="counter-column">
            <h3 class="counter-column__title counter-column__title--weak">
              ❌ Zayıf Karşı
            </h3>
            <div class="counter-list">
              ${weakAgainst.length > 0
                ? weakAgainst.map(c => this.renderCounterCard(c, 'negative')).join('')
                : '<p style="color: var(--text-muted); font-size: 0.875rem;">Veri yok</p>'
              }
            </div>
          </div>
        </div>
      </section>
    `;
  },

  /**
   * Render a single counter entry card
   */
  renderCounterCard(counter, type) {
    const name = counter.name || counter.champion_name || 'Unknown';
    const slug = counter.slug || name.toLowerCase().replace(/\s+/g, '-');
    const iconName = counter.icon_name || counter.champion_name || name;
    const iconUrl = `https://ddragon.leagueoflegends.com/cdn/14.1.1/img/champion/${iconName}.png`;
    const delta = counter.win_rate_diff ?? counter.winRateDiff ?? 0;
    const deltaPrefix = type === 'positive' ? '+' : '';
    const deltaDisplay = `${deltaPrefix}${delta.toFixed(1)}%`;
    const games = counter.games || counter.total_games || null;

    return `
      <a class="counter-card" href="champion.html?slug=${encodeURIComponent(slug)}">
        <img
          class="counter-card__icon"
          src="${iconUrl}"
          alt="${name}"
          loading="lazy"
          onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 44 44%22><rect fill=%22%23111827%22 width=%2244%22 height=%2244%22/><text x=%2222%22 y=%2228%22 text-anchor=%22middle%22 fill=%22%238b8fa3%22 font-size=%2214%22>?</text></svg>'"
        >
        <div class="counter-card__info">
          <div class="counter-card__name">${name}</div>
          ${games ? `<div class="counter-card__subtitle">${games.toLocaleString('tr-TR')} maç</div>` : ''}
        </div>
        <div class="counter-card__delta counter-card__delta--${type}">
          ${deltaDisplay}
        </div>
      </a>
    `;
  },

  /**
   * Show error state
   */
  showError(message) {
    const container = document.getElementById('championDetailContent');
    if (container) {
      container.innerHTML = `
        <div class="empty-state" style="margin-top: 64px;">
          <div class="empty-state__icon">⚠️</div>
          <div class="empty-state__title">${message}</div>
          <div class="empty-state__desc">
            <a href="index.html" style="color: var(--accent-gold);">Tier listesine dön</a>
          </div>
        </div>
      `;
    }
  },
};
