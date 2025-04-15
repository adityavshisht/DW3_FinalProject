<?php
session_start();

// Handle form submission for headphone
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['audio_quality'])) {

    // Save responses to session
    $_SESSION['headphones_condition'] = [
        'audio_quality' => $_POST['audio_quality'],
        'mic_working' => $_POST['mic_working'],
        'earpad_condition' => $_POST['earpad_condition'],
        'connectivity' => $_POST['connectivity'],
        'has_box' => $_POST['has_box']
    ];
    
    // Gather answers for condition evaluation
    $answers = [
        $_POST['audio_quality'],
        $_POST['mic_working'],
        $_POST['earpad_condition'],
        $_POST['connectivity'],
        $_POST['has_box']
    ];
    
    $total_questions = 5;
    
    // Count how many answers indicate a problem 
    $no_count = count(array_filter($answers, function($answer) {
        return $answer === 'No';
    }));
    
    $no_percentage = ($no_count / $total_questions) * 100;

    // Generate condition message based on "No" answers
    if ($no_percentage > 75) {
        $condition_message = "The condition of your headphones is not good enough, and the estimated price may be very low.";
    } elseif ($no_percentage > 50) {
        $condition_message = "The condition of your headphones is good, and the price will be moderate.";
    } elseif ($no_count === 0) {
        $condition_message = "Your headphones are in great condition, and the price will be high.";
    } else {
        $condition_message = "Your headphones are in fair condition.";
    }
    
    // Store message and return JSON response
    $_SESSION['condition_message'] = $condition_message;
    
    header('Content-Type: application/json');
    echo json_encode(['message' => $condition_message]);
    session_write_close();
    exit();
}

include 'header.php';
?>

<div class="container">
    <h2>Tell us more about your headphones</h2>
    <p>Please answer a few questions about your headphones.</p>
    
    <form id="headphonesForm" method="POST">
        <div class="question">
            <h3>1. Is the audio quality clear?</h3>
            <p>Check if the sound is clear without distortion or issues.</p>
            <input type="radio" id="audio_yes" name="audio_quality" value="Yes" required>
            <label for="audio_yes">Yes</label>
            <input type="radio" id="audio_no" name="audio_quality" value="No">
            <label for="audio_no">No</label>
        </div>

        <div class="question">
            <h3>2. Is the microphone working?</h3>
            <p>Test if the microphone (if present) functions properly.</p>
            <input type="radio" id="mic_yes" name="mic_working" value="Yes" required>
            <label for="mic_yes">Yes</label>
            <input type="radio" id="mic_no" name="mic_working" value="No">
            <label for="mic_no">No</label>
        </div>

        <div class="question">
            <h3>3. Are the earpads in good condition?</h3>
            <p>Check for wear, tear, or damage to the earpads.</p>
            <input type="radio" id="earpad_yes" name="earpad_condition" value="Yes" required>
            <label for="earpad_yes">Yes</label>
            <input type="radio" id="earpad_no" name="earpad_condition" value="No">
            <label for="earpad_no">No</label>
        </div>

        <div class="question">
            <h3>4. Does Bluetooth or wire work properly?</h3>
            <p>Verify connectivity (Bluetooth or wired) functions as expected.</p>
            <input type="radio" id="connect_yes" name="connectivity" value="Yes" required>
            <label for="connect_yes">Yes</label>
            <input type="radio" id="connect_no" name="connectivity" value="No">
            <label for="connect_no">No</label>
        </div>

        <div class="question">
            <h3>5. Do you have the box or packaging?</h3>
            <p>Having the original packaging can increase the value.</p>
            <input type="radio" id="box_yes" name="has_box" value="Yes" required>
            <label for="box_yes">Yes</label>
            <input type="radio" id="box_no" name="has_box" value="No">
            <label for="box_no">No</label>
        </div>

        <button type="submit">Continue →</button>
    </form>
    <div id="conditionMessage" class="condition-message"></div>
</div>

<script>
// Handle form submission using Fetch API
document.getElementById('headphonesForm').addEventListener('submit', function(e) {
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