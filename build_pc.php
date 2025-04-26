<?php
ob_start();
session_start();
include 'includes/header.php';
include 'includes/db.php';
$db = $pdo;
if (!isset($_SESSION['pc_build'])) {
    $_SESSION['pc_build'] = [
        'cpu' => null,
        'cpu_cooler' => null,
        'gpu' => null,
        'motherboard' => null,
        'ram' => null,
        'storage' => null,
        'psu' => null,
        'case' => null
    ];
}
function fetchComponents($type, $db, $filters = []) {
    $query = "SELECT * FROM products WHERE category = ?";
    $params = [$type];
    if (isset($filters['socket_type'])) {
        $query .= " AND (socket_type = ? OR socket_type LIKE ? OR socket_type LIKE ? OR socket_type LIKE ? OR socket_type = 'Universal')";
        $socketType = $filters['socket_type'];
        $params[] = $socketType;
        $params[] = "$socketType,%";
        $params[] = "%,$socketType,%";
        $params[] = "%,$socketType";
    }
    if (isset($filters['ram_type'])) {
        $query .= " AND ram_type = ?";
        $params[] = $filters['ram_type'];
    }
    if (isset($filters['form_factor'])) {
        $query .= " AND form_factor = ?";
        $params[] = $filters['form_factor'];
    }
    if (isset($filters['id'])) {
        $query .= " AND id = ?";
        $params[] = $filters['id'];
    }
    if (isset($filters['wattage'])) {
        $query .= " AND wattage >= ?";
        $params[] = $filters['wattage'];
    }
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("Query: $query");
    error_log("Params: " . json_encode($params));
    error_log("Results: " . json_encode($results));
    return $results;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $componentType = $_POST['component_type'];
    $componentId = $_POST['component_id'];
    $_SESSION['pc_build'][$componentType] = $componentId;
    if ($componentType === 'case') {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        foreach ($_SESSION['pc_build'] as $type => $id) {
            if ($id) {
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity'] += 1;
                } else {
                    $_SESSION['cart'][$id] = ['quantity' => 1];
                }
            }
        }
        unset($_SESSION['pc_build']);
        header('Location: cart.php');
        exit;
    }
    header('Location: build_pc.php');
    exit;
}
function calculateProgress() {
    $totalSteps = 8;
    $completedSteps = 0;
    foreach ($_SESSION['pc_build'] as $component => $value) {
        if ($value !== null) {
            $completedSteps++;
        }
    }
    return round(($completedSteps / $totalSteps) * 100);
}
$progress = calculateProgress();
?>
<style>
    .component-card {
        transition: all 0.3s ease;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
    .component-card.selected {
        background-color:
        box-shadow: 0 4px 12px rgba(180, 83, 9, 0.2) !important;
        transform: translateY(-5px);
        border-color:
    }
    .component-image {
        transition: transform 0.3s ease;
        object-fit: contain;
    }
    .component-name {
        font-size: 0.9rem;
        line-height: 1.3;
        height: 2.6rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .selected-indicator {
        transition: opacity 0.3s ease;
    }
    .component-card .text-xs {
        font-size: 0.7rem;
    }
    .component-gallery {
        opacity: 0;
        animation: fadeIn 0.5s ease forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .progress-animation {
        transition: width 1s ease;
    }
    @media (max-width: 768px) {
        .component-gallery {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 640px) {
        .component-gallery {
            grid-template-columns: repeat(1, 1fr);
        }
    }
</style>
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumbs -->
    <div class="wood-breadcrumbs mb-6">
        <div class="wood-breadcrumb-item">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
        </div>
        <div class="wood-breadcrumb-item active">
            Build Your PC
        </div>
    </div>
    <h1 class="page-title text-2xl mb-6">Custom PC Builder</h1>
    <!-- Progress bar -->
    <div class="wood-card p-4 mb-6">
        <div class="card-content">
            <div class="mb-4">
                <h3 class="text-amber-900 font-medium text-lg mb-2">Build Progress</h3>
                <div class="bg-amber-100 rounded-full h-4 overflow-hidden">
                    <div class="bg-amber-600 h-4 rounded-full progress-animation" style="width: <?= $progress ?>%"></div>
                </div>
                <p class="text-amber-800 text-right mt-1 font-medium"><?= $progress ?>% complete</p>
            </div>
            <!-- Steps Indicator -->
            <div class="grid grid-cols-8 gap-2 mt-6">
                <?php
                $steps = [
                    ['cpu', 'CPU', 'fa-microchip'],
                    ['cpu_cooler', 'CPU Cooler', 'fa-fan'],
                    ['motherboard', 'Motherboard', 'fa-server'],
                    ['ram', 'RAM', 'fa-memory'],
                    ['storage', 'Storage', 'fa-hdd'],
                    ['gpu', 'GPU', 'fa-tv'],
                    ['psu', 'PSU', 'fa-plug'],
                    ['case', 'Case', 'fa-desktop']
                ];
                foreach ($steps as $index => $step) {
                    $key = $step[0];
                    $label = $step[1];
                    $icon = $step[2];
                    $isCompleted = $_SESSION['pc_build'][$key] !== null;
                    $isActive = false;
                    if (!$isCompleted) {
                        $allPreviousCompleted = true;
                        for ($i = 0; $i < $index; $i++) {
                            if ($_SESSION['pc_build'][$steps[$i][0]] === null) {
                                $allPreviousCompleted = false;
                                break;
                            }
                        }
                        if ($allPreviousCompleted) {
                            $isActive = true;
                        }
                    }
                    $bgColor = $isCompleted ? 'bg-green-600' : ($isActive ? 'bg-amber-600' : 'bg-amber-200');
                    $textColor = $isCompleted || $isActive ? 'text-white' : 'text-amber-800';
                    $borderColor = $isActive ? 'border-amber-600' : ($isCompleted ? 'border-green-600' : 'border-amber-200');
                ?>
                <div class="flex flex-col items-center">
                    <div class="<?= $bgColor ?> <?= $textColor ?> w-8 h-8 flex items-center justify-center rounded-full mb-2 border-2 <?= $borderColor ?> transition-all">
                        <?php if ($isCompleted): ?>
                            <i class="fas fa-check"></i>
                        <?php else: ?>
                            <span><?= $index + 1 ?></span>
                        <?php endif; ?>
                    </div>
                    <span class="text-xs text-center text-amber-800 hidden md:block"><?= $label ?></span>
                    <i class="fas <?= $icon ?> text-amber-800 block md:hidden"></i>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- Two-column layout for PC Builder -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left column - Component Selection -->
        <div class="lg:w-2/3 build-pc-wizard fade-in">
            <form method="POST" action="build_pc.php">
                <?php if (!$_SESSION['pc_build']['cpu']): ?>
                    <div class="wood-card mb-8 overflow-hidden animated-card">
                        <div class="bg-amber-800 py-3 px-6 text-amber-50">
                            <h2 class="text-xl font-bold flex items-center">
                                <i class="fas fa-microchip mr-2"></i> Step 1: Select a CPU
                            </h2>
                        </div>
                        <div class="p-6 card-content">
                            <div class="mb-6">
                                <p class="wood-card-text mb-4">The CPU (Central Processing Unit) is the brain of your computer. It handles all the instructions you give your computer and the applications you run.</p>
                                <label for="cpu-select" class="wood-label flex items-center">
                                    <i class="fas fa-microchip mr-2 text-amber-800"></i> Choose a CPU:
                                </label>
                                <div class="component-gallery grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                                    <?php
                                    $cpus = fetchComponents('CPU', $db);
                                    foreach ($cpus as $cpu):
                                    ?>
                                    <label for="cpu-<?= $cpu['id'] ?>" class="component-card cursor-pointer block relative bg-amber-50 rounded-lg border-2 border-amber-100 hover:border-amber-600 transition-all p-4">
                                        <input type="radio" name="component_id" id="cpu-<?= $cpu['id'] ?>" value="<?= $cpu['id'] ?>" class="hidden component-radio">
                                        <div class="flex flex-col items-center">
                                            <div class="component-image-container h-24 flex items-center justify-center mb-2">
                                                <img src="assets/images/products/<?= htmlspecialchars($cpu['image']) ?>" alt="<?= htmlspecialchars($cpu['name']) ?>" class="component-image max-h-24 max-w-full">
                                            </div>
                                            <h5 class="component-name text-amber-900 font-medium text-center"><?= htmlspecialchars($cpu['name']) ?></h5>
                                            <p class="component-price text-amber-800 font-bold mt-1">$<?= number_format($cpu['price'], 2) ?></p>
                                            <p class="component-spec text-xs text-amber-700 mt-1">Socket: <?= htmlspecialchars($cpu['socket_type']) ?></p>
                                        </div>
                                        <div class="selected-indicator absolute top-2 right-2 opacity-0 transition-opacity">
                                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                        </div>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                                <!-- Fallback select dropdown for mobile devices -->
                                <select id="cpu-select" name="component_id_mobile" class="wood-input mt-4 md:hidden">
                                    <option value="">-- Select CPU --</option>
                                    <?php
                                    foreach ($cpus as $cpu) {
                                        echo "<option value='{$cpu['id']}'>{$cpu['name']} - \${$cpu['price']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="wooden-divider"></div>
                            <div class="flex justify-end mt-6">
                                <input type="hidden" name="component_type" value="cpu">
                                <button type="submit" class="wooden-cart-button">
                                    <span class="button-text">Next Step</span>
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php elseif (!$_SESSION['pc_build']['cpu_cooler']): ?>
                    <div class="wood-card mb-8 overflow-hidden animated-card">
                        <div class="bg-amber-800 py-3 px-6 text-amber-50">
                            <h2 class="text-xl font-bold flex items-center">
                                <i class="fas fa-fan mr-2"></i> Step 2: Select a CPU Cooler
                            </h2>
                        </div>
                        <div class="p-6 card-content">
                            <div class="mb-6">
                                <p class="wood-card-text mb-4">The CPU cooler keeps your processor from overheating. It's essential for maintaining optimal performance and extending the lifespan of your CPU.</p>
                                <?php
                                $cpuId = $_SESSION['pc_build']['cpu'];
                                $cpu = fetchComponents('CPU', $db, ['id' => $cpuId])[0];
                                ?>
                                <div class="flex flex-col md:flex-row gap-4 mb-4">
                                    <div class="wood-card bg-opacity-50 p-4 flex-1">
                                        <h3 class="wood-card-title">Selected CPU</h3>
                                        <p class="text-amber-900"><?= $cpu['name'] ?></p>
                                        <p class="text-sm text-amber-700">Socket: <?= $cpu['socket_type'] ?></p>
                                    </div>
                                </div>
                                <label for="cpu-cooler-select" class="wood-label flex items-center">
                                    <i class="fas fa-fan mr-2 text-amber-800"></i> Choose a Compatible CPU Cooler:
                                </label>
                                <div class="component-gallery grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                                    <?php
                                    $cpu_coolers = fetchComponents('CPU Cooler', $db, ['socket_type' => $cpu['socket_type']]);
                                    if (!empty($cpu_coolers)):
                                        foreach ($cpu_coolers as $cooler):
                                    ?>
                                    <label for="cooler-<?= $cooler['id'] ?>" class="component-card cursor-pointer block relative bg-amber-50 rounded-lg border-2 border-amber-100 hover:border-amber-600 transition-all p-4">
                                        <input type="radio" name="component_id" id="cooler-<?= $cooler['id'] ?>" value="<?= $cooler['id'] ?>" class="hidden component-radio">
                                        <div class="flex flex-col items-center">
                                            <div class="component-image-container h-24 flex items-center justify-center mb-2">
                                                <img src="assets/images/products/<?= htmlspecialchars($cooler['image']) ?>" alt="<?= htmlspecialchars($cooler['name']) ?>" class="component-image max-h-24 max-w-full">
                                            </div>
                                            <h5 class="component-name text-amber-900 font-medium text-center"><?= htmlspecialchars($cooler['name']) ?></h5>
                                            <p class="component-price text-amber-800 font-bold mt-1">$<?= number_format($cooler['price'], 2) ?></p>
                                        </div>
                                        <div class="selected-indicator absolute top-2 right-2 opacity-0 transition-opacity">
                                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                        </div>
                                    </label>
                                    <?php
                                        endforeach;
                                    else:
                                    ?>
                                    <div class="col-span-3 p-4 bg-amber-100 text-amber-800 rounded-lg">
                                        <p class="flex items-center"><i class="fas fa-exclamation-triangle mr-2 text-amber-600"></i> No compatible CPU coolers found for this socket type. Please go back and select a different CPU.</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <!-- Fallback select dropdown for mobile devices -->
                                <select id="cpu-cooler-select" name="component_id_mobile" class="wood-input mt-4 md:hidden">
                                    <option value="">-- Select CPU Cooler --</option>
                                    <?php
                                    foreach ($cpu_coolers as $cooler) {
                                        echo "<option value='{$cooler['id']}'>{$cooler['name']} - \${$cooler['price']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="wooden-divider"></div>
                            <div class="flex justify-between mt-6">
                                <a href="reset_build.php?component=cpu" class="wooden-cart-button bg-red-700">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    <span class="button-text text-white">Go Back</span>
                                </a>
                                <input type="hidden" name="component_type" value="cpu_cooler">
                                <button type="submit" class="wooden-cart-button">
                                    <span class="button-text">Next Step</span>
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php elseif (!$_SESSION['pc_build']['motherboard']): ?>
                    <div class="wood-card mb-8 overflow-hidden animated-card">
                        <div class="bg-amber-800 py-3 px-6 text-amber-50">
                            <h2 class="text-xl font-bold flex items-center">
                                <i class="fas fa-server mr-2"></i> Step 3: Select a Motherboard
                            </h2>
                        </div>
                        <div class="p-6 card-content">
                            <div class="mb-6">
                                <p class="wood-card-text mb-4">The motherboard is the main circuit board of your computer. It connects all the parts of your PC together and allows them to communicate with each other.</p>
                                <?php
                                $cpuId = $_SESSION['pc_build']['cpu'];
                                $cpu = fetchComponents('cpu', $db, ['id' => $cpuId])[0];
                                ?>
                                <div class="flex flex-col md:flex-row gap-4 mb-4">
                                    <div class="wood-card bg-opacity-50 p-4 flex-1">
                                        <h3 class="wood-card-title">Selected CPU</h3>
                                        <p class="text-amber-900"><?= $cpu['name'] ?></p>
                                        <p class="text-sm text-amber-700">Socket: <?= $cpu['socket_type'] ?></p>
                                    </div>
                                </div>
                                <label for="motherboard-select" class="wood-label flex items-center">
                                    <i class="fas fa-server mr-2 text-amber-800"></i> Choose a Compatible Motherboard:
                                </label>
                                <div class="component-gallery grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                                    <?php
                                    $motherboards = fetchComponents('Motherboard', $db, ['socket_type' => $cpu['socket_type']]);
                                    if (!empty($motherboards)):
                                        foreach ($motherboards as $motherboard):
                                    ?>
                                    <label for="mobo-<?= $motherboard['id'] ?>" class="component-card cursor-pointer block relative bg-amber-50 rounded-lg border-2 border-amber-100 hover:border-amber-600 transition-all p-4">
                                        <input type="radio" name="component_id" id="mobo-<?= $motherboard['id'] ?>" value="<?= $motherboard['id'] ?>" class="hidden component-radio">
                                        <div class="flex flex-col items-center">
                                            <div class="component-image-container h-24 flex items-center justify-center mb-2">
                                                <img src="assets/images/products/<?= htmlspecialchars($motherboard['image']) ?>" alt="<?= htmlspecialchars($motherboard['name']) ?>" class="component-image max-h-24 max-w-full">
                                            </div>
                                            <h5 class="component-name text-amber-900 font-medium text-center"><?= htmlspecialchars($motherboard['name']) ?></h5>
                                            <p class="component-price text-amber-800 font-bold mt-1">$<?= number_format($motherboard['price'], 2) ?></p>
                                            <div class="flex flex-wrap gap-2 mt-2 justify-center">
                                                <span class="text-xs bg-amber-100 px-2 py-1 rounded-full"><?= htmlspecialchars($motherboard['socket_type']) ?></span>
                                                <span class="text-xs bg-amber-100 px-2 py-1 rounded-full"><?= htmlspecialchars($motherboard['ram_type']) ?></span>
                                                <span class="text-xs bg-amber-100 px-2 py-1 rounded-full"><?= htmlspecialchars($motherboard['form_factor']) ?></span>
                                            </div>
                                        </div>
                                        <div class="selected-indicator absolute top-2 right-2 opacity-0 transition-opacity">
                                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                        </div>
                                    </label>
                                    <?php
                                        endforeach;
                                    else:
                                    ?>
                                    <div class="col-span-3 p-4 bg-amber-100 text-amber-800 rounded-lg">
                                        <p class="flex items-center"><i class="fas fa-exclamation-triangle mr-2 text-amber-600"></i> No compatible motherboards found for this socket type. Please go back and select a different CPU.</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <!-- Fallback select dropdown for mobile devices -->
                                <select id="motherboard-select" name="component_id_mobile" class="wood-input mt-4 md:hidden">
                                    <option value="">-- Select Motherboard --</option>
                                    <?php
                                    foreach ($motherboards as $motherboard) {
                                        echo "<option value='{$motherboard['id']}'>{$motherboard['name']} - \${$motherboard['price']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="wooden-divider"></div>
                            <div class="flex justify-between mt-6">
                                <a href="reset_build.php?component=cpu_cooler" class="wooden-cart-button bg-red-700">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    <span class="button-text text-white">Go Back</span>
                                </a>
                                <input type="hidden" name="component_type" value="motherboard">
                                <button type="submit" class="wooden-cart-button">
                                    <span class="button-text">Next Step</span>
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php elseif (!$_SESSION['pc_build']['ram']): ?>
                    <div class="wood-card mb-8 overflow-hidden animated-card">
                        <div class="bg-amber-800 py-3 px-6 text-amber-50">
                            <h2 class="text-xl font-bold flex items-center">
                                <i class="fas fa-memory mr-2"></i> Step 4: Select RAM
                            </h2>
                        </div>
                        <div class="p-6 card-content">
                            <div class="mb-6">
                                <p class="wood-card-text mb-4">RAM (Random Access Memory) is your computer's short-term memory. It temporarily stores data that your CPU needs to access quickly.</p>
                                <?php
                                $motherboardId = $_SESSION['pc_build']['motherboard'];
                                $motherboard = fetchComponents('motherboard', $db, ['id' => $motherboardId])[0];
                                ?>
                                <div class="flex flex-col md:flex-row gap-4 mb-4">
                                    <div class="wood-card bg-opacity-50 p-4 flex-1">
                                        <h3 class="wood-card-title">Selected Motherboard</h3>
                                        <p class="text-amber-900"><?= $motherboard['name'] ?></p>
                                        <p class="text-sm text-amber-700">RAM Type: <?= $motherboard['ram_type'] ?></p>
                                    </div>
                                </div>
                                <label for="ram-select" class="wood-label flex items-center">
                                    <i class="fas fa-memory mr-2 text-amber-800"></i> Choose Compatible RAM:
                                </label>
                                <div class="component-gallery grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                                    <?php
                                    $rams = fetchComponents('RAM', $db, ['ram_type' => $motherboard['ram_type']]);
                                    if (!empty($rams)):
                                        foreach ($rams as $ram):
                                    ?>
                                    <label for="ram-<?= $ram['id'] ?>" class="component-card cursor-pointer block relative bg-amber-50 rounded-lg border-2 border-amber-100 hover:border-amber-600 transition-all p-4">
                                        <input type="radio" name="component_id" id="ram-<?= $ram['id'] ?>" value="<?= $ram['id'] ?>" class="hidden component-radio">
                                        <div class="flex flex-col items-center">
                                            <div class="component-image-container h-24 flex items-center justify-center mb-2">
                                                <img src="assets/images/products/<?= htmlspecialchars($ram['image']) ?>" alt="<?= htmlspecialchars($ram['name']) ?>" class="component-image max-h-24 max-w-full">
                                            </div>
                                            <h5 class="component-name text-amber-900 font-medium text-center"><?= htmlspecialchars($ram['name']) ?></h5>
                                            <p class="component-price text-amber-800 font-bold mt-1">$<?= number_format($ram['price'], 2) ?></p>
                                            <p class="component-spec text-xs text-amber-700 mt-1"><?= htmlspecialchars($ram['ram_type']) ?></p>
                                        </div>
                                        <div class="selected-indicator absolute top-2 right-2 opacity-0 transition-opacity">
                                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                        </div>
                                    </label>
                                    <?php
                                        endforeach;
                                    else:
                                    ?>
                                    <div class="col-span-3 p-4 bg-amber-100 text-amber-800 rounded-lg">
                                        <p class="flex items-center"><i class="fas fa-exclamation-triangle mr-2 text-amber-600"></i> No compatible RAM found for this motherboard. Please go back and select a different motherboard.</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <!-- Fallback select dropdown for mobile devices -->
                                <select id="ram-select" name="component_id_mobile" class="wood-input mt-4 md:hidden">
                                    <option value="">-- Select RAM --</option>
                                    <?php
                                    foreach ($rams as $ram) {
                                        echo "<option value='{$ram['id']}'>{$ram['name']} - \${$ram['price']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="wooden-divider"></div>
                            <div class="flex justify-between mt-6">
                                <a href="reset_build.php?component=motherboard" class="wooden-cart-button bg-red-700">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    <span class="button-text text-white">Go Back</span>
                                </a>
                                <input type="hidden" name="component_type" value="ram">
                                <button type="submit" class="wooden-cart-button">
                                    <span class="button-text">Next Step</span>
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php elseif (!$_SESSION['pc_build']['storage']): ?>
                    <div class="wood-card mb-8 overflow-hidden animated-card">
                        <div class="bg-amber-800 py-3 px-6 text-amber-50">
                            <h2 class="text-xl font-bold flex items-center">
                                <i class="fas fa-hdd mr-2"></i> Step 5: Select Storage
                            </h2>
                        </div>
                        <div class="p-6 card-content">
                            <div class="mb-6">
                                <p class="wood-card-text mb-4">Storage is where all your files, programs, and operating system are saved. Choose between fast SSDs or high-capacity HDDs.</p>
                                <label for="storage-select" class="wood-label flex items-center">
                                    <i class="fas fa-hdd mr-2 text-amber-800"></i> Choose Storage:
                                </label>
                                <div class="component-gallery grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                                    <?php
                                    $storages = fetchComponents('Storage', $db);
                                    foreach ($storages as $storage):
                                    ?>
                                    <label for="storage-<?= $storage['id'] ?>" class="component-card cursor-pointer block relative bg-amber-50 rounded-lg border-2 border-amber-100 hover:border-amber-600 transition-all p-4">
                                        <input type="radio" name="component_id" id="storage-<?= $storage['id'] ?>" value="<?= $storage['id'] ?>" class="hidden component-radio">
                                        <div class="flex flex-col items-center">
                                            <div class="component-image-container h-24 flex items-center justify-center mb-2">
                                                <img src="assets/images/products/<?= htmlspecialchars($storage['image']) ?>" alt="<?= htmlspecialchars($storage['name']) ?>" class="component-image max-h-24 max-w-full">
                                            </div>
                                            <h5 class="component-name text-amber-900 font-medium text-center"><?= htmlspecialchars($storage['name']) ?></h5>
                                            <p class="component-price text-amber-800 font-bold mt-1">$<?= number_format($storage['price'], 2) ?></p>
                                        </div>
                                        <div class="selected-indicator absolute top-2 right-2 opacity-0 transition-opacity">
                                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                        </div>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                                <!-- Fallback select dropdown for mobile devices -->
                                <select id="storage-select" name="component_id_mobile" class="wood-input mt-4 md:hidden">
                                    <option value="">-- Select Storage --</option>
                                    <?php
                                    foreach ($storages as $storage) {
                                        echo "<option value='{$storage['id']}'>{$storage['name']} - \${$storage['price']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="wooden-divider"></div>
                            <div class="flex justify-between mt-6">
                                <a href="reset_build.php?component=ram" class="wooden-cart-button bg-red-700">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    <span class="button-text text-white">Go Back</span>
                                </a>
                                <input type="hidden" name="component_type" value="storage">
                                <button type="submit" class="wooden-cart-button">
                                    <span class="button-text">Next Step</span>
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php elseif (!$_SESSION['pc_build']['gpu']): ?>
                    <div class="wood-card mb-8 overflow-hidden animated-card">
                        <div class="bg-amber-800 py-3 px-6 text-amber-50">
                            <h2 class="text-xl font-bold flex items-center">
                                <i class="fas fa-tv mr-2"></i> Step 6: Select a GPU
                            </h2>
                        </div>
                        <div class="p-6 card-content">
                            <div class="mb-6">
                                <p class="wood-card-text mb-4">The GPU (Graphics Processing Unit) handles rendering images, videos, and animations. It's essential for gaming and graphic design.</p>
                                <label for="gpu-select" class="wood-label flex items-center">
                                    <i class="fas fa-tv mr-2 text-amber-800"></i> Choose a GPU:
                                </label>
                                <div class="component-gallery grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                                    <?php
                                    $gpus = fetchComponents('GPU', $db);
                                    foreach ($gpus as $gpu):
                                    ?>
                                    <label for="gpu-<?= $gpu['id'] ?>" class="component-card cursor-pointer block relative bg-amber-50 rounded-lg border-2 border-amber-100 hover:border-amber-600 transition-all p-4">
                                        <input type="radio" name="component_id" id="gpu-<?= $gpu['id'] ?>" value="<?= $gpu['id'] ?>" class="hidden component-radio">
                                        <div class="flex flex-col items-center">
                                            <div class="component-image-container h-24 flex items-center justify-center mb-2">
                                                <img src="assets/images/products/<?= htmlspecialchars($gpu['image']) ?>" alt="<?= htmlspecialchars($gpu['name']) ?>" class="component-image max-h-24 max-w-full">
                                            </div>
                                            <h5 class="component-name text-amber-900 font-medium text-center"><?= htmlspecialchars($gpu['name']) ?></h5>
                                            <p class="component-price text-amber-800 font-bold mt-1">$<?= number_format($gpu['price'], 2) ?></p>
                                            <?php if ($gpu['wattage']): ?>
                                            <p class="component-spec text-xs text-amber-700 mt-1">Power Required: <?= $gpu['wattage'] ?>W</p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="selected-indicator absolute top-2 right-2 opacity-0 transition-opacity">
                                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                        </div>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                                <!-- Fallback select dropdown for mobile devices -->
                                <select id="gpu-select" name="component_id_mobile" class="wood-input mt-4 md:hidden">
                                    <option value="">-- Select GPU --</option>
                                    <?php
                                    foreach ($gpus as $gpu) {
                                        echo "<option value='{$gpu['id']}'>{$gpu['name']} - \${$gpu['price']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="wooden-divider"></div>
                            <div class="flex justify-between mt-6">
                                <a href="reset_build.php?component=storage" class="wooden-cart-button bg-red-700">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    <span class="button-text text-white">Go Back</span>
                                </a>
                                <input type="hidden" name="component_type" value="gpu">
                                <button type="submit" class="wooden-cart-button">
                                    <span class="button-text">Next Step</span>
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php elseif (!$_SESSION['pc_build']['psu']): ?>
                    <div class="wood-card mb-8 overflow-hidden animated-card">
                        <div class="bg-amber-800 py-3 px-6 text-amber-50">
                            <h2 class="text-xl font-bold flex items-center">
                                <i class="fas fa-plug mr-2"></i> Step 7: Select a PSU
                            </h2>
                        </div>
                        <div class="p-6 card-content">
                            <div class="mb-6">
                                <p class="wood-card-text mb-4">The PSU (Power Supply Unit) delivers power to all components in your PC. Make sure it provides enough wattage for your build.</p>
                                <?php
                                $gpuId = $_SESSION['pc_build']['gpu'];
                                $gpu = $gpuId ? fetchComponents('GPU', $db, ['id' => $gpuId])[0] : null;
                                ?>
                                <?php if ($gpu): ?>
                                <div class="flex flex-col md:flex-row gap-4 mb-4">
                                    <div class="wood-card bg-opacity-50 p-4 flex-1">
                                        <h3 class="wood-card-title">Selected GPU</h3>
                                        <p class="text-amber-900"><?= $gpu['name'] ?></p>
                                        <p class="text-sm text-amber-700">Recommended Wattage: <?= $gpu['wattage'] ?>W</p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <label for="psu-select" class="wood-label flex items-center">
                                    <i class="fas fa-plug mr-2 text-amber-800"></i> Choose a PSU:
                                </label>
                                <div class="component-gallery grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                                    <?php
                                    $psus = $gpu ? fetchComponents('Power Supply', $db, ['wattage' => $gpu['wattage']]) : fetchComponents('Power Supply', $db);
                                    if (!empty($psus)):
                                        foreach ($psus as $psu):
                                    ?>
                                    <label for="psu-<?= $psu['id'] ?>" class="component-card cursor-pointer block relative bg-amber-50 rounded-lg border-2 border-amber-100 hover:border-amber-600 transition-all p-4">
                                        <input type="radio" name="component_id" id="psu-<?= $psu['id'] ?>" value="<?= $psu['id'] ?>" class="hidden component-radio">
                                        <div class="flex flex-col items-center">
                                            <div class="component-image-container h-24 flex items-center justify-center mb-2">
                                                <img src="assets/images/products/<?= htmlspecialchars($psu['image']) ?>" alt="<?= htmlspecialchars($psu['name']) ?>" class="component-image max-h-24 max-w-full">
                                            </div>
                                            <h5 class="component-name text-amber-900 font-medium text-center"><?= htmlspecialchars($psu['name']) ?></h5>
                                            <p class="component-price text-amber-800 font-bold mt-1">$<?= number_format($psu['price'], 2) ?></p>
                                            <span class="bg-amber-100 text-amber-800 text-xs px-2 py-1 rounded-full mt-1"><?= $psu['wattage'] ?>W</span>
                                        </div>
                                        <div class="selected-indicator absolute top-2 right-2 opacity-0 transition-opacity">
                                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                        </div>
                                    </label>
                                    <?php
                                        endforeach;
                                    else:
                                    ?>
                                    <div class="col-span-3 p-4 bg-amber-100 text-amber-800 rounded-lg">
                                        <p class="flex items-center"><i class="fas fa-exclamation-triangle mr-2 text-amber-600"></i> No compatible power supplies found for your GPU's wattage requirements. Please go back and select a different GPU.</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <!-- Fallback select dropdown for mobile devices -->
                                <select id="psu-select" name="component_id_mobile" class="wood-input mt-4 md:hidden">
                                    <option value="">-- Select PSU --</option>
                                    <?php
                                    foreach ($psus as $psu) {
                                        echo "<option value='{$psu['id']}'>{$psu['name']} ({$psu['wattage']}W) - \${$psu['price']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="wooden-divider"></div>
                            <div class="flex justify-between mt-6">
                                <a href="reset_build.php?component=gpu" class="wooden-cart-button bg-red-700">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    <span class="button-text text-white">Go Back</span>
                                </a>
                                <input type="hidden" name="component_type" value="psu">
                                <button type="submit" class="wooden-cart-button">
                                    <span class="button-text">Next Step</span>
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php elseif (!$_SESSION['pc_build']['case']): ?>
                    <div class="wood-card mb-8 overflow-hidden animated-card">
                        <div class="bg-amber-800 py-3 px-6 text-amber-50">
                            <h2 class="text-xl font-bold flex items-center">
                                <i class="fas fa-desktop mr-2"></i> Step 8: Select a Case
                            </h2>
                        </div>
                        <div class="p-6 card-content">
                            <div class="mb-6">
                                <p class="wood-card-text mb-4">The case houses and protects all your PC components. Choose one that fits your motherboard form factor and has good airflow.</p>
                                <?php
                                $motherboardId = $_SESSION['pc_build']['motherboard'];
                                $motherboard = fetchComponents('motherboard', $db, ['id' => $motherboardId])[0];
                                ?>
                                <div class="flex flex-col md:flex-row gap-4 mb-4">
                                    <div class="wood-card bg-opacity-50 p-4 flex-1">
                                        <h3 class="wood-card-title">Selected Motherboard</h3>
                                        <p class="text-amber-900"><?= $motherboard['name'] ?></p>
                                        <p class="text-sm text-amber-700">Form Factor: <?= $motherboard['form_factor'] ?></p>
                                    </div>
                                </div>
                                <label for="case-select" class="wood-label flex items-center">
                                    <i class="fas fa-desktop mr-2 text-amber-800"></i> Choose a Compatible Case:
                                </label>
                                <div class="component-gallery grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                                    <?php
                                    $cases = fetchComponents('Case', $db, ['form_factor' => $motherboard['form_factor']]);
                                    if (!empty($cases)):
                                        foreach ($cases as $case):
                                    ?>
                                    <label for="case-<?= $case['id'] ?>" class="component-card cursor-pointer block relative bg-amber-50 rounded-lg border-2 border-amber-100 hover:border-amber-600 transition-all p-4">
                                        <input type="radio" name="component_id" id="case-<?= $case['id'] ?>" value="<?= $case['id'] ?>" class="hidden component-radio">
                                        <div class="flex flex-col items-center">
                                            <div class="component-image-container h-24 flex items-center justify-center mb-2">
                                                <img src="assets/images/products/<?= htmlspecialchars($case['image']) ?>" alt="<?= htmlspecialchars($case['name']) ?>" class="component-image max-h-24 max-w-full">
                                            </div>
                                            <h5 class="component-name text-amber-900 font-medium text-center"><?= htmlspecialchars($case['name']) ?></h5>
                                            <p class="component-price text-amber-800 font-bold mt-1">$<?= number_format($case['price'], 2) ?></p>
                                            <p class="component-spec text-xs text-amber-700 mt-1">Form Factor: <?= htmlspecialchars($case['form_factor']) ?></p>
                                        </div>
                                        <div class="selected-indicator absolute top-2 right-2 opacity-0 transition-opacity">
                                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                        </div>
                                    </label>
                                    <?php
                                        endforeach;
                                    else:
                                    ?>
                                    <div class="col-span-3 p-4 bg-amber-100 text-amber-800 rounded-lg">
                                        <p class="flex items-center"><i class="fas fa-exclamation-triangle mr-2 text-amber-600"></i> No compatible cases found for your motherboard's form factor. Please go back and select a different motherboard.</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <!-- Fallback select dropdown for mobile devices -->
                                <select id="case-select" name="component_id_mobile" class="wood-input mt-4 md:hidden">
                                    <option value="">-- Select Case --</option>
                                    <?php
                                    foreach ($cases as $case) {
                                        echo "<option value='{$case['id']}'>{$case['name']} - \${$case['price']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="wooden-divider"></div>
                            <div class="flex justify-between mt-6">
                                <a href="reset_build.php?component=psu" class="wooden-cart-button bg-red-700">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    <span class="button-text text-white">Go Back</span>
                                </a>
                                <input type="hidden" name="component_type" value="case">
                                <button type="submit" class="wooden-cart-button bg-green-700">
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    <span class="button-text text-white">Finish & Add to Cart</span>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
            <!-- PC Building Guide (moved to bottom of left column) -->
            <div class="mt-10">
                <h2 class="page-title text-xl mb-6">PC Building Guide</h2>
                <div class="wood-card">
                    <div class="card-content p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="wood-badge mx-auto w-12 h-12 flex items-center justify-center mb-4 rounded-full bg-amber-800 text-white text-2xl">1</div>
                                <h3 class="wood-card-title">Choose Compatible Parts</h3>
                                <p class="text-amber-800">Our wizard helps you select components that work well together, ensuring compatibility.</p>
                            </div>
                            <div class="text-center">
                                <div class="wood-badge mx-auto w-12 h-12 flex items-center justify-center mb-4 rounded-full bg-amber-800 text-white text-2xl">2</div>
                                <h3 class="wood-card-title">Review Your Build</h3>
                                <p class="text-amber-800">Double-check your selections to make sure they meet your performance needs.</p>
                            </div>
                            <div class="text-center">
                                <div class="wood-badge mx-auto w-12 h-12 flex items-center justify-center mb-4 rounded-full bg-amber-800 text-white text-2xl">3</div>
                                <h3 class="wood-card-title">Complete Your Order</h3>
                                <p class="text-amber-800">Add your custom PC build to cart and proceed to checkout to get your dream PC.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right column - Build Preview -->
        <div class="lg:w-1/3">
            <?php
            $selectedComponentsCount = 0;
            foreach ($_SESSION['pc_build'] as $component) {
                if ($component !== null) {
                    $selectedComponentsCount++;
                }
            }
            ?>
            <div class="sticky top-4 space-y-6">
                <div class="wood-card overflow-hidden animated-card">
                    <div class="bg-amber-800 py-3 px-6 text-amber-50">
                        <h2 class="text-xl font-bold flex items-center">
                            <i class="fas fa-desktop mr-2"></i> Your Build Preview
                        </h2>
                    </div>
                    <div class="p-4 card-content">
                        <?php if($selectedComponentsCount == 0): ?>
                            <div class="p-6 text-center">
                                <i class="fas fa-desktop text-amber-300 text-5xl mb-4"></i>
                                <p class="text-amber-800">Your PC build preview will appear here as you select components.</p>
                            </div>
                        <?php else: ?>
                            <!-- PC Case Illustration -->
                            <div class="pc-case-illustration mx-auto bg-gray-900 border-2 border-amber-800 rounded-lg p-6 relative" style="height: 300px;">
                                <!-- Case Chassis -->
                                <div class="absolute inset-0 bg-gray-900 rounded-lg border-2 border-amber-800 overflow-hidden">
                                    <div class="wooden-texture-overlay opacity-5"></div>
                                </div>
                                <?php
                                if ($_SESSION['pc_build']['cpu'] !== null) {
                                    $cpuDetails = fetchComponents('CPU', $db, ['id' => $_SESSION['pc_build']['cpu']])[0];
                                ?>
                                <div class="absolute top-8 right-0 left-0 mx-auto bg-gray-700 rounded-md p-2 w-24 h-24 border border-amber-600 flex items-center justify-center component-highlight" data-component="cpu">
                                    <div class="text-center">
                                        <img src="assets/images/products/<?= htmlspecialchars($cpuDetails['image']) ?>" class="h-12 object-contain mx-auto" alt="CPU">
                                        <span class="text-white text-xs block mt-1">CPU</span>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php
                                if ($_SESSION['pc_build']['motherboard'] !== null) {
                                    $motherboardDetails = fetchComponents('Motherboard', $db, ['id' => $_SESSION['pc_build']['motherboard']])[0];
                                ?>
                                <div class="absolute top-6 left-6 right-6 bottom-6 bg-blue-900 opacity-40 rounded-md border border-amber-700 component-highlight" data-component="motherboard">
                                    <div class="absolute bottom-2 right-2 text-white text-xs">
                                        <span>Motherboard</span>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php
                                if ($_SESSION['pc_build']['cpu_cooler'] !== null) {
                                    $coolerDetails = fetchComponents('CPU Cooler', $db, ['id' => $_SESSION['pc_build']['cpu_cooler']])[0];
                                ?>
                                <div class="absolute top-32 right-0 left-0 mx-auto bg-gray-700 rounded-md p-2 w-28 h-12 border border-amber-600 flex items-center justify-center component-highlight" data-component="cpu_cooler">
                                    <div class="text-center">
                                        <i class="fas fa-fan text-amber-500 fa-spin"></i>
                                        <span class="text-white text-xs block">CPU Cooler</span>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php
                                if ($_SESSION['pc_build']['ram'] !== null) {
                                    $ramDetails = fetchComponents('RAM', $db, ['id' => $_SESSION['pc_build']['ram']])[0];
                                ?>
                                <div class="absolute top-10 right-10 bg-gray-800 rounded-md p-2 w-8 h-32 border border-amber-600 flex items-center justify-center component-highlight" data-component="ram">
                                    <div class="text-center">
                                        <i class="fas fa-memory text-amber-500 rotate-90"></i>
                                        <span class="text-white text-xs block mt-1 transform rotate-90">RAM</span>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php
                                if ($_SESSION['pc_build']['gpu'] !== null) {
                                    $gpuDetails = fetchComponents('GPU', $db, ['id' => $_SESSION['pc_build']['gpu']])[0];
                                ?>
                                <div class="absolute bottom-10 left-8 right-8 mx-auto bg-gray-700 rounded-md p-2 h-16 border border-amber-600 flex items-center justify-center component-highlight" data-component="gpu">
                                    <div class="text-center flex items-center">
                                        <img src="assets/images/products/<?= htmlspecialchars($gpuDetails['image']) ?>" class="h-10 object-contain mr-2" alt="GPU">
                                        <span class="text-white text-xs block">GPU</span>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php
                                if ($_SESSION['pc_build']['storage'] !== null) {
                                    $storageDetails = fetchComponents('Storage', $db, ['id' => $_SESSION['pc_build']['storage']])[0];
                                ?>
                                <div class="absolute top-10 left-10 bg-gray-800 rounded-md p-2 w-8 h-24 border border-amber-600 flex items-center justify-center component-highlight" data-component="storage">
                                    <div class="text-center">
                                        <i class="fas fa-hdd text-amber-500 rotate-90"></i>
                                        <span class="text-white text-xs block mt-1 transform rotate-90">Storage</span>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php
                                if ($_SESSION['pc_build']['psu'] !== null) {
                                    $psuDetails = fetchComponents('Power Supply', $db, ['id' => $_SESSION['pc_build']['psu']])[0];
                                ?>
                                <div class="absolute bottom-4 left-4 bg-gray-700 rounded-md p-2 w-20 h-20 border border-amber-600 flex items-center justify-center component-highlight" data-component="psu">
                                    <div class="text-center">
                                        <i class="fas fa-plug text-amber-500"></i>
                                        <span class="text-white text-xs block mt-1">PSU</span>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php
                                if ($_SESSION['pc_build']['case'] !== null) {
                                    $caseDetails = fetchComponents('Case', $db, ['id' => $_SESSION['pc_build']['case']])[0];
                                ?>
                                <div class="absolute bottom-2 right-2 text-amber-500 text-xs font-bold">
                                    <?= htmlspecialchars($caseDetails['name']) ?>
                                </div>
                                <?php } ?>
                            </div>
                            <!-- Selected components list -->
                            <div class="mt-4 p-4 bg-amber-50 rounded-lg">
                                <h4 class="font-bold text-amber-900 mb-3">Selected Components</h4>
                                <div class="space-y-2 max-h-40 overflow-y-auto">
                                    <?php
                                    $componentTypes = [
                                        'cpu' => 'CPU',
                                        'cpu_cooler' => 'CPU Cooler',
                                        'motherboard' => 'Motherboard',
                                        'ram' => 'RAM',
                                        'storage' => 'Storage',
                                        'gpu' => 'GPU',
                                        'psu' => 'Power Supply',
                                        'case' => 'Case'
                                    ];
                                    $totalPrice = 0;
                                    foreach ($componentTypes as $key => $label) {
                                        if ($_SESSION['pc_build'][$key] !== null) {
                                            $componentId = $_SESSION['pc_build'][$key];
                                            $componentDetails = null;
                                            $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
                                            $stmt->execute([$componentId]);
                                            $componentDetails = $stmt->fetch(PDO::FETCH_ASSOC);
                                            if ($componentDetails) {
                                                $totalPrice += $componentDetails['price'];
                                    ?>
                                    <div class="flex items-center justify-between border-b border-amber-200 pb-1 selected-component" data-component="<?= $key ?>">
                                        <div class="flex items-center">
                                            <span class="w-24 text-xs text-amber-700"><?= $label ?>:</span>
                                            <span class="text-sm text-amber-900 truncate"><?= htmlspecialchars($componentDetails['name']) ?></span>
                                        </div>
                                        <span class="text-sm font-bold text-amber-800">$<?= number_format($componentDetails['price'], 2) ?></span>
                                    </div>
                                    <?php
                                            }
                                        }
                                    }
                                    if ($totalPrice == 0) {
                                        echo '<p class="text-amber-700 text-sm italic">No components selected yet</p>';
                                    }
                                    ?>
                                </div>
                                <?php if ($totalPrice > 0): ?>
                                <div class="mt-4 pt-3 border-t border-amber-200 flex justify-between items-center">
                                    <span class="text-amber-800 text-sm">Estimated Total:</span>
                                    <span class="text-amber-900 text-lg font-bold">$<?= number_format($totalPrice, 2) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($selectedComponentsCount > 1): ?>
                <!-- Build Performance Stats -->
                <div class="wood-card overflow-hidden animated-card">
                    <div class="bg-amber-800 py-3 px-6 text-amber-50">
                        <h2 class="text-lg font-bold flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i> Performance Stats
                        </h2>
                    </div>
                    <div class="p-4 card-content">
                        <div class="space-y-3">
                            <?php
                            $stats = [
                                'processing' => 0,
                                'graphics' => 0,
                                'memory' => 0,
                                'storage' => 0,
                                'build_quality' => 0
                            ];
                            if ($_SESSION['pc_build']['cpu'] !== null) {
                                $cpuDetails = fetchComponents('CPU', $db, ['id' => $_SESSION['pc_build']['cpu']])[0];
                                $stats['processing'] = min(100, ($cpuDetails['price'] / 600) * 100);
                            }
                            if ($_SESSION['pc_build']['gpu'] !== null) {
                                $gpuDetails = fetchComponents('GPU', $db, ['id' => $_SESSION['pc_build']['gpu']])[0];
                                $stats['graphics'] = min(100, ($gpuDetails['price'] / 1600) * 100);
                            }
                            if ($_SESSION['pc_build']['ram'] !== null) {
                                $ramDetails = fetchComponents('RAM', $db, ['id' => $_SESSION['pc_build']['ram']])[0];
                                $stats['memory'] = min(100, ($ramDetails['price'] / 170) * 100);
                            }
                            if ($_SESSION['pc_build']['storage'] !== null) {
                                $storageDetails = fetchComponents('Storage', $db, ['id' => $_SESSION['pc_build']['storage']])[0];
                                $stats['storage'] = min(100, ($storageDetails['price'] / 130) * 100);
                            }
                            $totalPrice = 0;
                            $maxPrice = 3000;
                            foreach ($_SESSION['pc_build'] as $componentType => $componentId) {
                                if ($componentId !== null) {
                                    $stmt = $db->prepare("SELECT price FROM products WHERE id = ?");
                                    $stmt->execute([$componentId]);
                                    $price = $stmt->fetchColumn();
                                    $totalPrice += $price;
                                }
                            }
                            $stats['build_quality'] = min(100, ($totalPrice / $maxPrice) * 100);
                            foreach ($stats as $statName => $statValue) {
                                $color = 'bg-amber-500';
                                if ($statValue < 30) {
                                    $color = 'bg-red-500';
                                } else if ($statValue > 70) {
                                    $color = 'bg-green-500';
                                }
                                $displayName = ucwords(str_replace('_', ' ', $statName));
                            ?>
                            <div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-amber-900 font-medium"><?= $displayName ?></span>
                                    <span class="text-amber-800"><?= round($statValue) ?>%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="<?= $color ?> h-2 rounded-full" style="width: <?= $statValue ?>%"></div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <?php if ($progress == 100): ?>
                        <div class="mt-4">
                            <button type="submit" class="wooden-cart-button bg-green-700 w-full">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                <span class="button-text text-white">Add Complete Build to Cart</span>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.wood-card').each(function() {
        $(this).prepend('<div class="wooden-texture-footer absolute inset-0 z-0 opacity-10"></div>');
    });
    $('.animated-card').each(function(index) {
        $(this).css({
            'opacity': 0,
            'transform': 'translateY(20px)'
        });
        setTimeout(() => {
            $(this).animate({
                'opacity': 1,
                'transform': 'translateY(0)'
            }, 400, 'easeOutCubic');
        }, 200);
    });
    $('.wood-input').each(function() {
        $(this).css({
            'background-color': '#f5e0c0',
            'border': '1px solid #b08463',
            'color': '#5c4033',
            'border-radius': '8px',
            'padding': '10px 12px',
            'width': '100%',
            'appearance': 'none',
            'background-image': 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' fill=\'%235c4033\' viewBox=\'0 0 16 16\'%3E%3Cpath d=\'M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z\'/%3E%3C/svg%3E")',
            'background-repeat': 'no-repeat',
            'background-position': 'right 12px center',
            'background-size': '16px',
            'padding-right': '40px'
        });
    }).hover(
        function() {
            $(this).css({
                'border-color': '#714329',
                'box-shadow': '0 0 0 3px rgba(176, 132, 99, 0.2)'
            });
        },
        function() {
            $(this).css({
                'border-color': '#b08463',
                'box-shadow': 'none'
            });
        }
    );
    $('.component-radio').change(function() {
        $('.component-card').removeClass('selected').css('border-color', '');
        $('.selected-indicator').css('opacity', '0');
        if (this.checked) {
            $(this).closest('.component-card')
                .addClass('selected')
                .css('border-color', '#5c4033');
            $(this).closest('.component-card').find('.selected-indicator').css('opacity', '1');
            $('#' + $(this).closest('.component-gallery').next('select').attr('id')).val($(this).val());
        }
    });
    $('select[name^="component_id_mobile"]').change(function() {
        const selectedValue = $(this).val();
        if (selectedValue) {
            $(`input[type="radio"][value="${selectedValue}"]`).prop('checked', true).trigger('change');
        }
    });
    $('.component-card').hover(
        function() {
            if (!$(this).hasClass('selected')) {
                $(this).css('transform', 'translateY(-5px)');
                $(this).css('box-shadow', '0 10px 15px -3px rgba(0, 0, 0, 0.1)');
            }
        },
        function() {
            if (!$(this).hasClass('selected')) {
                $(this).css('transform', '');
                $(this).css('box-shadow', '');
            }
        }
    );
    // Add component image hover zoom effect
    $('.component-image').hover(
        function() {
            $(this).css('transform', 'scale(1.1)');
            $(this).css('transition', 'transform 0.3s ease');
        },
        function() {
            $(this).css('transform', '');
        }
    );
    // PC Build Preview highlight effects
    $('.component-highlight').hover(
        function() {
            const component = $(this).data('component');
            $(this).css({
                'border-color': '#f59e0b',
                'border-width': '2px',
                'box-shadow': '0 0 15px rgba(245, 158, 11, 0.5)'
            });
            $(`.selected-component[data-component="${component}"]`).addClass('bg-amber-100');
        },
        function() {
            const component = $(this).data('component');
            $(this).css({
                'border-color': '',
                'border-width': '',
                'box-shadow': ''
            });
            // Remove highlight from corresponding component
            $(`.selected-component[data-component="${component}"]`).removeClass('bg-amber-100');
        }
    );
});
</script>
<?php require_once 'includes/footer.php'; ?>
<?php ob_end_flush(); ?>