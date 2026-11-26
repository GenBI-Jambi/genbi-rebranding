<?php
use App\Core\Paginator;

$page = $page ?? 1;
$perPage = $perPage ?? 9;
$total = $total ?? 0;
$totalPages = $totalPages ?? 1;
$filters = $filters ?? [];
$activeQ = $filters['q'] ?? '';
$startItem = ($page - 1) * $perPage + 1;
$endItem = min($page * $perPage, $total);
?>
<section class="public-inner-hero py-16 md:py-24">
  <div class="site-container">
    <p class="eyebrow">Event</p>
    <h1 class="page-title mt-5 max-w-4xl">Agenda dan kegiatan GenBI Jambi.</h1>
    <p class="lead mt-7 max-w-3xl">Daftar kegiatan komunitas yang sudah dan akan dilaksanakan oleh GenBI Provinsi Jambi.</p>
  </div>
</section>
<section class="bg-cream py-12 md:py-16">
  <div class="site-container">
    <form class="event-search-form mb-8" method="get" action="/event" id="event-filter-form" role="search" aria-label="Pencarian Event">
      <div class="event-search-wrapper">
        <span class="event-search-icon" aria-hidden="true">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
        </span>
        <input id="event-search" name="q" class="event-search-input" placeholder="Cari event, lokasi, atau topik..." value="<?= $e($activeQ) ?>" autocomplete="off" />
        <button type="button" id="event-search-clear" class="event-search-clear" aria-label="Hapus pencarian" title="Hapus pencarian" style="<?= $activeQ !== '' ? 'display: inline-flex;' : 'display: none;' ?>">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </form>
    <div class="mb-5 text-sm text-neutral-600" id="event-count">
      <?php if ($total > 0): ?>
        Menampilkan <span class="font-semibold text-neutral-900"><?= $startItem ?>–<?= $endItem ?></span> dari <span class="font-semibold text-neutral-900"><?= $total ?></span> event.
      <?php elseif ($activeQ !== ''): ?>
        Tidak ada event yang cocok dengan "<span class="font-semibold text-neutral-900"><?= $e($activeQ) ?></span>".
      <?php else: ?>
        Belum ada event yang tersedia.
      <?php endif; ?>
    </div>
    <div class="event-grid" id="event-list" data-ssr="true">
      <?php if (!empty($items)): ?>
        <?php foreach ($items as $item): ?>
          <article class="event-card">
            <div class="event-card-image">
              <?php if (!empty($item['image'])): ?>
                <img src="<?= $e($item['image']) ?>" alt="<?= $e($item['title'] ?? '') ?>" loading="lazy" onerror="this.src='https://genbijambi.com/public/uploads/slider-1.png'" />
              <?php else: ?>
                <img src="https://genbijambi.com/public/uploads/slider-1.png" alt="<?= $e($item['title'] ?? '') ?>" loading="lazy" />
              <?php endif; ?>
              <span class="event-card-badge <?= ($item['status'] ?? '') === 'Upcoming' ? 'upcoming' : '' ?>">
                <?= $e($item['status'] ?? 'Event') ?>
              </span>
            </div>
            <div class="event-card-body">
              <div class="event-date-row">
                <svg class="h-3.5 w-3.5 text-[#114b9a] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                  <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                  <line x1="16" x2="16" y1="2" y2="6" />
                  <line x1="8" x2="8" y1="2" y2="6" />
                  <line x1="3" x2="21" y1="10" y2="10" />
                </svg>
                <span><?= $e($item['start_date'] ?? '') ?><?php if (!empty($item['end_date']) && $item['end_date'] !== ($item['start_date'] ?? '')): ?> – <?= $e($item['end_date']) ?><?php endif; ?></span>
              </div>
              <h3 class="event-card-title"><?= $e($item['title'] ?? '') ?></h3>
              <p class="event-card-excerpt"><?= $e($item['excerpt'] ?? '') ?></p>
              <div class="event-card-location">
                <?php if (!empty($item['location'])): ?>
                  <svg class="h-3.5 w-3.5 flex-shrink-0 text-[#114b9a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                  </svg>
                  <span class="truncate"><?= $e($item['location']) ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="event-card-footer">
              <a href="/event/<?= rawurlencode((string) ($item['slug'] ?? $item['id'])) ?>" class="btn-event-detail open-event" data-id="<?= (int) $item['id'] ?>" data-slug="<?= $e((string) ($item['slug'] ?? '')) ?>" aria-label="Lihat detail event <?= $e($item['title'] ?? '') ?>">
                <span>Lihat Detail Agenda</span>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="rounded-2xl border border-neutral-900/10 bg-white p-8 text-center text-sm text-neutral-600 col-span-full">Belum ada event yang cocok.</div>
      <?php endif; ?>
    </div>
    <?php if ($totalPages > 1): ?>
      <nav class="public-pagination mt-8" id="event-pagination" aria-label="Pagination event" data-ssr="true">
        <?php
          $filterParams = array_filter([
            'q' => $activeQ,
          ], static fn($v) => $v !== '' && $v !== null);
        ?>
        <?php if ($page > 1): ?>
          <a class="pager-button" href="/event?<?= $e(Paginator::buildQuery($page - 1, $filterParams)) ?>">Sebelumnya</a>
        <?php else: ?>
          <span class="pager-button" aria-disabled="true">Sebelumnya</span>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <?php if ($i === $page): ?>
            <span class="pager-button is-active" aria-current="page"><?= $i ?></span>
          <?php else: ?>
            <a class="pager-button" href="/event?<?= $e(Paginator::buildQuery($i, $filterParams)) ?>"><?= $i ?></a>
          <?php endif; ?>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
          <a class="pager-button" href="/event?<?= $e(Paginator::buildQuery($page + 1, $filterParams)) ?>">Berikutnya</a>
        <?php else: ?>
          <span class="pager-button" aria-disabled="true">Berikutnya</span>
        <?php endif; ?>
      </nav>
    <?php else: ?>
      <div class="public-pagination mt-8" id="event-pagination" aria-label="Pagination event"></div>
    <?php endif; ?>
  </div>
