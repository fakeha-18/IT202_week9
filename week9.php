<?php
$host = "sql1.njit.edu";
$username = "fi53";
$password = "T;7FW%*#dzU*p_gB17-P";
$database = "fi53";
s
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Student table
$sql = "CREATE TABLE IF NOT EXISTS Student (
    Name VARCHAR(100) NOT NULL,
    ID INT NOT NULL PRIMARY KEY,
    Major VARCHAR(100)
)";

if (!$conn->query($sql)) {
    die("Student table error: " . $conn->error);
}

// Create Transcript table
$sql = "CREATE TABLE IF NOT EXISTS Transcript (
    ID INT NOT NULL,
    Course VARCHAR(50) NOT NULL,
    Grade VARCHAR(5) NOT NULL,
    PRIMARY KEY (ID, Course),
    FOREIGN KEY (ID) REFERENCES Student(ID)
)";

if (!$conn->query($sql)) {
    die("Transcript table error: " . $conn->error);
}

// Insert 4 students
$sql = "INSERT INTO Student (Name, ID, Major) VALUES
('Ayesha', 1001, 'Information Systems'),
('Sara', 1002, 'Computer Science'),
('Ali', 1003, 'Business'),
('Zara', 1004, 'Biology')
ON DUPLICATE KEY UPDATE
Name = VALUES(Name),
Major = VALUES(Major)";

if (!$conn->query($sql)) {
    die("Student insert error: " . $conn->error);
}

// Insert 8 transcript entries
$sql = "INSERT INTO Transcript (ID, Course, Grade) VALUES
(1001, 'IT202', 'A'),
(1001, 'IS117', 'A'),
(1002, 'CS100', 'B+'),
(1002, 'MATH111', 'A'),
(1003, 'MGMT190', 'A'),
(1003, 'ECON201', 'B'),
(1004, 'BIOL101', 'A'),
(1004, 'CHEM121', 'B+')
ON DUPLICATE KEY UPDATE
Grade = VALUES(Grade)";

if (!$conn->query($sql)) {
    die("Transcript insert error: " . $conn->error);
}

// Function to display results
function showTable($conn, $title, $sql) {
    echo "<h2>" . htmlspecialchars($title) . "</h2>";

    $result = $conn->query($sql);

    if (!$result) {
        die("SELECT error: " . $conn->error);
    }

    echo "<table border='1' cellpadding='10'
          cellspacing='0'
          style='border-collapse:collapse;'>";

    echo "<tr>";
    foreach ($result->fetch_fields() as $field) {
        echo "<th>" . htmlspecialchars($field->name) . "</th>";
    }
    echo "</tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars((string)$value) . "</td>";
        }
        echo "</tr>";
    }

    echo "</table><br>";

    $result->free();
}

// Page heading
echo "<h1>Week 9 - SQL Tables and JOIN</h1>";

// Display Student table
showTable(
    $conn,
    "STUDENT TABLE",
    "SELECT Name, ID, Major FROM Student ORDER BY ID"
);

// Display Transcript table
showTable(
    $conn,
    "TRANSCRIPT TABLE",
    "SELECT ID, Course, Grade
     FROM Transcript
     ORDER BY ID, Course"
);

// JOIN Student and Transcript
$joinSQL = "SELECT
    Student.Name,
    Student.ID,
    Student.Major,
    Transcript.Course,
    Transcript.Grade
FROM Student
INNER JOIN Transcript
ON Student.ID = Transcript.ID
ORDER BY Student.ID, Transcript.Course";

// Display JOIN results
showTable(
    $conn,
    "JOINED TABLE",
    $joinSQL
);

// Close database connection
$conn->close();
?>