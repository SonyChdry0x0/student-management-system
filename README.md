# Student Management System

This Student Management System is a simple, web-based application designed to help educational institutions efficiently manage student information and attendance records. Built using HTML, CSS, JavaScript, and MySQL, it offers an intuitive interface for administrators to add, view, and organize student data, as well as track attendance seamlessly.

The system includes essential features such as adding new students with details like roll number, name, and course enrollment. Users can view the student list with options to filter by course, delete multiple student records, and download the list in CSV format for offline use. For attendance management, the system allows selecting a course and marking each student as present or absent using checkboxes, ensuring accurate and straightforward attendance tracking. Attendance records can also be viewed and exported with filtering options for convenience.

The project uses core web technologies: HTML, CSS, and JavaScript for the frontend, and MySQL for the backend database. This makes it lightweight and easy to deploy on local or remote web servers such as XAMPP or WAMP. Configuration of database connection settings is straightforward, enabling quick setup and integration.

## Installation

1. Clone or download this repository.  
2. Import the provided MySQL database schema into your MySQL server.  
3. Configure your backend database connection settings (e.g., in `config.php`).  
4. Deploy the project files on a local or remote web server (like XAMPP, WAMP, or a live server).  
5. Access the system via your browser.

## Usage

- Navigate to the **Add Student** page to input new students.  
- Use the **View Student** page to see, filter, delete, or export student data.  
- Go to the **Add Attendance** page, select the course, mark attendance for listed students, and submit.  
- Check attendance reports in **View Attendance**.

## Future Improvements

- Add user authentication and role-based access.  
- Implement pagination and search in student and attendance views.  
- Use AJAX for smoother page interactions without reloads.  
- Add notifications and reporting features.

## Technologies Used

- HTML, CSS, JavaScript  
- MySQL  
- Backend language (e.g., PHP) *(Adjust as per your project)*

## License

This project is open source and free to use.
