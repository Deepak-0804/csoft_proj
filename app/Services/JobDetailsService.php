<?php

class JobDetailsService {

    public static function getJobDetails($jobId) {
        $pdo = $GLOBALS['pdo'];

        if (!$jobId) {
            return ['error' => "No job selected"];
        }

        // 1️⃣ Load detailed job description
        $stmt = $pdo->prepare("SELECT * FROM jobdescription WHERE Jobid = :id");
        $stmt->execute(['id' => $jobId]);
        $job = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$job) {
            return ['error' => "Job not found"];
        }

        // 2️⃣ Load short job info
        $stmt2 = $pdo->prepare("SELECT * FROM jobs WHERE id = :id");
        $stmt2->execute(['id' => $jobId]);
        $jobDetails = $stmt2->fetch(PDO::FETCH_ASSOC);

        if (!$jobDetails) {
            return ['error' => "Job details not found"];
        }

        return [
            'job'        => $job,
            'jobDetails' => $jobDetails,
            'error'      => null
        ];
    }
}
