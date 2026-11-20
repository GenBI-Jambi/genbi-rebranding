<?php
/** @var callable $e */
$siteSettings = $siteSettings ?? null;
$sitePayload = is_object($siteSettings) && method_exists($siteSettings, 'clientPayload') ? $siteSettings->clientPayload() : ($site ?? []);
$themeKey = is_object($siteSettings) && method_exists($siteSettings, 'themeKey') ? $siteSettings->themeKey('public') : 'genbi';
$inlineThemeCss = is_object($siteSettings) && method_exists($siteSettings, 'themeInlineCss') ? $siteSettings->themeInlineCss('public') : '';
$settingsJson = json_encode($sitePayload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}';
$content = $content ?? '';
?>
<!doctype html>
<html lang="id" data-theme="<?= $e($themeKey) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= $e(\App\Services\CsrfService::token()) ?>">
  <?= $meta ?? '<title>GenBI Provinsi Jambi</title>' ?>
  <?php if ($inlineThemeCss !== ''): ?><style><?= $inlineThemeCss ?></style><?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Source+Serif+4:opsz,wght@8..60,400;8..60,500;8..60,600;8..60,700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Source+Serif+4:opsz,wght@8..60,400;8..60,500;8..60,600;8..60,700&display=swap"></noscript>
  <link rel="stylesheet" href="/assets/css/tailwind.css?v=1.0.1">
  <link rel="stylesheet" href="/assets/css/styles.css?v=1.0.1">
  <?= $jsonld ?? '' ?>
  <?php if (!empty($sitePayload['favicon'])): ?><link rel="icon" href="<?= $e((string) $sitePayload['favicon']) ?>"><?php endif; ?>
</head>
<body class="overflow-x-hidden <?= $e($bodyClass ?? '') ?>" data-ssr="true">
  <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-white focus:text-blue-700 focus:rounded focus:shadow-lg">Langsung ke konten</a>
  <div id="site-header"><?php require __DIR__ . '/../partials/public-header.php'; ?></div>
  <main id="main-content">
    <?= $content ?>
  </main>
  <footer id="site-footer"><?php require __DIR__ . '/../partials/public-footer.php'; ?></footer>
  <div id="modal-root"></div>
  <script>window.GenBISiteSettings = <?= $settingsJson ?>;</script>
  <script defer src="/assets/js/data.js"></script>
  <script defer src="/assets/js/api-core.js"></script>
  <script defer src="/assets/js/api.js"></script>
  <script defer src="/assets/js/app.js?v=1.0.1"></script>
  <script defer src="/assets/js/lib/ui.js"></script>
  <button id="back-to-top" class="back-to-top" aria-label="Kembali ke atas" title="Kembali ke atas"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0-6.75 6.75M12 4.5l6.75 6.75" /></svg></button>
  <?= $scripts ?? '' ?>
</body>
</html>
