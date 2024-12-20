<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<script src="keyboard-blocker.js"></script>

    <!-- Fixed Header -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td><img src="leftlogo.jpeg" alt="College Logo1"></td>
                <td>
                    <h1>VELLALAR COLLEGE FOR WOMEN</h1>
                    <p>College with Potential for Excellence | An ISO 9001:2015 Certified Institution Re-accredited with
                        'A+' Grade (CYCLE IV) by NAAC & Affiliated with Bharathiyar University, Coimbatore</p>
                </td>
                <td><img src="rightlogo.jpeg" alt="College Logo2"></td>
            </tr>
        </table>
    </div>

    <!-- Timer -->
    <?php include 'timer.php'; ?>

    <!-- Dynamic Content -->
    <div id="dynamic-content">
        <p>Loading...</p>
    </div>

    <script>
        // Track current position (starts at "President" by default)
        let currentPositionIndex = 0;  // Default to 0 (i.e., President)
        const positions = [
            'President',
            'Vice President',
            'Secretary',
            'Joint Secretary',
            'Fine Arts Secretary',
            'Quiz Club Secretary'
        ];

        // Reset position when it's the first load or the page is being loaded after submitting
        if (localStorage.getItem('currentPositionIndex') === null) {
            // Force start from "President"
            currentPositionIndex = 0;
        } else {
            // Otherwise, use stored position from localStorage
            currentPositionIndex = parseInt(localStorage.getItem('currentPositionIndex'));
        }

        // Function to load content dynamically for a given position
        function loadPosition(position) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `load_position.php?position=${encodeURIComponent(position)}`, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('dynamic-content').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        // Function to handle vote submission
        function submitVote(candidateId) {
            if (confirm("Are you sure you want to vote for this candidate?")) {
                const formData = new FormData();
                formData.append('candidate_id', candidateId);
                formData.append('position', positions[currentPositionIndex]);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'vote.php', true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        const response = xhr.responseText.trim();

                        // Move to the next position
                        currentPositionIndex++;

                        // If there are more positions to go, load the next one
                        if (currentPositionIndex < positions.length) {
                            loadPosition(positions[currentPositionIndex]);

                            // Save current position to localStorage
                            localStorage.setItem('currentPositionIndex', currentPositionIndex);
                        } else {
                            // Thank you message after the last position
                            alert("Thank you for voting !");

                            // Redirect to another page after voting is complete
                            window.location.href = 'index.html';
                        }
                    } else {
                        alert("There was an error processing your vote. Please try again.");
                    }
                };
                xhr.send(formData);
            }
        }

        // Load the initial position (this is always the first time or after reset, it loads "President")
        loadPosition(positions[currentPositionIndex]);

        // Save the current position index before the page reloads
        window.addEventListener('beforeunload', function () {
            localStorage.setItem('currentPositionIndex', currentPositionIndex);
        });

        // Restore scroll position after the page reloads (optional)
        window.addEventListener('load', function () {
            if (localStorage.getItem('scrollPosition')) {
                window.scrollTo(0, localStorage.getItem('scrollPosition'));
            }
        });
    </script>

</body>

</html>
