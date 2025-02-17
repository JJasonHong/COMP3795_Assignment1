<?php
// Ensure $db has been initialized via inc_db_params.php (e.g. $db = new SQLite3('./blog.sqlite');)

if ($db) {
    // Create the Users table if it doesn't exist
    $SQL_create_user_table = "CREATE TABLE IF NOT EXISTS Users (
        id INTEGER PRIMARY KEY AUTOINCREMENT, 
        username TEXT UNIQUE NOT NULL, -- This will be the email address 
        password TEXT NOT NULL,
        firstName TEXT NOT NULL, 
        lastName TEXT NOT NULL,
        registrationDate DATETIME DEFAULT CURRENT_TIMESTAMP,
        isApproved INTEGER NOT NULL DEFAULT 0,
        role TEXT CHECK(role IN ('Admin', 'Contributor')) DEFAULT 'Contributor'
        )";
    $db->exec($SQL_create_user_table);

    // Create the Posts table if it doesn't exist.
    $SQL_create_post_table = "CREATE TABLE IF NOT EXISTS Posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        title TEXT NOT NULL,
        slug TEXT UNIQUE NOT NULL,
        content TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES Users(id)
    );";
    $db->exec($SQL_create_post_table);

    // Insert default users if the Users table is empty.
    $SQL_check_user_empty = "SELECT COUNT(*) as count FROM Users";
    $result = $db->querySingle($SQL_check_user_empty, true);
    
    if ($result['count'] == 0) 
    {
        $SQL_insert_users = "
            INSERT INTO Users (username, password, firstName, lastName, registrationDate, isApproved, role)
            VALUES 
                ('alice@test.com', '".password_hash('secret', PASSWORD_BCRYPT)."', 'Alice', 'Johnson', datetime('now'), 1, 'Admin'),
                ('bob@test.com', '".password_hash('secret', PASSWORD_BCRYPT)."', 'Bob', 'Smith', datetime('now'), 0, 'Contributor'),
                ('charlie@test.com', '".password_hash('secret', PASSWORD_BCRYPT)."', 'Charlie', 'Brown', datetime('now'), 0, 'Contributor');
        ";
        $db->exec($SQL_insert_users);
    }

// Insert default posts if the Posts table is empty
$SQL_check_posts_empty = "SELECT COUNT(*) as count FROM Posts";
$result = $db->querySingle($SQL_check_posts_empty, true);

// Fetch user IDs to ensure they exist before inserting posts
$users = [];
$SQL_fetch_users = "SELECT id FROM Users ORDER BY id ASC LIMIT 3";
$query = $db->query($SQL_fetch_users);
while ($row = $query->fetchArray(SQLITE3_ASSOC)) 
{
    $users[] = $row['id'];
}

// Posts are only inserted when theres atleast 3 users, which is what we use to seed the users table
if ($result['count'] == 0 && count($users) >= 3) 
{
    $SQL_insert_posts = "
        INSERT INTO Posts (user_id, title, slug, content)
        VALUES 
            ({$users[0]}, 'Getting Started with Blogging', 'getting-started', 'Welcome to the blogging world! This post will guide you through the basics.'),
            ({$users[1]}, 'My Daily Routine', 'my-daily-routine', 'A glimpse into my daily life and how I stay productive.'),
            ({$users[2]}, 'Life Hacks for Efficiency', 'life-hacks', 'Here are some useful tricks to make life easier and more efficient.');
            
    ";
    $db->exec($SQL_insert_posts);
}
} else {
    echo "<p class='alert alert-danger'>Error creating database.</p>";
}
?>