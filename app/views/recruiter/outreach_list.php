<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><title>Outreach Messages</title></head>
<body>
<h2>Recruiter Outreach Messages</h2>
<table border="1" cellpadding="6">
    <tr><th>Seeker</th><th>Job</th><th>Message</th><th>Status</th><th>Sent At</th></tr>
    <?php foreach ($messages as $msg): ?>
    <tr>
        <td><?php echo htmlspecialchars($msg['seeker_name']); ?></td>
        <td><?php echo htmlspecialchars($msg['job_title']); ?></td>
        <td><?php echo nl2br(htmlspecialchars($msg['message'])); ?></td>
        <td><?php echo htmlspecialchars($msg['status']); ?></td>
        <td><?php echo htmlspecialchars($msg['sent_at']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<p><a href="index.php?route=recruiter/dashboard">Back</a></p>
</body>
</html>
