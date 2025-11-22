<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

$data = CareersService::getCareersData();
extract($data);  
$result = $data['result'];
$currentPage = $_GET['pg'] ?? 1;   // IMPORTANT FIX
?>



<div class="bg-mesh">
    <div class="career-container">
        <div class="career-header">
            <div class="career-title">
                <h2>Your first job at CSOFT is just the beginning of your journey</h2>
            </div>
            <div class="career-count">
                <p>Displaying <strong><?php echo $start; ?></strong> to <strong><?php echo $end; ?></strong> of <strong><?php echo $total_jobs; ?></strong> matching jobs</p>
            </div>
        </div>

        <div class="career-footer">
           <div class="career-form">
               <form method="GET" action="" class="search-form">
                    <input type="hidden" name="page" value="careers"> <!-- Keep page routing -->
                    <select name="job" class="search-field">
                        <option value="">Search Jobs</option>
                        <?php foreach($jobs as $job): ?>
                            <option value="<?php echo htmlspecialchars($job); ?>" <?php if(isset($_GET['job']) && $_GET['job'] == $job) echo 'selected'; ?>><?php echo htmlspecialchars($job); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="industry" class="search-field">
                        <option value="">Industry</option>
                        <?php foreach($industries as $industry): ?>
                            <option value="<?php echo htmlspecialchars($industry); ?>" <?php if(isset($_GET['industry']) && $_GET['industry'] == $industry) echo 'selected'; ?>><?php echo htmlspecialchars($industry); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="location" class="search-field">
                        <option value="">Location</option>
                        <?php foreach($locations as $loc): ?>
                            <option value="<?php echo htmlspecialchars($loc); ?>"<?php if(isset($_GET['location']) && $_GET['location'] == $loc) echo 'selected'; ?>><?php echo htmlspecialchars($loc); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="category" class="search-field">
                        <option value="">Job Category</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>"<?php if(isset($_GET['category']) && $_GET['category'] == $cat) echo 'selected'; ?>><?php echo htmlspecialchars($cat); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="employment" class="search-field">
                        <option value="">Employment Type</option>
                        <?php foreach($employmentTypes as $emp): ?>
                            <option value="<?php echo htmlspecialchars($emp); ?>"<?php if(isset($_GET['employment']) && $_GET['employment'] == $emp) echo 'selected'; ?>><?php echo htmlspecialchars($emp); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="workmodel" class="search-field">
                        <option value="">Work Model</option>
                        <?php foreach($workModels as $wm): ?>
                            <option value="<?php echo htmlspecialchars($wm); ?>"<?php if(isset($_GET['workmodel']) && $_GET['workmodel'] == $wm) echo 'selected'; ?>><?php echo htmlspecialchars($wm); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="search-btn">Search</button>
               </form>

                <div class="job-list-container">
                    <?php if ($result->rowCount() > 0): ?>
                            <?php while($job = $result->fetch(PDO::FETCH_ASSOC)): ?>
                                <div class="job-card">
                                    <h2 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h2>
                                    <p><strong>Industry:</strong> <?php echo htmlspecialchars($job['industry']); ?></p>
                                    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                                    <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
                                    <p>
                                        <strong>Employment Type:</strong> 
                                        <span class="badge employment-type"><?php echo htmlspecialchars($job['employment_type']); ?></span>
                                    </p>
                                    <p>
                                        <strong>Work Model:</strong> 
                                        <span class="badge work-model"><?php echo htmlspecialchars($job['work_model']); ?></span>
                                    </p>
                                    <p><strong>Description:</strong> <?php echo htmlspecialchars($job['description']); ?></p>
                                    <p><strong>Posted Date:</strong> <?php echo date("d-m-Y", strtotime($job['posted_date'])); ?></p>
                                     <div class="more-details">
                                        <a href="index.php?page=job-details&id=<?php echo $job['id']; ?>">Apply Now →</a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No jobs found.</p>
                    <?php endif; ?>
                </div>
                <div class="pagination">
                    <?php if($currentPage > 1): ?>
                        <a href="?<?php echo $filterQuery; ?>pg=<?php echo $currentPage - 1; ?>">Previous</a>
                    <?php endif; ?>

                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?<?php echo $filterQuery; ?>pg=<?php echo $i; ?>" <?php if($i == $currentPage) echo 'class="active"'; ?>><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if($currentPage < $total_pages): ?>
                        <a href="?<?php echo $filterQuery; ?>pg=<?php echo $currentPage + 1; ?>">Next</a>
                    <?php endif; ?>
                </div>


           </div>
        </div>
    
    </div>
</div>


