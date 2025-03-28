<?php
session_start();
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['all_keys_work'])) {
    $_SESSION['keyboard_condition'] = [
        'all_keys_work' => $_POST['all_keys_work'],
        'usb_bluetooth_ok' => $_POST['usb_bluetooth_ok'],
        'backlight_working' => $_POST['backlight_working'],
        'physical_condition' => $_POST['physical_condition'],
        'has_accessories' => $_POST['has_accessories']
    ];
    include 'schedule_appointment.php';
    exit();
}
?>

<div class="container">
    <h2>Keyboard Condition</h2>
    <form method="POST">
        <label>1. Are all keys working?</label><br>
        <input type="radio" name="all_keys_work" value="Yes" required> Yes
        <input type="radio" name="all_keys_work" value="No"> No<br>

        <label>2. Is USB/Bluetooth functioning?</label><br>
        <input type="radio" name="usb_bluetooth_ok" value="Yes" required> Yes
        <input type="radio" name="usb_bluetooth_ok" value="No"> No<br>

        <label>3. Is the backlight working (if applicable)?</label><br>
        <input type="radio" name="backlight_working" value="Yes" required> Yes
        <input type="radio" name="backlight_working" value="No"> No<br>

        <label>4. Is the keyboard physically clean and undamaged?</label><br>
        <input type="radio" name="physical_condition" value="Yes" required> Yes
        <input type="radio" name="physical_condition" value="No"> No<br>

        <label>5. Any accessories (extra keys, case)?</label><br>
        <input type="radio" name="has_accessories" value="Yes" required> Yes
        <input type="radio" name="has_accessories" value="No"> No<br>

        <button type="submit">Continue →</button>
    </form>
</div>
<?php include 'footer.php'; ?>
