<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><title>Recruiter Clients</title></head>
<body>
<h2>Manage Client Companies</h2>
<?php if (!empty($error)): ?><p style="color:red"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
<form method="post" action="index.php?route=recruiter/clients">
    <label>Standalone Company Name</label><br>
    <input name="company_name_override" required placeholder="e.g. Acme Corp"><br>
    <button type="submit">Add Client</button>
</form>

<h3>Your Clients</h3>
<table border="1" cellpadding="6">
    <tr><th>ID</th><th>Company</th><th>Employer Linked</th><th>Added</th></tr>
    <?php foreach ($clients as $client): ?>
        <tr>
            <td><?php echo (int)$client['id']; ?></td>
            <td><?php echo htmlspecialchars($client['company_name_override'] ?? $client['employer_company_name'] ?? 'N/A'); ?></td>
            <td><?php echo $client['employer_id'] ? 'Yes' : 'No'; ?></td>
            <td><?php echo htmlspecialchars($client['added_at']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<p><a href="index.php?route=recruiter/dashboard">Back</a></p>
</body>
</html>
