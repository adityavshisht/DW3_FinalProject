<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['working_display'])) {
    
    $_SESSION['smartwatch_condition'] = [
        'working_display' => $_POST['working_display'],
        'battery_health' => $_POST['battery_health'],
        'strap_condition' => $_POST['strap_condition'],
        'has_charger' => $_POST['has_charger'],
        'water_damage' => $_POST['water_damage']
    ];
    
    
    $answers = [
        $_POST['working_display'],
        $_POST['battery_health'],
        $_POST['strap_condition'],
        $_POST['has_charger'],
        $_POST['water_damage'] === 'Yes' ? 'No' : 'Yes' 
    ];
    
    $total_questions = 5;
    $no_count = count(array_filter($answers, function($answer) {
        return $answer === 'No';
    }));
    $no_percentage = ($no_count / $total_questions) * 100;
    
    
    if ($no_percentage > 75) {
        $condition_message = "The condition of your smartwatch is not good enough, and the estimated price may be very low.";
    } elseif ($no_percentage > 50) {
        $condition_message = "The condition of your smartwatch is good, and the price will be moderate.";
    } elseif ($no_count === 0) {
        $condition_message = "Your smartwatch is in great condition, and the price will be high.";
    } else {
        $condition_message = "Your smartwatch is in fair condition.";
    }
    
    $_SESSION['condition_message'] = $condition_message;
    
    
    header('Content-Type: application/json');
    echo json_encode(['message' => $condition_message]);
    session_write_close();
    exit();
}

include 'header.php';
?>

<div class="container">
    <h2>Tell us more about your smartwatch</h2>
    <p>Please answer a few questions about your smartwatch.</p>
    
    <form id="smartwatchForm" action="" method="POST">
        <div class="question">
            <h3>1. Is the display working properly?</h3>
            <p>Check if the screen displays content correctly without issues.</p>
            <input type="radio" id="display_yes" name="working_display" value="Yes" required>
            <label for="display_yes">Yes</label>
            <input type="radio" id="display_no" name="working_display" value="No">
            <label for="display_no">No</label>
        </div>

        <div class="question">
            <h3>2. Is the battery in good condition?</h3>
            <p>Verify if the battery holds a charge for a reasonable duration.</p>
            <input type="radio" id="battery_yes" name="battery_health" value="Yes" required>
            <label for="battery_yes">Yes</label>
            <input type="radio" id="battery_no" name="battery_health" value="No">
            <label for="battery_no">No</label>
        </div>

        <div class="question">
            <h3>3. Is the strap in good condition?</h3>
            <p>Check for wear, tears, or damage to the strap.</p>
            <input type="radio" id="strap_yes" name="strap_condition" value="Yes" required>
            <label for="strap_yes">Yes</label>
            <input type="radio" id="strap_no" name="strap_condition" value="No">
            <label for="strap_no">No</label>
        </div>

        <div class="question">
            <h3>4. Do you have the original charger?</h3>
            <p>Including the original charger may increase the estimated value.</p>
            <input type="radio" id="charger_yes" name="has_charger" value="Yes" required>
            <label for="charger_yes">Yes</label>
            <input type="radio" id="charger_no" name="has_charger" value="No">
            <label for="charger_no">No</label>
        </div>

        <div class="question">
            <h3>5. Has it suffered any water damage?</h3>
            <p>Check for signs of water exposure or damage.</p>
           
            <input type="radio" id="water_yes" name="water_damage" value="Yes">
            <label for="water_yes">Yes</label>
            <input type="radio" id="water_no" name="water_damage" value="No" required>
            <label for="water_no">No</label>
        </div>

        <button type="submit">Continue →</button>
    </form>
</div>

<script>
document.getElementById('smartwatchForm').addEventListener('submit', function(e) {
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