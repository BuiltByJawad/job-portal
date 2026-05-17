<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><title>Placement History</title></head>
<body>
<h2>Placement History (Hired Candidates)</h2>
<table border="1" cellpadding="6">
    <tr><th>Seeker</th><th>Job</th><th>Client</th><th>Date</th></tr>
    <?php foreach ($placements as $item): ?>
    <tr>
        <td><?php echo htmlspecialchars($item['seeker_name']); ?></td>
        <td><?php echo htmlspecialchars($item['job_title']); ?></td>
        <td><?php echo htmlspecialchars($item['client_name']); ?></td>
        <td><?php echo htmlspecialchars($item['applied_at']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<p><a href="index.php?route=recruiter/dashboard">Back</a></p>
</body>
</html>
