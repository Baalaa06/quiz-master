<?php
require_once '../../includes/config.php';
header('Content-Type: application/json');

$user = $_SESSION['user'] ?? null;
if (!$user) { 
    echo json_encode(['success'=>false,'message'=>'Login required']); 
    exit; 
}

$action = $_GET['action'] ?? '';

if ($action === 'place_bet') {
    $quiz_id = intval($_POST['quiz_id'] ?? 0);
    $bet_amount = floatval($_POST['bet_amount'] ?? 0);
    $target_score = intval($_POST['target_score'] ?? 0);
    
    if (!$quiz_id || $bet_amount <= 0 || $target_score < 0) {
        echo json_encode(['success'=>false,'message'=>'Invalid bet parameters']);
        exit;
    }
    
    try {
        // Check if user has sufficient balance
        $stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        $balance = $stmt->fetchColumn();
        
        if ($balance < $bet_amount) {
            echo json_encode(['success'=>false,'message'=>'Insufficient balance']);
            exit;
        }
        
        // Check if user already has a bet on this quiz
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM bets WHERE user_id = ? AND quiz_id = ?");
        $stmt->execute([$user['id'], $quiz_id]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['success'=>false,'message'=>'Bet already placed on this quiz']);
            exit;
        }
        
        // Deduct bet amount from wallet
        $stmt = $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance - ? WHERE id = ?");
        $stmt->execute([$bet_amount, $user['id']]);
        
        // Place bet
        $stmt = $pdo->prepare("INSERT INTO bets (user_id, quiz_id, bet_amount, target_score) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user['id'], $quiz_id, $bet_amount, $target_score]);
        
        // Update session balance
        $_SESSION['user']['wallet_balance'] -= $bet_amount;
        
        echo json_encode(['success'=>true,'message'=>'Bet placed successfully']);
    } catch (Exception $e) {
        echo json_encode(['success'=>false,'message'=>'Database error']);
    }
    exit;
}

if ($action === 'get_wallet') {
    try {
        $stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        $balance = $stmt->fetchColumn();
        echo json_encode(['success'=>true,'balance'=>$balance]);
    } catch (Exception $e) {
        echo json_encode(['success'=>false,'message'=>'Database error']);
    }
    exit;
}

if ($action === 'get_bets') {
    try {
        $stmt = $pdo->prepare("SELECT b.*, q.name as quiz_name FROM bets b JOIN quizzes q ON b.quiz_id = q.id WHERE b.user_id = ? ORDER BY b.created_at DESC");
        $stmt->execute([$user['id']]);
        $bets = $stmt->fetchAll();
        echo json_encode(['success'=>true,'bets'=>$bets]);
    } catch (Exception $e) {
        echo json_encode(['success'=>false,'message'=>'Database error']);
    }
    exit;
}

echo json_encode(['success'=>false,'message'=>'Invalid action']);
?>