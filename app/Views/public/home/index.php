<?php
$site = $site ?? [];
$stats = $stats ?? [];
$programs = $programs ?? [];
$bpiMembers = $bpiMembers ?? [];
$publicEvents = $publicEvents ?? [];
$announcements = $announcements ?? [];
$latestNews = $latestNews ?? [];
$homeContent = $homeContent ?? ($site['home'] ?? []);
$heroSlides = $site['heroSlides'] ?? [];
$initialSlide = $heroSlides[0] ?? null;

$uploadExists = static function (string $url): bool {
    $path = parse_url($url, PHP_URL_PATH);
    if (!is_string($path) || $path === '') {
        return false;
    }

    if (str_starts_with($path, '/public/uploads/')) {
        $path = substr($path, 7);
    }

    if (!str_starts_with($path, '/uploads/')) {
        return true;
    }

    return is_file(dirname(__DIR__, 4) . '/public' . $path);
};

$fallbackHeroImage = static function (int $index) use ($heroSlides): string {
    $heroCount = max(count($heroSlides), 1);
    return (string) ($heroSlides[$index % $heroCount]['image'] ?? $heroSlides[0]['image'] ?? 'https://genbijambi.com/public/uploads/slider-1.png');
};

$eventIconMarkup = static function (string $type): string {
    return match ($type) {
        'heart' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.49-2.01-4.5-4.5-4.5A4.48 4.48 0 0 0 12 6.36a4.48 4.48 0 0 0-4.5-2.61C5.01 3.75 3 5.76 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>',
        'users' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.5c0-2.07-2.69-3.75-6-3.75s-6 1.68-6 3.75M9 12.75A3.75 3.75 0 1 0 9 5.25a3.75 3.75 0 0 0 0 7.5Zm12 6.75c0-1.74-1.9-3.2-4.45-3.62M15 5.58a3.75 3.75 0 0 1 0 6.84"/></svg>',
        'chart' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 19.5h16.5M6.75 16.5v-6m5.25 6V6.75m5.25 9.75v-9"/></svg>',
        'bank' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.75 12 4.5l8.25 5.25M5.25 10.5h13.5M6.75 10.5v7.5m3.5-7.5v7.5m3.5-7.5v7.5m3.5-7.5v7.5M4.5 18h15"/></svg>',
        'academic' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 8.25 12 4.5l8.25 3.75L12 12 3.75 8.25Zm3 2.25v4.25c0 1.66 2.35 3 5.25 3s5.25-1.34 5.25-3V10.5"/></svg>',
        'spark' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m12 3 1.76 5.45h5.74l-4.64 3.37 1.77 5.45L12 13.9l-4.63 3.37 1.77-5.45L4.5 8.45h5.74L12 3Z"/></svg>',
        default => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 9h16.5M5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v12A2.25 2.25 0 0 1 18 21H6a2.25 2.25 0 0 1-2.25-2.25v-12A1.5 1.5 0 0 1 5.25 5.25Z"/></svg>',
    };
};
?>
<div class="home-page-shell">
  <section class="hero-bg hero-section-compact home-hero-section relative overflow-hidden text-white">
    <div id="hero-slider" class="absolute inset-0"<?= $heroSlides !== [] ? ' data-ssr="true"' : '' ?>>
      <?php foreach ($heroSlides as $index => $slide): ?>
        <img src="<?= $e((string) ($slide['image'] ?? '')) ?>" alt="<?= $e((string) ($slide['caption'] ?? 'Hero slide')) ?>" class="hero-image hero-bg-image <?= $index === 0 ? 'is-active' : '' ?>" />
      <?php endforeach; ?>
    </div>
    <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(12,53,114,0.92),rgba(12,53,114,0.70)_42%,rgba(12,53,114,0.30)_70%,rgba(12,53,114,0.18))]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.16),transparent_25%),linear-gradient(180deg,rgba(0,0,0,0.16),rgba(0,0,0,0.18))]"></div>
    <div class="site-container hero-inner-compact relative z-10 flex items-center">
      <div class="fade-up max-w-4xl">
        <span id="hero-eyebrow" class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/12 px-4 py-2 text-xs font-bold uppercase tracking-[0.14em] text-blue-50 backdrop-blur"><?= $initialSlide ? '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.8 3.7 8.4 8.1 4 9.5l4.4 1.4 1.4 4.4 1.4-4.4 4.4-1.4-4.4-1.4-1.4-4.4Zm7.4 6.7-.9 2.8-2.8.9 2.8.9.9 2.8.9-2.8 2.8-.9-2.8-.9-.9-2.8Z"/></svg> ' . htmlspecialchars((string) ($initialSlide['eyebrow'] ?? ''), ENT_QUOTES, 'UTF-8') : '' ?></span>
        <h1 id="hero-title" class="serif hero-title-compact mt-6 max-w-5xl font-semibold"><?= $e((string) ($initialSlide['title'] ?? '')) ?></h1>
        <p id="hero-caption" class="mt-5 max-w-2xl text-base leading-8 text-blue-50/85 md:text-lg"><?= $e((string) ($initialSlide['caption'] ?? '')) ?></p>
        <div class="mt-7 flex flex-col gap-3 sm:flex-row">
          <a data-transition href="<?= $url('about') ?>" class="btn btn-light">Kenali GenBI</a>
          <a data-transition href="<?= $url('news') ?>" class="btn btn-ghost-light">Baca Berita Terbaru</a>
          <button id="open-video" class="btn btn-ghost-light">Lihat Video</button>
        </div>
        <div class="mt-8 flex gap-2" id="hero-dots"<?= $heroSlides !== [] ? ' data-ssr="true"' : '' ?>>
          <?php foreach ($heroSlides as $index => $slide): ?>
            <button class="h-3 w-3 rounded-full transition hover:bg-white p-2 box-content <?= $index === 0 ? 'bg-white' : 'bg-white/40' ?>" aria-label="Slide <?= $index + 1 ?>" data-slide="<?= $index ?>"></button>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <?php if ($announcements !== []): ?>
    <?php $announcementsDataMap = []; ?>
    <section class="announcement-hero-section home-section-surface py-16 md:py-24" aria-labelledby="announcement-heading">
      <div class="site-container">
        <div class="home-section-intro fade-up">
          <p class="eyebrow"><?= $e((string) ($homeContent['announcementEyebrow'] ?? 'Pengumuman')) ?></p>
          <h2 id="announcement-heading" class="section-title mt-4"><?= $e((string) ($homeContent['announcementTitle'] ?? 'Info penting untuk anggota dan publik.')) ?></h2>
          <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-neutral-600"><?= $e((string) ($homeContent['announcementDescription'] ?? 'Pembaruan resmi, agenda penting, dan kabar prioritas GenBI Jambi ditampilkan dalam format ringkas agar mudah dipantau.')) ?></p>
          <a data-transition href="<?= $url('news') ?>?category=Pengumuman" class="btn btn-announcement mt-7">Lihat semua pengumuman</a>
        </div>
        <div class="carousel-shell fade-up" data-carousel>
          <div class="carousel-control-row relative z-10">
            <button class="carousel-nav" data-carousel-prev aria-label="Pengumuman sebelumnya">‹</button>
            <button class="carousel-nav" data-carousel-next aria-label="Pengumuman berikutnya">›</button>
          </div>
          <div class="horizontal-carousel announcement-scroll<?= count($announcements) <= 2 ? ' is-centered' : '' ?>" id="home-announcements" data-ssr="true" aria-label="Daftar pengumuman terbaru">
          <?php foreach ($announcements as $index => $item): ?>
            <?php
            $id = (int) ($item['id'] ?? ($index + 1));
            $title = (string) ($item['title'] ?? $item['news_title'] ?? 'Pengumuman GenBI');
            $contentFull = (string) ($item['content'] ?? $item['news_content'] ?? '');
            $excerpt = trim(strip_tags((string) ($item['excerpt'] ?? $item['news_content_short'] ?? $contentFull)));
            if (mb_strlen($excerpt) > 130) {
                $excerpt = rtrim(mb_substr($excerpt, 0, 130)) . '…';
            }
            $dateLabel = (string) ($item['date_formatted'] ?? '');
            if ($dateLabel === '') {
                $dateRaw = (string) ($item['start_date'] ?? $item['date'] ?? $item['news_date'] ?? $item['created_at'] ?? '');
                $dateTimestamp = $dateRaw !== '' ? strtotime($dateRaw) : false;
                $dateLabel = $dateTimestamp !== false ? date('d M Y', $dateTimestamp) : 'Terbaru';
            }
            $fileAttachment = trim((string) ($item['file_attachment'] ?? ''));
            $hasFile = $fileAttachment !== '';
            $fileUrl = '';
            $fileName = '';
            $fileExt = '';
            if ($hasFile) {
                $fileUrl = (str_starts_with($fileAttachment, 'http://') || str_starts_with($fileAttachment, 'https://') || str_starts_with($fileAttachment, '/'))
                    ? $fileAttachment
                    : '/' . ltrim($fileAttachment, '/');
                $fileName = basename($fileAttachment);
                $fileExt = strtoupper(pathinfo($fileAttachment, PATHINFO_EXTENSION));
                if ($fileExt === '') {
                    $fileExt = 'DOC';
                }
            }

            $announcementsDataMap[$id] = [
                'id' => $id,
                'title' => $title,
                'content' => $contentFull !== '' ? $contentFull : $excerpt,
                'excerpt' => $excerpt,
                'date' => $dateLabel,
                'has_file' => $hasFile,
                'file_url' => $fileUrl,
                'file_name' => $fileName,
                'file_ext' => $fileExt,
            ];
            ?>
            <article class="announcement-card enhanced-announcement-card" data-announcement-id="<?= $id ?>" role="group" aria-label="Pengumuman: <?= $e($title) ?>" tabindex="0">
              <!-- Top Row: Number + Date & Document Pill -->
              <div class="announcement-top-bar">
                <span class="announcement-number custom-announcement-number"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
                <div class="announcement-meta-pills">
                  <span class="announcement-date-pill"><?= $e($dateLabel) ?></span>
                  <?php if ($hasFile): ?>
                    <span class="announcement-doc-pill" title="Tersedia berkas lampiran <?= $e($fileExt) ?>">
                      <svg class="announcement-doc-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.5 9.525l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.5-3.6a3 3 0 000-4.2z" clip-rule="evenodd"/>
                      </svg>
                      <span><?= $e($fileExt) ?></span>
                    </span>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Main Content -->
              <div class="announcement-body">
                <h3 class="announcement-title" title="<?= $e($title) ?>"><?= $e($title) ?></h3>
                <p class="announcement-excerpt"><?= $e($excerpt) ?></p>
              </div>

              <!-- Bottom Actions Row -->
              <div class="announcement-action-bar">
                <?php if ($hasFile): ?>
                  <button type="button" class="btn-announcement-detail" data-open-modal="<?= $id ?>" aria-label="Lihat isi lengkap pengumuman: <?= $e($title) ?>">
                    <span>Baca Detail</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                  </button>
                  <a href="<?= $e($fileUrl) ?>" download="<?= $e($fileName) ?>" class="btn-announcement-download" data-download-btn data-file-url="<?= $e($fileUrl) ?>" data-file-name="<?= $e($fileName) ?>" title="Unduh berkas lampiran <?= $e($fileName) ?>">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Unduh Berkas</span>
                  </a>
                <?php else: ?>
                  <button type="button" class="btn-announcement-detail is-full" data-open-modal="<?= $id ?>" aria-label="Lihat isi lengkap pengumuman: <?= $e($title) ?>">
                    <span>Baca Selengkapnya</span>
                    <svg class="w-3.5 h-3.5 ml-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                  </button>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
          </div>
        </div>
      </div>

