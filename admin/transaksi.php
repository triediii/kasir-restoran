<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require 'config.php';

// Fetch all products
$stmt = $pdo->query("SELECT * FROM produk");
$produk = $stmt->fetchAll();

// Fetch tax percentage
$stmt = $pdo->query("SELECT pajak_persen FROM pengaturan WHERE id_pengaturan = 1");
$pajak = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'item/head.php'; ?>
    <style>
        .product-card {
            margin: 15px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            position: relative;
        }
        .product-card img {
            max-width: 100%;
            height: auto;
        }
        .product-card h5 {
            margin: 10px 0;
        }
        .product-card .category {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #007bff;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .product-card .price, .product-card .stock {
            margin: 5px 0;
        }
        .judul {
            color: black;
            font-size: 30px;
            margin-bottom: 2%;
        }
    </style>
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
                    <h1 class="judul">Transaksi Penjualan</h1>
                    <div class="form-group">
                        <input type="text" id="search" class="form-control" placeholder="Cari produk...">
                    </div>
                    <div class="row" id="product-list">
                        <?php foreach ($produk as $item): ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 product-card" data-name="<?= strtolower($item['nama_produk']) ?>" data-category="<?= strtolower($item['kategori']) ?>">
                                <div class="category"><?= htmlspecialchars($item['kategori']) ?></div>
                                <img src="../admin/admin/img/<?= htmlspecialchars($item['foto_produk']); ?>" class="card-img-top" alt="<?= htmlspecialchars($item['nama_produk']); ?>">
                                <div class="nama produk" style="color: black;">
                                    <h5><b><?= htmlspecialchars($item['nama_produk']) ?></b></h5>
                                </div>
                                <div class="stock">Stok: <?= htmlspecialchars($item['stok']) ?></div>
                                <div class="price" style="color: blue;">Rp <?= number_format($item['harga'], 2, ',', '.') ?></div>
                                <button class="btn btn-primary add-to-cart" data-id="<?= htmlspecialchars($item['id_produk']) ?>" data-name="<?= htmlspecialchars($item['nama_produk']) ?>" data-category="<?= htmlspecialchars($item['kategori']) ?>" data-price="<?= htmlspecialchars($item['harga']) ?>">Tambah</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <hr>
                    <div class="judul"><h3>Atur Transaksi</h3></div>
                    <div id="cart"></div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="color: black;">Nama Produk</th>
                                <th style="color: black;">Kategori</th>
                                <th style="color: black;">Jumlah</th>
                                <th style="color: black;">Harga Satuan</th>
                                <th style="color: black;">Subtotal</th>
                                <th style="color: black;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cart-table" style="color: black;"></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" style="color: black;">Total Produk</th>
                                <th style="color: black;" id="total-products">0</th>
                            </tr>
                            <tr>
                                <th colspan="4" style="color: black;">Total Harga</th>
                                <th style="color: black;" id="total-price">Rp 0,00</th>
                            </tr>
                            <tr>
                                <th colspan="4" style="color: black;">Pajak (<?= htmlspecialchars($pajak) ?>%)</th>
                                <th style="color: black;" id="tax">Rp 0,00</th>
                            </tr>
                            <tr>
                                <th colspan="4" style="color: black;">Total Pembayaran</th>
                                <th style="color: black;" id="total-payment">Rp 0,00</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div style="margin-bottom: 3%; margin-right: 5%; float: right;">
                        <button class="btn btn-primary" id="checkout">Proses Transaksi</button>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#product-list .product-card').filter(function() {
                    $(this).toggle($(this).data('name').indexOf(value) > -1 || $(this).data('category').indexOf(value) > -1);
                });
            });

            var cart = {};

            $('.add-to-cart').on('click', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var category = $(this).data('category');
                var price = parseFloat($(this).data('price'));

                if (!cart[id]) {
                    cart[id] = { name: name, category: category, price: price, quantity: 1 };
                } else {
                    cart[id].quantity += 1;
                }
                renderCart();
            });

            $(document).on('click', '.remove-from-cart', function() {
                var id = $(this).data('id');
                if (cart[id]) {
                    if (cart[id].quantity > 1) {
                        cart[id].quantity -= 1;
                    } else {
                        delete cart[id];
                    }
                }
                renderCart();
            });

            $(document).on('click', '.delete-from-cart', function() {
                var id = $(this).data('id');
                if (cart[id]) {
                    delete cart[id];
                }
                renderCart();
            });

            function renderCart() {
                var cartHtml = '';
                var total = 0;
                var totalProducts = 0;
                $.each(cart, function(id, item) {
                    var subtotal = item.price * item.quantity;
                    cartHtml += '<tr>' +
                        '<td>' + item.name + '</td>' +
                        '<td>' + item.category + '</td>' +
                        '<td>' + item.quantity + ' <button class="btn btn-sm btn-danger remove-from-cart" data-id="' + id + '">-</button></td>' +
                        '<td>Rp ' + item.price.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + '</td>' +
                        '<td>Rp ' + subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + '</td>' +
                        '<td><button class="btn btn-sm btn-danger delete-from-cart" data-id="' + id + '">Hapus</button></td>' +
                        '</tr>';
                    total += subtotal;
                    totalProducts += item.quantity;
                });
                $('#cart-table').html(cartHtml);
                $('#total-products').text(totalProducts);
                $('#total-price').text('Rp ' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

                var tax = total * <?= $pajak ?> / 100;
                var totalPayment = total + tax;

                $('#tax').text('Rp ' + tax.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
                $('#total-payment').text('Rp ' + totalPayment.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
            }

            $('#checkout').on('click', function() {
                $.ajax({
                    url: 'proses_transaksi.php',
                    type: 'POST',
                    data: { cart: cart },
                    dataType: 'json', // Pastikan format respons adalah JSON
                    success: function(response) {
                        if (response.transaksi_id) {
                            alert('Transaksi berhasil!');
                            window.location.href = 'print.php?transaksi_id=' + response.transaksi_id;
                        } else {
                            alert('Terjadi kesalahan. Silakan coba lagi.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan pada server.');
                    }
                });
            });
        });
    </script>
</body>
</html>
