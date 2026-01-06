<!DOCTYPE html>
<html>
<head>
    <title>Payment System</title>
</head>
<body>
<h2>Register / Login</h2>
<input type="text" id="username" placeholder="Username">
<input type="password" id="password" placeholder="Password">
<button onclick="register()">Register</button>
<button onclick="login()">Login</button>

<h2>Products</h2>
<ul id="productsList"></ul>

<h2>Make Payment</h2>
<select id="productSelect"></select>
<input type="number" id="qty" placeholder="Quantity">
<button onclick="pay()">Pay</button>

<h2>Transactions</h2>
<ul id="transactionsList"></ul>

<script>
let user_id = null;

// Register
function register(){
    fetch('api.php?action=register',{
        method:'POST',
        body: JSON.stringify({
            username: document.getElementById('username').value,
            password: document.getElementById('password').value
        })
    }).then(res=>res.json()).then(alert);
}

// Login
function login(){
    fetch('api.php?action=login',{
        method:'POST',
        body: JSON.stringify({
            username: document.getElementById('username').value,
            password: document.getElementById('password').value
        })
    }).then(res=>res.json()).then(data=>{
        alert(data.message);
        if(data.user_id) user_id = data.user_id;
    });
}

// Load products
function loadProducts(){
    fetch('api.php?action=products')
    .then(res=>res.json())
    .then(data=>{
        let select = document.getElementById('productSelect');
        select.innerHTML='';
        data.forEach(p=>{
            select.innerHTML += `<option value="${p.id}">${p.name} - ${p.price}</option>`;
        });
    });
}

// Pay
function pay(){
    if(!user_id){
        alert('Please login first');
        return;
    }
    let product_id = document.getElementById('productSelect').value;
    let qty = document.getElementById('qty').value;
    fetch('api.php?action=pay',{
        method:'POST',
        body: JSON.stringify({user_id, product_id, qty})
    }).then(res=>res.json()).then(data=>{
        alert(data.message + ' | Total: Rp.'+data.total);
        loadTransactions();
    });
}

// Load transactions
function loadTransactions(){
    fetch('api.php?action=transactions')
    .then(res=>res.json())
    .then(data=>{
        let ul = document.getElementById('transactionsList');
        ul.innerHTML='';
        data.forEach(t=>{
            ul.innerHTML += `<li>${t.username} Membeli ${t.quantity} ${t.product} | Total: Rp. ${t.total} | ${t.created_at}</li>`;
        });
    });
}

loadProducts();
loadTransactions();
</script>
</body>
</html>