</section>
<div id="event-modal" class="public-fixed-modal hidden"></div>

<style>
/* ==========================================================================
   SEARCH - CLEAN PILL BAR, WIDE ON DESKTOP & RESPONSIVE
   ========================================================================== */
.event-search-form {
  width: 100% !important;
  max-width: 100% !important;
}

.event-search-wrapper {
  display: flex !important;
  align-items: center !important;
  gap: 0.75rem !important;
  background: #ffffff !important;
  border: 1.5px solid rgba(15, 23, 42, 0.12) !important;
  border-radius: 9999px !important;
  padding: 0.75rem 1.25rem !important;
  box-shadow: 0 1px 6px rgba(15, 23, 42, 0.04) !important;
  transition: border-color 200ms ease, box-shadow 200ms ease !important;
  width: 100% !important;
  box-sizing: border-box !important;
}

@media (min-width: 768px) {
  .event-search-wrapper {
    padding: 0.85rem 1.45rem !important;
    gap: 0.85rem !important;
  }
}

.event-search-wrapper:focus-within {
  border-color: #114b9a !important;
  box-shadow: 0 0 0 3px rgba(17, 75, 154, 0.10), 0 4px 14px rgba(17, 75, 154, 0.08) !important;
}

.event-search-icon {
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  color: #94a3b8 !important;
  flex-shrink: 0 !important;
  transition: color 200ms ease !important;
}

.event-search-wrapper:focus-within .event-search-icon {
  color: #114b9a !important;
}

.event-search-input {
  flex: 1 1 0% !important;
  min-width: 0 !important;
  border: none !important;
  outline: none !important;
  background: transparent !important;
  color: #0f172a !important;
  font-family: 'Inter', sans-serif !important;
  font-size: 0.9375rem !important;
  line-height: 1.5 !important;
  padding: 0 !important;
  box-shadow: none !important;
}

.event-search-input::placeholder {
  color: #94a3b8 !important;
  font-weight: 400 !important;
}

.event-search-clear {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  width: 1.625rem !important;
  height: 1.625rem !important;
  border-radius: 9999px !important;
  background: #f1f5f9 !important;
  color: #64748b !important;
  flex-shrink: 0 !important;
  border: none !important;
  cursor: pointer !important;
  padding: 0 !important;
  transition: all 160ms ease !important;
}

.event-search-clear:hover {
  background: #e2e8f0 !important;
  color: #0f172a !important;
}

/* ==========================================================================
   EVENT CARD GRID - RESPONSIVE & EQUAL HEIGHT
   ========================================================================== */
