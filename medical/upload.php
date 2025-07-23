<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['files']) && isset($_POST['path'])) {
        // Sanitize the path input
        $path = $_POST["path"];
        $target_dir = rtrim($path, '/') . '/';

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // Check if the directory is writable
        if (!is_writable($target_dir)) {
            echo json_encode(['status' => 'error', 'message' => 'Target directory is not writable.']);
            exit;
        }

        $allowedTypes = ['jpg', 'png', 'jpeg', 'gif', 'pdf', 'txt', 'doc', 'docx'];
        $allowedMimeTypes = [
            'image/jpeg', 'image/png', 'image/gif',
            'application/pdf', 'text/plain',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        $responses = [];
        foreach ($_FILES['files']['name'] as $index => $fileName) {
            $target_file = $target_dir . basename($fileName);
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $fileMimeType = mime_content_type($_FILES['files']['tmp_name'][$index]);
            $fileSize = $_FILES['files']['size'][$index];

            // Check if file already exists
            if (file_exists($target_file)) {
                $responses[] = ['status' => 'error', 'message' => "File already exists: $fileName"];
                continue;
            }

            // Check file size (50MB limit)
            if ($fileSize > 50000000) {
                $responses[] = ['status' => 'error', 'message' => "File is too large: $fileName"];
                continue;
            }

            // Validate file type
            if (!in_array($fileType, $allowedTypes) || !in_array($fileMimeType, $allowedMimeTypes)) {
                $responses[] = ['status' => 'error', 'message' => "Invalid file type: $fileName"];
                continue;
            }

            // Move uploaded file
            if (move_uploaded_file($_FILES['files']['tmp_name'][$index], $target_file)) {
                chmod($target_file, 0644);
                $responses = ['status' => 'success', 'message' => "Uploaded: $fileName"];
            } else {
                $responses = ['status' => 'error', 'message' => "Error uploading: $fileName"];
            }
        }

        echo json_encode($responses);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No files or path specified.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
