<?php

$db = new SQLite3("../database/clinic.db");
$arc = new SQLite3("../database/archive.db");
$user = new SQLite3('../database/users.db');

$db->exec("CREATE TABLE IF NOT EXISTS patients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    age INTEGER,
    condition TEXT,
    phone_number TEXT,
    doctor_name TEXT,
    patient_gender TEXT,
    room TEXT,
    created_at TEXT,
    code TEXT
)");
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

function generateRandomCode($length = 30) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#$%@';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'adduser') {
    $name = $_POST['patient_name'];
    $age = $_POST['patient_age'];
    $condition = $_POST['condition'];
    $phone_number = $_POST['phone_number'];
    $doctor_name = $_POST['doctor_name'];
    $patient_gender = $_POST['patient_gender'];
    $room = $_POST['room'];
    $code = generateRandomCode();

    $stmt = $db->prepare("INSERT INTO patients (name, age, condition, phone_number, doctor_name, patient_gender, room, created_at, code) VALUES (:name, :age, :condition, :phone_number, :doctor_name, :patient_gender, :room, :created_at, :code)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':age', $age, SQLITE3_INTEGER);
    $stmt->bindValue(':condition', $condition, SQLITE3_TEXT);
    $stmt->bindValue(':phone_number', $phone_number, SQLITE3_TEXT);
    $stmt->bindValue(':doctor_name', $doctor_name, SQLITE3_TEXT);
    $stmt->bindValue(':patient_gender', $patient_gender, SQLITE3_TEXT);
    $stmt->bindValue(':created_at', date('Y-m-d H:i:s'), SQLITE3_TEXT);
    $stmt->bindValue(':room', $room, SQLITE3_TEXT);
    $stmt->bindValue(':code', $code, SQLITE3_TEXT);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to add patient']);
    }
} elseif($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getpatients'){
    $stmt = $db->query("SELECT * FROM patients");
    $patients = [];
    while($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $patients[] = $row;
    }
    echo json_encode($patients);
} elseif($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getarchive'){
    $stmt = $arc->query("SELECT * FROM archive ORDER BY created_at DESC");
    $archived_patients = [];
    while($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $archived_patients[] = $row;
    }
    echo json_encode($archived_patients);

} elseif($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_patient'){
    $id = $_POST['id'];
    $stmt = $db->prepare("DELETE FROM patients WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    if($stmt->execute()){
        echo json_encode(['success' => true],);
    }else{
        echo json_encode(['success' => false]);
    }

} elseif($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'setarchive'){
    $id = $_POST['id'];
    $status = $_POST['statu'];

    $stmt = $db->prepare("SELECT * FROM patients WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $patient = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    $stmt = $arc->prepare("INSERT INTO archive (name, age, condition, phone_number, doctor_name, patient_gender, room, created_at, code, end_at, statu) VALUES (:name, :age, :condition, :phone_number, :doctor_name, :patient_gender, :room, :created_at, :code, :end_at, :statu)");
    $stmt->bindValue(':name', $patient['name'], SQLITE3_TEXT);
    $stmt->bindValue(':age', $patient['age'], SQLITE3_INTEGER);
    $stmt->bindValue(':condition', $patient['condition'], SQLITE3_TEXT);
    $stmt->bindValue(':phone_number', $patient['phone_number'], SQLITE3_TEXT);
    $stmt->bindValue(':doctor_name', $patient['doctor_name'], SQLITE3_TEXT);
    $stmt->bindValue(':patient_gender', $patient['patient_gender'], SQLITE3_TEXT);
    $stmt->bindValue(':room', $patient['room'], SQLITE3_TEXT);
    $stmt->bindValue(':created_at', $patient['created_at'], SQLITE3_TEXT);
    $stmt->bindValue(':end_at', date('Y-m-d H:i:s'), SQLITE3_TEXT);
    $stmt->bindValue(':code', $patient['code'], SQLITE3_TEXT);
    $stmt->bindValue(':statu', $status, SQLITE3_TEXT);
    if($stmt->execute()){
        $stmt = $db->prepare("DELETE FROM patients WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

}elseif($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getDoctors'){
    $stmt = $user->query("SELECT id,name FROM users WHERE role = 'Doctor' ORDER BY name ASC");
    $doctors = [];
    while($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $doctors[] = $row;
    }
    echo json_encode($doctors); 
}elseif($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getPatient'){
    $id = $_POST['id'];
    $stmt = $db->prepare("SELECT * FROM patients WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $patient = $result->fetchArray(SQLITE3_ASSOC);
    echo json_encode($patient);
}elseif($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updatePatient'){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $condition = $_POST['condition'];
    $phone = $_POST['phone'];
    $doctor = $_POST['doctor'];
    $room = $_POST['room'];

    $stmt = $db->prepare("UPDATE patients SET name = :name, age = :age, patient_gender = :gender, condition = :condition, phone_number = :phone, doctor_name = :doctor, room = :room WHERE id = :id");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':age', $age, SQLITE3_INTEGER);
    $stmt->bindValue(':gender', $gender, SQLITE3_TEXT);
    $stmt->bindValue(':condition', $condition, SQLITE3_TEXT);
    $stmt->bindValue(':phone', $phone, SQLITE3_TEXT);
    $stmt->bindValue(':doctor', $doctor, SQLITE3_TEXT);
    $stmt->bindValue(':room', $room, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    if($stmt->execute()){
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}elseif($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'search'){
    $name = $_POST["name"];
    $room = $_POST["room"];
    $doctor = $_POST["doctor"];
    $stmt = $db->prepare("SELECT * FROM patients WHERE name LIKE :name or room LIKE :room or doctor_name LIKE :doctor ORDER BY created_at DESC");
    $stmt->bindValue(':name', "%".$name."%", SQLITE3_TEXT);
    $stmt->bindValue(':room', "%".$room."%", SQLITE3_TEXT);
    $stmt->bindValue(':doctor', "%".$doctor."%", SQLITE3_TEXT);
    $result = $stmt->execute();
    $patients = [];
    while($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $patients[] = $row;
    }
    echo json_encode($patients);
}

?>