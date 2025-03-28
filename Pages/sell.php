<?php
session_start();
include 'header.php';
include 'database_connection.php';

// Fetch categories from database
$categories = [];
$result = $conn->query("SELECT category_id, category_name FROM categories");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<main>
<?php if (!isset($_SESSION['user_id'])): ?>
    <p style="color: red;">You're not logged in. Please <a href="login.php">login</a> to sell your product.</p>
<?php else: ?>
    <h2>Sell Your Electronic Device</h2>
    <form id="sellForm" method="post">
        <label>Product Name:</label>
        <input type="text" name="product_name" required>

        <label>Category:</label>
        <select name="category_id" id="categorySelect" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['category_id'] ?>" data-name="<?= $category['category_name'] ?>">
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

        const categoryDropdown = document.getElementById('categorySelect');
        const selectedOption = categoryDropdown.options[categoryDropdown.selectedIndex];
        const categoryName = selectedOption.dataset.name;

        let targetPage = '';
        switch (categoryName) {
            case 'Phone':
                targetPage = 'sell_phone_form.php';
                break;
            case 'Laptop':
                targetPage = 'sell_laptop_form.php';
                break;
            case 'Tablet':
                targetPage = 'sell_tablet_form.php';
                break;
            case 'Smartwatch':
                targetPage = 'sell_smartwatch_form.php';
                break;
            case 'Headphones':
                targetPage = 'sell_headphones_form.php';
                break;
            case 'Keyboard':
                targetPage = 'sell_keyboard_form.php';
                break;
            case 'Computer Accessories':
                targetPage = 'sell_accessories_form.php';
                break;
        }


        this.action = targetPage;
        this.submit();
    });
</script>
<?php endif; ?>

<?php include 'footer.php'; ?>
