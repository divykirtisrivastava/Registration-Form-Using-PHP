
<?php
$conn = new mysqli("localhost", "root", "1234", "registration");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sqlCreateTable = "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    dob DATE NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sqlCreateTable) === TRUE) {
    echo "Student table created successfully or already exists.<br>";
} else {
    echo "Error creating student table: " . $conn->error . "<br>";
}

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$dob = $_POST['dob'];

if(isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
    $targetDirectory = "uploads/";
    $originalFilename = $_FILES["image"]["name"];
        $fileExtension = pathinfo($originalFilename, PATHINFO_EXTENSION);
        $uniqueFilename = uniqid() . '.' . $fileExtension;

        $targetFile = $targetDirectory . $uniqueFilename;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        echo "The file ". htmlspecialchars($originalFilename) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    echo "No image file uploaded or an error occurred.";
}

$sql = "INSERT INTO students (name, email, phone, dob, image) VALUES ('$name', '$email', '$phone', '$dob', '$targetFile')";

$sqlCheckDuplicate = "SELECT * FROM students WHERE email = '$email'";
$resultCheckDuplicate = $conn->query($sqlCheckDuplicate);
if ($resultCheckDuplicate->num_rows > 0) {
    echo "Error: Email address already exists.";
} else {
    if ($conn->query($sql) === TRUE) {
        header("Location: viewStudent.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
