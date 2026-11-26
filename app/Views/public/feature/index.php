<?php
$programs = is_array($programs ?? null) ? $programs : [];
?>
<section class="public-inner-hero py-16 md:py-24">
  <div class="article-container fade-up">
    <p class="eyebrow">Program Utama</p>
    <h1 class="page-title mt-5">Program yang tumbuh bersama anggota dan masyarakat.</h1>
    <p class="lead mt-7">Rangkaian program GenBI Provinsi Jambi dalam edukasi, pengabdian, kepemimpinan, dan kolaborasi.</p>
  </div>
</section>

<section class="home-section-surface bg-cream py-16 md:py-24">
  <div class="site-container">
    <?php if ($programs !== []): ?>
      <div class="feature-page-grid" id="feature-program-list" data-ssr="true" aria-label="Daftar Program Utama">
        <?php foreach ($programs as $index => $program): ?>
          <?php
          $imageRows = is_array($program['images'] ?? null) ? $program['images'] : [];
          $images = array_values(array_filter(array_map(static function (mixed $image): string {
              if (is_array($image)) {
                  return trim((string) ($image['url'] ?? ''));
              }
              return is_string($image) ? trim($image) : '';
          }, $imageRows)));
          if ($images === []) {
              $images = ['/uploads/slider-1.png'];
          }
          $slidesJson = json_encode($images, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '[]';
          $title = trim((string) ($program['title'] ?? '')) ?: trim((string) ($program['name'] ?? ''));
          $name = trim((string) ($program['name'] ?? '')) ?: $title;
          $desc = trim((string) ($program['description'] ?? ''));
          $focus = trim((string) ($program['focus'] ?? ''));
          // Only show kicker if it's different from the main name (avoid duplication)
          $kicker = (strcasecmp($title, $name) !== 0 && $title !== '') ? $title : '';
          ?>
          <article class="editorial-slide-card program-slide-card feature-page-card" data-program-slides="<?= $e($slidesJson) ?>" style="--program-bg-image: url('<?= $e($images[0]) ?>');">
            <span class="slide-index"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
            <div class="feature-card-header">
              <span class="program-icon" data-program-icon="<?= $e((string) ($program['icon_key'] ?? 'sparkles')) ?>"></span>
              <?php if ($kicker !== ''): ?><p class="slide-kicker"><?= $e($kicker) ?></p><?php endif; ?>
              <h2 class="feature-card-title"><?= $e($name) ?></h2>
            </div>
            <div class="feature-card-body">
              <?php if ($desc !== ''): ?><p class="feature-card-desc"><?= $e($desc) ?></p><?php endif; ?>
            </div>
            <div class="feature-card-footer">
              <span class="blue-badge"><?= $e($focus !== '' ? $focus : 'Program Unggulan') ?></span>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="soft-card p-8 text-center text-sm leading-7 text-neutral-600">Program Utama belum dipublikasikan.</div>
    <?php endif; ?>
  </div>
</section>


<style>
/* Fix icon and number color for program-slide-card in Feature page */
.program-slide-card .slide-index,
.program-slide-card .program-icon {
    color: #114b9a !important;
}

/* Ensure any SVG inside program-icon inherits the color if it uses stroke or fill */
.program-slide-card .program-icon svg,
.program-slide-card .program-icon i {
    color: #114b9a !important;
}

/* ==========================================================================
   FEATURE PAGE - EQUAL HEIGHT GRID & CARD LAYOUT
   ========================================================================== */
.feature-page-grid {
  display: grid !important;
  grid-template-columns: repeat(auto-fill, minmax(min(100%, 330px), 1fr)) !important;
  gap: 1.5rem !important;
  align-items: stretch !important;
}

.feature-page-card {
  display: flex !important;
  flex-direction: column !important;
  height: 100% !important;
  min-height: 440px !important;
}

.feature-card-header {
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  flex-shrink: 0 !important;
  text-align: center !important;
}

.feature-card-title {
  font-family: 'Source Serif 4', Georgia, serif !important;
  font-size: clamp(1.3rem, 2vw, 1.55rem) !important;
  font-weight: 680 !important;
  letter-spacing: -0.02em !important;
  line-height: 1.28 !important;
  color: #ffffff !important;
  margin-top: 0 !important;
  margin-bottom: 0.65rem !important;
  margin-inline: auto !important;
  width: 100% !important;
  max-width: 17rem !important;
  min-height: 3.6rem !important;
  display: -webkit-box !important;
  -webkit-line-clamp: 2 !important;
  -webkit-box-orient: vertical !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
}

.feature-card-body {
  flex: 1 1 auto !important;
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  text-align: center !important;
}

.feature-card-desc {
  font-family: Inter, ui-sans-serif, system-ui, sans-serif !important;
  font-size: 0.875rem !important;
  line-height: 1.6 !important;
  font-weight: 400 !important;
  color: rgba(247, 251, 255, 0.9) !important;
  margin-top: 0 !important;
  margin-bottom: 1.25rem !important;
  margin-inline: auto !important;
  width: 100% !important;
  max-width: 22rem !important;
  min-height: 5.5rem !important;
  display: -webkit-box !important;
  -webkit-line-clamp: 4 !important;
  -webkit-box-orient: vertical !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
}

.feature-card-footer {
  margin-top: auto !important;
  padding-top: 1rem !important;
  width: 100% !important;
  display: flex !important;
  justify-content: center !important;
}

.feature-card-footer .blue-badge {
  margin-top: auto !important;
  margin-inline: auto !important;
  display: inline-flex !important;
}
</style>
