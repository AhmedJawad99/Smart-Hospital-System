<?php
require 'db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS maintenance_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        device_name VARCHAR(255) NOT NULL,
        patient_id INT,
        usage_details TEXT,
        result TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "✅ Table 'maintenance_logs' created successfully.";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
