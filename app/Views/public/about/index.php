<?php
/** @var callable $e */
/** @var callable $url */
$aboutBlocks = [
    ['title' => 'Tentang GenBI', 'text' => 'Generasi Baru Indonesia adalah komunitas mahasiswa penerima beasiswa Bank Indonesia. GenBI Jambi menjadi ruang pembinaan, edukasi publik, literasi kebanksentralan, dan pengembangan kepemimpinan bagi mahasiswa Universitas Jambi dan UIN STS Jambi.'],
    ['title' => 'Visi', 'text' => 'Menjadikan kaum muda Indonesia sebagai generasi yang kompeten dalam berbagai bidang keilmuan, mampu membawa perubahan positif, dan menjadi inspirasi bagi bangsa dan negara.'],
    ['title' => 'Misi', 'text' => 'Menggagas kegiatan pemberdayaan, melakukan aksi nyata, peduli terhadap masyarakat, serta berbagi inspirasi dan motivasi sebagai energi bagi negeri.'],
];
$roles = [
    ['title' => 'Frontliners Bank Indonesia', 'text' => 'Mengkomunikasikan kelembagaan dan berbagai kebijakan Bank Indonesia kepada mahasiswa dan masyarakat umum.'],
    ['title' => 'Change Agents', 'text' => 'Menjadi agen perubahan dan role model di kalangan pelajar, mahasiswa, dan masyarakat.'],
    ['title' => 'Future Leaders', 'text' => 'Mempersiapkan anggota sebagai pemimpin masa depan di berbagai bidang dan tingkatan.'],
];

$faqs = [
    [
        'question' => 'Apa itu GenBI (Generasi Baru Indonesia)?',
        'answer' => 'Generasi Baru Indonesia (GenBI) adalah komunitas mahasiswa penerima Beasiswa Bank Indonesia yang resmi berdiri pada 11 November 2011. Komunitas ini menjadi wadah pembinaan kepemimpinan, kepedulian sosial, serta jembatan komunikasi kebijakan Bank Indonesia kepada mahasiswa dan masyarakat luas.',
    ],
    [
        'question' => 'Siapa saja anggota GenBI Provinsi Jambi?',
        'answer' => 'Anggota GenBI Provinsi Jambi adalah mahasiswa aktif terpilih yang telah dinyatakan lolos seleksi Beasiswa Bank Indonesia dari dua perguruan tinggi mitra di Jambi, yaitu Universitas Jambi (UNJA) dan Universitas Islam Negeri Sulthan Thaha Saifuddin (UIN STS) Jambi.',
    ],
    [
        'question' => 'Apa saja peran utama (tiga pilar) anggota GenBI?',
        'answer' => 'GenBI mengemban tiga pilar peran utama:<br><span class="block mt-2 font-medium text-neutral-800">1. <strong>Frontliners Bank Indonesia:</strong> Mengomunikasikan kebijakan Bank Indonesia (seperti QRIS, Cinta Bangga Paham Rupiah, dan sistem pembayaran digital).</span><span class="block mt-1 font-medium text-neutral-800">2. <strong>Change Agents:</strong> Menjadi pelopor perubahan positif dan teladan inspiratif di lingkungan kampus maupun masyarakat.</span><span class="block mt-1 font-medium text-neutral-800">3. <strong>Future Leaders:</strong> Mempersiapkan diri sebagai calon pemimpin berintegritas tinggi di masa depan.</span>',
    ],
    [
        'question' => 'Bagaimana cara menjadi anggota GenBI Provinsi Jambi?',
        'answer' => 'Untuk menjadi anggota GenBI, mahasiswa harus mendaftar dan dinyatakan lolos program Beasiswa Bank Indonesia saat periode seleksi dibuka di kampus UNJA atau UIN STS Jambi. Seleksi meliputi verifikasi berkas administrasi akademik dan wawancara langsung oleh pihak Bank Indonesia. Mahasiswa yang lolos seleksi otomatis bergabung menjadi anggota aktif GenBI.',
    ],
    [
        'question' => 'Apa saja program kerja dan kegiatan unggulan GenBI Jambi?',
        'answer' => 'GenBI Jambi memiliki program kerja yang aktif bergerak di bidang pendidikan, kebanksentralan, lingkungan, dan UMKM. Beberapa di antaranya meliputi <strong>SIGINJAI</strong> (Semarak Ekonomi dan Keuangan Syariah Negeri Jambi), <strong>GGTC</strong> (GenBI Goes to School & Campus), aksi peduli masyarakat GenBI PEKA, sosialisasi CBP Rupiah, serta pendampingan digitalisasi bagi pelaku UMKM.',
    ],
    [
        'question' => 'Apa manfaat yang didapatkan dengan menjadi bagian dari GenBI?',
        'answer' => 'Selain bantuan dana pendidikan berkala dari Bank Indonesia, anggota mendapatkan pembinaan kepemimpinan (leadership camp), pelatihan soft skills, akses seminar & jejaring nasional, pengalaman mengelola organisasi secara profesional, serta sertifikasi dan mentoring langsung dari pimpinan dan praktisi Bank Indonesia.',
    ],
    [
        'question' => 'Apakah kegiatan GenBI hanya berlangsung di dalam kampus?',
        'answer' => 'Tidak. Meskipun beranggotakan mahasiswa kampus UNJA dan UIN STS Jambi, kegiatan GenBI Jambi berfokus langsung pada aksi nyata di tengah masyarakat, sekolah-sekolah di berbagai kabupaten/kota di Jambi, pasar tradisional, pelaku usaha mikro, dan desa binaan.',
    ],
];
?>
<section class="public-inner-hero py-16 md:py-24">
  <div class="article-container fade-up">
    <p class="eyebrow">About</p>
    <h1 class="page-title mt-5">Tentang GenBI Provinsi Jambi.</h1>
    <p class="lead mt-7">Halaman ini fokus pada identitas organisasi, visi, misi, tujuan, dan peran GenBI sebagai frontliners Bank Indonesia, change agents, serta future leaders.</p>
  </div>
