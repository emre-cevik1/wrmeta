/**
 * WR META — API Client
 * Centralized API communication layer
 */
const API = {
  BASE_URL: window.location.hostname === 'localhost' ? 'http://localhost:8000/api' : '/api',

  /**
   * Generic GET request with error handling
   */
  async get(endpoint, params = {}) {
    const url = new URL(`${this.BASE_URL}${endpoint}`, window.location.origin);

    // Append query parameters
    Object.entries(params).forEach(([key, value]) => {
      if (value !== undefined && value !== null && value !== '') {
        url.searchParams.append(key, value);
      }
    });

    try {
      const response = await fetch(url.toString(), {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
        },
      });

      if (!response.ok) {
        const errorBody = await response.text();
        throw new Error(`API Error ${response.status}: ${errorBody || response.statusText}`);
      }

      const json = await response.json();
      if (json.success && json.data !== undefined) {
        return json.data;
      }
      return json;
    } catch (error) {
      if (error.name === 'TypeError' && error.message.includes('fetch')) {
        throw new Error('Sunucuya bağlanılamadı. Lütfen API sunucusunun çalıştığından emin olun.');
      }
      throw error;
    }
  },

  /**
   * Get all champions, optionally filtered by role and search query
   */
  async getChampions(role = '', search = '') {
    return this.get('/champions', { role, search });
  },

  /**
   * Get a single champion by slug
   */
  async getChampion(slug) {
    return this.get(`/champions/${slug}`);
  },

  /**
   * Get the tier list, optionally filtered by role
   */
  async getTierList(role = '') {
    return this.get('/tier-list', { role });
  },

  /**
   * Get counter matchups for a champion
   */
  async getCounters(slug) {
    return this.get(`/counters/${slug}`);
  },

  /**
   * Get overview / meta stats
   */
  async getOverview() {
    return this.get('/stats/overview');
  }
};
