<?php
session_start();

// Include database connection
require_once 'connect.php';

// Check if all required session data exists
if (!isset($_SESSION['full_name'])) {
    header('Location: Admission-AddStudent.php');
    exit();
}

try {
    // First, check if the connection is valid
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Set default value for student_type if not set
    $_SESSION['student_type'] = isset($_SESSION['student_type']) ? strtolower($_SESSION['student_type']) : 'new';
    if (!in_array($_SESSION['student_type'], ['new', 'old', 'irregular'])) {
        $_SESSION['student_type'] = 'new'; // Force valid value
    }

    // Prepare SQL statement for main admission data
    $sql = "
        INSERT INTO tbladmission_addstudent (
            full_name, birthdate, gender, nationality, religion, address, 
            province, zip, city, email, phone, photo, 
            fathers_name, fathers_occupation, father_contact, 
            mothers_name, mothers_occupation, mother_contact, 
            guardian_name, guardian_relationship, guardian_contact, 
            applying_grade, prevschool, last_grade, course, student_type, 
            confirm, signature_path, sigdate
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?
        )
    ";

    $stmt = $conn->prepare($sql);
    
    // Check if prepare() failed
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $bound = $stmt->bind_param(
        "sssssssssssssssssssssssssssss",
        $_SESSION['full_name'],
        $_SESSION['date'],
        $_SESSION['gender'],
        $_SESSION['nationality'],
        $_SESSION['religion'],
        $_SESSION['address'],
        $_SESSION['province'],
        $_SESSION['zip'],
        $_SESSION['city'],
        $_SESSION['email'],
        $_SESSION['phone'],
        isset($_SESSION['photo']) ? $_SESSION['photo'] : null,
        $_SESSION['fathers_name'],
        $_SESSION['fathers_occupation'],
        $_SESSION['father_contact'],
        $_SESSION['mothers_name'],
        $_SESSION['mothers_occupation'],
        $_SESSION['mother_contact'],
        $_SESSION['guardian_name'],
        $_SESSION['guardian_relationship'],
        $_SESSION['guardian_contact'],
        $_SESSION['applying_grade'],
        $_SESSION['prevschool'],
        $_SESSION['last_grade'],
        $_SESSION['Course'],
        $_SESSION['student_type'],
        $_SESSION['confirm'],
        isset($_SESSION['signature']) ? $_SESSION['signature'] : null,
        $_SESSION['sigdate']
    );

    if (!$bound) {
        throw new Exception("Bind param failed: " . $stmt->error);
    }

    // Execute the main admission insertion
    if ($stmt->execute()) {
        $Admission_ID = $stmt->insert_id;
        
        // Insert into student type table (ENUM approach)
        $typeStmt = $conn->prepare("
            INSERT INTO tbladmission_studenttype 
            (Admission_ID, StudentType) 
            VALUES (?, ?)
        ");

        if ($typeStmt === false) {
            throw new Exception("Prepare failed for student type: " . $conn->error);
        }

        $typeStmt->bind_param("is", $Admission_ID, $_SESSION['student_type']);

        if (!$typeStmt->execute()) {
            throw new Exception("Error inserting student type: " . $typeStmt->error);
        }

        $typeStmt->close();
        
        // Clear session data
        session_unset();
        session_destroy();
        
        // Redirect to success page
        header('Location: admission_success.php');
        exit();
    } else {
        throw new Exception("Error inserting student data: " . $stmt->error);
    }
    
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    // Log the error for debugging
    error_log("Admission Error: " . $e->getMessage());
    
    $_SESSION['error'] = "An error occurred during submission: " . $e->getMessage();
    header('Location: admission_error.php');
    exit();
}