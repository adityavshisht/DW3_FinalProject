<?php
session_start();
include 'header.php';
?>
<main>
    <h2>Sell Your Electronic Device</h2>
    <form action="sell_process.php" method="post">
        <label>Product Name:</label>
        <input type="text" name="product_name" required>
        <label>Category:</label>
        <select name="category">
            <option>Phone</option>
            <option>Laptop</option>
            <option>Tablet</option>
        </select>
        <label>Price:</label>
        <input type="number" name="price" required>
        <label>Description:</label>
        <textarea name="description"></textarea>
        <button type="submit">Submit</button>
    </form>
</main>
<?php include 'footer.php'; ?>