<?php

// Simple test to check if we can access the database
try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Testing Database Connection ===\n";
    
    // Get count of products
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total products: " . $result['total'] . "\n";
    
    // Get products with missing images
    $stmt = $pdo->query("SELECT COUNT(*) as missing FROM products WHERE image IS NULL OR image = '' OR image LIKE '%not found%'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Products with missing images: " . $result['missing'] . "\n";
    
    // Get sample products for soap category
    echo "\n=== Sample Products (first 10) ===\n";
    echo "ID | Name | Image | Unique ID\n";
    echo "----------------------------------------\n";
    
    $stmt = $pdo->query("SELECT id, name, image, unique_id FROM products LIMIT 10");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $name = substr($row['name'], 0, 25);
        $image = $row['image'] ?: 'NULL';
        $unique_id = $row['unique_id'] ?: 'NULL';
        echo "{$row['id']} | $name | $image | $unique_id\n";
    }
    
    // Look for soap-related products
    echo "\n=== Soap-related Products ===\n";
    $stmt = $pdo->query("SELECT id, name, image, unique_id FROM products WHERE name LIKE '%soap%' OR category LIKE '%soap%' OR subcategory LIKE '%soap%' LIMIT 5");
    $soapCount = 0;
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $name = substr($row['name'], 0, 25);
        $image = $row['image'] ?: 'NULL';
        $unique_id = $row['unique_id'] ?: 'NULL';
        echo "{$row['id']} | $name | $image | $unique_id\n";
        $soapCount++;
    }
    
    if($soapCount == 0) {
        echo "No soap products found. Checking beauty/cosmetic products...\n";
        $stmt = $pdo->query("SELECT id, name, image, unique_id FROM products WHERE name LIKE '%beauty%' OR category LIKE '%beauty%' OR name LIKE '%cosmetic%' LIMIT 5");
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name = substr($row['name'], 0, 25);
            $image = $row['image'] ?: 'NULL';
            $unique_id = $row['unique_id'] ?: 'NULL';
            echo "{$row['id']} | $name | $image | $unique_id\n";
        }
    }
    
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

?>