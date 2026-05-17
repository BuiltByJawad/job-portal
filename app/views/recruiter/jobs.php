<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><title>Recruiter Jobs</title></head>
<body>
<h2>All Jobs Posted Across Clients</h2>
<form method="get" action="index.php">
    <input type="hidden" name="route" value="recruiter/jobs">

    <label>Status</label>
    <select name="status">
        <option value="">All</option>
        <option value="draft" <?php echo (($_GET['status'] ?? '') === 'draft') ? 'selected' : ''; ?>>Draft</option>
        <option value="active" <?php echo (($_GET['status'] ?? '') === 'active') ? 'selected' : ''; ?>>Active</option>
        <option value="closed" <?php echo (($_GET['status'] ?? '') === 'closed') ? 'selected' : ''; ?>>Closed</option>
    </select>

    <label>Category</label>
    <select name="category_id">
        <option value="">All</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo (int)$category['id']; ?>" <?php echo (string)($category['id']) === (string)($_GET['category_id'] ?? '') ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
        <?php endforeach; ?>
    </select>

    <label>Client</label>
    <select name="employer_id">
        <option value="">All</option>
        <?php foreach ($clients as $client): if (!$client['employer_id']) continue; ?>
            <option value="<?php echo (int)$client['employer_id']; ?>" <?php echo (string)$client['employer_id'] === (string)($_GET['employer_id'] ?? '') ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($client['employer_company_name'] ?? $client['employer_owner_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Filter</button>
</form>

<table border="1" cellpadding="6">
    <tr>
        <th>Title</th><th>Client</th><th>Category</th><th>Status</th><th>Deadline</th><th>Created</th>
    </tr>
    <?php foreach ($jobs as $job): ?>
        <tr>
            <td><?php echo htmlspecialchars($job['title']); ?></td>
            <td><?php echo htmlspecialchars($job['company_name']); ?></td>
            <td><?php echo htmlspecialchars($job['category_name']); ?></td>
            <td><?php echo htmlspecialchars($job['status']); ?></td>
            <td><?php echo htmlspecialchars($job['deadline']); ?></td>
            <td><?php echo htmlspecialchars($job['created_at']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<p><a href="index.php?route=recruiter/dashboard">Back</a></p>
</body>
</html>