<style>
/* Button "Lihat semua pengumuman" */
.btn-announcement {
    background-color: #114b9a !important;
    color: white !important;
    border: 2px solid #114b9a !important;
    transition: all 0.2s ease-in-out;
}
.btn-announcement:hover {
    background-color: white !important;
    color: #114b9a !important;
    border-color: #114b9a !important;
}

/* Number Circle */
.custom-announcement-number {
    background-color: #114b9a !important;
    color: white !important;
    border: 2px solid white !important;
}

/* Carousel Arrows */
.carousel-control-row {
    position: relative;
    z-index: 20;
    pointer-events: auto;
}
.carousel-nav {
    background-color: #114b9a !important;
    color: white !important;
    border: 2px solid #114b9a !important;
    z-index: 20;
    transition: all 0.2s ease-in-out;
    opacity: 1 !important;
    visibility: visible !important;
}
.carousel-nav:hover {
    background-color: white !important;
    color: #114b9a !important;
    border-color: #114b9a !important;
}

/* Enhanced Announcement Card Layout */
.enhanced-announcement-card {
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
    text-align: left !important;
    cursor: pointer;
    user-select: none;
    position: relative;
    padding: clamp(1.35rem, 2.5vw, 1.8rem) !important;
    box-sizing: border-box;
}

