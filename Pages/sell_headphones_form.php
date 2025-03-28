<?php
session_start();
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['audio_quality'])) {
    $_SESSION['headphones_condition'] = [
        'audio_quality' => $_POST['audio_quality'],
        'mic_working' => $_POST['mic_working'],
        'earpad_condition' => $_POST['earpad_condition'],
        'connectivity' => $_POST['connectivity'],
        'has_box' => $_POST['has_box']
    ];
    include 'schedule_appointment.php';
    exit();
}
?>

<div class="container">
    <h2>Headphones Condition</h2>
    <form method="POST">
        <label>1. Is the audio quality clear?</label><br>
        <input type="radio" name="audio_quality" value="Yes" required> Yes
        <input type="radio" name="audio_quality" value="No"> No<br>

        <label>2. Is the microphone working?</label><br>
        <input type="radio" name="mic_working" value="Yes" required> Yes
        <input type="radio" name="mic_working" value="No"> No<br>

        <label>3. Are the earpads in good condition?</label><br>
        <input type="radio" name="earpad_condition" value="Yes" required> Yes
        <input type="radio" name="earpad_condition" value="No"> No<br>

        <label>4. Does Bluetooth or wire work properly?</label><br>
        <input type="radio" name="connectivity" value="Yes" required> Yes
        <input type="radio" name="connectivity" value="No"> No<br>

        <label>5. Do you have the box or packaging?</label><br>
        <input type="radio" name="has_box" value="Yes" required> Yes
        <input type="radio" name="has_box" value="No"> No<br>

        <button type="submit">Continue →</button>
    </form>
</div>
<?php include 'footer.php'; ?>
