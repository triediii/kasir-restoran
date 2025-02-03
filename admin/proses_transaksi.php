<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login-admin.php");
    exit();
}
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart = $_POST['cart'];
    $total = 0;

    // Mulai transaksi
    $pdo->beginTransaction();

    try {
        // Hitung total
        foreach ($cart as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        }

        // Simpan transaksi
        $stmt = $pdo->prepare("INSERT INTO transaksi (id_pengguna, total_harga, tanggal_transaksi) VALUES (?, ?, NOW())");
        $stmt->execute([$_SESSION['user_id'], $total]);
        $transaksi_id = $pdo->lastInsertId();

        // Simpan detail transaksi dan kurangi stok
        foreach ($cart as $id => $item) {
            $subtotal = $item['price'] * $item['quantity'];
            
            // Kurangi stok produk
            $stmt = $pdo->prepare("UPDATE produk SET stok = stok - :quantity WHERE id_produk = :id");
            $stmt->execute(['quantity' => $item['quantity'], 'id' => $id]);

            // Periksa stok produk
            $stmt = $pdo->prepare("SELECT stok FROM produk WHERE id_produk = :id");
            $stmt->execute(['id' => $id]);
            $stok = $stmt->fetchColumn();
            
            if ($stok < 0) {
                // Rollback transaksi jika stok tidak mencukupi
                $pdo->rollBack();
                echo json_encode(['error' => 'Stok tidak mencukupi untuk produk ID: ' . $id]);
                exit();
            }

            // Simpan detail transaksi
            $stmt = $pdo->prepare("INSERT INTO detail_transaksi (id_transaksi, id_produk, kuantitas, harga_satuan, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$transaksi_id, $id, $item['quantity'], $item['price'], $subtotal]);
        }

        // Commit transaksi
        $pdo->commit();
        echo json_encode(['transaksi_id' => $transaksi_id]);
    } catch (Exception $e) {
        // Rollback jika terjadi kesalahan
        $pdo->rollBack();
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
