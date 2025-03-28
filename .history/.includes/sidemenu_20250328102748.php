<?php
session_start();
include "init_session.php"; // Memastikan session berjalan dengan benar
$role = $_SESSION['role'] ?? 'user'; // Default role jika tidak ada
?>

<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
      <a href="<?= ($role === 'admin') ? 'dashmind.php' : 'dashboard.php'; ?>" class="app-brand-link">
        <span class="app-brand-text demo menu-text fw-bolder ms-2 text-uppercase">SMart</span>
      </a>
      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
      </a>
    </div>
    <div class="menu-inner-shadow"></div>
    
    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item">
          <a href="<?= ($role === 'admin') ? 'dashmind.php' : 'dashboard.php'; ?>" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Analytics">Dashboard</div>
          </a>
        </li>

        <!-- Menu untuk User -->
        <?php if ($role !== 'admin') : ?>
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Main</span></li>
        <li class="menu-item">
            <a href="pemesanan.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-receipt"></i>
              <div data-i18n="Pemesanan">Pemesanan</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="produk.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-cart-alt"></i>
              <div data-i18n="Produk">Produk</div>
            </a>
        </li>
        <?php endif; ?>

        <!-- Menu untuk Admin -->
        <?php if ($role === 'admin') : ?>
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Admin Panel</span></li>
        <li class="menu-item">
            <a href="users.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-user"></i>
              <div data-i18n="Users Panel">Users Panel</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="create_post.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-pencil"></i>
              <div data-i18n="Create Post">Create Posts (Product)</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="create_category.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-category"></i>
              <div data-i18n="Create Category">Create Category</div>
            </a>
        </li>
        <?php endif; ?>

        <!-- Setting -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Setting</span></li>
        <li class="menu-item">
            <a href="akun.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-user-circle"></i>
              <div data-i18n="Akun">Akun</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="logout.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-power-off"></i>
              <div data-i18n="Logout">Logout</div>
            </a>
        </li>
    </ul>
</aside>
<!-- / Menu -->
