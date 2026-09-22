<?php
session_start();
require_once("dbconnection.php");

if (!empty($_GET["action"])) {
    switch ($_GET["action"]) {
        case "add":
            $pid = $_GET['pid'];
            $tableName = isset($_GET['table']) ? $_GET['table'] : 'tblproduct';
            
            // Define folder paths based on table name
            $folder = 'images/'; // Default
            if ($tableName == 'tblproduct3') { $folder = 'images/trousers/'; }
            if ($tableName == 'tblproduct6') { $folder = 'images/dresses/'; }
            if ($tableName == 'tblproduct7') { $folder = 'images/coors/'; }
            if ($tableName == 'tblproduct4') { $folder = 'images/treats/'; }

            $query = "SELECT * FROM $tableName WHERE id ='$pid'";
            $result = mysqli_query($conn, $query);
            
            while ($productByCode = mysqli_fetch_array($result)) {
                $itemArray = array($productByCode["code"] => array(
                    'name' => $productByCode["name"], 
                    'code' => $productByCode["code"], 
                    "quantity" => 1, 
                    'price' => $productByCode["price"], 
                    'image' => $productByCode["image"],
                    'folder' => $folder // Store folder path in session[cite: 9, 10]
                ));
                
                if (!empty($_SESSION["cart_item"])) {
                    if (in_array($productByCode["code"], array_keys($_SESSION["cart_item"]))) {
                        foreach ($_SESSION["cart_item"] as $k => $v) {
                            if ($productByCode["code"] == $k) {
                                $_SESSION["cart_item"][$k]["quantity"] += 1;
                            }
                        }
                    } else {
                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
                    }
                } else {
                    $_SESSION["cart_item"] = $itemArray;
                }
            }
            break;
        case "remove":
            if (isset($_GET["code"])) {
                unset($_SESSION["cart_item"][$_GET["code"]]);
                if (empty($_SESSION["cart_item"])) { unset($_SESSION["cart_item"]); }
            }
            break;
        case "empty":
            unset($_SESSION["cart_item"]);
            break;
    }

    // NEW: If AJAX, return ONLY the cart content and exit
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        renderCartContent(); 
        exit; 
    }
}

