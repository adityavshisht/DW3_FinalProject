<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'header.php';
include 'database_connection.php'; 

$appointmentConfirmed = false;
$scheduledDate = '';
$scheduledTime = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !empty($_POST['name']) &&
        !empty($_POST['email']) &&
        !empty($_POST['phone']) &&
        !empty($_POST['address']) &&
        !empty($_POST['preferred_date']) &&
        !empty($_POST['preferred_time'])
    ) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $address = htmlspecialchars($_POST['address']);
        $preferred_date = $_POST['preferred_date'];
        $preferred_time = $_POST['preferred_time'];

       
        $sql = "INSERT INTO seller_info (name, email, phone, address, preferred_date, preferred_time, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $phone, $address, $preferred_date, $preferred_time);

        if ($stmt->execute()) {
            $appointmentConfirmed = true;
            $scheduledDate = $preferred_date;
            $scheduledTime = $preferred_time;
          
            $seller_id = $conn->insert_id;

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

            $product_id = 1; 
            $slot_date = $preferred_date;
            $status = 'Confirmed';

            if ($slot_id > 0) {
                $sql_pickup = "INSERT INTO pickup_slots (slot_id, product_id, seller_id, slot_date, slot_time, status) 
                               VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_pickup = $conn->prepare($sql_pickup);
                $stmt_pickup->bind_param("iiisss", $slot_id, $product_id, $seller_id, $slot_date, $slot_time, $status);

                if (!$stmt_pickup->execute()) {
                    $errorMessage = "Error saving to pickup_slots table. Please try again.";
                }
                $stmt_pickup->close();
            } else {
                $errorMessage = "Invalid time slot selected.";
            }
        } else {
            $errorMessage = "Error scheduling appointment. Please try again.";
        }

        $stmt->close();
    } else {
        $errorMessage = "Please fill out all required fields.";
    }
}

$conn->close();
?>

<div class="container">
    <h2>Schedule Your Appointment</h2>
    <p>Please provide your details for our technician to visit and inspect your item.</p>

    <?php if (!empty($errorMessage)): ?>
        <p style="color: red; text-align:center;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <?php if ($appointmentConfirmed): ?>
        <script>
            setTimeout(function() {
                alert("Appointment scheduled for <?= htmlspecialchars($scheduledDate) ?> at <?= htmlspecialchars($scheduledTime) ?>.");
                window.location.href = "index.php";
            }, 100);
        </script>
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