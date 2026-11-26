<?php
$isEdit = $isEdit ?? false;
$item = $item ?? null;
$name = $isEdit ? ($item['name'] ?? '') : '';
$prestasiTitle = $isEdit ? ($item['title'] ?? '') : '';
$category = $isEdit ? ($item['category'] ?? '') : '';
$year = $isEdit ? ($item['year'] ?? (string) date('Y')) : (string) date('Y');
$description = $isEdit ? ($item['description'] ?? '') : '';
$content = $isEdit ? ($item['content'] ?? $item['detail'] ?? '') : '';
$image = $isEdit ? ($item['image'] ?? '') : '';
$images = $isEdit ? ($item['images'] ?? []) : [];
if (!is_array($images)) {
    $images = [];
}
if ($image !== '' && !in_array($image, $images, true)) {
    array_unshift($images, $image);
}
$institution = $isEdit ? ($item['institution'] ?? '') : '';
$status = $isEdit ? ($item['status'] ?? 'draft') : 'draft';
$metaTitle = $isEdit ? ($item['meta_title'] ?? '') : '';
$metaKeyword = $isEdit ? ($item['meta_keyword'] ?? '') : '';
$metaDesc = $isEdit ? ($item['meta_description'] ?? '') : '';
$itemId = $isEdit ? (int) ($item['id'] ?? 0) : 0;

$prestasiCategories = [
    'Lomba Bank Indonesia',
    'Karya Tulis Ilmiah',
    'Belmawa',
    'Satria Data',
    'ONMIPA',
    'Pimnas',
    'PKM',
    'Custom',
];

