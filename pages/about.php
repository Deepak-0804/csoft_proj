
<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

$pdo = $GLOBALS['pdo'];        // Use the global PDO instance
$config = $GLOBALS['config'];  // Use global config

?>
<section class="aboutus">
    <div class="aboutcontainer">
        <div class="page-title">

            <div class="title">
                <h1>About Us</h1>
            </div>
            <div class="subtitle">
                <p>
                    <!-- Home link with SVG icon -->
                    <a href="index.php?page=home" class="breadcrumb-home">
                        <!-- Inline SVG for home icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 3.293l6 6V15h-4v-4H6v4H2V9.293l6-6z"/>
                        </svg>
                        Home
                    </a>
                    <!-- Separator -->
                    <span> / </span>
                    <!-- Current page text -->
                    <span class="current-page">About Us</span>
                </p>
            </div>
        </div>
    </div>
    <div class="aboutcompany">
      <p> 
            We have team members who have worked on the scales of Petabytes of Data managing Enterprise Solutions with Companies like Infosys, Wipro,Schwab, Compuware, Intel, Hyuwei,Sasken etc - who passionately understand the needs of a small budget to mega budget projects. Many of our Leaders worked in USA,UK,Germany for many years, so that we implement international standards as a must with every solution we work on for "the special You". We believe that
            "Customers are for ever- not just one time"
            <br><br>
            We have a strong team of Certified Professionals with proven expertise in Oracle,Dot Net, Java, MSSQL, Microsoft Programming, PHP,Netezza,Mysql, ajax, Java, android framework, objective-c , and iphone application programming.
            <br><br>
            Chandusoft offers its customers the best facilities and endless opportunities in the disciplines of Mobile Application Development , E-Commerce Development, Web Portal Development, Enterprise Application Development, Cross Platform Data porting, Enterprise level Architecting, Big Data Solutions,Data Quality Analysis, ETL and Data Management and Testing.
            <br><br>
            Chandusoft offers vibrant designs and wide spread options to its eCommerce customers who want to customize their shopping cart software and to meet its unique business needs and also distinguishes the site apart from its competitors.Zencart is giving a very tough competition to the OScommerce in India. Chandusoft offers the optimum packages for organization’s owner and provides ecommerce business solutions to many of its customers.
      </p>
    </div>

    <div class="glancecontainer reveal">
        <div class="glance-itemname">
            <h2>CSOFT at a Glance</h2>
        </div>
        <div class="glancerow">
            <div class="glance-item ">
                <h3>10+</h3>
                <p>Years of Experience</p>
            </div>
            <div class="glance-item ">
                <h3>500+</h3>
                <p>Happy Clients</p>
            </div>
            <div class="glance-item ">
                <h3>1000+</h3>
                <p>Projects Delivered</p>
            </div>
        </div>
    </div>

    <div class="BOD">
        <h2>Board of Directors</h2>
        <div class="BODcontainer">
            <div class="BOD1 slide-left">
                <img src="assets/images/mallikarjun_sir_img.jpg" alt="CEO Image" class="BODimg">
                <div class="BODinfo">
                    <h3 class="BODname">Mallikarjun Chandu</h3>
                    <div class="designation">Cheif Executive Officer (CEO)</div>
                    <a href="index.php?page=info" class="BODlink">
                        More Info
                        <span class="arrow">→</span>
                    </a>
                </div>
            </div>

            <div class="BOD2 slide-right">
                <img src="assets/images/kanakadurga_mam_img.jpg" alt="CEO Image" class="BODimg">
                <div class="BODinfo ">
                    <h3 class="BODname">Rama KanakaDurga Chandu</h3>
                    <div class="designation">Director</div>
                    <a href="index.php?page=info" class="BODlink">
                        More Info
                        <span class="arrow">→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
const container = document.querySelector('.reveal');

const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if(entry.isIntersecting){
            entry.target.classList.add('active');
        }
        else {
      entry.target.classList.remove('active'); // hide animation
    }
    });
}, { threshold: 0.1 });

observer.observe(container);



const items = document.querySelectorAll('.slide-left, .slide-right');

const observerr = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if(entry.isIntersecting){
      entry.target.classList.add('active');    // slide in
    } else {
      entry.target.classList.remove('active'); // optional: reverse
    }
  });
}, { threshold: 0.2 });

items.forEach(item => observerr.observe(item));


</script>