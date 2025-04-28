<?php
include 'connect.php';

// Sanitize POST data
function clean_input($conn, $data) {
    return $conn->real_escape_string(trim($data));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Admission data
    $admission_id = $_POST['admission_id'];
    $name = clean_input($conn, $_POST['name']);
    $bdate = clean_input($conn, $_POST['bdate']);
    $gender = clean_input($conn, $_POST['gender']);
    $religion = clean_input($conn, $_POST['religion']);
    $nat = clean_input($conn, $_POST['nat']);
    $curraddress = clean_input($conn, $_POST['curraddress']);
    $peraddress = clean_input($conn, $_POST['peraddress']);
    $city = clean_input($conn, $_POST['city']);
    $province = clean_input($conn, $_POST['province']);
    $zip = clean_input($conn, $_POST['zip']);
    $phoneno = clean_input($conn, $_POST['phoneno']);
    $email = clean_input($conn, $_POST['email']);

    // Guardian data
    $guardian_id = $_POST['guardian_id'];
    $mname = clean_input($conn, $_POST['mname']);
    $moccu = clean_input($conn, $_POST['moccu']);
    $mno = clean_input($conn, $_POST['mno']);
    $fname = clean_input($conn, $_POST['fname']);
    $foccu = clean_input($conn, $_POST['foccu']);
    $fno = clean_input($conn, $_POST['fno']);
    $gname = clean_input($conn, $_POST['gname']);
    $relationship = clean_input($conn, $_POST['relationship']);
    $gno = clean_input($conn, $_POST['gno']);

    // Academic data
    $prevschool = clean_input($conn, $_POST['prevschool']);
    $last_grade = clean_input($conn, $_POST['last_grade']);
    $applying_grade = clean_input($conn, $_POST['applying_grade']);
    $new_status = clean_input($conn, $_POST['Student_status']);
    $Course = clean_input($conn, $_POST['Course']);

    // Update admission table
    $conn->query("UPDATE admission SET 
        name='$name', bdate='$bdate', gender='$gender', religion='$religion', nat='$nat',
        curraddress='$curraddress', peraddress='$peraddress', city='$city',
        province='$province', zip='$zip', phoneno='$phoneno', email='$email'
        WHERE id=$admission_id");

    // Update guardian_info table
    $conn->query("UPDATE guardian_info SET 
        mname='$mname', moccu='$moccu', mno='$mno', fname='$fname',
        foccu='$foccu', fno='$fno', gname='$gname', relationship='$relationship', gno='$gno'
        WHERE id=$guardian_id");

    // Update student_documents table - specifically status_std, course, previous school, etc
    $conn->query("UPDATE student_documents SET 
        prevschool='$prevschool', 
        last_grade='$last_grade', 
        applying_grade='$applying_grade', 
        Course='$Course', 
        status_std='$new_status'
        WHERE id=$admission_id"); // make sure id matches admission id (assuming both tables sync by id)

    // Insert into the selected student status table (optional)
    $statusTables = ['transferee', 'old', 'irregular', 'new']; // correct capitalization
    if (!empty($new_status) && in_array($new_status, $statusTables)) {
        $conn->query("INSERT INTO `$new_status` (name) VALUES ('$name')");
    }

    // Redirect after processing
    header("Location: index.php");
    exit(); // Important to stop further code execution
}
?>
