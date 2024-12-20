<?php
include('db.php'); // Include your DB connection

// Check if the database connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input values
    $candidateId = intval($_POST['candidate_id']);
    $position = htmlspecialchars($_POST['position']);

    // Check if inputs are valid
    if ($candidateId <= 0 || empty($position)) {
        echo "Invalid input.";
        exit;
    }

    // Prepare the SQL query to update the vote_count for a specific candidate
    $query = "UPDATE candidates SET vote_count = vote_count + 1 WHERE id = ? AND position = ?";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Check if the statement preparation was successful
    if ($stmt === false) {
        echo "Error in query preparation: " . $conn->error;
        exit;
    }

    // Bind parameters for the prepared statement
    $stmt->bind_param("is", $candidateId, $position);

    // Execute the statement
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Vote successfully recorded!";
        } else {
            echo "No rows updated. Candidate might not exist or position mismatch.";
        }
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();
}
?>
