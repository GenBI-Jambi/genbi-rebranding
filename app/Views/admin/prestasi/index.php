<?php
use App\Core\Paginator;

if (!function_exists('formatCategoryDisplay')) {
    function formatCategoryDisplay(?string $category, int $maxWords = 3): string {
        $category = trim((string) $category);
        if ($category === '') {
            return '-';
        }
        $words = preg_split('/\s+/', $category);
        if (count($words) > $maxWords) {
            return implode(' ', array_slice($words, 0, $maxWords)) . '...';
        }
        return $category;
    }
}

$page = $page ?? 1;
$perPage = $perPage ?? 25;
$total = $total ?? 0;
$totalPages = $totalPages ?? 1;
$filters = $filters ?? [];
$activeQ = $filters['q'] ?? '';
$activeCategory = $filters['category'] ?? '';
$activeYear = $filters['year'] ?? '';
$activeStatus = $filters['status'] ?? '';
$startItem = ($page - 1) * $perPage + 1;
$endItem = min($page * $perPage, $total);
$filterParams = array_filter([
    'q' => $activeQ,
    'category' => $activeCategory,
    'year' => $activeYear,
    'status' => $activeStatus,
    'per_page' => $perPage !== 25 ? $perPage : '',
], static fn($v) => $v !== '' && $v !== null);

