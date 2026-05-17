<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><title>Create Recruiter Job</title></head>
<body>
<h2>Create Job on Behalf of Client</h2>
<?php if (!empty($error)): ?><p style="color:red"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
<form method="post" action="index.php?route=recruiter/jobs/create">
    <label>Client</label><br>
    <select name="client_id" required>
        <option value="">Select Client</option>
        <?php foreach ($clients as $client): ?>
        <option value="<?php echo (int)$client['id']; ?>"><?php echo htmlspecialchars($client['company_name_override'] ?? $client['employer_company_name'] ?? ('Client #' . $client['id'])); ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Category</label><br>
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php foreach ($categories as $category): ?>
        <option value="<?php echo (int)$category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
        <?php endforeach; ?>
    </select><br>

    <input name="title" placeholder="Job Title" required><br>
    <textarea name="description" placeholder="Description" required></textarea><br>
    <textarea name="requirements" placeholder="Requirements"></textarea><br>
    <textarea name="benefits" placeholder="Benefits"></textarea><br>
    <input name="salary_min" type="number" step="0.01" placeholder="Salary Min"><br>
    <input name="salary_max" type="number" step="0.01" placeholder="Salary Max"><br>
    <input name="location" placeholder="Location"><br>

    <label>Job Type</label><br>
    <select name="job_type" required>
        <option value="full-time">Full-time</option>
        <option value="part-time">Part-time</option>
        <option value="remote">Remote</option>
        <option value="contract">Contract</option>
    </select><br>

    <label>Experience</label><br>
    <select name="experience_level" required>
        <option value="entry">Entry</option>
        <option value="mid">Mid</option>
        <option value="senior">Senior</option>
    </select><br>

    <input name="deadline" type="date" required><br>
    <label>Status</label><br>
    <select name="status" required>
        <option value="draft">Draft</option>
        <option value="active">Active</option>
        <option value="closed">Closed</option>
    </select><br>
    <button type="submit">Create Job</button>
</form>
<p><a href="index.php?route=recruiter/dashboard">Back</a></p>
</body>
</html>
