<?php
// db.php
try {
    $db = new PDO('sqlite:' . __DIR__ . '/data/users.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Base table
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        firstname TEXT,
        lastname TEXT,
        gender TEXT,
        dob DATE
    )");

    // Migration: Add address fields
    $columns = ['street', 'suburb', 'postcode', 'state', 'country'];
    foreach ($columns as $column) {
        try {
            $db->exec("ALTER TABLE users ADD COLUMN $column TEXT");
        } catch (PDOException $e) {
            // Column likely already exists
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>