<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><title>Login</title></head>
<body>
<h2>Login</h2>
<?php if (!empty($error)): ?><p style="color:red"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
<?php if (!empty($success)): ?><p style="color:green"><?php echo htmlspecialchars($success); ?></p><?php endif; ?>
<form method="post" action="index.php?route=auth/login">
    <input name="email" type="email" placeholder="Email" required><br>
    <input name="password" type="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
<p><a href="index.php?route=auth/register">Create recruiter account</a></p>
</body>
</html>
