# Full-Stack E-Commerce Web Application

A responsive full-stack e-commerce web platform developed using PHP, MySQL, Bootstrap 5, and JavaScript. The project features category-driven product browsing, an asynchronous AJAX shopping bag, responsive offcanvas filters, and secure session-based authentication.

---

## Key Features

- **Dynamic Multi-Category Catalog:** Dedicated product views (`ecomdresses.php`, `ecomtrousers.php`, `ecomtop.php`, `ecomcoors.php`, and `ecomtreats.php`) dynamically fetching items, images, and pricing from MySQL database tables.
- **Asynchronous AJAX Shopping Bag:** Real-time cart updates (add, remove, and empty operations) handled via jQuery AJAX without triggering page refreshes.
- **Offcanvas Cart Drawer:** Bootstrap 5 sliding cart interface calculating subtotal costs dynamically and managing line items via session storage.
- **Secure Authentication System:** 
  - User registration (`signup.php`) with server-side validation and password hashing via `password_hash()` (`PASSWORD_DEFAULT`).
  - User login (`signin.php`) using prepared statements and `password_verify()` against SQL injection and credential compromise.
  - Session regeneration on login (`session_regenerate_id(true)`) to mitigate session fixation attacks.
- **Protected User Profile:** Dashboard (`dashboard.php`) accessible only to active sessions, with output sanitization (`htmlspecialchars`) to block cross-site scripting (XSS).
- **Responsive Layout:** Mobile-first design using Bootstrap 5 grid, offcanvas menus, sticky navigation, and Owl Carousel product carousels.

---

## Tech Stack

- **Backend:** PHP 8+
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, JavaScript (ES6+), Bootstrap 5.3, Bootstrap Icons, jQuery
- **UI Components:** Owl Carousel 2
- **Build Tooling:** Vite, npm

---

## Project Structure

```text
├── assets/                  # Vendor JavaScript, stylesheets, and Owl Carousel assets
├── images/                  # Category-organized media and product images
│   ├── coors/
│   ├── dresses/
│   ├── treats/
│   └── trousers/
├── dashboard.php            # Authenticated user dashboard
├── dbconnection.sample.php  # Sample database connection configuration
├── ecomcoors.php            # Co-ord sets catalog
├── ecomdresses.php          # Dresses catalog
├── ecomgenzfiles.php        # Trend-focused product showcase
├── ecomtop.php              # Tops catalog
├── ecomtreats.php           # Beauty & makeup catalog
├── ecomtrousers.php         # Trousers catalog
├── index.php                # Main storefront homepage & global cart logic
├── logout.php               # Session termination and redirect
├── package.json             # Frontend dependencies and scripts
├── phpcrud.sql              # Database schema dump and initial catalog data
├── signin.php               # User login interface
├── signup.php               # User registration interface
├── style.css                # Custom UI styles and overrides
└── vite.config.js           # Vite development server and build configuration
```

---

## Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL / MariaDB, PHP 8.0+)
- [Git](https://git-scm.com/)
- [Node.js](https://nodejs.org/) & npm (optional, for frontend bundling)

---

### Installation & Setup

#### 1. Clone the Repository
Clone the project into your local server directory (e.g., `C:/xampp/htdocs/` or `E:/xampp/htdocs/`):

```bash
git clone https://github.com/msmansisharma006-pixel/online-store-portfolio.git
cd online-store-portfolio
```

#### 2. Import the Database Schema
1. Start the **Apache** and **MySQL** services in the XAMPP Control Panel.
2. Open your browser and navigate to `http://localhost/phpmyadmin`.
3. Create a new database named `phpcrud`:
   ```sql
   CREATE DATABASE phpcrud;
   ```
4. Click on the `phpcrud` database, open the **Import** tab, choose the `phpcrud.sql` file from the project root, and click **Import**.

#### 3. Configure Database Credentials
1. Create a copy of `dbconnection.sample.php` in the project root:
   ```bash
   cp dbconnection.sample.php dbconnection.php
   ```
2. Open `dbconnection.php` and verify your local MySQL credentials:
   ```php
   <?php
   $servername = "127.0.0.1";
   $username   = "root";
   $password   = "";
   $dbname     = "phpcrud";

   $conn = new mysqli($servername, $username, $password, $dbname);

   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   ?>
   ```
   *(Note: `dbconnection.php` is tracked in `.gitignore` to prevent committing local environment credentials)*.

#### 4. Run the Project
Open your browser and visit:
```text
http://localhost/online-store-portfolio/index.php
```
*(If your folder inside `htdocs` is named `flip`, visit `http://localhost/flip/index.php`)*

---

## Database Architecture Overview

The database contains organized tables for product categorization and user profiles:
- `tblusers` — Stores registered customer accounts with hashed credentials and registration metadata.
- `tblproduct` — Core storefront catalog items.
- `tblproduct2` through `tblproduct7` — Categorical product tables managing product codes, descriptive titles, pricing, and category image associations (`trousers`, `dresses`, `coors`, `treats`, etc.).