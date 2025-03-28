<?php
session_start();
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['working_display'])) {
    $_SESSION['smartwatch_condition'] = [
        'working_display' => $_POST['working_display'],
        'battery_health' => $_POST['battery_health'],
        'strap_condition' => $_POST['strap_condition'],
        'has_charger' => $_POST['has_charger'],
        'water_damage' => $_POST['water_damage']
    ];
    include 'schedule_appointment.php';
    exit();
}
?>

<div class="container">
    <h2>Smartwatch Condition</h2>
    <form method="POST">
        <label>1. Is the display working properly?</label><br>
        <input type="radio" name="working_display" value="Yes" required> Yes
        <input type="radio" name="working_display" value="No"> No<br>

        <label>2. Is the battery in good condition?</label><br>
        <input type="radio" name="battery_health" value="Yes" required> Yes
        <input type="radio" name="battery_health" value="No"> No<br>

        <label>3. Is the strap in good condition?</label><br>
        <input type="radio" name="strap_condition" value="Yes" required> Yes
        <input type="radio" name="strap_condition" value="No"> No<br>

        <label>4. Do you have the original charger?</label><br>
        <input type="radio" name="has_charger" value="Yes" required> Yes
        <input type="radio" name="has_charger" value="No"> No<br>

        <label>5. Has it suffered any water damage?</label><br>
        <input type="radio" name="water_damage" value="No" required> No
        <input type="radio" name="water_damage" value="Yes"> Yes<br>

        <button type="submit">Continue →</button>
    </form>
</div>
<?php include 'footer.php'; ?>
