# Job Portal (Role 3 - Recruiter)

This repository contains my implementation of the Recruiter role for the Job Portal project.

## What is implemented
- Recruiter registration and login flow
- Admin-verification check before recruiter access
- Recruiter dashboard with role-based route protection
- Client company management
- Job posting on behalf of clients
- Recruiter job list with filters
- AJAX seeker search (XMLHttpRequest)
- Recruiter outreach messaging
- Outreach message status list
- Application management for recruiter-posted jobs
- Application status updates
- Unified pipeline summary
- Placement tracking
- Recruiter analytics and client-wise report
- Complaint submission to admin

## Tech stack
- PHP (MVC-style structure)
- MySQL
- mysqli prepared statements
- PHP sessions for authentication and access control

## Project structure
- `app/controllers` - request handling and flow control
- `app/models` - database access logic
- `app/views` - page templates
- `public/index.php` - front controller / route entry point
- `sql/` - schema, migration, and demo seed scripts

## How to run (XAMPP)
1. Put the project folder inside `htdocs`.
2. Open phpMyAdmin and import `sql/schema.sql`.
3. Run `sql/migrations_step4.sql`.
4. Optional: import `sql/seed_role3_demo.sql` for demo data.
5. Update DB credentials in `app/config/config.php` if needed.
6. Open `/project/public/index.php` in browser.

## Demo recruiter account (from seed)
- Email: `recruiter@jobportal.test`
- Password: `password`

## Notes
- AJAX requirement is implemented in seeker search:
  - Page route: `index.php?route=recruiter/seekers`
  - API route: `index.php?route=api/recruiter/seekers`
- Application status includes `hired` after running the Step 4 migration.
