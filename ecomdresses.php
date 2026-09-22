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
    <title>ecomdresses</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
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
    
    <div class="container">
                <div class="fixed-bottom bg-white border-top d-block d-lg-none py-2 px-3 shadow-lg" style="z-index: 1030;">
            <div class="row text-center g-0">
                <div class="col-6 border-end">
                    <button class="btn btn-link text-dark text-decoration-none w-100" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                        aria-controls="offcanvasBottom">
                         <i class="bi bi-sort-down me-2"></i>Sort
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-link text-dark text-decoration-none w-100" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop"
                        aria-controls="staticBackdrop">
                        <i class="bi bi-funnel me-2"></i>Filter
                    </button>
                </div>
            </div>
        </div>
        <div class="row my-0 my-lg-3">
            <div class="col-12 col-lg-3 d-lg-block d-none sticky-top">
                <p class="fs-5">Filters</p>
                <hr>
                <label for="customRange3" class="form-label">Example range</label>
                <input type="range" class="form-range" min="0" max="5" step="0.5" id="customRange3">
                <ul class="list-group">
                    <li class="list-group-item"><i class="bi bi-chevron-left"></i> An item</li>
                    <li class="list-group-item"><i class="bi bi-chevron-left"></i> A second item</li>
                    <li class="list-group-item"><i class="bi bi-chevron-left"></i> A third item</li>
                    <li class="list-group-item"><i class="bi bi-chevron-left"></i> A fourth item</li>
                    <li class="list-group-item">And a fifth one</li>
                </ul>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Brand
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        U.S. POLO ASSN.
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Allen Solly
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        PUMA
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        ADIDAS
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        TOMMY HILFIGER
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Levi's
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Gender
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Men
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Women
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Discount
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        30% or more
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        40% or more
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        50% or more
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        60% or more
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        70% or more
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseThree">
                                Size
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        2XS
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        S
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        M
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        L
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        XL
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        2XL
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        3XL
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Free
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-9">
                
<div class="row row-cols-2 row-cols-lg-3 g-3">
    <?php 
    // Fetch products specifically from tblproduct6 for the dresses page
    $query = "SELECT * FROM tblproduct6";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while($product = mysqli_fetch_array($result)) { 
    ?>
        <div class="col">
            <div class="card custom-card" style="border:none;">
                <!-- Product Image -->
                <img src="images/dresses/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="Product Image">
                
                <div class="card-body">
                    <!-- Brand/Code Name -->
                    <h5 class="card-title"><?php echo htmlspecialchars($product['code']); ?></h5>
                    
                    <!-- Description -->
                    <p class="card-text"><?php echo htmlspecialchars($product['name']); ?></p>
                    
                    <!-- Price -->
                    <p class="fw-bold">₹<?php echo number_format($product['price'], 2); ?></p>
                    
                    <!-- AJAX-enabled Buy Button with table parameter -->
                    <a href="index.php?action=add&pid=<?php echo $product['id']; ?>&table=tblproduct6" 
                       class="btn w-100 hover-btn" 
                       style="background-color: #b39dfa; color: white">
                        Click To Buy
                    </a>
                </div>
            </div>
        </div>
    <?php 
        }
    } else {
        echo "<div class='col-12'><p class='text-center'>No dresses found in the database.</p></div>";
    }
    ?>
