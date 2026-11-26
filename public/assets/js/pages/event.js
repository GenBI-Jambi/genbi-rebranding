(function(){
'use strict';
const { renderShell } = window.GenBIApp;
const { createModalController, observeFadeUp, safeImage } = window.GenBIUI;
const API = window.GenBIAPI;

renderShell('event');

const ssrList = document.querySelector('#event-list[data-ssr="true"]');
const ssrDetail = document.body.dataset.ssr === 'true' && !document.querySelector('#event-list');

function escapeHtml(str) {
  return String(str ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

if (ssrDetail) {
  document.body.classList.add('page-ready');
  observeFadeUp();
} else if (ssrList) {
  // SSR path: content rendered server-side with smooth live search & modal integration
  const eventModal = createModalController(document.querySelector('#event-modal'), {
    onClose: function() {
      document.body.classList.remove('modal-lock');
      document.documentElement.classList.remove('modal-lock');
      const btt = document.querySelector('#back-to-top');
      if (btt) {
        btt.style.removeProperty('display');
      }
    }
  });

  function bindDetailButtons(container) {
    if (!container) return;
    container.querySelectorAll('.open-event').forEach((button) => {
      button.addEventListener('click', (event) => {
        event.preventDefault();
        const id = Number(button.dataset.id);
        const slug = button.dataset.slug || '';
        if (id > 0) openEventModal({ id, slug }, eventModal);
      });
    });
  }

  bindDetailButtons(ssrList);

  const searchInput = document.querySelector('#event-search');
  const clearBtn = document.querySelector('#event-search-clear');
  const filterForm = document.querySelector('#event-filter-form');
  const countEl = document.querySelector('#event-count');
  const listEl = document.querySelector('#event-list');
  const paginationEl = document.querySelector('#event-pagination');

  let debounceTimer = null;
  let activeAbortController = null;
  let currentSearchQuery = searchInput ? searchInput.value.trim() : '';

  function renderCardHtml(item) {
    const safeImg = window.GenBIUI?.safeImage || ((v) => v || 'https://genbijambi.com/public/uploads/slider-1.png');
    const dateStr = escapeHtml(item.start_date || '') + (item.end_date && item.end_date !== item.start_date ? ' – ' + escapeHtml(item.end_date) : '');
    const slugOrId = encodeURIComponent(item.slug || item.id || '');
    const status = escapeHtml(item.status || 'Event');
    const isUpcoming = item.status === 'Upcoming' ? 'upcoming' : '';
    const imageSrc = item.image || item.photo || item.banner || 'https://genbijambi.com/public/uploads/slider-1.png';

    return `
      <article class="event-card">
        <div class="event-card-image">
          <img src="${safeImg(imageSrc)}" alt="${escapeHtml(item.title || '')}" loading="lazy" onerror="this.src='https://genbijambi.com/public/uploads/slider-1.png'" />
          <span class="event-card-badge ${isUpcoming}">${status}</span>
        </div>
        <div class="event-card-body">
          <div class="event-date-row">
            <svg class="h-3.5 w-3.5 text-[#114b9a] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
              <line x1="16" x2="16" y1="2" y2="6" />
              <line x1="8" x2="8" y1="2" y2="6" />
              <line x1="3" x2="21" y1="10" y2="10" />
            </svg>
            <span>${dateStr}</span>
          </div>
          <h3 class="event-card-title">${escapeHtml(item.title || '')}</h3>
          <p class="event-card-excerpt">${escapeHtml(item.excerpt || '')}</p>
          <div class="event-card-location">
            ${item.location ? `
              <svg class="h-3.5 w-3.5 flex-shrink-0 text-[#114b9a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
              </svg>
              <span class="truncate">${escapeHtml(item.location)}</span>
            ` : ''}
          </div>
        </div>
        <div class="event-card-footer">
          <a href="/event/${slugOrId}" class="btn-event-detail open-event" data-id="${Number(item.id)}" data-slug="${escapeHtml(item.slug || '')}" aria-label="Lihat detail event ${escapeHtml(item.title || '')}">
            <span>Lihat Detail Agenda</span>
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
          </a>
        </div>
      </article>
    `;
  }

  function renderSsrPagination(meta, query) {
    if (!paginationEl) return;
    const totalPages = Number(meta.total_pages || meta.totalPages || 1);
    const currentPage = Number(meta.page || 1);

    if (totalPages <= 1) {
      paginationEl.innerHTML = '';
      return;
    }

    let html = '';
    if (currentPage > 1) {
      html += `<button type="button" class="pager-button" data-page="${currentPage - 1}">Sebelumnya</button>`;
    } else {
      html += `<span class="pager-button" aria-disabled="true">Sebelumnya</span>`;
    }

    for (let i = 1; i <= totalPages; i++) {
      if (i === currentPage) {
        html += `<span class="pager-button is-active" aria-current="page">${i}</span>`;
      } else {
        html += `<button type="button" class="pager-button" data-page="${i}">${i}</button>`;
      }
    }

    if (currentPage < totalPages) {
      html += `<button type="button" class="pager-button" data-page="${currentPage + 1}">Berikutnya</button>`;
    } else {
      html += `<span class="pager-button" aria-disabled="true">Berikutnya</span>`;
    }

    paginationEl.innerHTML = html;
    paginationEl.querySelectorAll('button[data-page]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const p = Number(btn.dataset.page) || 1;
        performLiveSearch(currentSearchQuery, p);
        if (listEl) {
          listEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });
  }

  async function performLiveSearch(query, page = 1) {
    currentSearchQuery = query;

    if (clearBtn) {
      clearBtn.style.display = query !== '' ? 'inline-flex' : 'none';
    }

    const url = new URL(window.location.href);
    if (query) {
      url.searchParams.set('q', query);
    } else {
      url.searchParams.delete('q');
    }
    if (page > 1) {
      url.searchParams.set('page', String(page));
    } else {
      url.searchParams.delete('page');
    }
    window.history.replaceState(null, '', url.pathname + (url.search ? url.search : ''));

    if (activeAbortController) {
      activeAbortController.abort();
    }
    activeAbortController = new AbortController();

    if (listEl) {
      listEl.style.opacity = '0.55';
      listEl.style.transition = 'opacity 150ms ease';
    }

    try {
      const fetchParams = new URLSearchParams();
      if (query) fetchParams.set('q', query);
      if (page > 1) fetchParams.set('page', String(page));

      const reqUrl = `/event` + (fetchParams.toString() ? `?${fetchParams.toString()}` : '');
      const response = await fetch(reqUrl, {
        headers: { 'Accept': 'application/json' },
        signal: activeAbortController.signal
      });

      if (!response.ok) throw new Error('Search failed');
      const json = await response.json();
      const items = json.data || [];
      const meta = json.meta || { total: items.length, page: 1, per_page: 9, total_pages: 1 };

      if (countEl) {
        if (meta.total > 0) {
          const start = (meta.page - 1) * meta.per_page + 1;
          const end = Math.min(meta.page * meta.per_page, meta.total);
          countEl.innerHTML = `Menampilkan <span class="font-semibold text-neutral-900">${start}–${end}</span> dari <span class="font-semibold text-neutral-900">${meta.total}</span> event.`;
        } else if (query) {
          countEl.innerHTML = `Tidak ada event yang cocok dengan "<span class="font-semibold text-neutral-900">${escapeHtml(query)}</span>".`;
        } else {
          countEl.textContent = 'Belum ada event yang tersedia.';
        }
      }

      if (listEl) {
        if (items.length > 0) {
          listEl.innerHTML = items.map((item) => renderCardHtml(item)).join('');
          bindDetailButtons(listEl);
        } else {
          listEl.innerHTML = '<div class="rounded-2xl border border-neutral-900/10 bg-white p-8 text-center text-sm text-neutral-600 col-span-full">Belum ada event yang cocok.</div>';
        }
      }

      renderSsrPagination(meta, query);
    } catch (err) {
      if (err.name === 'AbortError') return;
      console.warn('Gagal memuat event live:', err);
    } finally {
      if (listEl) {
        listEl.style.opacity = '1';
      }
    }
  }

  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      clearTimeout(debounceTimer);
      const val = e.target.value;
      debounceTimer = setTimeout(() => {
        performLiveSearch(val.trim(), 1);
      }, 250);
    });

    searchInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        clearTimeout(debounceTimer);
        performLiveSearch(searchInput.value.trim(), 1);
      }
    });
  }

  if (clearBtn) {
    clearBtn.addEventListener('click', (e) => {
      e.preventDefault();
      if (searchInput) {
        searchInput.value = '';
        searchInput.focus();
      }
      clearTimeout(debounceTimer);
      performLiveSearch('', 1);
    });
  }

  if (filterForm) {
    filterForm.addEventListener('submit', (e) => {
      e.preventDefault();
      clearTimeout(debounceTimer);
      performLiveSearch(searchInput ? searchInput.value.trim() : '', 1);
    });
  }

  // Bind initial SSR pagination buttons
  if (paginationEl) {
    paginationEl.querySelectorAll('a.pager-button').forEach((link) => {
      link.addEventListener('click', (e) => {
        try {
          const hrefUrl = new URL(link.href, window.location.origin);
          const p = Number(hrefUrl.searchParams.get('page')) || 1;
          const q = hrefUrl.searchParams.get('q') ?? currentSearchQuery;
          e.preventDefault();
          performLiveSearch(q, p);
          if (listEl) {
            listEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        } catch (_) {}
      });
    });
  }
} else {
  // CSR fallback path: fetch data and render client-side (for fallbacks/event.html)
  observeFadeUp();

  let events = [];
  let state = { query: '', page: 1, perPage: 9 };

  const search = document.querySelector('#event-search');
  const count = document.querySelector('#event-count');
  const list = document.querySelector('#event-list');
  const pagination = document.querySelector('#event-pagination');
  const eventModal = createModalController(document.querySelector('#event-modal'), {
    onClose: function() {
      document.body.classList.remove('modal-lock');
      document.documentElement.classList.remove('modal-lock');
      const btt = document.querySelector('#back-to-top');
      if (btt) {
        btt.style.removeProperty('display');
      }
    }
  });

  init();

  async function init() {
    if (count) count.textContent = 'Memuat data event...';
    events = await API.getEventList();

    if (search) {
      search.addEventListener('input', (event) => {
        state.query = event.target.value;
        state.page = 1;
        renderEvents();
      });
    }

    document.querySelector('#event-search-clear')?.addEventListener('click', () => {
      if (search) search.value = '';
      state.query = '';
      state.page = 1;
      renderEvents();
    });

    renderEvents();
  }

  function getFiltered() {
    const query = state.query.toLowerCase().trim();
    return events.filter((item) => {
      const haystack = `${item.title} ${item.excerpt} ${item.location} ${item.status}`.toLowerCase();
      return !query || haystack.includes(query);
    });
  }

  function renderEvents() {
    const filtered = getFiltered();
    const totalPages = Math.max(1, Math.ceil(filtered.length / state.perPage));
    state.page = Math.min(state.page, totalPages);
    const start = (state.page - 1) * state.perPage;
    const paged = filtered.slice(start, start + state.perPage);

    if (count) {
      count.textContent = filtered.length
        ? `Menampilkan ${start + 1}-${Math.min(start + state.perPage, filtered.length)} dari ${filtered.length} event.`
        : 'Tidak ada event yang cocok.';
    }

    if (list) {
      list.innerHTML = paged.length ? paged.map((item) => {
        const dateStr = escapeHtml(item.start_date || '') + (item.end_date && item.end_date !== item.start_date ? ' – ' + escapeHtml(item.end_date) : '');
        return `
        <article class="event-card">
          <div class="event-card-image">
            <img src="${safeImage(item.image)}" alt="${escapeHtml(item.title)}" loading="lazy" onerror="this.src='https://genbijambi.com/public/uploads/slider-1.png'" />
            <span class="event-card-badge ${item.status === 'Upcoming' ? 'upcoming' : ''}">${escapeHtml(item.status)}</span>
          </div>
          <div class="event-card-body">
            <div class="event-date-row">
              <svg class="h-3.5 w-3.5 text-[#114b9a] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                <line x1="16" x2="16" y1="2" y2="6" />
                <line x1="8" x2="8" y1="2" y2="6" />
                <line x1="3" x2="21" y1="10" y2="10" />
              </svg>
              <span>${dateStr}</span>
            </div>
            <h3 class="event-card-title">${escapeHtml(item.title)}</h3>
            <p class="event-card-excerpt">${escapeHtml(item.excerpt)}</p>
            <div class="event-card-location">
              ${item.location ? `
                <svg class="h-3.5 w-3.5 flex-shrink-0 text-[#114b9a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                <span class="truncate">${escapeHtml(item.location)}</span>
              ` : ''}
            </div>
          </div>
          <div class="event-card-footer">
            <button class="btn-event-detail open-event" data-id="${item.id}" data-slug="${item.slug || ''}" aria-label="Lihat detail event ${escapeHtml(item.title)}">
              <span>Lihat Detail Agenda</span>
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0-7.5 7.5M21 12H3" />
              </svg>
            </button>
          </div>
        </article>
      `;
      }).join('') : `<div class="rounded-2xl border border-neutral-900/10 bg-white p-8 text-center text-sm text-neutral-600">Belum ada event yang cocok.</div>`;

      list.querySelectorAll('.open-event').forEach((button) => {
        button.addEventListener('click', () => openEventCSR({ id: Number(button.dataset.id), slug: button.dataset.slug || '' }, eventModal));
      });
    }
    renderPagination(totalPages);
  }

  function renderPagination(totalPages) {
    if (!pagination) return;
    if (totalPages <= 1) { pagination.innerHTML = ''; return; }
    const pages = Array.from({ length: totalPages }, (_, i) => i + 1);
    pagination.innerHTML = `
      <button class="pager-button" type="button" data-page="${Math.max(1, state.page - 1)}" ${state.page === 1 ? 'disabled' : ''}>Sebelumnya</button>
      ${pages.map((p) => `<button class="pager-button ${p === state.page ? 'is-active' : ''}" type="button" data-page="${p}">${p}</button>`).join('')}
      <button class="pager-button" type="button" data-page="${Math.min(totalPages, state.page + 1)}" ${state.page === totalPages ? 'disabled' : ''}>Berikutnya</button>
    `;
    pagination.querySelectorAll('[data-page]').forEach((button) => {
      button.addEventListener('click', () => {
        state.page = Number(button.dataset.page) || 1;
        renderEvents();
        if (list) list.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });
  }

  function openEventCSR(target, modal) {
    const item = events.find((e) => e.id === target.id || e.slug === target.slug);
    if (!item) return;
    renderEventModal(item, modal);
  }
}

function eventDetailHref(target) {
  return `/event/${encodeURIComponent(target.slug || target.id || '')}`;
}

async function openEventModal(target, modal) {
  // Hide back-to-top immediately
  const btt = document.querySelector('#back-to-top');
  if (btt) {
    btt.style.setProperty('display', 'none', 'important');
  }

  try {
    const response = await fetch(eventDetailHref(target), { headers: { 'Accept': 'application/json' } });
    if (!response.ok) {
      window.location.href = eventDetailHref(target);
      return;
    }
    const json = await response.json();
    const item = json.data;
    if (!item) {
      window.location.href = eventDetailHref(target);
      return;
    }
    renderEventModal(item, modal);
  } catch (e) {
    window.location.href = eventDetailHref(target);
  }
}

function renderEventModal(item, modal) {
  const safeImg = window.GenBIUI?.safeImage || ((v) => v || 'https://genbijambi.com/public/uploads/slider-1.png');
  const detailUrl = `/event/${encodeURIComponent(item.slug || item.id || '')}`;
  const dateStr = escapeHtml(item.start_date || '') + (item.end_date && item.end_date !== item.start_date ? ' \u2013 ' + escapeHtml(item.end_date) : '');
  const excerptText = escapeHtml(item.excerpt || '');

  // Hide back-to-top
  const btt = document.querySelector('#back-to-top');
  if (btt) {
    btt.style.setProperty('display', 'none', 'important');
  }

  modal.open({ content: `
    <div class="public-modal-panel event-detail-panel modal-panel is-open" role="dialog" aria-modal="true" aria-labelledby="event-modal-title">
      <button type="button" class="event-detail-close modal-close" data-modal-close aria-label="Tutup detail event" title="Tutup">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <figure class="event-detail-image">
        <img src="${safeImg(item.banner || item.image)}" alt="${escapeHtml(item.title)}" onerror="this.src='https://genbijambi.com/public/uploads/slider-1.png'" />
        <span class="event-detail-badge ${item.status === 'Upcoming' ? 'upcoming' : ''}">${escapeHtml(item.status || 'Event')}</span>
      </figure>

      <div class="event-detail-content">
        <div>
          <div class="flex flex-wrap items-center gap-2 pr-10">
            <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-[#114b9a] border border-blue-200/60">
              ${escapeHtml(item.status || 'Event')}
            </span>
          </div>
          <h2 id="event-modal-title" class="serif mt-3 text-2xl font-bold leading-tight tracking-tight text-neutral-950 md:text-3xl">
            ${escapeHtml(item.title)}
          </h2>
        </div>

        <div class="event-info-cards" style="margin-top:1rem;">
          <div class="event-info-card">
            <div class="event-info-icon">
              <svg class="h-4 w-4 text-[#114b9a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                <line x1="16" x2="16" y1="2" y2="6" />
                <line x1="8" x2="8" y1="2" y2="6" />
                <line x1="3" x2="21" y1="10" y2="10" />
              </svg>
            </div>
            <div>
              <span class="event-info-label">Tanggal Kegiatan</span>
              <span class="event-info-val">${dateStr}</span>
            </div>
          </div>
          ${item.location ? `
          <div class="event-info-card">
            <div class="event-info-icon">
              <svg class="h-4 w-4 text-[#114b9a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
              </svg>
            </div>
            <div>
              <span class="event-info-label">Lokasi</span>
              <span class="event-info-val">${escapeHtml(item.location)}</span>
            </div>
          </div>
          ` : ''}
        </div>

        ${excerptText ? `
        <div class="event-modal-body" style="margin-top:1rem;">
          <div class="event-modal-body-header">Deskripsi Kegiatan</div>
          <p class="event-modal-body-text">${excerptText}</p>
        </div>
        ` : ''}

        <div class="event-modal-footer" style="margin-top:auto;">
          <a data-transition href="${detailUrl}" class="btn btn-primary inline-flex items-center justify-center gap-2 w-full sm:w-auto" aria-label="Lihat detail lengkap ${escapeHtml(item.title)}">
            <span>Baca Detail Lengkap</span>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </a>
        </div>
      </div>
    </div>
  ` });
}

})();
