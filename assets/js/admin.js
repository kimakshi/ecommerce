document.addEventListener('DOMContentLoaded', () => {
  const btn = document.querySelector('[data-admin-menu-btn]');
  const sidebar = document.querySelector('[data-admin-sidebar]');
  if (btn && sidebar) {
    btn.addEventListener('click', () => sidebar.classList.toggle('open'));
  }

  const drawer = document.querySelector('[data-order-drawer]');
  const drawerClose = document.querySelectorAll('[data-order-drawer-close]');
  const drawerTitle = document.querySelector('[data-order-drawer-title]');
  const drawerMeta = document.querySelector('[data-order-drawer-meta]');
  const drawerItems = document.querySelector('[data-order-drawer-items]');
  const drawerTotal = document.querySelector('[data-order-drawer-total]');
  const statusSel = document.querySelector('[data-order-status]');
  const paySel = document.querySelector('[data-order-payment]');
  const saveBtn = document.querySelector('[data-order-save]');

  let currentOrder = '';

  const setDrawerOpen = (open) => {
    if (!drawer) return;
    drawer.classList.toggle('open', open);
    document.body.style.overflow = open ? 'hidden' : '';
  };

  const loadOrder = async (order) => {
    const res = await fetch(`order_detail.php?order=${encodeURIComponent(order)}`);
    const data = await res.json();
    if (!data.ok) throw new Error(data.error || 'Failed');
    return data;
  };

  const renderDrawer = (data) => {
    const o = data.order;
    currentOrder = o.order_number;
    if (drawerTitle) drawerTitle.textContent = `Order ${o.order_number}`;
    if (drawerMeta) drawerMeta.textContent = `${new Date(o.created_at).toLocaleString()}`;
    if (statusSel) statusSel.value = o.status;
    if (paySel) paySel.value = o.payment_status;
    if (drawerTotal) drawerTotal.textContent = `${o.total}`;

    if (drawerItems) {
      drawerItems.innerHTML = '';
      (data.items || []).forEach((it) => {
        const row = document.createElement('div');
        row.style.display = 'flex';
        row.style.justifyContent = 'space-between';
        row.style.gap = '10px';
        row.style.fontSize = '13px';
        row.style.color = 'rgba(229,231,235,.88)';
        row.innerHTML = `<div>${it.product_name} × ${it.quantity}</div><div><b>₹${Number(it.line_total).toLocaleString('en-IN')}</b></div>`;
        drawerItems.appendChild(row);
      });
    }

    const nameEl = document.querySelector('[data-order-customer-name]');
    const contactEl = document.querySelector('[data-order-customer-contact]');
    const addrEl = document.querySelector('[data-order-customer-address]');
    if (nameEl) nameEl.textContent = o.customer_name;
    if (contactEl) contactEl.textContent = `${o.customer_phone} · ${o.customer_email}`;
    if (addrEl) addrEl.textContent = `${o.address_line1}${o.address_line2 ? ', ' + o.address_line2 : ''}, ${o.city}, ${o.state} - ${o.pincode}`;
  };

  document.querySelectorAll('[data-order-open]')?.forEach((btnOpen) => {
    btnOpen.addEventListener('click', async (e) => {
      e.preventDefault();
      const order = btnOpen.getAttribute('data-order-open') || '';
      if (!order) return;
      try {
        setDrawerOpen(true);
        if (drawerTitle) drawerTitle.textContent = 'Loading...';
        const data = await loadOrder(order);
        renderDrawer(data);
      } catch {
        setDrawerOpen(false);
      }
    });
  });

  drawerClose.forEach((b) => b.addEventListener('click', () => setDrawerOpen(false)));
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && drawer && drawer.classList.contains('open')) setDrawerOpen(false);
  });

  if (saveBtn) {
    saveBtn.addEventListener('click', async () => {
      if (!currentOrder) return;
      const payload = new FormData();
      payload.append('order', currentOrder);
      payload.append('status', statusSel ? statusSel.value : 'pending');
      payload.append('payment_status', paySel ? paySel.value : 'unpaid');
      const res = await fetch('order_update.php', { method: 'POST', body: payload });
      const data = await res.json();
      if (!data.ok) return;
      const row = document.querySelector(`[data-order-row="${CSS.escape(currentOrder)}"]`);
      if (row) {
        const st = row.querySelector('[data-order-status-badge]');
        const ps = row.querySelector('[data-order-pay-badge]');
        const sVal = statusSel ? statusSel.value : 'pending';
        const pVal = paySel ? paySel.value : 'unpaid';
        if (st) {
          st.className = `admin-status ${sVal}`;
          st.textContent = sVal;
        }
        if (ps) {
          ps.className = `admin-status ${pVal}`;
          ps.textContent = pVal;
        }
      }
    });
  }

  const chart = document.querySelector('[data-admin-chart]');
  if (chart && chart.getContext) {
    const ctx = chart.getContext('2d');
    const raw = chart.getAttribute('data-series') || '[]';
    let series = [];
    try {
      series = JSON.parse(raw);
    } catch {
      series = [];
    }

    const dpr = window.devicePixelRatio || 1;
    const width = chart.clientWidth || 600;
    const height = chart.clientHeight || 210;
    chart.width = Math.floor(width * dpr);
    chart.height = Math.floor(height * dpr);
    ctx.scale(dpr, dpr);

    const pad = 14;
    ctx.clearRect(0, 0, width, height);

    if (!Array.isArray(series) || series.length < 2) {
      return;
    }

    const values = series.map((p) => Number(p.v) || 0);
    const min = Math.min(...values);
    const max = Math.max(...values);
    const span = Math.max(1, max - min);

    const x0 = pad;
    const y0 = pad;
    const x1 = width - pad;
    const y1 = height - pad;

    ctx.strokeStyle = 'rgba(255,255,255,.10)';
    ctx.lineWidth = 1;
    for (let i = 0; i <= 4; i += 1) {
      const y = y0 + (i * (y1 - y0)) / 4;
      ctx.beginPath();
      ctx.moveTo(x0, y);
      ctx.lineTo(x1, y);
      ctx.stroke();
    }

    const toX = (i) => x0 + (i * (x1 - x0)) / (series.length - 1);
    const toY = (v) => y1 - ((v - min) * (y1 - y0)) / span;

    ctx.beginPath();
    series.forEach((p, i) => {
      const x = toX(i);
      const y = toY(p.v);
      if (i === 0) ctx.moveTo(x, y);
      else ctx.lineTo(x, y);
    });

    ctx.strokeStyle = 'rgba(34,197,94,.95)';
    ctx.lineWidth = 2;
    ctx.stroke();

    ctx.lineTo(toX(series.length - 1), y1);
    ctx.lineTo(toX(0), y1);
    ctx.closePath();

    const grad = ctx.createLinearGradient(0, y0, 0, y1);
    grad.addColorStop(0, 'rgba(34,197,94,.25)');
    grad.addColorStop(1, 'rgba(34,197,94,0)');
    ctx.fillStyle = grad;
    ctx.fill();

    ctx.fillStyle = 'rgba(255,255,255,.75)';
    ctx.font = '12px Poppins, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif';
    ctx.textBaseline = 'top';
    ctx.fillText('Sales (last 7 days)', x0, y0);
  }
});
