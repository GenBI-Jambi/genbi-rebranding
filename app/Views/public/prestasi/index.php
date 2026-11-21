<?php
use App\Core\Paginator;

$page = $page ?? 1;
$perPage = $perPage ?? 12;
$total = $total ?? 0;
$totalPages = $totalPages ?? 1;
$startItem = ($page - 1) * $perPage + 1;
$endItem = min($page * $perPage, $total);
?>
<section class="public-inner-hero py-16 md:py-24">
  <div class="article-container fade-up">
    <p class="eyebrow">Prestasi</p>
    <h1 class="page-title mt-5">Prestasi GenBI Jambi.</h1>
    <p class="lead mt-7">Pencapaian anggota GenBI Provinsi Jambi di berbagai bidang kompetisi dan pengabdian.</p>
  </div>
</section>
<section class="bg-cream py-12 md:py-16">
  <div class="article-container">
    <div class="fade-up mb-6 flex flex-wrap items-center justify-between gap-4">
      <div class="text-sm text-neutral-600" id="prestasi-count">
        <?php if ($total > 0): ?>
          Menampilkan <span class="font-semibold text-neutral-900"><?= $startItem ?>–<?= $endItem ?></span> dari <span class="font-semibold text-neutral-900"><?= $total ?></span> prestasi.
        <?php else: ?>
          Belum ada data prestasi yang tersedia.
        <?php endif; ?>
      </div>
      <div class="flex items-center gap-1.5" role="group" aria-label="Pilihan tampilan data">
        <button type="button" class="chip is-active flex items-center gap-2" data-prestasi-layout="grid" aria-label="Tampilan Kisi">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
          </svg>
          <span>Kisi</span>
        </button>
        <button type="button" class="chip flex items-center gap-2" data-prestasi-layout="list" aria-label="Tampilan Daftar">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
          <span>Daftar</span>
        </button>
      </div>
    </div>
    <div class="prestasi-grid" id="prestasi-list" data-ssr="true">
      <?php if (!empty($items)): ?>
        <?php foreach ($items as $index => $item): ?>
          <?php
            $image = (string) ($item['image'] ?? $item['photo'] ?? '');
            $slug = (string) ($item['slug'] ?? $item['id']);
          ?>
          <article class="prestasi-row soft-row cursor-pointer"
                   role="button"
                   tabindex="0"
                   data-id="<?= (int) $item['id'] ?>"
                   data-slug="<?= $e($slug) ?>"
                   data-index="<?= $index ?>"
                   data-title="<?= $e($item['title'] ?? '') ?>"
                   data-name="<?= $e($item['name'] ?? '') ?>"
                   data-campus="<?= $e($item['campus'] ?? $item['institution'] ?? '') ?>"
                   data-category="<?= $e($item['category'] ?? '') ?>"
                   data-year="<?= $e((string)($item['year'] ?? '')) ?>"
                   data-description="<?= $e($item['description'] ?? '') ?>"
                   data-image="<?= $e($image) ?>"
                   data-institution="<?= $e($item['institution'] ?? '') ?>"
                   aria-label="Lihat ringkasan prestasi <?= $e($item['title'] ?? '') ?>"
                   title="Klik untuk melihat ringkasan prestasi">
            <figure class="prestasi-row-image" aria-hidden="<?= $image === '' ? 'true' : 'false' ?>">
              <?php if ($image !== ''): ?>
                <img src="<?= $e($image) ?>" alt="Dokumentasi <?= $e($item['title'] ?? '') ?>" loading="lazy" />
              <?php else: ?>
                <span>No image</span>
              <?php endif; ?>
            </figure>
            <span class="serif prestasi-number"><?= str_pad((string) ($startItem + $index), 2, '0', STR_PAD_LEFT) ?></span>
            <div class="prestasi-row-copy">
              <div class="flex flex-wrap items-center gap-2 text-xs font-bold text-blue-800">
                <span><?= $e($item['category'] ?? '') ?></span><span>•</span><span><?= $e((string)($item['year'] ?? '')) ?></span>
              </div>
              <h3 class="serif mt-2 text-2xl font-semibold leading-tight tracking-tight text-neutral-950"><?= $e($item['title'] ?? '') ?></h3>
              <p class="mt-3 text-sm leading-7 text-neutral-600"><?= $e($item['description'] ?? '') ?></p>
            </div>
            <div class="prestasi-person">
              <strong><?= $e($item['name'] ?? '') ?></strong><br /><?= $e($item['campus'] ?? $item['institution'] ?? '') ?>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="p-8 text-center text-sm text-neutral-600">Belum ada data prestasi yang tersedia.</div>
      <?php endif; ?>
    </div>
    <?php if ($totalPages > 1): ?>
      <nav class="public-pagination mt-8" id="prestasi-pagination" aria-label="Pagination prestasi" data-ssr="true">
        <?php if ($page > 1): ?>
          <a class="pager-button" href="/prestasi?<?= $e(Paginator::buildQuery($page - 1)) ?>">Sebelumnya</a>
        <?php else: ?>
          <span class="pager-button" aria-disabled="true">Sebelumnya</span>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <?php if ($i === $page): ?>
            <span class="pager-button is-active" aria-current="page"><?= $i ?></span>
          <?php else: ?>
            <a class="pager-button" href="/prestasi?<?= $e(Paginator::buildQuery($i)) ?>"><?= $i ?></a>
          <?php endif; ?>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
          <a class="pager-button" href="/prestasi?<?= $e(Paginator::buildQuery($page + 1)) ?>">Berikutnya</a>
        <?php else: ?>
          <span class="pager-button" aria-disabled="true">Berikutnya</span>
        <?php endif; ?>
      </nav>
    <?php else: ?>
      <div class="public-pagination mt-8" id="prestasi-pagination" aria-label="Pagination prestasi"></div>
    <?php endif; ?>
  </div>
