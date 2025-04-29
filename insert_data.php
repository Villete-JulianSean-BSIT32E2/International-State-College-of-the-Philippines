<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connect.php';

session_start(); // Start session to persist student ID between steps

// Step 1: Insert into admission
if (isset($_POST['name'])) {
    $stmt = $conn->prepare("INSERT INTO admission (name, bdate, gender, nat, religion, curraddress, province, peraddress, zip, email, city, phoneno)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssss", $_POST['name'], $_POST['bdate'], $_POST['gender'], $_POST['nat'], $_POST['religion'],
                      $_POST['curraddress'], $_POST['province'], $_POST['peraddress'], $_POST['zip'],
                      $_POST['email'], $_POST['city'], $_POST['phoneno']);
    $stmt->execute();

    // Save the newly inserted admission ID
    $_SESSION['student_id'] = $conn->insert_id;

    $stmt->close();
    header("Location: guardianinfo.html");
    exit();
}

// Step 2: Insert into guardian_info
if (isset($_POST['fname'])) {
    $stmt = $conn->prepare("INSERT INTO guardian_info (fname, mname, foccu, moccu, fno, mno, gname, relationship, gno)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $_POST['fname'], $_POST['mname'], $_POST['foccu'], $_POST['moccu'],
                      $_POST['fno'], $_POST['mno'], $_POST['gname'], $_POST['relationship'], $_POST['gno']);
    $stmt->execute();
    $stmt->close();
    header("Location: AcademicInformation.html");
    exit();
}

// Step 3: Insert academic info + file uploads
if (isset($_POST['applying_grade'])) {
    function uploadFile($key) {
        $dir = "uploads/";
        if (!file_exists($dir)) mkdir($dir, 0777, true);
        $name = basename($_FILES[$key]["name"]);
        $path = $dir . time() . "_" . $name;
        move_uploaded_file($_FILES[$key]["tmp_name"], $path);
        return $path;
    }

    $birth = uploadFile("doc1");
    $form137 = uploadFile("doc2");
    $tor = uploadFile("doc3");
    $good = uploadFile("doc4");
    $honor = uploadFile("doc5");
    $sign = uploadFile("signature");

    $confirmed = isset($_POST['confirm']) && $_POST['confirm'] === "yes" ? 1 : 0;

    $status_std = $_POST['Student_status'] ?? '';
    $student_id = $_SESSION['student_id'] ?? null;

    if ($student_id) {
        // Insert into student_documents with correct admission ID
        $stmt = $conn->prepare("INSERT INTO student_documents (id, applying_grade, prevschool, last_grade, Course, status_std, birth_cert_path, form137_path, tor_path, good_moral_path, honorable_dismissal_path, signature_path, sigdate, confirmed)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssssssssi",
                          $student_id, $_POST['applying_grade'], $_POST['prevschool'], $_POST['last_grade'], $_POST['Course'],
                          $status_std, $birth, $form137, $tor, $good, $honor, $sign, $_POST['sigdate'], $confirmed);
        $stmt->execute();
        $stmt->close();

        // Insert into status-specific table (transferee, old, irregular, new)
        $status = strtolower($status_std);
        $valid_statuses = ['transferee', 'old', 'irregular', 'new'];
        if (in_array($status, $valid_statuses)) {
            // Get student's name for the insert
            $name_result = $conn->query("SELECT name FROM admission WHERE id = $student_id LIMIT 1");
            $name_row = $name_result->fetch_assoc();
            $student_name = $name_row['name'] ?? 'Unknown';

            $stmt = $conn->prepare("INSERT INTO `$status` (name) VALUES (?)");
            $stmt->bind_param("s", $student_name);
            $stmt->execute();
            $stmt->close();
        }

        // Clear session ID
        unset($_SESSION['student_id']);

        echo "<script>alert('Submitted successfully!'); window.location.href='Post-AdmissionPreview.php';</script>";
    } else {
        echo "<script>alert('Error: No associated student ID found.'); window.location.href='index.php';</script>";
    }
}

$conn->close();
?>
