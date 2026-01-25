<?php
$file = __DIR__ . '/../app/Model/UtilisateurModel.php';
$content = file_get_contents($file);

echo "File: $file\n";
echo "First 10 bytes (hex):\n";
for ($i = 0; $i < min(10, strlen($content)); $i++) {
    printf("%02X ", ord($content[$i]));
}
echo "\n";

if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
    echo "BOM detected!\n";
} else {
    echo "No BOM detected.\n";
}

if (strpos($content, '<?php') !== 0) {
    echo "Whitespace before <?php detected!\n";
} else {
    echo "Starts with <?php correctly.\n";
}
