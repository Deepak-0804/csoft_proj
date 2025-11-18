<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

$pdo    = $GLOBALS['pdo'];
$config = $GLOBALS['config'];
$BASE   = rtrim($config['base_url'], '/');

if (isset($_GET['id'])) {
    $jobId = (int)$_GET['id']; // cast to int for safety
} else {
    echo "No job selected";
    exit;
}


// Example PDO query
$stmt = $pdo->prepare("SELECT * FROM jobdescription WHERE Jobid = :id");
$stmt->execute(['id' => $jobId]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    echo "Job not found";
    exit;
}


$stmt2 = $pdo->prepare("SELECT * FROM jobs WHERE id = :id");
$stmt2->execute(['id' => $jobId]);
$jobDetails = $stmt2->fetch(PDO::FETCH_ASSOC);

if (!$jobDetails) {
    echo "Job details not found";
    exit;
}


?>

<div class="job-header">
  <img src="<?php echo htmlspecialchars($job['job_image']); ?>" alt="Job image" class="job-img">

  <div class="job-title-overlay">
    
    <div class="overlay-left">
      <!-- left scrollable content will go here -->
         <div class="job-summary">
            <h3>Job Summary :</h3>
            <!-- summary details will go here -->
              <p>
                <?php echo htmlspecialchars($job['job_summary']); ?>
              </p>
          </div>

          <div class="job-responsibilities">
            <h3>Responsibilities :</h3>
            <ul>
              <?php
                $responsibilities = explode("\n", trim($job['responsibilities']));
                foreach ($responsibilities as $item) {
                  if (trim($item) !== '') {
                    echo '<li>' . htmlspecialchars($item) . '</li>';
                  }
                }
              ?>
            </ul>
          </div>

          <div class="job-qualifications">
            <h3>Qualifications :</h3>
            <ul>
              <?php
                $qualifications = explode("\n", trim($job['qualifications']));
                foreach ($qualifications as $item) {
                  if (trim($item) !== '') {
                    echo '<li>' . htmlspecialchars($item) . '</li>';
                  }
                }
              ?>
            </ul>
          </div>

          <div class="job-certifications">
            <h3>Certifications :</h3>
            <ul>
              <?php
                $certifications = explode("\n", trim($job['certifications_required']));
                foreach ($certifications as $item) {
                  if (trim($item) !== '') {
                    echo '<li>' . htmlspecialchars($item) . '</li>';
                  }
                }
              ?>
            </ul>
          </div>

    </div>

    <div class="overlay-right">
      <div class="apply-now">
        <a href="index.php?page=job-applied&id=<?php echo htmlspecialchars($jobDetails['id']); ?>" class="apply-btn">
          Apply Now
        </a>
      </div>


      <div class="key-job-details">
        <p><strong><h2>Key Job Details</h2></strong></p>

        <p><strong>Job category:</strong> <?php echo htmlspecialchars($jobDetails['category'] ?? 'N/A'); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($jobDetails['location'] ?? 'N/A'); ?></p>

        <p><strong>Date published:</strong> 
            <?php 
              echo !empty($jobDetails['posted_date']) 
                ? date('M d, Y', strtotime($jobDetails['posted_date'])) 
                : 'N/A'; 
            ?>
        </p>
        <p><strong>Employment type:</strong> <?php echo htmlspecialchars($jobDetails['employment_type'] ?? 'N/A'); ?></p>
        <p><strong>Work model:</strong> <?php echo htmlspecialchars($jobDetails['work_model'] ?? 'N/A'); ?></p>
      </div>

    </div>

  </div>
</div>


