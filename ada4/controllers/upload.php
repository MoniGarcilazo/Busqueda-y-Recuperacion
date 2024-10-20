<?php 
include '../controllers/Management.php';

$upload_dir = "../uploads/files/";

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$management = new Management($upload_dir);


if (isset($_FILES['files'])) {
    $file_count = count($_FILES['files']['name']);

    for ($i=0; $i < $file_count; $i++) { 
        $file_tmp = $_FILES['files']['tmp_name'][$i];
        $file_name = basename($_FILES['files']['name'][$i]);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($file_tmp, $file_path)) {
            echo "File '$file_name' uploaded successfully. <br/>";
        } else {
            echo "Error while uploading the file '$file_name'. <br/>";
        }
    }       
}
