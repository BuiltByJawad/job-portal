<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><title>Recruiter Complaints</title></head>
<body>
<h2>Submit Complaint to Admin</h2>
<?php if (!empty($_GET['success'])): ?><p style="color:green">Complaint submitted.</p><?php endif; ?>
<?php if (!empty($_GET['error'])): ?><p style="color:red">Provide a valid subject and at least 15 characters description.</p><?php endif; ?>
<form method="post" action="index.php?route=recruiter/complaints/submit">
    <label>Subject User (Employer/Seeker)</label><br>
    <select name="subject_id" required>
        <option value="">Select user</option>
        <?php foreach ($subjects as $s): ?>
            <option value="<?php echo (int)$s['id']; ?>"><?php echo htmlspecialchars($s['name'] . ' (' . $s['role'] . ')'); ?></option>
        <?php endforeach; ?>
    </select><br>
    <textarea name="description" required placeholder="Describe the issue" rows="5" cols="60"></textarea><br>
    <button type="submit">Submit Complaint</button>
</form>

<h3>My Submitted Complaints</h3>
<table border="1" cellpadding="6">
<tr><th>Subject</th><th>Description</th><th>Status</th><th>Admin Note</th><th>Created</th></tr>
<?php foreach ($complaints as $c): ?>
<tr>
<td><?php echo htmlspecialchars($c['subject_name']); ?></td>
<td><?php echo nl2br(htmlspecialchars($c['description'])); ?></td>
<td><?php echo htmlspecialchars($c['status']); ?></td>
<td><?php echo nl2br(htmlspecialchars($c['admin_note'] ?? '')); ?></td>
<td><?php echo htmlspecialchars($c['created_at']); ?></td>
</tr>
<?php endforeach; ?>
</table>
<p><a href="index.php?route=recruiter/dashboard">Back</a></p>
</body>
</html>
