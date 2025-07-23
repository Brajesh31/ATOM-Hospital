<?php
$db = new SQLite3("../database/clinic.db");
$mid = new SQLite3("../database/laboratory.db");

$mid->exec("CREATE TABLE IF NOT EXISTS medical (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    doctor_name TEXT,
    test TEXT,
    orderd TEXT,
    path TEXT
)");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case "get_patients":
            $stmt = $mid->prepare("SELECT * FROM medical WHERE orderd = :orderd");
            $stmt->bindValue(':orderd', 'true', SQLITE3_TEXT);
            $result = $stmt->execute();
            $patients = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $stmt2 = $db->prepare("SELECT age,patient_gender FROM patients WHERE code = :path");
                $stmt2->bindValue(':path', $row['path'], SQLITE3_TEXT);
                $result2 = $stmt2->execute();
                while ($patientRow = $result2->fetchArray(SQLITE3_ASSOC)) {
                    $patients[] = array_merge($row, $patientRow);
                }
            }
            echo json_encode($patients);
            break;
        case "finesh":
            $stmt = $mid->prepare("UPDATE medical SET orderd = :orderd WHERE path = :id");
            $stmt->bindValue(':orderd', 'false', SQLITE3_TEXT);
            $stmt->bindValue(':id', $_POST['id'], SQLITE3_TEXT);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
            break;
        case "search_patient":
            $name = '%' . $_POST["name"] . '%';
            $stmt2 = $mid->prepare("SELECT * FROM medical WHERE name LIKE :name AND orderd = 'true'");
            $stmt2->bindValue(':name', $name, SQLITE3_TEXT);
            $result2 = $stmt2->execute();
            $patients = [];
            while ($row = $result2->fetchArray(SQLITE3_ASSOC)) {
            $stmt3 = $db->prepare("SELECT age, patient_gender FROM patients WHERE code = :path");
            $stmt3->bindValue(':path', $row['path'], SQLITE3_TEXT);
            $result3 = $stmt3->execute();
            while ($patientRow = $result3->fetchArray(SQLITE3_ASSOC)) {
                $patients[] = array_merge($row, $patientRow);
            }
            }
            echo json_encode($patients);
            break;
        default:
            echo "Invalid action";
    }
}





?>


