<?php
include 'connect.php';

// Helper to sanitize inputs
function clean_input($conn, $data) {
    return $conn->real_escape_string(trim($data));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // --- 1) Gather & sanitize POST data ---
    $admission_id   = (int) $_POST['admission_id'];
    $guardian_id    = (int) $_POST['guardian_id'];
    $name           = clean_input($conn, $_POST['name']);
    $bdate          = clean_input($conn, $_POST['bdate']);
    $gender         = clean_input($conn, $_POST['gender']);
    $religion       = clean_input($conn, $_POST['religion']);
    $nat            = clean_input($conn, $_POST['nat']);
    $curraddress    = clean_input($conn, $_POST['curraddress']);
    $peraddress     = clean_input($conn, $_POST['peraddress']);
    $city           = clean_input($conn, $_POST['city']);
    $province       = clean_input($conn, $_POST['province']);
    $zip            = clean_input($conn, $_POST['zip']);
    $phoneno        = clean_input($conn, $_POST['phoneno']);
    $email          = clean_input($conn, $_POST['email']);

    $mname          = clean_input($conn, $_POST['mname']);
    $moccu          = clean_input($conn, $_POST['moccu']);
    $mno            = clean_input($conn, $_POST['mno']);
    $fname          = clean_input($conn, $_POST['fname']);
    $foccu          = clean_input($conn, $_POST['foccu']);
    $fno            = clean_input($conn, $_POST['fno']);
    $gname          = clean_input($conn, $_POST['gname']);
    $relationship   = clean_input($conn, $_POST['relationship']);
    $gno            = clean_input($conn, $_POST['gno']);

    $prevschool     = clean_input($conn, $_POST['prevschool']);
    $last_grade     = clean_input($conn, $_POST['last_grade']);
    $applying_grade = clean_input($conn, $_POST['applying_grade']);
    $Course         = clean_input($conn, $_POST['Course']);
    $new_status     = clean_input($conn, $_POST['status_std']); // field name in form

    // --- 2) Update the admission record ---
    $conn->query("
        UPDATE admission SET
            name         = '$name',
            bdate        = '$bdate',
            gender       = '$gender',
            religion     = '$religion',
            nat          = '$nat',
            curraddress  = '$curraddress',
            peraddress   = '$peraddress',
            city         = '$city',
            province     = '$province',
            zip          = '$zip',
            phoneno      = '$phoneno',
            email        = '$email'
        WHERE id = $admission_id
    ");

    // --- 3) Update the guardian_info record ---
    $conn->query("
        UPDATE guardian_info SET
            mname = '$mname',
            moccu = '$moccu',
            mno   = '$mno',
            fname = '$fname',
            foccu = '$foccu',
            fno   = '$fno',
            gname = '$gname',
            relationship = '$relationship',
            gno   = '$gno'
        WHERE id = $guardian_id
    ");

    // --- 4) Update the student_documents record ---
    $conn->query("
        UPDATE student_documents SET
            prevschool     = '$prevschool',
            last_grade     = '$last_grade',
            applying_grade = '$applying_grade',
            Course         = '$Course',
            status_std     = '$new_status'
        WHERE id = $admission_id
    ");

    // --- 5) Refresh membership in the four status tables ---
    $statusTables = ['new', 'transferee', 'irregular', 'old'];

    // Remove from all
    foreach ($statusTables as $table) {
        $conn->query("DELETE FROM `$table` WHERE name = '$name'");
    }

    // Insert into the selected one
    $selected = strtolower($new_status);
    if (in_array($selected, $statusTables)) {
        $conn->query("INSERT INTO `$selected` (name) VALUES ('$name')");
    }

    // --- 6) Redirect back to dashboard ---
    header("Location: admission.php");
    exit();
}
