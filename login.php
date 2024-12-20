<?php
include 'db.php'; // Include the database connection

// Retrieve and sanitize form inputs
$voter_id = isset($_POST['voter-id']) ? $conn->real_escape_string($_POST['voter-id']) : '';
$user_password = isset($_POST['password']) ? $conn->real_escape_string($_POST['password']) : '';

// Query to check voter credentials with case sensitivity using BINARY
$sql = "SELECT * FROM voters WHERE BINARY voter_id = '$voter_id' AND BINARY password = '$user_password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch voter data
    $row = $result->fetch_assoc();
    if ($row['voted'] == 0) {
        // Set the "voted" attribute to 1
        $update_sql = "UPDATE voters SET voted = 1 WHERE BINARY voter_id = '$voter_id'";
        if ($conn->query($update_sql) === TRUE) {
            // Check if the update was successful
            $check_sql = "SELECT voted FROM voters WHERE BINARY voter_id = '$voter_id'";
            $check_result = $conn->query($check_sql);
            $check_row = $check_result->fetch_assoc();
            if ($check_row['voted'] == 1) {
                // Store voting session details in local storage
                echo "<script>
                    localStorage.setItem('currentPositionIndex', 0); // Reset position to 'President'
                    localStorage.setItem('timerStarted', 'true');
                    localStorage.setItem('remainingTime', 120); // Set 2 minutes (120 seconds)
                    window.location.href = 'vote_page.php'; // Redirect to vote page
                </script>";
                exit();
            } else {
                echo "<script>alert('Error verifying vote status. Please try again.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Error updating vote status: " . $conn->error . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('You have already voted.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Enter correct Voter ID and Password.'); window.history.back();</script>";
}

// Close connection
$conn->close();
?>
