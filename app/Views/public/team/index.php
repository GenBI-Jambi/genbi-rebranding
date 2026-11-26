<?php
use App\Core\Paginator;

$page = $page ?? 1;
$perPage = $perPage ?? 12;
$total = $total ?? 0;
$totalPages = $totalPages ?? 1;
$members = $members ?? [];
$filters = $filters ?? [];
$filterOptions = $filterOptions ?? ['divisions' => [], 'campuses' => [], 'years' => []];
$activeQ = $filters['q'] ?? '';
$activeDivision = $filters['division'] ?? '';
$activeCampus = $filters['campus'] ?? '';
$activeYear = $filters['year'] ?? '';
$startItem = ($page - 1) * $perPage + 1;
$endItem = min($page * $perPage, $total);
$filterParams = array_filter([
    'q' => $activeQ,
    'division' => $activeDivision,
    'campus' => $activeCampus,
    'year' => $activeYear,
], static fn($v) => $v !== '' && $v !== null);
?>
<section class="public-inner-hero py-16 md:py-24">
  <div class="site-container fade-up">
    <p class="eyebrow">Pengurus & Anggota</p>
    <h1 class="page-title mt-5">Pengurus dan Anggota GenBI Jambi.</h1>
    <p class="lead mt-7">Direktori pengurus dan anggota GenBI Provinsi Jambi. Gunakan filter untuk menemukan anggota berdasarkan divisi, komisariat, atau tahun.</p>
  </div>
</section>
<section class="bg-cream py-12 md:py-16">
  <div class="site-container">
    <form class="fade-up mb-8" method="get" action="/team" id="team-filter-form">
      <div class="grid gap-3 md:grid-cols-[1fr_180px_180px_120px]">
        <input id="team-search" name="q" class="input-soft" placeholder="Cari nama, jabatan, divisi..." value="<?= $e($activeQ) ?>" />
        <select name="division" id="team-division" class="input-soft" onchange="this.form.submit()">
          <option value="">Semua Divisi</option>
          <?php foreach ($filterOptions['divisions'] ?? [] as $div): ?>
            <option value="<?= $e($div) ?>" <?= $div === $activeDivision ? 'selected' : '' ?>><?= $e($div) ?></option>
          <?php endforeach; ?>
        </select>
        <select name="campus" id="team-campus" class="input-soft" onchange="this.form.submit()">
          <option value="">Semua Kampus</option>
          <?php foreach ($filterOptions['campuses'] ?? [] as $campus): ?>
            <option value="<?= $e($campus) ?>" <?= $campus === $activeCampus ? 'selected' : '' ?>><?= $e($campus) ?></option>
          <?php endforeach; ?>
        </select>
        <select name="year" id="team-year" class="input-soft" onchange="this.form.submit()">
          <option value="">Semua Tahun</option>
          <?php foreach ($filterOptions['years'] ?? [] as $year): ?>
            <option value="<?= $e((string) $year) ?>" <?= (string) $year === $activeYear ? 'selected' : '' ?>><?= $e((string) $year) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <noscript><button type="submit" class="btn btn-primary mt-3">Terapkan Filter</button></noscript>
    </form>
    <div class="fade-up mb-4 flex flex-wrap items-center justify-between gap-3">
      <span class="text-sm text-neutral-600" id="team-count">
        <?php if ($total > 0): ?>
          Menampilkan <?= $startItem ?>–<?= $endItem ?> dari <?= $total ?> anggota.
        <?php else: ?>
          Belum ada data anggota yang tersedia.
        <?php endif; ?>
      </span>
      <div class="flex gap-1" role="group" aria-label="Pilihan tampilan data">
        <button id="team-layout-grid" class="chip is-active" type="button" aria-label="Tampilan Kisi">Kisi</button>
        <button id="team-layout-list" class="chip" type="button" aria-label="Tampilan Daftar">Daftar</button>
      </div>
    </div>
    <div class="fade-up team-public-grid" id="team-list" data-ssr="true">
      <?php if (!empty($members)): ?>
        <?php foreach ($members as $member): ?>
          <article class="team-public-card cursor-pointer group" data-id="<?= $e((string)($member['id'] ?? '')) ?>" tabindex="0" role="button" aria-label="Lihat profil <?= $e($member['name'] ?? '') ?>" title="Klik untuk melihat profil lengkap">
            <div class="team-public-photo">
              <?php if (!empty($member['photo'])): ?>
                <img src="<?= $e($member['photo']) ?>" alt="<?= $e($member['name']) ?>" loading="lazy" />
              <?php else: ?>
                <span><?= $e(mb_substr($member['name'] ?? '', 0, 2)) ?></span>
              <?php endif; ?>
            </div>
            <div>
              <p class="eyebrow"><?= $e(!empty($member['year']) ? 'Tahun ' . $member['year'] : 'GenBI Jambi') ?></p>
              <h3 class="serif mt-2 text-2xl font-semibold tracking-tight text-neutral-950 group-hover:text-[#114b9a] transition-colors"><?= $e($member['name'] ?? '') ?></h3>
              <p class="mt-2 text-sm font-semibold text-blue-800"><?= $e($member['role'] ?? 'Anggota') ?></p>
              <p class="mt-3 text-sm leading-6 text-neutral-600"><?= $e($member['division'] ?? '-') ?></p>
              <p class="text-sm leading-6 text-neutral-500"><?= $e($member['campus'] ?? '-') ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="rounded-2xl border border-neutral-900/10 bg-white p-8 text-center text-sm text-neutral-600 col-span-full">
          Belum ada data anggota yang cocok dengan filter.
        </div>
      <?php endif; ?>
    </div>
    <?php if ($totalPages > 1): ?>
      <nav class="public-pagination mt-8" id="team-pagination" aria-label="Navigasi halaman anggota" data-ssr="true">
        <?php if ($page > 1): ?>
          <a class="pager-button" href="/team?<?= $e(Paginator::buildQuery($page - 1, $filterParams)) ?>">Sebelumnya</a>
        <?php else: ?>
          <span class="pager-button" aria-disabled="true">Sebelumnya</span>
        <?php endif; ?>
        <?php
        $paginationItems = [];
        if ($totalPages <= 7) {
            $paginationItems = range(1, $totalPages);
        } elseif ($page <= 4) {
            $paginationItems = [1, 2, 3, 4, 5, '...', $totalPages];
        } elseif ($page >= $totalPages - 3) {
            $paginationItems = [1, '...', $totalPages - 4, $totalPages - 3, $totalPages - 2, $totalPages - 1, $totalPages];
        } else {
            $paginationItems = [1, '...', $page - 1, $page, $page + 1, '...', $totalPages];
        }
        ?>
        <?php foreach ($paginationItems as $item): ?>
          <?php if ($item === '...'): ?>
            <span class="pager-ellipsis" aria-hidden="true">&hellip;</span>
          <?php elseif ($item === $page): ?>
            <span class="pager-button is-active" aria-current="page"><?= $item ?></span>
          <?php else: ?>
            <a class="pager-button" href="/team?<?= $e(Paginator::buildQuery((int) $item, $filterParams)) ?>"><?= $item ?></a>
          <?php endif; ?>
        <?php endforeach; ?>
        <?php if ($page < $totalPages): ?>
          <a class="pager-button" href="/team?<?= $e(Paginator::buildQuery($page + 1, $filterParams)) ?>">Berikutnya</a>
        <?php else: ?>
          <span class="pager-button" aria-disabled="true">Berikutnya</span>
        <?php endif; ?>
      </nav>
    <?php else: ?>
      <div class="public-pagination mt-8" id="team-pagination" aria-label="Navigasi halaman anggota"></div>
    <?php endif; ?>
  </div>
