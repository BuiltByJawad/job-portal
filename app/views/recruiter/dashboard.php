<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Recruiter Dashboard</title>
<link rel="stylesheet" href="/project/public/assets/css/style.css">
</head>
<body>
<div class="container">
  <div class="dashboard-top">
    <div>
      <h2>Recruiter Dashboard</h2>
      <p class="muted">Welcome, <?php echo htmlspecialchars($user['name']); ?>. Use the modules below to manage your recruiting workflow.</p>
    </div>
    <a class="btn btn-secondary" href="index.php?route=auth/logout">Logout</a>
  </div>

  <div class="dashboard-grid">
    <div class="dashboard-card"><h3>Clients</h3><p>Add and manage the companies you recruit for.</p><a href="index.php?route=recruiter/clients">Open Client Management</a></div>
    <div class="dashboard-card"><h3>Create Job</h3><p>Post a new job on behalf of a selected client.</p><a href="index.php?route=recruiter/jobs/create">Create Job Posting</a></div>
    <div class="dashboard-card"><h3>All Jobs</h3><p>Track recruiter-posted jobs with filter controls.</p><a href="index.php?route=recruiter/jobs">View Job List</a></div>
    <div class="dashboard-card"><h3>Seeker Search (AJAX)</h3><p>Find candidates by skill, location, experience, and salary.</p><a href="index.php?route=recruiter/seekers">Search Seekers</a></div>
    <div class="dashboard-card"><h3>Outreach</h3><p>Review outreach messages and follow response status.</p><a href="index.php?route=recruiter/outreach">Open Outreach Log</a></div>
    <div class="dashboard-card"><h3>Applications</h3><p>Manage incoming applications and update candidate stages.</p><a href="index.php?route=recruiter/applications">Manage Applications</a></div>
    <div class="dashboard-card"><h3>Pipeline</h3><p>See active candidates grouped by current stage.</p><a href="index.php?route=recruiter/pipeline">View Pipeline</a></div>
    <div class="dashboard-card"><h3>Placements</h3><p>Track candidates marked as hired.</p><a href="index.php?route=recruiter/placements">Placement History</a></div>
    <div class="dashboard-card"><h3>Analytics & Client Report</h3><p>Monitor outreach performance and client-level stats.</p><a href="index.php?route=recruiter/analytics">Open Analytics</a></div>
    <div class="dashboard-card"><h3>Complaints</h3><p>Report issues with seeker or employer conduct to admin.</p><a href="index.php?route=recruiter/complaints">Submit Complaint</a></div>
  </div>
</div>
</body>
</html>