</section>
<div id="prestasi-modal" class="public-fixed-modal hidden"></div>
<script>
  window.prestasiData = <?= json_encode(!empty($items) ? array_values($items) : [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
</script>

<style>
/* ==========================================================================
   PERBAIKAN TAMPILAN TOOLBAR KISI & DAFTAR (GENBI PRESTASI)
   ========================================================================== */
.chip[data-prestasi-layout] {
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  min-height: 2.25rem;
  border-radius: 9999px;
  border: 1px solid rgba(23, 23, 23, 0.12);
  background: rgba(255, 255, 255, 0.9);
  padding: 0.45rem 0.95rem;
  font-size: 0.8125rem;
  font-weight: 600;
  color: #525252;
  transition: all 180ms ease;
  backdrop-filter: blur(8px);
  user-select: none;
}

.chip[data-prestasi-layout]:hover {
  background: #f8fafc;
  color: #0f172a;
  border-color: rgba(23, 23, 23, 0.22);
}

.chip[data-prestasi-layout].is-active {
  background: #114b9a !important;
  color: #ffffff !important;
  border-color: #114b9a !important;
  box-shadow: 0 4px 14px rgba(17, 75, 154, 0.25) !important;
}

.chip[data-prestasi-layout].is-active svg {
  stroke: #ffffff !important;
}

/* ==========================================================================
   PERBAIKAN LOADING TAMPILAN KLIK CARD GENPRES
   ========================================================================== */
.prestasi-loading-panel {
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  justify-content: center !important;
  width: min(100%, 380px) !important;
  min-height: 230px !important;
  margin: auto !important;
  padding: 2.5rem 1.75rem !important;
  text-align: center !important;
  background: #ffffff !important;
  border-radius: 1.75rem !important;
  border: 1px solid rgba(23, 23, 23, 0.08) !important;
  box-shadow: 0 25px 70px -15px rgba(0, 0, 0, 0.25) !important;
  animation: modalPopIn 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes modalPopIn {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(8px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

/* ==========================================================================
   MODAL DETAIL CARD PRESTASI
   ========================================================================== */
.prestasi-detail-panel {
  position: relative !important;
  width: min(100%, 940px) !important;
  max-height: calc(100vh - 3.5rem) !important;
  overflow-y: auto !important;
  padding: 0 !important;
  border-radius: 1.75rem !important;
  border: 1px solid rgba(23, 23, 23, 0.1) !important;
  background: #ffffff !important;
  box-shadow: 0 30px 100px rgba(0, 0, 0, 0.3) !important;
  animation: modalPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@media (min-width: 900px) {
  .prestasi-detail-panel {
    display: grid !important;
    width: min(100%, 1000px) !important;
    grid-template-columns: minmax(0, 0.95fr) minmax(0, 1.05fr) !important;
    align-items: stretch !important;
    max-height: min(85vh, 680px) !important;
  }
}

.prestasi-detail-close {
  position: absolute !important;
  top: 1rem !important;
  right: 1rem !important;
  z-index: 20 !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  width: 2.25rem !important;
  height: 2.25rem !important;
  border-radius: 9999px !important;
  background: rgba(255, 255, 255, 0.92) !important;
  border: 1px solid rgba(23, 23, 23, 0.12) !important;
  color: #525252 !important;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
  transition: all 180ms ease !important;
  cursor: pointer !important;
}

.prestasi-detail-close:hover {
  background: #ffffff !important;
  color: #0f172a !important;
  transform: scale(1.06) !important;
}

.prestasi-detail-image {
  overflow: hidden;
  background: var(--blue-50);
  border-radius: 1.75rem 1.75rem 0 0;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 220px;
}

@media (min-width: 900px) {
  .prestasi-detail-image {
    min-height: 100%;
    border-radius: 1.75rem 0 0 1.75rem;
  }
}

.prestasi-detail-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.prestasi-detail-copy {
  display: flex;
  flex-direction: column;
  padding: clamp(1.25rem, 3vw, 2.25rem);
  overflow-y: auto;
}

.prestasi-detail-meta {
  display: grid;
  gap: 0.65rem;
  margin-top: 1.25rem;
}

@media (min-width: 600px) {
  .prestasi-detail-meta {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

.prestasi-detail-meta p,
.prestasi-detail-meta > div {
  border-radius: 1rem;
  background: #f8fafc;
  border: 1px solid rgba(23, 23, 23, 0.07);
  padding: 0.75rem 0.95rem;
}

.prestasi-detail-meta span {
  display: block;
  color: #114b9a;
  font-size: 0.6875rem;
  font-weight: 800;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.prestasi-detail-meta strong {
  display: block;
  margin-top: 0.25rem;
  color: #0f172a;
  font-size: 0.8125rem;
  line-height: 1.45;
  font-weight: 600;
}
</style>
