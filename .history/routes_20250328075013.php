<?php
session_start();

// Ambil parameter halaman dari URL
$page = $_GET['page'] ?? 'dashboard';

// Proteksi halaman yang memerlukan login
$protected_pages = ['dashboard', 'edit_post', 'kategori', 'posts', 'proses_kategori', 'proses_post'];
if (in_array($page, $protected_pages) && !isset($_SESSION['user_id'])) {
    $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => 'Silakan login terlebih dahulu!'
    ];
    header('Location: auth/login.php');
    exit();
}

// Routing berdasarkan halaman yang diminta
switch ($page) {
    case 'dashboard':
        require 'dashboard.php';
        break;
    case 'edit_post':
        require 'edit_post.php';
        break;
    case 'kategori':
        require '/kategori.php';
        break;
    case 'posts':
        require 'posts.php';
        break;
    case 'proses_kategori':
        require 'proses_kategori.php';
        break;
    case 'proses_post':
        require 'proses_post.php';
        break;
    case 'login':
        require 'auth/login.php';
        break;
    case '/':
        require 'auth/login.php';
        break;
    case 'register':
        require 'auth/register.php';
        break;
    case 'logout':
        require 'auth/logout.php';
        break;
    default:
        require 'blog/dashboard.php';
        break;
}
?>
