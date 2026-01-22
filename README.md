# University Library Management System

## Overview
The **University Library Management System** is a web-based application designed to facilitate library operations such as book reservations, fine management, and user administration. This system allows students to reserve books and view accumulated fines, while librarians and administrators can manage books and users efficiently.

## Features
### Student Features:
- Reserve books
- View accumulated fines for overdue books

### Librarian Features:
- Add, update, and delete books
- Manage student book reservations (approve, reject, etc.)

### Admin Features:
- Add and manage student and librarian accounts

### Additional Features:
- Bulk book addition via API
- MySQL database integration

## Technologies Used
- **Frontend:** HTML, CSS
- **Backend:** PHP
- **Database:** MySQL (via XAMPP)
- **API:** Custom API for bulk book addition

## Installation and Setup
### Prerequisites
- XAMPP installed on your system
- PHP and MySQL configured properly

### Steps
1. **Clone the repository:**
   ```bash
   git clone https://github.com/Nahida-Jannat/ULMS
   ```
2. **Move the project to the XAMPP htdocs directory:**
   ```bash
   mv ULMS /xampp/htdocs/
   ```
3. **Import the database:**
   - Open phpMyAdmin
   - Create a new database (e.g., `library_db`)
   - Import the SQL backup file (`library_db.sql`) from the repository

4. **Start the XAMPP services:**
   - Run Apache and MySQL from XAMPP Control Panel

5. **Configure Database Connection:**
   - Open includes/`config.php` file and update the database credentials if necessary

6. **Access the System:**
   - Open a web browser and navigate to:  
     `http://localhost/ULMS`

## API for Bulk Book Addition
- The system includes an API to allow bulk book uploads.
- Refer to the `api_fetch.php` file for API  usage.

## License
This project is open-source and available under the [MIT License](LICENSE).


