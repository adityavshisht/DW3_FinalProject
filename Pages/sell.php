<?php
session_start();
include 'header.php';
?>

<main>
    <h2>Sell Your Electronic Device</h2>
    <form id="sellForm" method="post">
        <label>Product Name:</label>
        <input type="text" name="product_name" required>
        <label>Category:</label>
        <select name="category" id="categorySelect">
            <option value="Phone">Phone</option>
            <option value="Laptop">Laptop</option>
            <option value="Tablet">Tablet</option>
        </select>
        <label>Price:</label>
        <input type="number" name="price" required>
        <label>Description:</label>
        <textarea name="description"></textarea>
        <button type="submit">Submit</button>
    </form>
</main>

<script>
    document.getElementById('sellForm').addEventListener('submit', function(e) {
        e.preventDefault(); 
        
        const category = document.getElementById('categorySelect').value;
        let targetPage = '';
        
        switch(category) {
            case 'Phone':
                targetPage = 'sell_phone_form.php';
                break;
            case 'Laptop':
                targetPage = 'sell_laptop_form.php';
                break;
            case 'Tablet':
                targetPage = 'sell_tablet_form.php';
                break;
        }
        
        this.action = targetPage; 
        this.submit(); 
    });
</script>

<?php include 'footer.php'; ?>