// NEW: Helper function to keep HTML consistent
function renderCartContent() {
    if (isset($_SESSION["cart_item"])) {
        $total_price = 0;
        echo '<div class="cart-items">';
        foreach ($_SESSION["cart_item"] as $item) {
            $item_price = $item["quantity"] * $item["price"];
            // Use $item["folder"] to fix broken images[cite: 9, 10]
            echo '<div class="d-flex align-items-center mb-3 border-bottom pb-2">
                    <img src="'.$item["folder"].htmlspecialchars($item["image"]).'" style="width: 60px; height: 60px; object-fit: cover;" class="me-3">
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">'.htmlspecialchars($item["name"]).'</h6>
                        <small class="text-muted">Qty: '.$item["quantity"].' x ₹'.number_format($item["price"], 2).'</small>
                        <div class="fw-semibold">₹'.number_format($item_price, 2).'</div>
                    </div>
                    <!-- Relative href link fixes the Remove button[cite: 9, 10] -->
                    <a href="?action=remove&code='.$item["code"].'" class="text-danger ms-2 ajax-btn"><i class="bi bi-trash"></i></a>
                  </div>';
            $total_price += $item_price;
        }
        echo '</div><div class="mt-4"><div class="d-flex justify-content-between fw-bold mb-3"><span>Total:</span><span>₹'.number_format($total_price, 2).'</span></div>
              <a href="?action=empty" class="btn btn-outline-danger w-100 mb-2 ajax-btn">Empty Cart</a>
              <button class="btn w-100" style="background-color: #b39dfa; color: white;">Checkout</button></div>';
    } else {
        echo '<div class="text-center py-5"><i class="bi bi-bag-x" style="font-size: 3rem; color: #ccc;"></i><p class="mt-3">Your cart is empty.</p></div>';
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>ecomhome</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- Stylesheets -->
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,400italic,300italic' rel='stylesheet'
        type='text/css'>
    <!-- <link rel="stylesheet" href="assets/css/docs.theme.min.css"> -->
    <link rel="stylesheet" href="style.css">
    <!-- Owl Stylesheets -->
    <link rel="stylesheet" href="assets/owlcarousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/owlcarousel/assets/owl.theme.default.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark d-none d-md-block sticky-top" style="background-color: #b39dfa;">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="flipkart-plus_8d85f4.png" alt="" class="d-block w-50"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li>
                        <input class="form-control ms-auto me-5" type="search" placeholder="Search" aria-label="Search"
                            style="width: 600px;">
                    </li>
                   <li><a href="signin.php" class="btn bg-light">Login</a></li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#offcanvasCart" data-bs-toggle="offcanvas" role="button" aria-controls="offcanvasCart">
                            <i class="bi bi-bag-heart-fill"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            More
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" style="background-color: #a589ffde;">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
    <nav class="navbar navbar-expand-md navbar-light bg-light sticky-top" style="flex-wrap: inherit;">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" 
            data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
            <nav class="navbar bg-body-tertiary">
            <div class="container-fluid d-block d-md-none">
                <nav class="navbar bg-body-tertiary">
                    <div class="container-fluid">
                        <form class="d-flex align-items-center w-100" role="search">
                        
                        <div class="flex-grow-1 me-2">
                            <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                        </div>
                        <a href="signin.php" class="btn bg-light">Login</a>
                        <a class="nav-link fs-4" href="#offcanvasCart" data-bs-toggle="offcanvas" role="button" aria-controls="offcanvasCart">
                            <i class="bi bi-bag-heart-fill"></i>
                        </a>

                        </form>
                    </div>
                </nav>
            </div>
            </nav>
        <div class="offcanvas offcanvas-start offcanvas-md" data-bs-backdrop="static" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasNavbar" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item dropdown">
                        <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Fashion Clothing
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" style="background-color: #a589ffde;">
                            <li><a class="dropdown-item" href="ecomdresses.php">Dresses</a></li>
                            <li><a class="dropdown-item" href="ecomtrousers.php">Trousers</a></li>
                            <li><a class="dropdown-item" href="ecomtop.php">Tops</a></li>
                            <li><a class="dropdown-item" href="ecomcoors.php">Coors</a></li>
                            <li><a class="dropdown-item" href="#">Skirts</a></li>
                            <li><a class="dropdown-item" href="#">Hoodies & Sets</a></li>
                            <li><a class="dropdown-item" href="#">Jumpsuits</a></li>
                            <li><a class="dropdown-item" href="#">New Arrivals</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Makeup
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" style="background-color: #a589ffde;">
                            <li><a class="dropdown-item" href="#">Face</a></li>
                            <li><a class="dropdown-item" href="#">Eyes</a></li>
                            <li><a class="dropdown-item" href="#">Lips</a></li>
                            <li><a class="dropdown-item" href="#">Nails</a></li>
                            <li><a class="dropdown-item" href="#">Tools & Brushes</a></li>
                            <li><a class="dropdown-item" href="#">Makeup Kits & Combos</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Skin
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" style="background-color: #a589ffde;">
                            <li><a class="dropdown-item" href="#">Facewash</a></li>
                            <li><a class="dropdown-item" href="#">Toner</a></li>
                            <li><a class="dropdown-item" href="#">Serum</a></li>
                            <li><a class="dropdown-item" href="#">Moisturizer</a></li>
                            <li><a class="dropdown-item" href="#">Sunscreen</a></li>
                            <li><a class="dropdown-item" href="#">Eye Cream</a></li>
                            <li><a class="dropdown-item" href="#">Masks</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Hair
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" style="background-color: #a589ffde;">
                            <li><a class="dropdown-item" href="#">Shampoo</a></li>
                            <li><a class="dropdown-item" href="#">Conditioner</a></li>
                            <li><a class="dropdown-item" href="#">Hair Oil</a></li>
                            <li><a class="dropdown-item" href="#">Hair Mask</a></li>
                            <li><a class="dropdown-item" href="#">Hair Serum</a></li>
                            <li><a class="dropdown-item" href="#">Hair Supplements</a></li>
                            <li><a class="dropdown-item" href="#">Dry Shampoo</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Appliances
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" style="background-color: #a589ffde;">
                            <li><a class="dropdown-item" href="#">Hair Styling Tool</a></li>
                            <li><a class="dropdown-item" href="#">Pro Hair Styling</a></li>
                            <li><a class="dropdown-item" href="#">Hair Removal Tools</a></li>
                            <li><a class="dropdown-item" href="#">Shaving Tools</a></li>
                            <li><a class="dropdown-item" href="#">Oral Care</a></li>
                            <li><a class="dropdown-item" href="#">Face\Skin Tools</a></li>
                            <li><a class="dropdown-item" href="#">Massage Tools</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Personal Care
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" style="background-color: #a589ffde;">
                            <li><a class="dropdown-item" href="#">Menstural Care</a></li>
                            <li><a class="dropdown-item" href="#">Intimate Hygiene</a></li>
                            <li><a class="dropdown-item" href="#">Health Supplements</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Bath & Body
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" style="background-color: #a589ffde;">
                            <li><a class="dropdown-item" href="#">Bath Salts</a></li>
                            <li><a class="dropdown-item" href="#">Body Wash</a></li>
                            <li><a class="dropdown-item" href="#">Body Lotion</a></li>
                            <li><a class="dropdown-item" href="#">Body Scrub</a></li>
                            <li><a class="dropdown-item" href="#">Deodorant</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Lingerie & Sleepwear
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" style="background-color: #a589ffde;">
                            <li><a class="dropdown-item" href="#">Bra</a></li>
                            <li><a class="dropdown-item" href="#">Underwear</a></li>
                            <li><a class="dropdown-item" href="#">Sleep & Lounge</a></li>
                            <li><a class="dropdown-item" href="#">Shapewear</a></li>
                            <li><a class="dropdown-item" href="#">Swimwear</a></li>
                            <li><a class="dropdown-item" href="#">Activewear</a></li>
                            <li><a class="dropdown-item" href="#">Maternity Wear</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Fragrance</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Offer Zone</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Accessories</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
    <div class="container d-none d-md-block">
        <div class="row">
            <div class="col-12">
                <a href="ecomgenzfiles.php"><img src="images/theme-image-1751877585519.jpeg" class="w-100 d-block" alt=""></a>
            </div>
        </div>
    </div>
    <div class="container-fluid p-0 d-block d-md-none">
        <div class="row g-0">
            <div class="col-12" style="position: relative; background: #000;">
                <div id="videoLoader" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white;">
                    Loading...
                </div>
                <a href="ecomlaneige.html">
                <video 
                    id="bannerVideo"
                    class="w-100 d-block" 
                    autoplay 
                    muted 
                    loop 
                    playsinline 
                    style="pointer-events: none; opacity: 0; transition: opacity 0.5s ease-in-out;">
                    <source src="images/MasterheadBanner_App_17th_Jan.mp4" type="video/mp4">
                </video>
                </a>
            </div>
        </div>
    </div>
    <div class="container my-4">
    <div class="owl-carousel owl-theme ">
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/1.jpeg" alt="Brand 1"></a>
                <div class="mt-2 text-left fw-medium">Makeup</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/2.jpeg" alt="Brand 2"></a>
                <div class="mt-2 text-left fw-medium">Skincare</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/3.jpeg" alt="Brand 3"></a>
                <div class="mt-2 text-left fw-medium">Hair</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/4.jpeg" alt="Brand 4"></a>
                <div class="mt-2 text-left fw-medium">Fragrance</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/5.jpeg" alt="Brand 5"></a>
                <div class="mt-2 text-left fw-medium">Bath & Body</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/6.jpeg" alt="Brand 1"></a>
                <div class="mt-2 text-left fw-medium">Men</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/7.jpeg" alt="Brand 2"></a>
                <div class="mt-2 text-left fw-medium">Wellbeing</div>
            </div>
        </div>
    </div>
</div>
    <div class="container d-none d-md-block">
        <div class="row my-3">
            <div class="col-12">
                <a href="http://"><img src="images/Fenty-beauty-carousel-desk-1767616625418.jpeg" class="w-100 " alt=""></a>
            </div>
        </div>
    </div>
    <div class="container-fluid P-0 d-block d-md-none">
        <div class="row">
            <div class="col-12">
                <a href="ecomgenzfiles.php"><img src="images/GENZ.JPG" class="w-100 d-block" alt=""></a>
            </div>
        </div>
    </div>
    <div class="container my-3 d-none d-lg-block">
        <div class="row row-cols-1 row-cols-lg-3 g-3">
            <div class="col">
                <div class="card" style="border:none;">
                    <a href="http://"><img src="images/sheglam.jpeg" class="card-img-top" alt="..."></a>
                        <div class="card-body" style="background-color: #f8efe9;">
                            <h5 class="card-title">Sheglam</h5>
                            <p class="card-text">Trend-forward makeup, straight from your feed to your cart</p>
                        </div>
                </div>
            </div>
            <div class="col">
                <div class="card" style="border:none;">
                    <a href="http://"><img src="images/boj.jpeg" class="card-img-top" alt="..."></a>
                        <div class="card-body" style="background-color: #f8efe9;">
                            <h5 class="card-title">Beauty of Joseon</h5>
                            <p class="card-text">Glow-boosting formulas rooted in traditional Korean skincare</p>
                        </div>
                </div>
            </div>
            <div class="col">
                <div class="card" style="border:none;">
                    <a href="http://"><img src="images/tirtir.jpeg" class="card-img-top" alt="..."></a>
                        <div class="card-body" style="background-color: #f8efe9;">
                            <h5 class="card-title">Tirtir</h5>
                            <p class="card-text">The formula that broke the internet—glass skin in just one swipe</p>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Hot Right Now</h4>
        <a href="#" class="text-dark text-decoration-none fw-semibold">View All</a>
    </div>
    
    <div class="owl-carousel owl-theme ">
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Warm-Woody-Fragrances-.jpeg"  alt="Brand 1"></a>
                <div class="card-body py-3 px-0">
                    <h5 class="card-title" style="margin: 0px 0px 0px 0px">Warm earthy fragrances</h5>
                    <p class="card-text">An edit of warm, woody notes</p>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Bitten-Berry-Lips.jpeg" alt="Brand 2"></a>
                <div class="card-body py-3 px-0">
                    <h5 class="card-title" style="margin: 0px 0px 0px 0px">Just-bitten Lips</h5>
                    <p class="card-text">Lightweight tints for a fresh, berry-stained lip</p>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Gourmands-Fragrances-.jpeg" alt="Brand 3"></a>
                <div class="card-body py-3 px-0">
                    <h5 class="card-title" style="margin: 0px 0px 0px 0px">Gourmands Fragrances</h5>
                    <p class="card-text">Scents that feel like a sweet treat</p>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Scalp-care-D-1762860024608.jpeg" alt="Brand 4"></a>
                <div class="card-body py-3 px-0">
                    <h5 class="card-title" style="margin: 0px 0px 0px 0px">Scalp Revival</h5>
                    <p class="card-text">Your winter edit for a healthy, nourished scalp</p>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Mocha-Mousse-.jpeg" alt="Brand 5"></a>
                <div class="card-body py-3 px-0">
                    <h5 class="card-title" style="margin: 0px 0px 0px 0px">Mocha Mousse</h5>
                    <p class="card-text">Soft, velvety glow with mocha warmth</p>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Cloud-Skincare-.jpeg" alt="Brand 5"></a>
                <div class="card-body py-3 px-0">
                    <h5 class="card-title" style="margin: 0px 0px 0px 0px">Cloud Skin</h5>
                    <p class="card-text">Airy whipped textures for soft, matte-glow skin</p>
                </div>
            </div>
        </div>
    </div>
</div>
   <div class="container d-none d-md-block">
    <div class="row my-3">
        <div class="col-12">
            <a href="http://"><img src="images/tt.JPG" class="w-100" alt="Description"></a>
        </div>
    </div>
</div>
    <div class="container d-block d-md-none">
        <div class="row my-3">
            <div class="col-12 px-0">
                <a href="http://"><img src="images/tt-mobile.jpeg" class="w-100 d-block" alt=""></a>
            </div>
        </div>
    </div>
    
    <div class="container my-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Flash Deals</h4>
        <a href="#" class="text-dark text-decoration-none fw-semibold">View All</a>
    </div>
    
    <div class="owl-carousel owl-theme ">
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Blush-.jpeg" alt="Brand 1"></a>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/foundation-.jpeg" alt="Brand 2"></a>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/hair-serum-.jpeg" alt="Brand 3"></a>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/lipstick-.jpeg" alt="Brand 4"></a>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/masks-.jpeg" alt="Brand 5"></a>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/perfume-.jpeg" alt="Brand 1"></a>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/toner-.jpeg" alt="Brand 2"></a>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/serum-.jpeg" alt="Brand 3"></a>
            </div>
        </div>
    </div>
</div>
<div class="container d-none d-md-block">
    <div class="row my-3">
        <div class="col-12">
            <a href="http://"><img src="images/tt3.jpeg" class="w-100 " alt=""></a>
        </div>
    </div>
</div>
<div class="container d-block d-md-none">
    <div class="row my-3">
        <div class="col-12 px-0">
            <a href="http://"><img src="images/Kiko-Strip-banner-.jpeg" class="w-100 d-block" alt=""></a>
        </div>
    </div>
 </div>
<div class="container my-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Your Favourite Brands</h4>
        <a href="#" class="text-dark text-decoration-none fw-semibold">View All</a>
    </div>
    
    <div class="owl-carousel owl-theme ">
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/owl1.jpeg" alt="Brand 1"></a>
                <div class="mt-2 text-left fw-medium">Amouage</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/owl2.jpeg" alt="Brand 2"></a>
                <div class="mt-2 text-left fw-medium">Fenty Beauty</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/owl3.jpeg" alt="Brand 3"></a>
                <div class="mt-2 text-left fw-medium">Prada</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/owl4.jpeg" alt="Brand 4"></a>
                <div class="mt-2 text-left fw-medium">Sol De Janeiro</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/owl5.jpeg" alt="Brand 5"></a>
                <div class="mt-2 text-left fw-medium">Chantecaille</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/owl7.jpeg" alt="Brand 6"></a>
                <div class="mt-2 text-left fw-medium">Round Lab</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/owl8.jpeg" alt="Brand 7"></a>
                <div class="mt-2 text-left fw-medium">Pyungkang Yul</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/owl9.jpeg" alt="Brand 8"></a>
                <div class="mt-2 text-left fw-medium">Keune</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/owl10.jpeg" alt="Brand 9"></a>
                <div class="mt-2 text-left fw-medium">Chloe</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/owl11.jpeg" alt="Brand 10"></a>
                <div class="mt-2 text-left fw-medium">House Of Hur</div>
            </div>
        </div>
    </div>
</div>
<div class="container d-none d-md-block">
    <div class="row my-3">
        <div class="col-12">
            <a href="http://"><img src="images/Sheglam-top-banner-Desktop-.jpeg" class="w-100 " alt=""></a>
        </div>
    </div>
</div>
<div class="container d-block d-md-none">
    <div class="row my-3">
        <div class="col-12 px-0">
            <a href="http://"><img src="images/Sheglam-Mobile-.jpeg" class="w-100 d-block" alt=""></a>
        </div>
    </div>
 </div>

<div class="container my-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Top Picks For You</h4>
        <a href="#" class="text-dark text-decoration-none fw-semibold">View All</a>
    </div>
    
    <div class="owl-carousel owl-theme">
        <?php 
        // Use the $conn variable from dbconnection.php[cite: 1]
        $query = "SELECT * FROM tblproduct";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while($product = mysqli_fetch_array($result)) { 
        ?>
            <div class="item">
                <div class="card custom-card" style="border:none;">
                    <!-- 'image' column from your table[cite: 2] -->
                    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="Product Image">
                    
                    <div class="card-body">
                        <!-- 'code' column contains the brand name[cite: 2] -->
                        <h5 class="card-title"><?php echo htmlspecialchars($product['code']); ?></h5>
                        
                        <!-- 'name' column contains the description[cite: 2] -->
                        <p class="card-text"><?php echo htmlspecialchars($product['name']); ?></p>
                        
                        <p class="fw-bold">₹<?php echo number_format($product['price'], 2); ?></p>
                        
                        <!-- Links to your existing cart 'add' action[cite: 2] -->
                        <a href="index.php?action=add&pid=<?php echo $product['id']; ?>&table=tblproduct" class="btn w-100 hover-btn" style="background-color: #b39dfa; color: white;">Click To Buy</a>
                    </div>
                </div>
            </div>
        <?php 
            }
        } else {
            echo "<p class='text-center'>No products found.</p>";
        }
        ?>
    </div>
</div>
<div class="container my-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Best In Makeup</h4>
        <a href="#" class="text-dark text-decoration-none fw-semibold">View All</a>
    </div>
    
    <div class="owl-carousel owl-theme ">
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/top sellers.jpeg" alt="Brand 1"></a>
                <div class="mt-2 text-left fw-medium">Up to 50% off</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/lakeme.avif" alt="Brand 2"></a>
                <div class="mt-2 text-left fw-medium">Up to 60% off + gift over ₹999</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/mac.avif" alt="Brand 3"></a>
                <div class="mt-2 text-left fw-medium">Up to 25% off</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/sheglamoffer.avif" alt="Brand 4"></a>
                <div class="mt-2 text-left fw-medium">Flat 10% off + buy 2, get 1 free</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/toofaced.avif" alt="Brand 5"></a>
                <div class="mt-2 text-left fw-medium">Up to 25% off</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/benefit.avif" alt="Brand 6"></a>
                <div class="mt-2 text-left fw-medium">Up to 50% off + gift over ₹3200</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/etude.jpeg" alt="Brand 7"></a>
                <div class="mt-2 text-left fw-medium">Up to 40% off + buy 1, get 1 on select picks</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/hince.avif" alt="Brand 8"></a>
                <div class="mt-2 text-left fw-medium">Flat 15% off</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/bobbibrown.avif" alt="Brand 9"></a>
                <div class="mt-2 text-left fw-medium">Up to 25% off</div>
            </div>
        </div>
    </div>
</div>
<div class="container d-none d-md-block">
    <div class="row my-3">
        <div class="col-12">
            <a href="http://"><img src="images/Akind-CLP-Top-Carousel-Desktop.jpeg" class="w-100" alt="Description"></a>
        </div>
    </div>
</div>
    <div class="container d-block d-md-none">
        <div class="row my-3">
            <div class="col-12">
                <a href="http://"><img src="images/Akind-CLP-Top-Carousel-Mobile.jpeg" class="w-100 d-block" alt=""></a>
            </div>
        </div>
    </div>
<div class="container my-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Best In Skin</h4>
        <a href="#" class="text-dark text-decoration-none fw-semibold">View All</a>
    </div>
    
    <div class="owl-carousel owl-theme ">
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/top sellers.jpeg" alt="Brand"></a>
                <div class="mt-2 text-left fw-medium">Up to 50% off</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Minimalis.jpeg" alt="Brand 1"></a>
                <div class="mt-2 text-left fw-medium">Up to 20% off + free gifts</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Cetaphil.jpeg" alt="Brand 2"></a>
                <div class="mt-2 text-left fw-medium">Up to 20% off + gift over ₹699</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Caudalie.jpeg" alt="Brand 3"></a>
                <div class="mt-2 text-left fw-medium">Up to 20% off</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/ClayCo..jpeg" alt="Brand 4"></a>
                <div class="mt-2 text-left fw-medium">Up to 20% off + gift over ₹1299</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Innisfree.jpeg" alt="Brand 5"></a>
                <div class="mt-2 text-left fw-medium">Up to 30% off + gifts over ₹999</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Loccitane.jpeg" alt="Brand 6"></a>
                <div class="mt-2 text-left fw-medium">Free gift on all orders</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Embryolisse.jpeg" alt="Brand 7"></a>
                <div class="mt-2 text-left fw-medium">Flat 10% off</div>
            </div>
        </div>
    </div>
</div>

<div class="container my-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Top Picks For You</h4>
        <a href="#" class="text-dark text-decoration-none fw-semibold">View All</a>
    </div>
    
    <div class="owl-carousel owl-theme">
        <?php 
        // Use the $conn variable from dbconnection.php[cite: 1]
        $query = "SELECT * FROM tblproduct2";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while($product = mysqli_fetch_array($result)) { 
        ?>
            <div class="item">
                <div class="card custom-card" style="border:none;">
                    <!-- 'image' column from your table[cite: 2] -->
                    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="Product Image">
                    
                    <div class="card-body">
                        <!-- 'code' column contains the brand name[cite: 2] -->
                        <h5 class="card-title"><?php echo htmlspecialchars($product['code']); ?></h5>
                        
                        <!-- 'name' column contains the description[cite: 2] -->
                        <p class="card-text"><?php echo htmlspecialchars($product['name']); ?></p>
                        
                        <p class="fw-bold">₹<?php echo number_format($product['price'], 2); ?></p>
                        
                        <!-- Links to your existing cart 'add' action[cite: 2] -->
                        <a href="index.php?action=add&pid=<?php echo $product['id']; ?>&table=tblproduct2" class="btn w-100 hover-btn" style="background-color: #b39dfa; color: white;">Click To Buy</a>
                    </div>
                </div>
            </div>
        <?php 
            }
        } else {
            echo "<p class='text-center'>No products found.</p>";
        }
        ?>
    </div>
