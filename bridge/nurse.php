<?php

header('Content-Type: application/json');

// Database connection

try {
    $conn = new SQLite3('../database/clinic.db');
    $mid = new SQLite3('../database/laboratory.db');
} catch (PDOException $e) {
    die(json_encode(['error' => 'Database connection failed: '. $e->getMessage()]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case "get_all_patients":
            $stmt = $conn->query('SELECT * FROM patients');
            $patients = [];
            while($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
                $patients[] = $row;
            }
            echo json_encode($patients);
            break;
        case "search_patient":
            $name = '%' . $_POST["name"] . '%';
            $stmt2 = $conn->prepare("SELECT * FROM patients WHERE name LIKE :name");
            $stmt2->bindValue(':name', $name, SQLITE3_TEXT);
            $result2 = $stmt2->execute();
            $patients = [];
            while ($row = $result2->fetchArray(SQLITE3_ASSOC)) {
                $patients[] = $row;
            }
            echo json_encode($patients);
            break;
            
            case 'view_patient':
                $patient_id = $_POST['id'];
                $stmt = $conn->prepare('SELECT code FROM patients WHERE id = :patient_id');
                $stmt->bindValue(':patient_id', $patient_id, SQLITE3_INTEGER);
                $result = $stmt->execute();
                $patient = $result->fetchArray(SQLITE3_ASSOC);
            
                if ($patient) {
                    $med_stmt = $mid->prepare('SELECT orderd FROM medical WHERE path = :path');
                    $med_stmt->bindValue(':path', $patient["code"], SQLITE3_TEXT);
                    $med_result = $med_stmt->execute();
                    $med_patient = $med_result->fetchArray(SQLITE3_ASSOC);
                    
                    if ($med_patient && $med_patient["orderd"] == "false") {
                        echo json_encode(['status' => 'success', 'path' => $patient["code"]]);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Patient medical tests not found']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Patient not found']);
                }
                break;
            case "order_tests":
                $id = $_POST['id'];
                $test = $_POST['test'];
                $stmt = $conn->prepare('SELECT * FROM patients WHERE id = :id');
                $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
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
                    $insertStmt->bindValue(':doctor_name', $patient["doctor_name"], SQLITE3_TEXT);
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
$mid->close();

?>