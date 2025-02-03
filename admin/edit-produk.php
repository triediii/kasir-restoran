<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login-admin.php");
    exit();
}
require 'config.php';

$produk_list = [];
$selected_produk = null;

// Mengambil semua produk untuk dropdown
$sql = "SELECT id_produk, nama_produk FROM produk";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produk_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mengambil data produk yang dipilih
if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];
    $sql = "SELECT * FROM produk WHERE id_produk = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_produk]);
    $selected_produk = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_produk = $_POST['id_produk'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_diterbitkan = date('Y-m-d H:i:s');

    // Mengambil nama file foto produk dan menentukan path target
    $foto_produk = $_FILES['foto_produk']['name'];
    $target_dir = "admin/img/";
    $target_file = $target_dir . basename($id_produk . "." . pathinfo($foto_produk, PATHINFO_EXTENSION));

    // Memastikan folder tujuan ada dan memiliki izin yang benar
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Mengunggah file
    if (move_uploaded_file($_FILES['foto_produk']['tmp_name'], $target_file)) {
        // Menyimpan data ke database
        $sql = "UPDATE produk SET kategori = ?, harga = ?, stok = ?, deskripsi = ?, foto_produk = ?, tanggal_ditambahkan = ? WHERE id_produk = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$kategori, $harga, $stok, $deskripsi, basename($target_file), $tanggal_diterbitkan, $id_produk]);

        echo "<script>alert('Produk berhasil diperbarui!'); window.location='kelola-produk.php';</script>";
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
                    <!-- Form untuk mengedit produk -->
                    <h1 class="judul">Edit Produk</h1>
                    <form class="form-inline mb-4" method="get" style="padding-left: 12%;">
                        <input class="form-control mr-sm-2"  type="search" name="search" placeholder="Cari produk..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" oninput="filterDropdown()">
                        <button class="btn btn-primary my-2 my-sm-0" type="submit">Cari</button>
                    </form>
                    <form method="get" style="padding-left: 12%; padding-right: 12%; margin-bottom: 2%;">
                        <select class="form-control" name="id" onchange="this.form.submit()" id="produkDropdown">
                            <option value="">Pilih produk untuk diedit</option>
                            <?php foreach ($produk_list as $produk): ?>
                                <option value="<?php echo $produk['id_produk']; ?>" <?php echo (isset($_GET['id']) && $_GET['id'] == $produk['id_produk']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($produk['nama_produk']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                    <?php if ($selected_produk): ?>
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id_produk" value="<?php echo $selected_produk['id_produk']; ?>">
                            <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                                <label for="kategori" style="color: black;">Kategori*</label>
                                <input type="text" class="form-control" id="kategori" name="kategori" value="<?php echo htmlspecialchars($selected_produk['kategori']); ?>" required>
                            </div>
                            <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                                <label for="harga" style="color: black;">Harga*</label>
                                <input type="number" class="form-control" id="harga" name="harga" step="0.01" value="<?php echo htmlspecialchars($selected_produk['harga']); ?>" required>
                            </div>
                            <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                                <label for="stok" style="color: red;">Stok*</label>
                                <input type="number" class="form-control" id="stok" name="stok" value="<?php echo htmlspecialchars($selected_produk['stok']); ?>" required>
                            </div>
                            <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                                <label for="deskripsi" style="color: black;">Deskripsi*</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?php echo htmlspecialchars($selected_produk['deskripsi']); ?></textarea>
                            </div>
                            <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                                <label for="foto_produk" style="color: black;">Foto Produk*</label>
                                <input type="file" class="form-control-file" id="foto_produk" name="foto_produk">
                            </div>
                            <div class="tambah" style="padding-left: 12%;">
                                <button type="submit" class="btn btn-primary">Perbarui Produk</button>
                            </div>
                        </form>
                    <?php endif; ?>
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

    <!-- Scripts -->
</body>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<script>
function filterDropdown() {
    var input, filter, ul, li, a, i;
    input = document.querySelector('input[name="search"]');
    filter = input.value.toUpperCase();
    select = document.getElementById("produkDropdown");
    options = select.getElementsByTagName("option");

    for (i = 0; i < options.length; i++) {
        txtValue = options[i].textContent || options[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            options[i].style.display = "";
        } else {
            options[i].style.display = "none";
        }
    }
}
</script>
</html>

<style>
    .judul {
        color: black;
        font-size: 30px;
        margin-bottom: 2%;
        padding-left: 12%;
    }
</style>
