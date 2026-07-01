<?php
require_once __DIR__ . '/config/env.php';
require_once __DIR__ . '/controllers/DeleteReviewController.php';

$controller = new DeleteReviewController();
$controller->handleRequest();
