<?php
$appointmentConfirmed = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    
    if (!empty($_POST['name']) && 
        !empty($_POST['email']) && 
        !empty($_POST['phone']) && 
        !empty($_POST['address']) && 
        !empty($_POST['preferred_date']) && 
        !empty($_POST['preferred_time'])) {
       
        $_SESSION['appointment'] = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'preferred_date' => $_POST['preferred_date'],
            'preferred_time' => $_POST['preferred_time']
        ];
        $appointmentConfirmed = true;
    } else {
        
        echo '<p style="color: red;">Please fill out all required fields.</p>';
    }
}
?>

<body>
    <div class="container">
        <h2>Schedule Your Appointment</h2>
        <p>Please provide your details for our technician to visit and inspect your item.</p>

        <?php if ($appointmentConfirmed): ?>
            <div class="confirmation-message">
                <p>Appointment is confirmed!</p>
                <p>Our technician will visit you on <?php echo htmlspecialchars($_SESSION['appointment']['preferred_date']); ?> between <?php echo htmlspecialchars($_SESSION['appointment']['preferred_time']); ?>.</p>
            </div>
        <?php else: ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div>
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div>
                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>

                <div>
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>

                <div>
                    <label for="preferred_date">Preferred Date:</label>
                    <input type="date" id="preferred_date" name="preferred_date" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                </div>

                <div>
                    <label for="preferred_time">Preferred Time:</label>
                    <select id="preferred_time" name="preferred_time" required>
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
</body>