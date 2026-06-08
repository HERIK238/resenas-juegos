<?php
// Start session and load configuration
require_once __DIR__ . '/config/env.php';
require_once __DIR__ . '/controllers/ReviewsController.php';

$controller = new ReviewsController();
$controller->handleRequest();


