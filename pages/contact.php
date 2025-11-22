<?php

if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}


// handle POST logic BEFORE OUTPUT
$result = ContactService::handleFormSubmit();
$error = $result['error'] ?? null;

?>

<section class="contactcontainer">
    <div class="mb-4 p-4 text-white" style="background-size: cover; border-radius: 8px;">
        <h1>Contact Us</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="index.php?page=home" class="d-flex align-items-center text-decoration-none text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                            <path d="M8 3.293l6 6V15h-4v-4H6v4H2V9.293l6-6z" />
                        </svg>
                        Home
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
            </ol>
        </nav>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>


    <div class="row">
        <div class="col-md-6">
            <p>
                We believe in solving complex business challenges of the converging world, by using cutting-edge technologies.<br>
                Explore CSOFT worldwide locations<br>
                on our Global Footprint page.
            </p>
        </div>

        <div class="col-md-6">
            <form action="index.php?page=contact" method="post" novalidate>

                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES); ?>">

                <div class="mb-3">
                    <label for="first-name" class="form-label">First Name *</label>
                    <input type="text" class="form-control" id="first-name" name="first-name" required>
                </div>

                <div class="mb-3">
                    <label for="last-name" class="form-label">Last Name *</label>
                    <input type="text" class="form-control" id="last-name" name="last-name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>

                <div class="mb-3">
                    <label for="contact" class="form-label">Contact Number *</label>
                    <input type="tel" class="form-control" id="contact" name="contact" required>
                </div>

                <div class="mb-3">
                    <label for="company" class="form-label">Your Company's Name *</label>
                    <input type="text" class="form-control" id="company" name="company" required>
                </div>

                <div class="mb-3">
                    <label for="country" class="form-label">Country *</label>
                    <select class="form-select" id="country" name="country" required>
                        <option value="">Select...</option>
                        <option value="india">India</option>
                        <option value="usa">USA</option>
                        <option value="uk">UK</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="industry" class="form-label">Your Industry *</label>
                    <select class="form-select" id="industry" name="industry" required>
                        <option value="">Select...</option>
                        <option value="it">IT</option>
                        <option value="finance">Finance</option>
                        <option value="healthcare">Healthcare</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="services" class="form-label">Services/Technologies interested in</label>
                    <select class="form-select" id="services" name="services">
                        <option value="">Select...</option>
                        <option value="cloud">Cloud</option>
                        <option value="ai">AI/ML</option>
                        <option value="devops">DevOps</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="referred" class="form-label">Referred by *</label>
                    <select class="form-select" id="referred" name="referred" required>
                        <option value="">Select...</option>
                        <option value="friend">Friend</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="website">Website</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Let us know how we can help you today *</label>
                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="marketing" name="marketing">
                    <label class="form-check-label" for="marketing">
                        I agree to receive marketing communication from CSOFT
                    </label>
                </div>

                <div class="mb-3">
                    <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($config['recaptcha_site_key']); ?>"></div>

                </div>

                <button type="submit" class="btn btn-primary">Submit</button>

                <script src="https://www.google.com/recaptcha/api.js" async defer></script>

            </form>
        </div>
    </div>

</section>