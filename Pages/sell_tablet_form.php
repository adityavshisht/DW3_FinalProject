<?php
session_start();

// Handle form submission when the user answers the tablet condition questions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['turns_on'])) {

    // Store the submitted answers in session for later use
    $_SESSION['tablet_condition'] = [
        'turns_on' => $_POST['turns_on'],
        'screen_condition' => $_POST['screen_condition'],
        'touch_functioning' => $_POST['touch_functioning'],
        'battery_condition' => $_POST['battery_condition'],
        'ports_functional' => $_POST['ports_functional'],
        'original_charger' => $_POST['original_charger'],
        'warranty' => $_POST['warranty']
    ];

    // Create an array of the responses for analysis
    $answers = [
        $_POST['turns_on'],
        $_POST['screen_condition'],
        $_POST['touch_functioning'],
        $_POST['battery_condition'],
        $_POST['ports_functional'],
        $_POST['original_charger'],
        $_POST['warranty']
    ];

    // Count how many "No" answers were submitted
    $total_questions = 7;
    $no_count = count(array_filter($answers, function($answer) {
        return $answer === 'No';
    }));
    $no_percentage = ($no_count / $total_questions) * 100;

    // Determine the condition message based on the percentage of "No" responses
    if ($no_percentage > 75) {
        $condition_message = "The condition of your tablet is not good enough, and the estimated price may be very low.";
    } elseif ($no_percentage > 50) {
        $condition_message = "The condition of your tablet is good, and the price will be moderate.";
    } elseif ($no_count === 0) {
        $condition_message = "Your tablet is in great condition, and the price will be high.";
    } else {
        $condition_message = "Your tablet is in fair condition.";
    }

    // Store the message in session and return it as JSON
    $_SESSION['condition_message'] = $condition_message;

    header('Content-Type: application/json');
    echo json_encode(['message' => $condition_message]);
    session_write_close();
    exit();
}

include 'header.php';
?>

<div class="container">
    <h2>Tell us more about your tablet</h2>
    <p>Please answer a few questions about your tablet.</p>

    <form id="tabletForm" action="" method="POST">
        <div class="question">
            <h3>1. Does the tablet turn on and function properly?</h3>
            <p>Ensure that the tablet boots up without any issues.</p>
            <input type="radio" id="turns_on_yes" name="turns_on" value="Yes" required>
            <label for="turns_on_yes">Yes</label>
            <input type="radio" id="turns_on_no" name="turns_on" value="No">
            <label for="turns_on_no">No</label>
        </div>

        <div class="question">
            <h3>2. Is the screen free from cracks or major scratches?</h3>
            <p>Check if the screen has any visible damage.</p>
            <input type="radio" id="screen_yes" name="screen_condition" value="Yes" required>
            <label for="screen_yes">Yes</label>
            <input type="radio" id="screen_no" name="screen_condition" value="No">
            <label for="screen_no">No</label>
        </div>

        <div class="question">
            <h3>3. Is the touch functionality working properly?</h3>
            <p>Verify that the touchscreen responds accurately to touch.</p>
            <input type="radio" id="touch_yes" name="touch_functioning" value="Yes" required>
            <label for="touch_yes">Yes</label>
            <input type="radio" id="touch_no" name="touch_functioning" value="No">
            <label for="touch_no">No</label>
        </div>

        <div class="question">
            <h3>4. Is the battery in good condition and holds a charge?</h3>
            <p>Check if the battery lasts a reasonable duration.</p>
            <input type="radio" id="battery_yes" name="battery_condition" value="Yes" required>
            <label for="battery_yes">Yes</label>
            <input type="radio" id="battery_no" name="battery_condition" value="No">
            <label for="battery_no">No</label>
        </div>

        <div class="question">
            <h3>5. Are all ports and connectivity options functional?</h3>
            <p>Test USB, charging, and other ports for functionality.</p>
            <input type="radio" id="ports_yes" name="ports_functional" value="Yes" required>
            <label for="ports_yes">Yes</label>
            <input type="radio" id="ports_no" name="ports_functional" value="No">
            <label for="ports_no">No</label>
        </div>

        <div class="question">
            <h3>6. Do you have the original charger and accessories?</h3>
            <p>Including the original charger may increase the estimated value.</p>
            <input type="radio" id="charger_yes" name="original_charger" value="Yes" required>
            <label for="charger_yes">Yes</label>
            <input type="radio" id="charger_no" name="original_charger" value="No">
            <label for="charger_no">No</label>
        </div>

        <div class="question">
            <h3>7. Is your tablet under manufacturer warranty?</h3>
            <p>A valid manufacturer warranty can improve the price offer.</p>
            <input type="radio" id="warranty_yes" name="warranty" value="Yes" required>
            <label for="warranty_yes">Yes</label>
            <input type="radio" id="warranty_no" name="warranty" value="No">
            <label for="warranty_no">No</label>
        </div>

        <button type="submit">Continue →</button>
    </form>

    <div id="conditionMessage" class="condition-message"></div>
</div>

<script>
// Intercept form submission to send the data using fetch
document.getElementById('tabletForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const conditionMessageDiv = document.getElementById('conditionMessage');

    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        conditionMessageDiv.textContent = data.message;
        setTimeout(() => {
            window.location.href = 'schedule_appointment.php';
        }, 3000); // Redirect after 3 seconds
    })
    .catch(error => {
        console.error('Error:', error);
        conditionMessageDiv.textContent = 'An error occurred while processing your request.';
    });
});
</script>

<?php include 'footer.php'; ?>
