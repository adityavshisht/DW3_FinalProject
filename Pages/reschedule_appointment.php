<?php
session_start();
include 'database_connection.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];
$slot_id = isset($_GET['slot_id']) ? $_GET['slot_id'] : null;
$errorMessage = '';
$successMessage = '';

if (!$slot_id) {
    header("Location: appointment_history.php");
    exit();
}

// Fetch current appointment details
try {
    $sql = "SELECT ps.*, si.equipment_type, si.equipment_condition, si.id as seller_info_id
            FROM pickup_slots ps 
            LEFT JOIN seller_info si ON ps.seller_id = si.id 
            WHERE ps.slot_id = :slot_id AND ps.seller_id = :seller_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['slot_id' => $slot_id, 'seller_id' => $seller_id]);
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$appointment) {
        header("Location: appointment_history.php");
        exit();
    }
} catch (PDOException $e) {
    $errorMessage = "Error fetching appointment details.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_date = $_POST['preferred_date'];
    $new_time = $_POST['preferred_time'];
    
    // Convert time slot to actual time
    $slot_time = '00:00:00';
    switch ($new_time) {
        case '9:00-11:00':
            $slot_time = '10:00:00';
            break;
        case '11:00-13:00':
            $slot_time = '11:30:00';
            break;
        case '13:00-15:00':
            $slot_time = '14:00:00';
            break;
        case '15:00-17:00':
            $slot_time = '16:00:00';
            break;
    }
    
    try {
        // Update the appointment
        $sql = "UPDATE pickup_slots 
                SET slot_date = :slot_date, 
                    slot_time = :slot_time,
                    status = 'Rescheduled'
                WHERE slot_id = :slot_id AND seller_id = :seller_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'slot_date' => $new_date,
            'slot_time' => $slot_time,
            'slot_id' => $slot_id,
            'seller_id' => $seller_id
        ]);
        
        // Update seller_info table if we have a seller_info_id
        if (isset($appointment['seller_info_id'])) {
            $sql_seller = "UPDATE seller_info 
                          SET preferred_date = :preferred_date,
                              preferred_time = :preferred_time
                          WHERE id = :seller_info_id";
            
            $stmt_seller = $pdo->prepare($sql_seller);
            $stmt_seller->execute([
                'preferred_date' => $new_date,
                'preferred_time' => $new_time,
                'seller_info_id' => $appointment['seller_info_id']
            ]);
        }
        
        $successMessage = "Appointment rescheduled successfully. Redirecting...";
        header("refresh:3;url=appointment_history.php");
        
    } catch (PDOException $e) {
        $errorMessage = "Error rescheduling appointment: " . $e->getMessage();
    }
}
?>

<div class="container">
    <h2>Reschedule Appointment</h2>
    
    <?php if (!empty($errorMessage)): ?>
        <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
    <?php endif; ?>
    
    <?php if (!empty($successMessage)): ?>
        <p class="success-message" style="color: green; text-align: center;"><?= htmlspecialchars($successMessage) ?></p>
    <?php else: ?>
        <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?slot_id=' . htmlspecialchars($slot_id) ?>" class="appointment-form">
            <div class="form-group">
                <label>Current Date:</label>
                <input type="text" value="<?= htmlspecialchars($appointment['slot_date']) ?>" readonly class="form-control">
            </div>
            
            <div class="form-group">
                <label>Current Time:</label>
                <input type="text" value="<?= date('h:i A', strtotime($appointment['slot_time'])) ?>" readonly class="form-control">
            </div>
            
            <div class="form-group">
                <label>Equipment Type:</label>
                <input type="text" value="<?= htmlspecialchars($appointment['equipment_type'] ?? 'Not specified') ?>" readonly class="form-control">
            </div>
            
            <div class="form-group">
                <label for="preferred_date">New Date:</label>
                <input type="date" id="preferred_date" name="preferred_date" 
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>" 
                       class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="preferred_time">New Time:</label>
                <select id="preferred_time" name="preferred_time" class="form-control" required>
                    <option value="">-- Select Time Slot --</option>
                    <option value="9:00-11:00">9:00 AM - 11:00 AM</option>
                    <option value="11:00-13:00">11:00 AM - 1:00 PM</option>
                    <option value="13:00-15:00">1:00 PM - 3:00 PM</option>
                    <option value="15:00-17:00">3:00 PM - 5:00 PM</option>
                </select>
            </div>
            
            <div class="form-group" style="text-align: center;">
                <button type="submit" class="btn">Reschedule Appointment</button>
                <a href="appointment_history.php" class="btn" style="margin-left: 10px;">Cancel</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?> 