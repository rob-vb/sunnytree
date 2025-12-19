/**
 * Single Product Page JavaScript
 *
 * Handles gallery (Swiper), variant selection, and cross-sell add-to-cart.
 * Adapted for reference HTML structure.
 *
 * @package SunnyTree
 */

import Swiper from 'swiper';
import { Navigation, Thumbs, FreeMode } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/thumbs';
import 'swiper/css/free-mode';
import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';

class SingleProduct {
  constructor() {
    this.gallery = document.querySelector('#product__images');
    this.variantsContainer = document.querySelector('[data-product-variants]');
    this.crossSellBtns = document.querySelectorAll('[data-cross-sell-add]');
    this.addToCartBtn = document.querySelector('[data-add-to-cart]');

    this.mainSwiper = null;
    this.thumbSwiper = null;

    this.init();
  }

  init() {
    if (this.gallery) {
      this.initGallery();
      this.initLightbox();
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
   * Initialize Swiper gallery with thumbs
   */
  initGallery() {
    const mainEl = this.gallery.querySelector('.product__gallery-main');
    const thumbsEl = this.gallery.querySelector('.product__gallery-thumbs');
    if (!mainEl) return;

    // Initialize thumbs first (if present)
    if (thumbsEl) {
      this.thumbSwiper = new Swiper(thumbsEl, {
        modules: [FreeMode],
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
      });
    }

    // Initialize main swiper
    this.mainSwiper = new Swiper(mainEl, {
      modules: [Navigation, Thumbs],
      spaceBetween: 10,
      loop: true,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      thumbs: this.thumbSwiper ? { swiper: this.thumbSwiper } : undefined,
    });
  }

  /**
   * Initialize PhotoSwipe lightbox
   */
  initLightbox() {
    const galleryEl = this.gallery.querySelector('#product__gallery');
    if (!galleryEl) return;

    const lightbox = new PhotoSwipeLightbox({
      gallery: '#product__gallery',
      children: 'a',
      pswpModule: () => import('photoswipe'),
    });
    lightbox.init();
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
      this.addToCartBtn.classList.add('is-loading');

      try {
        // Collect main product
        const productId = this.addToCartBtn.dataset.productId;
        const quantity = parseInt(document.querySelector('#product__amount')?.value || 1, 10);

        // Build list of products to add
        const productsToAdd = [];

        // Add main product
        const mainProduct = {
          product_id: productId,
          quantity: quantity,
        };

        // Add variation data if present
        const variationId = document.querySelector('[data-variation-id]')?.value;
        if (variationId) {
          mainProduct.variation_id = variationId;
          mainProduct.attributes = {};
          document.querySelectorAll('[data-attribute-field]').forEach((input) => {
            mainProduct.attributes[input.name] = input.value;
          });
        }

        productsToAdd.push(mainProduct);

        // Collect selected cross-sell products
        document.querySelectorAll('.sunny-c--product-line').forEach((line) => {
          const amountInput = line.querySelector('.product-amount');
          const productIdInput = line.querySelector('.product-id');

          if (amountInput && amountInput.style.display !== 'none') {
            const crossQuantity = parseInt(amountInput.value || 0, 10);
            if (crossQuantity > 0 && productIdInput) {
              productsToAdd.push({
                product_id: productIdInput.value,
                quantity: crossQuantity,
              });
            }
          }
        });

        // Add each product via AJAX
        let lastFragments = null;
        let lastCartHash = null;

        for (const product of productsToAdd) {
          const ajaxData = new URLSearchParams();
          ajaxData.append('product_id', product.product_id);
          ajaxData.append('quantity', product.quantity);

          if (product.variation_id) {
            ajaxData.append('variation_id', product.variation_id);
            if (product.attributes) {
              Object.entries(product.attributes).forEach(([key, value]) => {
                ajaxData.append(key, value);
              });
            }
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
            lastFragments = data.fragments;
            lastCartHash = data.cart_hash;
          }
        }

        // Redirect to cart page after adding products
        if (lastFragments) {
          const cartUrl = this.addToCartBtn.dataset.cartUrl || '/cart/';
          window.location.href = cartUrl;
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
