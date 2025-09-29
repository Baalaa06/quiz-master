<?php
require_once '../includes/config.php';

$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Master</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
    <div class="navbar">
        <div class="container">
            <a href="/quiz-master/public/">Quiz Master</a>
            <?php if ($user): ?>
                <?php if ($user['is_admin']): ?>
                    <a href="admin/dashboard.php">Admin Dashboard</a>
                <?php else: ?>
                    <a href="user/dashboard.php">Dashboard</a>
                <?php endif; ?>
                <a href="auth/logout.php">Logout (<?= htmlspecialchars($user['username']) ?>)</a>
            <?php else: ?>
                <a href="/quiz-master/public/auth/login.php">Login</a>
                <a href="/quiz-master/public/auth/register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <div class="card fade-in">
            <h1 class="float">Welcome to Quiz Master</h1>
            <p style="font-size: 18px; color: #666; margin-bottom: 30px;">A comprehensive quiz management system for creating and taking quizzes.</p>
            
            <?php if (!$user): ?>
                <div class="slide-up">
                    <h2>Get Started</h2>
                    <p style="margin-bottom: 20px;">Please login or register to access the quiz system.</p>
                    <div style="display: flex; gap: 15px; justify-content: center;">
                        <a href="/quiz-master/public/auth/login.php"><button class="ripple">Login</button></a>
                        <a href="/quiz-master/public/auth/register.php"><button class="btn-success ripple">Register</button></a>
                    </div>
                </div>
            <?php else: ?>
                <div class="bounce-in">
                    <h2>Quick Actions</h2>
                    <?php if ($user['is_admin']): ?>
                        <div style="text-align: center; margin: 20px 0;">
                            <a href="admin/dashboard.php"><button class="glow ripple">Admin Dashboard</button></a>
                            <p style="margin-top: 15px; color: #666;">Manage subjects, chapters, quizzes, and questions.</p>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; margin: 20px 0;">
                            <a href="user/dashboard.php"><button class="glow ripple">User Dashboard</button></a>
                            <p style="margin-top: 15px; color: #666;">Take quizzes and view your scores.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>