.event-grid {
  display: grid !important;
  grid-template-columns: 1fr !important;
  gap: 1rem !important;
  align-items: stretch !important;
}

@media (min-width: 560px) {
  .event-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    gap: 1.125rem !important;
  }
}

@media (min-width: 1024px) {
  .event-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    gap: 1.25rem !important;
  }
}

.event-card {
  display: flex !important;
  flex-direction: column !important;
  height: 100% !important;
  border-radius: 1.125rem !important;
  border: 1px solid rgba(15, 23, 42, 0.07) !important;
  background: #ffffff !important;
  padding: 0.75rem !important;
  box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03) !important;
  transition: transform 260ms cubic-bezier(0.16, 1, 0.3, 1), box-shadow 260ms ease, border-color 260ms ease !important;
  overflow: hidden !important;
}

@media (min-width: 560px) {
  .event-card { padding: 0.875rem !important; }
}

.event-card:hover {
  transform: translateY(-3px) !important;
  border-color: rgba(17, 75, 154, 0.2) !important;
  box-shadow: 0 12px 32px -8px rgba(17, 75, 154, 0.08) !important;
}

.event-card-image {
  position: relative !important;
  width: 100% !important;
  aspect-ratio: 16 / 10 !important;
  border-radius: 0.75rem !important;
  overflow: hidden !important;
  background: #f1f5f9 !important;
  flex-shrink: 0 !important;
}

.event-card-image img {
  width: 100% !important;
  height: 100% !important;
  object-fit: cover !important;
  display: block !important;
  transition: transform 350ms cubic-bezier(0.16, 1, 0.3, 1) !important;
}

.event-card:hover .event-card-image img {
  transform: scale(1.03) !important;
}

.event-card-badge {
  position: absolute !important;
  top: 0.5rem !important;
  right: 0.5rem !important;
  border-radius: 9999px !important;
  background: rgba(15, 23, 42, 0.72) !important;
  padding: 0.25rem 0.6rem !important;
  color: #ffffff !important;
  font-size: 0.625rem !important;
  font-weight: 700 !important;
  letter-spacing: 0.06em !important;
  text-transform: uppercase !important;
  backdrop-filter: blur(6px) !important;
  -webkit-backdrop-filter: blur(6px) !important;
  border: 1px solid rgba(255, 255, 255, 0.15) !important;
}

.event-card-badge.upcoming {
  background: #114b9a !important;
}

.event-card-body {
  display: flex !important;
  flex-direction: column !important;
  flex: 1 1 auto !important;
  padding: 0.65rem 0.125rem 0 !important;
}

.event-date-row {
  display: inline-flex !important;
  align-items: center !important;
  gap: 0.35rem !important;
  font-size: 0.6875rem !important;
  font-weight: 700 !important;
  color: #114b9a !important;
  letter-spacing: 0.04em !important;
  text-transform: uppercase !important;
}

.event-card-title {
  font-family: 'Source Serif 4', Georgia, serif !important;
  font-size: 1.0625rem !important;
  font-weight: 650 !important;
  line-height: 1.35 !important;
  color: #0f172a !important;
  margin-top: 0.35rem !important;
  margin-bottom: 0 !important;
  display: -webkit-box !important;
  -webkit-line-clamp: 2 !important;
  -webkit-box-orient: vertical !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
  min-height: 2.85rem !important;
}

@media (min-width: 1024px) {
  .event-card-title { font-size: 1.125rem !important; min-height: 3rem !important; }
}

.event-card-excerpt {
  font-size: 0.8rem !important;
  line-height: 1.55 !important;
  color: #64748b !important;
  margin-top: 0.35rem !important;
  margin-bottom: 0 !important;
  display: -webkit-box !important;
  -webkit-line-clamp: 3 !important;
  -webkit-box-orient: vertical !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
  min-height: 3.7rem !important;
  flex: 1 1 auto !important;
}

.event-card-location {
  display: flex !important;
  align-items: center !important;
  gap: 0.3rem !important;
  font-size: 0.725rem !important;
  font-weight: 600 !important;
  color: #0c3572 !important;
  margin-top: 0.5rem !important;
  min-height: 1.1rem !important;
}

.event-card-footer {
  margin-top: 0.65rem !important;
  padding-top: 0.625rem !important;
  border-top: 1px solid #f1f5f9 !important;
  flex-shrink: 0 !important;
}

