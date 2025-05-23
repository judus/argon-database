-- Roles
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Join table (many-to-many user-role)
CREATE TABLE IF NOT EXISTS user_roles (
    user_id INT,
    role_id INT,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Posts
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert roles (ignore duplicates)
INSERT INTO roles (name) VALUES
    ('admin'),
    ('editor'),
    ('viewer')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Insert users
INSERT INTO users (name, email) VALUES
    ('Steve', 'steve@example.com'),
    ('Alice', 'alice@example.com'),
    ('Bob', 'bob@example.com'),
    ('Eva', 'eva@synthetix.space'),
    ('Kara', 'kara@citadel.ai');

-- Assign roles to users
INSERT INTO user_roles (user_id, role_id)
SELECT u.id, r.id FROM users u, roles r
WHERE (u.name = 'Steve' AND r.name = 'admin')
   OR (u.name = 'Alice' AND r.name IN ('editor', 'viewer'))
   OR (u.name = 'Bob' AND r.name = 'viewer')
   OR (u.name = 'Eva' AND r.name = 'admin')
   OR (u.name = 'Kara' AND r.name = 'editor');

-- Insert posts
INSERT INTO posts (user_id, title, content)
    SELECT users.id, t.title, t.content
    FROM (
        SELECT 'Steve' AS name, 'Launch Update' AS title, 'We just deployed the new build to the test cluster.' AS content
        UNION ALL
        SELECT 'Alice', 'Design Notes', 'Here’s a breakdown of the new UI layout.'
        UNION ALL
        SELECT 'Eva', 'Mission Log: Sector 9', 'Scouted the debris field. Found no survivors.'
        UNION ALL
        SELECT 'Kara', 'Simulation Results', 'Results from the latest neural mesh simulation attached.'
    ) AS t
        JOIN users ON users.name = t.name;
