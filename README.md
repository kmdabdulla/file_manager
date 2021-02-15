
# Simple File Management System

The application provides basic file backup and encryption service. Basic user authentication is provided. Currently, the application supports encrypted file uploads, listing the uploaded files, deleting and downloading the decrypted files. 

Application can be accessed [here](https://www.file.mohamedabdulla.com)

## Built With

* PHP 7.4
* Nginx 1.4.6
* MySql 5.1
* Laravel 8.16.1
* Arch Linux

## Source Files Description
1. app/Controllers/LoginController.php - handles user authentication and new account creation.
2. app/Models/User.php - Provides the model for LoginController.
3. app/Requests/UserLogin.php - Provides the required validation and sanitizations for methods declared in LoginController.php
4. app/Controllers/FileController.php - handles File operaions such as upload, encrypt, download etc.
5. app/Models/FileUploads.php - Provides the model for FileController.
6. app/Requests/FileUpload.php - Provides the required validation and sanitizations for methods declared in FileController.php
7. All the views for the application can be found resources folder. 
8. All the database schema can be found in database/migrations.


## Security measures
1. Laravel framework support for CSRF prevention is used. 
2. Proper sanitization, validation and output encoding are used to prevent SQL injection and XSS.
3. AES-256-CBC is currently used for encryption. Some authenticated encryption algorithms like AES-256-GCM will be used in the future (warranted investigation).    
 

## TO DO
1. User management activities such as change email, forget password and OAuth support etc. 
2. Online File view/modification.
3. User and File access rights.
4. Upload File duplicate checks. Right now the application treats duplicate files as separate versions.
5. Efficient way to upload and encrypt larger files.

## Author
Mohamed Abdulla

LinkedIn: [Abdulla](https://www.linkedin.com/in/kmdabdulla)
Website: [www.mohamedabdulla.com](https://www.mohamedabdulla.com)

## Acknowledgments
* Laravel Support Forums
* Stack OverFlow Community
