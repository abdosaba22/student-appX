<?php

#$servername = "172.31.35.41";
#$username = "abdo";
#$password = "123456";
#$dbname = "applicationDB";


$servername = getenv('DB_HOST');
$username   = getenv('DB_USER');
$password   = getenv('DB_PASSWORD');
$dbname     = getenv('DB_NAME');

$conn = mysqli_init();
$conn->ssl_set(NULL, NULL, NULL, NULL, NULL);

if (!$conn->real_connect($servername, $username, $password, $dbname)) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT s.student_id, s.name, s.age, p.phone_number
        FROM students s
        LEFT JOIN st_phones p ON s.student_id = p.student_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Students & Phones</h2>";
    echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Age</th><th>Phone</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["student_id"]."</td><td>".$row["name"]."</td><td>".$row["age"]."</td><td>".$row["phone_number"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$conn->close();
?>  
