(function () {
  'use strict';

  function initRingSizeSwatches() {
    var select = document.querySelector('.variations_form select[name="attribute_pa_ring-size"]');
    if (!select || select.dataset.monolithSwatchesReady === '1') return;

    select.dataset.monolithSwatchesReady = '1';

    var valueCell = select.closest('td.value') || select.parentNode;
    var wrap = document.createElement('div');
    wrap.className = 'mono-size-swatches';
    wrap.setAttribute('role', 'group');
    wrap.setAttribute('aria-label', 'Ring size');

    select.classList.add('mono-native-variation-select');
    valueCell.insertBefore(wrap, select);

    function sync() {
      wrap.innerHTML = '';

      Array.prototype.slice.call(select.options).forEach(function (option) {
        if (!option.value) return;

        var button = document.createElement('button');
        button.type = 'button';
        button.className = 'mono-size-swatch';
        button.textContent = option.textContent.trim();
        button.dataset.value = option.value;
        button.disabled = !!option.disabled;
        button.setAttribute('aria-pressed', option.value === select.value ? 'true' : 'false');

        if (option.value === select.value) {
          button.classList.add('is-selected');
        }

        button.addEventListener('click', function () {
          if (button.disabled) return;
          select.value = button.dataset.value;
          select.dispatchEvent(new Event('change', { bubbles: true }));
        });

        wrap.appendChild(button);
      });
    }

    sync();
    select.addEventListener('change', sync);

    var observer = new MutationObserver(sync);
    observer.observe(select, { childList: true, subtree: true, attributes: true, attributeFilter: ['disabled'] });
  }

  // The native WooCommerce variation button stays disabled until size is set.
  // Make that initial state instructive, while preserving the native variation
  // validation and add-to-cart submission.
  function initMonolithPurchaseAction() {
    var form = document.querySelector('.monolith-cinematic-pdp .variations_form');
    if (!form || form.dataset.monolithPurchaseReady === '1') return;
    var button = form.querySelector('.single_add_to_cart_button');
    var select = form.querySelector('select[name="attribute_pa_ring-size"]');
    if (!button || !select) return;
    form.dataset.monolithPurchaseReady = '1';

    function syncPurchaseAction() {
      var hasSize = !!select.value;
      var disabled = button.disabled || button.classList.contains('disabled') ||
                     button.classList.contains('wc-variation-selection-needed');
      var label = !hasSize ? 'SELECT RING SIZE' : (disabled ? 'CHECKING AVAILABILITY' : 'ADD TO BAG');
      if (button.textContent.trim() !== label) button.textContent = label;
      if (button.getAttribute('aria-label') !== label) button.setAttribute('aria-label', label);
    }

    syncPurchaseAction();
    select.addEventListener('change', function() {
      syncPurchaseAction();
      window.setTimeout(syncPurchaseAction, 60);
    });
    new MutationObserver(syncPurchaseAction).observe(button, {
      attributes:true, attributeFilter:['class','disabled']
    });
    if (window.jQuery) {
      window.jQuery(form).on(
        'found_variation show_variation hide_variation reset_data woocommerce_variation_has_changed',
        syncPurchaseAction
      );
    }
  }

  document.addEventListener('DOMContentLoaded', initRingSizeSwatches);
  document.addEventListener('DOMContentLoaded', initMonolithPurchaseAction);
  document.addEventListener('wc_variation_form', initRingSizeSwatches);

  if (window.jQuery) {
    window.jQuery(function ($) {
      $('.variations_form').on('woocommerce_update_variation_values reset_data found_variation', initRingSizeSwatches);
    });
  }
}());
