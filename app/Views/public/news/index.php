<?php
use App\Core\Paginator;

$page = $page ?? 1;
$perPage = $perPage ?? 12;
$total = $total ?? 0;
$totalPages = $totalPages ?? 1;
$filters = $filters ?? [];
$activeQ = $filters['q'] ?? '';
$activeCategory = $filters['category'] ?? '';
$recentPosts = $recentPosts ?? [];
$startItem = ($page - 1) * $perPage + 1;
$endItem = min($page * $perPage, $total);
?>
<section class="public-inner-hero py-16 md:py-24">
  <div class="article-container">
    <p class="eyebrow">Berita</p>
    <h1 class="page-title mt-5">Berita GenBI Jambi.</h1>
    <p class="lead mt-7">Layout dibuat seperti publikasi editorial. Pengguna membaca daftar berita dengan ritme yang nyaman, bukan grid kartu yang padat.</p>
  </div>
</section>

<section class="bg-cream py-12 md:py-16">
  <div class="site-container">
    
    <!-- Form Pencarian (Smooth Tanpa Tombol Cari) -->
    <form class="news-filter mb-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-3" method="get" action="/news" id="news-filter-form">
      <?php if ($activeCategory !== ''): ?>
        <input type="hidden" name="category" value="<?= $e($activeCategory) ?>" />
      <?php endif; ?>
      <div class="relative flex-1">
        <input
          id="news-search"
          name="q"
          type="text"
          class="input-soft"
          placeholder="Cari berita berdasarkan judul..."
          value="<?= $e($activeQ) ?>"
          aria-label="Cari berita"
          autocomplete="off"
        />
      </div>
      <a
        href="/news"
        id="news-reset-link"
        class="btn btn-secondary text-sm flex items-center justify-center whitespace-nowrap <?= ($activeQ !== '' || $activeCategory !== '') ? '' : 'hidden' ?>"
      >
        Reset Filter
      </a>
      <div id="news-category" class="hidden"></div>
    </form>

    <!-- Info Jumlah & Status Filter -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3 text-sm text-neutral-600" id="news-count">
      <?php if ($total > 0): ?>
        <div>Menampilkan <?= $startItem ?>–<?= $endItem ?> dari <?= $total ?> berita<?= $activeQ !== '' ? ' untuk pencarian judul "' . $e($activeQ) . '"' : '' ?><?= $activeCategory !== '' ? ' (Kategori: ' . $e($activeCategory) . ')' : '' ?>.</div>
      <?php else: ?>
        <div>Belum ada berita yang tersedia<?= $activeQ !== '' ? ' dengan judul "' . $e($activeQ) . '"' : '' ?>.</div>
      <?php endif; ?>
    </div>
      
    <!-- Layout Berita & Sidebar -->
    <div class="news-layout-grid">

      <!-- Kolom Kiri: Daftar Berita -->
      <div id="news-list" data-ssr="true" style="width:100%; min-width:0;">
        <h2 class="sr-only">Daftar Berita</h2>

        <?php if (!empty($items)): ?>
          <?php foreach ($items as $index => $item): ?>
            <article>
              <a
                data-transition
                href="/news/<?= rawurlencode((string) $item['slug']) ?>"
                class="article-link <?= $index === 0 ? 'pt-0 border-t-0' : '' ?>"
              >
                <div class="grid gap-5 md:grid-cols-[170px_1fr] md:items-start">
                  <div class="aspect-[4/3] overflow-hidden rounded-2xl bg-blue-50">
                    <?php if (!empty($item['image'])): ?>
                      <img
                        src="<?= $e($item['image']) ?>"
                        alt="<?= $e($item['title']) ?>"
                        class="h-full w-full object-cover"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='https://genbijambi.com/public/uploads/slider-1.png';"
                      />
                    <?php endif; ?>
                  </div>

                  <div>
                    <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-neutral-500">
                      <span class="text-blue-800"><?= $e($item['category']) ?></span>
                      <span><?= $e(substr((string) ($item['date'] ?? ''), 0, 10)) ?></span>
                    </div>

                    <h3 class="serif mt-3 text-3xl font-semibold leading-tight tracking-tight text-neutral-950">
                      <?= $e($item['title']) ?>
                    </h3>

                    <p class="mt-4 text-base leading-7 text-neutral-600">
                      <?= $e($item['excerpt']) ?>
                    </p>
                    <span class="btn btn-secondary mt-5 w-fit">Detail</span>
                  </div>
                </div>
              </a>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="rounded-2xl border border-neutral-900/10 bg-white p-8 text-center text-sm text-neutral-600">
            <p class="font-medium text-neutral-800">Belum ada data berita yang tersedia.</p>
            <div class="mt-4">
              <a href="/news" class="btn btn-secondary text-xs">Reset Semua Filter</a>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Kolom Kanan: Sidebar Kategori & Berita Pos Terbaru -->
      <aside class="news-sidebar space-y-6" style="width:100%;">
        
        <!-- Card Kategori -->
        <div class="rounded-2xl border border-neutral-900/10 bg-white p-6">
          <h2 class="text-sm font-bold tracking-[0.15em] text-neutral-900">
            KATEGORI
          </h2>

          <div class="mt-4 border-t border-neutral-200"></div>

          <ul class="mt-3 space-y-1">
            <li>
              <a
                href="/news<?= $activeQ !== '' ? '?q=' . rawurlencode($activeQ) : '' ?>"
                class="block rounded-lg px-3 py-2 text-sm transition hover:bg-blue-50 hover:text-blue-800 <?= $activeCategory === '' ? 'bg-blue-50 text-blue-800 font-semibold' : 'text-neutral-600' ?>"
              >
                Semua Berita
              </a>
            </li>

            <?php foreach (($categories ?? []) as $category): ?>
              <?php
                $categoryName = (string) ($category['category_name'] ?? '');
                $categoryId = (string) ($category['category_id'] ?? '');
                $isCategoryActive = ((string) $activeCategory === $categoryId || (string) $activeCategory === $categoryName);
              ?>

              <li>
                <a
                  href="/news?<?= $e(http_build_query(array_filter([
                    'category' => $categoryName,
                    'q' => $activeQ,
                  ], static fn ($value) => $value !== '' && $value !== null))) ?>"
                  class="block rounded-lg px-3 py-2 text-sm transition hover:bg-blue-50 hover:text-blue-800 <?= $isCategoryActive ? 'bg-blue-50 text-blue-800 font-semibold' : 'text-neutral-600' ?>"
                >
                  <?= $e($categoryName) ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>

          <?php if (empty($categories)): ?>
            <p class="mt-4 text-sm text-neutral-500">
              Belum ada kategori tersedia.
            </p>
          <?php endif; ?>
        </div>

        <!-- Card Berita Pos Terbaru -->
        <div class="rounded-2xl border border-neutral-900/10 bg-white p-6">
          <h2 class="text-sm font-bold tracking-[0.15em] text-neutral-900">
            BERITA POS TERBARU
          </h2>

          <div class="mt-4 border-t border-neutral-200"></div>

          <div class="mt-4 space-y-4">
            <?php if (!empty($recentPosts)): ?>
              <?php foreach ($recentPosts as $rIndex => $recentItem): ?>
                <?php
                  $rTitle = (string) ($recentItem['title'] ?? $recentItem['news_title'] ?? '');
                  $rSlug = (string) ($recentItem['slug'] ?? '');
                  $rDate = (string) ($recentItem['date'] ?? '');
                  $rCategory = (string) ($recentItem['category'] ?? '');
                  $rDateFormatted = $rDate !== '' ? date('d M Y', strtotime($rDate)) : '';
                ?>
                <article class="<?= $rIndex > 0 ? 'pt-3 border-t border-neutral-100' : '' ?>">
                  <a
                    data-transition
                    href="/news/<?= rawurlencode($rSlug) ?>"
                    class="group block"
                  >
                    <div class="flex items-center gap-2 text-xs font-semibold text-neutral-500">
                      <span class="text-blue-800"><?= $e($rCategory) ?></span>
                      <?php if ($rDateFormatted !== ''): ?>
                        <span>•</span>
                        <span><?= $e($rDateFormatted) ?></span>
                      <?php endif; ?>
                    </div>
                    <h3 class="serif mt-1 text-base font-semibold leading-snug text-neutral-950 group-hover:text-blue-800 transition">
                      <?= $e($rTitle) ?>
                    </h3>
                  </a>
                </article>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-sm text-neutral-500">
                Belum ada berita terbaru.
              </p>
            <?php endif; ?>
          </div>
        </div>

      </aside>

    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
      <nav class="public-pagination mt-8" id="news-pagination" aria-label="Pagination berita" data-ssr="true">
        <?php
          $filterParams = array_filter([
            'q' => $activeQ,
            'category' => $activeCategory,
          ], static fn($v) => $v !== '' && $v !== null);
        ?>
        <?php if ($page > 1): ?>
          <a class="pager-button" href="/news?<?= $e(Paginator::buildQuery($page - 1, $filterParams)) ?>">Sebelumnya</a>
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
            <a class="pager-button" href="/news?<?= $e(Paginator::buildQuery((int) $item, $filterParams)) ?>"><?= $item ?></a>
          <?php endif; ?>
        <?php endforeach; ?>
        <?php if ($page < $totalPages): ?>
          <a class="pager-button" href="/news?<?= $e(Paginator::buildQuery($page + 1, $filterParams)) ?>">Berikutnya</a>
        <?php else: ?>
          <span class="pager-button" aria-disabled="true">Berikutnya</span>
        <?php endif; ?>
      </nav>
    <?php else: ?>
      <div class="public-pagination mt-8" id="news-pagination" aria-label="Pagination berita"></div>
    <?php endif; ?>

  </div>
</section>

<style>
.news-layout-grid {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 280px;
  gap: 32px;
  align-items: start;
  width: 100%;
}
@media (max-width: 900px) {
  .news-layout-grid {
    grid-template-columns: 1fr;
    gap: 24px;
  }
}
</style>
