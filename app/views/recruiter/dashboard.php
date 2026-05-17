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
        <li><a href="index.php?route=recruiter/applications">Client Job Applications</a></li>
        <li><a href="index.php?route=recruiter/pipeline">Unified Candidate Pipeline</a></li>
        <li><a href="index.php?route=recruiter/placements">Placement History</a></li>
        <li><a href="index.php?route=recruiter/analytics">Recruiter Analytics & Client Report</a></li>
        <li><a href="index.php?route=recruiter/complaints">Submit Complaint to Admin</a></li>
    </ul>
    <p><a href="index.php?route=auth/logout">Logout</a></p>
</body>
</html>