$standardCategories = [
    'Lomba Bank Indonesia',
    'Karya Tulis Ilmiah',
    'Belmawa',
    'Satria Data',
    'ONMIPA',
    'Pimnas',
    'PKM',
];
$prestasiCategories = $standardCategories;
if ($activeCategory !== '' && !in_array($activeCategory, $prestasiCategories, true)) {
    $prestasiCategories[] = $activeCategory;
}
?>
<section class="mx-auto max-w-7xl">
  <header class="cms-header slide-in">
    <div>
      <p class="eyebrow">Admin CMS</p>
      <h1 class="section-title mt-3">View Prestasi</h1>
      <p class="mt-4 max-w-2xl text-base leading-7 text-neutral-600">Daftar prestasi anggota GenBI. Aksi hapus memakai custom confirmation modal.</p>
    </div>
    <div class="cms-actions">
      <a href="<?= $url('admin/prestasi-token') ?>" class="btn btn-secondary">Buat Link Form Prestasi</a>
      <a href="<?= $url('admin.prestasi.add') ?>" class="btn btn-primary">Add Prestasi</a>
    </div>
  </header>

  <div class="mt-6">
    <section class="admin-card news-filter-card p-4 sm:p-5 md:p-6">
      <form method="get" action="/admin/prestasi" id="prestasi-filter-form" class="news-filter-form">
        <div class="news-filter-grid">
          <!-- 1. Search Field -->
          <div class="news-filter-field is-search">
            <label for="prestasi-search" class="news-filter-label">Pencarian</label>
            <div class="news-search-wrap">
              <svg class="news-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
              <input id="prestasi-search" name="q" class="news-filter-input" placeholder="Cari judul, nama, kategori, penyelenggara..." value="<?= $e($activeQ) ?>" autocomplete="off">
              <button type="button" id="prestasi-search-clear" class="news-search-clear" title="Hapus pencarian" style="<?= !empty($activeQ) ? '' : 'display: none;' ?>">×</button>
            </div>
          </div>

          <!-- 2. Status Field -->
          <div class="news-filter-field is-status">
            <label for="prestasi-status-select" class="news-filter-label">Status</label>
            <select id="prestasi-status-select" name="status" class="config-input w-full js-admin-custom-select news-filter-select">
              <option value="">Semua Status</option>
              <option value="published" <?= $activeStatus === 'published' ? 'selected' : '' ?>>Published</option>
              <option value="draft" <?= $activeStatus === 'draft' ? 'selected' : '' ?>>Draft</option>
              <option value="archived" <?= $activeStatus === 'archived' ? 'selected' : '' ?>>Archived</option>
            </select>
          </div>

          <!-- 3. Category Field -->
          <div class="news-filter-field is-category">
            <label for="prestasi-category-select" class="news-filter-label">Kategori</label>
            <select id="prestasi-category-select" name="category" class="config-input w-full js-admin-custom-select news-filter-select">
              <option value="">Semua Kategori</option>
              <?php foreach ($prestasiCategories as $cat): ?>
                <option value="<?= $e($cat) ?>" <?= $cat === $activeCategory ? 'selected' : '' ?> title="<?= $e($cat) ?>">
                  <?= $e(formatCategoryDisplay($cat, 3)) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- 4. Show / Entries Field -->
          <div class="news-filter-field is-per-page">
            <label for="prestasi-per-page-select" class="news-filter-label">Show Menampilkan</label>
            <select id="prestasi-per-page-select" name="per_page" class="config-input w-full js-admin-custom-select news-filter-select">
              <?php foreach ([10, 25, 50, 100] as $opt): ?>
                <option value="<?= $opt ?>" <?= $opt === $perPage ? 'selected' : '' ?>><?= $opt ?> entries</option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </form>

      <!-- Bottom Bar: Reset link & Summary -->
      <div class="news-filter-footer">
        <div class="news-filter-footer-left">
          <?php if (!empty($activeQ) || !empty($activeStatus) || !empty($activeCategory)): ?>
            <a href="/admin/prestasi" class="news-filter-reset-link" title="Reset filter ke awal">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
              <span>Reset Filter</span>
            </a>
          <?php endif; ?>
        </div>

        <div class="news-filter-footer-right">
          <?php if ($total > 0): ?>
            <div class="news-filter-summary">
              Menampilkan <span class="font-semibold text-neutral-800"><?= $startItem ?>-<?= $endItem ?></span> dari <span class="font-semibold text-neutral-800"><?= $total ?></span> prestasi.
            </div>
          <?php else: ?>
            <div class="news-filter-summary text-neutral-500">
              Tidak ada data prestasi yang sesuai dengan filter.
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <section class="admin-card news-table-card p-0 mt-5">
      <div class="admin-data-table-wrap">
        <table class="admin-table admin-data-table admin-data-table-prestasi">
          <colgroup>
            <col class="admin-col-index">
            <col class="admin-col-title">
            <col class="admin-col-person">
            <col class="admin-col-category">
            <col class="admin-col-year">
            <col class="admin-col-institution">
            <col class="admin-col-status">
            <col class="admin-col-actions">
          </colgroup>
          <thead>
            <tr>
              <th class="admin-col-index text-center align-middle">No.</th>
              <th class="admin-col-title text-left align-middle">Judul</th>
              <th class="admin-col-person text-center align-middle">Nama Anggota</th>
              <th class="admin-col-category text-center align-middle">Kategori</th>
              <th class="admin-col-year text-center align-middle">Tahun</th>
              <th class="admin-col-institution text-center align-middle">Penyelenggara</th>
              <th class="admin-col-status text-center align-middle">Status</th>
              <th class="admin-col-actions text-center align-middle">Action</th>
            </tr>
          </thead>
          <tbody id="admin-prestasi-list" data-ssr="true">
            <?php if (empty($items)): ?>
              <tr data-empty-row="1">
                <td colspan="8" class="text-center text-sm text-neutral-500 py-8">Belum ada data prestasi.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($items as $index => $item): ?>
                <tr>
                  <td class="admin-cell-index text-center align-middle"><?= $startItem + $index ?></td>
                  <td class="admin-cell-title align-middle">
                    <div class="admin-table-media">
                      <?php if (!empty($item['image'])): ?>
                        <img src="<?= $e($item['image']) ?>" class="table-thumb rounded" alt="<?= $e($item['title'] ?? '') ?>" />
                      <?php else: ?>
                        <span class="admin-thumb-placeholder">No image</span>
                      <?php endif; ?>
                      <div class="admin-table-title">
                        <strong><?= $e($item['title'] ?? '') ?></strong>
                        <p><?= $e(mb_substr($item['description'] ?? '', 0, 90)) ?></p>
                      </div>
                    </div>
                  </td>
                  <td class="admin-cell-person text-center align-middle"><?= $e($item['name'] ?? '') ?></td>
                  <td class="admin-cell-category text-center align-middle" title="<?= $e($item['category'] ?? '') ?>">
                    <span class="cms-pill"><?= $e(formatCategoryDisplay($item['category'] ?? '', 3)) ?></span>
                  </td>
                  <td class="admin-cell-year text-center align-middle"><?= $e($item['year'] ?? '') ?></td>
                  <td class="admin-cell-institution text-center align-middle"><?= $e($item['institution'] ?? '') ?></td>
                  <td class="admin-cell-status text-center align-middle">
                    <?php $itemStatus = strtolower($item['status'] ?? 'draft'); ?>
                    <span class="cms-pill <?= $itemStatus === 'published' ? 'cms-pill-green' : ($itemStatus === 'draft' ? 'cms-pill-yellow' : '') ?>">
                      <?= $e(ucfirst($itemStatus)) ?>
                    </span>
                  </td>
                  <td class="admin-cell-actions text-center align-middle">
                    <div class="admin-table-actions justify-center">
                      <button class="btn btn-outline btn-sm whitespace-nowrap shrink-0" data-detail-prestasi="<?= (int) $item['id'] ?>">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M2.75 10s2.75-4.75 7.25-4.75S17.25 10 17.25 10 14.5 14.75 10 14.75 2.75 10 2.75 10Z" stroke="currentColor" stroke-width="1.5"/><circle cx="10" cy="10" r="2.25" stroke="currentColor" stroke-width="1.5"/></svg>
                        Detail
                      </button>
                      <?php if ($itemStatus !== 'published'): ?>
                        <button class="btn btn-primary btn-sm whitespace-nowrap shrink-0" data-approve-prestasi="<?= (int) $item['id'] ?>">
                          Approve
                        </button>
                      <?php endif; ?>
                      <a class="btn btn-secondary btn-sm whitespace-nowrap shrink-0" href="<?= $url('admin.prestasi.edit', ['id' => (int) $item['id']]) ?>">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M13.25 3.75a2.1 2.1 0 1 1 2.97 2.97L7 16.94 3 17l.06-4 10.19-10.25Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="m12 5 3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        Edit
                      </a>
                      <button class="btn btn-danger btn-sm whitespace-nowrap shrink-0" data-delete-prestasi="<?= (int) $item['id'] ?>">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M4.5 5.5h11M8 5.5V4.25A1.25 1.25 0 0 1 9.25 3h1.5A1.25 1.25 0 0 1 12 4.25V5.5m-5.5 0 .5 10A1.25 1.25 0 0 0 8.25 16.75h3.5A1.25 1.25 0 0 0 13 15.5l.5-10M8.5 8.25v5M11.5 8.25v5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Delete
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <?php if ($totalPages > 1): ?>
      <nav class="admin-pagination mt-5" aria-label="Pagination prestasi" data-ssr="true">
        <?php if ($page > 1): ?>
          <a class="pager-button" href="<?= $url('admin.prestasi') ?>?<?= $e(Paginator::buildQuery($page - 1, $filterParams)) ?>">Sebelumnya</a>
        <?php else: ?>
          <span class="pager-button" aria-disabled="true">Sebelumnya</span>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <?php if ($i === $page): ?>
            <span class="pager-button is-active" aria-current="page"><?= $i ?></span>
          <?php else: ?>
            <a class="pager-button" href="<?= $url('admin.prestasi') ?>?<?= $e(Paginator::buildQuery($i, $filterParams)) ?>"><?= $i ?></a>
          <?php endif; ?>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
          <a class="pager-button" href="<?= $url('admin.prestasi') ?>?<?= $e(Paginator::buildQuery($page + 1, $filterParams)) ?>">Berikutnya</a>
        <?php else: ?>
          <span class="pager-button" aria-disabled="true">Berikutnya</span>
        <?php endif; ?>
      </nav>
    <?php endif; ?>
  </div>
</section>
