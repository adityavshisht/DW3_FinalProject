<?php
// Start a new session or resume the existing one
session_start();
include 'database_connection.php';
include 'header.php';

// Initialize variables to store form data and messages
$appointmentConfirmed = false;
$scheduledDate = '';
$scheduledTime = '';
$errorMessage = '';
$successMessage = '';

// Get the equipment condition from the previous assessment
$condition_message = isset($_SESSION['condition_message']) ? $_SESSION['condition_message'] : 'Not specified';

// When the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Make sure all required fields are filled out
    if (
        !empty($_POST['name']) &&
        !empty($_POST['email']) &&
        !empty($_POST['phone']) &&
        !empty($_POST['address']) &&
        !empty($_POST['equipment_type']) &&
        !empty($_POST['preferred_date']) &&
        !empty($_POST['preferred_time'])
    ) {
        // Clean up the submitted data to prevent security issues
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $address = htmlspecialchars($_POST['address']);
        $equipment_type = htmlspecialchars($_POST['equipment_type']);
        $condition = htmlspecialchars($_POST['condition']);
        $preferred_date = $_POST['preferred_date'];
        $preferred_time = $_POST['preferred_time'];

        try {
            // Save the seller's information to our database
            $sql = "INSERT INTO seller_info (name, email, phone, address, preferred_date, preferred_time, created_at, equipment_type, equipment_condition) 
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
            
            // Store the appointment details for confirmation
            $appointmentConfirmed = true;
            $scheduledDate = $preferred_date;
            $scheduledTime = $preferred_time;
            
            // Use the logged-in user's ID for the appointment
            $seller_id = $_SESSION['user_id'];

            // Convert the selected time slot to our internal scheduling system
            $slot_id = 0;
            $slot_time = '00:00:00';
            switch ($preferred_time) {
                case '9:00-11:00':
                    $slot_id = 1;
                    $slot_time = '10:00:00'; // Mid-morning slot
                    break;
                case '11:00-13:00':
                    $slot_id = 2;
                    $slot_time = '11:30:00'; // Late morning slot
                    break;
                case '13:00-15:00':
                    $slot_id = 3;
                    $slot_time = '14:00:00'; // Early afternoon slot
                    break;
                case '15:00-17:00':
                    $slot_id = 4;
                    $slot_time = '16:00:00'; // Late afternoon slot
                    break;
            }

            // Set up the appointment details
            $slot_date = $preferred_date;
            $status = 'Confirmed';

            // Book the pickup slot in our system
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
                $errorMessage = "Sorry, we couldn't process your selected time slot. Please try again.";
            }

            // Show success message if everything went well
            if ($appointmentConfirmed) {
                $successMessage = "Great! Your appointment is scheduled for $scheduledDate at $scheduledTime. Taking you back to home page...";
            }

        } catch (PDOException $e) {
            // Log the error for our team to investigate
            error_log("Database error: " . $e->getMessage());
            $errorMessage = "Sorry, we couldn't schedule your appointment right now. Please try again later.";
        }
    } else {
        $errorMessage = "Please fill in all the required fields to schedule your appointment.";
    }
}
?>

<div class="container">
    <h2>Schedule Your Appointment</h2>
    <p>Let us know when you'd like our technician to visit and inspect your device.</p>

    <!-- Show any error messages -->
    <?php if (!empty($errorMessage)): ?>
        <p class="error-message"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <!-- Show success message and redirect -->
    <?php if ($appointmentConfirmed): ?>
        <meta http-equiv="refresh" content="3;url=../index.php">
        <p style="color: green; text-align: center; font-size: 16px; margin: 20px 0;">
            <?php echo $successMessage; ?>
        </p>
        
    <!-- Appointment booking form -->    
    <?php else: ?>
        <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="appointment-form">
            <!-- Personal Information -->
            <div class="form-group">
                <label for="name">Your Full Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone">Contact Number:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="address">Pickup Address:</label>
                <textarea id="address" name="address" required></textarea>
            </div>

            <!-- Device Information -->
            <div class="form-group">
                <label for="equipment_type">What are you selling?</label>
                <select id="equipment_type" name="equipment_type" required>
                    <option value="">-- Select Your Device Type --</option>
                    <option value="Phone">Phone</option>
                    <option value="Tablet">Tablet</option>
                    <option value="Laptop">Laptop</option>
                    <option value="Smart Watches">Smart Watch</option>
                    <option value="Computer Accessories">Computer Accessories</option>
                    <option value="Headphones">Headphones</option>
                </select>
            </div>

            <div class="form-group">
                <label for="condition">Device Condition Assessment:</label>
                <input type="text" id="condition" name="condition" value="<?= htmlspecialchars($condition_message) ?>" readonly>
            </div>

            <!-- Appointment Time Selection -->
            <div class="form-group">
                <label for="preferred_date">When would you like us to visit?</label>
                <input type="date" id="preferred_date" name="preferred_date" 
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
            </div>

            <div class="form-group">
                <label for="preferred_time">Choose a convenient time:</label>
                <select id="preferred_time" name="preferred_time" required>
                    <option value="">-- Select Your Preferred Time --</option>
                    <option value="9:00-11:00">Morning: 9:00 AM - 11:00 AM</option>
                    <option value="11:00-13:00">Mid-day: 11:00 AM - 1:00 PM</option>
                    <option value="13:00-15:00">Afternoon: 1:00 PM - 3:00 PM</option>
                    <option value="15:00-17:00">Late Afternoon: 3:00 PM - 5:00 PM</option>
                </select>
            </div>

            <button type="submit">Schedule My Appointment →</button>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>