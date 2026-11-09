<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use Throwable;

class Pengumuman
{
    private ?string $lastError = null;

    public function __construct(private ?PDO $db = null) {}

    /**
     * Mengambil pesan error terakhir jika query gagal
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * Merapikan baris data dari database agar siap digunakan oleh Frontend maupun Admin
     */
    public static function mapRow(array $row): array
    {
        $content = (string) ($row['content'] ?? '');
        $cleanContent = trim(strip_tags($content));
        $excerpt = mb_strlen($cleanContent) > 150
            ? rtrim(mb_substr($cleanContent, 0, 150)) . '…'
            : $cleanContent;

        $startDate = !empty($row['start_date']) ? (string) $row['start_date'] : null;
        $endDate = !empty($row['end_date']) ? (string) $row['end_date'] : null;

        $dateFormatted = 'Terbaru';
        if ($startDate !== null) {
            $timestamp = strtotime($startDate);
            if ($timestamp !== false) {
                $dateFormatted = date('d M Y', $timestamp);
            }
        }

        return [
            'id' => (int) ($row['id'] ?? 0),
            'title' => (string) ($row['title'] ?? ''),
            'content' => $content,
            'excerpt' => $excerpt,
            'file_attachment' => (string) ($row['file_attachment'] ?? ''),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'date_formatted' => $dateFormatted,
            'is_active' => (int) ($row['is_active'] ?? 1) === 1,
            'sort_order' => (int) ($row['sort_order'] ?? 0),
            'created_at' => (string) ($row['created_at'] ?? ''),
            'updated_at' => (string) ($row['updated_at'] ?? ''),
        ];
    }

    /**
     * Mengambil daftar pengumuman aktif untuk ditampilkan pada Section Beranda (/)
     */
    public function activeForHome(int $limit = 8): array
    {
        if (!$this->db) {
            return [];
        }

        try {
            $sql = "
                SELECT * FROM tbl_pengumuman
                WHERE is_active = 1


                ORDER BY sort_order ASC, created_at DESC, id DESC
                LIMIT :limit
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', max(1, min($limit, 50)), PDO::PARAM_INT);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map([self::class, 'mapRow'], $results);
        } catch (Throwable $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }

    /**
     * Mengambil daftar pengumuman untuk tabel panel Admin (dengan filter & pagination)
     */
    public function all(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        if (!$this->db) {
            return [];
        }

        try {
            $conditions = ['1=1'];
            $params = [];

            if (isset($filters['is_active']) && $filters['is_active'] !== '') {
                $conditions[] = 'is_active = :is_active';
                $params[':is_active'] = (int) $filters['is_active'];
            }

            if (!empty($filters['q'])) {
                $conditions[] = '(title LIKE :q OR content LIKE :q)';
                $params[':q'] = '%' . trim((string) $filters['q']) . '%';
            }

            $sql = 'SELECT * FROM tbl_pengumuman WHERE ' . implode(' AND ', $conditions)
                . ' ORDER BY sort_order ASC, created_at DESC, id DESC LIMIT :limit OFFSET :offset';

            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            $stmt->bindValue(':limit', max(1, min($limit, 100)), PDO::PARAM_INT);
            $stmt->bindValue(':offset', max(0, $offset), PDO::PARAM_INT);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map([self::class, 'mapRow'], $results);
        } catch (Throwable $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }

    /**
     * Menghitung total data pengumuman untuk keperluan pagination Admin
     */
    public function count(array $filters = []): int
    {
        if (!$this->db) {
            return 0;
        }

        try {
            $conditions = ['1=1'];
            $params = [];

            if (isset($filters['is_active']) && $filters['is_active'] !== '') {
                $conditions[] = 'is_active = :is_active';
                $params[':is_active'] = (int) $filters['is_active'];
            }

            if (!empty($filters['q'])) {
                $conditions[] = '(title LIKE :q OR content LIKE :q)';
                $params[':q'] = '%' . trim((string) $filters['q']) . '%';
            }

            $sql = 'SELECT COUNT(*) FROM tbl_pengumuman WHERE ' . implode(' AND ', $conditions);
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return (int) $stmt->fetchColumn();
        } catch (Throwable $e) {
            $this->lastError = $e->getMessage();
            return 0;
        }
    }

    /**
     * Mencari satu data pengumuman berdasarkan ID
     */
    public function find(int $id): ?array
    {
        if (!$this->db || $id <= 0) {
            return null;
        }

        try {
            $stmt = $this->db->prepare('SELECT * FROM tbl_pengumuman WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row ? self::mapRow($row) : null;
        } catch (Throwable $e) {
            $this->lastError = $e->getMessage();
            return null;
        }
    }

    /**
     * Menyimpan data pengumuman baru
     */
    public function create(array $data): int
    {
        if (!$this->db) {
            return 0;
        }

        try {
            $sql = '
                INSERT INTO tbl_pengumuman 
                (title, content, file_attachment, start_date, end_date, is_active, sort_order, created_at)
                VALUES 
                (:title, :content, :file_attachment, :start_date, :end_date, :is_active, :sort_order, NOW())
            ';

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':title' => trim((string) ($data['title'] ?? '')),
                ':content' => !empty($data['content']) ? (string) $data['content'] : null,
                ':file_attachment' => !empty($data['file_attachment']) ? (string) $data['file_attachment'] : null,
                ':start_date' => !empty($data['start_date']) ? (string) $data['start_date'] : null,
                ':end_date' => !empty($data['end_date']) ? (string) $data['end_date'] : null,
                ':is_active' => !empty($data['is_active']) ? 1 : 0,
                ':sort_order' => (int) ($data['sort_order'] ?? 0),
            ]);

            return (int) $this->db->lastInsertId();
        } catch (Throwable $e) {
            $this->lastError = $e->getMessage();
            return 0;
        }
    }

    /**
     * Memperbarui data pengumuman yang sudah ada
     */
    public function update(int $id, array $data): bool
    {
        if (!$this->db || $id <= 0) {
            return false;
        }

        try {
            $fields = [
                'title = :title',
                'content = :content',
                'file_attachment = :file_attachment',
                'start_date = :start_date',
                'end_date = :end_date',
                'is_active = :is_active',
                'sort_order = :sort_order',
                'updated_at = NOW()',
            ];

            $sql = 'UPDATE tbl_pengumuman SET ' . implode(', ', $fields) . ' WHERE id = :id';

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':title' => trim((string) ($data['title'] ?? '')),
                ':content' => !empty($data['content']) ? (string) $data['content'] : null,
                ':file_attachment' => !empty($data['file_attachment']) ? (string) $data['file_attachment'] : null,
                ':start_date' => !empty($data['start_date']) ? (string) $data['start_date'] : null,
                ':end_date' => !empty($data['end_date']) ? (string) $data['end_date'] : null,
                ':is_active' => !empty($data['is_active']) ? 1 : 0,
                ':sort_order' => (int) ($data['sort_order'] ?? 0),
            ]);
        } catch (Throwable $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    /**
     * Menghapus data pengumuman secara permanen
     */
    public function delete(int $id): bool
    {
        if (!$this->db || $id <= 0) {
            return false;
        }

        try {
            $stmt = $this->db->prepare('DELETE FROM tbl_pengumuman WHERE id = :id');
            return $stmt->execute([':id' => $id]);
        } catch (Throwable $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }
}
