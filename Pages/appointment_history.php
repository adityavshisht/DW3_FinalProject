<?php
session_start();
include 'header.php';
include 'database_connection.php'; // This defines $pdo (not $conn)

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

try {
    $sql = "SELECT * FROM pickup_slots WHERE seller_id = :seller_id ORDER BY slot_date, slot_time";
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
    <?php elseif (count($appointments) > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0" style="margin: 20px auto; border-collapse: collapse;">
            <tr style="background-color: #f4f4f4;">
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
            <?php foreach ($appointments as $row): 
                $datetime = strtotime($row['slot_date'] . ' ' . $row['slot_time']);
                $status = $datetime < time() ? 'Completed' : htmlspecialchars($row['status']);
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['slot_date']) ?></td>
                    <td><?= date('h:i A', strtotime($row['slot_time'])) ?></td>
                    <td><?= $status ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No appointments found.</p>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>
