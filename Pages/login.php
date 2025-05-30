<?php
  
  require 'database_connection.php';
  require 'functions.php';
  require 'sessions.php';

  // Redirect if the user is already logged in
  if ($logged_in) {
    header('Location: account.php');
    exit;
  }

  // Handle redirect destination after login
  $redirect = $_GET['redirect'] ?? '/dw3/DW3_FinalProject/index.php';
  
  // If there's a product ID to preserve in redirect, append it
  if (isset($_GET['id'])) {
    $redirect .= '?id=' . htmlspecialchars($_GET['id']);
  }

  $errors = [];
  
  // Handle form submission
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $redirectPage = $_POST['redirect'] ?? 'index.php';

    // Fetch the user by email
    $sql = "SELECT 
        user_id, 
        name AS forename, 
        email, 
        password,
        user_type 
      FROM users 
      WHERE email = :email";

    $user = pdo($pdo, $sql, ['email' => $email])->fetch();

    if (!$user) {
      $errors['message'] = 'No user with this email can be found!';
    } else {
	  // Verify the password
      if (password_verify($password, $user['password'])) {
        login($user); // Using the login function from sessions.php
        $_SESSION['user_id'] = $user['user_id'];    // Adding your custom session variables
        $_SESSION['username'] = $user['forename'];  // Mapping name to forename
        $_SESSION['user_type'] = $user['user_type'];
        
        // Redirect to the originally intended page
		header("Location: $redirectPage");
        exit;
      } else {
        $errors['message'] = 'Invalid email or password!';
      }
    }
  }
?>

<?php include 'header.php'; ?>

<div class="wrapper">
    <main>
        <h2>Login</h2>
        <form action="login.php" method="post">
            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

            <button type="submit" class="btn">Login</button>
        </form>

        <?php if (isset($errors['message'])): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($errors['message']) ?></div>
        <?php endif; ?>

        <p>Don't have an account? <a href="signup.php" class="btn">Sign Up</a></p>
    </main>
</div>

<?php include 'footer.php'; ?>