<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$appointmentConfirmed = false;
$scheduledDate = '';
$scheduledTime = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !empty($_POST['name']) &&
        !empty($_POST['email']) &&
        !empty($_POST['phone']) &&
        !empty($_POST['address']) &&
        !empty($_POST['preferred_date']) &&
        !empty($_POST['preferred_time'])
    ) {
        $_SESSION['appointment'] = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'preferred_date' => $_POST['preferred_date'],
            'preferred_time' => $_POST['preferred_time']
        ];

        $appointmentConfirmed = true;
        $scheduledDate = $_POST['preferred_date'];
        $scheduledTime = $_POST['preferred_time'];
    } else {
        echo '<p style="color: red; text-align:center;">❌ Please fill out all required fields.</p>';
    }
}
?>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Schedule Your Appointment</h2>
    <p>Please provide your details for our technician to visit and inspect your item.</p>

    <?php if ($appointmentConfirmed): ?>
        <!-- Confirmation JS -->
        <script>
            setTimeout(function() {
                alert("✅ Appointment scheduled for <?= htmlspecialchars($scheduledDate) ?> at <?= htmlspecialchars($scheduledTime) ?>.");
                window.location.href = "index.php";
            }, 100);
        </script>
    <?php else: ?>
        <!-- Appointment Form -->
        <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <label>Full Name:</label>
            <input type="text" name="name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Phone Number:</label>
            <input type="tel" name="phone" required>

            <label>Address:</label>
            <textarea name="address" required></textarea>

            <label>Preferred Date:</label>
            <input type="date" name="preferred_date" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>

            <label>Preferred Time:</label>
            <select name="preferred_time" required>
                <option value="">-- Select Time Slot --</option>
                <option value="9:00-11:00">9:00 AM - 11:00 AM</option>
                <option value="11:00-13:00">11:00 AM - 1:00 PM</option>
                <option value="13:00-15:00">1:00 PM - 3:00 PM</option>
                <option value="15:00-17:00">3:00 PM - 5:00 PM</option>
            </select>

            <button type="submit">Schedule Appointment →</button>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
