# Running the Bainevier Website on XAMPP (with MySQL)

This site now has a Sign Up / Login system and saves Contact Us submissions
to a MySQL database, using PHP. Static HTML can't do that on its own, so it
needs to run through XAMPP's Apache + MySQL + PHP, not just be opened
directly as a file.

## 1. Install XAMPP (if not already installed)
Download from https://www.apachefriends.org and install it.

## 2. Copy the project into htdocs
Copy the whole `Bainevier-Website-Founder-1` folder into:
```
C:\xampp\htdocs\bainevier
```
(You can rename the folder to whatever you like — `bainevier` is just an
example. Whatever you name it becomes part of the URL in step 5.)

## 3. Start Apache and MySQL
Open the **XAMPP Control Panel** and click **Start** next to both
**Apache** and **MySQL**. Both rows should turn green.

## 4. Import the database
1. In your browser, go to `http://localhost/phpmyadmin`.
2. Click **Import** in the top menu.
3. Click **Choose File** and select `database/bainevier_db.sql` from this
   project.
4. Click **Go** at the bottom of the page.
5. You should now see a new database called **bainevier_db** in the left
   sidebar, containing two tables: `users` and `contact_messages`.

## 5. Open the site through Apache (not by double-clicking the file)
In your browser, go to:
```
http://localhost/bainevier/index.html
```
(Replace `bainevier` with whatever you named the folder in step 2.)

Sign Up, Login, and the Contact Us form will only work when the site is
loaded this way — opening `index.html` directly from File Explorer will not
work, because PHP only runs through Apache.

## 6. Test it
- Go to **Sign Up**, create an account → you should land on a **Welcome**
  page and see a new row in the `users` table in phpMyAdmin.
- Go to **Login** with that same email/password → same welcome page.
- Submit the **Contact Us** form → WhatsApp opens as before, and a new row
  appears in the `contact_messages` table in phpMyAdmin.

## If something doesn't connect
Open `db_connect.php` and check these three lines match your MySQL setup:
```php
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
```
A fresh XAMPP install uses `root` with a **blank** password by default. If
you ever set a MySQL root password in phpMyAdmin, update `$DB_PASS` here to
match it.

## What was added
| File | Purpose |
|---|---|
| `signup.html` / `login.html` | The Sign Up and Login pages (forms) |
| `signup.php` | Validates the form, hashes the password, saves the user, logs them in |
| `login.php` | Checks email/password, logs the user in |
| `logout.php` | Ends the session |
| `welcome.php` | Page shown after signing up / logging in |
| `contact_handler.php` | Saves Contact Us submissions to MySQL |
| `db_connect.php` | Shared database connection settings |
| `database/bainevier_db.sql` | The database schema to import |

## Notes on what was kept simple
- No "forgot password" flow yet — easy to add later if needed.
- No admin dashboard to view signups/messages yet — for now, check them
  directly in phpMyAdmin under the `users` and `contact_messages` tables.
- Passwords are hashed with PHP's `password_hash()` — never stored as plain
  text.
- The Sign Up / Login links in the navigation menu are the same on every
  page; they don't yet change to "Welcome, [Name]" once you're logged in
  elsewhere on the site (only `welcome.php` knows you're logged in). That's
  a reasonable next step if you want it.
