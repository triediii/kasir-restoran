<?php
$current_page = basename($_SERVER['PHP_SELF']);
require 'config.php';

// Ambil data nama_toko dan logo dari tabel pengaturan
$query = "SELECT nama_toko, logo FROM pengaturan WHERE id_pengaturan = 1";
$stmt = $pdo->prepare($query);
$stmt->execute();
$pengaturan = $stmt->fetch(PDO::FETCH_ASSOC);

$nama_toko = $pengaturan['nama_toko'];
$logo = $pengaturan['logo'];
?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center" href="../admin/index.php">
        <div class="sidebar-brand-icon">
            <img src="admin/img/<?php echo htmlspecialchars($logo); ?>" alt="Logo" style="border-radius: 50%; width: 40px; height: 40px;">
        </div>
        <div class="sidebar-brand-text mx-3">
            <?php echo htmlspecialchars($nama_toko); ?>
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= ($current_page == 'index.php') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Menu
    </div>

    <!-- Nav Item - Daftar Produk -->
    <li class="nav-item <?= ($current_page == 'daftar-produk.php') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/daftar-produk.php">
            <i class="fas fa-fw fa-table"></i>
            <span>Daftar Produk</span></a>
    </li>

    <!-- Nav Item - Kelola Produk -->
    <li class="nav-item <?= ($current_page == 'kelola-produk.php') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/kelola-produk.php">
            <i class="fas fa-fw fa-box"></i>
            <span>Tambah Produk</span></a>
    </li>

    <!-- Nav Item - Transaksi -->
    <li class="nav-item <?= ($current_page == 'transaksi.php') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/transaksi.php">
            <i class="fas fa-fw fa-exchange-alt"></i>
            <span>Transaksi</span></a>
    </li>
    <!-- Nav Riwayat - Transaksi -->
    <li class="nav-item <?= ($current_page == 'riwayat-transaksi.php') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/riwayat-transaksi.php">
            <i class="fas fa-clock"></i>
            <span>Riwayat Transaksi</span></a>
    </li>
    <!-- Nav Item - Laporan -->
    <li class="nav-item <?= ($current_page == 'laporan.php') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/laporan.php">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Laporan</span></a>
    </li>

    <!-- Nav Item - Pengaturan -->
    <li class="nav-item <?= ($current_page == 'pengaturan.php') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/pengaturan.php">
            <i class="fas fa-fw fa-cog"></i>
            <span>Pengaturan</span></a>
    </li>

    <!-- Nav Item - Pengguna -->
    <li class="nav-item <?= ($current_page == 'pengguna.php') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/pengguna.php">
            <i class="fas fa-fw fa-user"></i>
            <span>Pengguna</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