.btn-event-detail {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  gap: 0.4rem !important;
  width: 100% !important;
  padding: 0.55rem 0.75rem !important;
  border-radius: 9999px !important;
  background: #f8fafc !important;
  border: 1px solid rgba(15, 23, 42, 0.08) !important;
  color: #1e293b !important;
  font-family: 'Inter', sans-serif !important;
  font-size: 0.775rem !important;
  font-weight: 600 !important;
  text-decoration: none !important;
  transition: all 180ms ease !important;
  cursor: pointer !important;
}

.btn-event-detail:hover {
  background: #114b9a !important;
  color: #ffffff !important;
  border-color: #114b9a !important;
  box-shadow: 0 3px 10px rgba(17, 75, 154, 0.18) !important;
}

/* ==========================================================================
   EVENT MODAL - RESPONSIVE 2-COL DESKTOP / STACKED MOBILE
   ========================================================================== */
#event-modal.public-fixed-modal {
  position: fixed !important;
  inset: 0 !important;
  z-index: 99999 !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  box-sizing: border-box !important;
  overflow: hidden !important;
  background: rgba(15, 23, 42, 0.55) !important;
  padding: 0.5rem !important;
  backdrop-filter: blur(5px) !important;
  -webkit-backdrop-filter: blur(5px) !important;
}

@media (min-width: 640px) {
  #event-modal.public-fixed-modal { padding: 1rem !important; }
}

@media (min-width: 1024px) {
  #event-modal.public-fixed-modal { padding: 1.5rem !important; }
}

#event-modal.public-fixed-modal.hidden {
  display: none !important;
}

