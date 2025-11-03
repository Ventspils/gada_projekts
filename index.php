<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Vents</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src='main.js'></script>
</head>
<body>
<div class='login'>
    <form action='cfg.php' method='post'>
    <div class="mb-3">
        <input name='login' type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email address">
    </div>
        <br>
    <div class="mb-3">
        <input name='pass' type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
    </div>
        <br>
    <button type="submit" class="btn btn-primary">Submit</button>
    <span class="switch-text" id="showRegister">Reģistrēties</span>
    </form>
</div>
<div class='register'>
    <form action='register_cfg.php' method='post'>
        <div class="mb-3">
            <input name='vards' type="text" class="form-control" placeholder="Name" required>
        </div>
        <br>
        <div class="mb-3">
            <input name='uzvards' type="text" class="form-control" placeholder="Surname" required>
        </div>
        <br>
        <div class="mb-3">
            <input name='email' type="email" class="form-control" placeholder="Email address" required>
        </div>
        <br>
        <div class="mb-3">
            <input name='password' type="password" class="form-control" placeholder="Password" required>
        </div>
        <br>
        <div class="mb-3">
            <input name='klase' type="text" class="form-control" placeholder="Klase (piem. 11.b)" required>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Reģistrēties</button>
        <span class="switch-text" id="showLogin">Pieteikties</span>
    </form>
</div>
<script>
// Formu pārslēgšana
const loginBox = document.querySelector('.login');
const registerBox = document.querySelector('.register');
document.getElementById('showRegister').onclick = () => {
    loginBox.style.display = 'none';
    registerBox.style.display = 'block';
};
document.getElementById('showLogin').onclick = () => {
    registerBox.style.display = 'none';
    loginBox.style.display = 'block';
};

// AJAX reģistrācija
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('register_cfg.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            registerBox.style.display = 'none';
            loginBox.style.display = 'block';
        }
    })
    .catch(err => {
        alert("Kļūda savienojumā ar serveri.");
        console.error(err);
    });
});
</script>
</body>
</html>