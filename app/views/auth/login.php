<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><title>Login</title><link rel="stylesheet" href="/project/public/assets/css/style.css"></head>
<body>
<div class="container" style="max-width:560px;">
<h2>Login</h2>
<?php if (!empty($error)): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<?php if (!empty($success)): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
<form method="post" action="index.php?route=auth/login">
    <label>Email</label><input name="email" type="email" required>
    <label>Password</label><input name="password" type="password" required>
    <div class="actions"><button type="submit">Login</button></div>
</form>
<p><a href="index.php?route=auth/register">Create recruiter account</a></p>
</div>
</body>
</html>