.announcement-top-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    margin-bottom: 1.25rem;
}

.announcement-top-bar .announcement-number {
    position: static !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 9999px;
    font-size: 0.85rem;
    font-weight: 700;
}

.announcement-meta-pills {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.announcement-date-pill {
    display: inline-flex;
    align-items: center;
    border-radius: 9999px;
    background: rgba(255, 255, 255, 0.16);
    backdrop-filter: blur(4px);
    padding: 0.35rem 0.75rem;
    color: rgba(255, 255, 255, 0.95);
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    border: 1px solid rgba(255, 255, 255, 0.20);
}

.announcement-doc-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    border-radius: 9999px;
    background: #ffffff;
    color: #114b9a;
    padding: 0.35rem 0.65rem;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

.announcement-doc-icon {
    width: 0.85rem;
    height: 0.85rem;
}

.announcement-body {
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    margin-bottom: 1.4rem;
}

.announcement-title {
    margin-top: 0 !important;
    color: #ffffff !important;
    font-family: 'Source Serif 4', Georgia, serif !important;
    font-size: clamp(1.25rem, 2vw, 1.45rem) !important;
    font-weight: 700 !important;
    line-height: 1.3 !important;
    letter-spacing: -0.02em !important;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
}

.announcement-excerpt {
    margin-top: 0.75rem !important;
    color: rgba(255, 255, 255, 0.88) !important;
    font-family: 'Inter', -apple-system, sans-serif !important;
    font-size: 0.925rem !important;
    font-weight: 400 !important;
    line-height: 1.6 !important;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    text-shadow: 0 1px 6px rgba(0, 0, 0, 0.15);
}

.announcement-action-bar {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    width: 100%;
    margin-top: auto;
}

.btn-announcement-detail {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 0.6rem 0.95rem;
    font-size: 0.825rem;
    font-weight: 600;
    color: #ffffff;
    background: rgba(255, 255, 255, 0.14);
    border: 1px solid rgba(255, 255, 255, 0.28);
    border-radius: 0.85rem;
    backdrop-filter: blur(4px);
    transition: all 0.2s ease-in-out;
    flex: 1 1 auto;
    cursor: pointer;
}
.btn-announcement-detail:hover {
    background: rgba(255, 255, 255, 0.26);
    border-color: rgba(255, 255, 255, 0.6);
    transform: translateY(-1px);
}
.btn-announcement-detail.is-full {
    width: 100%;
    padding: 0.7rem 1rem;
    font-size: 0.875rem;
}

.btn-announcement-download {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 0.6rem 0.95rem;
    font-size: 0.825rem;
    font-weight: 700;
    color: #114b9a;
    background: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.9);
    border-radius: 0.85rem;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
    transition: all 0.2s ease-in-out;
    flex: 1 1 auto;
    cursor: pointer;
    text-decoration: none;
}
.btn-announcement-download:hover {
    background: #f0f7ff;
    color: #0c3572;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.18);
}

