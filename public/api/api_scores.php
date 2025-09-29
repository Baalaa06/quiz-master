<?php
require_once '../../includes/config.php';
header('Content-Type: application/json');

$user = $_SESSION['user'] ?? null;
if (!$user) { 
    echo json_encode(['success'=>false,'message'=>'Login required']); 
    exit; 
}

$action = $_GET['action'] ?? '';

if ($action == 'list_user_scores') {
    try {
        $stmt = $pdo->prepare("SELECT a.*, q.name as quiz_name, c.name as chapter_name, s.name as subject_name FROM attempts a JOIN quizzes q ON a.quiz_id=q.id JOIN chapters c ON q.chapter_id=c.id JOIN subjects s ON c.subject_id=s.id WHERE a.user_id = ? ORDER BY a.time_stamp_of_attempt DESC");
        $stmt->execute([$user['id']]);
        $attempts = $stmt->fetchAll();
        echo json_encode(['success'=>true,'attempts'=>$attempts]);
    } catch (Exception $e) {
        echo json_encode(['success'=>false,'message'=>'Database error']);
    }
    exit;
}

if ($action == 'recent_attempts') {
    try {
        $stmt = $pdo->prepare("SELECT a.*, q.name as quiz_name FROM attempts a JOIN quizzes q ON a.quiz_id=q.id WHERE a.user_id = ? ORDER BY a.time_stamp_of_attempt DESC LIMIT 5");
        $stmt->execute([$user['id']]);
        $attempts = $stmt->fetchAll();
        echo json_encode(['success'=>true,'attempts'=>$attempts]);
    } catch (Exception $e) {
        echo json_encode(['success'=>false,'message'=>'Database error']);
    }
    exit;
}

echo json_encode(['success'=>false,'message'=>'Invalid action']);