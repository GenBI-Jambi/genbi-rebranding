<?php

/** @var callable $e */
$item = $item ?? null;
$isEdit = !empty($item && ($item['id'] ?? 0) > 0);
?>
<section class="mx-auto max-w-7xl">
    <!-- Header Navigation & Title -->
    <header class="cms-header slide-in flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <a href="/admin/pengumuman" class="inline-flex items-center gap-1.5 text-xs font-bold text-neutral-500 hover:text-blue-900 mb-3 transition-colors group">
                <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali ke Daftar Pengumuman</span>
            </a>
            <h1 class="section-title text-2xl md:text-3xl font-extrabold text-neutral-900 tracking-tight">
                <?= $isEdit ? 'Edit Pengumuman Resmi' : 'Tambah Pengumuman Baru' ?>
            </h1>
            <p class="mt-1 text-sm text-neutral-600">
                <?= $isEdit ? 'Perbaiki isi pengumuman, perpanjang periode tayang, atau perbarui lampiran dokumen.' : 'Tulis judul pengumuman, detail informasi edaran, jadwal tayang, dan unggah berkas lampiran resmi.' ?>
            </p>
        </div>
        <div class="hidden md:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50/80 border border-blue-200 text-blue-900 text-xs font-semibold">
            <span class="w-2 h-2 rounded-full <?= $isEdit ? 'bg-amber-500' : 'bg-blue-600' ?>"></span>
            <span><?= $isEdit ? 'Mode Edit Data Pengumuman' : 'Modul Pengumuman Resmi' ?></span>
        </div>
    </header>

    <!-- Banner Khusus Mode Edit -->
    <?php if ($isEdit): ?>
        <div class="mt-6 p-5 bg-gradient-to-r from-blue-950 via-blue-900 to-indigo-900 rounded-2xl shadow-md text-white flex flex-col md:flex-row md:items-center justify-between gap-4 border border-blue-800">
            <div class="flex items-start md:items-center gap-4">
                <div class="p-3 bg-white/10 backdrop-blur-md rounded-xl text-blue-200 shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-amber-400 text-neutral-900 font-extrabold text-[11px] uppercase tracking-wider shadow-xs">Mode Edit</span>
                        <span class="text-xs font-bold text-blue-200">ID Pengumuman: #<?= (int) ($item['id'] ?? 0) ?></span>
                    </div>
                    <h2 class="text-base md:text-lg font-extrabold tracking-tight text-white mt-1">
                        <?= $e($item['title'] ?? '') ?>
                    </h2>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1.5 text-xs font-medium text-blue-100/90">
                        <span><strong class="text-blue-300 font-semibold">Status:</strong> <?= !empty($item['is_active']) ? '🟢 Aktif' : '⚪ Nonaktif' ?></span>
                        <span class="text-blue-300/60">•</span>
                        <span><strong class="text-blue-300 font-semibold">Urutan:</strong> <?= (int) ($item['sort_order'] ?? 0) ?></span>
                        <?php if (!empty($item['start_date'])): ?>
                            <span class="text-blue-300/60">•</span>
                            <span><strong class="text-blue-300 font-semibold">Mulai:</strong> <?= $e($item['start_date']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if (!empty($item['file_attachment'])): ?>
                <div class="flex-shrink-0">
                    <a href="<?= $e($item['file_attachment']) ?>" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white text-blue-900 hover:bg-blue-50 font-extrabold text-xs rounded-xl shadow transition-all">
                        <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        <span>Pratinjau Berkas Lampiran</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- CONTAINER ALERT KESALAHAN VALIDASI (Error Alert Banner) -->
    <div id="form-error-alert" class="hidden mt-6 p-4 md:p-5 rounded-2xl bg-red-50 border-2 border-red-300 text-red-900 shadow-sm transition-all" role="alert">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0 p-1 bg-red-100 text-red-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-red-900" id="error-alert-title">Validasi Formulir Gagal</h3>
                <p class="text-xs text-red-700 mt-1" id="error-alert-subtitle">Mohon periksa dan perbaiki isian data di bawah ini:</p>
                <ul class="mt-2 text-xs text-red-800 list-disc list-inside space-y-1 font-medium" id="error-list"></ul>
            </div>
            <button type="button" onclick="hideErrorAlert()" class="text-red-500 hover:text-red-700 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Form Utama -->
    <form id="pengumuman-form" novalidate class="admin-card mt-6 p-6 md:p-10 shadow-sm border border-neutral-200/80 rounded-2xl bg-white space-y-8" onsubmit="submitPengumumanForm(event)">

        <!-- BLOK 1: INFORMASI UTAMA PENGUMUMAN -->
        <div>
            <div class="flex items-center justify-between pb-3 mb-6 border-b border-neutral-200 text-neutral-900">
                <div class="flex items-center gap-2">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900 text-white text-xs font-bold">1</span>
                    <h3 class="text-base font-extrabold tracking-wide uppercase">Informasi & Konten Pengumuman</h3>
                </div>
                <span class="text-xs text-neutral-500"><strong class="text-red-500">*</strong> Wajib diisi</span>
            </div>

            <div class="space-y-6">
                <!-- Judul Pengumuman -->
                <div>
                    <label for="title-input" class="block text-sm font-bold text-neutral-800 tracking-wide">
                        Judul Pengumuman <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-2">
                        <input type="text" id="title-input" name="title" value="<?= $e($item['title'] ?? '') ?>" placeholder="Contoh: Pengumuman Hasil Wawancara Penerima Beasiswa Bank Indonesia 2026" class="form-input w-full rounded-xl border border-neutral-300 bg-white py-2.5 px-4 text-sm font-semibold text-neutral-900 shadow-sm focus:border-blue-900 focus:ring-4 focus:ring-blue-100 placeholder:text-neutral-400 placeholder:font-normal transition-all" required minlength="3" />
                    </div>
                    <p id="error-title" class="text-xs text-red-600 font-semibold mt-1.5 hidden"></p>
                    <p class="mt-1 text-[11px] text-neutral-500">Tuliskan judul pengumuman dengan jelas, lugas, dan mudah dimengerti pembaca.</p>
                </div>

                <!-- Isi / Konten Pengumuman -->
                <div>
                    <div class="flex items-center justify-between">
                        <label for="content-input" class="block text-sm font-bold text-neutral-800 tracking-wide">
                            Isi / Ringkasan Pengumuman
                        </label>
                        <span id="content-counter" class="text-[11px] text-neutral-400 font-medium">0 Karakter</span>
                    </div>
                    <div class="mt-2">
                        <textarea id="content-input" name="content" rows="6" placeholder="Tuliskan keterangan detail pengumuman, petunjuk teknis, berkas yang perlu dibawa, atau kontak narahubung..." class="form-input w-full rounded-xl border border-neutral-300 bg-white py-3 px-4 text-sm leading-relaxed text-neutral-900 shadow-sm focus:border-blue-900 focus:ring-4 focus:ring-blue-100 placeholder:text-neutral-400 transition-all font-normal"><?= $e($item['content'] ?? '') ?></textarea>
                    </div>
                    <p id="error-content" class="text-xs text-red-600 font-semibold mt-1.5 hidden"></p>
                    <p class="mt-1 text-[11px] text-neutral-500">Isi ini akan ditampilkan di halaman depan beranda atau modal pratinjau pengumuman.</p>
                </div>
            </div>
        </div>

        <!-- BLOK 2: BERKAS LAMPIRAN RESMI -->
        <div>
            <div class="flex items-center gap-2 pb-3 mb-6 border-b border-neutral-200 text-neutral-900 mt-6">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900 text-white text-xs font-bold">2</span>
                <h3 class="text-base font-extrabold tracking-wide uppercase">Berkas Dokumen Lampiran (Opsional)</h3>
            </div>

            <div class="space-y-4">
                <p class="text-xs text-neutral-600">
                    Unggah berkas dokumen PDF, Surat Edaran Word, Formulir Excel, atau Gambar Pengumuman (Maksimal 25 MB).
                </p>

                <!-- Input Hidden URL Berkas -->
                <input type="hidden" id="file-attachment-input" name="file_attachment" value="<?= $e($item['file_attachment'] ?? '') ?>" />

                <!-- Dropzone Area -->
                <div id="file-dropzone" class="border-2 border-dashed border-neutral-300 hover:border-blue-700 bg-neutral-50/70 hover:bg-blue-50/30 rounded-2xl p-6 md:p-8 text-center transition-all cursor-pointer relative group">
                    <input type="file" id="attachment-file-input" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="handleFileSelected(event)" />
                    <div class="flex flex-col items-center justify-center pointer-events-none">
                        <div class="w-14 h-14 rounded-full bg-blue-100 text-blue-900 flex items-center justify-center mb-3 shadow-inner group-hover:scale-105 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-neutral-800">
                            Klik untuk memilih berkas atau seret & lepas berkas ke sini
                        </p>
                        <p class="text-xs text-neutral-500 mt-1">
                            Format yang didukung: <span class="font-semibold text-neutral-700">PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, WEBP</span> (Maks. 25 MB)
                        </p>
                    </div>
                </div>

                <!-- Status Unggah Berkas -->
                <div id="file-upload-status" class="hidden text-xs font-semibold text-blue-900 bg-blue-50 p-3 rounded-xl border border-blue-200 flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-blue-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span id="upload-status-text">Mengunggah berkas lampiran ke server...</span>
                </div>

                <!-- Preview File Terpilih / Tersimpan -->
                <div id="file-preview-card" class="<?= empty($item['file_attachment']) ? 'hidden' : '' ?> p-4 bg-blue-50/60 rounded-xl border border-blue-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-10 h-10 rounded-lg bg-blue-900 text-white flex items-center justify-center shrink-0 font-bold text-xs uppercase" id="preview-ext-badge">
                            <?= !empty($item['file_attachment']) ? strtoupper(pathinfo($item['file_attachment'], PATHINFO_EXTENSION) ?: 'FILE') : 'FILE' ?>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-neutral-900 truncate" id="preview-file-name">
                                <?= !empty($item['file_attachment']) ? basename($item['file_attachment']) : 'berkas_lampiran' ?>
                            </p>
                            <p class="text-[11px] text-blue-800 truncate" id="preview-file-path">
                                <?= $e($item['file_attachment'] ?? '') ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a id="preview-download-link" href="<?= $e($item['file_attachment'] ?? '#') ?>" target="_blank" class="px-3 py-1.5 text-xs font-bold text-blue-900 bg-white hover:bg-blue-50 border border-blue-200 rounded-lg shadow-xs transition-colors inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            <span>Buka</span>
                        </a>
                        <button type="button" onclick="removeAttachmentFile()" class="px-3 py-1.5 text-xs font-bold text-red-600 bg-white hover:bg-red-50 border border-red-200 rounded-lg shadow-xs transition-colors inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Hapus</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- BLOK 3: JADWAL PENAYANGAN & PENGATURAN STATUS -->
        <div>
            <div class="flex items-center gap-2 pb-3 mb-6 border-b border-neutral-200 text-neutral-900 mt-6">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900 text-white text-xs font-bold">3</span>
                <h3 class="text-base font-extrabold tracking-wide uppercase">Jadwal Penayangan & Visibilitas</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Tanggal Mulai Tayang -->
                <div>
                    <label for="start-date-input" class="block text-sm font-bold text-neutral-800 tracking-wide">
                        Tanggal Mulai Tayang
                    </label>
                    <div class="mt-2">
                        <input type="date" id="start-date-input" name="start_date" value="<?= $e($item['start_date'] ?? '') ?>" class="form-input w-full rounded-xl border border-neutral-300 bg-white py-2.5 px-4 text-sm font-medium text-neutral-800 shadow-sm focus:border-blue-900 focus:ring-4 focus:ring-blue-100 transition-all" />
                    </div>
                    <p id="error-start_date" class="text-xs text-red-600 font-semibold mt-1.5 hidden"></p>
                    <p class="mt-1 text-[11px] text-neutral-500">Kosongkan jika ingin langsung aktif hari ini.</p>
                </div>

                <!-- Tanggal Selesai Tayang -->
                <div>
                    <label for="end-date-input" class="block text-sm font-bold text-neutral-800 tracking-wide">
                        Tanggal Selesai Tayang
                    </label>
                    <div class="mt-2">
                        <input type="date" id="end-date-input" name="end_date" value="<?= $e($item['end_date'] ?? '') ?>" class="form-input w-full rounded-xl border border-neutral-300 bg-white py-2.5 px-4 text-sm font-medium text-neutral-800 shadow-sm focus:border-blue-900 focus:ring-4 focus:ring-blue-100 transition-all" />
                    </div>
                    <p id="error-end_date" class="text-xs text-red-600 font-semibold mt-1.5 hidden"></p>
                    <p class="mt-1 text-[11px] text-neutral-500">Kosongkan jika pengumuman berlaku seterusnya.</p>
                </div>

                <!-- Urutan (Sort Order) -->
                <div>
                    <label for="sort-order-input" class="block text-sm font-bold text-neutral-800 tracking-wide">
                        Urutan Tampil (Prioritas)
                    </label>
                    <div class="mt-2">
                        <input type="number" id="sort-order-input" name="sort_order" value="<?= (int) ($item['sort_order'] ?? 0) ?>" min="0" max="9999" class="form-input w-full rounded-xl border border-neutral-300 bg-white py-2.5 px-4 text-sm font-bold text-neutral-900 shadow-sm focus:border-blue-900 focus:ring-4 focus:ring-blue-100 transition-all" />
                    </div>
                    <p id="error-sort_order" class="text-xs text-red-600 font-semibold mt-1.5 hidden"></p>
                    <p class="mt-1 text-[11px] text-neutral-500">Nilai lebih kecil (0, 1, 2) tayang paling atas.</p>
                </div>

                <!-- Status Publikasi -->
                <div>
                    <label for="is-active-input" class="block text-sm font-bold text-neutral-800 tracking-wide">
                        Status Publikasi
                    </label>
                    <div class="mt-2">
                        <select id="is-active-input" name="is_active" class="form-input w-full rounded-xl border border-neutral-300 bg-white py-2.5 px-4 text-sm font-bold text-neutral-800 shadow-sm focus:border-blue-900 focus:ring-4 focus:ring-blue-100 transition-all">
                            <option value="1" <?= empty($item) || !empty($item['is_active']) ? 'selected' : '' ?>>🟢 Aktif (Ditayangkan)</option>
                            <option value="0" <?= !empty($item) && empty($item['is_active']) ? 'selected' : '' ?>>⚪ Nonaktif (Draft/Arsip)</option>
                        </select>
                    </div>
                    <p class="mt-1 text-[11px] text-neutral-500">Pilih aktif agar tayang pada beranda publik.</p>
                </div>
            </div>
        </div>

        <!-- TOMBOL AKSI SUBMIT / BATAL -->
        <div class="pt-6 border-t border-neutral-200 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
            <a href="/admin/pengumuman" class="w-full sm:w-auto px-6 py-2.5 text-center text-sm font-bold text-neutral-700 bg-neutral-100 hover:bg-neutral-200 rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" id="btn-submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-2.5 text-sm font-bold text-white bg-blue-900 hover:bg-blue-800 rounded-xl shadow-md hover:shadow-lg transition-all transform active:scale-95 disabled:opacity-50 disabled:pointer-events-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span id="btn-submit-text"><?= $isEdit ? 'Simpan Perubahan' : 'Terbitkan Pengumuman' ?></span>
            </button>
        </div>
    </form>
</section>

<!-- SweetAlert2 Library untuk Dialog Interaktif -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Hitung karakter konten textarea
    (function() {
        var contentInput = document.getElementById('content-input');
        var counter = document.getElementById('content-counter');
        if (contentInput && counter) {
            var updateCount = function() {
                var len = contentInput.value.length;
                counter.textContent = len + ' Karakter';
            };
            contentInput.addEventListener('input', updateCount);
            updateCount();
        }
    })();

    // Sembunyikan Alert Error
    function hideErrorAlert() {
        var alertEl = document.getElementById('form-error-alert');
        if (alertEl) alertEl.classList.add('hidden');
    }

    // Tampilkan Alert Error dengan rincian
    function showErrorAlert(title, errors) {
        var alertEl = document.getElementById('form-error-alert');
        var titleEl = document.getElementById('error-alert-title');
        var listEl = document.getElementById('error-list');
        if (!alertEl || !listEl) return;

        if (titleEl) titleEl.textContent = title || 'Validasi Formulir Gagal';
        listEl.innerHTML = '';

        if (Array.isArray(errors) && errors.length > 0) {
            errors.forEach(function(msg) {
                var li = document.createElement('li');
                li.textContent = msg;
                listEl.appendChild(li);
            });
        } else if (typeof errors === 'string') {
            var li = document.createElement('li');
            li.textContent = errors;
            listEl.appendChild(li);
        }

        alertEl.classList.remove('hidden');
        alertEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Sorot input merah dan tampilkan pesan error per field
    function setFieldError(fieldId, errorMsg) {
        var inputEl = document.getElementById(fieldId + '-input');
        var errorEl = document.getElementById('error-' + fieldId);

        if (inputEl) {
            inputEl.classList.add('border-red-500', 'focus:border-red-600', 'focus:ring-red-100', 'bg-red-50/20');
            inputEl.classList.remove('border-neutral-300', 'focus:border-blue-900', 'focus:ring-blue-100');
        }

        if (errorEl) {
            errorEl.textContent = errorMsg;
            errorEl.classList.remove('hidden');
        }
    }

    // Bersihkan semua sorotan error
    function clearFieldErrors() {
        hideErrorAlert();
        ['title', 'content', 'start_date', 'end_date', 'sort_order'].forEach(function(field) {
            var inputEl = document.getElementById(field + '-input');
            var errorEl = document.getElementById('error-' + field);
            if (inputEl) {
                inputEl.classList.remove('border-red-500', 'focus:border-red-600', 'focus:ring-red-100', 'bg-red-50/20');
                inputEl.classList.add('border-neutral-300', 'focus:border-blue-900', 'focus:ring-blue-100');
            }
            if (errorEl) {
                errorEl.textContent = '';
                errorEl.classList.add('hidden');
            }
        });
    }

    // Handle File Attachment Selection & Upload
    var selectedAttachmentFile = null;

    function handleFileSelected(event) {
        var file = event.target.files && event.target.files[0];
        if (!file) return;

        // Validasi ukuran file (Max 25 MB)
        var maxSizeBytes = 25 * 1024 * 1024;
        if (file.size > maxSizeBytes) {
            showErrorAlert('Ukuran File Melebihi Batas', [
                'Ukuran file yang dipilih (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB) melebihi batas maksimal 25 MB.'
            ]);
            event.target.value = '';
            return;
        }

        selectedAttachmentFile = file;

        // Update preview UI sementara
        var card = document.getElementById('file-preview-card');
        var nameEl = document.getElementById('preview-file-name');
        var pathEl = document.getElementById('preview-file-path');
        var badgeEl = document.getElementById('preview-ext-badge');
        var ext = file.name.split('.').pop().toUpperCase();

        if (card) card.classList.remove('hidden');
        if (nameEl) nameEl.textContent = file.name;
        if (pathEl) pathEl.textContent = 'Siap diunggah (' + (file.size / 1024).toFixed(1) + ' KB)';
        if (badgeEl) badgeEl.textContent = ext;
    }

    function removeAttachmentFile() {
        selectedAttachmentFile = null;
        var fileInput = document.getElementById('attachment-file-input');
        var hiddenInput = document.getElementById('file-attachment-input');
        var card = document.getElementById('file-preview-card');

        if (fileInput) fileInput.value = '';
        if (hiddenInput) hiddenInput.value = '';
        if (card) card.classList.add('hidden');
    }

    // Fungsi Upload File ke Server
    async function uploadAttachmentFile(file, csrfToken) {
        var statusContainer = document.getElementById('file-upload-status');
        var statusText = document.getElementById('upload-status-text');

        if (statusContainer) statusContainer.classList.remove('hidden');
        if (statusText) statusText.textContent = 'Mengunggah ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)...';

        var formData = new FormData();
        formData.append('file', file);

        try {
            var res = await fetch('/admin/pengumuman/upload', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            var rawText = await res.text();
            var json;
            try {
                json = JSON.parse(rawText);
            } catch (e) {
                var cleanErr = rawText.replace(/<[^>]*>?/gm, ' ').replace(/\s+/g, ' ').trim();
                console.error('Upload Error Server Response:', rawText);
                throw new Error(cleanErr || ('Gagal mengunggah berkas (Status: ' + res.status + ')'));
            }

            if (!res.ok) {
                throw new Error(json.error || json.details || 'Gagal mengunggah berkas.');
            }

            if (json.data && json.data.url) {
                var hiddenInput = document.getElementById('file-attachment-input');
                if (hiddenInput) hiddenInput.value = json.data.url;
                return json.data.url;
            } else {
                throw new Error('Server tidak mengembalikan URL berkas.');
            }
        } finally {
            if (statusContainer) statusContainer.classList.add('hidden');
        }
    }

    // Submit Handler Form Pengumuman
    async function submitPengumumanForm(event) {
        event.preventDefault();
        clearFieldErrors();

        var form = event.target;
        var btn = document.getElementById('btn-submit');
        var btnText = document.getElementById('btn-submit-text');
        var originalBtnText = btnText ? btnText.textContent : 'Simpan';
        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        var title = form.title.value.trim();
        var content = form.content.value.trim();
        var startDate = form.start_date.value;
        var endDate = form.end_date.value;
        var sortOrder = parseInt(form.sort_order.value, 10) || 0;
        var isActive = form.is_active.value === '1' ? 1 : 0;
        var fileAttachment = document.getElementById('file-attachment-input')?.value || '';

        // Validasi Sisi Klien
        var clientErrors = [];
        if (!title || title.length < 3) {
            clientErrors.push('Judul pengumuman wajib diisi minimal 3 karakter.');
            setFieldError('title', 'Judul pengumuman wajib diisi minimal 3 karakter.');
        }

        if (startDate && endDate && startDate > endDate) {
            clientErrors.push('Tanggal selesai tayang tidak boleh lebih awal dari tanggal mulai tayang.');
            setFieldError('end_date', 'Tanggal selesai tayang harus sama atau setelah tanggal mulai.');
        }

        if (clientErrors.length > 0) {
            showErrorAlert('Harap Periksa Isian Anda', clientErrors);
            return;
        }

        // Mulai Proses Simpan
        try {
            btn.disabled = true;
            if (btnText) btnText.textContent = 'Memproses...';

            // 1. Upload berkas jika ada file baru yang dipilih
            if (selectedAttachmentFile) {
                if (btnText) btnText.textContent = 'Mengunggah Berkas...';
                fileAttachment = await uploadAttachmentFile(selectedAttachmentFile, csrfToken);
            }

            // 2. Simpan data Pengumuman ke Backend
            if (btnText) btnText.textContent = 'Menyimpan Data...';

            var isEdit = <?= $isEdit ? 'true' : 'false' ?>;
            var targetUrl = isEdit ? '/admin/pengumuman/<?= (int) ($item['id'] ?? 0) ?>/update' : '/admin/pengumuman';

            var payload = {
                title: title,
                content: content,
                file_attachment: fileAttachment,
                start_date: startDate ? startDate : null,
                end_date: endDate ? endDate : null,
                sort_order: sortOrder,
                is_active: isActive
            };

            var res = await fetch(targetUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });

            var rawText = await res.text();
            var jsonRes;
            try {
                jsonRes = JSON.parse(rawText);
            } catch (e) {
                var cleanErr = rawText.replace(/<[^>]*>?/gm, ' ').replace(/\s+/g, ' ').trim();
                console.error('Submit Error Server Response:', rawText);
                throw new Error(cleanErr || ('Server Error (' + res.status + ')'));
            }

            if (!res.ok) {
                var errorMsg = jsonRes.error || 'Gagal menyimpan pengumuman ke database.';
                if (jsonRes.details) {
                    errorMsg += ' Detail: ' + jsonRes.details;
                }
                if (jsonRes.field) {
                    setFieldError(jsonRes.field, errorMsg);
                }
                throw new Error(errorMsg);
            }

            // Sukses!
            btn.disabled = false;
            if (btnText) btnText.textContent = 'Berhasil!';

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: isEdit ? 'Pengumuman Berhasil Diperbarui' : 'Pengumuman Berhasil Diterbitkan',
                    text: isEdit ? 'Perubahan data pengumuman telah disimpan ke database.' : 'Pengumuman baru telah aktif dan siap dibaca publik.',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: 'Kembali ke Daftar ➔'
                }).then(() => {
                    window.location.href = '/admin/pengumuman';
                });
            } else {
                alert(isEdit ? 'Pengumuman berhasil diperbarui!' : 'Pengumuman berhasil diterbitkan!');
                window.location.href = '/admin/pengumuman';
            }

        } catch (error) {
            btn.disabled = false;
            if (btnText) btnText.textContent = originalBtnText;

            showErrorAlert('Gagal Menyimpan Pengumuman', [error.message || 'Terjadi kesalahan sistem. Silakan coba lagi.']);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Kendala Penyimpanan',
                    text: error.message || 'Silakan cek kembali kelengkapan isian atau hubungi administrator.',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Tutup'
                });
            }
        }
    }
</script>
