<?php

session_start();
$db = new SQLite3('../database/users.db');
$db2 = new SQLite3('../database/clinic.db');
$arc = new SQLite3("../database/archive.db");

// Create users table if not exists
create_UsersTable($db);
create_WithTable($db);
create_archiveTable($arc);

function create_UsersTable($db) {
    $query = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        password TEXT NOT NULL,
        password_hash TEXT NOT NULL,
        status TEXT NOT NULL,
        role TEXT NOT NULL
    )";
    $db->exec($query);
}

function create_WithTable($db) {
    $query = "CREATE TABLE IF NOT EXISTS assist (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name_doctor TEXT NOT NULL,
        name_assist TEXT NOT NULL
    )";
    $db->exec($query);
}

function create_archiveTable($arc) {
    $arc->exec("CREATE TABLE IF NOT EXISTS archive (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        age INTEGER,
        condition TEXT,
        phone_number TEXT,
        doctor_name TEXT,
        patient_gender TEXT,
        room TEXT,
        created_at TEXT,
        code TEXT,
        end_at TEXT,
        statu TEXT
    )");
}

// Handle CRUD actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'get_users':
            get_users($db);
            break;
        case 'get_password':
            get_password($db,$_POST['id']);
            break;
        case 'add':
            addUser($db, $_POST);
            break;
        case 'delete':
            deleteUser($db, $_POST['id']);
            break;
        case "deletepation":
            deletePation($db2, $_POST['id']);
            break;
        case 'update':
            updateUser($db, $_POST);
            break;
        case 'search':
            searchUsers($db, $_POST);
            break;
        case 'chart':
            chart($db, $db2,$arc);
            break;
        case "get_pation":
            getPatients($db2);
            break;
        case "getassist":
            getassistanddoctor($db);
            break;
        case "get_assist":
            getAssist($db);
            break;
        case "removeAssist":
            removeAssist($db, $_POST['id']);
            break;
        case "addassist":
            addassist($db, $_POST);
            break;
        case "archive":
            Pationarchive($arc);
            break;
        default:
            echo "Invalid action.";
    }
}

// Fetch all users excluding passwords
function get_users($db) {
    $users = $db->query("SELECT id, name, status, role FROM users");
        $data = [];
        while ($row = $users->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }
    echo json_encode($data);
}

function get_password($db, $id) {
    $stmt = $db->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    $row = $result->fetchArray(SQLITE3_ASSOC);
    if ($row) {
        echo json_encode(["pass" => $row['password']]);
    } else {
        echo json_encode(["error" => "No password found"]);
    }
}

function addUser($db, $data) {
    $name = $data['name'];
    $password = $data['password'];
    $password_hash = password_hash($data['password'], PASSWORD_BCRYPT); // Hash the password
    $status = $data['status'];
    $role = $data['role'];
    
    // Find the first available ID
    $result = $db->query("SELECT id FROM users ORDER BY id ASC");
    $availableId = 1;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        if ($row['id'] != $availableId) {
            break;
        }
        $availableId++;
    }

    $stmt = $db->prepare("INSERT INTO users (id, name, password, password_hash, status, role) VALUES (:id, :name, :password, :password_hash, :status, :role)");
    $stmt->bindValue(':id', $availableId, SQLITE3_INTEGER);
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':password', $password, SQLITE3_TEXT);
    $stmt->bindValue(':password_hash', $password_hash, SQLITE3_TEXT);
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    $stmt->bindValue(':role', $role, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo "User added successfully with ID: $availableId";
    } else {
        echo "Error adding user.";
    }
}

function deleteUser($db, $id) {
    $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    if ($stmt->execute()) {
        $users = $db->query("SELECT id, name, status, role FROM users");
        $data = [];
        while ($row = $users->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }

        echo json_encode(['success' => true,'massege' => json_encode($data)],);
    } else {
        echo "Error deleting user.";
    }
}