/* Modal Open Transitions */
#announcement-detail-modal:not(.hidden) {
    animation: fadeIn 0.2s ease-out forwards;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Toast In/Out */
#public-download-toast.show {
    transform: translateY(0);
    opacity: 1;
}

/* Fix transparent icon background and color for Agenda section */
.agenda-slide-card .program-icon {
    background-color: rgb(238, 246, 255) !important;
    color: #114b9a !important;
    box-shadow: inset 0 0 0 1px rgb(17 75 154 / 0.10) !important;
}
.agenda-slide-card .program-icon svg,
.agenda-slide-card .program-icon i {
    color: #114b9a !important;
}
</style>
    </section>
  <?php endif; ?>

  <section class="home-section-surface py-14">
    <div class="site-container grid gap-6 border-y border-neutral-900/10 py-8 md:grid-cols-4" id="stats-row"<?= $stats !== [] ? ' data-ssr="true"' : '' ?>>
      <?php foreach ($stats as $item): ?>
        <div class="fade-up">
          <p class="serif text-4xl font-semibold tracking-tight text-neutral-950"><?= $e((string) ($item['value'] ?? '')) ?></p>
          <p class="mt-2 text-sm leading-6 text-neutral-600"><?= $e((string) ($item['label'] ?? '')) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="home-section-surface py-16 md:py-24">
    <div class="site-container">
      <div class="home-section-intro fade-up">
        <p class="eyebrow"><?= $e((string) ($homeContent['programEyebrow'] ?? 'Program utama')) ?></p>
        <h2 class="section-title mt-4"><?= $e((string) ($homeContent['programTitle'] ?? 'Program yang dekat dengan anggota dan masyarakat.')) ?></h2>
        <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-neutral-600"><?= $e((string) ($homeContent['programDescription'] ?? 'Setiap program dirancang sebagai ruang belajar, ruang kolaborasi, dan ruang kontribusi agar anggota GenBI Jambi tumbuh sekaligus memberi manfaat.')) ?></p>
        <a data-transition href="<?= $url('about') ?>" class="btn btn-brand-primary mt-7">Lihat profil lengkap</a>
      </div>
      <div class="carousel-shell fade-up" data-carousel>
        <div class="carousel-control-row relative z-10">
          <button class="carousel-nav" data-carousel-prev aria-label="Program sebelumnya">‹</button>
          <button class="carousel-nav" data-carousel-next aria-label="Program berikutnya">›</button>
        </div>
        <div class="horizontal-carousel program-carousel" id="program-list" aria-label="Daftar program utama" data-ssr="true">
          <?php if ($programs !== []): ?>
            <?php foreach ($programs as $index => $program): ?>
              <?php
              $images = $program['images'] ?? [];
              $firstImage = $images[0]['url'] ?? 'https://genbijambi.com/public/uploads/slider-1.png';
              ?>
              <article
                class="editorial-slide-card program-slide-card"
                role="group"
                aria-roledescription="slide"
                aria-label="Program <?= $index + 1 ?> dari <?= count($programs) ?>"
                data-program-slides="<?= $e(json_encode(array_values(array_map(static fn(array $image): string => (string) ($image['url'] ?? ''), $images)), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '[]') ?>"
                style="--program-bg-image: url('<?= $e($firstImage) ?>');"
              >
                <span class="slide-index"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
                <span class="program-icon mx-auto" data-program-icon="<?= $e($program['icon_key'] ?? 'sparkles') ?>"></span>
                <p class="slide-kicker"><?= $e($program['title'] ?? '') ?></p>
                <h3><?= $e($program['name'] ?? '') ?></h3>
                <p><?= $e($program['description'] ?? '') ?></p>
                <?php if (!empty($program['focus'])): ?>
                  <span class="blue-badge mx-auto mt-5"><?= $e($program['focus']) ?></span>
                <?php endif; ?>
              </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="home-section-surface py-16 md:py-24">
    <div class="site-container">
      <div class="home-section-intro fade-up">
        <p class="eyebrow"><?= $e((string) ($homeContent['teamEyebrow'] ?? 'GenBI Provinsi Jambi')) ?></p>
        <h2 class="section-title mt-4"><?= $e((string) ($homeContent['teamTitle'] ?? 'Wajah pengurus yang menjaga arah gerak organisasi.')) ?></h2>
        <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-neutral-600"><?= $e((string) ($homeContent['teamDescription'] ?? 'Badan Pengurus Inti menghubungkan ide, anggota, dan agenda kerja agar GenBI Jambi tetap solid, aktif, dan relevan bagi lingkungan sekitar.')) ?></p>
        <a data-transition href="<?= $url('team') ?>" class="btn btn-secondary mt-7">Lihat direktori anggota</a>
      </div>
      <div class="carousel-shell fade-up" data-carousel>
        <div class="carousel-control-row relative z-10">
          <button class="carousel-nav" data-carousel-prev aria-label="Anggota sebelumnya">‹</button>
          <button class="carousel-nav" data-carousel-next aria-label="Anggota berikutnya">›</button>
        </div>
        <div class="horizontal-carousel bpi-carousel" id="bpi-list" aria-label="Daftar GenBI Provinsi Jambi"<?= $bpiMembers !== [] ? ' data-ssr="true"' : '' ?>>
          <?php foreach ($bpiMembers as $index => $member): ?>
            <?php $photo = (string) ($member['photo'] ?? $member['image'] ?? ''); ?>
            <article class="editorial-slide-card bpi-slide-card" role="group" aria-roledescription="slide" aria-label="Anggota BPI <?= $index + 1 ?> dari <?= count($bpiMembers) ?>">
              <figure class="bpi-slide-photo">
                <span class="member-photo-skeleton" aria-hidden="true"></span>
                <img src="<?= $e($photo) ?>" alt="Foto <?= $e((string) ($member['name'] ?? 'Anggota BPI')) ?>" loading="lazy" onload="this.previousElementSibling.classList.add('is-hidden')" onerror="this.classList.add('is-hidden'); this.previousElementSibling.classList.remove('is-hidden')" />
              </figure>
              <div class="bpi-slide-content">
                <div class="bpi-slide-header">
                  <h3 class="bpi-slide-name"><?= $e((string) ($member['name'] ?? '')) ?></h3>
                  <p class="bpi-slide-role"><?= $e((string) ($member['role'] ?? '')) ?></p>
                </div>
                <div class="bpi-slide-footer">
                  <span class="blue-badge"><span class="bpi-badge-dot" aria-hidden="true"></span><?= $e((string) ($member['commission'] ?? $member['komsat'] ?? 'GenBI Wilayah')) ?></span>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="home-section-surface py-16 md:py-24">
    <div class="site-container">
      <div class="home-section-intro fade-up">
        <p class="eyebrow"><?= $e((string) ($homeContent['eventEyebrow'] ?? 'Agenda utama')) ?></p>
        <h2 class="section-title mt-4"><?= $e((string) ($homeContent['eventTitle'] ?? 'Kegiatan yang lahir dari kebutuhan sekitar.')) ?></h2>
        <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-neutral-600"><?= $e((string) ($homeContent['eventDescription'] ?? 'Agenda GenBI Jambi tidak berhenti di seremoni. Setiap kegiatan menjadi kesempatan untuk belajar, melayani, dan membangun jejaring kebaikan.')) ?></p>
        <a data-transition href="<?= $url('event') ?>" class="btn btn-brand-primary mt-7">Lihat semua agenda</a>
      </div>
      <div class="carousel-shell fade-up" data-carousel>
        <div class="carousel-control-row relative z-10">
          <button class="carousel-nav" data-carousel-prev aria-label="Agenda sebelumnya">‹</button>
          <button class="carousel-nav" data-carousel-next aria-label="Agenda berikutnya">›</button>
        </div>
        <div class="horizontal-carousel event-carousel" id="home-events" aria-label="Daftar agenda utama"<?= $publicEvents !== [] ? ' data-ssr="true"' : '' ?>>
          <?php foreach ($publicEvents as $index => $event): ?>
            <?php
            $slides = [];
            if (!empty($event['images']) && is_array($event['images'])) {
                $slides = array_values(array_filter(
                    array_map(static fn($image): string => (string) $image, $event['images']),
                    static fn(string $image): bool => $image !== '' && $uploadExists($image)
                ));
            }

            if ($slides === []) {
                $slides = [$fallbackHeroImage($index)];
            }

            $eventType = (string) ($event['type'] ?? $event['category'] ?? 'Agenda Komunitas');
            $eventDate = (string) ($event['date'] ?? $event['start_date'] ?? $event['start'] ?? '-');
            $eventDescription = (string) ($event['description'] ?? $event['excerpt'] ?? '');
            $eventIcon = (string) ($event['icon'] ?? 'calendar');
            ?>
            <article class="editorial-slide-card program-slide-card agenda-slide-card" role="group" aria-roledescription="slide" aria-label="Agenda <?= $index + 1 ?> dari <?= count($publicEvents) ?>" data-program-slides='<?= $e(json_encode($slides, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '[]') ?>' style="--program-bg-image: url('<?= $e($slides[0]) ?>');">
              <span class="slide-index"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
              <span class="program-icon mx-auto"><?= $eventIconMarkup($eventIcon) ?></span>
              <p class="slide-kicker"><?= $e($eventType) ?></p>
              <h3><?= $e((string) ($event['title'] ?? '')) ?></h3>
              <p><?= $e($eventDescription) ?></p>
              <span class="blue-badge mx-auto mt-5"><?= $e($eventDate) ?></span>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="home-section-surface py-16 md:py-24">
    <div class="article-container">
      <div class="fade-up mb-9 flex flex-col justify-between gap-5 md:flex-row md:items-end">
        <div>
          <p class="eyebrow"><?= $e((string) ($homeContent['newsEyebrow'] ?? 'Latest news')) ?></p>
          <h2 class="section-title mt-4"><?= $e((string) ($homeContent['newsTitle'] ?? 'Berita terbaru')) ?></h2>
        </div>
        <a data-transition href="<?= $url('news') ?>" class="btn btn-secondary w-fit">Lihat semua berita</a>
      </div>
      <div class="fade-up" id="home-news"<?= $latestNews !== [] ? ' data-ssr="true"' : '' ?>>
        <?php if ($latestNews !== []): ?>
          <?php foreach ($latestNews as $item): ?>
            <a data-transition href="<?= $e('/news/' . ($item['slug'] ?? '')) ?>" class="home-news-card">
              <figure class="home-news-media"><img src="<?= $e((string) ($item['image'] ?? '')) ?>" alt="<?= $e((string) ($item['title'] ?? 'Berita GenBI')) ?>" loading="lazy" onerror="this.src='https://genbijambi.com/public/uploads/slider-1.png'" /></figure>
              <div class="home-news-copy">
                <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-neutral-500">
                  <span class="text-blue-800"><?= $e((string) ($item['category'] ?? 'Berita GenBI')) ?></span>
                  <span><?= $e(!empty($item['date']) ? date('d M Y', strtotime((string) $item['date'])) : '-') ?></span>
                </div>
                <h3 class="serif text-2xl font-semibold leading-tight tracking-tight text-neutral-950 md:text-3xl"><?= $e((string) ($item['title'] ?? 'Berita GenBI')) ?></h3>
                <p class="text-base leading-7 text-neutral-600"><?= $e((string) ($item['excerpt'] ?? '')) ?></p>
              </div>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="home-section-surface py-14 md:py-20">
    <div class="site-container contact-prefooter fade-up" id="home-contact-card"<?= !empty($site) ? ' data-ssr="true"' : '' ?>>
      <div>
        <p class="eyebrow">Contact us</p>
        <h2 class="serif mt-3 text-3xl font-semibold tracking-tight text-neutral-950 md:text-5xl">Mau berkolaborasi dengan GenBI Jambi?</h2>
        <p class="mt-4 max-w-2xl text-base leading-7 text-neutral-600">Hubungi kami untuk informasi kegiatan, publikasi, kolaborasi, dan agenda komunitas.</p>
      </div>
      <div class="contact-prefooter-card">
        <p class="contact-label">Address</p>
        <p><?= $e((string) ($site['address'] ?? '')) ?></p>
        <div class="mt-5 grid gap-2 text-sm">
          <a href="mailto:<?= $e((string) ($site['email'] ?? '')) ?>"><?= $e((string) ($site['email'] ?? '')) ?></a>
          <a href="tel:<?= $e((string) ($site['phone'] ?? '')) ?>"><?= $e((string) ($site['phone'] ?? '')) ?></a>
        </div>
        <a data-transition href="<?= $url('contact') ?>" class="btn btn-primary mt-6 w-fit">Contact Us</a>
      </div>
    </div>
  </section>
