<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $emp_id = $_POST['emp_id'];
            $sql = "INSERT INTO emp_table (firstname, lastname, email, emp_id) VALUES ('$firstname', '$lastname', '$email', '$emp_id')";
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            break;

        case 'read':
            $sql = "SELECT * FROM emp_table";
            $result = $conn->query($sql);
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
            break;

        case 'update':
            $id = $_POST['id'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $emp_id = $_POST['emp_id'];
            $sql = "UPDATE emp_table SET firstname='$firstname', lastname='$lastname', email='$email', emp_id='$emp_id' WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                echo "Record updated successfully";
            } else {
                echo "Error updating record: " . $conn->error;
            }
            break;

        case 'delete':
            $id = $_POST['id'];
            $sql = "DELETE FROM emp_table WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                echo "Record deleted successfully";
            } else {
                echo "Error deleting record: " . $conn->error;
            }
            break;
    }
}
$conn->close();
?>
