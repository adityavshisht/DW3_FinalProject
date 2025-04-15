<?php
session_start();

// Handle form submission for accessory condition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fully_functional'])) {
    
	 // Store responses in session
    $_SESSION['accessories_condition'] = [
        'fully_functional' => $_POST['fully_functional'],
        'cables_included' => $_POST['cables_included'],
        'physical_damage' => $_POST['physical_damage'],
        'original_box' => $_POST['original_box']
    ];
    
    // Convert answers to negatives for condition scoring
    $answers = [
        $_POST['fully_functional'] === 'No' ? 1 : 0,  
        $_POST['cables_included'] === 'No' ? 1 : 0,   
        $_POST['physical_damage'] === 'Yes' ? 1 : 0,  
        $_POST['original_box'] === 'No' ? 1 : 0       
    ];
    
    $total_questions = 4;
    $negative_count = array_sum($answers);
    $negative_percentage = ($negative_count / $total_questions) * 100;
    
    // Determine the condition message based on negative answers
    if ($negative_percentage > 75) {
        $condition_message = "The condition of your accessories is not good enough, and the estimated price may be very low.";
    } elseif ($negative_percentage > 50) {
        $condition_message = "The condition of your accessories is good, and the price will be moderate.";
    } elseif ($negative_count === 0) {
        $condition_message = "Your accessories are in great condition, and the price will be high.";
    } else {
        $condition_message = "Your accessories are in fair condition.";
    }
    
	// Store the condition message in session and return response
    $_SESSION['condition_message'] = $condition_message;
    
   
    header('Content-Type: application/json');
    echo json_encode(['message' => $condition_message]);
    session_write_close();
    exit();
}

include 'header.php';
?>

<div class="container">
    <h2>Accessory Condition</h2>
    <p>Please answer a few questions about your accessories.</p>
    
    <form id="accessoryForm" method="POST">
        <div class="question">
            <h3>1. Is the item fully functional?</h3>
            <p>Check if all features and functions work as intended.</p>
            <input type="radio" id="functional_yes" name="fully_functional" value="Yes" required>
            <label for="functional_yes">Yes</label>
            <input type="radio" id="functional_no" name="fully_functional" value="No">
            <label for="functional_no">No</label>
        </div>

        <div class="question">
            <h3>2. Are all cables and parts included?</h3>
            <p>Verify if all original cables and components are present.</p>
            <input type="radio" id="cables_yes" name="cables_included" value="Yes" required>
            <label for="cables_yes">Yes</label>
            <input type="radio" id="cables_no" name="cables_included" value="No">
            <label for="cables_no">No</label>
        </div>

        <div class="question">
            <h3>3. Any physical damage?</h3>
            <p>Check for scratches, dents, or other visible damage.</p>
            <input type="radio" id="damage_yes" name="physical_damage" value="Yes">
            <label for="damage_yes">Yes</label>
            <input type="radio" id="damage_no" name="physical_damage" value="No" required>
            <label for="damage_no">No</label>
        </div>

        <div class="question">
            <h3>4. Do you have the original box/packaging?</h3>
            <p>Having the original packaging can increase the value.</p>
            <input type="radio" id="box_yes" name="original_box" value="Yes" required>
            <label for="box_yes">Yes</label>
            <input type="radio" id="box_no" name="original_box" value="No">
            <label for="box_no">No</label>
        </div>

        <button type="submit">Continue →</button>
    </form>
    <div id="conditionMessage" class="condition-message"></div>
</div>

<script>
// Handle form submission using Fetch API
document.getElementById('accessoryForm').addEventListener('submit', function(e) {
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