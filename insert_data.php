<?php
include 'connect.php';

// Insert into admission
if (isset($_POST['name'])) {
    $stmt = $conn->prepare("INSERT INTO admission (name, bdate, gender, nat, religion, curraddress, province, peraddress, zip, email, city, phoneno)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssss", $_POST['name'], $_POST['bdate'], $_POST['gender'], $_POST['nat'], $_POST['religion'],
                      $_POST['curraddress'], $_POST['province'], $_POST['peraddress'], $_POST['zip'],
                      $_POST['email'], $_POST['city'], $_POST['phoneno']);
    $stmt->execute();
    $stmt->close();
    header("Location: guardianinfo.html");
    exit();
}

// Insert into guardian_info
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

// Insert academic info + uploads
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

    $stmt = $conn->prepare("INSERT INTO student_documents (applying_grade, prevschool, last_grade, birth_cert_path, form137_path, tor_path, good_moral_path, honorable_dismissal_path, signature_path, sigdate, confirmed)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $confirmed = isset($_POST['confirm']) && $_POST['confirm'] == "yes" ? 1 : 0;
    $stmt->bind_param("ssssssssssi", $_POST['applying_grade'], $_POST['prevschool'], $_POST['last_grade'],
                      $birth, $form137, $tor, $good, $honor, $sign, $_POST['sigdate'], $confirmed);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Submitted successfully!'); window.location.href='Post-AdmissionPreview.php';</script>";
}

$conn->close();
?>