</section>
<section class="bg-cream py-16 md:py-24">
  <div class="article-container prose-soft fade-up" id="about-content" data-ssr="true">
    <?php foreach ($aboutBlocks as $block): ?>
      <section class="border-t border-neutral-900/10 py-9 first:border-t-0 first:pt-0">
        <h2 class="serif text-3xl font-semibold tracking-tight text-neutral-950"><?= $e($block['title']) ?></h2>
        <p><?= $e($block['text']) ?></p>
      </section>
    <?php endforeach; ?>
  </div>
</section>
<section class="bg-[var(--surface-soft)] py-16 md:py-24">
  <div class="site-container grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
    <div class="fade-up">
      <p class="eyebrow">Role organisasi</p>
      <h2 class="section-title mt-4">Tiga peran utama yang perlu cepat terbaca.</h2>
    </div>
    <div class="fade-up soft-card overflow-hidden" id="role-list" data-ssr="true">
      <?php foreach ($roles as $index => $role): ?>
        <article class="soft-row grid gap-4 p-6 md:grid-cols-[80px_1fr]">
          <span class="serif text-4xl font-semibold text-blue-800">0<?= $index + 1 ?></span>
          <div>
            <h3 class="text-lg font-bold text-neutral-950"><?= $e($role['title']) ?></h3>
            <p class="mt-2 text-sm leading-7 text-neutral-600"><?= $e($role['text']) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<section class="border-t border-neutral-900/10 bg-cream py-16 md:py-24" id="faq-section">
  <div class="site-container">
    <div class="fade-up mx-auto max-w-3xl text-center">
      <p class="eyebrow">Tanya Jawab</p>
      <h2 class="section-title mt-4">Pertanyaan yang Sering Diajukan (FAQ)</h2>
      <p class="mx-auto mt-4 max-w-xl text-base leading-7 text-neutral-600">Informasi penting seputar identitas, peran organisasi, seleksi beasiswa, dan program kerja GenBI Provinsi Jambi.</p>
    </div>
    <div class="fade-up mx-auto mt-12 grid max-w-3xl gap-4" id="faq-accordion">
      <?php foreach ($faqs as $index => $faq): ?>
        <details class="faq-item group rounded-2xl border border-neutral-900/10 bg-white/95 p-5 md:p-6 shadow-sm transition-all duration-200 hover:border-[#114b9a]/30 hover:shadow-md" <?= $index === 0 ? 'open' : '' ?>>
          <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-neutral-950 focus:outline-none">
            <span class="faq-question text-base md:text-lg font-semibold tracking-tight text-neutral-900 transition-colors duration-200">
              <?= $e($faq['question']) ?>
            </span>
            <span class="faq-icon flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 text-[#114b9a]">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7"/></svg>
            </span>
          </summary>
          <div class="faq-content mt-4 border-t border-neutral-100 pt-4 text-sm md:text-base leading-7 text-neutral-600">
            <?= $faq['answer'] ?>
          </div>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<style>