</section>
<div id="member-modal" class="public-fixed-modal hidden"></div>

<script>
window.teamMembersData = <?= json_encode($members, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?> || [];
</script>

<style>
/* ==========================================================================
   PERBAIKAN FOTO CARD ANGGOTA (TAMPAK SETENGAH BADAN PAS DI TENGAH)
   ========================================================================== */
.team-public-card {
  cursor: pointer !important;
  transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease !important;
  user-select: none;
  position: relative;
}

.team-public-card:hover,
.team-public-card:focus-visible {
  transform: translateY(-5px) !important;
  border-color: rgba(17, 75, 154, 0.35) !important;
  box-shadow: 0 18px 40px -12px rgba(17, 75, 154, 0.15) !important;
  outline: none;
}

.team-public-photo {
  width: 100% !important;
  aspect-ratio: 4 / 5 !important;
  border-radius: 1.25rem !important;
  overflow: hidden !important;
  background-color: #f1f5f9 !important;
  position: relative !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
}

.team-public-photo img {
  width: 100% !important;
  height: 100% !important;
  object-fit: cover !important;
  object-position: center 20% !important; /* Fokus kepala hingga dada/pinggang setengah badan, horizontal pas di tengah */
  transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1) !important;
}

.team-public-card:hover .team-public-photo img,
.team-public-card:focus-visible .team-public-photo img {
  transform: scale(1.04) !important;
}

.team-public-photo span {
  font-size: 2.25rem !important;
  font-weight: 700 !important;
  color: #114b9a !important;
  background: #e0f2fe !important;
  width: 100% !important;
  height: 100% !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
}

