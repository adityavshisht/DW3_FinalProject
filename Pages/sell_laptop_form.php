<?php
session_start();
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['turns_on'])) {
   
    $_SESSION['laptop_condition'] = [
        'turns_on' => $_POST['turns_on'],
        'screen_condition' => $_POST['screen_condition'],
        'keyboard_functioning' => $_POST['keyboard_functioning'],
        'battery_condition' => $_POST['battery_condition'],
        'ports_functional' => $_POST['ports_functional'],
        'original_charger' => $_POST['original_charger'],
        'warranty' => $_POST['warranty']
    ];
    
    include 'schedule_appointment.php';
    exit();
}
?>

    <div class="container">
        <h2>Tell us more about your laptop</h2>
        <p>Please answer a few questions about your laptop.</p>
        
        <form action="" method="POST">
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
                <input type="radio" id="screen_condition_yes" name="screen_condition" value="Yes" required>
                <label for="screen_condition_yes">Yes</label>
                <input type="radio" id="screen_condition_no" name="screen_condition" value="No">
                <label for="screen_condition_no">No</label>
            </div>

            <div class="question">
                <h3>3. Are all keyboard keys functioning properly?</h3>
                <p>Test whether all keys on the keyboard are working.</p>
                <input type="radio" id="keyboard_functioning_yes" name="keyboard_functioning" value="Yes" required>
                <label for="keyboard_functioning_yes">Yes</label>
                <input type="radio" id="keyboard_functioning_no" name="keyboard_functioning" value="No">
                <label for="keyboard_functioning_no">No</label>
            </div>

            <div class="question">
                <h3>4. Is the battery in good condition and holds a charge?</h3>
                <p>Verify if the battery lasts a reasonable duration.</p>
                <input type="radio" id="battery_condition_yes" name="battery_condition" value="Yes" required>
                <label for="battery_condition_yes">Yes</label>
                <input type="radio" id="battery_condition_no" name="battery_condition" value="No">
                <label for="battery_condition_no">No</label>
            </div>

            <div class="question">
                <h3>5. Are all ports and connectivity options functional?</h3>
                <p>Check if USB, HDMI, charging, and headphone ports are working.</p>
                <input type="radio" id="ports_functional_yes" name="ports_functional" value="Yes" required>
                <label for="ports_functional_yes">Yes</label>
                <input type="radio" id="ports_functional_no" name="ports_functional" value="No">
                <label for="ports_functional_no">No</label>
            </div>

            <div class="question">
                <h3>6. Do you have the original charger and accessories?</h3>
                <p>Providing the original charger may increase the estimated value.</p>
                <input type="radio" id="original_charger_yes" name="original_charger" value="Yes" required>
                <label for="original_charger_yes">Yes</label>
                <input type="radio" id="original_charger_no" name="original_charger" value="No">
                <label for="original_charger_no">No</label>
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
    </div>
    <?php include 'footer.php'; ?>