/* ==========================================================================
   TOMBOL KEMBALI KE ATAS (BACK TO TOP) - HALAMAN ABOUT
   ========================================================================== */
.back-to-top {
  position: fixed !important;
  right: 1.5rem !important;
  bottom: 1.5rem !important;
  z-index: 9999 !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  width: 3rem !important;
  height: 3rem !important;
  border-radius: 9999px !important;
  background: #114b9a !important;
  color: #ffffff !important;
  border: 2px solid rgba(255, 255, 255, 0.9) !important;
  box-shadow: 0 6px 20px rgba(12, 53, 114, 0.35), 0 2px 6px rgba(0, 0, 0, 0.12) !important;
  cursor: pointer !important;
  opacity: 0 !important;
  visibility: hidden !important;
  pointer-events: none !important;
  transform: translateY(14px) scale(0.92) !important;
  transition: opacity 280ms cubic-bezier(0.16, 1, 0.3, 1),
              transform 280ms cubic-bezier(0.16, 1, 0.3, 1),
              visibility 280ms ease,
              background-color 200ms ease,
              box-shadow 200ms ease !important;
}

.back-to-top:hover {
  background: #0c3572 !important;
  color: #ffffff !important;
  transform: translateY(-2px) scale(1.06) !important;
  box-shadow: 0 10px 28px rgba(12, 53, 114, 0.45) !important;
}

.back-to-top:active {
  transform: translateY(0) scale(0.95) !important;
}

.back-to-top.is-visible {
  opacity: 1 !important;
  visibility: visible !important;
  pointer-events: auto !important;
  transform: translateY(0) scale(1) !important;
}

@media (max-width: 640px) {
  .back-to-top {
    right: 1rem !important;
    bottom: 1.25rem !important;
    width: 2.75rem !important;
    height: 2.75rem !important;
  }
}

/* ==========================================================================
   PUBLIC FAQ ACCORDION STYLES
   ========================================================================== */
.faq-item {
  border: 1px solid rgba(23, 23, 23, 0.08) !important;
  background-color: rgba(255, 255, 255, 0.95) !important;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
}

.faq-item:hover {
  border-color: rgba(17, 75, 154, 0.35) !important;
  box-shadow: 0 4px 12px rgba(17, 75, 154, 0.06) !important;
}

.faq-item[open] {
  border-color: rgba(17, 75, 154, 0.35) !important;
  background-color: #ffffff !important;
  box-shadow: 0 10px 25px -5px rgba(17, 75, 154, 0.08), 0 8px 10px -6px rgba(17, 75, 154, 0.04) !important;
}

.faq-item summary {
  display: flex !important;
  align-items: center !important;
  justify-content: space-between !important;
  cursor: pointer !important;
  list-style: none !important;
  user-select: none !important;
}

.faq-item summary::-webkit-details-marker {
  display: none !important;
}

.faq-item summary::marker {
  display: none !important;
}

.faq-item summary .faq-question {
  transition: color 0.2s ease !important;
}

.faq-item[open] summary .faq-question {
  color: #114b9a !important;
}

.faq-item .faq-icon {
  width: 2rem !important;
  height: 2rem !important;
  min-width: 2rem !important;
  min-height: 2rem !important;
  border-radius: 9999px !important;
  background-color: #eff6ff !important;
  color: #114b9a !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  flex-shrink: 0 !important;
  transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.2s ease, color 0.2s ease !important;
}

.faq-item .faq-icon svg {
  width: 1rem !important;
  height: 1rem !important;
  flex-shrink: 0 !important;
  stroke: currentColor !important;
  display: block !important;
  transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
}

.faq-item[open] summary .faq-icon {
  transform: rotate(180deg) !important;
  background-color: #114b9a !important;
  color: #ffffff !important;
}
</style>
