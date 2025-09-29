CREATE DATABASE IF NOT EXISTS quiz_master CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quiz_master;

-- Users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  qualification VARCHAR(100),
  dob DATE NOT NULL,
  is_admin TINYINT(1) DEFAULT 0,
  wallet_balance DECIMAL(10,2) DEFAULT 10.00,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Subjects
CREATE TABLE subjects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  created_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Chapters
CREATE TABLE chapters (
  id INT AUTO_INCREMENT PRIMARY KEY,
  subject_id INT NOT NULL,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

-- Quizzes
CREATE TABLE quizzes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  chapter_id INT NOT NULL,
  creator_id INT,
  date_of_quiz DATE NOT NULL,
  time_duration INT NOT NULL, -- in minutes
  remarks TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (chapter_id) REFERENCES chapters(id) ON DELETE CASCADE,
  FOREIGN KEY (creator_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Questions
-- options stored in JSON array: ['option A','option B','option C','option D']
CREATE TABLE questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quiz_id INT NOT NULL,
  question_statement TEXT NOT NULL,
  options JSON NOT NULL,
  correct_option INT NOT NULL, -- index (0..n-1)
  marks INT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Scores / Attempts
CREATE TABLE attempts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quiz_id INT NOT NULL,
  user_id INT NOT NULL,
  time_stamp_of_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  total_score INT NOT NULL,
  raw_response JSON, -- store per-question answers and correctness
  FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bets
CREATE TABLE bets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  quiz_id INT NOT NULL,
  bet_amount DECIMAL(10,2) NOT NULL,
  target_score INT NOT NULL,
  actual_score INT DEFAULT NULL,
  payout DECIMAL(10,2) DEFAULT 0.00,
  status ENUM('pending', 'won', 'lost') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, full_name, email, dob, is_admin, wallet_balance) 
VALUES ('admin', 'admin123', 'Administrator', 'admin@quiz.com', '1990-01-01', 1, 100.00);