@keyframes eventModalIn {
  from { opacity: 0; transform: scale(0.96) translateY(8px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}

.event-detail-panel {
  position: relative !important;
  width: 100% !important;
  max-width: 880px !important;
  margin: auto !important;
  background: #ffffff !important;
  border-radius: 1rem !important;
  border: 1px solid rgba(15, 23, 42, 0.06) !important;
  box-shadow: 0 20px 50px -8px rgba(0, 0, 0, 0.25) !important;
  animation: eventModalIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  overflow: hidden !important;
  display: flex !important;
  flex-direction: column !important;
  max-height: calc(100dvh - 1rem) !important;
}

@media (min-width: 640px) {
  .event-detail-panel {
    border-radius: 1.25rem !important;
    max-height: calc(100dvh - 2rem) !important;
  }
}

/* Mobile: entire panel scrolls */
@media (max-width: 899px) {
  .event-detail-panel {
    overflow-y: auto !important;
    overscroll-behavior: contain !important;
    -webkit-overflow-scrolling: touch !important;
    scrollbar-width: thin !important;
    scrollbar-color: rgba(17, 75, 154, 0.15) transparent !important;
  }
  .event-detail-panel::-webkit-scrollbar { width: 4px !important; }
  .event-detail-panel::-webkit-scrollbar-thumb {
    background: rgba(17, 75, 154, 0.12) !important;
    border-radius: 9999px !important;
  }
  .event-detail-content {
    height: auto !important;
    max-height: none !important;
    overflow-y: visible !important;
    padding: 1rem 1rem 1.25rem !important;
  }
}

/* Desktop: 2-col grid, right col scrolls */
@media (min-width: 900px) {
  .event-detail-panel {
    display: grid !important;
    grid-template-columns: minmax(300px, 380px) minmax(0, 1fr) !important;
    align-items: stretch !important;
    height: min(80vh, 580px) !important;
    max-height: 580px !important;
    overflow: hidden !important;
  }
}

.event-detail-close {
  position: absolute !important;
  top: 0.625rem !important;
  right: 0.625rem !important;
  z-index: 60 !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  width: 1.875rem !important;
  height: 1.875rem !important;
  border-radius: 9999px !important;
  background: rgba(255, 255, 255, 0.9) !important;
  border: 1px solid rgba(15, 23, 42, 0.1) !important;
  color: #475569 !important;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06) !important;
  backdrop-filter: blur(6px) !important;
  -webkit-backdrop-filter: blur(6px) !important;
  transition: all 160ms ease !important;
  cursor: pointer !important;
}

@media (min-width: 640px) {
  .event-detail-close {
    top: 0.875rem !important;
    right: 0.875rem !important;
    width: 2rem !important;
    height: 2rem !important;
  }
}

.event-detail-close:hover {
  background: #ffffff !important;
  color: #0c3572 !important;
  border-color: rgba(17, 75, 154, 0.3) !important;
  transform: scale(1.05) !important;
}

.event-detail-close:active {
  transform: scale(0.93) !important;
}

.event-detail-image {
  position: relative !important;
  overflow: hidden !important;
  background: #f1f5f9 !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  flex-shrink: 0 !important;
  height: 200px !important;
  margin: 0 !important;
}

@media (min-width: 640px) {
  .event-detail-image { height: 220px !important; }
}

@media (min-width: 900px) {
  .event-detail-image {
    height: 100% !important;
    min-height: 100% !important;
  }
}

.event-detail-image img {
  width: 100% !important;
  height: 100% !important;
  object-fit: cover !important;
  display: block !important;
}

.event-detail-badge {
  position: absolute !important;
  top: 0.625rem !important;
  left: 0.625rem !important;
  border-radius: 9999px !important;
  background: rgba(15, 23, 42, 0.75) !important;
  padding: 0.25rem 0.65rem !important;
  color: #ffffff !important;
  font-size: 0.625rem !important;
  font-weight: 700 !important;
  letter-spacing: 0.06em !important;
  text-transform: uppercase !important;
  backdrop-filter: blur(6px) !important;
  -webkit-backdrop-filter: blur(6px) !important;
  border: 1px solid rgba(255, 255, 255, 0.18) !important;
}

.event-detail-badge.upcoming { background: #114b9a !important; }

.event-detail-content {
  display: flex !important;
  flex-direction: column !important;
  padding: 1.25rem 1.5rem !important;
  overflow-y: auto !important;
  overscroll-behavior: contain !important;
  -webkit-overflow-scrolling: touch !important;
  height: 100% !important;
  max-height: 100% !important;
  box-sizing: border-box !important;
  scrollbar-width: thin !important;
  scrollbar-color: rgba(17, 75, 154, 0.15) transparent !important;
}

.event-detail-content::-webkit-scrollbar { width: 4px !important; }
.event-detail-content::-webkit-scrollbar-thumb {
  background: rgba(17, 75, 154, 0.12) !important;
  border-radius: 9999px !important;
}

/* Modal inner components */
.event-info-cards {
  display: flex !important;
  flex-direction: column !important;
  gap: 0.4rem !important;
}

.event-info-card {
  display: flex !important;
  align-items: center !important;
  gap: 0.65rem !important;
  background: #f8fafc !important;
  border: 1px solid rgba(226, 232, 240, 0.85) !important;
  border-radius: 0.75rem !important;
  padding: 0.55rem 0.75rem !important;
  text-align: left !important;
}

.event-info-icon {
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  width: 1.875rem !important;
  height: 1.875rem !important;
  border-radius: 9999px !important;
  background: #eef6ff !important;
  border: 1px solid rgba(17, 75, 154, 0.1) !important;
  flex-shrink: 0 !important;
}

.event-info-label {
  display: block !important;
  font-size: 0.625rem !important;
  font-weight: 700 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.04em !important;
  color: #114b9a !important;
  text-align: left !important;
}

.event-info-val {
  display: block !important;
  font-size: 0.8rem !important;
  font-weight: 600 !important;
  color: #0f172a !important;
  line-height: 1.3 !important;
  margin-top: 0.05rem !important;
  text-align: left !important;
}

.event-modal-body {
  background: #f8fafc !important;
  border: 1px solid rgba(226, 232, 240, 0.85) !important;
  border-radius: 0.75rem !important;
  padding: 0.65rem 0.75rem !important;
  text-align: left !important;
}

.event-modal-body-header {
  font-size: 0.625rem !important;
  font-weight: 700 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.04em !important;
  color: #64748b !important;
  margin-bottom: 0.35rem !important;
  text-align: left !important;
}

.event-modal-body-text {
  font-size: 0.8rem !important;
  line-height: 1.6 !important;
  color: #334155 !important;
  text-align: left !important;
}

.event-modal-footer {
  flex-shrink: 0 !important;
  padding-top: 0.875rem !important;
  border-top: 1px solid #f1f5f9 !important;
}

/* Hide back-to-top when event modal is open */
body.modal-lock #back-to-top,
body.modal-lock .back-to-top,
#event-modal:not(.hidden) ~ #back-to-top {
  opacity: 0 !important;
  visibility: hidden !important;
  pointer-events: none !important;
}
</style>
