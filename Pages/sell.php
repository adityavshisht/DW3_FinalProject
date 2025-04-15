<?php
session_start();
include 'header.php';
require 'database_connection.php';  // Provides the $pdo database connection

// Fetch all categories from the database
$sql = "SELECT category_id, category_name FROM categories";
$statement = $pdo->query($sql); 
$categories = $statement->fetchAll(PDO::FETCH_ASSOC); // Get categories as associative array
?>

<main>
<?php if (!isset($_SESSION['user_id'])): ?>
    <!-- Show a message if user is not logged in -->
    <p style="color: red;">You're not logged in. Please <a href="login.php">login</a> to sell your product.</p>
<?php else: ?>
    <h2>Sell Your Electronic Device</h2>
	
	<!-- Product submission form -->
    <form id="sellForm" method="post">
        <label>Product Name:</label>
        <input type="text" name="product_name" required>

        <label>Category:</label>
        <select name="category_id" id="categorySelect" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['category_id'] ?>" data-name="<?= htmlspecialchars($category['category_name']) ?>">
                    <?= htmlspecialchars($category['category_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Price:</label>
        <input type="number" name="price" required>

        <label>Description:</label>
        <textarea name="description"></textarea>

        <button type="submit">Submit</button>
    </form>
<?php endif; ?>
</main>

<?php if (isset($_SESSION['user_id'])): ?>
<script>
document.getElementById('sellForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const productName = document.querySelector('[name="product_name"]').value.toLowerCase();
    const categorySelect = document.getElementById('categorySelect');
    const selectedOption = categorySelect.options[categorySelect.selectedIndex];
    const categoryName = selectedOption.dataset.name;
    const categoryId = categorySelect.value;

    //keyword mapping for validations
    const categoryKeywords = {
        'Phone': ['iphone', 'samsung', 'pixel', 'oppo', 'vivo', 'oneplus', 'realme', 'redmi', 'xiomi'],
        'Laptop': ['macbook', 'dell', 'hp', 'asus', 'acer', 'laptop', 'lenovo'],
        'Tablet': ['ipad', 'tab', 'tablet', 'galaxy tab'],
        'Smartwatch': ['smartwatch', 'fitbit', 'watch', 'galaxy watch'],
        'Headphones': ['headphones', 'earbuds', 'earphones', 'airpods'],
        'Keyboards': ['keyboard', 'mechanical keyboard', 'gaming keyboard'],
        'Computer Accessories': ['charger', 'mouse', 'case', 'cover', 'accessory']
    };

    //validate the product name to matche the selected category
    const expectedKeywords = categoryKeywords[categoryName];
    const matchesCategory = expectedKeywords?.some(keyword => productName.includes(keyword));

    if (!matchesCategory) {
        alert(`The product name doesn't seem to be a "${categoryName}". Please put the correct category.`);
        return;
    }

    //category to form mapping
    const categoryForms = {
        'Phone': 'sell_phone_form.php',
        'Laptop': 'sell_laptop_form.php',
        'Tablet': 'sell_tablet_form.php',
        'Smartwatch': 'sell_smartwatches_form.php',
        'Headphones': 'sell_headphones_form.php',
        'Keyboards': 'sell_keyboard_form.php',
        'Computer Accessories': 'sell_accessories_form.php'
    };

    const targetPage = categoryForms[categoryName];

    if (!targetPage) {
        alert("Sorry, no form available for the selected category.");
        return;
    }

    this.action = targetPage;
    this.submit();
});
</script>

<?php endif; ?>

<?php include 'footer.php'; ?>