(function(){
'use strict';
const { formatInitials, renderShell } = window.GenBIApp;
const { createCustomSelect, createModalController, observeFadeUp, safeImage, unique } = window.GenBIUI;
const API = window.GenBIAPI;

renderShell('team');
observeFadeUp();

const memberModal = createModalController(document.querySelector('#member-modal'));
let teamMembers = Array.isArray(window.teamMembersData) ? [...window.teamMembersData] : [];
let filterOptions = { divisions: [], campuses: [], years: [] };
let state = {
  query: '',
  division: 'Semua',
  campus: 'Semua',
  year: 'Semua',
  page: 1,
  perPage: 12,
  layout: 'grid'
};

function escapeHtml(str) {
  if (str === null || str === undefined) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

function bindMemberCardClicks(container) {
  if (!container) return;
  const cards = container.querySelectorAll('.team-public-card, .team-public-row');
  cards.forEach((card) => {
    if (card.dataset.modalBound === '1') return;
    card.dataset.modalBound = '1';

    const triggerOpen = (e) => {
      if (e.target.closest('a')) return;
      const id = Number(card.dataset.id);
      if (id) {
        openMemberById(id);
      }
    };

    card.addEventListener('click', triggerOpen);
    card.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        triggerOpen(e);
      }
    });
  });
}

function openMemberById(id) {
  let member = null;
  if (Array.isArray(window.teamMembersData)) {
    member = window.teamMembersData.find((m) => Number(m.id) === id);
  }
  if (!member && Array.isArray(teamMembers)) {
    member = teamMembers.find((m) => Number(m.id) === id);
  }
  if (member) {
    openMember(member);
    return;
  }

  // Fallback jika belum dimuat di memori
  API.getTeamList().then((res) => {
    if (res && Array.isArray(res.members)) {
      teamMembers = res.members;
      member = teamMembers.find((m) => Number(m.id) === id);
      if (member) {
        openMember(member);
      }
    }
  }).catch(() => {});
}