</div>

<div class="container my-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Trending Categories</h4>
        <a href="#" class="text-dark text-decoration-none fw-semibold">View All</a>
    </div>
    
    <div class="owl-carousel owl-theme ">
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/K-beauty.jpeg" alt="Brand"></a>
                <div class="mt-2 text-left fw-medium">K-Beauty</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Gen-z.jpeg" alt="Brand 1"></a>
                <div class="mt-2 text-left fw-medium">Gen Z</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Wedding.jpeg" alt="Brand 2"></a>
                <div class="mt-2 text-left fw-medium">Wedding</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Homegrown.jpeg" alt="Brand 3"></a>
                <div class="mt-2 text-left fw-medium">Homegrown</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Derm.jpeg" alt="Brand 4"></a>
                <div class="mt-2 text-left fw-medium">Derm 101</div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0">
                <a href="http://">
                <img src="images/Festive-faves.jpeg" alt="Brand 5"></a>
                <div class="mt-2 text-left fw-medium">Festive Faves</div>
            </div>
        </div>
    </div>
</div>
<div class="container d-none d-md-block">
    <div class="row my-3">
        <div class="col-12">
            <a href="http://"><img src="images/Beauty-advisor-banner-strip-banner-Desktop-.jpeg" class="w-100 " alt=""></a>
        </div>
    </div>
