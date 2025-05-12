<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];

    // Prepare statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT Admission_ID, full_name, applying_grade, course FROM tbladmission_addstudent WHERE full_name LIKE CONCAT('%', ?, '%') LIMIT 10");
    $stmt->bind_param("s", $searchQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    // If records found
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Escape double quotes for JavaScript string safety
            $fullName = htmlspecialchars($row['full_name'], ENT_QUOTES);
            $grade = htmlspecialchars($row['applying_grade'], ENT_QUOTES);
            $course = htmlspecialchars($row['course'], ENT_QUOTES);

            echo "<div class='search-result-item' onclick='selectStudent(" . $row['Admission_ID'] . ", \"" . $fullName . "\", \"" . $grade . "\", \"" . $course . "\")'>" . $fullName . "</div>";
        }
    } else {
        echo "<div class='search-result-item'>No results found.</div>";
    }

    $stmt->close();
}

$conn->close();
?>
