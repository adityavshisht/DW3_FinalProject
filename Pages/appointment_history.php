<?php
session_start();
include 'header.php';
include 'database_connection.php'; // This defines $pdo (not $conn)

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

// Handle appointment cancellation
if (isset($_POST['cancel_appointment'])) {
    $slot_id = $_POST['slot_id'];
    try {
        $sql = "UPDATE pickup_slots SET status = 'Cancelled' WHERE slot_id = :slot_id AND seller_id = :seller_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['slot_id' => $slot_id, 'seller_id' => $seller_id]);
        $successMessage = "Appointment cancelled successfully.";
    } catch (PDOException $e) {
        $errorMessage = "Error cancelling appointment.";
    }
}

try {
    // Join with seller_info table to get more details
    $sql = "SELECT ps.*, si.equipment_type, si.equipment_condition 
            FROM pickup_slots ps 
            JOIN seller_info si ON ps.seller_id = si.id 
            WHERE ps.seller_id = :seller_id 
            ORDER BY ps.slot_date, ps.slot_time";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['seller_id' => $seller_id]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $appointments = [];
    $errorMessage = "Error fetching appointments.";
}
?>

<main>
    <h2>Your Scheduled Appointments</h2>

    <?php if (isset($errorMessage)): ?>
        <p style="color: red; text-align: center;"><?= $errorMessage ?></p>
    <?php endif; ?>
    
    <?php if (isset($successMessage)): ?>
        <p style="color: green; text-align: center;"><?= $successMessage ?></p>
    <?php endif; ?>

    <?php if (count($appointments) > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0" style="margin: 20px auto; border-collapse: collapse;">
            <tr style="background-color: #f4f4f4;">
                <th>Date</th>
                <th>Time</th>
                <th>Equipment Type</th>
                <th>Condition</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($appointments as $row): 
                $datetime = strtotime($row['slot_date'] . ' ' . $row['slot_time']);
                $status = $datetime < time() ? 'Completed' : htmlspecialchars($row['status']);
                $canCancel = $status !== 'Completed' && $status !== 'Cancelled' && $datetime > time();
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['slot_date']) ?></td>
                    <td><?= date('h:i A', strtotime($row['slot_time'])) ?></td>
                    <td><?= htmlspecialchars($row['equipment_type']) ?></td>
                    <td><?= htmlspecialchars($row['equipment_condition']) ?></td>
                    <td><?= $status ?></td>
                    <td>
                        <?php if ($canCancel): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="slot_id" value="<?= $row['slot_id'] ?>">
                                <button type="submit" name="cancel_appointment" class="btn btn-danger" 
                                        onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                    Cancel
                                </button>
                            </form>
                            <a href="reschedule_appointment.php?slot_id=<?= $row['slot_id'] ?>" class="btn btn-primary">Reschedule</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No appointments found.</p>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>
