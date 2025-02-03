<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login-admin.php");
    exit();
}
require 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_diterbitkan = date('Y-m-d H:i:s');

    // Mengambil nama file foto produk dan menentukan path target
    $foto_produk = $_FILES['foto_produk']['name'];
    $target_dir = "admin/img/";
    $target_file = $target_dir . basename($nama_produk . "." . pathinfo($foto_produk, PATHINFO_EXTENSION));

    // Memastikan folder tujuan ada dan memiliki izin yang benar
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Mengunggah file
    if (move_uploaded_file($_FILES['foto_produk']['tmp_name'], $target_file)) {
        // Menyimpan data ke database
        $sql = "INSERT INTO produk (nama_produk, kategori, harga, stok, deskripsi, foto_produk, tanggal_ditambahkan) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama_produk, $kategori, $harga, $stok, $deskripsi, basename($target_file), $tanggal_diterbitkan]);

        echo "<script>alert('Produk berhasil ditambahkan!'); window.location='kelola-produk.php';</script>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Terjadi kesalahan saat mengunggah foto.</div>";
    }
}
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
                    <!-- Form untuk menambah produk -->
                    <h1 class="judul">Tambah Produk</h1>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="nama_produk" style = "color: black;">Nama Produk*</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="kategori" style = "color: black;">Kategori*</label>
                            <input type="text" class="form-control" id="kategori" name="kategori" required>
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="harga" style = "color: black;">Harga*</label>
                            <input type="number" class="form-control" id="harga" name="harga" step="0.01" required>
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="stok" style = "color: black;">Stok*</label>
                            <input type="number" class="form-control" id="stok" name="stok" required>
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="deskripsi" style = "color: black;">Deskripsi*</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="foto_produk" style = "color: black;">Foto Produk*</label>
                            <input type="file" class="form-control-file" id="foto_produk" name="foto_produk" required>
                        </div>
                        <div class="tambah" style="padding-left: 12%;">
                        <button type="submit" class="btn btn-primary">Tambah Produk</button>
                        </div>
                    </form>
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

    <!-- Scripts -->
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
        margin-bottom: 2%;
        padding-left: 12%;
    }

