<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Models\Pengumuman;

class PengumumanAdminController
{
    private const UPLOAD_DIR = '/uploads/pengumuman/';
    private const MAX_FILE_SIZE = 25 * 1024 * 1024; // 25 MB
    private const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'webp'];
    private const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    public function __construct(private ?Pengumuman $pengumuman = null) {}

    /**
     * Memastikan akses hanya untuk admin yang sudah login.
     * Jika belum login: Terpental (redirect ke /admin/login) atau Forbidden 403 untuk API.
     */
    private function assertAuth(Request $request, Response $response): bool
    {
        $user = Session::get('_auth_user');
        if (!$user || !in_array($user['role'] ?? '', ['superadmin', 'admin', 'Admin'], true)) {
            if ($request->acceptsJson()) {
                $response->json([
                    'error' => 'Akses ditolak (403 Forbidden). Anda harus login sebagai admin terlebih dahulu.',
                ], 403);
            } else {
                $response->redirect('/admin/login');
            }
            return false;
        }

        return true;
    }

    /**
     * Mendapatkan direktori absolut penyimpanan uploads/pengumuman/.
     * Otomatis mendukung struktur Local development (public/) maupun cPanel Production (public_html/ atau DocumentRoot).
     */
    private function getUploadDirectory(): string
    {
        $base = dirname(__DIR__, 3);
        $publicDir = $base . '/public';

        if (!is_dir($publicDir) && is_dir($base . '/public_html')) {
            $publicDir = $base . '/public_html';
        } elseif (!is_dir($publicDir) && !empty($_SERVER['DOCUMENT_ROOT']) && is_dir($_SERVER['DOCUMENT_ROOT'])) {
            $publicDir = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\');
        }

        $uploadDir = $publicDir . self::UPLOAD_DIR;
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        // Proteksi keamanan .htaccess (seperti pada sistem buku & galeri)
        $htaccess = $uploadDir . '.htaccess';
        if (!is_file($htaccess)) {
            @file_put_contents($htaccess, "php_flag engine off\nRemoveHandler .php .phtml .php3 .php4 .php5\n");
        }

        return $uploadDir;
    }

    /**
     * Menghapus berkas fisik di server jika data dihapus atau diganti
     */
    private function cleanupPhysicalFile(?string $urlPath): void
    {
        if (empty($urlPath) || !str_starts_with($urlPath, '/uploads/')) {
            return;
        }

        $base = dirname(__DIR__, 3);
        $publicDir = $base . '/public';
        if (!is_dir($publicDir) && is_dir($base . '/public_html')) {
            $publicDir = $base . '/public_html';
        } elseif (!is_dir($publicDir) && !empty($_SERVER['DOCUMENT_ROOT']) && is_dir($_SERVER['DOCUMENT_ROOT'])) {
            $publicDir = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\');
        }

        $physicalPath = $publicDir . $urlPath;
        if (is_file($physicalPath)) {
            @unlink($physicalPath);
        }
    }

    /**
     * Mengambil daftar data pengumuman untuk tabel admin (JSON)
     */
    public function index(Request $request, Response $response): void
    {
        if (!$this->assertAuth($request, $response)) {
            return;
        }

        $limit = (int) ($request->query('per_page') ?: 25);
        $page = max(1, (int) ($request->query('page') ?: 1));
        $offset = ($page - 1) * $limit;

        $filters = [];
        $q = trim((string) ($request->query('q') ?? ''));
        if ($q !== '') {
            $filters['q'] = $q;
        }

        $isActive = $request->query('is_active');
        if ($isActive !== null && $isActive !== '') {
            $filters['is_active'] = (int) $isActive;
        }

        $data = $this->pengumuman?->all($filters, $limit, $offset) ?? [];
        $total = $this->pengumuman?->count($filters) ?? 0;

        $response->json([
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $limit,
            'last_page' => $limit > 0 ? (int) ceil($total / $limit) : 1,
        ]);
    }

    /**
     * Menampilkan detail satu pengumuman berdasarkan ID (JSON)
     */
    public function show(Request $request, Response $response, array $params): void
    {
        if (!$this->assertAuth($request, $response)) {
            return;
        }

        $id = (int) ($params['id'] ?? 0);
        $item = $this->pengumuman?->find($id);

        if (!$item) {
            $response->json(['error' => 'Pengumuman tidak ditemukan'], 404);
            return;
        }

        $response->json(['data' => $item]);
    }

    /**
     * Menyimpan data pengumuman baru (POST JSON)
     */
    public function store(Request $request, Response $response): void
    {
        if (!$this->assertAuth($request, $response)) {
            return;
        }

        $payload = $this->sanitize($request->json());

        // Validasi Judul
        if (empty($payload['title']) || mb_strlen(trim((string) $payload['title'])) < 3) {
            $response->json([
                'error' => 'Judul pengumuman wajib diisi minimal 3 karakter.',
                'field' => 'title',
            ], 422);
            return;
        }

        $id = $this->pengumuman?->create($payload) ?? 0;
        if ($id <= 0) {
            $details = $this->pengumuman?->getLastError() ?: 'Gagal menyimpan pengumuman ke database.';
            $response->json([
                'error' => 'Gagal menyimpan pengumuman ke database.',
                'details' => $details,
            ], 500);
            return;
        }

        $response->json([
            'data' => [
                'id' => $id,
                'message' => 'Pengumuman berhasil ditambahkan.',
            ],
        ], 201);
    }

    /**
     * Memperbarui data pengumuman (POST JSON)
     */
    public function update(Request $request, Response $response, array $params): void
    {
        if (!$this->assertAuth($request, $response)) {
            return;
        }

        $id = (int) ($params['id'] ?? 0);
        if ($id <= 0) {
            $response->json(['error' => 'ID pengumuman tidak valid.'], 400);
            return;
        }

        $existing = $this->pengumuman?->find($id);
        if (!$existing) {
            $response->json(['error' => 'Pengumuman tidak ditemukan.'], 404);
            return;
        }

        $payload = $this->sanitize($request->json());

        if (isset($payload['title']) && mb_strlen(trim((string) $payload['title'])) < 3) {
            $response->json([
                'error' => 'Judul pengumuman minimal 3 karakter.',
                'field' => 'title',
            ], 422);
            return;
        }

        // Jika ada lampiran baru dan berbeda dari berkas lama, hapus berkas lama dari server
        if (isset($payload['file_attachment']) && $payload['file_attachment'] !== $existing['file_attachment']) {
            $this->cleanupPhysicalFile($existing['file_attachment']);
        }

        $success = $this->pengumuman?->update($id, $payload) ?? false;
        if (!$success) {
            $details = $this->pengumuman?->getLastError() ?: 'Gagal memperbarui pengumuman.';
            $response->json([
                'error' => 'Gagal memperbarui pengumuman di database.',
                'details' => $details,
            ], 500);
            return;
        }

        $response->json([
            'data' => [
                'id' => $id,
                'updated' => true,
                'message' => 'Pengumuman berhasil diperbarui.',
            ],
        ]);
    }

    /**
     * Menghapus data pengumuman (POST JSON)
     */
    public function delete(Request $request, Response $response, array $params): void
    {
        if (!$this->assertAuth($request, $response)) {
            return;
        }

        $id = (int) ($params['id'] ?? 0);
        if ($id <= 0) {
            $response->json(['error' => 'ID pengumuman tidak valid.'], 400);
            return;
        }

        $existing = $this->pengumuman?->find($id);
        if (!$existing) {
            $response->json(['error' => 'Pengumuman tidak ditemukan.'], 404);
            return;
        }

        // Hapus berkas fisik jika ada
        if (!empty($existing['file_attachment'])) {
            $this->cleanupPhysicalFile($existing['file_attachment']);
        }

        $success = $this->pengumuman?->delete($id) ?? false;
        if (!$success) {
            $details = $this->pengumuman?->getLastError() ?: 'Gagal menghapus pengumuman.';
            $response->json([
                'error' => 'Gagal menghapus pengumuman dari database.',
                'details' => $details,
            ], 500);
            return;
        }

        $response->json([
            'data' => [
                'id' => $id,
                'deleted' => true,
                'message' => 'Pengumuman berhasil dihapus.',
            ],
        ]);
    }

    /**
     * Upload lampiran file pengumuman (PDF, Dokumen, atau Gambar)
     */
    public function upload(Request $request, Response $response): void
    {
        if (!$this->assertAuth($request, $response)) {
            return;
        }

        $file = $_FILES['file'] ?? $_FILES['attachment'] ?? null;
        if (!$file || !is_uploaded_file($file['tmp_name'])) {
            $response->json(['error' => 'Tidak ada file yang diunggah.'], 422);
            return;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $response->json(['error' => 'Gagal mengunggah file. Kode error: ' . $file['error']], 422);
            return;
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            $response->json(['error' => 'Ukuran file terlalu besar. Maksimal 25 MB.'], 422);
            return;
        }

        $originalName = (string) ($file['name'] ?? '');
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            $response->json([
                'error' => 'Format file tidak didukung. Format yang diizinkan: PDF, Word, Excel, JPG, PNG, WEBP.',
            ], 422);
            return;
        }

        // Validasi MIME type jika ekstensi fileinfo tersedia
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = $finfo ? (string) finfo_file($finfo, $file['tmp_name']) : '';
            if ($finfo) {
                finfo_close($finfo);
            }

            if ($mime !== '' && !in_array($mime, self::ALLOWED_MIME_TYPES, true)) {
                $response->json(['error' => 'Tipe konten file tidak valid (' . $mime . ').'], 422);
                return;
            }
        }

        $uploadDir = $this->getUploadDirectory();
        if (!is_dir($uploadDir)) {
            $response->json(['error' => 'Gagal menyiapkan folder penyimpanan server.'], 500);
            return;
        }

        $filename = 'pengumuman_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (!@move_uploaded_file($file['tmp_name'], $destination)) {
            $response->json(['error' => 'Gagal memindahkan file ke penyimpanan server.'], 500);
            return;
        }

        $publicUrl = self::UPLOAD_DIR . $filename;
        $response->json([
            'data' => [
                'url' => $publicUrl,
                'name' => $originalName,
                'filename' => $filename,
                'size' => $file['size'],
            ],
        ], 201);
    }

    /**
     * Sanitasi data input sebelum diproses
     */
    private function sanitize(array $data): array
    {
        $clean = [];

        if (array_key_exists('title', $data)) {
            $clean['title'] = strip_tags(trim((string) $data['title']));
        }
        if (array_key_exists('content', $data)) {
            $clean['content'] = trim((string) $data['content']);
        }
        if (array_key_exists('file_attachment', $data)) {
            $clean['file_attachment'] = trim((string) $data['file_attachment']);
        }
        if (array_key_exists('start_date', $data)) {
            $date = trim((string) $data['start_date']);
            $clean['start_date'] = $date !== '' ? $date : null;
        }
        if (array_key_exists('end_date', $data)) {
            $date = trim((string) $data['end_date']);
            $clean['end_date'] = $date !== '' ? $date : null;
        }
        if (array_key_exists('is_active', $data)) {
            $clean['is_active'] = !empty($data['is_active']) ? 1 : 0;
        }
        if (array_key_exists('sort_order', $data)) {
            $clean['sort_order'] = (int) $data['sort_order'];
        }

        return $clean;
    }
}
