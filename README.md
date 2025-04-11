CO-OP - Gamer Dating Web Application

Overview
CO-OP is a swipe-based dating platform specifically built for gamers. It allows users to create a profile, find matches based on game interests, like/pass users, and chat in real time.


Download the Files from here
https://github.com/BonelessChachii/CO-OP



How to Deploy This Project

1. Install XAMPP
- Download from: https://www.apachefriends.org/index.html
- Install with default settings.
- Make sure Apache and MySQL are selected.

2. Copy Project Files

- Place the `CO-OP` folder inside:
- C:\xampp\htdocs\
- Final path should look like:
- C:\xampp\htdocs\CO-OP\


3. Start XAMPP
- Open the XAMPP Control Panel.
- Click **Start** on Apache and MySQL.
- Apache PID 16780 5672
	 PORTS 80, 433

- MySQL	PID 17184
	PORT 3306

- If MySQL id not starting then go to task manager and look for a task called mysqld and end that task.

4. Create the MySQL Database
- Go to: http://localhost/phpmyadmin
- Click **New** and create a database named:
- coop_db
- then click go

5. Import SQL Tables
- Inside phpMyAdmin
- Click on Import
- Choose File coop_db.sql
- Import



6. Run the Web App
- Open your browser and visit:
- http://localhost/CO-OP/login.php


7. To run two user on localhost 
- Run http://localhost/CO-OP/login.php on a NORMAL browser and create an account 
- For the other user open Incognito tab or use separate browser to create another account.
- This way You can test two account's chat function with each other.


8. AI Usage Citation
- AI Tools Used:
- ChatGPT (GPT-3.5 via OpenAI)

Prompts Used:
- Help Debugging
- Fix Css Styles 
- Help with logic

Affected Files/Components:
- Style.css, home.php, connections.php, edit_profile.php, discord login.php, profile.php

Port & Config Notes
- Apache: Port 80, 443
- MySQL: Port 3306 (or 3307 if conflicts)


9. File Structure
- HTML: register.html, login.html
- PHP: login.php, register.php, profile.php, home.php, chat.php, edit_profile.php, messages.php
- JS: swipe.js
- Database: `coop_db` MySQL schema



10. Notes
- Ensure uploads folder exists and has write permissions.
- Works on both Windows and macOS with XAMPP.

11. Group 7 
- 
