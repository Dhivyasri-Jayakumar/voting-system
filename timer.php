<?php
// timer.php
$timerDuration = 120; // Set timer duration to 2 minutes (120 seconds)
?>
<div id="timerDisplay"></div>
<script>
    // Timer duration from PHP (2 minutes, i.e., 120 seconds)
    const initialTimerDuration = <?php echo $timerDuration; ?>;

    // Function to start the timer
    function startTimer() {
        const timerElement = document.getElementById('timerDisplay');
        let remainingTime = localStorage.getItem('remainingTime');

        if (!remainingTime) {
            // Initialize the timer if it's not already set (set to 2 minutes)
            remainingTime = initialTimerDuration;
            localStorage.setItem('remainingTime', remainingTime);
        } else {
            remainingTime = parseInt(remainingTime, 10);
        }

        // Display timer immediately (no delay)
        timerElement.style.display = 'block';

        const timerInterval = setInterval(() => {
            if (remainingTime <= 0) {
                clearInterval(timerInterval);
                alert('Time is up! Thanks for voting.');
                localStorage.removeItem('remainingTime'); // Clear timer
                localStorage.removeItem('timerStarted'); // Clear flag
                window.location.href = 'index.html'; // Redirect to voting page
            } else {
                // Update timer display
                const minutes = Math.floor(remainingTime / 60);
                const seconds = remainingTime % 60;
                timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                localStorage.setItem('remainingTime', remainingTime - 1); // Save updated time
                remainingTime--;

                // Show "Hurry up!" prompt when there are 15 seconds left
                if (remainingTime === 15) {
                    alert('Hurry up! Time is running out!');
                }
            }
        }, 1000);

        // Stop timer when user votes for the last position (Quiz Club Secretary)
        window.stopTimerAndRedirect = function() {
            clearInterval(timerInterval);
            alert('Thanks for Voting!');
            localStorage.removeItem('remainingTime');
            localStorage.removeItem('timerStarted');
            window.location.href = 'index.html'; // Redirect to voting page
        };
    }

    // Ensure the timer starts immediately when the page is ready
    document.addEventListener('DOMContentLoaded', function () {
        // Start the timer if it should run (timerStarted flag in localStorage)
        if (localStorage.getItem('timerStarted') === 'true') {
            startTimer();
        } else {
            // Hide timer if not started (before the "Submit" button is clicked)
            document.getElementById('timerDisplay').style.display = 'none';
        }
    });

    // Function to start/reset the timer when submit is clicked
    function onSubmit() {
        // Reset timer to 2 minutes and start the countdown
        localStorage.setItem('remainingTime', initialTimerDuration);
        localStorage.setItem('timerStarted', 'true'); // Set flag to indicate the timer has started
        startTimer(); // Start the timer
    }

    // Optional: If you want the timer to show immediately without delay, pre-load the display
    document.getElementById('timerDisplay').style.display = 'block';

    // Add this function call to your submit button HTML:
    // Example: <button type="submit" onclick="onSubmit()">Submit</button>
</script>

<script src="keyboard-blocker.js"></script>

<style>
    #timerDisplay {
        position: fixed;
        top: 25%;
        right: 10px;
        font-size: 18px;
        color: white;
        background-color: green;
        padding: 5px 10px;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        display: none; /* Initially hidden, will show once the timer starts */
    }
</style>
