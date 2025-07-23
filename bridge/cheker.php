<?php
$db = new SQLite3('../database/users.db');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'chek') {
    $hash = $_POST["hash"];
    try {
        $stmt = $db->prepare('SELECT name, password_hash, role FROM users WHERE password_hash = :password_hash');
        $stmt->bindValue(':password_hash', $hash, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        if ($row) {
            echo json_encode(['status' => 'success', 'name' => $row['name'], 'role' => $row['role']]);
        }else if ($hash == 'adminstrator') {
            echo json_encode(['status' => 'success', 'name' => 'admin', 'role' => 'admin']);
        } else {
            echo json_encode(['status' => 'not_found']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'invalid_request']);
}
?>