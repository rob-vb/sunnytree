/**
 * Single Product Page JavaScript
 *
 * Handles gallery (Swiper), variant selection, and cross-sell add-to-cart.
 * Adapted for reference HTML structure.
 *
 * @package SunnyTree
 */

import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';

class SingleProduct {
  constructor() {
    this.gallery = document.querySelector('#product__images');
    this.variantsContainer = document.querySelector('[data-product-variants]');
    this.crossSellBtns = document.querySelectorAll('[data-cross-sell-add]');
    this.addToCartBtn = document.querySelector('[data-add-to-cart]');

    this.mainSwiper = null;

    this.init();
  }

  init() {
    if (this.gallery) {
      this.initGallery();
      this.initThumbnails();
    }

    if (this.variantsContainer) {
      this.initVariants();
    }

    if (this.crossSellBtns.length) {
      this.initCrossSellButtons();
    }

    if (this.addToCartBtn) {
      this.initAddToCart();
    }
  }

  /**
   * Initialize Swiper gallery
   */
  initGallery() {
    const mainEl = this.gallery.querySelector('[data-gallery-main]');
    if (!mainEl) return;

    // Initialize main swiper
    this.mainSwiper = new Swiper(mainEl, {
      modules: [Navigation],
      spaceBetween: 10,
      loop: true,
      navigation: {
        nextEl: '[data-gallery-next]',
        prevEl: '[data-gallery-prev]',
      },
      on: {
        slideChange: () => {
          this.updateThumbnailActive();
        },
      },
    });
  }

  /**
   * Initialize thumbnail click navigation
   */
  initThumbnails() {
    const thumbContainer = this.gallery.querySelector('#product__thumb');
    if (!thumbContainer) return;

    const thumbs = thumbContainer.querySelectorAll('span[data-id]');

    thumbs.forEach((thumb) => {
      thumb.addEventListener('click', () => {
        const slideIndex = parseInt(thumb.dataset.id, 10);

        // Update swiper (accounting for loop mode duplicates)
        if (this.mainSwiper) {
          this.mainSwiper.slideToLoop(slideIndex);
        }

        // Update active thumbnail
        thumbs.forEach((t) => t.classList.remove('thumb--active'));
        thumb.classList.add('thumb--active');
      });
    });
  }

  /**
   * Update thumbnail active state based on swiper position
   */
  updateThumbnailActive() {
    if (!this.mainSwiper) return;

    const thumbContainer = this.gallery.querySelector('#product__thumb');
    if (!thumbContainer) return;

    const thumbs = thumbContainer.querySelectorAll('span[data-id]');
    const realIndex = this.mainSwiper.realIndex;

    thumbs.forEach((thumb) => {
      const thumbIndex = parseInt(thumb.dataset.id, 10);
      if (thumbIndex === realIndex) {
        thumb.classList.add('thumb--active');
      } else {
        thumb.classList.remove('thumb--active');
      }
    });
  }

  /**
   * Initialize variant button selection
   */
  initVariants() {
    const variationDataEl = document.querySelector('[data-product-variations]');
    if (!variationDataEl) return;

    let variations;
    try {
      variations = JSON.parse(variationDataEl.textContent);
    } catch {
      console.warn('Could not parse variation data');
      return;
    }

    const buttons = this.variantsContainer.querySelectorAll('[data-variant-option]');
    const priceContainer = document.querySelector('[data-variation-price]');
    const variationIdInput = document.querySelector('[data-variation-id]');

    // Track selected attributes
    const selectedAttributes = {};

    buttons.forEach((btn) => {
      btn.addEventListener('click', () => {
        const attrName = btn.dataset.attributeName;
        const attrValue = btn.dataset.attributeValue;
        const variationId = btn.dataset.variationId;

        // Update selection state - remove active class from siblings
        const siblings = this.variantsContainer.querySelectorAll('[data-variant-option]');
        siblings.forEach((s) => {
          if (s.dataset.attributeName === attrName) {
            s.classList.remove('sunny-varianten-tegel--active');
          }
        });

        // Add active class to clicked button
        btn.classList.add('sunny-varianten-tegel--active');

        // Store selection
        selectedAttributes[`attribute_${attrName}`] = attrValue;

        // Update hidden attribute field
        const attrField = document.querySelector(`[data-attribute-field="${attrName}"]`);
        if (attrField) {
          attrField.value = attrValue;
        }

        // Update hidden variation_id input directly from data attribute
        if (variationIdInput && variationId) {
          variationIdInput.value = variationId;
        }

        // Find matching variation for price update
        const matchingVariation = this.findMatchingVariation(variations, selectedAttributes);

        if (matchingVariation) {
          // Update price
          if (priceContainer && matchingVariation.price_html) {
            priceContainer.querySelector('.price__number').innerHTML = matchingVariation.price_html;
          }

          // Trigger WooCommerce variation found event
          const event = new CustomEvent('found_variation', {
            detail: matchingVariation,
          });
          document.body.dispatchEvent(event);
        }
      });
    });

    // Auto-select first option
    const firstBtn = this.variantsContainer.querySelector('[data-variant-option]');
    if (firstBtn) {
      firstBtn.click();
    }
  }

  /**
   * Find variation matching selected attributes
   */
  findMatchingVariation(variations, selectedAttributes) {
    return variations.find((variation) => {
      return Object.entries(selectedAttributes).every(([key, value]) => {
        const variationValue = variation.attributes[key];
        // Empty means "any" in WooCommerce variations
        return variationValue === '' || variationValue === value;
      });
    });
  }