/* List view row */
.team-public-row {
  cursor: pointer !important;
  transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease !important;
  user-select: none;
}

.team-public-row:hover,
.team-public-row:focus-visible {
  transform: translateY(-2px) !important;
  border-color: rgba(17, 75, 154, 0.35) !important;
  box-shadow: 0 10px 28px -8px rgba(17, 75, 154, 0.12) !important;
  outline: none;
}

.team-public-row-photo {
  width: 4.75rem !important;
  height: 6rem !important;
  aspect-ratio: 4 / 5 !important;
  border-radius: 1rem !important;
  overflow: hidden !important;
  background-color: #f1f5f9 !important;
  flex-shrink: 0 !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
}

.team-public-row-photo img {
  width: 100% !important;
  height: 100% !important;
  object-fit: cover !important;
  object-position: center 20% !important;
}

/* Modal detail styling - Minimalist & Editorial */
.public-fixed-modal {
  position: fixed !important;
  inset: 0 !important;
  width: 100vw !important;
  height: 100vh !important;
  height: 100dvh !important;
  z-index: 99999 !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  box-sizing: border-box !important;
  overflow-y: auto !important;
  background: rgba(15, 23, 42, 0.55) !important;
  padding: clamp(1rem, 3vw, 1.5rem) !important;
  backdrop-filter: blur(8px) !important;
}

.public-fixed-modal.hidden {
  display: none !important;
}

.team-detail-modal-panel {
  position: relative !important;
  width: min(100%, 560px) !important;
  margin: auto !important;
  max-height: calc(100vh - 2.5rem) !important;
  overflow-y: auto !important;
  border-radius: 1.25rem !important;
  border: 1px solid rgba(23, 23, 23, 0.08) !important;
  background: #ffffff !important;
  padding: clamp(1.25rem, 3vw, 1.75rem) !important;
  box-shadow: 0 20px 45px -12px rgba(17, 75, 154, 0.18), 0 0 0 1px rgba(0, 0, 0, 0.04) !important;
}

.team-modal-photo-box {
  width: 100% !important;
  aspect-ratio: 4 / 5 !important;
  border-radius: 0.875rem !important;
  overflow: hidden !important;
  background-color: #f8fafc !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
}

.team-modal-photo-box img {
  width: 100% !important;
  height: 100% !important;
  object-fit: cover !important;
  object-position: center 20% !important;
}

.team-modal-link {
  display: inline-flex !important;
  align-items: center !important;
  gap: 0.375rem !important;
  padding: 0.35rem 0.65rem !important;
  border-radius: 0.5rem !important;
  border: 1px solid rgba(23, 23, 23, 0.1) !important;
  background-color: #fafafa !important;
  color: #404040 !important;
  font-size: 0.75rem !important;
  font-weight: 500 !important;
  transition: all 0.2s ease !important;
  text-decoration: none !important;
}

.team-modal-link:hover {
  background-color: #114b9a !important;
  border-color: #114b9a !important;
  color: #ffffff !important;
}

.team-modal-link svg {
  flex-shrink: 0 !important;
}

/* Team Member Credential Info Box */
.team-credential-box {
  background: #f8fafc !important;
  border: 1px solid rgba(23, 23, 23, 0.08) !important;
  border-radius: 1rem !important;
  padding: 0.85rem 1rem !important;
  display: flex !important;
  flex-direction: column !important;
  gap: 0.65rem !important;
}

.team-credential-item {
  display: flex !important;
  align-items: center !important;
  gap: 0.75rem !important;
}

.team-credential-icon {
  width: 2.15rem !important;
  height: 2.15rem !important;
  border-radius: 0.625rem !important;
  background: #eef4fb !important;
  color: #114b9a !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  flex-shrink: 0 !important;
}

.team-credential-icon svg {
  width: 1.1rem !important;
  height: 1.1rem !important;
  stroke-width: 2 !important;
}

.team-credential-text {
  min-width: 0 !important;
  flex: 1 1 0% !important;
}

.team-credential-label {
  display: block !important;
  font-size: 0.6875rem !important;
  font-weight: 500 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.04em !important;
  color: #64748b !important;
  line-height: 1.2 !important;
}

.team-credential-val {
  display: block !important;
  font-size: 0.875rem !important;
  font-weight: 600 !important;
  color: #0f172a !important;
  line-height: 1.35 !important;
  margin-top: 0.125rem !important;
}

.team-credential-divider {
  height: 1px !important;
  background: rgba(226, 232, 240, 0.8) !important;
  width: 100% !important;
}
</style>
