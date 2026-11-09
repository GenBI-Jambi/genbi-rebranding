<?php

declare(strict_types=1);

return [
    'up' => static function (\PDO $db): void {
        $db->exec("
            CREATE TABLE IF NOT EXISTS tbl_pengumuman (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                content TEXT NULL,
                file_attachment VARCHAR(1000) NULL,
                start_date DATE NULL,
                end_date DATE NULL,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                sort_order INT NOT NULL DEFAULT 0,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NULL,

                PRIMARY KEY (id),
                INDEX idx_pengumuman_active (is_active),
                INDEX idx_pengumuman_dates (start_date, end_date),
                INDEX idx_pengumuman_sort (sort_order, id)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci
        ");
    },

    'down' => static function (\PDO $db): void {
        $db->exec("DROP TABLE IF EXISTS tbl_pengumuman");
    },
];
