<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'header.php';
include 'database_connection.php'; // Provides $pdo connection

// Initialize variables
$appointmentConfirmed = false;
$scheduledDate = '';
$scheduledTime = '';
$errorMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Validate all required fields are filled
    if (
        !empty($_POST['name']) &&
        !empty($_POST['email']) &&
        !empty($_POST['phone']) &&
        !empty($_POST['address']) &&
        !empty($_POST['preferred_date']) &&
        !empty($_POST['preferred_time'])
    ) {
		// Sanitize inputs
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $address = htmlspecialchars($_POST['address']);
        $preferred_date = $_POST['preferred_date'];
        $preferred_time = $_POST['preferred_time'];

        try {
            // Insert seller info into the database
            $sql = "INSERT INTO seller_info (name, email, phone, address, preferred_date, preferred_time, created_at) 
                    VALUES (:name, :email, :phone, :address, :preferred_date, :preferred_time, NOW())";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':address' => $address,
                ':preferred_date' => $preferred_date,
                ':preferred_time' => $preferred_time
            ]);
			
            // Set confirmation flag and store submitted date/time
            $appointmentConfirmed = true;
            $scheduledDate = $preferred_date;
            $scheduledTime = $preferred_time;
            $seller_id = $pdo->lastInsertId();

            // Assign internal slot ID and actual time value
            $slot_id = 0;
            switch ($preferred_time) {
                case '9:00-11:00':
                    $slot_id = 1;
                    $slot_time = '14:00:00';
                    break;
                case '11:00-13:00':
                    $slot_id = 2;
                    $slot_time = '10:30:00';
                    break;
                case '13:00-15:00':
                    $slot_id = 3;
                    $slot_time = '13:00:00';
                    break;
                case '15:00-17:00':
                    $slot_id = 4;
                    $slot_time = '15:00:00';
                    break;
                default:
                    $slot_id = 0;
                    $slot_time = '00:00:00';
            }

            $slot_date = $preferred_date;
            $status = 'Confirmed';

            // Insert slot booking if time slot is valid
            if ($slot_id > 0) {
                $sql_pickup = "INSERT INTO pickup_slots (slot_id, seller_id, slot_date, slot_time, status) 
                              VALUES (:slot_id, :seller_id, :slot_date, :slot_time, :status)";
                
                $stmt_pickup = $pdo->prepare($sql_pickup);
                $stmt_pickup->execute([
                    ':slot_id' => $slot_id,
                    ':seller_id' => $seller_id,
                    ':slot_date' => $slot_date,
                    ':slot_time' => $slot_time,
                    ':status' => $status
                ]);
            } else {
                $errorMessage = "Invalid time slot selected.";
            }
        } catch (PDOException $e) {
			// Optional: error_log($e->getMessage());
            $errorMessage = "Error scheduling appointment. Please try again.";
        }
    } else {
        $errorMessage = "Please fill out all required fields.";
    }
}
?>

<div class="container">
    <h2>Schedule Your Appointment</h2>
    <p>Please provide your details for our technician to visit and inspect your item.</p>

    <!-- Display error messages -->
	<?php if (!empty($errorMessage)): ?>
        <p style="color: red; text-align:center;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <!-- If confirmed, show success and redirect -->
	<?php if ($appointmentConfirmed): ?>
        <script>
            setTimeout(function() {
                alert("Appointment scheduled for <?= htmlspecialchars($scheduledDate) ?> at <?= htmlspecialchars($scheduledTime) ?>.");
                window.location.href = "index.php";
            }, 100);
        </script>
		
	<!-- Appointment form -->	
    <?php else: ?>
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