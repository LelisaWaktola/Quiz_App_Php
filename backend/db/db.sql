CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT
);
CREATE TABLE quizzes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  category_id INT NOT NULL,
  created_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);
CREATE TABLE questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quiz_id INT NOT NULL,
  question_text TEXT NOT NULL,
  FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);
CREATE TABLE results (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  quiz_id INT NOT NULL,
  score INT NOT NULL,
  taken_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);



INSERT INTO categories (name, description) VALUES  
('Biology', 'Test your knowledge of life sciences.'),
('Physics', 'Challenge yourself with physics problems.'),
('Chemistry', 'Master reactions, atoms, and molecules.'),
('Mathematics', 'Sharpen your problem-solving skills.'),
('Computer Science', 'Explore programming, logic, and tech.'),
('History', 'Dive into past events and cultures.');
INSERT INTO users (name, email, password) VALUES
('Alice', 'alice@example.com', 'hashed_password_1'),
('Bob', 'bob@example.com', 'hashed_password_2');
INSERT INTO quizzes (title, category_id) VALUES
('Basic Biology Quiz', 1),
('Intro to Physics', 2),
('Chemistry Concepts', 3),
('Algebra Practice', 4),
('Programming Basics', 5),
('Ancient History', 6);
INSERT INTO questions (quiz_id, question_text) VALUES
(1, 'What is the powerhouse of the cell?'),
(1, 'What do plants use for photosynthesis?');
INSERT INTO results (user_id, quiz_id, score) VALUES
(1, 1, 90),
(2, 2, 70);


CREATE TABLE admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