function openMember(member) {
  if (!memberModal) return;

  const photoHtml = member.photo
    ? `<img src="${safeImage(member.photo)}" alt="${escapeHtml(member.name)}" class="w-full h-full object-cover" style="object-position: center 20%;" onerror="this.parentElement.innerHTML='<span class=\\'text-3xl font-bold text-[#114b9a]\\'>${formatInitials(member.name)}</span>'" />`
    : `<span class="text-3xl font-bold text-[#114b9a]">${formatInitials(member.name)}</span>`;

  const bioHtml = member.detail || member.bio
    ? `<div class="mt-4 pt-3.5 border-t border-neutral-100">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400 mb-1">Catatan</p>
        <p class="text-xs text-neutral-600 leading-relaxed">${escapeHtml(member.detail || member.bio)}</p>
       </div>`
    : '';

  const socialLinks = [];
  if (member.instagram) {
    const rawIg = String(member.instagram).trim();
    const cleanIg = rawIg.replace(/^@/, '').replace(/^(https?:\/\/)?(www\.)?instagram\.com\/?/, '');
    const igUrl = rawIg.startsWith('http') ? rawIg : `https://instagram.com/${cleanIg}`;
    socialLinks.push(`<a href="${igUrl}" target="_blank" rel="noopener noreferrer" class="team-modal-link" title="Instagram">
      <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
      <span>Instagram</span>
    </a>`);
  }
  if (member.linkedin) {
    const rawLi = String(member.linkedin).trim();
    const liUrl = rawLi.startsWith('http') ? rawLi : `https://linkedin.com/in/${rawLi}`;
    socialLinks.push(`<a href="${liUrl}" target="_blank" rel="noopener noreferrer" class="team-modal-link" title="LinkedIn">
      <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
      <span>LinkedIn</span>
    </a>`);
  }
  if (member.email) {
    socialLinks.push(`<a href="mailto:${escapeHtml(member.email)}" class="team-modal-link" title="Email">
      <svg class="h-3.5 w-3.5 fill-none stroke-current" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.24a2.25 2.25 0 01-1.07 1.92l-7.5 4.62a2.25 2.25 0 01-2.36 0l-7.5-4.62a2.25 2.25 0 01-1.07-1.92v-.24"/></svg>
      <span>Email</span>
    </a>`);
  }
  if (member.phone) {
    const rawPhone = String(member.phone).replace(/[^0-9]/g, '');
    const waPhone = rawPhone.startsWith('0') ? '62' + rawPhone.slice(1) : rawPhone;
    socialLinks.push(`<a href="https://wa.me/${waPhone}" target="_blank" rel="noopener noreferrer" class="team-modal-link" title="WhatsApp">
      <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
      <span>WhatsApp</span>
    </a>`);
  }

  const socialSection = socialLinks.length
    ? `<div class="mt-4 pt-3.5 border-t border-neutral-100 flex flex-wrap gap-2">${socialLinks.join('')}</div>`
    : '';

  memberModal.open({ content: `
    <div class="team-detail-modal-panel relative w-full bg-white text-neutral-900" role="dialog" aria-modal="true" aria-labelledby="member-modal-title">
      <button class="modal-close absolute top-4 right-4 flex h-8 w-8 items-center justify-center rounded-full text-neutral-400 hover:text-neutral-900 hover:bg-neutral-100 transition-colors focus:outline-none" aria-label="Tutup detail profil">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
      <div class="flex flex-col sm:flex-row gap-5 sm:gap-6 items-start">
        <div class="team-modal-photo-col w-32 sm:w-40 flex-shrink-0 mx-auto sm:mx-0">
          <div class="team-modal-photo-box w-full aspect-[4/5] rounded-xl overflow-hidden bg-neutral-100 border border-neutral-200/60 shadow-sm flex items-center justify-center">
            ${photoHtml}
          </div>
        </div>
        <div class="team-modal-info-col min-w-0 flex-1 w-full">
          <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#114b9a]">GenBI Provinsi Jambi</p>
            <h2 id="member-modal-title" class="serif mt-1 text-xl sm:text-2xl font-bold tracking-tight text-neutral-950">${escapeHtml(member.name)}</h2>
            <p class="mt-0.5 text-xs sm:text-sm font-semibold text-[#114b9a]">${escapeHtml(member.role || 'Anggota')}</p>
          </div>
          <div class="mt-4 team-credential-box">
            <div class="team-credential-item">
              <div class="team-credential-icon" aria-hidden="true">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v1.069m7.5 0c.98.056 1.95.14 2.91.251m-13.32 0c.96-.112 1.93-.196 2.91-.252"/></svg>
              </div>
              <div class="team-credential-text">
                <span class="team-credential-label">Divisi / Bidang</span>
                <span class="team-credential-val">${escapeHtml(member.division || '-')}</span>
              </div>
            </div>
            <div class="team-credential-divider" aria-hidden="true"></div>
            <div class="team-credential-item">
              <div class="team-credential-icon" aria-hidden="true">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333M4.5 21V10.333"/></svg>
              </div>
              <div class="team-credential-text">
                <span class="team-credential-label">Komisariat / Kampus</span>
                <span class="team-credential-val">${escapeHtml(member.campus || '-')}</span>
              </div>
            </div>
            <div class="team-credential-divider" aria-hidden="true"></div>
            <div class="team-credential-item">
              <div class="team-credential-icon" aria-hidden="true">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
              </div>
              <div class="team-credential-text">
                <span class="team-credential-label">Periode Kepengurusan</span>
                <span class="team-credential-val">${escapeHtml(member.year ? 'Tahun ' + member.year : '-')}</span>
              </div>
            </div>
          </div>
          ${bioHtml}
          ${socialSection}
        </div>
      </div>
    </div>
  ` });
}

