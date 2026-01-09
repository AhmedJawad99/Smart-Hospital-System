CREATE DATABASE IF NOT EXISTS smart_hospital;
USE smart_hospital;

DROP TABLE IF EXISTS patients;

CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    gender ENUM('Male', 'Female') DEFAULT 'Male',
    phone VARCHAR(20),
    triage_level ENUM('Normal', 'Critical') DEFAULT 'Normal',
    status ENUM('Waiting', 'With_Doctor', 'In_Lab', 'Pharmacy', 'Discharged') DEFAULT 'Waiting',
    doctor_diagnosis TEXT,
    lab_request TEXT,
    lab_result TEXT,
    medication TEXT,
    pharmacy_instructions TEXT,
    blood_type VARCHAR(5),
    allergies TEXT,
    nurse_call BOOLEAN DEFAULT 0,
    heart_rate INT DEFAULT 0,
    temperature DECIMAL(4,1) DEFAULT 0.0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS maintenance_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    device_name VARCHAR(255) NOT NULL,
    patient_id INT,
    usage_details TEXT,
    result TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