</div>
    <div class="container d-block d-md-none">
        <div class="row my-3">
            <div class="col-12">
                <a href="http://"><img src="images/Beauty-advisor-banner-strip-banner-Mobile-.JPG" class="w-100 d-block" alt=""></a>
            </div>
        </div>
    </div>
    <div class="container-fluid d-none d-md-block">
        <div class="container" style="background-color: #f8efe9;">
            <footer class="row py-5 px-5 my-5">

                <div class="col">
                    <div class="highlight-body" style="background-color: #f8efe9;">
                        <div><img src="images/a.avif" alt="" width="32" height="32"></div>
                        <h5 class="highlight-title mt-5">100% Authentic</h5>
                        <p class="highlight-text">All our products are directly sourced from brands</p>
                    </div>
                </div>

                <div class="col">
                    <div class="highlight-body" style="background-color: #f8efe9;">
                        <div><img src="images/truck.avif" alt="" width="32" height="32"></div>
                        <h5 class="highlight-title mt-5">Free Shipping</h5>
                        <p class="highlight-text">On all orders above ₹299</p>
                    </div>
                </div>

                <div class="col">
                    <div class="highlight-body" style="background-color: #f8efe9;">
                        <div><img src="images/advice.avif" alt="" width="32" height="32"></div>
                        <h5 class="highlight-title mt-5">Certified Beauty Advisors</h5>
                        <p class="highlight-text">Get expert consultations</p>
                    </div>
                </div>

                <div class="col">
                    <div class="highlight-body" style="background-color: #f8efe9;">
                        <div><img src="images/return.avif" alt="" width="32" height="32"></div>
                        <h5 class="highlight-title mt-5">Easy Returns</h5>
                        <p class="highlight-text">Hassle-free pick-ups and refunds</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <div class="container-fluid d-block d-md-none" style="background-color: #f8efe9;">
        <footer class="row flex-column py-4 px-4">

            <div class="col">
                <div class="d-flex align-items-left">
                    <div class="me-3">
                        <img src="images/a.avif" alt="" width="32" height="32">
                    </div>
                    <div>
                        <h5 class="highlight-title" style="font-size: 1.1rem; color: #333;">100% Authentic</h5>
                        <p class="highlight-text" style="font-size: 0.9rem;">All our products are directly sourced from brands</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-left">
                    <div class="me-3">
                        <img src="images/truck.avif" alt="" width="32" height="32">
                    </div>
                    <div>
                        <h5 class="highlight-title" style="font-size: 1.1rem; color: #333;">Free Shipping</h5>
                        <p class="highlight-text" style="font-size: 0.9rem;">On all orders above ₹299</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-left">
                    <div class="me-3">
                        <img src="images/advice.avif" alt="" width="32" height="32">
                    </div>
                    <div>
                        <h5 class="highlight-title" style="font-size: 1.1rem; color: #333;">Certified Beauty Advisors</h5>
                        <p class="highlight-text" style="font-size: 0.9rem;">Get expert consultations</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-left">
                    <div class="me-3">
                        <img src="images/return.avif" alt="" width="32" height="32">
                    </div>
                    <div>
                        <h5 class="highlight-title" style="font-size: 1.1rem; color: #333;">Easy Returns</h5>
                        <p class="highlight-text" style="font-size: 0.9rem;">Hassle-free pick-ups and refunds</p>
                    </div>
                </div>
            </div>

        </footer>
