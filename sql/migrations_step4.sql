ALTER TABLE applications
MODIFY COLUMN status ENUM('submitted','reviewed','shortlisted','interview','rejected','withdrawn','hired') NOT NULL DEFAULT 'submitted';
