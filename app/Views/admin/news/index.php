<?php
use App\Core\Paginator;

$page = $page ?? 1;
$perPage = $perPage ?? 25;
$total = $total ?? 0;
$totalPages = $totalPages ?? 1;
$filters = $filters ?? [];
$categories = $categories ?? [];
$selectedCategories = $selectedCategories ?? [];
$layout = $layout ?? 'list';
$startItem = ($page - 1) * $perPage + 1;
$endItem = min($page * $perPage, $total);

$selectedCategory = !empty($selectedCategories) ? (int) $selectedCategories[0] : null;

$filterParams = array_filter([
    'q' => $filters['q'] ?? '',
    'status' => $filters['status'] ?? '',
    'category' => $selectedCategory ?: '',
    'layout' => $layout,
    'per_page' => $perPage !== 25 ? $perPage : '',
], static fn($v) => $v !== '' && $v !== null);
?>
<section class="mx-auto max-w-7xl">
  <header class="cms-header slide-in">
    <div>
      <p class="eyebrow">Admin CMS</p>
      <h1 class="section-title mt-3">View News</h1>
      <p class="mt-4 max-w-2xl text-base leading-7 text-neutral-600">Daftar berita dari <code>tbl_news</code>. Aksi hapus tetap memakai custom confirmation modal.</p>
    </div>
    <div class="cms-actions">
      <a href="<?= $url('admin.news.add') ?>" class="btn btn-primary">Add News</a>
    </div>
  </header>

  <div class="mt-6">
    <section class="admin-card news-filter-card p-4 sm:p-5 md:p-6">
      <form method="get" action="/admin/news" id="news-filter-form" class="news-filter-form">
        <input type="hidden" name="layout" value="<?= $e($layout) ?>">

        <div class="news-filter-grid">
          <!-- 1. Search Field -->
          <div class="news-filter-field is-search">
            <label for="news-search" class="news-filter-label">Pencarian</label>
            <div class="news-search-wrap">
              <svg class="news-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
              <input id="news-search" name="q" class="news-filter-input" placeholder="Cari judul, konten, kategori..." value="<?= $e($filters['q'] ?? '') ?>" autocomplete="off">
              <button type="button" id="news-search-clear" class="news-search-clear" title="Hapus pencarian" style="<?= !empty($filters['q']) ? '' : 'display: none;' ?>">×</button>
            </div>
          </div>

          <!-- 2. Status Field -->
          <div class="news-filter-field is-status">
            <label for="news-status-select" class="news-filter-label">Status</label>
            <select id="news-status-select" name="status" class="config-input w-full js-admin-custom-select news-filter-select">
              <option value="">Semua Status</option>
              <option value="draft" <?= ($filters['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
              <option value="published" <?= ($filters['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
              <option value="archived" <?= ($filters['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archived</option>
            </select>
          </div>

          <!-- 3. Category Field -->
          <div class="news-filter-field is-category">
            <label for="news-category-select" class="news-filter-label">Category</label>
            <select id="news-category-select" name="category" class="config-input w-full js-admin-custom-select news-filter-select">
              <option value="">Semua Kategori</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= (int) $cat['category_id'] ?>" <?= in_array((int) $cat['category_id'], $selectedCategories, true) ? 'selected' : '' ?>>
                  <?= $e($cat['category_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- 4. Show / Entries Field -->
          <div class="news-filter-field is-per-page">
            <label for="news-per-page-select" class="news-filter-label">Show Menampilkan</label>
            <select id="news-per-page-select" name="per_page" class="config-input w-full js-admin-custom-select news-filter-select">
              <?php foreach ([10, 25, 50, 100] as $opt): ?>
                <option value="<?= $opt ?>" <?= $opt === $perPage ? 'selected' : '' ?>><?= $opt ?> entries</option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </form>

      <!-- Bottom Bar: View Toggle & Summary -->
      <div class="news-filter-footer">
        <div class="news-filter-footer-left">
          <div class="news-view-toggle" role="group" aria-label="Layout mode">
            <a href="?<?= $e(http_build_query(array_merge($filterParams, ['layout' => 'list']))) ?>" class="news-view-toggle-btn <?= $layout === 'list' ? 'is-active' : '' ?>" title="Tampilan Tabel">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
              <span>List</span>
            </a>
            <a href="?<?= $e(http_build_query(array_merge($filterParams, ['layout' => 'grid']))) ?>" class="news-view-toggle-btn <?= $layout === 'grid' ? 'is-active' : '' ?>" title="Tampilan Grid">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
              <span>Grid</span>
            </a>
          </div>

          <?php if (!empty($filters['q']) || !empty($filters['status']) || !empty($selectedCategories)): ?>
            <a href="/admin/news?layout=<?= $e($layout) ?>" class="news-filter-reset-link" title="Reset filter ke awal">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
              <span>Reset Filter</span>
            </a>
          <?php endif; ?>
        </div>

        <div class="news-filter-footer-right">
          <?php if ($total > 0): ?>
            <div class="news-filter-summary">
              Menampilkan <span class="font-semibold text-neutral-800"><?= $startItem ?>-<?= $endItem ?></span> dari <span class="font-semibold text-neutral-800"><?= $total ?></span> berita.
            </div>
          <?php else: ?>
            <div class="news-filter-summary text-neutral-500">
              Tidak ada berita yang sesuai dengan filter.
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <?php if ($layout === 'list'): ?>
      <section class="admin-card news-table-card p-0 mt-5">
        <div class="admin-data-table-wrap">
          <table class="admin-table admin-data-table admin-data-table-news">
            <colgroup>
              <col class="admin-col-index">
              <col class="admin-col-title">
              <col class="admin-col-excerpt">
              <col class="admin-col-image">
              <col class="admin-col-meta">
              <col class="admin-col-status">
              <col class="admin-col-actions">
            </colgroup>
            <thead>
              <tr>
                <th>No.</th>
                <th>News Title</th>
                <th>News Short Content</th>
                <th>Photo</th>
                <th>Category</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="admin-news-list" data-ssr="true">
              <?php if (empty($items)): ?>
                <tr>
                  <td colspan="7" class="text-center text-sm text-neutral-500">Belum ada berita.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($items as $index => $item): ?>
                  <tr>
                    <td class="admin-cell-index"><?= $startItem + $index ?></td>
                    <td class="admin-cell-title">
                      <div class="admin-table-title">
                        <strong><?= $e($item['title']) ?></strong>
                        <p><?= $e(substr((string) ($item['date'] ?? ''), 0, 10)) ?></p>
                      </div>
                    </td>
                    <td class="admin-cell-excerpt">
                      <p class="admin-table-copy news-caption-cell"><?= $e($item['excerpt']) ?></p>
                    </td>
                    <td class="admin-cell-thumb">
                      <?php if (!empty($item['photo'])): ?>
                        <img src="<?= $e($item['photo']) ?>" class="table-thumb" alt="<?= $e($item['title']) ?>" onerror="this.style.display='none'">
                      <?php else: ?>
                        <span class="admin-thumb-placeholder">No image</span>
                      <?php endif; ?>
                    </td>
                    <td class="admin-cell-meta"><span class="cms-pill"><?= $e($item['category']) ?></span></td>
                    <td class="admin-cell-status">
                      <span class="status-pill <?= $e(strtolower($item['status'] ?? 'draft')) ?>">
                        <?= $e(ucfirst($item['status'] ?? 'draft')) ?>
                      </span>
                    </td>
                    <td class="admin-cell-actions">
                      <div class="admin-table-actions">
                        <a class="btn btn-secondary btn-sm" href="<?= $url('admin.news.edit', ['id' => (int) $item['id']]) ?>">
                          <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M13.25 3.75a2.1 2.1 0 1 1 2.97 2.97L7 16.94 3 17l.06-4 10.19-10.25Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="m12 5 3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                          Edit
                        </a>
                        <button class="btn btn-danger btn-sm" data-delete-news="<?= (int) $item['id'] ?>">
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
    <?php endif; ?>

    <?php if ($layout === 'grid'): ?>
      <div class="admin-news-grid mt-5" id="admin-news-list" data-ssr="true">
        <?php if (empty($items)): ?>
          <div class="admin-card p-8 text-center text-sm text-neutral-500">Belum ada berita.</div>
        <?php else: ?>
          <?php foreach ($items as $item): ?>
            <article class="admin-news-card">
              <?php if (!empty($item['photo'])): ?>
                <img src="<?= $e($item['photo']) ?>" alt="<?= $e($item['title']) ?>" onerror="this.remove()">
              <?php else: ?>
                <div class="admin-news-card-placeholder">No Image</div>
              <?php endif; ?>
              <div class="admin-news-card-content">
                <h2><?= $e($item['title']) ?></h2>
                <p><?= $e(mb_substr($item['excerpt'], 0, 120)) ?><?= mb_strlen($item['excerpt']) > 120 ? '...' : '' ?></p>
                <div class="admin-news-card-meta">
                  <span class="cms-pill"><?= $e($item['category']) ?></span>
                  <span class="status-pill <?= $e(strtolower($item['status'] ?? 'draft')) ?>"><?= $e(ucfirst($item['status'] ?? 'draft')) ?></span>
                  <span class="text-xs text-neutral-500"><?= $e(substr((string) ($item['date'] ?? ''), 0, 10)) ?></span>
                </div>
                <div class="admin-news-card-actions">
                  <a class="btn btn-secondary btn-sm" href="<?= $url('admin.news.edit', ['id' => (int) $item['id']]) ?>">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M13.25 3.75a2.1 2.1 0 1 1 2.97 2.97L7 16.94 3 17l.06-4 10.19-10.25Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="m12 5 3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Edit
                  </a>
                  <button class="btn btn-danger btn-sm" data-delete-news="<?= (int) $item['id'] ?>">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M4.5 5.5h11M8 5.5V4.25A1.25 1.25 0 0 1 9.25 3h1.5A1.25 1.25 0 0 1 12 4.25V5.5m-5.5 0 .5 10A1.25 1.25 0 0 0 8.25 16.75h3.5A1.25 1.25 0 0 0 13 15.5l.5-10M8.5 8.25v5M11.5 8.25v5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Delete
                  </button>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <?php if ($totalPages > 1): ?>
      <nav class="admin-pagination mt-5" aria-label="Pagination berita" data-ssr="true">
        <?php if ($page > 1): ?>
          <a class="pager-button" href="<?= $url('admin.news') ?>?<?= $e(Paginator::buildQuery($page - 1, $filterParams)) ?>">Sebelumnya</a>
        <?php else: ?>
          <span class="pager-button" aria-disabled="true">Sebelumnya</span>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <?php if ($i === $page): ?>
            <span class="pager-button is-active" aria-current="page"><?= $i ?></span>
          <?php else: ?>
            <a class="pager-button" href="<?= $url('admin.news') ?>?<?= $e(Paginator::buildQuery($i, $filterParams)) ?>"><?= $i ?></a>
          <?php endif; ?>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
          <a class="pager-button" href="<?= $url('admin.news') ?>?<?= $e(Paginator::buildQuery($page + 1, $filterParams)) ?>">Berikutnya</a>
        <?php else: ?>
          <span class="pager-button" aria-disabled="true">Berikutnya</span>
        <?php endif; ?>
      </nav>
    <?php endif; ?>
  </div>
</section>
