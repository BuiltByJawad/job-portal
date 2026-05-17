<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><title>Pipeline</title></head>
<body>
<h2>Unified Candidate Pipeline</h2>
<ul>
    <li>Submitted: <?php echo (int)$summary['submitted']; ?></li>
    <li>Reviewed: <?php echo (int)$summary['reviewed']; ?></li>
    <li>Shortlisted: <?php echo (int)$summary['shortlisted']; ?></li>
    <li>Interview: <?php echo (int)$summary['interview']; ?></li>
</ul>
<p><a href="index.php?route=recruiter/dashboard">Back</a></p>
</body>
</html>
