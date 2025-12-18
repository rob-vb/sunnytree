/**
 * SunnyTree Product Filters Module
 *
 * AJAX-based product filtering with URL state management.
 *
 * @package SunnyTree
 */

class ProductFilters {
  constructor() {
    this.sidebar = document.querySelector('[data-filter-sidebar]');
    this.form = document.querySelector('[data-filter-form]');
    this.productsContainer = document.querySelector('[data-products-container]');
    this.mobileToggle = document.querySelector('[data-filter-mobile-toggle]');
    this.backdrop = document.querySelector('[data-filter-backdrop]');
    this.closeBtn = document.querySelector('[data-filter-close]');
    this.applyBtn = document.querySelector('[data-filter-apply]');
    this.clearAllBtns = document.querySelectorAll('[data-filter-clear-all]');
    this.chipsContainer = document.querySelector('[data-filter-chips]');

    this.isLoading = false;
    this.debounceTimer = null;
    this.config = window.sunnyTreeFilters || {};

    if (!this.sidebar || !this.form) {
      return;
    }

    this.init();
  }

  init() {
    this.bindEvents();
    this.initPriceRange();
    this.initFromUrl();
    this.updateActiveChips();
  }

  bindEvents() {
    // Filter input changes (checkboxes)
    this.form.addEventListener('change', (e) => {
      if (e.target.matches('[data-filter-input]')) {
        this.debounceFilter();
      }
    });

    // Filter group toggles (collapse/expand)
    this.sidebar.querySelectorAll('[data-filter-toggle]').forEach((toggle) => {
      toggle.addEventListener('click', () => this.toggleGroup(toggle));
    });

    // Show more/less buttons
    this.sidebar.querySelectorAll('[data-filter-show-more]').forEach((btn) => {
      btn.addEventListener('click', () => this.toggleShowMore(btn));
    });

    // Clear all filters buttons
    this.clearAllBtns.forEach((btn) => {
      btn.addEventListener('click', () => this.clearAllFilters());
    });

    // Active chip removal (delegated)
    if (this.chipsContainer) {
      this.chipsContainer.addEventListener('click', (e) => {
        const removeBtn = e.target.closest('[data-filter-chip-remove]');
        if (removeBtn) {
          const chip = removeBtn.closest('[data-filter-chip]');
          if (chip) {
            this.removeFilter(chip.dataset.filterChip, chip.dataset.filterValue);
          }
        }
      });
    }

    // Mobile filter toggle
    if (this.mobileToggle) {
      this.mobileToggle.addEventListener('click', () => this.openMobileDrawer());
    }

    // Mobile close button
    if (this.closeBtn) {
      this.closeBtn.addEventListener('click', () => this.closeMobileDrawer());
    }

    // Mobile apply button
    if (this.applyBtn) {
      this.applyBtn.addEventListener('click', () => this.closeMobileDrawer());
    }

    // Backdrop click closes drawer
    if (this.backdrop) {
      this.backdrop.addEventListener('click', () => this.closeMobileDrawer());
    }

    // Browser back/forward navigation
    window.addEventListener('popstate', () => {
      this.initFromUrl();
      this.filterProducts(1, false); // Don't update URL on popstate
    });

    // Pagination handling (delegated to dynamic content)
    document.addEventListener('click', (e) => {
      const pageLink = e.target.closest('.woocommerce-pagination a.page-numbers');
      if (pageLink && this.productsContainer) {
        e.preventDefault();
        const page = this.getPageFromUrl(pageLink.href);
        if (page) {
          this.filterProducts(page);
        }
      }
    });

    // Escape key closes mobile drawer
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.sidebar.classList.contains('is-open')) {
        this.closeMobileDrawer();
      }
    });
  }

  initPriceRange() {
    const priceRange = this.sidebar.querySelector('[data-price-range]');
    if (!priceRange) return;

    const minInput = priceRange.querySelector('[data-price-min-input]');
    const maxInput = priceRange.querySelector('[data-price-max-input]');

    if (!minInput || !maxInput) return;

    // Store references for updatePriceRangeVisuals
    this.priceRangeElements = {
      priceRange,
      minInput,
      maxInput,
      minDisplay: priceRange.querySelector('[data-price-min-display]'),
      maxDisplay: priceRange.querySelector('[data-price-max-display]'),
      selectedTrack: priceRange.querySelector('[data-price-selected]'),
      hiddenMin: priceRange.querySelector('[data-filter-min-price]'),
      hiddenMax: priceRange.querySelector('[data-filter-max-price]'),
      rangeMin: parseInt(priceRange.dataset.min || minInput.min, 10),
      rangeMax: parseInt(priceRange.dataset.max || minInput.max, 10),
    };

    // Only bind events once
    if (!this.priceRangeInitialized) {
      this.priceRangeInitialized = true;

      // Track which thumb is being dragged
      minInput.addEventListener('mousedown', () => (this.activeThumb = 'min'));
      minInput.addEventListener('touchstart', () => (this.activeThumb = 'min'), { passive: true });
      maxInput.addEventListener('mousedown', () => (this.activeThumb = 'max'));
      maxInput.addEventListener('touchstart', () => (this.activeThumb = 'max'), { passive: true });

      minInput.addEventListener('input', () => this.updatePriceRangeVisuals());
      maxInput.addEventListener('input', () => this.updatePriceRangeVisuals());

      // Trigger filter on change (mouseup/touchend)
      minInput.addEventListener('change', () => this.debounceFilter(100));
      maxInput.addEventListener('change', () => this.debounceFilter(100));
    }

    // Initialize visuals
    this.updatePriceRangeVisuals();
  }

  updatePriceRangeVisuals() {
    const els = this.priceRangeElements;
    if (!els) return;

    let min = parseInt(els.minInput.value, 10);
    let max = parseInt(els.maxInput.value, 10);

    // Prevent crossover with a minimum gap
    const minGap = Math.max(1, Math.round((els.rangeMax - els.rangeMin) * 0.02));
    if (min > max - minGap) {
      if (this.activeThumb === 'min') {
        min = max - minGap;
        els.minInput.value = min;
      } else {
        max = min + minGap;
        els.maxInput.value = max;
      }
    }

    // Update displays
    if (els.minDisplay) els.minDisplay.textContent = min;
    if (els.maxDisplay) els.maxDisplay.textContent = max;

    // Update hidden inputs
    if (els.hiddenMin) els.hiddenMin.value = min;
    if (els.hiddenMax) els.hiddenMax.value = max;

    // Update visual track
    const percentMin = ((min - els.rangeMin) / (els.rangeMax - els.rangeMin)) * 100;
    const percentMax = ((max - els.rangeMin) / (els.rangeMax - els.rangeMin)) * 100;

    if (els.selectedTrack) {
      els.selectedTrack.style.left = `${percentMin}%`;
      els.selectedTrack.style.width = `${percentMax - percentMin}%`;
    }
  }

  initFromUrl() {
    const params = new URLSearchParams(window.location.search);

    // Reset all inputs first
    this.form.querySelectorAll('input[type="checkbox"][data-filter-input]').forEach((input) => {
      input.checked = false;
    });

    // Set values from URL
    params.forEach((value, key) => {
      if (key === 'min_price') {
        const minInput = this.form.querySelector('[data-price-min-input]');
        const hiddenMin = this.form.querySelector('[data-filter-min-price]');
        if (minInput) minInput.value = value;
        if (hiddenMin) hiddenMin.value = value;
      } else if (key === 'max_price') {
        const maxInput = this.form.querySelector('[data-price-max-input]');
        const hiddenMax = this.form.querySelector('[data-filter-max-price]');
        if (maxInput) maxInput.value = value;
        if (hiddenMax) hiddenMax.value = value;
      } else {
        // Handle array values (comma-separated)
        const values = value.split(',');
        values.forEach((v) => {
          const checkbox = this.form.querySelector(
            `input[name="${key}[]"][value="${v}"]`
          );
          if (checkbox) checkbox.checked = true;
        });
      }
    });

    // Update price range visual
    this.updatePriceRangeVisuals();
    this.updateActiveChips();
  }

  debounceFilter(delay = 300) {
    clearTimeout(this.debounceTimer);
    this.debounceTimer = setTimeout(() => this.filterProducts(), delay);
  }

  async filterProducts(page = 1, updateUrl = true) {
    if (this.isLoading) return;

    this.isLoading = true;
    this.setLoadingState(true);

    const formData = new FormData(this.form);
    formData.append('action', 'sunnytree_filter_products');
    formData.append('nonce', this.config.nonce || '');
    formData.append('page', page.toString());

    try {
      const response = await fetch(this.config.ajaxUrl || '/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: formData,
      });

      const data = await response.json();

      if (data.success) {
        this.updateProducts(data.data.html);
        this.updateResultCount(data.data.total);

        if (updateUrl) {
          this.updateUrl(page);
        }

        this.updateActiveChips();

        // Scroll to top of products on page change (not on filter change)
        if (page > 1) {
          this.productsContainer?.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
          });
        }
      }
    } catch (error) {
      console.error('Filter error:', error);
    } finally {
      this.isLoading = false;
      this.setLoadingState(false);
    }
  }

  updateProducts(html) {
    if (!this.productsContainer) return;

    this.productsContainer.innerHTML = html;

    // Re-run matchHeight for consistent card heights (if using jQuery matchHeight)
    if (typeof jQuery !== 'undefined' && jQuery.fn.matchHeight) {
      jQuery('.sunny-product-title').matchHeight({ byRow: false });
      jQuery('.sunny-product-title-cat').matchHeight({ byRow: false });
      jQuery('.sunny-special-content').matchHeight({ byRow: false });
    }
  }

  updateResultCount(total) {
    const resultCount = document.querySelector('.woocommerce-result-count');
    if (resultCount) {
      const productWord = total === 1
        ? (this.config.i18n?.product || 'product')
        : (this.config.i18n?.products || 'producten');
      resultCount.textContent = `${total} ${productWord}`;
    }

    // Update mobile button count
    const mobileCount = this.sidebar.querySelector('[data-filter-count]');
    if (mobileCount) {
      mobileCount.textContent = `(${total})`;
    }
  }

  updateUrl(page = 1) {
    const formData = new FormData(this.form);
    const params = new URLSearchParams();

    // Group values by key
    const grouped = {};
    formData.forEach((value, key) => {
      // Skip empty values and current_category (internal use only)
      if (!value || key === 'current_category') return;

      const cleanKey = key.replace('[]', '');
      if (!grouped[cleanKey]) {
        grouped[cleanKey] = [];
      }
      grouped[cleanKey].push(value);
    });

    // Add grouped values to params
    Object.entries(grouped).forEach(([key, values]) => {
      params.set(key, values.join(','));
    });

    // Remove default price values
    const priceRange = this.sidebar.querySelector('[data-price-range]');
    if (priceRange) {
      const rangeMin = parseInt(priceRange.dataset.min, 10);
      const rangeMax = parseInt(priceRange.dataset.max, 10);
      const currentMin = parseInt(params.get('min_price') || '0', 10);
      const currentMax = parseInt(params.get('max_price') || '0', 10);

      if (currentMin <= rangeMin) params.delete('min_price');
      if (currentMax >= rangeMax) params.delete('max_price');
    }

    // Add page if not first
    if (page > 1) {
      params.set('page', page.toString());
    }

    const newUrl = params.toString()
      ? `${window.location.pathname}?${params.toString()}`
      : window.location.pathname;

    window.history.pushState({}, '', newUrl);
  }

  updateActiveChips() {
    if (!this.chipsContainer) return;

    const chips = [];
    const formData = new FormData(this.form);

    // Collect checkbox filters
    formData.forEach((value, key) => {
      // Skip hidden fields and current_category
      if (key === 'current_category' || key === 'min_price' || key === 'max_price') return;

      const cleanKey = key.replace('[]', '');
      const input = this.form.querySelector(`input[name="${key}"][value="${value}"]`);
      let label = value;

      if (input && input.dataset.label) {
        label = input.dataset.label;
      }

      chips.push({ key: cleanKey, value, label });
    });

    // Check price range
    const priceRange = this.sidebar.querySelector('[data-price-range]');
    if (priceRange) {
      const rangeMin = parseInt(priceRange.dataset.min, 10);
      const rangeMax = parseInt(priceRange.dataset.max, 10);
      const hiddenMin = this.form.querySelector('[data-filter-min-price]');
      const hiddenMax = this.form.querySelector('[data-filter-max-price]');
      const currentMin = parseInt(hiddenMin?.value || '0', 10);
      const currentMax = parseInt(hiddenMax?.value || '0', 10);

      if (currentMin > rangeMin || currentMax < rangeMax) {
        chips.push({
          key: 'price',
          value: `${currentMin}-${currentMax}`,
          label: `\u20AC${currentMin} - \u20AC${currentMax}`,
        });
      }
    }

    // Render chips
    if (chips.length === 0) {
      this.chipsContainer.innerHTML = '';
      this.chipsContainer.style.display = 'none';
      return;
    }

    this.chipsContainer.style.display = '';
    this.chipsContainer.innerHTML = chips
      .map(
        (chip) => `
      <span class="filter-chip" data-filter-chip="${this.escapeHtml(chip.key)}" data-filter-value="${this.escapeHtml(chip.value)}">
        <span class="filter-chip__label">${this.escapeHtml(chip.label)}</span>
        <button type="button" class="filter-chip__remove" data-filter-chip-remove aria-label="Verwijder filter">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 6 6 18M6 6l12 12"/>
          </svg>
        </button>
      </span>
    `
      )
      .join('');
  }

  removeFilter(key, value) {
    if (key === 'price') {
      // Reset price range
      const priceRange = this.sidebar.querySelector('[data-price-range]');
      if (priceRange) {
        const rangeMin = parseInt(priceRange.dataset.min, 10);
        const rangeMax = parseInt(priceRange.dataset.max, 10);
        const minInput = priceRange.querySelector('[data-price-min-input]');
        const maxInput = priceRange.querySelector('[data-price-max-input]');

        if (minInput) minInput.value = rangeMin;
        if (maxInput) maxInput.value = rangeMax;
        this.updatePriceRangeVisuals();
      }
    } else {
      // Uncheck the checkbox
      const checkbox = this.form.querySelector(
        `input[name="${key}[]"][value="${value}"]`
      );
      if (checkbox) checkbox.checked = false;
    }

    this.filterProducts();
  }

  clearAllFilters() {
    // Reset all checkboxes
    this.form
      .querySelectorAll('input[type="checkbox"]')
      .forEach((cb) => (cb.checked = false));

    // Reset price range using stored elements or query fresh
    const els = this.priceRangeElements;
    if (els) {
      els.minInput.value = els.rangeMin;
      els.maxInput.value = els.rangeMax;
      this.updatePriceRangeVisuals();
    }

    this.filterProducts();
  }

  toggleGroup(toggle) {
    const group = toggle.closest('[data-filter-group]');
    const content = group?.querySelector('.filter-group__content');
    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';

    toggle.setAttribute('aria-expanded', (!isExpanded).toString());

    if (content) {
      if (isExpanded) {
        content.style.maxHeight = '0';
        content.style.overflow = 'hidden';
      } else {
        content.style.maxHeight = `${content.scrollHeight}px`;
        content.style.overflow = '';
        // Remove max-height after transition to allow dynamic content
        setTimeout(() => {
          if (toggle.getAttribute('aria-expanded') === 'true') {
            content.style.maxHeight = '';
          }
        }, 300);
      }
    }
  }

  toggleShowMore(btn) {
    const group = btn.closest('[data-filter-group]');
    const hiddenItems = group?.querySelectorAll('.filter-group__item--hidden');
    const moreText = btn.querySelector('[data-more-text]');
    const lessText = btn.querySelector('[data-less-text]');
    const icon = btn.querySelector('.filter-group__more-icon');

    const isExpanded = btn.classList.contains('is-expanded');

    hiddenItems?.forEach((item) => {
      item.style.display = isExpanded ? 'none' : '';
    });

    btn.classList.toggle('is-expanded');

    if (moreText && lessText) {
      moreText.style.display = isExpanded ? '' : 'none';
      lessText.style.display = isExpanded ? 'none' : '';
    }

    if (icon) {
      icon.style.transform = isExpanded ? '' : 'rotate(180deg)';
    }
  }

  openMobileDrawer() {
    this.sidebar?.classList.add('is-open');
    this.backdrop?.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  closeMobileDrawer() {
    this.sidebar?.classList.remove('is-open');
    this.backdrop?.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  setLoadingState(loading) {
    this.productsContainer?.classList.toggle('is-loading', loading);
    this.sidebar?.classList.toggle('is-loading', loading);

    // Add/remove loading overlay
    if (loading && this.productsContainer) {
      const overlay = document.createElement('div');
      overlay.className = 'products-loading-overlay';
      overlay.innerHTML = '<span class="products-loading-spinner"></span>';
      this.productsContainer.appendChild(overlay);
    } else {
      this.productsContainer
        ?.querySelector('.products-loading-overlay')
        ?.remove();
    }
  }

  getPageFromUrl(url) {
    try {
      const urlObj = new URL(url);
      return parseInt(
        urlObj.searchParams.get('paged') ||
          urlObj.searchParams.get('page') ||
          '1',
        10
      );
    } catch {
      return 1;
    }
  }

  escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  new ProductFilters();
});

export default ProductFilters;
