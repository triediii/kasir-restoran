<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login-admin.php");
    exit();
}
require 'config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt the password
    $email = $_POST['email'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $role = $_POST['role'];

    // Query to insert a new user
    $query = "INSERT INTO pengguna (username, password, email, nama_lengkap, role, tanggal_dibuat) 
            VALUES (:username, :password, :email, :nama_lengkap, :role, NOW())";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $password);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':nama_lengkap', $nama_lengkap);
    $stmt->bindValue(':role', $role);

    if ($stmt->execute()) {
        echo "<script>alert('Pengguna berhasil ditambahkan!'); window.location='pengguna.php';</script>";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
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
                    <!-- Form Tambah Pengguna -->
                    <h1 class="judul">Tambah Pengguna</h1>
                    <form action="" method="POST">
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="username" style = "color: black;">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="password" style = "color: black;">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="email" style = "color: black;">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="nama_lengkap" style = "color: black;">Nama Lengkap</label>
                            <input type="nama_lengkap" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                        </div>
                        <div class="form-group" style="padding-left: 12%; padding-right: 12%;">
                            <label for="role" style = "color: black;">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="kasir">Kasir</option>
                            </select>
                        </div>
                        <div class="tombol" style="padding-left: 12%;">
                        <button type="submit" class="btn btn-primary">Tambah Pengguna</button>
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
