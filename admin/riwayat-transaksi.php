<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require 'config.php';

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_transaksi'])) {
    $id_transaksi = $_POST['id_transaksi'];
    try {
        $pdo->beginTransaction();

        // Delete from detail_transaksi table
        $sql_delete_detail = "DELETE FROM detail_transaksi WHERE id_transaksi = :id_transaksi";
        $stmt_delete_detail = $pdo->prepare($sql_delete_detail);
        $stmt_delete_detail->execute(['id_transaksi' => $id_transaksi]);

        // Delete from transaksi table
        $sql_delete_transaksi = "DELETE FROM transaksi WHERE id_transaksi = :id_transaksi";
        $stmt_delete_transaksi = $pdo->prepare($sql_delete_transaksi);
        $stmt_delete_transaksi->execute(['id_transaksi' => $id_transaksi]);

        $pdo->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => 'Error: ' . $e->getMessage()]);
    }
    exit();
}

// Fetch transaction data
$sql = "SELECT t.id_transaksi, t.tanggal_transaksi, u.nama_lengkap, t.total_harga
        FROM transaksi t
        JOIN pengguna u ON t.id_pengguna = u.id_pengguna
        ORDER BY t.id_transaksi DESC"; // Order by latest transactions
$stmt = $pdo->prepare($sql);
$stmt->execute();
$transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'item/head.php'; ?>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body id="page-top">

<div id="wrapper">
    <?php include 'item/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include 'item/header.php'; ?>
            <div class="container-fluid">
                <h1 class="h3 mb-2 text-gray-800">Riwayat Transaksi</h1>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Kasir/Pengguna</th>
                                        <th>Barang</th>
                                        <th>Nominal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($transactions as $transaction) {
                                        $sql_products = "SELECT p.nama_produk, dt.kuantitas 
                                                         FROM detail_transaksi dt
                                                         JOIN produk p ON dt.id_produk = p.id_produk
                                                         WHERE dt.id_transaksi = :id_transaksi";
                                        $stmt_products = $pdo->prepare($sql_products);
                                        $stmt_products->execute(['id_transaksi' => $transaction['id_transaksi']]);
                                        $products = $stmt_products->fetchAll();

                                        $product_list = [];
                                        foreach ($products as $product) {
                                            $product_list[] = $product['nama_produk'] . ' (' . $product['kuantitas'] . ')';
                                        }
                                        $product_names = implode(', ', $product_list);

                                        echo "<tr>
                                                <td>{$no}</td>
                                                <td>{$transaction['tanggal_transaksi']}</td>
                                                <td>{$transaction['nama_lengkap']}</td>
                                                <td>{$product_names}</td>
                                                <td>Rp " . number_format($transaction['total_harga'], 2, ',', '.') . "</td>
                                                <td><button class='btn btn-danger btn-sm delete-btn' data-id='{$transaction['id_transaksi']}'><i class='fas fa-trash'></i></button></td>
                                            </tr>";
                                        $no++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

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

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#dataTable').DataTable({
        "pageLength": 10
    });

    $('#searchBox').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#rowsPerPage').on('change', function() {
        table.page.len(this.value).draw();
    });

    $(document).on('click', '.delete-btn', function() {
        var transactionId = $(this).data('id');
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: 'riwayat-transaksi.php',
                type: 'POST',
                data: { id_transaksi: transactionId },
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.success) {
                        location.reload();
                    } else {
                        alert('Gagal menghapus transaksi: ' + res.error);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + xhr.responseText);
                }
            });
        }
    });
});
</script>

</body>
</html>
