<?php
/**
 * Migration helper for adding payment columns to the `orders` table.
 * Run this from your browser (e.g., http://localhost/FYP-Online-Bakery/FYP Chunkit/migrate_add_payment_columns.php)
 * or from CLI: php migrate_add_payment_columns.php
 */

include 'config.php';

try {
    // Add columns if they do not exist (MySQL 8.0+ supports IF NOT EXISTS)
    $sql = "ALTER TABLE `orders`
        ADD COLUMN IF NOT EXISTS `payment_method` VARCHAR(50) DEFAULT NULL,
        ADD COLUMN IF NOT EXISTS `payment_status` ENUM('pending','paid','failed') DEFAULT 'pending';";

    $pdo->exec($sql);

    // Ensure existing rows have a default payment_status
    $pdo->exec("UPDATE `orders` SET payment_status = 'pending' WHERE payment_status IS NULL");

    echo "Migration completed successfully.\n";
} catch (PDOException $e) {
    echo "Migration failed: " . htmlspecialchars($e->getMessage()) . "\n";
    error_log('Migration error: ' . $e->getMessage());
    exit(1);
}

// Helpful message
echo "If you see 'Migration completed successfully', please retry the checkout page.\n";