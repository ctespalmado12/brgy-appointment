# Barangay Appointment System

A web-based appointment management system built using PHP, MySQL, and XAMPP. It allows residents to schedule appointments with barangay officials and manage requests efficiently.

## Features
- Admin, baragay official, and client login
- Official scheduling and appointment tracking
- Email verification and password reset (SendinBlue API)
- Responsive design (Bootstrap)
- CRUD operations for appointments

## Tech Stack
- **Frontend:** HTML, CSS, Bootstrap
- **Backend:** PHP (non-Laravel)
- **Database:** MySQL
- **Email API:** SendinBlue
- **Environment:** XAMPP

## Installation
1. Clone this repository:
   ```bash
   git clone https://github.com/ctespalmado12/brgy-appointment.git

2. Move it to:
C:\xampp\htdocs\brgy-appointment

3. Open `brgy_appointment.sql` in MySQL Workbench and run the entire script.  
   It will automatically create the schema, tables, and sample data.

4. Install dependencies:
   ```bash
   composer install

5. Create a `.env` file in the project root and add the following:
  # Database Configuration
DB_HOST= your_db_host

DB_USER= your_db_user

DB_PASS= your_db_pass

DB_NAME=brgy_appointment

# SendinBlue API Key
SENDINBLUE_API_KEY=your_api_key_here

  
  > ⚙️ The `.env` file stores sensitive credentials such as your database connection details and API keys.  
  > Do **not** commit this file to Git — it is already listed in `.gitignore`.

6. Start XAMPP
  Open the XAMPP Control Panel.
  Start Apache

7. Visit:
  http://localhost/brgy-appointment/index.php
