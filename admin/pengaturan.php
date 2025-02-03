<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login-admin.php");
    exit();
}
require 'config.php';

// Tetapkan nilai id_pengaturan yang tidak dapat diubah
$id_pengaturan = 1;

// Query untuk mendapatkan data berdasarkan id_pengaturan
$query = "SELECT * FROM pengaturan WHERE id_pengaturan = :id_pengaturan";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':id_pengaturan', $id_pengaturan, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Data tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_toko = $_POST['nama_toko'];
    $alamat_toko = $_POST['alamat_toko'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];
    $pajak_persen = $_POST['pajak_persen'];

    // Handle file upload
    $logo = $data['logo'];
    if ($_FILES['logo']['name']) {
        $target_dir = "admin/img/";
        $target_file = $target_dir . basename($_FILES["logo"]["name"]);
        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
            $logo = $_FILES["logo"]["name"];
        }
    }

    // Query untuk mengupdate data
    $update_query = "UPDATE pengaturan SET 
                        logo = :logo, 
                        nama_toko = :nama_toko, 
                        alamat_toko = :alamat_toko, 
                        telepon = :telepon, 
                        email = :email, 
                        pajak_persen = :pajak_persen, 
                        tanggal_diperbarui = NOW() 
                    WHERE id_pengaturan = :id_pengaturan";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->bindValue(':logo', $logo);
    $update_stmt->bindValue(':nama_toko', $nama_toko);
    $update_stmt->bindValue(':alamat_toko', $alamat_toko);
    $update_stmt->bindValue(':telepon', $telepon);
    $update_stmt->bindValue(':email', $email);
    $update_stmt->bindValue(':pajak_persen', $pajak_persen);
    $update_stmt->bindValue(':id_pengaturan', $id_pengaturan, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='pengaturan.php';</script>";
    } else {
        echo "Error: " . $update_stmt->errorInfo()[2];
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
                    <!-- Form Edit Pengaturan -->
                    <h1 class="judul">Edit Toko</h1>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="logo-toko" style="padding-left: 12%; padding-right: 12%;">
                        <img src="admin/img/<?php echo htmlspecialchars($data['logo']); ?>" alt="Logo" width="100" style="border-radius: 50%;">
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="logo" style = "color: black;">Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo">
                            
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="nama_toko" style = "color: black;">Nama Toko</label>
                            <input type="text" class="form-control" id="nama_toko" name="nama_toko" value="<?php echo htmlspecialchars($data['nama_toko']); ?>" required>
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="alamat_toko" style = "color: black;">Alamat Toko</label>
                            <input type="text" class="form-control" id="alamat_toko" name="alamat_toko" value="<?php echo htmlspecialchars($data['alamat_toko']); ?>" required>
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="telepon" style = "color: black;">Telepon</label>
                            <input type="text" class="form-control" id="telepon" name="telepon" value="<?php echo htmlspecialchars($data['telepon']); ?>" required>
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="email" style = "color: black;">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="pajak_persen" style = "color: black;">Pajak (%)</label>
                            <input type="text" class="form-control" id="pajak_persen" name="pajak_persen" value="<?php echo htmlspecialchars($data['pajak_persen']); ?>">
                        </div>
                        <div class="tambah" style="padding-left: 12%;">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
