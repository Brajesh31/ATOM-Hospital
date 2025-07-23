<?php
header('Content-Type: application/json');

// Database connection

try {
    $conn = new SQLite3('../database/clinic.db');
    $arc = new SQLite3('../database/archive.db');
    $mid = new SQLite3('../database/laboratory.db');
    $db3 = new SQLite3('../database/users.db');
} catch (PDOException $e) {
    die(json_encode(['error' => 'Database connection failed: '. $e->getMessage()]));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $patient_id = $_POST['id']?? null;
    $doctor_id = $_POST['name']?? null;
    switch ($action) {
        case 'get_doctor_name':
            $assist_name = $_POST['assist_name']?? null;
            $stmt = $db3->prepare('SELECT * FROM assist WHERE name_assist = :assist_name');
            $stmt->bindValue(':assist_name', $assist_name, SQLITE3_TEXT);
            $result = $stmt->execute();
            $doctor_name = $result->fetchArray(SQLITE3_ASSOC)['name_doctor'];
            echo json_encode(['doctor_name' => $doctor_name]);

            break;
        case 'get_patients':
            $doctor_id = $_POST['doctor_name']?? null;
            $stmt = $conn->prepare('SELECT * FROM patients WHERE doctor_name = :doctor_id');
            $stmt->bindValue(':doctor_id', $doctor_id, SQLITE3_TEXT);
            $result = $stmt->execute();
            $patients = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $patients[] = $row;
            }
            echo json_encode($patients);
            break;
            case 'archive':
                $status = $_POST['status'];
                $stmt = $conn->prepare('SELECT * FROM patients WHERE id = :patient_id AND doctor_name = :doctor_id');
                $stmt->bindValue(':patient_id', $patient_id, SQLITE3_INTEGER);
                $stmt->bindValue(':doctor_id', $doctor_id, SQLITE3_TEXT);
                $result = $stmt->execute();
                $patient = $result->fetchArray(SQLITE3_ASSOC);
    
                if ($patient) {
                    $stmt = $arc->prepare('INSERT INTO archive (name, age, condition, phone_number, doctor_name, patient_gender, room, created_at, end_at, statu) VALUES (:name, :age, :condition, :phone_number, :doctor_name, :patient_gender, :room, :created_at, :end_at, :statu)');
                    $stmt->bindValue(':name', $patient['name'], SQLITE3_TEXT);
                    $stmt->bindValue(':age', $patient['age'], SQLITE3_INTEGER);
                    $stmt->bindValue(':condition', $patient['condition'], SQLITE3_TEXT);
                    $stmt->bindValue(':phone_number', $patient['phone_number'], SQLITE3_TEXT);
                    $stmt->bindValue(':doctor_name', $doctor_id, SQLITE3_TEXT);
                    $stmt->bindValue(':patient_gender', $patient['patient_gender'], SQLITE3_TEXT);
                    $stmt->bindValue(':room', $patient['room'], SQLITE3_TEXT);
                    $stmt->bindValue(':created_at', $patient['created_at'], SQLITE3_TEXT);
                    $stmt->bindValue(':end_at', date('Y-m-d H:i:s'), SQLITE3_TEXT);
                    $stmt->bindValue(':statu', $status, SQLITE3_TEXT);
    
                    if ($stmt->execute()) {
                        $stmt = $conn->prepare('DELETE FROM patients WHERE id = :patient_id AND doctor_name = :doctor_id');
                        $stmt->bindValue(':patient_id', $patient_id, SQLITE3_INTEGER);
                        $stmt->bindValue(':doctor_id', $doctor_id, SQLITE3_TEXT);
                        $stmt->execute();
                        echo json_encode(['status' => 'success']);
                    } else {
                        echo json_encode(['status' => 'error']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Patient not found']);
                }
                break;
    
            case 'view_patient':
                $stmt = $conn->prepare('SELECT * FROM patients WHERE id = :patient_id AND doctor_name = :doctor_id');
                $stmt->bindValue(':patient_id', $patient_id, SQLITE3_INTEGER);
                $stmt->bindValue(':doctor_id', $doctor_id, SQLITE3_TEXT);
                $result = $stmt->execute();
                $patient = $result->fetchArray(SQLITE3_ASSOC);
    
                $mide = $mid->prepare('SELECT * FROM medical WHERE path = :path AND doctor_name = :doctor_id');
                $mide->bindValue(':doctor_id', $doctor_id, SQLITE3_TEXT);
                $mide->bindValue(':path', $patient["code"], SQLITE3_TEXT);
                $mid_result = $mide->execute();
                $mid_patient = $mid_result->fetchArray(SQLITE3_ASSOC);
                if ($mid_patient && $mid_patient["orderd"] == "false") {
                    echo json_encode(['status' => 'success', 'path' => $patient["code"]]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Patient midecal tests not found']);
                }
                break;
            case "order_tests":
                $id = $_POST['id'];
                $name = $_POST['name'];
                $test = $_POST['test'];
                $stmt = $conn->prepare('SELECT * FROM patients WHERE id = :id AND doctor_name = :doctor_id');
                $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
                $stmt->bindValue(':doctor_id', $name, SQLITE3_TEXT);
                $result = $stmt->execute();
                $patient = $result->fetchArray(SQLITE3_ASSOC);
                if ($patient) {
                $checkStmt = $mid->prepare('SELECT * FROM medical WHERE name = :name AND path = :path');
                $checkStmt->bindValue(':name', $patient["name"], SQLITE3_TEXT);
                $checkStmt->bindValue(':path', $patient["code"], SQLITE3_TEXT);
                $checkResult = $checkStmt->execute();
                $existingRecord = $checkResult->fetchArray(SQLITE3_ASSOC);
                if ($existingRecord) {
                    if ($existingRecord["orderd"] == "true") {
                    echo json_encode(['status' => 'wait', 'message' => '']);
                    } else {
                    $updateStmt = $mid->prepare('UPDATE medical SET orderd = :orderd WHERE name = :name AND path = :path');
                    $updateStmt->bindValue(':orderd', "true", SQLITE3_TEXT);
                    $updateStmt->bindValue(':name', $patient["name"], SQLITE3_TEXT);
                    $updateStmt->bindValue(':path', $patient["code"], SQLITE3_TEXT);
                    if ($updateStmt->execute()) {
                        echo json_encode(['status' => 'success', 'message' => 'Order updated']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to update order']);
                    }
                    }
                } else {
                    $insertStmt = $mid->prepare('INSERT INTO medical (name, path, doctor_name,test, orderd) VALUES (:name, :path, :doctor_name,:test ,:orderd)');
                    $insertStmt->bindValue(':name', $patient["name"], SQLITE3_TEXT);
                    $insertStmt->bindValue(':path', $patient["code"], SQLITE3_TEXT);
                    $insertStmt->bindValue(':doctor_name', $name, SQLITE3_TEXT);
                    $insertStmt->bindValue(':test', $test, SQLITE3_TEXT);
                    $insertStmt->bindValue(':orderd', "true", SQLITE3_TEXT);
                    if ($insertStmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Order created']);
                    } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to create order']);
                    }
                }
                } else {
                echo json_encode(['status' => 'error', 'message' => 'Patient not found']);
                }
                break;
            default:
                echo json_encode(['error' => 'Invalid request']);
                break;
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
$conn->close();
$arc->close();
$mid->close();
$db3->close();
// SQLite3 connection for medical record

?>