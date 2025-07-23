<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);

    try {
        // Connect to SQLite database
        $db = new SQLite3('../database/users.db');

        // Check if user exists
        $stmt = $db->prepare('SELECT name,password,password_hash, role,Status FROM users WHERE name = :name');
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $result = $stmt->execute();
        $user = $result->fetchArray(SQLITE3_ASSOC);


        function get_role($user){
            if ($user['role'] == 'Admin') {
                return "admin/admin.html";
            } 
            elseif($user['role'] == 'Doctor'){
                return "workspace/patient.html";
            }
            elseif($user['role'] == 'Doctor_assist'){
                return "workspace/assistpatient.html";
            }
            elseif($user['role'] == 'Nurse'){
                return "workspace/nurse.html";
            }
            elseif($user['role'] == 'receptionist'){
                return "workspace/receptionist.html";
            }
            elseif($user['role'] == 'laboratory'){
                return "workspace/laboratory.html";
            } else {
                return "index.html";    
            }
        }

        if ($user && $password == $user['password'] && $user['status'] == 'Active'){  // Check password and status{
            echo json_encode([
                "Name" => $user["name"],
                "password" => $password,
                "role" => $user["role"],
                "password_hash" => $user['password_hash'],
                "dir" => get_role($user)
            ]);
        }else if($user && $password == $user['password'] && $user['status'] == 'inactive'){
            echo 'account_inactive';
        }else if($name == 'admin' && $password == "admin"){
            echo json_encode([
                "Name" => 'admin',
                "password" => 'admin',
                "role" => 'admin',
                "password_hash" => 'adminstrator',
                "dir" => 'admin/admin.html'
            ]);
        }
        else {
            echo "Invalid username or password.";
        }

        // Close the statement and database connection
        $stmt->close();
        $db->close();
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
}
