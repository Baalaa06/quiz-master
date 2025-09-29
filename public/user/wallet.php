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
    <title>My Wallet - Quiz Master</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="navbar">
        <div class="container">
            <a href="../">Quiz Master</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="view_quizzes.php">Available Quizzes</a>
            <a href="wallet.php">Wallet</a>
            <a href="../auth/logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h2>My Wallet</h2>
            <div style="text-align: center; padding: 20px;">
                <h3>Balance: $<span id="walletBalance"><?= number_format($user['wallet_balance'], 2) ?></span></h3>
            </div>
        </div>

        <div class="card">
            <h3>Betting History</h3>
            <div id="bettingHistory">Loading...</div>
        </div>
    </div>

    <script src="../js/app.js"></script>
    <script>
    function loadBets() {
        ajaxRequest('../api/api_betting.php?action=get_bets')
            .then(response => {
                if (response.success) {
                    const html = response.bets.map(b => {
                        const statusColor = b.status === 'won' ? '#28a745' : b.status === 'lost' ? '#dc3545' : '#ffc107';
                        return `<div style="padding: 15px; border: 1px solid #ddd; margin: 10px 0; border-left: 4px solid ${statusColor};">
                            <h4>${b.quiz_name}</h4>
                            <p><strong>Bet Amount:</strong> $${b.bet_amount}</p>
                            <p><strong>Target Score:</strong> ${b.target_score}</p>
                            <p><strong>Actual Score:</strong> ${b.actual_score || 'Pending'}</p>
                            <p><strong>Status:</strong> ${b.status.toUpperCase()}</p>
                            <p><strong>Payout:</strong> $${b.payout}</p>
                            <small>Date: ${new Date(b.created_at).toLocaleString()}</small>
                        </div>`;
                    }).join('');
                    document.getElementById('bettingHistory').innerHTML = html || '<p>No bets placed yet</p>';
                } else {
                    document.getElementById('bettingHistory').innerHTML = '<p>Error loading betting history</p>';
                }
            });
    }

    loadBets();
    </script>
</body>
</html>