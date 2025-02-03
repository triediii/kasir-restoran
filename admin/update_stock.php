<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $change = $_POST['change'];

    try {
        $sql = "UPDATE produk SET stok = stok + :change WHERE id_produk = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['change' => $change, 'id' => $id]);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