</div>
    
    
    <div class="container-fluid border-top">
    <div class="container">
        <footer class="row row-cols-1 row-cols-md-5 py-3">
            <div class="col mb-3">
                <a href="/" class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none">
                    <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                </a>
                <p class="text-body-secondary">© 2026</p>
            </div>

            <div class="col mb-3 d-none d-md-block"></div>

            <div class="col mb-3 px-2 accordion-footer">
                <h5 class="d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#aboutUs">
                    About Us
                    <span class="d-md-none toggle-icon"></span>
                </h5>
                <div id="aboutUs" class="collapse d-md-block">
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Who We Are</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">For You</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Offers</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Tribe</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Site Map</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Testimonials</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Help</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">blog</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Categories</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Sell On</a></li>
                    </ul>
                </div>
            </div>

            <div class="col mb-3 px-2 accordion-footer">
                <h5 class="d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#customerCare">
                    Customer Care
                    <span class="d-md-none toggle-icon"></span>
                </h5>
                <div id="customerCare" class="collapse d-md-block">
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Help Center</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">FAQs</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Buying Guides</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Beauty Advisor</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Locate Us</a></li>
                    </ul>
                </div>
            </div>

            <div class="col mb-3 px-2 accordion-footer">
                <h5 class="d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#categories">
                    Categories
                    <span class="d-md-none toggle-icon"></span>
                </h5>
                <div id="categories" class="collapse d-md-block">
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Makeup</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Skin</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Hair</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Fragrance</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Men</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Bath & Body</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Tools & Appliances</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Mom & Baby</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Minis</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Homegrown</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Wellness</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Gifts</a></li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- offcanvas cart -->

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCartLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasCartLabel">Your Shopping Bag</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
    <!-- React Widget Container -->
        <div id="react-cart-root"></div>
    </div>
