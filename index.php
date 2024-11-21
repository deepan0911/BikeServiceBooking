
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Repair Booking</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Noto+Sans+JP:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php session_start(); ?>
  <header>
  <nav>
  <div class="nav-container">
    <ul>
      <li class="dropdown">
        <a href="#" class="dropbtn">ACCOUNT</a>
        <div class="dropdown-content">
          <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) { ?>
            <a href="logout.php" class="login" onclick="return logoutAlert(event)">LOGOUT</a>
            <a href="bookings.php" class="login">BOOKINGS</a>
            <a href="payment.php" class="login">PAYMENT</a>
          <?php } else { ?>
            <a href="user_login.html" class="login">LOGIN</a>
          <?php } ?>
        </div>
      </li>
      <li><a href="index.php">HOME</a></li>
      <li><a href="#about">ABOUT</a></li>
      <li><a href="#contact">CONTACT</a></li>
    </ul>
  </div>
</nav>
</header>


  
  <main>
    <section class="hero">
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) { ?>
    <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
  <?php } ?>
      <h1>Get Your Bike Ready for the Road!</h1><br>
      <button class="book-now" ><a href="#detail" style="text-decoration: none; color: white;">Book Now!</a></button>
    </section>
    
    <section class="services">
  <h2>Our Services</h2><hr>
  <div class="service-container">
    <div class="service">
      <img src="https://promechanic.co.in/wp-content/uploads/2023/02/settings.png" alt="Choose Service" class="service-image">
      <h3>General Service</h3>
    </div>
    <div class="service">
      <img src="https://promechanic.co.in/wp-content/uploads/2023/02/piston.png" alt="Schedule" class="service-image">
      <h3>Engine Works</h3>
    </div>
    <div class="service">
      <img src="https://promechanic.co.in/wp-content/uploads/2023/02/spray.png" alt="Confirm" class="service-image">
      <h3>Foam Wash</h3>
    </div>
    <div class="service">
      <img src="https://promechanic.co.in/wp-content/uploads/2023/02/speed.png" alt="Ride Again" class="service-image">
      <h3>Mileage Tuning</h3>
    </div>
    <div class="service">
      <img src="https://promechanic.co.in/wp-content/uploads/2023/02/lubricant.png" alt="Ride Again" class="service-image">
      <h3>Oil Change</h3>
    </div>
    <div class="service">
      <img src="https://promechanic.co.in/wp-content/uploads/2023/02/spark-plug.png" alt="Ride Again" class="service-image">
      <h3>Electrical Check</h3>
    </div>
  </div>
</section>

    