</div>
                <div class="row my-3"></div>
                    <div class=" col">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" aria-label="Previous" style="background-color: #b39dfa; color: white">
                                        <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item active" aria-current="page"><a class="page-link" href="#" style="background-color: #b39dfa; color: white">1</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#" style="background-color: #b39dfa; color: white">2</a></li>
                            <li class="page-item"><a class="page-link" href="#" style="background-color: #b39dfa; color: white">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next" style="background-color: #b39dfa; color: white">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Sort Off Canvas -->


    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel">
        <div class="offcanvas-header" style="background-color: #b39dfa; color: white">
            <h5 class="offcanvas-title text-uppercase" id="offcanvasBottomLabel">Sort by</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div style="background-color: #b39dfa; color: white">
            <hr>
        </div>
        <div class="offcanvas-body small" style="background-color: #b39dfa; color: white">
            <div class="form-check">
                <input class="form-check-input float-end" type="radio" name="exampleRadios" id="exampleRadios1"
                    value="option1" checked>
                <label class="form-check-label float-start" for="exampleRadios1">
                    Popularity
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input float-end" type="radio" name="exampleRadios" id="exampleRadios1"
                    value="option1" checked>
                <label class="form-check-label float-start" for="exampleRadios1">
                    Price -- Low to High
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input float-end" type="radio" name="exampleRadios" id="exampleRadios1"
                    value="option1" checked>
                <label class="form-check-label float-start" for="exampleRadios1">
                    Price -- High to Low
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input float-end" type="radio" name="exampleRadios" id="exampleRadios1"
                    value="option1" checked>
                <label class="form-check-label float-start" for="exampleRadios1">
                    Newest First
                </label>
            </div>
        </div>
    </div>
    <!-- Filter Off canvas -->


    <div class="offcanvas offcanvas-start" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop"
        aria-labelledby="staticBackdropLabel">
        <div class="offcanvas-header" style="background-color: #b39dfa; color: white">
            <h5 class="offcanvas-title" id="staticBackdropLabel">Offcanvas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" style="background-color: #b39dfa; color: white">
            <div class="col-12 col-lg-3">
                <p class="fs-5">Filters</p>
                <hr>
                <label for="customRange3" class="form-label">Example range</label>
                <input type="range" class="form-range" min="0" max="5" step="0.5" id="customRange3">
                <ul class="list-group">
                    <li class="list-group-item" style="background-color: #b39dfa; color: white"><i class="bi bi-chevron-left"></i> An item</li>
                    <li class="list-group-item" style="background-color: #b39dfa; color: white"><i class="bi bi-chevron-left"></i> A second item</li>
                    <li class="list-group-item" style="background-color: #b39dfa; color: white"><i class="bi bi-chevron-left"></i> A third item</li>
                    <li class="list-group-item" style="background-color: #b39dfa; color: white"><i class="bi bi-chevron-left"></i> A fourth item</li>
                    <li class="list-group-item" style="background-color: #b39dfa; color: white">And a fifth one</li>
                </ul>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="background-color: #b39dfa; color: white">
                                Brand
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body" style="background-color: #b39dfa; color: white">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        U.S. POLO ASSN.
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Allen Solly
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        PUMA
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        ADIDAS
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        TOMMY HILFIGER
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Levi's
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="background-color: #b39dfa; color: white">
                                Gender
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body" style="background-color: #b39dfa; color: white">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Men
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Women
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="background-color: #b39dfa; color: white">
                                Discount
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body" style="background-color: #b39dfa; color: white">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        30% or more
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        40% or more
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        50% or more
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        60% or more
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        70% or more
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseThree" style="background-color: #b39dfa; color: white">
                                Size
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body" style="background-color: #b39dfa; color: white">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        2XS
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        S
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        M
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        L
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        XL
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        2XL
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        3XL
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Free
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    <?php renderCartContent(); ?>
</div>
<!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
        </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
        </script>

    <script src="assets/vendors/jquery.min.js"></script>
    <script src="assets/owlcarousel/owl.carousel.js"></script>
    <script>
        $(document).ready(function () {
            $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 10,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: true
                    },
                    600: {
                        items: 3,
                        nav: false
                    },
                    1000: {
                        items: 5,
                        nav: true,
                        loop: false,
                        margin: 20
                    }
                }
            })
        })
    </script>
<script>
$(document).ready(function() {
    $(document).on('click', '.hover-btn, .ajax-btn', function(e) {
        e.preventDefault(); // Stop page jumping/reload
        
        var targetUrl = $(this).attr('href'); // Gets the dynamic link

        $.ajax({
            url: targetUrl,
            type: 'GET',
            headers: { "X-Requested-With": "XMLHttpRequest" }, 
            success: function(response) {
                // Only update the cart body[cite: 7]
                $('#offcanvasCart .offcanvas-body').html(response);
                
                // Pop open the bag
                var myOffcanvas = document.getElementById('offcanvasCart');
                var bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(myOffcanvas);
                bsOffcanvas.show();
            }
        });
    });
});
</script>
</body>

</html>