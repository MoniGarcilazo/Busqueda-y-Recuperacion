<?php 
$upload_dir = "uploads/files/";

// Creatin the folder if does not exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (isset($_FILES['files'])) {
    $file_count = count($_FILES['files']['name']);

    echo $file_count;

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

    $inverted_index = generate_inverted_index($upload_dir);
    echo "<h2>Inverted index: <h2 />";
    echo "<pre>";
    print_r($inverted_index);
    echo "</pre>";
}

function generate_inverted_index($dir): mixed {
    $inverted_index = [];

    $files = glob($dir . "*.txt");
    $space = '';

    foreach ($files as $file_path) {
        $file_name = basename($file_path);
        $content = file_get_contents($file_path);

        $content = strtolower($content);
        $content = preg_replace('/[^\p{L}\p{N}\s]/u', $space, $content);

        $words = explode(' ', $content);

        foreach ($words as $word) {
            if (empty($word)) {
                continue;
            }

            if (!isset($inverted_index[$word])) {
                $inverted_index[$word] = [];
            }

            if (in_array($file_name, $inverted_index[$word])) {
                $inverted_index[$word][] = $file_name;
            }
        }
    }

    return $inverted_index;
}
