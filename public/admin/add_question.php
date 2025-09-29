<?php
require_once '../../includes/config.php';
require_login();
require_admin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Questions - Quiz Master</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="navbar">
        <div class="container">
            <a href="../">Quiz Master</a>
            <a href="dashboard.php">Admin Dashboard</a>
            <a href="../auth/logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h2>Add Question</h2>
            <form id="questionForm">
                <div class="form-group">
                    <label for="quiz_id">Quiz:</label>
                    <select id="quiz_id" name="quiz_id" required>
                        <option value="">Select Quiz</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="question_statement">Question:</label>
                    <textarea id="question_statement" name="question_statement" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Options:</label>
                    <input type="text" name="options[]" placeholder="Option 1" required>
                    <input type="text" name="options[]" placeholder="Option 2" required>
                    <input type="text" name="options[]" placeholder="Option 3">
                    <input type="text" name="options[]" placeholder="Option 4">
                </div>
                <div class="form-group">
                    <label for="correct_option">Correct Option (0-based index):</label>
                    <select id="correct_option" name="correct_option" required>
                        <option value="0">Option 1</option>
                        <option value="1">Option 2</option>
                        <option value="2">Option 3</option>
                        <option value="3">Option 4</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="marks">Marks:</label>
                    <input type="number" id="marks" name="marks" value="1" min="1" required>
                </div>
                <button type="submit">Add Question</button>
            </form>
        </div>
    </div>

    <script src="../js/app.js"></script>
    <script>
    document.getElementById('questionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const quiz_id = document.getElementById('quiz_id').value;
        const question_statement = document.getElementById('question_statement').value;
        const correct_option = document.getElementById('correct_option').value;
        const marks = document.getElementById('marks').value;
        
        const options = [];
        const optionInputs = this.querySelectorAll('input[name="options[]"]');
        optionInputs.forEach(input => {
            if (input.value.trim()) {
                options.push(input.value.trim());
            }
        });
        
        if (!quiz_id || !question_statement || options.length < 2) {
            showAlert('Please fill all fields and provide at least 2 options', 'danger');
            return;
        }
        
        const data = {
            quiz_id: quiz_id,
            question_statement: question_statement,
            correct_option: correct_option,
            marks: marks,
            options: JSON.stringify(options)
        };
        
        ajaxRequest('../api/api_admin.php?action=add_question', data, 'POST')
            .then(response => {
                if (response.success) {
                    showAlert('Question added successfully!');
                    this.reset();
                } else {
                    showAlert(response.message, 'danger');
                }
            })
            .catch(error => {
                showAlert('Error adding question', 'danger');
            });
    });

    function loadQuizzes() {
        ajaxRequest('../api/api_admin.php?action=get_quizzes')
            .then(response => {
                if (response.success) {
                    const select = document.getElementById('quiz_id');
                    select.innerHTML = '<option value="">Select Quiz</option>' +
                        response.quizzes.map(q => `<option value="${q.id}">${q.subject_name} - ${q.chapter_name} - ${q.name}</option>`).join('');
                } else {
                    showAlert('Error loading quizzes', 'danger');
                }
            })
            .catch(error => {
                showAlert('Error loading quizzes', 'danger');
            });
    }

    loadQuizzes();
    </script>
</body>
</html>