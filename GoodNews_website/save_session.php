<?php
session_start();
if (isset($_POST['newsItems'])) {
    $_SESSION['newsItems'] = json_decode($_POST['newsItems'], true);
}
echo json_encode(['status' => 'success']);
?>