<?php
/**
 * ================================================================
 * VELORA Marketplace — index.php  (Main Entry Point / Orchestrator)
 * ================================================================
 * File ini hanya berisi:
 *   1. Konfigurasi session / halaman
 *   2. Pemanggilan (require) setiap bagian dari masing-masing anggota
 *
 * PEMBAGIAN TUGAS:
 *   main/section1.php → Tom     (Navbar · Hero · Stats · Categories · Featured · POTW)
 *   main/section2.php → Bagus   (Journal · Team · About/Footer)
 *   auth/signIn.php             → Felysia (Halaman Login)
 *   auth/signUp.php             → Felysia (Halaman Register)
 *   cart/shoppingCart.php       → Jodi    (Keranjang Belanja)
 *   cart/checkOut.php           → Jodi    (Checkout & Pembayaran)
 *
 * SHARED ASSETS (semua halaman):
 *   assets/style.css  → Design System (CSS tokens, komponen, layout)
 *   js/main.js        → JavaScript bersama (theme, navbar, reveal, toast)
 * ================================================================
 */

define('VELORA_ENTRY', true);
session_start();

/* ── Logout handler ── */
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php?msg=logout');
    exit;
}

/* ── Konfigurasi halaman ── */
$page_title   = 'VELORA — The Curated Marketplace';
$page_desc    = 'VELORA is a premium curated marketplace for unique products and expert services.';
$current_year = date('Y');

/* ── Jumlah item cart (di-set oleh cart/shoppingCart.php via $_SESSION) ── */
$cart_count = $_SESSION['cart_count'] ?? 3;

/* ── Status login user (di-set oleh auth/signIn.php via $_SESSION) ── */
$is_logged_in = isset($_SESSION['user_id']);
$user_name    = $is_logged_in ? htmlspecialchars($_SESSION['user_name'] ?? '') : '';

/* ── Flash message (contoh: setelah logout) ── */
$flash_msg  = '';
$flash_type = 'default';
if (isset($_GET['msg']) && $_GET['msg'] === 'logout') {
    $flash_msg  = 'You have been signed out. See you next time!';
    $flash_type = 'success';
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_desc) ?>">

    <!--
        Shared CSS Design System
        Berisi: font, token warna, navbar, tombol, animasi, komponen landing page, footer, responsive
        Path: assets/style.css
    -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<!-- Scroll progress bar (top strip) -->
<div class="scroll-prog" id="scrollProg"></div>

<?php
/* ──────────────────────────────────────────────────────────
   BAGIAN 1 — Tom
   File  : main/section1.php
   Isi   : Navbar, Hero, Stats Bar, Categories, Featured, Product of the Week
────────────────────────────────────────────────────────── */
require __DIR__ . '/main/section1.php';

/* ──────────────────────────────────────────────────────────
   BAGIAN 2 — Bagus
   File  : main/section2.php
   Isi   : Journal, Team, About / Footer
────────────────────────────────────────────────────────── */
require __DIR__ . '/main/section2.php';
?>

<!--
    Shared JavaScript
    Berisi: theme toggle, navbar scroll shrink, mobile menu,
            scroll reveal, counter animasi, global toast (window.showToast)
    Path: js/main.js
-->
<script src="js/main.js"></script>

<!-- Scroll progress bar — update width saat user scroll -->
<script>
(function () {
    var prog = document.getElementById('scrollProg');
    if (!prog) return;
    window.addEventListener('scroll', function () {
        var s = document.documentElement.scrollTop || document.body.scrollTop;
        var h = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        prog.style.width = (h > 0 ? (s / h * 100) : 0) + '%';
    }, { passive: true });
})();
</script>

<?php if ($flash_msg): ?>
<!-- Flash toast — muncul setelah redirect (misal: setelah logout) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.showToast(<?= json_encode($flash_msg) ?>, <?= json_encode($flash_type) ?>);
});
</script>
<?php endif; ?>

</body>
</html>
