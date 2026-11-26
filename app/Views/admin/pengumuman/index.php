<?php

use App\Core\Paginator;

/** @var callable $e */
$items = $items ?? [];
$filters = $filters ?? [];
$total = $total ?? 0;
$page = (int) ($page ?? 1);
$perPage = (int) ($perPage ?? 10);
$totalPages = (int) ($totalPages ?? 1);

// Perhitungan nomor urut item
$startItem = ($page - 1) * $perPage + 1;
$endItem = min($page * $perPage, $total);

// Membangun parameter filter untuk dipertahankan di URL pagination
$filterParams = array_filter([
    'q' => $filters['q'] ?? '',
    'is_active' => $filters['is_active'] ?? '',
    'per_page' => $perPage !== 10 ? $perPage : null,
], static fn($v) => $v !== '' && $v !== null);
?>
<section class="mx-auto max-w-7xl">
    <!-- Header Bagian Atas -->
    <header class="cms-header slide-in flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="eyebrow">Admin CMS</p>
            <h1 class="section-title mt-2 text-2xl md:text-3xl font-extrabold text-neutral-900 tracking-tight">Pengumuman Resmi GenBI</h1>
            <p class="mt-1.5 max-w-2xl text-sm leading-6 text-neutral-600">
                Kelola informasi edaran, jadwal seleksi, pengumuman beasiswa, dan berkas lampiran resmi GenBI Jambi.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="/admin/pengumuman-add" class="btn btn-primary inline-flex items-center gap-2 px-5 py-2.5 shadow-md hover:shadow-lg transition-all rounded-xl font-bold text-sm bg-blue-900 hover:bg-blue-800 text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Pengumuman Baru</span>
            </a>
        </div>
    </header>

    <!-- Bilah Pencarian, Filter & Toolbar -->
    <section class="admin-card mt-6 p-4 md:p-6 shadow-sm border border-neutral-200/80 rounded-2xl bg-white">
        <form method="GET" action="/admin/pengumuman" id="pengumuman-filter-form" class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <!-- Smooth Search Box & Status Filter -->
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto flex-1">
                <div class="relative flex-1 max-w-md">
                    <input type="text" id="smooth-search-input" name="q" value="<?= $e($filters['q'] ?? '') ?>" placeholder="Cari judul atau isi pengumuman..." autocomplete="off" class="form-input w-full pl-11 pr-11 py-2.5 rounded-xl text-sm bg-white border-2 border-neutral-300 hover:border-blue-500 focus:border-blue-600 focus:ring-4 focus:ring-blue-50 text-neutral-800 font-semibold transition-all shadow-sm placeholder:text-neutral-400 placeholder:font-normal" />
                    <svg class="absolute left-3.5 top-3 w-5 h-5 text-neutral-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>

                    <!-- Indikator Loading saat memuat pencarian -->
                    <div id="search-spinner" class="absolute right-3.5 top-3 hidden">
                        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Filter Status Publikasi -->
                <select name="is_active" onchange="this.form.submit()" class="form-input rounded-xl text-sm border-2 border-neutral-300 hover:border-blue-500 py-2.5 px-3 focus:border-blue-600 focus:ring-4 focus:ring-blue-50 font-semibold text-neutral-700 w-auto bg-white shadow-sm transition-all">
                    <option value="">Semua Status</option>
                    <option value="1" <?= ($filters['is_active'] ?? '') === '1' ? 'selected' : '' ?>>Aktif (Ditayangkan)</option>
                    <option value="0" <?= ($filters['is_active'] ?? '') === '0' ? 'selected' : '' ?>>Nonaktif (Draft/Arsip)</option>
                </select>

                <?php if (!empty($filters['q']) || ($filters['is_active'] ?? '') !== ''): ?>
                    <a href="/admin/pengumuman" class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600 hover:text-red-700 self-center px-3 py-2.5 bg-red-50 hover:bg-red-100 rounded-xl border border-red-200 transition-colors shrink-0 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Reset Filter</span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Opsi Jumlah Baris & Summary -->
            <div class="flex items-center gap-3 text-sm text-neutral-600 font-medium w-full md:w-auto justify-end border-t md:border-t-0 pt-3 md:pt-0 border-neutral-100">
                <span>Show</span>
                <select name="per_page" onchange="this.form.submit()" class="form-input rounded-lg text-xs border-neutral-300 py-1 px-2.5 font-bold text-blue-900 focus:border-blue-600">
                    <?php foreach ([10, 25, 50, 100] as $opt): ?>
                        <option value="<?= $opt ?>" <?= $opt === $perPage ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
                <span>entries</span>
            </div>
        </form>

        <!-- Informasi Menampilkan Jumlah Data -->
        <?php if ($total > 0): ?>
            <div class="mt-3 text-xs font-medium text-neutral-500 bg-neutral-50 px-3 py-1.5 rounded-lg inline-block border border-neutral-200">
                Menampilkan <strong class="text-neutral-800"><?= $startItem ?> - <?= $endItem ?></strong> dari total <strong class="text-blue-900"><?= number_format($total) ?></strong> pengumuman
            </div>
        <?php endif; ?>

        <!-- Tabel Responsive -->
        <div class="mt-4 overflow-x-auto rounded-xl border border-neutral-200/80">
            <?php if ($items === []): ?>
                <?php if (!empty($filters['q']) || ($filters['is_active'] ?? '') !== ''): ?>
                    <!-- State 1: Data Tidak Ditemukan Karena Filter -->
                    <div class="p-12 text-center bg-amber-50/40 border-2 border-dashed border-amber-300/80 rounded-2xl max-w-2xl mx-auto my-6 shadow-sm">
                        <div class="w-16 h-16 bg-amber-100/80 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-neutral-800">Pengumuman Tidak Ditemukan</h3>
                        <p class="text-xs text-neutral-600 mt-2 leading-relaxed">
                            Tidak ada pengumuman yang sesuai dengan kriteria filter Anda
                            <?php if (!empty($filters['q'])): ?>
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-900 font-bold rounded">"<?= $e($filters['q']) ?>"</span>
                            <?php endif; ?>
                            <?php if (($filters['is_active'] ?? '') !== ''): ?>
                                dengan status <span class="font-bold uppercase text-neutral-700"><?= $filters['is_active'] === '1' ? 'Aktif' : 'Nonaktif' ?></span>
                            <?php endif; ?>.
                        </p>
                        <div class="mt-6">
                            <a href="/admin/pengumuman" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-blue-900 hover:bg-blue-800 rounded-xl shadow transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>Reset Filter & Tampilkan Semua</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- State 2: Belum Ada Data Pengumuman Sama Sekali -->
                    <div class="p-14 text-center border-2 border-dashed border-neutral-200 rounded-2xl bg-neutral-50/50 max-w-2xl mx-auto my-6">
                        <div class="w-16 h-16 bg-blue-50 text-blue-900 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                        </div>
                        <p class="text-base font-bold text-neutral-800">Daftar Pengumuman Masih Kosong</p>
                        <p class="text-xs text-neutral-500 mt-1 max-w-md mx-auto leading-relaxed">Belum ada pengumuman resmi atau edaran informasi yang ditambahkan ke sistem Admin GenBI.</p>
                        <div class="mt-6">
                            <a href="/admin/pengumuman-add" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-blue-900 hover:bg-blue-800 rounded-xl shadow transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Tambah Pengumuman Pertama</span>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-neutral-200 bg-neutral-50/75 text-neutral-600 uppercase text-xs tracking-wider font-semibold">
                            <th class="p-3.5 rounded-tl-xl w-14 text-center">No</th>
                            <th class="p-3.5 min-w-[260px]">Judul & Informasi</th>
                            <th class="p-3.5 hidden sm:table-cell min-w-[140px]">Lampiran</th>
                            <th class="p-3.5 hidden md:table-cell min-w-[160px]">Periode Tayang</th>
                            <th class="p-3.5 hidden lg:table-cell text-center w-20">Urutan</th>
                            <th class="p-3.5 text-center w-28">Status</th>
                            <th class="p-3.5 rounded-tr-xl text-right min-w-[140px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="admin-pengumuman-tbody" class="divide-y divide-neutral-150">
                        <?php foreach ($items as $index => $item): ?>
                            <tr class="pengumuman-row hover:bg-blue-50/25 transition-colors" data-search="<?= strtolower($e($item['title'] . ' ' . ($item['content'] ?? '') . ' ' . ($item['file_attachment'] ?? ''))) ?>">
                                <!-- Nomor Urut -->
                                <td class="p-3.5 text-center text-xs font-bold text-neutral-400 align-top">
                                    <?= $startItem + $index ?>
                                </td>

                                <!-- Judul & Isi -->
                                <td class="p-3.5 align-top">
                                    <p class="font-bold text-neutral-900 leading-snug hover:text-blue-900 transition-colors">
                                        <?= $e($item['title']) ?>
                                    </p>
                                    <?php if (!empty($item['excerpt'])): ?>
                                        <p class="text-xs text-neutral-500 mt-1 leading-relaxed line-clamp-2">
                                            <?= $e($item['excerpt']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <!-- Informasi Mobile & Tablet -->
                                    <div class="md:hidden mt-2 pt-2 border-t border-neutral-100 flex flex-wrap gap-2 text-xs text-neutral-500">
                                        <?php if (!empty($item['file_attachment'])): ?>
                                            <a href="<?= $e($item['file_attachment']) ?>" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-100 hover:bg-blue-100">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                </svg>
                                                <span>Berkas Lampiran</span>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (!empty($item['start_date']) || !empty($item['end_date'])): ?>
                                            <span class="inline-flex items-center gap-1 text-[11px] text-neutral-600 bg-neutral-100 px-2 py-0.5 rounded">
                                                <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span><?= $e($item['start_date'] ?: 'Sekarang') ?> s/d <?= $e($item['end_date'] ?: 'Seterusnya') ?></span>
                                            </span>
                                        <?php endif; ?>
                                        <span class="text-[11px] text-neutral-400">Urutan: <strong><?= (int) ($item['sort_order'] ?? 0) ?></strong></span>
                                    </div>
                                </td>

                                <!-- Lampiran (Desktop/Tablet) -->
                                <td class="p-3.5 align-top hidden sm:table-cell">
                                    <?php if (!empty($item['file_attachment'])): 
                                        $ext = strtolower(pathinfo($item['file_attachment'], PATHINFO_EXTENSION));
                                    ?>
                                        <a href="<?= $e($item['file_attachment']) ?>" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-900 border border-blue-200 hover:bg-blue-100 transition-colors" title="Buka lampiran: <?= $e($item['file_attachment']) ?>">
                                            <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            <span class="uppercase font-bold"><?= $e($ext ?: 'FILE') ?></span>
                                            <span class="text-neutral-500 text-[10px]">Lihat</span>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-xs text-neutral-400 italic">Tanpa Berkas</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Periode Tayang -->
                                <td class="p-3.5 align-top hidden md:table-cell">
                                    <div class="text-xs space-y-1">
                                        <div class="flex items-center gap-1.5 text-neutral-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span class="text-neutral-400">Mulai:</span>
                                            <strong class="font-medium"><?= $e($item['start_date'] ? date('d M Y', strtotime($item['start_date'])) : 'Langsung Tayang') ?></strong>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-neutral-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span class="text-neutral-400">Selesai:</span>
                                            <strong class="font-medium"><?= $e($item['end_date'] ? date('d M Y', strtotime($item['end_date'])) : 'Tidak Ada Batas') ?></strong>
                                        </div>
                                    </div>
                                </td>

                                <!-- Urutan (Sort Order) -->
                                <td class="p-3.5 align-top text-center hidden lg:table-cell">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-neutral-100 text-neutral-700 border border-neutral-200">
                                        <?= (int) ($item['sort_order'] ?? 0) ?>
                                    </span>
                                </td>

                                <!-- Status Aktif -->
                                <td class="p-3.5 align-top text-center">
                                    <?php if (!empty($item['is_active'])): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span>Aktif</span>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-neutral-100 text-neutral-600 border border-neutral-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-neutral-400"></span>
                                            <span>Nonaktif</span>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Aksi -->
                                <td class="p-3.5 align-top text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <?php if (!empty($item['file_attachment'])): ?>
                                            <a href="<?= $e($item['file_attachment']) ?>" target="_blank" class="px-2.5 py-1.5 text-xs font-semibold text-neutral-700 bg-neutral-100 hover:bg-neutral-200 rounded-lg transition-colors inline-flex items-center gap-1" title="Unduh / Buka File">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Berkas</span>
                                            </a>
                                        <?php endif; ?>

                                        <a href="/admin/pengumuman-edit?id=<?= (int) $item['id'] ?>" class="px-2.5 py-1.5 text-xs font-semibold text-blue-900 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            <span>Edit</span>
                                        </a>

                                        <button type="button" onclick="deletePengumumanItem(<?= (int) $item['id'] ?>, '<?= addslashes((string) $item['title']) ?>')" class="px-2.5 py-1.5 text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Container Empty State saat pencarian live client-side tidak cocok -->
                <div id="live-empty-search" class="hidden p-12 text-center bg-amber-50/40 border-2 border-dashed border-amber-300/80 rounded-2xl max-w-2xl mx-auto my-6 shadow-sm">
                    <div class="w-16 h-16 bg-amber-100/80 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-neutral-800">Tidak Ada Hasil Yang Cocok</h3>
                    <p class="text-xs text-neutral-600 mt-2">Tidak ada pengumuman yang cocok dengan kata kunci <span id="live-keyword-span" class="px-2 py-0.5 bg-amber-100 text-amber-900 font-bold rounded"></span>.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination Bar -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-neutral-150 pt-4" aria-label="Pagination">
                <div class="flex items-center gap-1">
                    <?php if ($page > 1): ?>
                        <a class="pager-button px-3.5 py-1.5 rounded-lg border border-neutral-300 text-xs font-semibold text-neutral-700 hover:bg-neutral-100 transition-colors" href="/admin/pengumuman?<?= $e(Paginator::buildQuery($page - 1, $filterParams)) ?>">Sebelumnya</a>
                    <?php else: ?>
                        <span class="pager-button px-3.5 py-1.5 rounded-lg border border-neutral-200 text-xs font-semibold text-neutral-300 cursor-not-allowed" aria-disabled="true">Sebelumnya</span>
                    <?php endif; ?>
                </div>

                <div class="flex items-center gap-1.5 flex-wrap justify-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i === $page): ?>
                            <span class="pager-button is-active px-3.5 py-1.5 rounded-lg bg-blue-900 text-white text-xs font-bold shadow-sm" aria-current="page"><?= $i ?></span>
                        <?php else: ?>
                            <a class="pager-button px-3.5 py-1.5 rounded-lg border border-neutral-300 text-xs font-semibold text-neutral-700 hover:bg-neutral-100 transition-colors" href="/admin/pengumuman?<?= $e(Paginator::buildQuery($i, $filterParams)) ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>

                <div class="flex items-center gap-1">
                    <?php if ($page < $totalPages): ?>
                        <a class="pager-button px-3.5 py-1.5 rounded-lg border border-neutral-300 text-xs font-semibold text-neutral-700 hover:bg-neutral-100 transition-colors" href="/admin/pengumuman?<?= $e(Paginator::buildQuery($page + 1, $filterParams)) ?>">Berikutnya</a>
                    <?php else: ?>
                        <span class="pager-button px-3.5 py-1.5 rounded-lg border border-neutral-200 text-xs font-semibold text-neutral-300 cursor-not-allowed" aria-disabled="true">Berikutnya</span>
                    <?php endif; ?>
                </div>
            </nav>
        <?php endif; ?>
    </section>
</section>

<!-- Include SweetAlert2 Library untuk Konfirmasi Hapus Modern -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Skrip Smooth Search & Delete Modal -->
<script>
    (function() {
        var searchInput = document.getElementById('smooth-search-input');
        var searchForm = document.getElementById('pengumuman-filter-form');
        var spinner = document.getElementById('search-spinner');
        var rows = document.querySelectorAll('.pengumuman-row');
        var liveEmpty = document.getElementById('live-empty-search');
        var liveSpan = document.getElementById('live-keyword-span');

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                var keyword = e.target.value.toLowerCase().trim();
                var visibleCount = 0;
                if (rows.length > 0) {
                    rows.forEach(function(row) {
                        var searchData = (row.getAttribute('data-search') || row.textContent).toLowerCase();
                        if (keyword === '' || searchData.includes(keyword)) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });
                    if (visibleCount === 0 && keyword !== '') {
                        if (liveEmpty) liveEmpty.classList.remove('hidden');
                        if (liveSpan) liveSpan.textContent = '"' + keyword + '"';
                    } else {
                        if (liveEmpty) liveEmpty.classList.add('hidden');
                    }
                }
            });
        }

        if (searchForm && spinner) {
            searchForm.addEventListener('submit', function() {
                spinner.classList.remove('hidden');
            });
        }
    })();

    // Fungsi Hapus Pengumuman dengan SweetAlert2 & Fallback
    function deletePengumumanItem(id, title) {
        var execDelete = function() {
            var token = document.querySelector('meta[name="csrf-token"]')?.content || '';

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Sedang Menghapus...',
                    text: 'Mohon tunggu sejenak, sistem sedang menghapus data pengumuman.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            fetch('/admin/pengumuman/' + id + '/delete', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({})
            })
            .then(async function(res) {
                var rawText = await res.text();
                var data;
                try {
                    data = JSON.parse(rawText);
                } catch (e) {
                    var cleanErr = rawText.replace(/<[^>]*>?/gm, ' ').replace(/\s+/g, ' ').trim();
                    throw new Error(cleanErr || ('Gagal menghapus data (Status: ' + res.status + ')'));
                }
                if (!res.ok) {
                    throw new Error(data.error || data.details || 'Gagal menghapus pengumuman dari server');
                }
                return data;
            })
            .then(function(res) {
                if (res.data && res.data.deleted) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Dihapus!',
                            text: 'Pengumuman "' + title + '" telah berhasil dihapus.',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        alert('Pengumuman berhasil dihapus!');
                        window.location.reload();
                    }
                } else {
                    throw new Error(res.error || 'Terjadi kesalahan sistem.');
                }
            })
            .catch(function(error) {
                console.error('Delete Error:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menghapus',
                        text: error.message || 'Terjadi kesalahan jaringan atau server saat menghapus pengumuman.',
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'Tutup'
                    });
                } else {
                    alert('Gagal menghapus: ' + (error.message || 'Terjadi kesalahan jaringan'));
                }
            });
        };

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Pengumuman Ini?',
                html: 'Anda akan menghapus pengumuman:<br><strong class="text-neutral-900 font-bold">' + title + '</strong>.<br><span class="text-xs text-red-600 mt-2.5 inline-block font-medium bg-red-50 px-3 py-1 rounded-lg border border-red-200">Data yang telah dihapus tidak dapat dipulihkan kembali.</span>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Permanen!',
                cancelButtonText: 'Batal',
                focusCancel: true,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    execDelete();
                }
            });
        } else {
            if (confirm('Apakah Anda yakin ingin menghapus pengumuman "' + title + '" secara permanen?')) {
                execDelete();
            }
        }
    }
</script>
