<?php
require_once '../../includes/config.php';
require_once '../../includes/csrf.php';

// If already logged in, redirect
if (current_user()) {
    header('Location: /quiz-master/public/');
    exit;
}

// Handle AJAX registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');
    $qualification = trim($_POST['qualification'] ?? '');
    $dob = $_POST['dob'] ?? '1970-01-01';
    
    if (!$username || !$email || !$password || !$full_name) {
        echo json_encode(['success'=>false,'message'=>'Missing required fields']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success'=>false,'message'=>'Invalid email']);
        exit;
    }
    
    if (strlen($password) < 6) {
        echo json_encode(['success'=>false,'message'=>'Password must be at least 6 characters']);
        exit;
    }
    
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username,email,password,full_name,qualification,dob,wallet_balance) VALUES(?,?,?,?,?,?,10.00)");
        $stmt->execute([$username, $email, $hash, $full_name, $qualification, $dob]);
        echo json_encode(['success'=>true,'message'=>'Registered successfully']);
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            echo json_encode(['success'=>false,'message'=>'Username or email already exists']);
        } else {
            echo json_encode(['success'=>false,'message'=>'Database error']);
        }
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Quiz Master</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="navbar">
        <div class="container">
            <a href="/quiz-master/public/">Quiz Master</a>
            <a href="/quiz-master/public/auth/login.php">Login</a>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h2>Register</h2>
            <form id="registerForm">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>
                
                <div class="form-group">
                    <label for="qualification">Qualification:</label>
                    <input type="text" id="qualification" name="qualification">
                </div>
                
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit">Register</button>
            </form>
            
            <p style="margin-top: 20px;">
                Already have an account? <a href="/quiz-master/public/auth/login.php">Login here</a>
            </p>
        </div>
    </div>

    <script src="../js/app.js"></script>
    <script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = serializeForm(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Registering...';
        
        ajaxRequest('register.php', formData, 'POST')
            .then(response => {
                if (response.success) {
                    showAlert('Registration successful! Please login.', 'success');
                    setTimeout(() => {
                        window.location.href = '/quiz-master/public/auth/login.php';
                    }, 2000);
                } else {
                    showAlert(response.message, 'danger');
                }
            })
            .catch(error => {
                showAlert('An error occurred. Please try again.', 'danger');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Register';
            });
    });
    </script>
</body>
</html>