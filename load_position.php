<?php
include('db.php');

// Get the position from the URL parameter, or default to "President"
$position = isset($_GET['position']) ? htmlspecialchars($_GET['position']) : 'President';

// Fetch candidates for the given position
$query = "SELECT id, name, department FROM candidates WHERE position = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $position);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>" . strtoupper($position) . " CANDIDATES</h2>";
if ($result->num_rows > 0) {
    echo "<table class='candidates-table' id='candidatesTable'>";
    $counter = 0;
    while ($row = $result->fetch_assoc()) {
        if ($counter % 2 == 0) echo "<tr>";
        echo "
            <td>
                <div class='candidate'>
                    <img src='candidatepic.jpg' height='100' width='100' alt='Candidate'>
                    <div class='candidate-info'>
                        <p><strong>Name:</strong> " . htmlspecialchars($row['name']) . "</p>
                        <p><strong>Department:</strong> " . htmlspecialchars($row['department']) . "</p>
                    </div>
                    <button class='vote-btn' onclick='submitVote(" . $row['id'] . ")'>Vote</button>
                </div>
            </td>
        ";
        $counter++;
        if ($counter % 2 == 0) echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No candidates found for this position.</p>";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidates for Election</title>
    <!-- Your CSS styling here -->
    <link rel="stylesheet" href="style.css">

</head>
<body>

    <!-- The PHP code will output the candidates here -->
    <div id="content">
        <?php
        // The candidate list will be dynamically displayed here
        ?>
    </div>

    <script>
        // Save the current position and content before page reload
        window.addEventListener('beforeunload', function () {
            // Save the scroll position
            localStorage.setItem('scrollPosition', window.scrollY);

            // Save the current state of the candidates table
            let candidatesContent = document.getElementById('candidatesTable') ? document.getElementById('candidatesTable').innerHTML : '';
            localStorage.setItem('pageContent', candidatesContent);

            // Save the current position (e.g., President, Secretary, etc.)
            let currentPosition = "<?php echo $position; ?>";
            localStorage.setItem('currentPosition', currentPosition);
        });

        // On page load, restore the content, scroll position, and position
        window.addEventListener('load', function () {
            // Restore the position of the candidates' list
            let savedPosition = localStorage.getItem('currentPosition');
            if (savedPosition) {
                // Redirect to the same page with the saved position
                window.location.href = window.location.pathname + "?position=" + savedPosition;
            }

            // Check if we have a saved scroll position
            if (localStorage.getItem('scrollPosition')) {
                window.scrollTo(0, localStorage.getItem('scrollPosition'));
            }

            // Check if we have saved page content
            if (localStorage.getItem('pageContent')) {
                document.getElementById('candidatesTable').innerHTML = localStorage.getItem('pageContent');
            }
        });

        // Example: Submit vote function (you can modify this based on your back-end handling)
        function submitVote(candidateId) {
            // Here, you can send an AJAX request or form submission to process the vote.
            alert('Vote submitted for candidate ID: ' + candidateId);
        }
    </script>
</body>
</html>
