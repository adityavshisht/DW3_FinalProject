<?php
session_start();

// Handle laptop condition form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['turns_on'])) {

    // Store the user's responses in the session
    $_SESSION['laptop_condition'] = [
        'turns_on' => $_POST['turns_on'],
        'screen_condition' => $_POST['screen_condition'],
        'keyboard_functioning' => $_POST['keyboard_functioning'],
        'battery_condition' => $_POST['battery_condition'],
        'ports_functional' => $_POST['ports_functional'],
        'original_charger' => $_POST['original_charger'],
        'warranty' => $_POST['warranty']
    ];

    // Collect answers for condition evaluation
    $answers = [
        $_POST['turns_on'],
        $_POST['screen_condition'],
        $_POST['keyboard_functioning'],
        $_POST['battery_condition'],
        $_POST['ports_functional'],
        $_POST['original_charger'],
        $_POST['warranty']
    ];

    $total_questions = 7;

    // Count how many answers were marked "No"
    $no_count = count(array_filter($answers, function($answer) {
        return $answer === 'No';
    }));

    $no_percentage = ($no_count / $total_questions) * 100;

    // Generate the condition message based on the score
    if ($no_percentage > 75) {
        $condition_message = "The condition of your laptop is not good enough, and the estimated price may be very low.";
    } elseif ($no_percentage > 50) {
        $condition_message = "The condition of your laptop is good, and the price will be moderate.";
    } elseif ($no_count === 0) {
        $condition_message = "Your laptop is in great condition, and the price will be high.";
    } else {
        $condition_message = "Your laptop is in fair condition.";
    }

    // Save the message to session and return JSON response
    $_SESSION['condition_message'] = $condition_message;

    header('Content-Type: application/json');
    echo json_encode(['message' => $condition_message]);
    session_write_close();
    exit();
}

include 'header.php';
?>

<div class="container">
    <h2>Tell us more about your laptop</h2>
    <p>Please answer a few questions about your laptop.</p>

    <form id="laptopForm" action="" method="POST">
        <div class="question">
            <h3>1. Does the laptop turn on and function properly?</h3>
            <p>Ensure that the laptop boots up without any issues.</p>
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
            <h3>3. Are all keyboard keys functioning properly?</h3>
            <p>Test whether all keys on the keyboard are working.</p>
            <input type="radio" id="keyboard_yes" name="keyboard_functioning" value="Yes" required>
            <label for="keyboard_yes">Yes</label>
            <input type="radio" id="keyboard_no" name="keyboard_functioning" value="No">
            <label for="keyboard_no">No</label>
        </div>

        <div class="question">
            <h3>4. Is the battery in good condition and holds a charge?</h3>
            <p>Verify if the battery lasts a reasonable duration.</p>
            <input type="radio" id="battery_yes" name="battery_condition" value="Yes" required>
            <label for="battery_yes">Yes</label>
            <input type="radio" id="battery_no" name="battery_condition" value="No">
            <label for="battery_no">No</label>
        </div>

        <div class="question">
            <h3>5. Are all ports and connectivity options functional?</h3>
            <p>Check if USB, HDMI, charging, and headphone ports are working.</p>
            <input type="radio" id="ports_yes" name="ports_functional" value="Yes" required>
            <label for="ports_yes">Yes</label>
            <input type="radio" id="ports_no" name="ports_functional" value="No">
            <label for="ports_no">No</label>
        </div>

        <div class="question">
            <h3>6. Do you have the original charger and accessories?</h3>
            <p>Providing the original charger may increase the estimated value.</p>
            <input type="radio" id="charger_yes" name="original_charger" value="Yes" required>
            <label for="charger_yes">Yes</label>
            <input type="radio" id="charger_no" name="original_charger" value="No">
            <label for="charger_no">No</label>
        </div>

        <div class="question">
            <h3>7. Is your laptop under manufacturer warranty?</h3>
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
document.getElementById('laptopForm').addEventListener('submit', function(e) {
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