function updateUser($db, $data) {
    $id = $data['id'];
    $name = $data['name'];
    $status = $data['status'];
    $role = $data['role'];

    $stmt = $db->prepare("UPDATE users SET name = :name, status = :status, role = :role WHERE id = :id");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    $stmt->bindValue(':role', $role, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    if ($stmt->execute()) {
        $users = $db->query("SELECT id, name, status, role FROM users");
        $data = [];
        while ($row = $users->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }

        echo json_encode(['success' => true,'massege' => json_encode($data)],);
    } else {
        echo "Error updating user.";
    }
}

function searchUsers($db, $data) {
    $name = $data['name'] ?? '';
    $status = $data['status'] ?? '';
    $role = $data['role'] ?? '';

    $query = "SELECT id, name, status, role FROM users WHERE 1=1";
    if (!empty($name)) {
        $query .= " AND name LIKE :name";
    }
    if (!empty($status)) {
        $query .= " AND status = :status";
    }
    if (!empty($role)) {
        $query .= " AND role = :role";
    }

    $stmt = $db->prepare($query);
    if (!empty($name)) {
        $stmt->bindValue(':name', '%' . $name . '%', SQLITE3_TEXT);
    }
    if (!empty($status)) {
        $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    }
    if (!empty($role)) {
        $stmt->bindValue(':role', $role, SQLITE3_TEXT);
    }
    $result = $stmt->execute();

    $users = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $users[] = $row;
    }

    echo json_encode($users);
    exit;
}

function getPatients($db2){
    $stmt = $db2->prepare("SELECT id, name,age, condition, phone_number, doctor_name, patient_gender, room, created_at FROM patients");
    $result = $stmt->execute();
    $rows = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }
    echo json_encode($rows);
}

function deletePation($db, $id) {
    $stmt = $db->prepare("DELETE FROM patients WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    if ($stmt->execute()) {
        $patients = $db->query("SELECT id, name,age, condition, phone_number, doctor_name, patient_gender, room, created_at FROM patients");
        $data = [];
        while ($row = $patients->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }
        echo json_encode(['success' => true,'massege' => json_encode($data)],);
    } else {
        echo "Error deleting patient.";
    }
}
function getAssist($db3){
    $stmt = $db3->prepare("SELECT * FROM assist");
    $result = $stmt->execute();
    $rows = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }
    echo json_encode($rows);

}
function getassistanddoctor($db) {
    $stmt = $db->prepare("SELECT name, role FROM users WHERE role = 'Doctor' OR role = 'Doctor_assist'");
    $result = $stmt->execute();
    $rows = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = ['role' => $row['role'], 'name' => $row['name']];
    }
    echo json_encode($rows);
}

function addassist($db, $data) {
    $doctor = $data['Doctor_name'];
    $assist = $data['assist_name'];
    $stmt = $db->prepare("INSERT INTO assist (name_doctor, name_assist) VALUES (:doctor, :assist)");
    $stmt->bindValue(':doctor', $doctor, SQLITE3_TEXT);
    $stmt->bindValue(':assist', $assist, SQLITE3_TEXT);
    if($stmt->execute()){
        $stmt2 = $db->prepare("SELECT * FROM assist");
        $result = $stmt2->execute();
        $rows = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $row;
        }
        echo json_encode(["success" => true,"mas"=> json_encode($rows)]);
    }
    $stmt->close();
    
}

function removeAssist($db, $data) {
    $id = $_POST['id'];
    $stmt = $db->prepare("DELETE FROM assist WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    if ($stmt->execute()) {
        $stmt2 = $db->prepare("SELECT * FROM assist");
        $result = $stmt2->execute();
        $rows = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $row;
        }
        echo json_encode(["success" => true,"mas"=> json_encode($rows)]);
    }
    $stmt->close();
}

function Pationarchive($arc){
    $stmt = $arc->prepare("SELECT * FROM archive");
    $result = $stmt->execute();
    $rows = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }
    echo json_encode($rows);
}

function chart($db, $db2,$arc) {
    $roles = [];
    $result = $db->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
    $patientCount = $db2->querySingle("SELECT COUNT(*) as count FROM patients");
    $arccount = $arc->querySingle("SELECT COUNT(*) as count FROM archive");

    $aliveCount = $arc->querySingle("SELECT COUNT(statu) as count FROM archive WHERE statu = 'alive'");
    $deadCount = $arc->querySingle("SELECT COUNT(statu) as count FROM archive WHERE statu = 'dead'");
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $roles[$row['role']] = $row['count'];
    }
    $roles['patients'] = $patientCount;
    $roles['archive'] = $arccount;
    $roles["alive"] = $aliveCount;
    $roles['dead'] = $deadCount;

    header('Content-Type: application/json');
    echo json_encode($roles);
}


?>