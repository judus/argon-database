-- Roles
CREATE TABLE IF NOT EXISTS roles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
);

-- Users
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT UNIQUE,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

-- Join table (many-to-many user-role)
CREATE TABLE IF NOT EXISTS user_roles (
    user_id INTEGER NOT NULL,
    role_id INTEGER NOT NULL,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Posts
CREATE TABLE IF NOT EXISTS posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    published_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert roles
INSERT OR IGNORE INTO roles (name) VALUES
    ('admin'),
    ('editor'),
    ('viewer');

-- Insert users
INSERT OR IGNORE INTO users (name, email) VALUES
    ('Steve', 'steve@example.com'),
    ('Alice', 'alice@example.com'),
    ('Bob', 'bob@example.com'),
    ('Eva', 'eva@synthetix.space'),
    ('Kara', 'kara@citadel.ai');

-- Assign roles to users
INSERT OR IGNORE INTO user_roles (user_id, role_id)
SELECT u.id, r.id FROM users u, roles r
WHERE (u.name = 'Steve' AND r.name = 'admin')
   OR (u.name = 'Alice' AND r.name IN ('editor', 'viewer'))
   OR (u.name = 'Bob' AND r.name = 'viewer')
   OR (u.name = 'Eva' AND r.name = 'admin')
   OR (u.name = 'Kara' AND r.name = 'editor');

-- Add posts
-- Add posts (SQLite-friendly)
INSERT INTO posts (user_id, title, content)
SELECT u.id, 'Launch Update', 'We just deployed the new build to the test cluster.'
FROM users u WHERE u.name = 'Steve';

INSERT INTO posts (user_id, title, content)
SELECT u.id, 'Design Notes', 'Here’s a breakdown of the new UI layout.'
FROM users u WHERE u.name = 'Alice';

INSERT INTO posts (user_id, title, content)
SELECT u.id, 'Mission Log: Sector 9', 'Scouted the debris field. Found no survivors.'
FROM users u WHERE u.name = 'Eva';

INSERT INTO posts (user_id, title, content)
SELECT u.id, 'Simulation Results', 'Results from the latest neural mesh simulation attached.'
FROM users u WHERE u.name = 'Kara';
