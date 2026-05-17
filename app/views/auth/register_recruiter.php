<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><title>Recruiter Registration</title></head>
<body>
<h2>Recruiter Registration</h2>
<?php if (!empty($error)): ?><p style="color:red"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
<form method="post" action="index.php?route=auth/register">
    <input name="name" placeholder="Full Name" required><br>
    <input name="email" type="email" placeholder="Email" required><br>
    <input name="phone" placeholder="Phone" required><br>
    <input name="password" type="password" placeholder="Password" required><br>
    <input name="agency_name" placeholder="Agency Name" required><br>
    <input name="specialization" placeholder="Specialization" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <input name="website" placeholder="Website"><br>
    <button type="submit">Submit</button>
</form>
<p><a href="index.php?route=auth/login">Login</a></p>
</body>
</html>
