<?php
require_once '../../includes/config.php';
require_login();

$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Quiz Master</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/animations.css">
</head>
<body>
    <div class="navbar">
        <div class="container">
            <a href="../">Quiz Master</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="view_quizzes.php">Available Quizzes</a>
            <a href="view_scores.php">My Scores</a>
            <a href="wallet.php">Wallet</a>
            <a href="../auth/logout.php">Logout (<?= htmlspecialchars($user['username']) ?>)</a>
        </div>
    </div>

    <div class="container">
        <div class="card fade-in">
            <h1>User Dashboard</h1>
            <p style="font-size: 18px; color: #667eea; font-weight: 600;">Welcome back, <?= htmlspecialchars($user['full_name']) ?>!</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
            <div class="card slide-up float" style="text-align: center;">
                <h3>Available Quizzes</h3>
                <p style="color: #666; margin-bottom: 20px;">Browse and take available quizzes</p>
                <a href="view_quizzes.php"><button class="ripple">View Quizzes</button></a>
            </div>

            <div class="card slide-up float" style="text-align: center; animation-delay: 0.2s;">
                <h3>My Scores</h3>
                <p style="color: #666; margin-bottom: 20px;">View your quiz results and performance</p>
                <a href="view_scores.php"><button class="btn-success ripple">View Scores</button></a>
            </div>
        </div>

        <div class="card bounce-in">
            <h3>Recent Activity</h3>
            <div id="recentActivity" class="loading">Loading recent activity...</div>
        </div>
    </div>

    <script src="../js/app.js"></script>
    <script>
    ajaxRequest('../api/api_scores.php?action=recent_attempts')
        .then(response => {
            if (response.success && response.attempts.length > 0) {
                const html = response.attempts.map(a => 
                    `<div style="padding: 10px; border: 1px solid #ddd; margin: 5px 0;">
                        <strong>${a.quiz_name}</strong><br>
                        <small>Score: ${a.total_score} | Date: ${new Date(a.time_stamp_of_attempt).toLocaleDateString()}</small>
                    </div>`
                ).join('');
                document.getElementById('recentActivity').innerHTML = html;
            } else {
                document.getElementById('recentActivity').innerHTML = 'No recent activity';
            }
        })
        .catch(() => {
            document.getElementById('recentActivity').innerHTML = 'Error loading activity';
        });
    </script>
</body>
</html>