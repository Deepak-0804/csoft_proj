<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

?>

<section class="hero">
    <div class="container-fluid p-0">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide banner-slide d-flex align-items-center justify-content-start text-white">
                    <div class="ps-5">
                        <h1>Welcome to Csoft!</h1>
                        <p>Your trusted partner for software solutions.</p>
                        <a href="index.php?page=contact" class="btn btn-get-in-touch mt-3">Get in touch</a>
                    </div>
                </div>

                <div class="swiper-slide">
                    <img src="AItraining.png" class="img-fluid w-100" alt="Slide 2">
                </div>
                <div class="swiper-slide">
                    <img src="Engineering.png" class="img-fluid w-100" alt="Slide 3">
                </div>
                <!-- Add more slides if needed -->
            </div>

            <!-- Optional navigation buttons -->

            <!-- Optional pagination dots -->
            <div class="swiper-pagination"></div>
        </div>

        <!-- Swiper goes here -->
    </div>

    <div class="container">

        <div class="container1">
            <span>What we Offer</span>
        </div>
        <div class="container2">
            <h2>Our Services</h2>
        </div>
        <div class="container-fluid servicescontainer">
            <div class="container my-5">
                <div class="row g-4">

                    <!-- Hospital Management -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                                    <h5 class="card-title mb-0">Hospital Management System</h5>
                                    <div class="icon-badge d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-home text-white"></i>
                                    </div>
                                </div>
                                <p class="card-text">
                                    <span class="quote-start">
                                        <!-- Inline SVG for opening quote -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#dc0000" viewBox="0 0 24 24" style="vertical-align: middle;">
                                            <text x="0" y="20" font-family="serif" font-weight="bold" font-size="24">“</text>
                                        </svg>
                                    </span>

                                    Our Hospital Management System helps hospitals streamline patient care, appointments, staff management, billing, and reporting, ensuring smooth operations and better healthcare services.

                                    <span class="quote-end">
                                        <!-- Inline SVG for closing quote -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#dc0000" viewBox="0 0 24 24" style="vertical-align: middle;">
                                            <text x="0" y="20" font-family="serif" font-weight="bold" font-size="24">”</text>
                                        </svg>
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="services.php" class="btn btn-outline-primary">Read More</a>
                            </div>
                        </div>
                    </div>

                    <!-- Dental Management -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                                    <h5 class="card-title mb-0">Dental Management System</h5>
                                    <div class="icon-badge d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-tooth text-white"></i>
                                    </div>
                                </div>
                                <p class="card-text">
                                    <span class="quote-start">
                                        <!-- Inline SVG for opening quote -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#dc0000" viewBox="0 0 24 24" style="vertical-align: middle;">
                                            <text x="0" y="20" font-family="serif" font-weight="bold" font-size="24">“</text>
                                        </svg>
                                    </span>
                                    Our Dental Management System helps clinics manage patients, appointments, treatments, and billing efficiently, ensuring smooth operations and better patient care.
                                    <span class="quote-end">
                                        <!-- Inline SVG for closing quote -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#dc0000" viewBox="0 0 24 24" style="vertical-align: middle;">
                                            <text x="0" y="20" font-family="serif" font-weight="bold" font-size="24">”</text>
                                        </svg>
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="services.php" class="btn btn-outline-primary">Read More</a>
                            </div>
                        </div>
                    </div>


                    <!-- Education Management -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                                    <h5 class="card-title mb-0">Education Management System</h5>
                                    <div class="icon-badge d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-book text-white"></i>
                                    </div>
                                </div>
                                <p class="card-text">
                                    <span class="quote-start">
                                        <!-- Inline SVG for opening quote -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#dc0000" viewBox="0 0 24 24" style="vertical-align: middle;">
                                            <text x="0" y="20" font-family="serif" font-weight="bold" font-size="24">“</text>
                                        </svg>
                                    </span>
                                    Our Educational Management System helps institutions manage students, classes, exams, and results in a simple and organized way, making administration smoother.
                                    <span class="quote-end">
                                        <!-- Inline SVG for closing quote -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#dc0000" viewBox="0 0 24 24" style="vertical-align: middle;">
                                            <text x="0" y="20" font-family="serif" font-weight="bold" font-size="24">”</text>
                                        </svg>
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="services.php" class="btn btn-outline-primary">Read More</a>
                            </div>
                        </div>
                    </div>

                    <!-- Store Management -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                                    <h5 class="card-title mb-0">Store Management System</h5>
                                    <div class="icon-badge d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-store text-white"></i>
                                    </div>
                                </div>
                                <p class="card-text">
                                    <span class="quote-start">
                                        <!-- Inline SVG for opening quote -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#dc0000" viewBox="0 0 24 24" style="vertical-align: middle;">
                                            <text x="0" y="20" font-family="serif" font-weight="bold" font-size="24">“</text>
                                        </svg>
                                    </span>
                                    An efficient Store Management System to manage inventory, sales, suppliers, and orders, ensuring smooth store operations.
                                    <span class="quote-end">
                                        <!-- Inline SVG for closing quote -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#dc0000" viewBox="0 0 24 24" style="vertical-align: middle;">
                                            <text x="0" y="20" font-family="serif" font-weight="bold" font-size="24">”</text>
                                        </svg>
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="services.php" class="btn btn-outline-primary">Read More</a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <div class="testimonials">
        <div class="heading">
            <span>What Our Clients Say</span>
            <h2>Testimonials</h2>
        </div>
        <div class="testimonial-slider">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide testi-item">
                        <img src="assets/images/download1.jpg" alt="Client 1" class="testi-img">
                        <p class="testi-text">"Great service and support!"<br><span class="testi-name">- Client 1</span></p>
                    </div>
                    <div class="swiper-slide testi-item">
                        <img src="assets/images/download2.jpg" alt="Client 2" class="testi-img">
                        <p class="testi-text">"Very satisfied with the results."<br><span class="testi-name">- Client 2</span></p>
                    </div>
                    <div class="swiper-slide testi-item">
                        <img src="assets/images/download3.jpg" alt="Client 3" class="testi-img">
                        <p class="testi-text">"Highly recommend their services!"<br><span class="testi-name">- Client 3</span></p>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

        </div>
    </div>
</section>


<script>
    const swiper = new Swiper('.swiper', {
        loop: true,
        slidesPerView: 1, // 👈 THIS is crucial
        spaceBetween: 30,
        pagination: {
            el: '.swiper-pagination',
            clickable: true, // ✅ fixed
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
    });

</script>