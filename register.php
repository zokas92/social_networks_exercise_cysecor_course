<?php include "inc/header.php";

validate_user_registration();
display_message();?>
<form method="POST">
    <input type="text" name="first_name" placeholder="First Name" required>
    <input tyle="text" name="last_name" placeholder="Last Name" required>
    <input tyle="text" name="username" placeholder="Username" required>
    <input tyle="email" name="email" placeholder="Email" required>
    <input tyle="password" name="password" placeholder="Password" required>
    <input tyle="password" name="confirm_password" placeholder="Confirm Password" required>
    <input type="submit" name="register-submit" placeholder="Register Now">

</form>
<?php include "inc/footer.php"; ?>