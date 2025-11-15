<?php
function showError($message) {
    // You can return HTML or just the message
    return "<div class='error-message'>{$message}</div>";
}

function showSuccess($message) {
    return "<div class='success-message'>{$message}</div>";
}
?>
