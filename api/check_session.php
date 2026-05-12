<?php
session_start();
header('Content-Type: application/json');
if (isset($_SESSION['user_id'])) {
    echo json_encode(['logged'=>true]);
} else {
    echo json_encode(['logged'=>false]);
}
?>