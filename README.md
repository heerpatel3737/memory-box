# Memory Box

A digital scrapbook and memory preservation platform that allows users to capture, organize, and relive meaningful memories through images, stories, timelines, and future letters.

## Features

* User Authentication
* Profile Management
* Memory Creation & Editing
* Image Uploads
* Memory Categories (Drawers)
* Favorites
* Future Letter Vault
* Dashboard Analytics
* Responsive Design

## Tech Stack

* PHP
* MySQL
* HTML5
* CSS3
* JavaScript

## Installation

1. Clone repository
2. Import `db_setup.sql` to your MySQL instance to create the `memoryboxDB` database
3. Create a `.env` file in the root directory and configure database credentials:
   ```env
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=memoryboxDB
   ```
4. Start Apache and MySQL (e.g. using XAMPP or similar)
5. Open `http://localhost/memory_Box` in your browser

## Project Structure

```
memory_Box/
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── images/
│   │   └── giftBox1.gif
│   ├── js/
│   │   └── main.js
│   └── uploads/
│       └── memories/ (created on upload)
├── includes/
│   ├── db.php
│   ├── footer.php
│   ├── functions.php
│   └── header.php
├── confessions.php
├── dashboard.php
├── db_setup.sql
├── drawers.php
├── index.php
├── letters.php
├── login.php
├── logout.php
├── memories.php
├── memory-editor.php
├── memory-view.php
├── profile.php
├── signup.php
├── timeline.php
├── .env.example
├── .gitignore
└── README.md
```
