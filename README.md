
## Project setup process
1. Start XAMPP/LAMPP server and Open terminal.
2. Open terminal and Clone the project Using HTTPS

    ```
   $ git clone https://github.com/sorwaar/File-Archiving-System-with-Google-picker-API-using-Laravel-8.git
    ```
    or SSH
    ```
   $ git@github.com:sorwaar/File-Archiving-System-with-Google-picker-API-using-Laravel-8.git
    ```
3. Open project root

    ```
    cd File-Archiving-System-with-Google-picker-API-using-Laravel-8
    ```
4. Install composer using command

    ```
   $ composer install
    ``` 
5. Create a database . 
    If you are using XAMPP , you can do this by visiting localhost/phpmyadmin from your browser.
4. Setup ENV

    ```
   $ cp env.example .env
   
   $ php artisan key:generate
    ``` 


5. Open .env file in text editor and change database connection to yours.

    ```
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=root
    DB_PASSWORD=your password
    ``` 
5. Run migration and seed

    ```
   $ php artisan migrate:fresh --seed
    ``` 
6. Now visit => localhost/File-Archiving-System-with-Google-picker-API-using-Laravel-8

    NB: Do not need to run ' php artisan serve '  because google picker dosen't work in a port as like licalhost:8000 . 
