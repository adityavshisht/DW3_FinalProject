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
$successMessage = ''; // For inline success message

// Retrieve condition message from session
$condition_message = isset($_SESSION['condition_message']) ? $_SESSION['condition_message'] : 'Not specified';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate all required fields are filled
    if (
        !empty($_POST['name']) &&
        !empty($_POST['email']) &&
        !empty($_POST['phone']) &&
        !empty($_POST['address']) &&
        !empty($_POST['equipment_type']) &&
        !empty($_POST['preferred_date']) &&
        !empty($_POST['preferred_time'])
    ) {
        // Sanitize inputs
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $address = htmlspecialchars($_POST['address']);
        $equipment_type = htmlspecialchars($_POST['equipment_type']);
        $condition = htmlspecialchars($_POST['condition']);
        $preferred_date = $_POST['preferred_date'];
        $preferred_time = $_POST['preferred_time'];

        try {
            // Insert seller info into the database
            $sql = "INSERT INTO seller_info (name, email, phone, address, preferred_date, preferred_time, created_at,equipment_type, equipment_condition) 
                    VALUES (:name, :email, :phone, :address, :preferred_date, :preferred_time, NOW(), :equipment_type, :condition)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':address' => $address,
                ':equipment_type' => $equipment_type,
                ':condition' => $condition,
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
            $slot_time = '00:00:00';
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

            // Set success message
            if ($appointmentConfirmed) {
                $successMessage = "Appointment scheduled for $scheduledDate at $scheduledTime. Redirecting to home page...";
            }

        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage()); // Log to error log
            $errorMessage = "Error scheduling appointment: " . $e->getMessage();
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
        <p class="error-message"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <!-- Display success message and redirect -->
    <?php if ($appointmentConfirmed): ?>
        <!-- Meta refresh to redirect after 3 seconds -->
        <meta http-equiv="refresh" content="3;url=../index.php">
        <p style="color: green; text-align: center; font-size: 16px; margin: 20px 0;">
            <?php echo $successMessage; ?>
        </p>
        
    <!-- Appointment form -->    
    <?php else: ?>
        <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="appointment-form">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" required></textarea>
            </div>

            <div class="form-group">
                <label for="equipment_type">Equipment Type:</label>
                <select id="equipment_type" name="equipment_type" required>
                    <option value="">-- Select Equipment Type --</option>
                    <option value="Phone">Phone</option>
                    <option value="Tablet">Tablet</option>
                    <option value="Laptop">Laptop</option>
                    <option value="Smart Watches">Smart Watches</option>
                    <option value="Computer Accessories">Computer Accessories</option>
                    <option value="Headphones">Headphones</option>
                </select>
            </div>

            <div class="form-group">
                <label for="condition">Condition of Equipment:</label>
                <input type="text" id="condition" name="condition" value="<?= htmlspecialchars($condition_message) ?>" readonly>
            </div>

            <div class="form-group">
                <label for="preferred_date">Preferred Date:</label>
                <input type="date" id="preferred_date" name="preferred_date" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
            </div>

            <div class="form-group">
                <label for="preferred_time">Preferred Time:</label>
                <select id="preferred_time" name="preferred_time" required>
                    <option value="">-- Select Time Slot --</option>
                    <option value="9:00-11:00">9:00 AM - 11:00 AM</option>
                    <option value="11:00-13:00">11:00 AM - 1:00 PM</option>
                    <option value="13:00-15:00">1:00 PM - 3:00 PM</option>
                    <option value="15:00-17:00">3:00 PM - 5:00 PM</option>
                </select>
            </div>

            <button type="submit">Schedule Appointment →</button>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>