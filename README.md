# Online Course Management System (LMS)

A modern, responsive Learning Management System (LMS) built with **PHP** and **MySQL**. This platform supports Admin, Faculty, and Student roles, enabling course management, attendance, assignments, quizzes, progress tracking, and more.

## Features
- **Role-based Dashboards**: Separate panels for Admin, Faculty, and Students
- **User Authentication**: Secure login, registration, and profile management
- **Course, Department, and Subject Management** (Admin)
- **Task/Assignment & Quiz Management** (Faculty)
- **Student Progress Tracking & Performance Alerts** (Faculty)
- **Task/Quiz Submission, Progress & Feedback** (Student)
- **Attendance & Marks Management** (Faculty/Student)
- **Notice Board & System Reports**
- **Modern UI**: Responsive, animated, and grid-based design
- **First Admin Registration**: Hidden page for initial admin setup

## Folder Structure
```
Online Course/
│
├── assets/
│   ├── css/style.css         # Main CSS (modern, responsive, animated)
│   ├── navbar.php            # Top navigation bar include
│   ├── sidepanel_admin.php   # Admin side panel
│   ├── sidepanel_faculty.php # Faculty side panel
│   └── sidepanel_student.php # Student side panel
│
├── dashboard/
│   ├── admin.php             # Admin dashboard
│   ├── faculty.php           # Faculty dashboard
│   └── student.php           # Student dashboard
│
├── module/
│   └── admin_register.php    # Hidden admin registration page
│
├── attendance.php            # Attendance module
├── marks.php                 # Marks module
├── notice.php                # Notice board
├── reports.php               # System reports (admin)
├── feedback.php              # Student feedback
├── progress.php              # Student progress
├── quizzes.php               # Student quizzes
├── tasks.php                 # Student tasks
├── performance_alerts.php    # Faculty performance alerts
├── student_progress.php      # Faculty view of student progress
├── quiz_management.php       # Faculty quiz management
├── task_management.php       # Faculty task management
├── subject_management.php    # Admin subject management
├── department_management.php # Admin department management
├── course_management.php     # Admin course management
├── profile.php               # User profile
├── register.php              # Registration
├── login.php                 # Login
├── logout.php                # Logout
├── index.php                 # Entry point, routes to dashboards
├── db.php                    # Centralized DB connection
├── collegedb.sql             # Database schema & sample data
└── README.md                 # This file
```

## Setup Instructions
1. **Clone or copy the project** to your XAMPP/LAMP `htdocs` directory.
2. **Create the database**:
   - Import `collegedb.sql` into your MySQL server (phpMyAdmin or CLI).
3. **Configure DB connection**:
   - Edit `db.php` if your MySQL username/password differs from `root`/no password.
4. **First Admin Registration**:
   - Visit `/module/admin_register.php` in your browser to create the first admin account. This page is hidden after the first admin is created.
5. **Access the app**:
   - Go to `/index.php` to start using the LMS.

## Usage Notes
- **Role-based access**: Users are routed to their dashboard after login.
- **Modern UI**: All pages use a navigation bar, side panel, blue section headers, grid layouts, and subtle animations.
- **Responsive**: The UI adapts to desktop and tablet screens.
- **Security**: Passwords are hashed, and admin registration is protected.

## Requirements
- PHP 7.4+
- MySQL 5.7+
- XAMPP/LAMP/WAMP or compatible local server

## Screenshots
![Screenshot 2025-06-13 142714](https://github.com/user-attachments/assets/0f73b4cc-b00b-4e4c-922f-194e9fade30f)
![Screenshot 2025-06-13 142726](https://github.com/user-attachments/assets/05c1d66a-3e9b-4ccd-9611-cbc619845d48)
![Screenshot 2025-06-13 142734](https://github.com/user-attachments/assets/5699dd49-10a0-4289-9e1e-fbaab8caea34)
![Screenshot 2025-06-13 143028](https://github.com/user-attachments/assets/f396ba5f-7cf9-4e04-8036-275491b50ea3)
![Screenshot 2025-06-13 143039](https://github.com/user-attachments/assets/0775ea22-6cb2-458e-8d82-8beb1ea9b992)
![Screenshot 2025-06-13 143102](https://github.com/user-attachments/assets/2d23c5b7-f816-4961-aaf1-de90da27a735)
![Screenshot 2025-06-13 142601](https://github.com/user-attachments/assets/1cef0ecf-60e5-4c1f-9843-1395d5500934)


## Credits
- Developed by Jayasimma D
- UI inspired by modern LMS platforms

---
For any issues or suggestions, please open an issue or contact the maintainer. 
