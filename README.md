# ProjectZero Admin (User Authentication)

## Project Setup

### 1. Clone the Repository

Navigate to your `htdocs` directory (or your web server's root directory):

```bash
cd /path/to/htdocs
```

Clone the repository and directly checkout the UserAuth branch:

```bash
git clone --branch UserAuth https://github.com/ennonn/admin.git
```

### 2. Setup the Database

Open phpMyAdmin in your browser (e.g., http://localhost/phpmyadmin).

Create a new database called ecommerce_db.

Import the SQL file ```ecommerce_db_updated.sql``` from the cloned repository.

### 3. Configure the .env File

Copy the .env.example file and rename it to .env:

Open the .env file and configure it with your local environment settings, including database connection, base URL, and Mailtrap credentials.

```bash
BASE_URL=http://localhost:8888/admin/api.php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=root
DB_PASSWORD=
JWT_SECRET=your_jwt_secret_here

MAILTRAP_USERNAME=your_mailtrap_username
MAILTRAP_PASSWORD=your_mailtrap_password
```

### 4. Setup Mailtrap for Email Verification

Log in to Mailtrap to handle the email functionality.

The Mailtrap credentials are already included in the .env file.


### 5. Install Dependencies
Navigate to your project directory and install dependencies using Composer:

```bash
composer install
```

### 6. Run the Application

Ensure your local server (e.g., XAMPP or MAMP) is running, then access the app in your browser at:

### 7. API Documentation
You can find the full API documentation here:
<a href="https://documenter.getpostman.com/view/30788290/2sAY4shiko" target="_blank">API Documentation</a>

......
