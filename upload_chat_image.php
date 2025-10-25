<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

session_start();
if (!isset($_SESSION['role'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

if (!isset($_FILES['image'])) {
    echo json_encode(["error" => "No file uploaded"]);
    exit;
}

$file = $_FILES['image'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["error" => "Upload error code: " . $file['error']]);
    exit;
}

$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!isset($allowed[$mime])) {
    echo json_encode(["error" => "Unsupported file type"]);
    exit;
}

if ($file['size'] > 5 * 1024 * 1024) { // 5MB
    echo json_encode(["error" => "File too large (max 5MB)"]);
    exit;
}

$ext = $allowed[$mime];
$baseDir = __DIR__ . DIRECTORY_SEPARATOR . 'Images' . DIRECTORY_SEPARATOR . 'chat_uploads';
if (!is_dir($baseDir)) {
    mkdir($baseDir, 0777, true);
}

$safeName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
$filename = $safeName . '_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
$dest = $baseDir . DIRECTORY_SEPARATOR . $filename;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    echo json_encode(["error" => "Failed to save file"]);
    exit;
}

$url = 'Images/chat_uploads/' . $filename;
echo json_encode(["url" => $url]);
