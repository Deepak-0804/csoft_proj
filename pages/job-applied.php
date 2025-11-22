<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

$jobId = $_GET['id'] ?? null;

$result = JobAppliedService::handle($jobId);

if (!empty($result['error'])) {
    echo "<div class='alert alert-danger'>{$result['error']}</div>";
}
