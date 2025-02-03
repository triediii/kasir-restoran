<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login-admin.php");
    exit();
}
require 'config.php';

if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];

    try {
        // Memulai transaksi
        $pdo->beginTransaction();

        // Hapus entri di tabel detail_transaksi yang berkaitan dengan produk
        $sql_detail = "DELETE FROM detail_transaksi WHERE id_produk = :id_produk";
        $stmt_detail = $pdo->prepare($sql_detail);
        $stmt_detail->bindParam(':id_produk', $id_produk, PDO::PARAM_INT);
        $stmt_detail->execute();

        // Hapus produk dari tabel produk
        $sql_produk = "DELETE FROM produk WHERE id_produk = :id_produk";
        $stmt_produk = $pdo->prepare($sql_produk);
        $stmt_produk->bindParam(':id_produk', $id_produk, PDO::PARAM_INT);
        $stmt_produk->execute();

        // Commit transaksi
        $pdo->commit();

        $_SESSION['message'] = "";
    } catch (PDOException $e) {
        // Rollback transaksi jika terjadi kesalahan
        $pdo->rollBack();
        $_SESSION['error'] = "Terjadi kesalahan saat menghapus produk: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "ID produk tidak valid.";
}

header("Location: daftar-produk.php");
exit();
?>