</div>
<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>

<script src="assets/vendors/jquery.min.js"></script>
<script src="assets/owlcarousel/owl.carousel.js"></script>

<script>
$(document).ready(function(){
    // Single, clean Owl Carousel initialization
    $(".owl-carousel").owlCarousel({
        loop: true,
        margin: 20,
        nav: true,
        dots: false,
        autoplay: false,
        navText: ["<i class='bi bi-chevron-left'></i>","<i class='bi bi-chevron-right'></i>"],
        responsive:{
            0:{ items: 1.2 },
            600:{ items: 2.5 },
            1000:{ items: 4 }
        }
    });

    // AJAX Cart Handler
    $(document).on('click', '.hover-btn, .ajax-btn', function(e) {
        e.preventDefault();
        var targetUrl = $(this).attr('href');

        $.ajax({
            url: targetUrl,
            type: 'GET',
            headers: { "X-Requested-With": "XMLHttpRequest" }, 
            success: function(response) {
                $('#offcanvasCart .offcanvas-body').html(response);
                var myOffcanvas = document.getElementById('offcanvasCart');
                var bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(myOffcanvas);
                bsOffcanvas.show();
            }
        });
    });

    // Banner Video Auto-Play
    const video = document.getElementById('bannerVideo');
    const loader = document.getElementById('videoLoader');
    if (video) {
        video.play().catch(() => {});
        video.addEventListener('canplaythrough', () => {
            video.style.opacity = '1';
            if (loader) loader.style.display = 'none';
        });
        video.addEventListener('ended', () => video.play());
    }

    // Text Truncation
    const limit = 55;
    document.querySelectorAll('.card-text').forEach(textElement => {
        const originalText = textElement.textContent.trim();
        if (originalText.length > limit) {
            textElement.textContent = originalText.slice(0, limit) + "...";
        }
    });
});
</script>

