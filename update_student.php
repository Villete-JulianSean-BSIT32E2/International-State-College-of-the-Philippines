<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update admission
    $stmt = $conn->prepare("UPDATE admission SET name=?, bdate=?, gender=?, nat=?, religion=?, curraddress=?, province=?, peraddress=?, zip=?, email=?, city=?, phoneno=? WHERE id=?");
    $stmt->bind_param("ssssssssssssi", $_POST['name'], $_POST['bdate'], $_POST['gender'], $_POST['nat'], $_POST['religion'],
                      $_POST['curraddress'], $_POST['province'], $_POST['peraddress'], $_POST['zip'], $_POST['email'], $_POST['city'], $_POST['phoneno'], $_POST['admission_id']);
    $stmt->execute();
    $stmt->close();

    // Update guardian_info
    $stmt = $conn->prepare("UPDATE guardian_info SET fname=?, mname=?, foccu=?, moccu=?, fno=?, mno=?, gname=?, relationship=?, gno=? WHERE id=?");
    $stmt->bind_param("sssssssssi", $_POST['fname'], $_POST['mname'], $_POST['foccu'], $_POST['moccu'],
                      $_POST['fno'], $_POST['mno'], $_POST['gname'], $_POST['relationship'], $_POST['gno'], $_POST['guardian_id']);
    $stmt->execute();
    $stmt->close();

    // Update student_documents
    $stmt = $conn->prepare("UPDATE student_documents SET applying_grade=?, prevschool=?, last_grade=? WHERE id=?");
    $stmt->bind_param("sssi", $_POST['applying_grade'], $_POST['prevschool'], $_POST['last_grade'], $_POST['docs_id']);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Student information updated successfully!'); window.location.href='Personal-Information.php';</script>";
}
?>
