<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $dir = $_POST['dir'];

    if ($action === 'get') {
        // Perform your logic to get data based on $dir
        // For example, let's assume you want to list files in the directory
        $response = [];
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file!== '.' && $file!== '..') {
                $response[] = $file;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($response);

    } 
} else {
    // Handle invalid request method
    $response['error'] = 'Invalid request method';
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>