// Inisialisasi SSR (Server-Side Rendered)
const ssrTeamList = document.querySelector('#team-list[data-ssr="true"]');
if (ssrTeamList) {
  document.body.classList.add('page-ready');
  const gridBtn = document.querySelector('#team-layout-grid');
  const listBtn = document.querySelector('#team-layout-list');

  gridBtn?.addEventListener('click', () => {
    ssrTeamList.className = 'fade-up team-public-grid in-view';
    gridBtn.classList.add('is-active');
    listBtn?.classList.remove('is-active');
  });

  listBtn?.addEventListener('click', () => {
    ssrTeamList.className = 'fade-up team-public-list in-view';
    listBtn.classList.add('is-active');
    gridBtn?.classList.remove('is-active');
  });

  // Pasang klik kartu untuk modal pada elemen SSR
  bindMemberCardClicks(ssrTeamList);

  const filterForm = document.querySelector('#team-filter-form');
  const searchInput = document.querySelector('#team-search');
  if (searchInput && filterForm) {
    searchInput.addEventListener('keydown', (event) => {
      if (event.key === 'Enter') {
        event.preventDefault();
        filterForm.submit();
      }
    });
  }
} else {
  // Inisialisasi Client-Side (Fallback jika tanpa SSR)
  hydrateStateFromUrl();
  const search = document.querySelector('#team-search');
  const count = document.querySelector('#team-count');
  const list = document.querySelector('#team-list');
  const pagination = document.querySelector('#team-pagination');
  const gridButton = document.querySelector('#team-layout-grid');
  const listButton = document.querySelector('#team-layout-list');

  initClient();

  async function initClient() {
    if (count) count.textContent = 'Memuat data anggota...';
    if (search) search.value = state.query;
    setLayoutButtons();

    try {
      const result = await API.getTeamList();
      teamMembers = result.members || [];
      filterOptions = result.filters || filterOptions;
    } catch {
      teamMembers = [];
    }

    const divSelect = document.querySelector('#team-division');
    if (divSelect) {
      createCustomSelect(divSelect, {
        label: 'Divisi',
        options: unique(filterOptions.divisions.length ? filterOptions.divisions : teamMembers.map((m) => m.division)),
        value: state.division,
        onChange: (val) => { state.division = val; state.page = 1; updateUrlState(); renderTeam(); }
      });
    }

    const campusSelect = document.querySelector('#team-campus');
    if (campusSelect) {
      createCustomSelect(campusSelect, {
        label: 'Komisariat/Kampus',
        options: unique(filterOptions.campuses.length ? filterOptions.campuses : teamMembers.map((m) => m.campus)),
        value: state.campus,
        onChange: (val) => { state.campus = val; state.page = 1; updateUrlState(); renderTeam(); }
      });
    }

    const yearSelect = document.querySelector('#team-year');
    if (yearSelect) {
      createCustomSelect(yearSelect, {
        label: 'Tahun',
        options: unique(filterOptions.years.length ? filterOptions.years : teamMembers.map((m) => m.year)),
        value: state.year,
        onChange: (val) => { state.year = val; state.page = 1; updateUrlState(); renderTeam(); }
      });
    }

    search?.addEventListener('input', (event) => {
      state.query = event.target.value;
      state.page = 1;
      updateUrlState();
      renderTeam();
    });

    gridButton?.addEventListener('click', () => setLayout('grid'));
    listButton?.addEventListener('click', () => setLayout('list'));

    renderTeam();
  }

  function setLayout(layout) {
    state.layout = layout;
    updateUrlState();
    setLayoutButtons();
    renderTeam();
  }

  function setLayoutButtons() {
    gridButton?.classList.toggle('is-active', state.layout === 'grid');
    listButton?.classList.toggle('is-active', state.layout === 'list');
  }

  function hydrateStateFromUrl() {
    const params = new URLSearchParams(window.location.search);
    const layout = params.get('view') || params.get('layout');
    if (layout === 'grid' || layout === 'list') state.layout = layout;
    state.query = params.get('q') || '';
    state.division = params.get('division') || 'Semua';
    state.campus = params.get('campus') || 'Semua';
    state.year = params.get('year') || 'Semua';
    state.page = Math.max(1, Number(params.get('page')) || 1);
  }

  function updateUrlState() {
    const params = new URLSearchParams();
    if (state.layout !== 'grid') params.set('view', state.layout);
    if (state.query.trim()) params.set('q', state.query.trim());
    if (state.division !== 'Semua') params.set('division', state.division);
    if (state.campus !== 'Semua') params.set('campus', state.campus);
    if (state.year !== 'Semua') params.set('year', state.year);
    if (state.page > 1) params.set('page', String(state.page));

    const query = params.toString();
    const url = query ? `${window.location.pathname}?${query}` : window.location.pathname;
    window.history.replaceState({}, '', url);
  }

  function getFilteredMembers() {
    const query = state.query.toLowerCase().trim();
    return teamMembers.filter((member) => {
      const haystack = `${member.name} ${member.role} ${member.division} ${member.campus} ${member.commission} ${member.year}`.toLowerCase();
      return (!query || haystack.includes(query))
        && (state.division === 'Semua' || member.division === state.division)
        && (state.campus === 'Semua' || member.campus === state.campus || member.commission === state.campus)
        && (state.year === 'Semua' || String(member.year) === String(state.year));
    });
  }

  function renderTeam() {
    if (!list) return;
    const filtered = getFilteredMembers();
    const totalPages = Math.max(1, Math.ceil(filtered.length / state.perPage));
    state.page = Math.min(state.page, totalPages);
    updateUrlState();
    const start = (state.page - 1) * state.perPage;
    const paged = filtered.slice(start, start + state.perPage);

    if (count) {
      count.textContent = filtered.length
        ? `Menampilkan ${start + 1}–${Math.min(start + state.perPage, filtered.length)} dari ${filtered.length} anggota (${teamMembers.length} total).`
        : `Belum ada data anggota yang cocok dengan filter.`;
    }

    list.className = `fade-up ${state.layout === 'list' ? 'team-public-list' : 'team-public-grid'} in-view`;
    list.innerHTML = paged.length
      ? paged.map((member) => state.layout === 'list' ? renderListCard(member) : renderGridCard(member)).join('')
      : `<div class="rounded-2xl border border-neutral-900/10 bg-white p-8 text-center text-sm text-neutral-600 col-span-full">Belum ada data anggota yang cocok dengan filter.</div>`;

    bindMemberCardClicks(list);
    renderPagination(totalPages);
  }

  function renderGridCard(member) {
    return `
      <article class="team-public-card cursor-pointer group" data-id="${member.id}" tabindex="0" role="button" aria-label="Lihat profil ${escapeHtml(member.name)}" title="Klik untuk melihat profil lengkap">
        <div class="team-public-photo">
          ${member.photo ? `<img src="${safeImage(member.photo)}" alt="${escapeHtml(member.name)}" loading="lazy" onerror="this.replaceWith(document.createTextNode('${formatInitials(member.name)}'))" />` : `<span>${formatInitials(member.name)}</span>`}
        </div>
        <div>
          <p class="eyebrow">${escapeHtml(member.year ? 'Tahun ' + member.year : 'GenBI Jambi')}</p>
          <h3 class="serif mt-2 text-2xl font-semibold tracking-tight text-neutral-950 group-hover:text-[#114b9a] transition-colors">${escapeHtml(member.name)}</h3>
          <p class="mt-2 text-sm font-semibold text-blue-800">${escapeHtml(member.role || 'Anggota')}</p>
          <p class="mt-3 text-sm leading-6 text-neutral-600">${escapeHtml(member.division || '-')}</p>
          <p class="text-sm leading-6 text-neutral-500">${escapeHtml(member.campus || '-')}</p>
        </div>
      </article>
    `;
  }

  function renderListCard(member) {
    return `
      <article class="team-public-row cursor-pointer group" data-id="${member.id}" tabindex="0" role="button" aria-label="Lihat profil ${escapeHtml(member.name)}" title="Klik untuk melihat profil lengkap">
        <div class="team-public-row-photo">
          ${member.photo ? `<img src="${safeImage(member.photo)}" alt="${escapeHtml(member.name)}" loading="lazy" onerror="this.replaceWith(document.createTextNode('${formatInitials(member.name)}'))" />` : `<span>${formatInitials(member.name)}</span>`}
        </div>
        <div>
          <h3 class="text-base font-bold text-neutral-950 group-hover:text-[#114b9a] transition-colors">${escapeHtml(member.name)}</h3>
          <p class="mt-1 text-sm leading-6 text-neutral-600">${escapeHtml(member.role || 'Anggota')}</p>
        </div>
        <div class="text-sm leading-6 text-neutral-600">
          <p class="font-semibold text-neutral-800">${escapeHtml(member.division || '-')}</p>
          <p>${escapeHtml(member.campus || '-')}</p>
        </div>
        <div class="text-sm font-semibold text-neutral-500">${escapeHtml(member.year ? 'Tahun ' + member.year : '')}</div>
      </article>
    `;
  }

  function getPaginationItems(current, total) {
    if (total <= 7) {
      return Array.from({ length: total }, (_, i) => i + 1);
    }
    if (current <= 4) {
      return [1, 2, 3, 4, 5, '...', total];
    }
    if (current >= total - 3) {
      return [1, '...', total - 4, total - 3, total - 2, total - 1, total];
    }
    return [1, '...', current - 1, current, current + 1, '...', total];
  }

  function renderPagination(totalPages) {
    if (!pagination) return;
    if (totalPages <= 1) {
      pagination.innerHTML = '';
      return;
    }
    const items = getPaginationItems(state.page, totalPages);
    pagination.innerHTML = `
      <button class="pager-button" type="button" data-page="${Math.max(1, state.page - 1)}" ${state.page === 1 ? 'disabled aria-disabled="true"' : ''}>Sebelumnya</button>
      ${items.map((item) => {
        if (item === '...') {
          return `<span class="pager-ellipsis" aria-hidden="true">&hellip;</span>`;
        }
        return `<button class="pager-button ${item === state.page ? 'is-active' : ''}" type="button" data-page="${item}" ${item === state.page ? 'aria-current="page"' : ''}>${item}</button>`;
      }).join('')}
      <button class="pager-button" type="button" data-page="${Math.min(totalPages, state.page + 1)}" ${state.page === totalPages ? 'disabled aria-disabled="true"' : ''}>Berikutnya</button>
    `;
    pagination.querySelectorAll('[data-page]').forEach((button) => {
      button.addEventListener('click', () => {
        if (button.disabled || button.getAttribute('aria-disabled') === 'true') return;
        state.page = Number(button.dataset.page) || 1;
        updateUrlState();
        renderTeam();
        list.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });
  }
}

})();
