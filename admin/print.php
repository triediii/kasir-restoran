<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login-admin.php");
    exit();
}
require 'config.php';

// Ambil data pengaturan toko
$stmt = $pdo->prepare("SELECT nama_toko, logo, alamat_toko, telepon, email, pajak_persen FROM pengaturan WHERE id_pengaturan = 1");
$stmt->execute();
$pengaturan = $stmt->fetch();

$transaksi_id = $_GET['transaksi_id'];
$stmt2 = $pdo->prepare("SELECT * FROM transaksi WHERE id_transaksi = ?");
$stmt2->execute([$transaksi_id]);
$transaksi = $stmt2->fetch();

// Ambil detail transaksi
$stmt3 = $pdo->prepare("SELECT * FROM detail_transaksi dt JOIN produk p ON dt.id_produk = p.id_produk WHERE dt.id_transaksi = ?");
$stmt3->execute([$transaksi_id]);
$detail_transaksi = $stmt3->fetchAll();

// Hitung total dan pajak
$total = 0;
foreach ($detail_transaksi as $item) {
    $total += $item['kuantitas'] * $item['harga'];
}
$pajak = ($total * $pengaturan['pajak_persen']) / 100;
$total_pembelian = $total + $pajak;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 58mm; /* ukuran lebar rollpaper */
            margin: 0;
            padding: 0;
        }
        .header, .footer {
            text-align: center;
        }
        .header img {
            max-width: 50px;
        }
        .detail-transaksi, .transaksi-list {
            margin: 10px 0;
        }
        .transaksi-list li {
            list-style: none;
            padding: 5px 0;
        }
        .footer {
            border-top: 1px solid black;
            padding-top: 10px;
            margin-top: 10px;
            font-size: smaller;
        }
        .total-section {
            border-top: 1px solid black;
            margin-top: 10px;
            padding-top: 10px;
            font-size: smaller;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <img src="admin/img/<?= $pengaturan['logo'] ?>" alt="Logo Toko">
        <h1><?= $pengaturan['nama_toko'] ?></h1>
    </div>
    <div class="detail-transaksi">
        <p>Tanggal: <?= date('d-m-Y H:i:s', strtotime($transaksi['tanggal_transaksi'])) ?></p>
    </div>
    <ul class="transaksi-list">
        <?php foreach ($detail_transaksi as $item): ?>
        <li>
            <strong><?= $item['nama_produk'] ?></strong><br>
            Jumlah: <?= $item['kuantitas'] ?><br>
            Harga Satuan: Rp <?= number_format($item['harga'], 2, ',', '.') ?><br>
            Subtotal: Rp <?= number_format($item['kuantitas'] * $item['harga'], 2, ',', '.') ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <div class="total-section">
        <p>Total: Rp <?= number_format($total, 2, ',', '.') ?></p>
        <p>Pajak (<?= $pengaturan['pajak_persen'] ?>%): Rp <?= number_format($pajak, 2, ',', '.') ?></p>
        <p>Total Pembelian: Rp <?= number_format($total_pembelian, 2, ',', '.') ?></p>
    </div>
    <div class="footer">
        <p>Telepon: <?= $pengaturan['telepon'] ?></p>
        <p>Email: <?= $pengaturan['email'] ?></p>
        <p>Alamat: <?= $pengaturan['alamat_toko'] ?></p>
    </div>
</body>

</html>
