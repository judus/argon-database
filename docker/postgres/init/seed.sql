-- Roles
CREATE TABLE IF NOT EXISTS roles (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

-- Users
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Join table (many-to-many user-role)
CREATE TABLE IF NOT EXISTS user_roles (
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    role_id INTEGER REFERENCES roles(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, role_id)
);

-- Posts
CREATE TABLE IF NOT EXISTS posts (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert roles
INSERT INTO roles (name) VALUES
    ('admin'),
    ('editor'),
    ('viewer')
ON CONFLICT (name) DO NOTHING;

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

-- Add posts
INSERT INTO posts (user_id, title, content)
SELECT id, title, content FROM (
   VALUES
       ('Steve', 'Launch Update', 'We just deployed the new build to the test cluster.'),
       ('Alice', 'Design Notes', 'Here’s a breakdown of the new UI layout.'),
       ('Eva', 'Mission Log: Sector 9', 'Scouted the debris field. Found no survivors.'),
       ('Kara', 'Simulation Results', 'Results from the latest neural mesh simulation attached.')
) AS temp(name, title, content)
   JOIN users ON users.name = temp.name;
