<?php
require_once 'includes/config.php';

// Debug the add_question functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>POST Data Debug:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h2>Options Processing:</h2>";
    $options = [];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'options[') === 0 && !empty(trim($value))) {
            $options[] = trim($value);
            echo "Found option: " . trim($value) . "<br>";
        }
    }
    echo "Total options: " . count($options) . "<br>";
    echo "Options array: ";
    print_r($options);
}
?>

<form method="POST">
    <h2>Test Add Question Form</h2>
    <input type="hidden" name="quiz_id" value="1">
    <input type="hidden" name="question_statement" value="Test question">
    <input type="hidden" name="correct_option" value="0">
    <input type="hidden" name="marks" value="1">
    
    <input type="text" name="options[0]" value="Option 1" placeholder="Option 1">
    <input type="text" name="options[1]" value="Option 2" placeholder="Option 2">
    <input type="text" name="options[2]" value="Option 3" placeholder="Option 3">
    <input type="text" name="options[3]" value="Option 4" placeholder="Option 4">
    
    <button type="submit">Test Submit</button>
</form>