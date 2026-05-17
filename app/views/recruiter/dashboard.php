<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><title>Recruiter Dashboard</title></head>
<body>
    <h2>Recruiter Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($user['name']); ?>.</p>
    <ul>
        <li><a href="index.php?route=recruiter/clients">Manage Clients</a></li>
        <li><a href="index.php?route=recruiter/jobs/create">Create Job for Client</a></li>
        <li><a href="index.php?route=recruiter/jobs">View All Client Jobs</a></li>
        <li><a href="index.php?route=recruiter/seekers">Search Seekers (AJAX)</a></li>
        <li><a href="index.php?route=recruiter/outreach">Outreach Messages</a></li>
    </ul>
    <p><a href="index.php?route=auth/logout">Logout</a></p>
</body>
</html>
