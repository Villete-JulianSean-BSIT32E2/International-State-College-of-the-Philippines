<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_id = $_GET['id'];

// Ensure student_documents row exists
$conn->query("INSERT IGNORE INTO student_documents (id) VALUES ($student_id)");

// Fetch student name
$student = $conn->query("SELECT name FROM admission WHERE id = $student_id")->fetch_assoc();
$documents = $conn->query("SELECT * FROM student_documents WHERE id = $student_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set dummy path if "Cleared", empty if "Pending"
    $birth_cert = $_POST['birth_cert'] === 'Cleared' ? 'dummy_birth_cert.pdf' : '';
    $form137 = $_POST['form137'] === 'Cleared' ? 'dummy_form137.pdf' : '';
    $tor = $_POST['tor'] === 'Cleared' ? 'dummy_tor.pdf' : '';
    $good_moral = $_POST['good_moral'] === 'Cleared' ? 'dummy_good_moral.pdf' : '';
    $hon_dismissal = $_POST['honorable_dismissal'] === 'Cleared' ? 'dummy_hon_dismissal.pdf' : '';

    $stmt = $conn->prepare("
        UPDATE student_documents SET 
            birth_cert_path = ?, 
            form137_path = ?, 
            tor_path = ?, 
            good_moral_path = ?, 
            honorable_dismissal_path = ?
        WHERE id = ?
    ");
    $stmt->bind_param("sssssi", $birth_cert, $form137, $tor, $good_moral, $hon_dismissal, $student_id);
    $stmt->execute();
    $stmt->close();

    header("Location: registrar.php?page=student_records");
    exit();
}
?>

<h2>Edit Documents for <?= htmlspecialchars($student['name']) ?></h2>

<form method="POST" style="max-width: 500px; background: #f9f9f9; padding: 20px; border-radius: 6px;">
    <?php
    function renderSelect($name, $current) {
        $current_status = !empty($current) ? 'Cleared' : 'Pending';
        echo "
            <label>" . ucfirst(str_replace("_", " ", $name)) . ":</label>
            <select name=\"$name\" required>
                <option value=\"Pending\" " . ($current_status === 'Pending' ? 'selected' : '') . ">Pending</option>
                <option value=\"Cleared\" " . ($current_status === 'Cleared' ? 'selected' : '') . ">Cleared</option>
            </select><br><br>
        ";
    }

    renderSelect('birth_cert', $documents['birth_cert_path']);
    renderSelect('form137', $documents['form137_path']);
    renderSelect('tor', $documents['tor_path']);
    renderSelect('good_moral', $documents['good_moral_path']);
    renderSelect('honorable_dismissal', $documents['honorable_dismissal_path']);
    ?>

    <button type="submit" style="background-color: #3498db; color: white; padding: 8px 16px; border: none; border-radius: 4px;">Save Changes</button>
</form>
