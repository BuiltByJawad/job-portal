<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><title>Recruiter Analytics</title></head>
<body>
<h2>Recruiter Analytics</h2>
<ul>
    <li>Total outreach sent: <?php echo (int)$summary['total_outreach']; ?></li>
    <li>Outreach response rate: <?php echo (float)$summary['response_rate']; ?>%</li>
    <li>Total applications managed: <?php echo (int)$summary['total_applications_managed']; ?></li>
    <li>Placement success rate: <?php echo (float)$summary['placement_success_rate']; ?>%</li>
</ul>

<h3>Client Report</h3>
<form method="get" action="index.php">
    <input type="hidden" name="route" value="recruiter/analytics">
    <select name="employer_id">
        <option value="">All Clients</option>
        <?php foreach ($clients as $client): if (!$client['employer_id']) continue; ?>
            <option value="<?php echo (int)$client['employer_id']; ?>" <?php echo (string)$client['employer_id'] === (string)($_GET['employer_id'] ?? '') ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($client['employer_company_name'] ?? $client['employer_owner_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Generate</button>
</form>

<table border="1" cellpadding="6">
    <tr>
        <th>Client</th><th>Total Jobs</th><th>Total Applications</th><th>Submitted</th><th>Reviewed</th><th>Shortlisted</th><th>Interview</th><th>Hired</th>
    </tr>
    <?php foreach ($clientReport as $row): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['client_name']); ?></td>
        <td><?php echo (int)$row['total_jobs']; ?></td>
        <td><?php echo (int)$row['total_applications']; ?></td>
        <td><?php echo (int)$row['submitted']; ?></td>
        <td><?php echo (int)$row['reviewed']; ?></td>
        <td><?php echo (int)$row['shortlisted']; ?></td>
        <td><?php echo (int)$row['interview']; ?></td>
        <td><?php echo (int)$row['hired']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<p><a href="index.php?route=recruiter/dashboard">Back</a></p>
</body>
</html>
