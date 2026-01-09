<?php
header('Content-Type: application/json');
require 'db.php';

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'add_patient':
            $name = $_POST['name'] ?? '';
            $age = $_POST['age'] ?? '';
            $gender = $_POST['gender'] ?? 'Male';
            $phone = $_POST['phone'] ?? '';
            $triage_level = $_POST['triage_level'] ?? 'Normal';
            
            if ($name && $age) {
                $stmt = $pdo->prepare("INSERT INTO patients (name, age, gender, phone, triage_level, status) VALUES (?, ?, ?, ?, ?, 'Waiting')");
                $stmt->execute([$name, $age, $gender, $phone, $triage_level]);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing data']);
            }
            break;

        case 'fetch_data':
            $stmt = $pdo->query("SELECT * FROM patients ORDER BY created_at ASC");
            $patients = $stmt->fetchAll();
            echo json_encode($patients);
            break;

        case 'fetch_patient':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
            $stmt->execute([$id]);
            $patient = $stmt->fetch();
            echo json_encode($patient ? $patient : ['error' => 'Not found']);
            break;

        case 'update_patient':
            $id = $_POST['id'] ?? 0;
            $status = $_POST['status'] ?? null;
            $diagnosis = $_POST['doctor_diagnosis'] ?? null;
            $lab_req = $_POST['lab_request'] ?? null;
            $lab_res = $_POST['lab_result'] ?? null;
            $meds = $_POST['medication'] ?? null;
            $pharm_inst = $_POST['pharmacy_instructions'] ?? null;
            $blood = $_POST['blood_type'] ?? null;
            $allergies = $_POST['allergies'] ?? null;
            $hr = $_POST['heart_rate'] ?? null;
            $temp = $_POST['temperature'] ?? null;

            $query = "UPDATE patients SET ";
            $params = [];
            
            if ($status) { $query .= "status = ?, "; $params[] = $status; }
            if ($diagnosis) { $query .= "doctor_diagnosis = ?, "; $params[] = $diagnosis; }
            if ($lab_req) { $query .= "lab_request = ?, "; $params[] = $lab_req; }
            if ($lab_res) { $query .= "lab_result = ?, "; $params[] = $lab_res; }
            if ($meds) { $query .= "medication = ?, "; $params[] = $meds; }
            if ($pharm_inst) { $query .= "pharmacy_instructions = ?, "; $params[] = $pharm_inst; }
            if ($blood) { $query .= "blood_type = ?, "; $params[] = $blood; }
            if ($allergies) { $query .= "allergies = ?, "; $params[] = $allergies; }
            if ($hr) { $query .= "heart_rate = ?, "; $params[] = $hr; }
            
            $query = rtrim($query, ", ");
            $query .= " WHERE id = ?";
            $params[] = $id;

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            // Log Device Usage if provided
            $device_name = $_POST['device_name'] ?? null;
            if ($device_name) {
                $usage_details = "Used for Client ID: $id";
                $result_log = $lab_res ?? 'No Result';
                
                $stmtLog = $pdo->prepare("INSERT INTO maintenance_logs (device_name, patient_id, usage_details, result) VALUES (?, ?, ?, ?)");
                $stmtLog->execute([$device_name, $id, $usage_details, $result_log]);
            }
            
            echo json_encode(['success' => true]);
            break;

        case 'fetch_maintenance':
            $stmt = $pdo->query("SELECT m.*, p.name as patient_name FROM maintenance_logs m LEFT JOIN patients p ON m.patient_id = p.id ORDER BY m.created_at DESC");
            echo json_encode($stmt->fetchAll());
            break;

        case 'toggle_nurse_call':
            $id = $_POST['id'] ?? 0;
            $call_val = $_POST['nurse_call'] ?? 0;
            
            $stmt = $pdo->prepare("UPDATE patients SET nurse_call = ? WHERE id = ?");
            $stmt->execute([$call_val, $id]);
            echo json_encode(['success' => true]);
            break;

        case 'reset_system':
            $stmt = $pdo->exec("TRUNCATE TABLE patients");
            echo json_encode(['success' => true]);
            break;

        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
