<?php
// Mock environment
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['covoiturage_id'] = 40;
$_GET['p'] = 'covoiturages.participer';

// Capture output
ob_start();
require __DIR__ . '/index.php';
$output = ob_get_clean();

file_put_contents(__DIR__ . '/debug_output.txt', "START\n" . $output . "\nEND");
echo "Debug output written.";
