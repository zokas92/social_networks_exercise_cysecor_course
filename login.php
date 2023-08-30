<?php include "inc/header.php" ?>
<h1>Login</h1>
<div>
<?php 
validate_user_login();
display_message(); ?>
</div>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" name="login-submit" value="Log In">
</form>
<?php include "inc/footer.php" ?>
