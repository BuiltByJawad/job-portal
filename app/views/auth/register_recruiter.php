<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><title>Recruiter Registration</title><link rel="stylesheet" href="/project/public/assets/css/style.css"></head>
<body>
<div class="container" style="max-width:760px;">
<h2>Recruiter Registration</h2>
<?php if (!empty($error)): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<form method="post" action="index.php?route=auth/register">
    <div class="grid-2">
        <div><label>Full Name</label><input name="name" required></div>
        <div><label>Email</label><input name="email" type="email" required></div>
        <div><label>Phone</label><input name="phone" required></div>
        <div><label>Password</label><input name="password" type="password" required></div>
        <div><label>Agency Name</label><input name="agency_name" required></div>
        <div><label>Specialization</label><input name="specialization" required></div>
    </div>
    <label>Description</label><textarea name="description"></textarea>
    <label>Website</label><input name="website">
    <div class="actions"><button type="submit">Submit</button></div>
</form>
<p><a href="index.php?route=auth/login">Login</a></p>
</div>
</body>
</html>

