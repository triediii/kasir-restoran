<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login-admin.php");
    exit();
}
require 'config.php';

// Mengatur jumlah baris per halaman
$rows_per_page = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
$search_query = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

// Menentukan halaman saat ini
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $rows_per_page;

// Query untuk mengambil data produk
$sql = "SELECT * FROM produk WHERE nama_produk LIKE :search LIMIT :offset, :rows";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', $search_query);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':rows', $rows_per_page, PDO::PARAM_INT);
$stmt->execute();
$produk_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Menghitung total produk untuk pagination
$sql_count = "SELECT COUNT(*) FROM produk WHERE nama_produk LIKE :search";
$stmt_count = $pdo->prepare($sql_count);
$stmt_count->bindValue(':search', $search_query);
$stmt_count->execute();
$total_products = $stmt_count->fetchColumn();
$total_pages = ceil($total_products / $rows_per_page);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'item/head.php'; ?>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'item/sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'item/header.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h1 class="judul" >Daftar Produk</h1>
                    <!-- Pencarian dan Pengaturan Tampilan -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <!-- Tombol Pencarian -->
                        <form class="form-inline" method="get">
                            <input class="form-control mr-sm-2" type="search" name="search" placeholder="Cari produk..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                            <button class="btn btn-primary my-2 my-sm-0" type="submit">Cari</button>
                        </form>

                        <!-- Tombol Edit Produk -->
                        <a href="edit-produk.php" class="btn btn-warning" style="background-color: dodgerblue; border: none;">Edit Produk</a>

                        <!-- Dropdown Pengaturan Tampilan -->
                        <form class="form-inline" method="get">
                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                            <select class="form-control" name="rows" onchange="this.form.submit()">
                                <option value="10" <?php echo $rows_per_page == 10 ? 'selected' : ''; ?>>10</option>
                                <option value="25" <?php echo $rows_per_page == 25 ? 'selected' : ''; ?>>25</option>
                                <option value="50" <?php echo $rows_per_page == 50 ? 'selected' : ''; ?>>50</option>
                            </select>
                        </form>
                    </div>

                    <!-- Tampilkan Produk -->
                    <div class="row">
                        <?php foreach ($produk_list as $produk): ?>
                            <div class="col-lg-3 col-md-4 mb-4">
                                <div class="card">
                                    <!-- Tombol Hapus Produk -->
                                    <a href="hapus-produk.php?id=<?php echo $produk['id_produk']; ?>" class="btn btn-danger btn-sm position-absolute" style="top: 10px; right: 10px;">Hapus</a>
                                    <img src="admin/img/<?php echo htmlspecialchars($produk['foto_produk']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($produk['nama_produk']); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title" style="color: black;"><b><?php echo htmlspecialchars($produk['nama_produk']); ?></b></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($produk['deskripsi']); ?></p>
                                        <p class="card-text" style="color: black;">Rp<?php echo number_format($produk['harga'], 2, ',', '.'); ?></p>
                                        <p class="card-text" style="color: blue;">Stok: <?php echo htmlspecialchars($produk['stok']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?search=<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>&rows=<?php echo $rows_per_page; ?>&page=<?php echo max(1, $page - 1); ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?search=<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>&rows=<?php echo $rows_per_page; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?search=<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>&rows=<?php echo $rows_per_page; ?>&page=<?php echo min($total_pages, $page + 1); ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <!-- Add your footer here if needed -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

</body>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>
</html>
<style>
    .judul {
        color: black;
        font-size: 30px;
    }
</style>