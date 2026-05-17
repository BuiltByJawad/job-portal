<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><title>Applications</title></head>
<body>
<h2>Applications for Client Jobs</h2>
<form method="get" action="index.php">
    <input type="hidden" name="route" value="recruiter/applications">
    <select name="job_id">
        <option value="">All Jobs</option>
        <?php foreach ($jobs as $job): ?>
            <option value="<?php echo (int)$job['id']; ?>" <?php echo (string)$job['id'] === (string)($_GET['job_id'] ?? '') ? 'selected' : ''; ?>><?php echo htmlspecialchars($job['title']); ?></option>
        <?php endforeach; ?>
    </select>
    <select name="status">
        <option value="">All Status</option>
        <?php foreach (['submitted','reviewed','shortlisted','interview','rejected','withdrawn','hired'] as $status): ?>
            <option value="<?php echo $status; ?>" <?php echo (($_GET['status'] ?? '') === $status) ? 'selected' : ''; ?>><?php echo ucfirst($status); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filter</button>
</form>

<table border="1" cellpadding="6">
    <tr><th>Seeker</th><th>Job</th><th>Client</th><th>Status</th><th>Applied At</th><th>Update</th></tr>
    <?php foreach ($applications as $app): ?>
    <tr>
        <td><?php echo htmlspecialchars($app['seeker_name']); ?></td>
        <td><?php echo htmlspecialchars($app['job_title']); ?></td>
        <td><?php echo htmlspecialchars($app['client_name']); ?></td>
        <td><?php echo htmlspecialchars($app['status']); ?></td>
        <td><?php echo htmlspecialchars($app['applied_at']); ?></td>
        <td>
            <form method="post" action="index.php?route=recruiter/applications/status">
                <input type="hidden" name="application_id" value="<?php echo (int)$app['id']; ?>">
                <select name="status" required>
                    <?php foreach (['submitted','reviewed','shortlisted','interview','rejected','withdrawn','hired'] as $status): ?>
                    <option value="<?php echo $status; ?>" <?php echo $app['status'] === $status ? 'selected' : ''; ?>><?php echo ucfirst($status); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Save</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<p><a href="index.php?route=recruiter/dashboard">Back</a></p>
</body>
</html>
