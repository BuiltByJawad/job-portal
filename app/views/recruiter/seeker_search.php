<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Seeker Search</title>
</head>
<body>
<h2>Search Seeker Database (AJAX)</h2>
<?php if (!empty($_GET['success'])): ?><p style="color:green">Outreach sent successfully.</p><?php endif; ?>
<?php if (!empty($_GET['error'])): ?><p style="color:red">Please provide all outreach fields.</p><?php endif; ?>

<div>
    <input id="keyword" placeholder="Keyword (skills/headline)">
    <input id="location" placeholder="Location">
    <input id="min_experience" type="number" step="0.1" placeholder="Min experience">
    <input id="max_expected_salary" type="number" step="0.01" placeholder="Max expected salary">
    <button onclick="searchSeekers()">Search</button>
</div>

<div id="results"></div>

<script>
function escapeHtml(str) {
    return String(str || '').replace(/[&<>\"']/g, function(m) {
        return ({'&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;',"'":'&#039;'})[m];
    });
}

const recruiterJobs = <?php echo json_encode(array_map(function($j){ return ['id'=>(int)$j['id'], 'title'=>$j['title']]; }, $jobs)); ?>;

function renderJobOptions() {
    return recruiterJobs.map(function(job) {
        return '<option value="' + job.id + '">' + escapeHtml(job.title) + '</option>';
    }).join('');
}

function searchSeekers() {
    const params = new URLSearchParams({
        route: 'api/recruiter/seekers',
        keyword: document.getElementById('keyword').value,
        location: document.getElementById('location').value,
        min_experience: document.getElementById('min_experience').value,
        max_expected_salary: document.getElementById('max_expected_salary').value
    });

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'index.php?' + params.toString(), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status !== 200) {
                document.getElementById('results').innerHTML = '<p style="color:red">Search failed.</p>';
                return;
            }

            const payload = JSON.parse(xhr.responseText);
            const seekers = payload.data || [];

            if (!seekers.length) {
                document.getElementById('results').innerHTML = '<p>No seekers found.</p>';
                return;
            }

            let html = '<h3>Results</h3>';
            seekers.forEach(function(seeker) {
                html += '<div style="border:1px solid #ccc; padding:8px; margin:8px 0">';
                html += '<p><strong>' + escapeHtml(seeker.name) + '</strong> (' + escapeHtml(seeker.email) + ')</p>';
                html += '<p>Headline: ' + escapeHtml(seeker.headline) + '</p>';
                html += '<p>Skills: ' + escapeHtml(seeker.skills) + '</p>';
                html += '<p>Experience: ' + escapeHtml(seeker.years_experience) + ' years</p>';
                html += '<p>Location: ' + escapeHtml(seeker.preferred_location) + '</p>';
                html += '<p>Expected Salary: ' + escapeHtml(seeker.expected_salary) + '</p>';
                html += '<p>Resume: ' + (seeker.resume_path ? '<a href="../' + escapeHtml(seeker.resume_path) + '" target="_blank">View</a>' : 'Not shared') + '</p>';
                html += '<form method="post" action="index.php?route=recruiter/outreach/send">';
                html += '<input type="hidden" name="seeker_id" value="' + seeker.seeker_id + '">';
                html += '<select name="job_id" required><option value="">Select job</option>' + renderJobOptions() + '</select><br>';
                html += '<textarea name="message" required placeholder="Write outreach message"></textarea><br>';
                html += '<button type="submit">Send Outreach</button>';
                html += '</form>';
                html += '</div>';
            });

            document.getElementById('results').innerHTML = html;
        }
    };
    xhr.send();
}
</script>

<p><a href="index.php?route=recruiter/dashboard">Back</a></p>
</body>
</html>
