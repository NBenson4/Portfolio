<?php
// Load CSV data
$filename = 'expanded_clothing_store_products.csv';
$products = [];

// Open the CSV file and read the data into a 2D array
if (($handle = fopen($filename, 'r')) !== FALSE) {
    // Skip the header row
    fgetcsv($handle);

    // Read each row and store it in the $products array
    while (($data = fgetcsv($handle)) !== FALSE) {
        $products[] = $data;
    }
    fclose($handle);
}

// Collect user data
$time_of_search = date('Y-m-d H:i:s'); // Get current date and time
$browser = $_SERVER['HTTP_USER_AGENT']; // Get user's browser information

// Initialize search parameters for advanced search
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';
$color = isset($_GET['color']) ? $_GET['color'] : '';
$size = isset($_GET['size']) ? $_GET['size'] : '';

// Regular search parameter (from main search bar)
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Function to filter products by advanced search criteria
function advancedSearch($products, $brand, $color, $size) {
    $results = [];
    foreach ($products as $product) {
        if ((!$brand || stripos($product[0], $brand) !== FALSE) && // Search in 'brand'
            (!$color || stripos($product[2], $color) !== FALSE) && // Search in 'color'
            (!$size || stripos($product[3], $size) !== FALSE)) {   // Search in 'size'
            $results[] = $product;
        }
    }
    return $results;
}

// Function to filter products by regular search
function regularSearch($products, $search) {
    $results = [];
    foreach ($products as $product) {
        if (stripos($product[0], $search) !== FALSE || 
            stripos($product[1], $search) !== FALSE || 
            stripos($product[2], $search) !== FALSE || 
            stripos($product[3], $search) !== FALSE) {
            $results[] = $product;
        }
    }
    return $results;
}

// Determine which search was used
if (!empty($search)) {
    // Perform regular search
    $results = regularSearch($products, $search);

    // Log search data for regular search
    $log_entry = "Regular Search: '$search' | Time: $time_of_search | Browser: $browser" . PHP_EOL;
} else {
    // Perform advanced search
    $results = advancedSearch($products, $brand, $color, $size);

    // Log search data for advanced search
    $log_entry = "Advanced Search - Brand: '$brand', Color: '$color', Size: '$size' | Time: $time_of_search | Browser: $browser" . PHP_EOL;
}

// Log search data to a log file
file_put_contents('user_search_log.txt', $log_entry, FILE_APPEND);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Advanced Search - Cairn Clothes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Cairn Clothes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Men</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Women</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Accessories</a></li>
                    <li class="nav-item"><a class="nav-link active" href="advanced_search.php">Advanced Search</a></li>
                </ul>
                <form class="d-flex" action="advanced_search.php" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search for clothes..." aria-label="Search" />
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Advanced Search Form -->
    <div class="container mt-4">
        <h2>Advanced Search</h2>
        <form method="GET" action="advanced_search.php">
            <div class="mb-3">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" class="form-control" id="brand" name="brand" placeholder="Enter brand name">
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">Color</label>
                <input type="text" class="form-control" id="color" name="color" placeholder="Enter color">
            </div>
            <div class="mb-3">
                <label for="size" class="form-label">Size</label>
                <input type="text" class="form-control" id="size" name="size" placeholder="Enter size">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <!-- Search Results -->
    <div class="container mt-4">
        <h2>Search Results</h2>
        <div class="row">
            <?php if (count($results) > 0): ?>
                <?php foreach ($results as $product): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="https://via.placeholder.com/400x300" class="card-img-top" alt="<?php echo htmlspecialchars($product[1]); ?>" />
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product[1]); ?></h5>
                                <p class="card-text">
                                    Brand: <?php echo htmlspecialchars($product[0]); ?><br>
                                    Color: <?php echo htmlspecialchars($product[2]); ?><br>
                                    Size: <?php echo htmlspecialchars($product[3]); ?><br>
                                    Quantity: <?php echo htmlspecialchars($product[4]); ?>
                                </p>
                                <a href="#" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No results found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-4">
        <p>&copy; <?php echo date('Y'); ?> Cairn Clothes. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