$fixedCategories = array_diff($prestasiCategories, ['Custom']);
$isCustomCategory = $category !== '' && !in_array($category, $fixedCategories, true);
$customCategoryVal = $isCustomCategory ? $category : '';
$selectedCategoryOption = $isCustomCategory ? 'Custom' : $category;
?>
<section class="mx-auto max-w-7xl">
  <header class="cms-header slide-in">
    <div>
      <p class="eyebrow">Admin CMS</p>
      <h1 class="section-title mt-3"><?= $isEdit ? 'Edit Prestasi' : 'Add Prestasi' ?></h1>
      <p class="mt-4 max-w-2xl text-base leading-7 text-neutral-600">Kelola data prestasi anggota GenBI Jambi.</p>
    </div>
    <div class="cms-actions">
      <a href="<?= $url('admin.prestasi') ?>" class="btn btn-secondary">View All</a>
    </div>
  </header>

  <div id="cms-body" class="mt-6">
    <form class="prestasi-editor-form w-full" id="prestasi-editor-form" data-ssr="true" data-edit="<?= $isEdit ? '1' : '0' ?>" data-item-id="<?= $itemId ?>">
      <div class="prestasi-config-panel w-full space-y-6">
        <!-- 1. Informasi Prestasi Card -->
        <section class="config-card medium-config-card w-full p-6 sm:p-8">
          <h2 class="text-lg font-bold text-neutral-900 border-b border-neutral-200/70 pb-3 mb-6">Informasi Prestasi</h2>
          <div class="space-y-5">
            <div class="config-field">
              <span class="text-xs font-bold uppercase tracking-wider text-neutral-700 block mb-2">Nama Anggota</span>
              <input class="config-input w-full" id="prestasi-member-search" list="prestasi-member-list" value="<?= $e($name) ?>" placeholder="Cari nama anggota..." autocomplete="off" />
              <datalist id="prestasi-member-list"></datalist>
            </div>
            <div class="config-field">
              <span class="text-xs font-bold uppercase tracking-wider text-neutral-700 block mb-2">Penyelenggara</span>
              <input class="config-input w-full" id="prestasi-institution" value="<?= $e($institution) ?>" placeholder="Nama institusi atau penyelenggara lomba" />
            </div>
            <div class="config-field">
              <span class="text-xs font-bold uppercase tracking-wider text-neutral-700 block mb-2">Tahun</span>
              <input class="config-input w-full" id="prestasi-year" type="number" min="1900" max="2099" step="1" value="<?= $e($year) ?>" placeholder="<?= date('Y') ?>" />
            </div>
            <div class="config-field">
              <span class="text-xs font-bold uppercase tracking-wider text-neutral-700 block mb-2">Kategori</span>
              <div class="prestasi-category-picker relative w-full" id="prestasi-category-picker">
                <div class="relative">
                  <input type="text"
                         class="config-input w-full pr-10"
                         id="prestasi-category"
                         name="category"
                         value="<?= $e($selectedCategoryOption) ?>"
                         placeholder="Pilih jenis kategori..."
                         readonly
                         autocomplete="off" />
                  <button type="button" class="prestasi-picker-toggle absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-neutral-400 hover:text-neutral-700 focus:outline-none" id="prestasi-category-toggle" aria-label="Buka pilihan kategori">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path d="M5.25 7.5L10 12.25L14.75 7.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  </button>
                </div>
                <div class="prestasi-picker-menu hidden" id="prestasi-category-menu">
                  <div class="prestasi-picker-search-wrap">
                    <svg class="h-3.5 w-3.5 text-neutral-400 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor"><circle cx="8.5" cy="8.5" r="5.75" stroke-width="1.5"/><path d="M13 13L17 17" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <input type="text" class="prestasi-picker-search-input" id="prestasi-category-search" placeholder="Cari jenis kategori..." autocomplete="off" />
                  </div>
                  <div class="prestasi-picker-list" id="prestasi-category-list">
                    <?php foreach ($prestasiCategories as $cat): ?>
                      <button type="button" class="prestasi-picker-item <?= $cat === $selectedCategoryOption ? 'is-selected' : '' ?>" data-value="<?= $e($cat) ?>">
                        <span><?= $e($cat) ?></span>
                        <?php if ($cat === $selectedCategoryOption): ?>
                          <svg class="h-4 w-4 text-blue-600 ml-auto" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path d="M16.7 5.3l-8.5 8.5-4.2-4.2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <?php endif; ?>
                      </button>
                    <?php endforeach; ?>
                  </div>
                </div>
                <!-- Custom Category Input (shown when Custom is selected) -->
                <div id="prestasi-custom-category-wrap" class="<?= $isCustomCategory ? '' : 'hidden' ?> mt-3 bg-blue-50/60 p-3.5 rounded-xl border border-blue-200/80">
                  <label for="prestasi-custom-category" class="text-xs font-bold uppercase tracking-wider text-blue-900 block mb-1.5">Kategori Kustom (Maksimal 2 Kata)</label>
                  <input type="text"
                         id="prestasi-custom-category"
                         class="config-input w-full bg-white"
                         value="<?= $e($customCategoryVal) ?>"
                         placeholder="Ketik kategori kustom (contoh: Debat Hukum, Hackathon AI)..."
                         maxlength="50"
                         autocomplete="off" />
                  <p id="prestasi-custom-category-hint" class="text-xs text-neutral-500 mt-1">Kategori kustom maksimal 2 kata.</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- 2. Deskripsi Singkat Card -->
        <section class="config-card medium-config-card w-full p-6 sm:p-8">
          <h2 class="text-lg font-bold text-neutral-900 border-b border-neutral-200/70 pb-3 mb-6">Deskripsi</h2>
          <div class="config-field">
            <span class="text-xs font-bold uppercase tracking-wider text-neutral-700 block mb-2">Deskripsi Singkat</span>
            <textarea class="config-input w-full" id="prestasi-desc-field" rows="4" placeholder="Tulis deskripsi singkat prestasi..."><?= $e($description) ?></textarea>
          </div>
        </section>

        <!-- 3. Detail Prestasi Card -->
        <section class="config-card medium-config-card w-full p-6 sm:p-8">
          <h2 class="text-lg font-bold text-neutral-900 border-b border-neutral-200/70 pb-3 mb-6">Detail Prestasi</h2>
          <div id="prestasi-editor" class="medium-editor-host w-full"></div>
          <div id="prestasi-editor-fallback" class="editor-fallback hidden w-full">
            <textarea class="config-input w-full" id="prestasi-content-field" rows="8" placeholder="Tulis detail prestasi..."><?= $e($content) ?></textarea>
          </div>
        </section>

        <!-- 4. Foto Prestasi Card -->
        <section class="config-card medium-config-card prestasi-photo-card prestasi-photo-uploader w-full p-6 sm:p-8">
          <h2 class="text-lg font-bold text-neutral-900 border-b border-neutral-200/70 pb-3 mb-6">Foto Prestasi</h2>
          <div class="public-upload-field public-prestasi-photo-card prestasi-photo-uploader w-full" data-prestasi-upload-field>
            <div class="public-upload-empty <?= !empty($image) ? 'hidden' : '' ?>" data-prestasi-empty>
              <strong>Belum ada foto</strong>
            </div>
            <input id="prestasi-image-file" class="hidden" type="file" accept="image/*" multiple />
            <button type="button" id="prestasi-upload-btn" class="btn btn-secondary w-full py-2.5">Upload Foto</button>
            <input class="config-input w-full mt-3" id="prestasi-image-url" value="<?= $e($image) ?>" placeholder="URL gambar (opsional)" />
            <input type="hidden" id="prestasi-gallery-json" value="<?= $e(json_encode(array_values($images), JSON_UNESCAPED_SLASHES) ?: '[]') ?>" />
            <p id="prestasi-gallery-status" class="config-hint mt-2">Pilih beberapa foto sekaligus. Foto pertama menjadi foto utama.</p>
            <div class="public-upload-preview w-full" id="prestasi-gallery-preview">
              <div class="public-upload-preview-controls">
                <strong>Preview foto terpilih</strong>
                <span id="prestasi-gallery-counter">0 foto</span>
              </div>
              <div class="public-upload-preview-slider w-full">
                <button type="button" class="public-upload-scroll" id="prestasi-gallery-scroll-left" aria-label="Geser preview ke kiri">‹</button>
                <div id="prestasi-gallery-list" class="admin-upload-gallery public-upload-preview-strip" aria-label="Preview foto prestasi"></div>
                <button type="button" class="public-upload-scroll" id="prestasi-gallery-scroll-right" aria-label="Geser preview ke kanan">›</button>
              </div>
            </div>
          </div>
        </section>

        <!-- 5. SEO Information Card -->
        <section class="config-card medium-config-card w-full p-6 sm:p-8">
          <h2 class="text-lg font-bold text-neutral-900 border-b border-neutral-200/70 pb-3 mb-6">SEO Information</h2>
          <div class="space-y-5">
            <div class="config-field">
              <span class="text-xs font-bold uppercase tracking-wider text-neutral-700 block mb-2">Meta Title</span>
              <input class="config-input w-full" id="prestasi-meta-title" value="<?= $e($metaTitle ?: $prestasiTitle) ?>" />
            </div>
            <div class="config-field">
              <span class="text-xs font-bold uppercase tracking-wider text-neutral-700 block mb-2">Meta Keywords</span>
              <textarea class="config-input w-full" id="prestasi-meta-keyword" rows="3"><?= $e($metaKeyword ?: ($category ? $category . ', GenBI Jambi, prestasi' : '')) ?></textarea>
            </div>
            <div class="config-field">
              <span class="text-xs font-bold uppercase tracking-wider text-neutral-700 block mb-2">Meta Description</span>
              <textarea class="config-input w-full" id="prestasi-meta-desc" rows="4"><?= $e($metaDesc ?: $description) ?></textarea>
            </div>
          </div>
        </section>

        <!-- 6. Status Card -->
        <section class="config-card medium-config-card w-full p-6 sm:p-8">
          <h2 class="text-lg font-bold text-neutral-900 border-b border-neutral-200/70 pb-3 mb-6">Status</h2>
          <div class="config-field">
            <span class="text-xs font-bold uppercase tracking-wider text-neutral-700 block mb-2">Publish Status</span>
            <select class="config-input js-admin-custom-select w-full" id="prestasi-status">
              <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
              <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
              <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Archived</option>
            </select>
          </div>
        </section>

        <!-- Submit Button -->
        <div class="pt-2">
          <button type="submit" class="btn btn-primary w-full py-3 text-base font-semibold shadow-md"><?= $isEdit ? 'Update Prestasi' : 'Submit Prestasi' ?></button>
        </div>
      </div>
    </form>
  </div>
</section>
