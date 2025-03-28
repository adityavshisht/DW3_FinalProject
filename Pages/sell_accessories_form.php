<?php
session_start();
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fully_functional'])) {
    $_SESSION['accessories_condition'] = [
        'fully_functional' => $_POST['fully_functional'],
        'cables_included' => $_POST['cables_included'],
        'physical_damage' => $_POST['physical_damage'],
        'original_box' => $_POST['original_box']
    ];
    include 'schedule_appointment.php';
    exit();
}
?>

<div class="container">
    <h2>Accessory Condition</h2>
    <form method="POST">
        <label>1. Is the item fully functional?</label><br>
        <input type="radio" name="fully_functional" value="Yes" required> Yes
        <input type="radio" name="fully_functional" value="No"> No<br>

        <label>2. Are all cables and parts included?</label><br>
        <input type="radio" name="cables_included" value="Yes" required> Yes
        <input type="radio" name="cables_included" value="No"> No<br>

        <label>3. Any physical damage?</label><br>
        <input type="radio" name="physical_damage" value="No" required> No
        <input type="radio" name="physical_damage" value="Yes"> Yes<br>

        <label>4. Do you have the original box/packaging?</label><br>
        <input type="radio" name="original_box" value="Yes" required> Yes
        <input type="radio" name="original_box" value="No"> No<br>

        <button type="submit">Continue →</button>
    </form>
</div>
<?php include 'footer.php'; ?>
