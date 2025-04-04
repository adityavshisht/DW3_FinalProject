<?php
session_start();

// Handle form submission for keyboard
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['all_keys_work'])) {
    
	// Save answers into the session
    $_SESSION['keyboard_condition'] = [
        'all_keys_work' => $_POST['all_keys_work'],
        'usb_bluetooth_ok' => $_POST['usb_bluetooth_ok'],
        'backlight_working' => $_POST['backlight_working'],
        'physical_condition' => $_POST['physical_condition'],
        'has_accessories' => $_POST['has_accessories']
    ];
   
    // Gather responses for scoring
    $answers = [
        $_POST['all_keys_work'],
        $_POST['usb_bluetooth_ok'],
        $_POST['backlight_working'],
        $_POST['physical_condition'],
        $_POST['has_accessories']
    ];
    
    $total_questions = 5;
	
	// Count how many responses are negative
    $no_count = count(array_filter($answers, function($answer) {
        return $answer === 'No';
    }));
	
    $no_percentage = ($no_count / $total_questions) * 100;
    
    // Generate message based on condition scoring
    if ($no_percentage > 75) {
        $condition_message = "The condition of your keyboard is not good enough, and the estimated price may be very low.";
    } elseif ($no_percentage > 50) {
        $condition_message = "The condition of your keyboard is good, and the price will be moderate.";
    } elseif ($no_count === 0) {
        $condition_message = "Your keyboard is in great condition, and the price will be high.";
    } else {
        $condition_message = "Your keyboard is in fair condition.";
    }
    
	// Store result and return it as a JSON response
    $_SESSION['condition_message'] = $condition_message;
    
  
    header('Content-Type: application/json');
    echo json_encode(['message' => $condition_message]);
    session_write_close();
    exit();
}

include 'header.php';
?>

<div class="container">
    <h2>Tell us more about your keyboard</h2>
    <p>Please answer a few questions about your keyboard.</p>
    
    <form id="keyboardForm" action="" method="POST">
        <div class="question">
            <h3>1. Are all keys working?</h3>
            <p>Test all keys to ensure they respond correctly.</p>
            <input type="radio" id="keys_yes" name="all_keys_work" value="Yes" required>
            <label for="keys_yes">Yes</label>
            <input type="radio" id="keys_no" name="all_keys_work" value="No">
            <label for="keys_no">No</label>
        </div>

        <div class="question">
            <h3>2. Is USB/Bluetooth functioning?</h3>
            <p>Check if the keyboard connects properly via USB or Bluetooth.</p>
            <input type="radio" id="usb_yes" name="usb_bluetooth_ok" value="Yes" required>
            <label for="usb_yes">Yes</label>
            <input type="radio" id="usb_no" name="usb_bluetooth_ok" value="No">
            <label for="usb_no">No</label>
        </div>

        <div class="question">
            <h3>3. Is the backlight working (if applicable)?</h3>
            <p>Verify if the keyboard backlight functions correctly.</p>
            <input type="radio" id="backlight_yes" name="backlight_working" value="Yes" required>
            <label for="backlight_yes">Yes</label>
            <input type="radio" id="backlight_no" name="backlight_working" value="No">
            <label for="backlight_no">No</label>
        </div>

        <div class="question">
            <h3>4. Is the keyboard physically clean and undamaged?</h3>
            <p>Check for physical damage, stains, or wear and tear.</p>
            <input type="radio" id="physical_yes" name="physical_condition" value="Yes" required>
            <label for="physical_yes">Yes</label>
            <input type="radio" id="physical_no" name="physical_condition" value="No">
            <label for="physical_no">No</label>
        </div>

        <div class="question">
            <h3>5. Do you have any accessories (extra keys, case)?</h3>
            <p>Including accessories may increase the estimated value.</p>
            <input type="radio" id="accessories_yes" name="has_accessories" value="Yes" required>
            <label for="accessories_yes">Yes</label>
            <input type="radio" id="accessories_no" name="has_accessories" value="No">
            <label for="accessories_no">No</label>
        </div>

        <button type="submit">Continue →</button>
    </form>
</div>

<script>
document.getElementById('keyboardForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        window.location.href = 'schedule_appointment.php';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing your request.');
    });
});
</script>

<?php include 'footer.php'; ?>