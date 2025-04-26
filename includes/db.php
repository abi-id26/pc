<?php
$host = 'localhost';
$dbname = 'pc_hardware_store';
$username = 'root';
$password = '';
try { $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) { die("Could not connect to the database: " . $e->getMessage()); }
$pdo->exec("
    CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10, 2) NOT NULL,
        image VARCHAR(255),
        category VARCHAR(100),
        stock INT DEFAULT 0,
        socket_type VARCHAR(50) NULL,
        ram_type VARCHAR(50) NULL,
        wattage INT NULL,
        form_factor VARCHAR(50) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('user', 'admin') DEFAULT 'user',
        active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        total DECIMAL(10, 2),
        status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );
    CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        product_id INT,
        quantity INT,
        price DECIMAL(10, 2),
        FOREIGN KEY (order_id) REFERENCES orders(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    );
    CREATE TABLE IF NOT EXISTS shipping_addresses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        first_name VARCHAR(100),
        last_name VARCHAR(100),
        address TEXT,
        city VARCHAR(100),
        state VARCHAR(100),
        zip VARCHAR(20),
        FOREIGN KEY (order_id) REFERENCES orders(id)
    );
");
$stmt = $pdo->query("SELECT COUNT(*) FROM products");
if ($stmt->fetchColumn() == 0) {
    $sampleProducts = [
        ['Intel Core i9-13900K', '16-Core, 24-Thread Unlocked Desktop Processor', 599.99, 'Intel Core i9-13900K.webp', 'CPU', 10, 'LGA1700', null, null, null],
        ['AMD Ryzen 9 7950X', '16-Core, 32-Thread Unlocked Desktop Processor', 549.99, 'AMD Ryzen 9 7950X.webp', 'CPU', 5, 'AM4', null, null, null],
        ['Intel Core i5-12600K', '10-Core, 16-Thread Unlocked Desktop Processor', 289.99, 'Intel Core i5-12600K.webp', 'CPU', 8, 'LGA1700', null, null, null],
        ['AMD Ryzen 5 5600X', '6-Core, 12-Thread Unlocked Desktop Processor', 199.99, 'AMD Ryzen 5 5600X.webp', 'CPU', 12, 'AM4', null, null, null],
        ['NVIDIA GeForce RTX 4090', '24GB GDDR6X Graphics Card', 1599.99, 'NVIDIA GeForce RTX 4090.webp', 'GPU', 3, null, null, 450, null],
        ['AMD Radeon RX 7900 XTX', '24GB GDDR6 Graphics Card', 999.99, 'AMD Radeon RX 7900 XTX.webp', 'GPU', 7, null, null, 350, null],
        ['NVIDIA GeForce RTX 3060', '12GB GDDR6 Graphics Card', 329.99, 'NVIDIA GeForce RTX 3060.webp', 'GPU', 15, null, null, 200, null],
        ['AMD Radeon RX 6600', '8GB GDDR6 Graphics Card', 249.99, 'AMD Radeon RX 6600.webp', 'GPU', 20, null, null, 160, null],
        ['Corsair Vengeance RGB 32GB', 'DDR5 5600MHz Memory Kit', 149.99, 'Corsair Vengeance RGB 32GB.webp', 'RAM', 20, null, 'DDR5', null, null],
        ['G.Skill Trident Z5 32GB', 'DDR5 6000MHz Memory Kit', 169.99, 'G.Skill Trident Z5 32GB.webp', 'RAM', 15, null, 'DDR5', null, null],
        ['Corsair Vengeance LPX 16GB', 'DDR4 3200MHz Memory Kit', 79.99, 'Corsair Vengeance LPX 16GB.webp', 'RAM', 25, null, 'DDR4', null, null],
        ['Kingston Fury Beast 16GB', 'DDR4 3600MHz Memory Kit', 89.99, 'Kingston Fury Beast 16GB.webp', 'RAM', 30, null, 'DDR4', null, null],
        ['Samsung 980 Pro 1TB', 'PCIe 4.0 NVMe SSD', 129.99, 'Samsung 980 Pro 1TB.webp', 'Storage', 25, null, null, null, null],
        ['WD Black SN850X 1TB', 'PCIe 4.0 NVMe SSD', 119.99, 'WD Black SN850X 1TB.webp', 'Storage', 30, null, null, null, null],
        ['Crucial MX500 1TB', 'SATA SSD', 89.99, 'Crucial MX500 1TB.webp', 'Storage', 40, null, null, null, null],
        ['Seagate Barracuda 2TB', '7200 RPM HDD', 59.99, 'Seagate Barracuda 2TB.webp', 'Storage', 50, null, null, null, null],
        ['ASUS ROG Strix Z790-E', 'LGA 1700 ATX Motherboard', 449.99, 'ASUS ROG Strix Z790-E.webp', 'Motherboard', 5, 'LGA1700', 'DDR5', null, 'ATX'],
        ['MSI MPG B550 Gaming Edge', 'AM4 ATX Motherboard', 179.99, 'MSI MPG B550 Gaming Edge.webp', 'Motherboard', 10, 'AM4', 'DDR4', null, 'ATX'],
        ['Gigabyte Z690 Aorus Elite', 'LGA 1700 ATX Motherboard', 229.99, 'Gigabyte Z690 Aorus Elite.webp', 'Motherboard', 8, 'LGA1700', 'DDR5', null, 'ATX'],
        ['ASRock B450M Pro4', 'AM4 Micro-ATX Motherboard', 89.99, 'ASRock B450M Pro4.webp', 'Motherboard', 15, 'AM4', 'DDR4', null, 'Micro-ATX'],
        ['Corsair RM850x', '850W 80+ Gold Fully Modular PSU', 129.99, 'Corsair RM850x.webp', 'Power Supply', 15, null, null, 850, null],
        ['EVGA SuperNOVA 750 G6', '750W 80+ Gold Fully Modular', 109.99, 'EVGA SuperNOVA 750 G6.webp', 'Power Supply', 20, null, null, 750, null],
        ['Cooler Master MWE 650', '650W 80+ Bronze PSU', 79.99, 'Cooler Master MWE 650.webp', 'Power Supply', 25, null, null, 650, null],
        ['Thermaltake Smart 500W', '500W 80+ White PSU', 49.99, 'Thermaltake Smart 500W.webp', 'Power Supply', 30, null, null, 500, null],
        ['Noctua NH-D15', 'Premium Dual-Tower Air Cooler', 89.95, 'Noctua NH-D15.webp', 'CPU Cooler', 10, null, null, null, null],
        ['Corsair iCUE H150i', '360mm RGB Liquid CPU Cooler', 169.99, 'Corsair iCUE H150i.webp', 'CPU Cooler', 5, null, null, null, null],
        ['Fractal Design Meshify C', 'ATX Mid Tower Case', 89.99, 'Fractal Design Meshify C.webp', 'Case', 10, null, null, null, 'ATX'],
        ['NZXT H510 Elite', 'Mid-Tower ATX Case with Tempered Glass', 149.99, 'NZXT H510 Elite.webp', 'Case', 5, null, null, null, 'ATX'],
        ['Cooler Master MasterBox Q300L', 'Micro-ATX Case', 49.99, 'Cooler Master MasterBox Q300L.webp', 'Case', 20, null, null, null, 'Micro-ATX'],
        ['Thermaltake Versa H18', 'Micro-ATX Case', 39.99, 'Thermaltake Versa H18.webp', 'Case', 25, null, null, null, 'Micro-ATX']
    ];
    $insert = $pdo->prepare("INSERT INTO products (name, description, price, image, category, stock, socket_type, ram_type, wattage, form_factor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($sampleProducts as $product) {
        $insert->execute($product);
    }
}
?>