<!-- Live Support Chat Widget (Node.js + Socket.io) -->
<div id="chat-widget" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
    <button id="toggle-chat" class="btn btn-primary rounded-circle p-3 shadow" style="background-color: #b39dfa; border: none;">
        <i class="bi bi-chat-dots-fill fs-4"></i>
    </button>
    
    <div id="chat-box" class="card shadow d-none mt-2" style="width: 300px; position: absolute; bottom: 60px; right: 0;">
        <div class="card-header text-white" style="background-color: #b39dfa;">
            <h6 class="mb-0 text-center">Chat With Us</h6>
        </div>
        <div class="card-body overflow-auto" id="chat-messages" style="height: 200px; font-size: 0.9rem;">
            <p class="text-muted">Welcome! How can we help you today?</p>
        </div>
        <div class="card-footer p-2">
            <div class="input-group">
                <input type="text" id="chat-input" class="form-control form-control-sm" placeholder="Type a message...">
                <button id="send-chat" class="btn btn-sm text-white" style="background-color: #b39dfa;">Send</button>
            </div>
        </div>
    </div>
</div>

<!-- Socket.io Client Library -->
<script src="http://localhost:5000/socket.io/socket.io.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const socket = io("http://localhost:5000");

    const chatBox = document.getElementById("chat-box");
    const toggleBtn = document.getElementById("toggle-chat");
    const sendBtn = document.getElementById("send-chat");
    const input = document.getElementById("chat-input");
    const messages = document.getElementById("chat-messages");

    if (toggleBtn && chatBox) {
        toggleBtn.addEventListener("click", () => {
            chatBox.classList.toggle("d-none");
        });
    }

    if (sendBtn) {
        sendBtn.addEventListener("click", () => {
            const text = input.value.trim();
            if (text) {
                socket.emit("send_message", { text: text, time: new Date().toLocaleTimeString() });
                input.value = "";
            }
        });
    }

    socket.on("receive_message", (data) => {
        const msgDiv = document.createElement("div");
        msgDiv.className = "mb-2";
        msgDiv.innerHTML = `<strong>User:</strong> ${data.text} <br><small class="text-muted" style="font-size:0.75rem">${data.time}</small>`;
        messages.appendChild(msgDiv);
        messages.scrollTop = messages.scrollHeight;
    });
});
</script>
</body>
</html>