  /**
   * Initialize cross-sell add to cart buttons
   */
  initCrossSellButtons() {
    let selectedExtraProducts = 0;

    this.crossSellBtns.forEach((btn) => {
      const productLine = btn.closest('.sunny-c--product-line');
      const amountInput = productLine.querySelector('.product-amount');
      const productIdInput = productLine.querySelector('.product-id');

      btn.addEventListener('click', (e) => {
        e.preventDefault();

        // Toggle button/input visibility
        btn.style.display = 'none';
        amountInput.style.display = 'block';
        amountInput.value = 1;

        // Set the product name attributes
        const index = selectedExtraProducts + 1;
        productIdInput.name = `product[${index}][product_id]`;
        amountInput.name = `product[${index}][product_amount]`;

        selectedExtraProducts++;
        this.updateOrderButton();
        this.updateTotalAmount();
      });

      // Handle amount change
      if (amountInput) {
        amountInput.addEventListener('change', () => {
          if (parseInt(amountInput.value, 10) === 0) {
            // Remove from selection
            amountInput.style.display = 'none';
            btn.style.display = 'flex';
            productIdInput.name = '';
            amountInput.name = '';
            selectedExtraProducts--;
            this.updateOrderButton();
          }
          this.updateTotalAmount();
        });
      }
    });
  }

  /**
   * Update order button text (single vs multiple products)
   */
  updateOrderButton() {
    const oneProduct = document.querySelector('.one-product');
    const moreProducts = document.querySelector('.more-products');
    const hasExtras = document.querySelectorAll('.sunny-c--product-line input.product-amount[style*="block"]').length > 0;

    if (oneProduct && moreProducts) {
      if (hasExtras) {
        oneProduct.style.display = 'none';
        moreProducts.style.display = 'flex';
      } else {
        oneProduct.style.display = 'flex';
        moreProducts.style.display = 'none';
      }
    }
  }

  /**
   * Update total amount display
   */
  updateTotalAmount() {
    const baseAmount = parseInt(document.querySelector('#product__amount')?.value || 1, 10);
    let totalAmount = baseAmount;

    document.querySelectorAll('.sunny-c--product-line .product-amount').forEach((input) => {
      if (input.style.display !== 'none') {
        totalAmount += parseInt(input.value || 0, 10);
      }
    });

    const amountSpan = document.querySelector('.more-products .amount');
    if (amountSpan) {
      amountSpan.textContent = totalAmount;
    }

    // Update base product amount
    const baseAmountInput = document.querySelector('.sunny-c--complete-order__base-product .amount');
    if (baseAmountInput) {
      baseAmountInput.value = baseAmount;
    }
  }

  /**
   * Initialize main add to cart button
   */
  initAddToCart() {
    this.addToCartBtn.addEventListener('click', async () => {
      const form = document.querySelector('#form-product');
      if (!form) return;

      this.addToCartBtn.classList.add('is-loading');

      try {
        const formData = new FormData(form);

        // For simple products, add directly
        const productId = this.addToCartBtn.dataset.productId;
        const quantity = document.querySelector('#product__amount')?.value || 1;

        // Check if it's using the multi-product form structure
        const hasMultiProducts = document.querySelector('[name="product[0][product_id]"]');

        if (hasMultiProducts) {
          // Submit the multi-product form via AJAX
          const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData,
          });

          if (response.ok) {
            // Redirect to cart or reload
            window.location.reload();
          }
        } else {
          // Simple add to cart
          const ajaxData = new URLSearchParams();
          ajaxData.append('product_id', productId);
          ajaxData.append('quantity', quantity);

          // Add variation data if present
          const variationId = document.querySelector('[data-variation-id]')?.value;
          if (variationId) {
            ajaxData.append('variation_id', variationId);

            // Add attribute values
            document.querySelectorAll('[data-attribute-field]').forEach((input) => {
              ajaxData.append(input.name, input.value);
            });
          }

          const response = await fetch('/?wc-ajax=add_to_cart', {
            method: 'POST',
            body: ajaxData,
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
          });

          const data = await response.json();

          if (data.fragments) {
            // Update cart fragments
            Object.entries(data.fragments).forEach(([selector, html]) => {
              const el = document.querySelector(selector);
              if (el) {
                el.outerHTML = html;
              }
            });

            // Trigger WooCommerce event
            document.body.dispatchEvent(
              new CustomEvent('added_to_cart', {
                detail: {
                  fragments: data.fragments,
                  cart_hash: data.cart_hash,
                  product_id: productId,
                },
              })
            );

            // Show success feedback
            this.addToCartBtn.classList.add('is-added');
            setTimeout(() => {
              this.addToCartBtn.classList.remove('is-added');
            }, 2000);
          }
        }
      } catch (error) {
        console.error('Add to cart error:', error);
      } finally {
        this.addToCartBtn.classList.remove('is-loading');
      }
    });

    // Also handle Enter key on the button
    this.addToCartBtn.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        this.addToCartBtn.click();
      }
    });

    // Update amount when quantity changes
    const quantityInput = document.querySelector('#product__amount');
    if (quantityInput) {
      quantityInput.addEventListener('change', () => {
        this.updateTotalAmount();
      });
    }
  }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  // Check for product page elements
  if (document.querySelector('#product') || document.querySelector('.product')) {
    new SingleProduct();
  }
});

export default SingleProduct;