<section class="booking-form" id="detail"><br>
            <h2>Book Your Service</h2><hr>
            <div class="booking-container">
                <div class="booking-form-left">
                    <form id="booking-form" method="post">
                        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) { ?>
                            <input type="hidden" id="name" name="name" value="<?php echo $_SESSION['name']; ?>">
                            <input type="hidden" id="mobile" name="mobile" value="<?php echo $_SESSION['mobile'] ?? ''; ?>">
                            <input type="hidden" id="email" name="email" value="<?php echo $_SESSION['email']; ?>">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Vehicle Number :</label>
                                    <input type="text" id="vehicle_number" name="vehicle_number" class="input-container" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Problem / issue</label>
                                    <textarea class="input-container-big inputs" name="complaint" placeholder="Write a detailed issue about Your bike"></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Choose Service Date :</label>
                                    <input type="datetime-local" id="service_date" name="service_date" class="input-container-date" required>
                                    <span id="availability-status"></span>
                                </div>
                            </div><br>
                            <div class="btn-container">
                                <input type="submit" class="btn btn-items" value="Book Service!">
                            </div>
                        <?php } else { ?>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Name :</label>
                                    <input type="text" id="name" name="name" class="input-container login-required" required>
                                </div>
                                <div class="form-group">
                                    <label>Mobile :</label>
                                    <input type="tel" id="mobile" name="mobile" class="input-container login-required" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>E-mail :</label>
                                    <input type="email" id="email" name="email" class="input-container login-required" required>
                                </div>
                                <div class="form-group">
                                    <label>Vehicle Number :</label>
                                    <input type="text" id="vehicle_number" name="vehicle_number" class="input-container login-required" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Problem / issue</label>
                                    <textarea class="input-container-big inputs login-required" name="complaint" placeholder="Write a detailed issue about Your bike"></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Choose Service Date :</label>
                                    <input type="datetime-local" id="service_date" name="service_date" class="input-container-date login-required" required>
                                    <span id="availability-status"></span>
                                </div>
                            </div><br>
                            <div class="btn-container">
                                <input type="submit" class="btn btn-items login-required" value="Book Service!">
                            </div>
                        <?php } ?>
                    </form>
                    <div id="booking-status"></div>
                </div>
                <div class="booking-form-right">
                    <h3>Services</h3>
                    <div class="services-container">
                        <ul class="services-list">
                            <li>General Service</li>
                            <li>Oil Change</li>
                            <li>Water Wash</li>
                            <li>Clutch Works</li>
                            <li>Engine Repair</li>
                            <li>Accident Repair</li>
                            <li>Tyre Replacement</li>
                            <li>Insurance Renewal</li>
                            <li>Brake Shoes Replacement</li>
                            <li>Fork Adjustment and Repair</li>
                            <li>Chain Sprocket Replacement</li>
                            <li>Self Motor Not Working and Repair</li>
                            <li>Battery Charging and Replacement</li>
                            <li>Starting Issue, Carburetor Cleaning</li>
                            <li>Headlight Bulb Replacement and Repair</li>
                            <li>Tank Cleaning (required for vehicle kept idle)</li>
                        </ul>
                    </div>
                 </div>
        </div>
    </section>


<section class="about" id="about">
  <h2>About Us</h2><hr>
  <p>We are a team of passionate bike enthusiasts dedicated to providing top-notch bike repair services. With years of experience in the industry, our expert mechanics use state-of-the-art equipment to ensure your bike is running smoothly and efficiently.</p>
  <p>Our mission is to provide exceptional customer service, quality repairs, and maintenance to keep you riding safely and confidently. We strive to build long-lasting relationships with our customers and become your trusted bike repair partner.</p>
  <div class="about-team">
    <h3>Meet Our Team</h3>
    <div class="team-member">
      <h4>Deepan</h4>
      <p>Lead Mechanic</p>
    </div>
   
  </div>
</section>


<section class="contact" id="contact">
  <h2>Get in Touch</h2><hr>
  <p>Have a question or need assistance? We're here to help!</p>
  <div class="contact-info">
    <p>Address: Bike Service Centre, Coimbatore</p>
    <p>Phone: 9360648801</p>
    <p>Email: <a href="mailto:deepann2004@gmail.com">deepann2004@gmail.com</a></p>
  </div>
  <div class="contact-form">
    <form action="send-email.php" method="post">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Your Name" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="your@email.com" required>
      </div>
      <div class="form-group">
        <label for="message">Message:</label>
        <textarea id="message" name="message" placeholder="Your Message" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
  </div>
</section>


<footer>
<div class="contact-info">
      <p>Address: Bike Service Centre, Coimbatore </p>
      <p>Phone: 9360648801</p>
      <p>Email:  deepann2004@gmail.com</p>
    </div>
    <div class="social-media">
  <a href="#" target="_blank" class="facebook"><i class="fa-brands fa-facebook"></i></a>
  <a href="#" target="_blank" class="twitter"><i class="fa-brands fa-twitter"></i></a>
  <a href="#" target="_blank" class="instagram"><i class="fa-brands fa-instagram"></i></a>
</footer>
<script src="script.js">
</script>
<script>
    document.querySelector('.book-now a').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('detail').scrollIntoView({ behavior:'smooth' });
    });

    // Add event listener to login-required inputs
    document.querySelectorAll('.login-required').forEach(element => {
        element.addEventListener('click', function() {
            // Check if user is logged in
            if (!<?php echo isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ? 'true' : 'false'; ?>) {
                // Display alert message
                if (confirm("Please login to book a service. Do you want to login now?")) {
                    // Redirect to login page
                    window.location.href = 'user_login.html';
                }
            }
        });
    });
</script>

</body>
</html>







