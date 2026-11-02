(function(){
'use strict';
const { newsDetailUrl, renderShell } = window.GenBIApp;
const { createCustomSelect, observeFadeUp, unique } = window.GenBIUI;
const API = window.GenBIAPI;

if (document.body.dataset.ssr !== 'true') {
  renderShell('news');
}
observeFadeUp();

// Check if SSR markup exists
const list = document.querySelector('#news-list');
if (list?.dataset.ssr === 'true') {
  document.body.classList.add('page-ready');
  const filterForm = document.querySelector('#news-filter-form');
  const searchInput = document.querySelector('#news-search');
  const countEl = document.querySelector('#news-count');
  const resetBtn = document.querySelector('#news-reset-link');
  const pagination = document.querySelector('#news-pagination');
  const articles = Array.from(list.querySelectorAll('article'));
  let originalCountHtml = countEl ? countEl.innerHTML : '';

  if (searchInput) {
    // Smooth live filtering without needing any submit button
    searchInput.addEventListener('input', (event) => {
      const q = event.target.value.toLowerCase().trim();
      let visibleCount = 0;

      articles.forEach((art) => {
        const titleEl = art.querySelector('h3');
        const title = (titleEl?.textContent || '').toLowerCase();
        const matches = !q || title.includes(q);
        art.style.display = matches ? '' : 'none';
        if (matches) visibleCount++;
      });

      // Show/hide reset button smoothly
      if (resetBtn) {
        if (q !== '') {
          resetBtn.classList.remove('hidden');
        } else {
          // If input is empty and URL has no active query, hide reset button
          const urlParams = new URLSearchParams(window.location.search);
          if (!urlParams.get('q') && !urlParams.get('category')) {
            resetBtn.classList.add('hidden');
          }
        }
      }

      // Smooth count feedback
      if (countEl) {
        if (q === '') {
          countEl.innerHTML = originalCountHtml;
        } else if (visibleCount > 0) {
          countEl.innerHTML = `<div>Menampilkan <strong>${visibleCount}</strong> berita untuk pencarian judul "<strong>${escapeHtml(event.target.value)}</strong>".</div>`;
        } else {
          countEl.innerHTML = `<div>Tidak ada berita yang cocok dengan judul "<strong>${escapeHtml(event.target.value)}</strong>".</div>`;
        }
      }

      // Dynamic empty state if all filtered out
      let emptyMsg = list.querySelector('#news-live-empty');
      if (visibleCount === 0 && articles.length > 0) {
        if (!emptyMsg) {
          emptyMsg = document.createElement('div');
          emptyMsg.id = 'news-live-empty';
          emptyMsg.className = 'rounded-2xl border border-neutral-900/10 bg-white p-8 text-center text-sm text-neutral-600';
          emptyMsg.innerHTML = '<p class="font-medium text-neutral-800">Belum ada data berita yang cocok.</p><p class="mt-1 text-xs text-neutral-500">Silakan gunakan kata kunci judul lain atau klik Reset Filter.</p>';
          list.appendChild(emptyMsg);
        }
        emptyMsg.style.display = '';
      } else if (emptyMsg) {
        emptyMsg.style.display = 'none';
      }

      // Hide server-side pagination while live search is active
      if (pagination) {
        pagination.style.display = q ? 'none' : '';
      }
    });

    searchInput.addEventListener('keydown', (event) => {
      if (event.key === 'Enter') {
        event.preventDefault();
        if (filterForm) filterForm.submit();
      }
    });
  }

  function escapeHtml(str) {
    return str.replace(/[&<>'"]/g, tag => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      "'": '&#39;',
      '"': '&quot;'
    }[tag] || tag));
  }

  return;
}

let state = { query: '', category: 'Semua', page: 1, perPage: 12 };
let news = [];
const search = document.querySelector('#news-search');
const count = document.querySelector('#news-count');
const pagination = document.querySelector('#news-pagination');

init();

search.addEventListener('input', (event) => {
  state.query = event.target.value;
  state.page = 1;
  renderNews();
});

async function init() {
  count.textContent = 'Memuat berita...';
  news = await API.getNewsList();
  createCustomSelect(document.querySelector('#news-category'), {
    label: 'Kategori',
    options: unique(news.map((item) => item.category)),
    onChange: (value) => { state.category = value; state.page = 1; renderNews(); }
  });
  renderNews();
}

function renderNews() {
  const query = state.query.toLowerCase().trim();
  const filtered = news.filter((item) => {
    const haystack = (item.title || '').toLowerCase();
    return (!query || haystack.includes(query)) && (state.category === 'Semua' || item.category === state.category);
  });
  const totalPages = Math.max(1, Math.ceil(filtered.length / state.perPage));
  state.page = Math.min(state.page, totalPages);
  const start = (state.page - 1) * state.perPage;
  const paged = filtered.slice(start, start + state.perPage);
  count.textContent = filtered.length
    ? `Menampilkan ${start + 1}-${Math.min(start + state.perPage, filtered.length)} dari ${filtered.length} berita.`
    : 'Tidak ada berita yang cocok.';
  list.innerHTML = paged.length ? paged.map((item, index) => `
    <a data-transition href="${newsDetailUrl(item)}" class="article-link ${index === 0 ? 'pt-0 border-t-0' : ''}">
      <div class="grid gap-5 md:grid-cols-[170px_1fr] md:items-start">
        <div class="aspect-[4/3] overflow-hidden rounded-2xl bg-blue-50">
          <img src="${item.image}" alt="${item.title}" class="h-full w-full object-cover" loading="lazy" onerror="this.src='https://genbijambi.com/public/uploads/slider-1.png'" />
        </div>
        <div>
          <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-neutral-500">
            <span class="text-blue-800">${item.category}</span><span>${item.date}</span><span>${item.readTime}</span>
          </div>
          <h3 class="serif mt-3 text-3xl font-semibold leading-tight tracking-tight text-neutral-950">${item.title}</h3>
          <p class="mt-4 text-base leading-7 text-neutral-600">${item.excerpt}</p>
          <span class="btn btn-secondary mt-5 w-fit">Detail</span>
        </div>
      </div>
    </a>
  `).join('') : `<div class="rounded-2xl border border-neutral-900/10 bg-white p-8 text-center text-sm text-neutral-600">Belum ada data berita yang cocok.</div>`;
  renderPagination(totalPages);
}

function renderPagination(totalPages) {
  if (!pagination) return;
  if (totalPages <= 1) {
    pagination.innerHTML = '';
    return;
  }
  const pages = Array.from({ length: totalPages }, (_, index) => index + 1);
  pagination.innerHTML = `
    <button class="pager-button" type="button" data-page="${Math.max(1, state.page - 1)}" ${state.page === 1 ? 'disabled' : ''}>Sebelumnya</button>
    ${pages.map((page) => `<button class="pager-button ${page === state.page ? 'is-active' : ''}" type="button" data-page="${page}">${page}</button>`).join('')}
    <button class="pager-button" type="button" data-page="${Math.min(totalPages, state.page + 1)}" ${state.page === totalPages ? 'disabled' : ''}>Berikutnya</button>
  `;
  pagination.querySelectorAll('[data-page]').forEach((button) => {
    button.addEventListener('click', () => {
      state.page = Number(button.dataset.page) || 1;
      renderNews();
      list.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });
}

})();
