<?php
/** @var callable $e */
$items = $items ?? [];

$resolveCategoryBanner = static function (array $item): array {
    $name = (string) ($item['name'] ?? $item['category_name'] ?? '');
    $banner = trim((string) ($item['banner'] ?? $item['category_banner'] ?? ''));
    $lower = mb_strtolower($name);

    // 1. Valid absolute HTTP/HTTPS URL
    if (preg_match('#^https?://#i', $banner)) {
        return ['url' => $banner, 'badge' => 'Custom', 'hasImage' => true];
    }

    // 2. Valid file in uploads directory (if uploaded)
    if ($banner !== '' && !str_starts_with($banner, 'category-banner-')) {
        $clean = ltrim($banner, '/');
        $clean = preg_replace('#^uploads/#', '', $clean);
        $fullPath = dirname(__DIR__, 4) . '/public/uploads/' . $clean;
        if (file_exists($fullPath)) {
            return ['url' => '/uploads/' . $clean, 'badge' => 'Uploaded', 'hasImage' => true];
        }
    }

    // 3. Map to official project banner assets
    if (str_contains($lower, 'uin')) {
        return ['url' => '/assets/images/banner_unja_uin_bi.png', 'badge' => 'Komsat UIN STS', 'hasImage' => true];
    }
    if (str_contains($lower, 'unja') || str_contains($lower, 'universitas jambi')) {
        return ['url' => '/assets/images/banner_unja_uin_bi.png', 'badge' => 'Komsat UNJA', 'hasImage' => true];
    }
    if (str_contains($lower, 'bank indonesia')) {
        return ['url' => '/assets/images/banner-genbi-bi.jpg', 'badge' => 'Bank Indonesia', 'hasImage' => true];
    }
    if (str_contains($lower, 'wilayah')) {
        return ['url' => '/assets/images/banner-genbi-bi.jpg', 'badge' => 'GenBI Wilayah', 'hasImage' => true];
    }
    if (str_contains($lower, 'kolaborasi') || str_contains($lower, 'prestasi') || str_contains($lower, 'pengumuman')) {
        return ['url' => '/assets/images/banner-genbi-bi.jpg', 'badge' => 'GenBI Official', 'hasImage' => true];
    }

    // 4. Fallback to default GenBI official banner if file exists
    $defaultBannerPath = dirname(__DIR__, 4) . '/public/assets/images/banner-genbi-bi.jpg';
    if (file_exists($defaultBannerPath)) {
        return ['url' => '/assets/images/banner-genbi-bi.jpg', 'badge' => 'GenBI Official', 'hasImage' => true];
    }

    return ['url' => '', 'badge' => 'Tanpa Banner', 'hasImage' => false];
};
?>
<section class="mx-auto max-w-7xl">
  <header class="cms-header slide-in">
    <div>
      <p class="eyebrow">Admin CMS</p>
      <h1 class="section-title mt-3 text-2xl sm:text-3xl md:text-4xl font-semibold tracking-tight">View Categories</h1>
      <p class="mt-3 max-w-2xl text-sm sm:text-base leading-relaxed text-neutral-600">Kategori berita dipakai untuk filter publik, pengumuman beranda, dan editor berita.</p>
    </div>
    <button class="btn btn-primary shrink-0" type="button" data-category-add>Add New</button>
  </header>
  <section class="admin-card mt-6 p-4 sm:p-5 md:p-6" id="admin-category-list" data-ssr="true">
    <div class="admin-responsive-table">
      <table class="cms-table">
        <thead>
          <tr>
            <th class="w-16">SL</th>
            <th>Category Name</th>
            <th>Category Banner</th>
            <th class="w-36 text-right sm:text-left">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($items === []): ?>
            <tr><td colspan="4" class="py-8 text-center text-neutral-500">Belum ada kategori.</td></tr>
          <?php endif; ?>
          <?php foreach ($items as $index => $item): ?>
            <?php $b = $resolveCategoryBanner($item); ?>
            <tr>
              <td class="admin-cell-index"><?= $index + 1 ?></td>
              <td>
                <span class="font-semibold text-neutral-900"><?= $e($item['name'] ?? '') ?></span>
              </td>
              <td>
                <?php if ($b['hasImage'] && $b['url'] !== ''): ?>
                  <div class="category-banner-box" title="<?= $e($item['name'] ?? '') ?> - <?= $e($b['badge']) ?>">
                    <img src="<?= $e($b['url']) ?>" class="category-banner-img" alt="<?= $e($item['name'] ?? '') ?>" loading="lazy" onerror="this.closest('.category-banner-box').classList.add('is-fallback');">
                    <span class="category-banner-badge"><?= $e($b['badge']) ?></span>
                    <div class="category-banner-fallback">
                      <svg class="h-4 w-4 shrink-0 text-[#114b9a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                      <span class="truncate"><?= $e($item['name'] ?? 'GenBI') ?></span>
                    </div>
                  </div>
                <?php else: ?>
                  <div class="category-banner-empty" title="Belum ada banner untuk kategori ini">
                    <svg class="h-4 w-4 shrink-0 text-[#114b9a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                    <span>Tanpa Banner</span>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <div class="flex gap-2">
                  <button class="cms-action edit" type="button" data-category-edit="<?= (int) ($item['id'] ?? 0) ?>" data-category-name="<?= $e($item['name'] ?? '') ?>">Edit</button>
                  <button class="cms-action delete" type="button" data-category-delete="<?= (int) ($item['id'] ?? 0) ?>">Delete</button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</section>