</div>

<!-- Video Modal -->
<div id="video-modal" class="fixed inset-0 z-[80] hidden bg-neutral-950/70 p-4 backdrop-blur-sm">
  <div class="mx-auto mt-10 max-w-4xl rounded-[1.75rem] bg-cream p-4 shadow-2xl modal-panel md:p-6">
    <div class="flex items-start justify-between gap-5 px-1 pb-4">
      <div>
        <p class="eyebrow">Video</p>
        <h3 class="serif mt-2 text-2xl font-semibold tracking-tight text-neutral-950 md:text-3xl">Video profil GenBI Jambi</h3>
      </div>
      <button id="close-video" class="btn-icon" aria-label="Tutup video">×</button>
    </div>
    <div class="video-frame">
      <iframe id="profile-video" width="560" height="315" src="" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
  </div>
</div>

<!-- Interactive Announcement Detail Modal -->
<div id="announcement-detail-modal" class="fixed inset-0 z-[85] hidden bg-neutral-950/70 p-4 backdrop-blur-sm flex items-center justify-center transition-all duration-200" role="dialog" aria-modal="true" aria-labelledby="modal-announcement-title">
  <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col bg-white rounded-3xl shadow-2xl overflow-hidden border border-neutral-200 transition-all">
    <!-- Top accent bar -->
    <div class="h-1.5 w-full bg-[#114b9a]"></div>

    <!-- Header -->
    <div class="px-6 pt-5 pb-4 flex items-start justify-between gap-4 border-b border-neutral-100">
      <div class="pr-6">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-[#114b9a] border border-blue-100">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
          <span>PENGUMUMAN RESMI GENBI</span>
        </div>
        <h2 id="modal-announcement-title" class="serif text-xl sm:text-2xl font-bold text-neutral-900 mt-2.5 leading-snug">
          <!-- Populated by JS -->
        </h2>
        <div class="flex items-center gap-2 mt-2 text-xs font-medium text-neutral-500">
          <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          <span id="modal-announcement-date"><!-- Populated by JS --></span>
        </div>
      </div>
      <button type="button" id="close-announcement-modal" class="p-2 text-neutral-400 hover:text-neutral-700 hover:bg-neutral-100 rounded-full transition-colors flex-shrink-0" aria-label="Tutup jendela pengumuman">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <!-- Scrollable Content Body -->
    <div class="px-6 py-5 overflow-y-auto max-h-[55vh] text-neutral-700 leading-relaxed text-sm sm:text-base space-y-4">
      <div id="modal-announcement-content" class="whitespace-pre-line break-words text-neutral-800">
        <!-- Populated by JS -->
      </div>

      <!-- Attachment Box (Shown only if file exists) -->
      <div id="modal-announcement-attachment-box" class="hidden mt-6 p-4 rounded-2xl bg-blue-50/70 border border-blue-200/80">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div class="flex items-start gap-3 min-w-0">
            <div class="w-10 h-10 rounded-xl bg-[#114b9a] text-white flex items-center justify-center flex-shrink-0 shadow-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="min-w-0 flex-1">
              <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-[#114b9a] uppercase tracking-wider" id="modal-attachment-type">DOKUMEN LAMPIRAN</span>
              </div>
              <p class="text-sm font-semibold text-neutral-900 truncate mt-0.5" id="modal-attachment-name">dokumen.pdf</p>
              <p class="text-xs text-neutral-500 mt-0.5">Berkas resmi lampiran pengumuman Bank Indonesia</p>
            </div>
          </div>
          <div class="flex items-center gap-2 flex-shrink-0 w-full sm:w-auto">
            <a id="modal-preview-btn" href="#" target="_blank" rel="noopener noreferrer" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl bg-white text-[#114b9a] border border-blue-200 hover:bg-blue-50 transition-colors shadow-sm">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              <span>Buka</span>
            </a>
            <a id="modal-download-btn" href="#" download class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-xl bg-[#114b9a] text-white hover:bg-[#0c3572] transition-colors shadow-sm">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
              <span>Unduh Berkas</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Footer -->
    <div class="px-6 py-3.5 bg-neutral-50/80 border-t border-neutral-100 flex items-center justify-end">
      <button type="button" id="close-announcement-modal-btn" class="px-5 py-2 text-xs font-semibold text-neutral-700 bg-white border border-neutral-200 rounded-xl hover:bg-neutral-100 transition-colors">
        Tutup
      </button>
    </div>
  </div>
</div>

<!-- Download Toast Notification -->
<div id="public-download-toast" class="fixed bottom-6 right-6 z-[95] pointer-events-none translate-y-12 opacity-0 transition-all duration-300">
  <div class="pointer-events-auto flex items-center gap-3 bg-white/95 backdrop-blur-md border border-blue-200 shadow-xl rounded-2xl px-4 py-3 max-w-sm">
    <div class="w-8 h-8 rounded-full bg-blue-50 text-[#114b9a] flex items-center justify-center flex-shrink-0">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
    </div>
    <div class="min-w-0 flex-1">
      <p class="text-xs font-semibold text-neutral-900" id="toast-title">Sedang Mengunduh Berkas</p>
      <p class="text-[11px] text-neutral-500 truncate mt-0.5" id="toast-filename">dokumen.pdf</p>
    </div>
  </div>
</div>

<style>
/* Button "Lihat profil lengkap" */
.btn-brand-primary {
    background-color: #114b9a !important;
    color: white !important;
    border: 2px solid #114b9a !important;
    transition: all 0.2s ease-in-out;
}
.btn-brand-primary:hover {
    background-color: white !important;
    color: #114b9a !important;
    border-color: #114b9a !important;
}

/* Icon and Number color specifically for program-slide-card */
.program-slide-card .slide-index,
.program-slide-card .program-icon {
    color: #114b9a !important;
}

.program-slide-card .program-icon svg,
.program-slide-card .program-icon i {
    color: #114b9a !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const announcementsData = <?= json_encode($announcementsDataMap ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    const modal = document.getElementById('announcement-detail-modal');
    const modalTitle = document.getElementById('modal-announcement-title');
    const modalDate = document.getElementById('modal-announcement-date');
    const modalContent = document.getElementById('modal-announcement-content');
    const attachmentBox = document.getElementById('modal-announcement-attachment-box');
    const attachmentType = document.getElementById('modal-attachment-type');
    const attachmentName = document.getElementById('modal-attachment-name');
    const previewBtn = document.getElementById('modal-preview-btn');
    const downloadBtn = document.getElementById('modal-download-btn');
    const closeBtn = document.getElementById('close-announcement-modal');
    const closeFooterBtn = document.getElementById('close-announcement-modal-btn');
    const toast = document.getElementById('public-download-toast');
    const toastFilename = document.getElementById('toast-filename');
    let toastTimeout = null;

    function showDownloadToast(filename) {
        if (!toast) return;
        if (toastTimeout) clearTimeout(toastTimeout);
        if (toastFilename) toastFilename.textContent = filename || 'dokumen.pdf';
        toast.classList.add('show');
        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 3500);
    }

    function openModal(id) {
        const item = announcementsData[id];
        if (!item || !modal) return;

        if (modalTitle) modalTitle.textContent = item.title;
        if (modalDate) modalDate.textContent = item.date;
        if (modalContent) modalContent.textContent = item.content || item.excerpt || '';

        if (item.has_file && attachmentBox) {
            attachmentBox.classList.remove('hidden');
            if (attachmentType) attachmentType.textContent = (item.file_ext || 'DOKUMEN') + ' LAMPIRAN';
            if (attachmentName) attachmentName.textContent = item.file_name || 'Berkas Lampiran';
            if (previewBtn) previewBtn.href = item.file_url;
            if (downloadBtn) {
                downloadBtn.href = item.file_url;
                downloadBtn.setAttribute('download', item.file_name || 'dokumen');
            }
        } else if (attachmentBox) {
            attachmentBox.classList.add('hidden');
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Attach click listeners to cards
    document.querySelectorAll('.enhanced-announcement-card').forEach(card => {
        card.addEventListener('click', function (e) {
            if (e.target.closest('[data-download-btn]')) {
                return;
            }
            const id = this.getAttribute('data-announcement-id');
            if (id) openModal(id);
        });

        card.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const id = this.getAttribute('data-announcement-id');
                if (id) openModal(id);
            }
        });
    });

    // Attach click listeners to download buttons on cards
    document.querySelectorAll('[data-download-btn]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const fileName = this.getAttribute('data-file-name') || 'dokumen.pdf';
            showDownloadToast(fileName);
        });
    });

    // Attach click listener to modal download button
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function () {
            const fileName = attachmentName ? attachmentName.textContent : 'dokumen.pdf';
            showDownloadToast(fileName);
        });
    }

    // Modal close events
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (closeFooterBtn) closeFooterBtn.addEventListener('click', closeModal);
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });
    }

    window.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
</script>
