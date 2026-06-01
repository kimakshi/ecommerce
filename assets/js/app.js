document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('[data-mobile-menu-toggle]');
  const menu = document.querySelector('[data-mobile-menu]');
  if (toggle && menu) {
    const setOpen = (open) => {
      menu.classList.toggle('open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    };

    toggle.addEventListener('click', () => {
      setOpen(!menu.classList.contains('open'));
    });

    menu.querySelectorAll('a').forEach((a) => {
      a.addEventListener('click', () => setOpen(false));
    });
  }

  const modal = document.querySelector('[data-img-modal]');
  const modalImg = document.querySelector('[data-img-modal-img]');
  const stage = document.querySelector('[data-img-stage]');
  const closeBtns = document.querySelectorAll('[data-img-modal-close]');
  const btnIn = document.querySelector('[data-img-zoom-in]');
  const btnOut = document.querySelector('[data-img-zoom-out]');
  const btnReset = document.querySelector('[data-img-zoom-reset]');
  const triggers = document.querySelectorAll('[data-zoom-img]');

  if (modal && modalImg && stage && btnIn && btnOut && btnReset && triggers.length) {
    let scale = 1;
    let tx = 0;
    let ty = 0;
    const min = 1;
    const max = 4;
    const step = 0.25;

    const apply = () => {
      modalImg.style.transform = `translate(${tx}px, ${ty}px) scale(${scale})`;
    };

    const reset = () => {
      scale = 1;
      tx = 0;
      ty = 0;
      apply();
    };

    const open = (src, alt) => {
      modalImg.src = src;
      modalImg.alt = alt || '';
      modal.classList.add('open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
      reset();
    };

    const close = () => {
      modal.classList.remove('open');
      modal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
      modalImg.src = '';
      reset();
    };

    triggers.forEach((img) => {
      img.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        const src = img.getAttribute('src') || '';
        if (src) open(src, img.getAttribute('alt') || '');
      });
    });

    closeBtns.forEach((b) => b.addEventListener('click', close));

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modal.classList.contains('open')) close();
    });

    btnIn.addEventListener('click', () => {
      scale = Math.min(max, +(scale + step).toFixed(2));
      apply();
    });
    btnOut.addEventListener('click', () => {
      scale = Math.max(min, +(scale - step).toFixed(2));
      if (scale === 1) {
        tx = 0;
        ty = 0;
      }
      apply();
    });
    btnReset.addEventListener('click', reset);

    stage.addEventListener('wheel', (e) => {
      if (!modal.classList.contains('open')) return;
      e.preventDefault();
      const direction = e.deltaY > 0 ? -1 : 1;
      scale = Math.min(max, Math.max(min, +(scale + direction * step).toFixed(2)));
      if (scale === 1) {
        tx = 0;
        ty = 0;
      }
      apply();
    }, { passive: false });

    let dragging = false;
    let startX = 0;
    let startY = 0;
    let startTx = 0;
    let startTy = 0;

    const pointerDown = (e) => {
      if (!modal.classList.contains('open')) return;
      if (scale <= 1) return;
      dragging = true;
      modalImg.classList.add('dragging');
      startX = e.clientX;
      startY = e.clientY;
      startTx = tx;
      startTy = ty;
      stage.setPointerCapture(e.pointerId);
    };
    const pointerMove = (e) => {
      if (!dragging) return;
      tx = startTx + (e.clientX - startX);
      ty = startTy + (e.clientY - startY);
      apply();
    };
    const pointerUp = () => {
      dragging = false;
      modalImg.classList.remove('dragging');
    };

    stage.addEventListener('pointerdown', pointerDown);
    stage.addEventListener('pointermove', pointerMove);
    stage.addEventListener('pointerup', pointerUp);
    stage.addEventListener('pointercancel', pointerUp);
  }

  const priceRange = document.querySelector('[data-price-range]');
  const priceLabel = document.querySelector('[data-price-label]');
  if (priceRange && priceLabel) {
    const sync = () => {
      priceLabel.textContent = `Price: ₹${parseInt(priceRange.value, 10).toLocaleString('en-IN')}`;
    };
    priceRange.addEventListener('input', sync);
    sync();
  }
});
