<?php
// กำหนดโฟลเดอร์สำหรับเก็บรูปภาพชิ้นงานเสีย
$target_dir = "naylin/";

// ตรวจสอบและสร้างโฟลเดอร์ถ้ายังไม่มี
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if (isset($_FILES["imageFile"])) {
    $filename = time() . "_" . basename($_FILES["imageFile"]["name"]);
    $target_file = $target_dir . $filename;
    
    if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file)) {
        echo json_encode(["status" => "success", "url" => $target_file]);
    } else {
        echo json_encode(["status" => "error", "message" => "อัปโหลดรูปภาพไม่สำเร็จ"]);
    }
} else {
    // กรณีต้องการเรียกดูรายการรูปภาพทั้งหมดในโฟลเดอร์ naylin
    if (isset($_GET['action']) && $_GET['action'] == 'list') {
        $images = [];
        if (is_dir($target_dir)) {
            $files = scandir($target_dir);
            foreach ($files as $file) {
                if ($file !== "." && $file !== "..") {
                    $images[] = $target_dir . $file;
                }
            }
        }
        echo json_encode($images);
        exit;
    }

    echo json_encode(["status" => "error", "message" => "ไม่พบไฟล์รูปภาพ"]);
}
?>
