(function () {
  'use strict';

  const { renderShell } = window.GenBIApp || {};
  const { createModalController, observeFadeUp } = window.GenBIUI || {};
  const API = window.GenBIAPI || {};
  const { news } = window.GenBIData || {};

  if (typeof renderShell === 'function') {
    renderShell('prestasi');
  }

  ensurePrestasiModal();
  const modalEl = document.querySelector('#prestasi-modal');
  let activeLoadingId = null;

  function closePrestasiModal() {
    activeLoadingId = null;
    if (prestasiModal) {
      prestasiModal.close();
    }
    if (modalEl) {
      modalEl.classList.add('hidden');
      modalEl.innerHTML = '';
    }
    document.body.classList.remove('modal-lock');
    document.documentElement.classList.remove('modal-lock');
    const btt = document.querySelector('#back-to-top');
    if (btt) {
      btt.style.removeProperty('display');
    }
  }

  const prestasiModal = (typeof createModalController === 'function' && modalEl)
    ? createModalController(modalEl, {
        onClose: () => {
          document.body.classList.remove('modal-lock');
          document.documentElement.classList.remove('modal-lock');
          const btt = document.querySelector('#back-to-top');
          if (btt) {
            btt.style.removeProperty('display');
          }
          activeLoadingId = null;
        }
      })
    : null;

  let prestasi = Array.isArray(window.prestasiData) ? [...window.prestasiData] : [];
  let currentLayout = 'grid';

  function escapeHtml(str = '') {
    return String(str ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function ensurePrestasiModal() {
    let modal = document.querySelector('#prestasi-modal');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'prestasi-modal';
      modal.className = 'public-fixed-modal hidden';
      document.body.appendChild(modal);
    }
    modal.addEventListener('click', (e) => {
      if (e.target === modal || e.target.closest('.prestasi-detail-close, [data-modal-close], .modal-close')) {
        e.preventDefault();
        closePrestasiModal();
      }
    });
  }

  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const modal = document.querySelector('#prestasi-modal');
      if (modal && !modal.classList.contains('hidden')) {
        closePrestasiModal();
      }
    }
  });

  function resolvePrestasiDetailData(item = {}) {
    const title = String(item.title || 'Prestasi Anggota GenBI').trim();
    const name = String(item.name || item.member_name || '-').trim();
    let campus = String(item.campus || '').trim();
    let prodi = '';
    let category = String(item.category || item.kategori || 'Prestasi').trim();
    const year = String(item.year || item.tahun || '').trim();
    let rawInstitution = String(item.institution || item.penyelenggara || '').trim();
    const rawDesc = String(item.description || item.deskripsi || '').trim();

    let namaAcara = '';
    let penyelenggara = '';
    let capaian = '';
    let extraRows = [];
    let narrativeText = '';

    if (rawDesc.includes('|')) {
      const parts = rawDesc.split('|').map((p) => p.trim()).filter(Boolean);
      parts.forEach((part) => {
        const colonIdx = part.indexOf(':');
        if (colonIdx > -1) {
          const label = part.substring(0, colonIdx).trim();
          const labelLower = label.toLowerCase();
          const val = part.substring(colonIdx + 1).trim();

          if (labelLower === 'program studi' || labelLower === 'prodi' || labelLower === 'jurusan') {
            prodi = val;
          } else if (labelLower === 'kampus' || labelLower === 'asal kampus' || labelLower === 'universitas' || labelLower === 'komisariat') {
            if (!campus || campus === '-') campus = val;
          } else if (labelLower === 'acara' || labelLower === 'nama acara' || labelLower === 'kegiatan' || labelLower === 'nama kegiatan') {
            namaAcara = val;
          } else if (labelLower === 'penyelenggara' || labelLower === 'diadakan oleh' || labelLower === 'institusi') {
            penyelenggara = val;
          } else if (labelLower === 'prestasi' || labelLower === 'capaian' || labelLower === 'juara') {
            capaian = val;
          } else {
            extraRows.push({ label, val });
          }
        } else {
          extraRows.push({ label: '', val: part });
        }
      });
    } else {
      narrativeText = rawDesc;
    }

    // Fallback if namaAcara is empty
    if (!namaAcara && rawInstitution) {
      namaAcara = rawInstitution;
    }

    // Split "yang diadakan oleh" or "diadakan oleh" if present in namaAcara
    if (namaAcara) {
      const splitMatch = namaAcara.match(/\s+(?:yang\s+)?diadakan\s+oleh\s+/i);
      if (splitMatch && splitMatch.index !== undefined) {
        const ev = namaAcara.substring(0, splitMatch.index).trim();
        const org = namaAcara.substring(splitMatch.index + splitMatch[0].length).trim();
        namaAcara = ev;
        if (!penyelenggara) {
          penyelenggara = org;
        }
      }
    }

    if (!penyelenggara && rawInstitution && rawInstitution !== namaAcara) {
      penyelenggara = rawInstitution;
    }

    return {
      title,
      name,
      campus,
      prodi,
      category,
      year,
      namaAcara,
      penyelenggara,
      capaian,
      extraRows,
      narrativeText,
    };
  }

  function getFallbackImage(index = 0) {
    if (Array.isArray(news) && news.length > 0) {
      return news[Math.abs(index) % news.length]?.image || 'https://genbijambi.com/public/uploads/slider-1.png';
    }
    return 'https://genbijambi.com/public/uploads/slider-1.png';
  }

  function resolveItemImage(item = {}, index = 0) {
    const raw = item.image || item.photo || (Array.isArray(item.images) && item.images[0]) || '';
    if (raw && typeof raw === 'string' && raw.trim() !== '') {
      return raw;
    }
    return getFallbackImage(index);
  }

  function renderLoadingModal() {
    return `
      <div class="public-modal-panel prestasi-loading-panel modal-panel is-open" role="dialog" aria-modal="true" aria-label="Memuat ringkasan prestasi">
        <div class="relative flex items-center justify-center my-3">
          <div class="w-14 h-14 rounded-full border-4 border-blue-100 animate-pulse"></div>
          <svg class="w-14 h-14 absolute animate-spin text-[#114b9a]" fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
            <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <div class="w-2.5 h-2.5 rounded-full bg-[#114b9a] absolute shadow-sm"></div>
        </div>
        <h4 class="mt-4 text-base font-semibold text-neutral-900 tracking-tight font-sans">Memuat Ringkasan Prestasi</h4>
        <p class="mt-1.5 text-xs text-neutral-500 font-normal">Mohon tunggu sebentar...</p>
      </div>
    `;
  }

  function renderDetailModal(item = {}, index = 0) {
    const fallbackImage = getFallbackImage(index);
    const image = resolveItemImage(item, index);
    const data = resolvePrestasiDetailData(item);
    const slug = item.slug || item.id || '';
    const detailUrl = `/prestasi/${encodeURIComponent(slug)}`;

    // Clean category badge so it's not a duplicated huge sentence
    const displayCategory = (data.category && data.category.toLowerCase() !== data.title.toLowerCase() && data.category.length <= 28)
      ? data.category
      : 'GenBI Prestasi';

    return `
      <div class="public-modal-panel prestasi-detail-panel modal-panel is-open" role="dialog" aria-modal="true" aria-labelledby="prestasi-modal-title">
        <button type="button" class="prestasi-detail-close modal-close" data-modal-close aria-label="Tutup ringkasan prestasi" title="Tutup">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <figure class="prestasi-detail-image">
          <img src="${image}" alt="Dokumentasi ${escapeHtml(data.title)}" onerror="this.src='${fallbackImage}'" />
        </figure>

        <div class="prestasi-detail-copy">
          <div class="flex flex-wrap items-center gap-2 pr-10">
            <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-[#114b9a] border border-blue-200/60">
              ${escapeHtml(displayCategory)}
            </span>
            ${data.year ? `<span class="inline-flex items-center rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-neutral-600">Tahun ${escapeHtml(data.year)}</span>` : ''}
          </div>

          <h2 id="prestasi-modal-title" class="serif mt-3 text-2xl font-bold leading-tight tracking-tight text-neutral-950 md:text-3xl">
            ${escapeHtml(data.title)}
          </h2>

          <!-- 1. DATA PENERIMA PRESTASI (DATA PRIBADI) -->
          <div class="prestasi-card-block">
            <div class="prestasi-card-tag">Data Penerima Prestasi</div>
            <div class="prestasi-person-row">
              <div class="prestasi-person-avatar">
                <svg class="h-4 w-4 text-[#114b9a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
              </div>
              <div class="min-w-0 flex-1">
                <h3 class="prestasi-person-name">${escapeHtml(data.name)}</h3>
              </div>
            </div>
            <div class="prestasi-data-grid">
              ${data.prodi ? `
                <div class="prestasi-data-item">
                  <span class="prestasi-data-label">Program Studi</span>
                  <span class="prestasi-data-val">${escapeHtml(data.prodi)}</span>
                </div>
              ` : ''}
              ${data.campus ? `
                <div class="prestasi-data-item">
                  <span class="prestasi-data-label">Asal Kampus / Komisariat</span>
                  <span class="prestasi-data-val">${escapeHtml(data.campus)}</span>
                </div>
              ` : ''}
            </div>
          </div>

          <!-- 2. INFORMASI KEGIATAN & PERLOMBAAN -->
          <div class="prestasi-card-block">
            <div class="prestasi-card-tag">Informasi Kegiatan & Perlombaan</div>
            <div class="prestasi-data-grid">
              ${data.namaAcara ? `
                <div class="prestasi-data-item">
                  <span class="prestasi-data-label">Nama Kegiatan / Acara</span>
                  <span class="prestasi-data-val font-semibold text-neutral-900">${escapeHtml(data.namaAcara)}</span>
                </div>
              ` : ''}
              ${data.penyelenggara ? `
                <div class="prestasi-data-item">
                  <span class="prestasi-data-label">Penyelenggara / Diadakan Oleh</span>
                  <span class="prestasi-data-val">${escapeHtml(data.penyelenggara)}</span>
                </div>
              ` : ''}
              ${data.capaian && data.capaian.toLowerCase() !== data.title.toLowerCase() ? `
                <div class="prestasi-data-item">
                  <span class="prestasi-data-label">Capaian Prestasi</span>
                  <span class="prestasi-data-val">${escapeHtml(data.capaian)}</span>
                </div>
              ` : ''}
              ${data.extraRows.map(row => `
                <div class="prestasi-data-item">
                  ${row.label ? `<span class="prestasi-data-label">${escapeHtml(row.label)}</span>` : ''}
                  <span class="prestasi-data-val">${escapeHtml(row.val)}</span>
                </div>
              `).join('')}
              ${data.narrativeText ? `
                <div class="prestasi-data-item">
                  <span class="prestasi-data-val leading-relaxed text-neutral-600">${escapeHtml(data.narrativeText)}</span>
                </div>
              ` : ''}
            </div>
          </div>

          <div class="prestasi-detail-footer">
            <a data-transition href="${detailUrl}" class="btn btn-primary inline-flex items-center justify-center gap-2 w-full sm:w-auto" aria-label="Baca detail lengkap mengenai ${escapeHtml(data.title)}">
              <span>Baca Detail Lengkap</span>
              <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    `;
  }

  async function handlePrestasiCardClick(event, card) {
    if (event) {
      event.preventDefault();
      event.stopPropagation();
    }

    const id = card.dataset.id || '';
    const slug = card.dataset.slug || id;
    const index = Number(card.dataset.index || 0);

    // Find local item data if available
    let localItem = (prestasi || []).find((p) => String(p.id) === String(id) || String(p.slug) === String(slug));
    if (!localItem && card.dataset.title) {
      localItem = {
        id,
        slug,
        title: card.dataset.title || '',
        name: card.dataset.name || '',
        campus: card.dataset.campus || '',
        category: card.dataset.category || '',
        year: card.dataset.year || '',
        description: card.dataset.description || '',
        image: card.dataset.image || '',
        institution: card.dataset.institution || '',
      };
    }

    activeLoadingId = id;

    const btt = document.querySelector('#back-to-top');
    if (btt) {
      btt.style.setProperty('display', 'none', 'important');
    }

    // 1. Buka modal dengan loading spinner elegan
    if (prestasiModal) {
      prestasiModal.open({
        content: renderLoadingModal(),
        trigger: card,
      });
    }

    // 2. Beri durasi putaran loading yang halus (260ms) agar terasa natural
    await new Promise((resolve) => setTimeout(resolve, 260));

    // Jika modal sudah ditutup oleh pengguna sebelum selesai, batalkan render
    if (activeLoadingId !== id) return;

    const finalItem = localItem || { title: 'Ringkasan Prestasi', id, slug };

    // 3. Tampilkan ringkasan modal prestasi dengan tombol ke halaman detail
    if (prestasiModal) {
      prestasiModal.open({
        content: renderDetailModal(finalItem, index),
        trigger: card,
      });

      if (modalEl) {
        modalEl.querySelectorAll('.prestasi-detail-close, [data-modal-close]').forEach((btn) => {
          btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            closePrestasiModal();
          });
        });
      }

      const modalImage = document.querySelector('#prestasi-modal .prestasi-detail-image img');
      if (modalImage) {
        modalImage.addEventListener('error', () => {
          modalImage.src = getFallbackImage(index);
        });
      }
    }
  }

  function bindCardClicks(container) {
    if (!container) return;
    container.querySelectorAll('[data-id]').forEach((card) => {
      card.addEventListener('click', (e) => handlePrestasiCardClick(e, card));
      card.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          handlePrestasiCardClick(e, card);
        }
      });
    });
  }

  function bindLayoutToggles() {
    document.querySelectorAll('[data-prestasi-layout]').forEach((button) => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const layout = button.dataset.prestasiLayout || 'grid';
        currentLayout = layout;

        document.querySelectorAll('[data-prestasi-layout]').forEach((item) => {
          item.classList.toggle('is-active', item.dataset.prestasiLayout === layout);
        });

        const listEl = document.querySelector('#prestasi-list');
        if (listEl) {
          listEl.className = layout === 'grid' ? 'prestasi-grid' : 'soft-card overflow-hidden prestasi-list';
        }
      });
    });
  }

  // Check if SSR markup exists
  const ssrList = document.querySelector('#prestasi-list[data-ssr="true"]');
  if (ssrList) {
    document.body.classList.add('page-ready');
    if (typeof observeFadeUp === 'function') {
      observeFadeUp();
    }
    bindLayoutToggles();
    bindCardClicks(ssrList);
    return;
  }

  // Client-rendered fallback mode
  const clientList = document.querySelector('#prestasi-list');
  if (clientList) {
    bindLayoutToggles();
    initClientRendering(clientList);
  }

  async function initClientRendering(container) {
    container.innerHTML = `
      <div class="flex flex-col items-center justify-center p-12 text-center">
        <svg class="h-8 w-8 animate-spin text-[#114b9a]" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="mt-3 text-sm font-medium text-neutral-500">Memuat data prestasi...</span>
      </div>
    `;

    try {
      if (typeof API.getPrestasiList === 'function') {
        prestasi = await API.getPrestasiList();
      }
    } catch (err) {
      console.warn('Gagal memuat API prestasi:', err);
    }

    renderClientPrestasi(container);
  }

  function renderClientPrestasi(container) {
    container.className = currentLayout === 'grid' ? 'prestasi-grid' : 'soft-card overflow-hidden prestasi-list';

    if (!Array.isArray(prestasi) || prestasi.length === 0) {
      container.innerHTML = '<div class="p-8 text-center text-sm text-neutral-600">Belum ada data prestasi yang tersedia.</div>';
      return;
    }

    container.innerHTML = prestasi
      .map((item, index) => (currentLayout === 'grid' ? renderClientGridItem(item, index) : renderClientListItem(item, index)))
      .join('');

    bindCardClicks(container);
    if (typeof observeFadeUp === 'function') {
      observeFadeUp();
    }
  }

  function renderClientListItem(item, index) {
    const image = resolveItemImage(item, index);
    const fallbackImage = getFallbackImage(index);
    return `
      <button type="button" class="prestasi-row soft-row text-left cursor-pointer" data-id="${item.id}" data-slug="${escapeHtml(item.slug || item.id)}" data-index="${index}" title="Klik untuk melihat ringkasan prestasi">
        <figure class="prestasi-row-image">
          <img src="${image}" alt="Dokumentasi ${escapeHtml(item.title)}" loading="lazy" onerror="this.src='${fallbackImage}'" />
        </figure>
        <span class="serif prestasi-number">${String(index + 1).padStart(2, '0')}</span>
        <div class="prestasi-row-copy">
          <div class="flex flex-wrap items-center gap-2 text-xs font-bold text-blue-800">
            <span>${escapeHtml(item.category || 'Prestasi')}</span><span>•</span><span>${escapeHtml(String(item.year || ''))}</span>
          </div>
          <h3 class="serif mt-2 text-2xl font-semibold leading-tight tracking-tight text-neutral-950">${escapeHtml(item.title)}</h3>
          <p class="mt-3 text-sm leading-7 text-neutral-600">${escapeHtml(item.description || '')}</p>
        </div>
        <div class="prestasi-person">
          <strong>${escapeHtml(item.name || item.member_name || '')}</strong><br />${escapeHtml(item.campus || item.institution || '')}
        </div>
      </button>
    `;
  }

  function renderClientGridItem(item, index) {
    const image = resolveItemImage(item, index);
    const fallbackImage = getFallbackImage(index);
    return `
      <button type="button" class="prestasi-grid-card soft-card text-left cursor-pointer" data-id="${item.id}" data-slug="${escapeHtml(item.slug || item.id)}" data-index="${index}" title="Klik untuk melihat ringkasan prestasi">
        <figure class="prestasi-grid-image">
          <img src="${image}" alt="Dokumentasi ${escapeHtml(item.title)}" loading="lazy" onerror="this.src='${fallbackImage}'" />
        </figure>
        <div class="prestasi-grid-copy">
          <div class="flex flex-wrap items-center gap-2 text-xs font-bold text-blue-800">
            <span>${escapeHtml(item.category || 'Prestasi')}</span><span>•</span><span>${escapeHtml(String(item.year || ''))}</span>
          </div>
          <h3 class="serif mt-3 text-2xl font-semibold leading-tight tracking-tight text-neutral-950">${escapeHtml(item.title)}</h3>
          <p class="mt-3 text-sm leading-7 text-neutral-600">${escapeHtml(item.description || '')}</p>
          <p class="mt-4 rounded-2xl bg-blue-50 px-4 py-3 text-sm leading-6 text-blue-950">
            <strong>${escapeHtml(item.name || item.member_name || '')}</strong><br />${escapeHtml(item.campus || item.institution || '')}
          </p>
        </div>
      </button>
    `;
  }
})();
