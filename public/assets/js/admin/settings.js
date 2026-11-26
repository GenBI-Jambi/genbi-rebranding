(function () {
  'use strict';

  const fallback = window.GenBIData || {};
  const bootstrap = window.GenBISettingsBootstrap || {};
  const initialSite = bootstrap.site || window.GenBISiteSettings || fallback.site || {};
  const tabs = fallback.settingTabs || [];
  const { renderAdminShell, icon, showToast, escapeHtml } = window.GenBIAdmin;
  const API = window.GenBIAPI;

  function resolveActiveTab() {
    const hash = (window.location.hash || '').replace('#', '').toLowerCase();
    const validKeys = tabs.map((t) => t.key);
    return validKeys.includes(hash) ? hash : (validKeys[0] || 'logo');
  }

  const state = {
    active: resolveActiveTab(),
    site: initialSite,
  };

  renderAdminShell('settings');
  renderTabs();
  renderPanel();

  window.addEventListener('hashchange', () => {
    const newTab = resolveActiveTab();
    if (newTab !== state.active) {
      state.active = newTab;
      renderTabs();
      renderPanel();
    }
  });

  function renderTabs() {
    const root = document.querySelector('#settings-tabs');
    if (!root) return;
    root.innerHTML = tabs.map((tab) => `
      <button type="button" class="admin-tab ${tab.key === state.active ? 'is-active' : ''}" data-tab="${tab.key}">
        ${icon(tab.icon)}
        ${tab.label}
      </button>
    `).join('');

    root.querySelectorAll('button').forEach((button) => {
      button.addEventListener('click', () => {
        state.active = button.dataset.tab;
        history.replaceState(null, '', '#' + state.active);
        renderTabs();
        renderPanel();
      });
    });
  }

  function panelLayout(title, description, content) {
    return `
      <div class="admin-editor-shell slide-in">
        <header class="admin-editor-head">
          <div>
            <p class="eyebrow">${title}</p>
            <h2 class="serif mt-3 text-3xl sm:text-4xl font-semibold tracking-tight text-[rgb(var(--text-primary))]">${title}</h2>
            <p class="mt-3 max-w-3xl text-sm sm:text-base leading-relaxed sm:leading-7 text-[rgb(var(--text-secondary))]">${description}</p>
          </div>
        </header>
        <div class="admin-editor-canvas">${content}</div>
      </div>
    `;
  }

  function field(label, key, value, type = 'text') {
    const tag = type === 'textarea'
      ? `<textarea class="input-soft settings-input min-h-32" data-field="${key}">${escapeHtml(value || '')}</textarea>`
      : `<input class="input-soft settings-input" data-field="${key}" type="${type}" value="${escapeHtml(value || '')}">`;
    return `<label class="settings-field"><span class="settings-field-label">${label}</span>${tag}</label>`;
  }

  function fileField(label, key, value) {
    return `
      <div class="settings-upload-card">
        <div class="settings-upload-head">
          ${value ? `<img src="${escapeHtml(value)}" alt="${escapeHtml(label)}" class="settings-upload-preview">` : ''}
          <div>
            <p class="settings-upload-title">${label}</p>
            <p class="settings-upload-note">Unggah gambar baru atau masukkan URL langsung, lalu simpan perubahan.</p>
          </div>
        </div>
        <div class="grid gap-3 mt-1">
          <input class="input-soft settings-input" data-field="${key}" type="text" value="${escapeHtml(value || '')}" placeholder="/uploads/branding/...">
          <input class="admin-file-input settings-file-input" type="file" data-upload-for="${key}" accept="image/*,.ico,.svg">
        </div>
      </div>
    `;
  }

  function bannerPreview(label, imageUrl, caption = '') {
    return `
      <article class="settings-banner-preview">
        <div class="settings-banner-preview-media">
          ${imageUrl ? `<img src="${escapeHtml(imageUrl)}" alt="${escapeHtml(label)}" loading="lazy">` : '<div class="settings-banner-preview-empty">Belum ada gambar</div>'}
        </div>
        <div class="settings-banner-preview-copy">
          <p class="settings-banner-preview-label">${escapeHtml(label)}</p>
          <p class="settings-banner-preview-caption">${escapeHtml(caption || 'Preview banner saat ini dari pengaturan live.')}</p>
        </div>
      </article>
    `;
  }

  function heroPreview() {
    const primary = state.site.heroSlides?.[0] || {};
    const secondary = state.site.heroSlides?.[1] || {};
    const image = primary.image || secondary.image || '';
    const eyebrow = primary.eyebrow || 'Energi untuk Negeri';
    const title = primary.title || 'Bersama GenBI, tumbuh dan berdampak untuk Jambi.';
    const caption = primary.caption || 'Preview hero halaman utama.';
    return `
      <section class="settings-hero-preview" style="--settings-hero-image: url('${escapeHtml(image)}')">
        <div class="settings-hero-preview-overlay"></div>
        <div class="settings-hero-preview-copy">
          <span>${escapeHtml(eyebrow)}</span>
          <h3>${escapeHtml(title)}</h3>
          <p>${escapeHtml(caption)}</p>
          <div class="settings-hero-preview-actions"><i></i><i></i></div>
        </div>
      </section>
    `;
  }

  function renderPanel() {
    const root = document.querySelector('#settings-panel');
    if (!root) return;
    const panels = {
      logo: () => renderFormPanel('Logo', 'Logo website untuk publik dan admin.', 'logo', [fileField('Logo URL', 'site.logo_url', state.site.logo)]),
      favicon: () => renderFormPanel('Favicon', 'Favicon browser mengikuti setting live.', 'favicon', [fileField('Favicon URL', 'site.favicon_url', state.site.favicon)]),
      topbar: () => renderFormPanel('Top Bar', 'Kontak cepat pada bagian atas website publik.', 'topbar', [field('Email', 'site.topbar_email', state.site.email, 'email'), field('Phone', 'site.topbar_phone', state.site.phone)]),
      footer: () => renderFormPanel('Footer', 'Konten footer dan identitas organisasi.', 'footer', [field('Site Name', 'site.name', state.site.name), field('Tagline', 'site.tagline', state.site.tagline), field('Footer Copyright', 'site.footer_copyright', state.site.footerCopyright), field('Footer Address', 'site.footer_address', state.site.address, 'textarea'), field('Footer Email', 'site.footer_email', state.site.footerEmail || state.site.email, 'email'), field('Footer Phone', 'site.footer_phone', state.site.footerPhone || ''), field('Recent News Count', 'site.footer_recent_news_count', state.site.footerRecentNewsCount || 3, 'number')]),
      email: () => renderFormPanel('Email', 'Alamat pengirim dan penerima email website.', 'email', [field('Email From', 'site.email_from', state.site.email, 'email'), field('Email To', 'site.email_to', state.site.footerEmail || state.site.email, 'email')]),
      banner: () => renderFormPanel('Banner', 'Copy dan background hero halaman utama.', 'banner', [
        heroPreview(),
        `<div class="settings-banner-preview-grid">${bannerPreview('Banner Image 1', state.site.heroSlides?.[0]?.image || '', state.site.heroSlides?.[0]?.caption || '')}${bannerPreview('Banner Image 2', state.site.heroSlides?.[1]?.image || '', state.site.heroSlides?.[1]?.caption || '')}</div>`,
        field('Hero Badge', 'site.banner_badge', state.site.heroSlides?.[0]?.eyebrow || ''),
        field('Hero Title', 'site.banner_headline', state.site.heroSlides?.[0]?.title || '', 'textarea'),
        field('Hero Subtitle', 'site.banner_subtitle', state.site.heroSlides?.[0]?.caption || '', 'textarea'),
        fileField('Banner Image 1', 'site.banner_image_1', state.site.heroSlides?.[0]?.image || ''),
        field('Hero Title Alt', 'site.banner_headline_alt', state.site.heroSlides?.[1]?.title || '', 'textarea'),
        field('Hero Subtitle Alt', 'site.banner_subtitle_alt', state.site.heroSlides?.[1]?.caption || '', 'textarea'),
        fileField('Banner Image 2', 'site.banner_image_2', state.site.heroSlides?.[1]?.image || ''),
      ]),
      sidebar: () => renderFormPanel('Sidebar', 'Heading sidebar untuk halaman publik.', 'sidebar', [field('News Heading', 'site.sidebar_heading_news', state.site.sidebar?.news || ''), field('Recent Heading', 'site.sidebar_heading_recent', state.site.sidebar?.recent || ''), field('Upcoming Heading', 'site.sidebar_heading_upcoming', state.site.sidebar?.upcoming || ''), field('Past Heading', 'site.sidebar_heading_past', state.site.sidebar?.past || ''), field('Contact Heading', 'site.sidebar_heading_contact', state.site.sidebar?.contact || '')]),
      color: () => renderFormPanel('Color', 'Override warna brand utama yang disimpan di settings.', 'color', [field('Primary', 'site.color_primary', state.site.colors?.primary || '#114b9a'), field('Primary Hover', 'site.color_primary_hover', state.site.colors?.primaryHover || '#0c3572'), field('Primary Soft', 'site.color_primary_soft', state.site.colors?.primarySoft || '#eef6ff')]),
    };
    root.innerHTML = (panels[state.active] || panels.logo)();
    bindPanel(root);
  }

  function renderFormPanel(title, description, endpoint, fields) {
    return panelLayout(title, description, `
      <form class="settings-form" data-endpoint="${endpoint}">
        <div class="settings-form-grid ${endpoint === 'banner' ? 'is-banner' : ''}">
          ${fields.join('')}
        </div>
        <div class="pt-3"><button type="submit" class="btn btn-primary">Simpan ${title}</button></div>
      </form>
    `);
  }

  function bindPanel(root) {
    root.querySelectorAll('form[data-endpoint]').forEach((form) => {
      form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const endpoint = form.dataset.endpoint;
        const payload = {};
        form.querySelectorAll('[data-field]').forEach((input) => {
          payload[input.dataset.field] = input.value;
        });
        const json = await postJson(`/admin/settings/${endpoint}`, payload);
        if (json && json.ok) {
          applyPayload(payload);
          showToast('Settings berhasil disimpan.');
        }
      });
    });

    root.querySelectorAll('[data-upload-for]').forEach((input) => {
      input.addEventListener('change', async () => {
        const file = input.files && input.files[0];
        if (!file) return;
        const formData = new FormData();
        formData.append('image', file);
        const response = await fetch('/admin/settings/upload', {
          method: 'POST',
          headers: csrfHeaders(false),
          body: formData,
        });
        const json = await response.json();
        if (!response.ok || !json.data?.url) {
          showToast(json.error || 'Upload gagal.');
          return;
        }
        const target = root.querySelector(`[data-field="${input.dataset.uploadFor}"]`);
        if (target) target.value = json.data.url;
      });
    });
  }

  async function postJson(url, payload) {
    const response = await fetch(url, {
      method: 'POST',
      headers: { ...csrfHeaders(true), 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });
    const json = await response.json();
    if (!response.ok) {
      showToast(json.error || 'Gagal menyimpan settings.');
      return null;
    }
    return json;
  }

  function csrfHeaders(includeAccept) {
    const token = (API && API.getCsrfToken && API.getCsrfToken()) || document.querySelector('meta[name="csrf-token"]')?.content || '';
    return includeAccept ? { 'X-CSRF-TOKEN': token, Accept: 'application/json' } : { 'X-CSRF-TOKEN': token, Accept: 'application/json' };
  }

  function applyPayload(payload) {
    Object.entries(payload).forEach(([key, value]) => {
      switch (key) {
        case 'site.logo_url': state.site.logo = value; break;
        case 'site.favicon_url': state.site.favicon = value; break;
        case 'site.topbar_email': state.site.email = value; break;
        case 'site.topbar_phone': state.site.phone = value; break;
        case 'site.name': state.site.name = value; break;
        case 'site.tagline': state.site.tagline = value; break;
        case 'site.footer_address': state.site.address = value; break;
        case 'site.footer_email': state.site.footerEmail = value; break;
        case 'site.footer_phone': state.site.footerPhone = value; break;
        case 'site.footer_copyright': state.site.footerCopyright = value; break;
        case 'site.footer_recent_news_count': state.site.footerRecentNewsCount = value; break;
        case 'site.banner_badge': ensureHeroSlide(0).eyebrow = value; ensureHeroSlide(1).eyebrow = value; break;
        case 'site.banner_headline': ensureHeroSlide(0).title = value; break;
        case 'site.banner_headline_alt': ensureHeroSlide(1).title = value; break;
        case 'site.banner_subtitle': ensureHeroSlide(0).caption = value; break;
        case 'site.banner_subtitle_alt': ensureHeroSlide(1).caption = value; break;
        case 'site.banner_image_1': ensureHeroSlide(0).image = value; break;
        case 'site.banner_image_2': ensureHeroSlide(1).image = value; break;
        case 'site.sidebar_heading_news': state.site.sidebar.news = value; break;
        case 'site.sidebar_heading_recent': state.site.sidebar.recent = value; break;
        case 'site.sidebar_heading_upcoming': state.site.sidebar.upcoming = value; break;
        case 'site.sidebar_heading_past': state.site.sidebar.past = value; break;
        case 'site.sidebar_heading_contact': state.site.sidebar.contact = value; break;
        case 'site.color_primary': state.site.colors.primary = value; break;
        case 'site.color_primary_hover': state.site.colors.primaryHover = value; break;
        case 'site.color_primary_soft': state.site.colors.primarySoft = value; break;
      }
    });
    window.GenBISiteSettings = { ...(window.GenBISiteSettings || {}), ...state.site };
  }

  function ensureHeroSlide(index) {
    state.site.heroSlides = Array.isArray(state.site.heroSlides) ? state.site.heroSlides : [];
    state.site.heroSlides[index] = state.site.heroSlides[index] || { image: '', eyebrow: '', title: '', caption: '' };
    state.site.sidebar = state.site.sidebar || {};
    state.site.colors = state.site.colors || {};
    return state.site.heroSlides[index];
  }
})();
