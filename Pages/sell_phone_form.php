<?php
session_start();
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calls'])) {
  
    $_SESSION['phone_condition'] = [
        'calls' => $_POST['calls'],
        'touchscreen' => $_POST['touchscreen'],
        'original_screen' => $_POST['original_screen'],
        'warranty' => $_POST['warranty'],
        'bill' => $_POST['bill']
    ];
    
    include 'schedule_appointment.php';
    exit();
}
?>

    <div class="container">
        <h2>Tell us more about your device</h2>
        <p>Please answer a few questions about your device.</p>
        
        <form action="" method="POST">
            <div class="question">
                <h3>1. Are you able to make and receive calls?</h3>
                <p>Check your device for cellular network connectivity issues.</p>
                <input type="radio" id="calls_yes" name="calls" value="Yes" required>
                <label for="calls_yes">Yes</label>
                <input type="radio" id="calls_no" name="calls" value="No">
                <label for="calls_no">No</label>
            </div>

            <div class="question">
                <h3>2. Is your device’s touch screen working properly?</h3>
                <p>Check the touch screen functionality of your phone.</p>
                <input type="radio" id="touchscreen_yes" name="touchscreen" value="Yes" required>
                <label for="touchscreen_yes">Yes</label>
                <input type="radio" id="touchscreen_no" name="touchscreen" value="No">
                <label for="touchscreen_no">No</label>
            </div>

            <div class="question">
                <h3>3. Is your phone’s screen original?</h3>
                <p>Pick “Yes” if screen was never changed or was changed by an Authorized Service Center. Pick “No” if screen was changed at a local shop.</p>
                <input type="radio" id="original_screen_yes" name="original_screen" value="Yes" required>
                <label for="original_screen_yes">Yes</label>
                <input type="radio" id="original_screen_no" name="original_screen" value="No">
                <label for="original_screen_no">No</label>
            </div>

            <div class="question">
                <h3>4. Is your device under manufacturer warranty?</h3>
                <p>You can get a better price for your device if it’s under manufacturer warranty with a GST valid bill.</p>
                <input type="radio" id="warranty_yes" name="warranty" value="Yes" required>
                <label for="warranty_yes">Yes</label>
                <input type="radio" id="warranty_no" name="warranty" value="No">
                <label for="warranty_no">No</label>
            </div>

            <div class="question">
                <h3>5. Do you have valid bill with the same IMEI?</h3>
                <p>Make sure your bill has device IMEI mentioned on it.</p>
                <input type="radio" id="bill_yes" name="bill" value="Yes" required>
                <label for="bill_yes">Yes</label>
                <input type="radio" id="bill_no" name="bill" value="No">
                <label for="bill_no">No</label>
            </div>

            <button type="submit">Continue →</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
