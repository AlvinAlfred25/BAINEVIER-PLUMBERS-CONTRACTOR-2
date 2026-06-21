<?php
session_start();

// Anyone not logged in gets sent to the login page.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$userName = htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Account | Bainevier Plumbers &amp; Contractors</title>
  <link rel="icon" type="image/svg+xml" href="Images/favicon.svg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>

  <header class="main-header">
    <a href="index.html" class="logo-area">
      <img src="Images/Logo-2.jpg" alt="Bainevier Plumbers & Contractors logo" width="216" height="55">
    </a>
    <h1>Bainevier Plumbers & Contractors</h1>
    <button class="nav-toggle" aria-label="Toggle navigation menu" aria-expanded="false">&#9776;</button>
    <nav class="main-nav" aria-label="Primary">
      <a href="index.html">Home</a>
      <a href="about_us.html">About Us</a>
      <a href="services.html">Services</a>
      <a href="contact_us.html">Contact Us</a>
      <a href="logout.php" class="nav-login">Logout</a>
    </nav>
  </header>

  <main id="main">
    <div class="page-banner">
      <span class="eyebrow">My Account</span>
      <h1>Welcome, <?php echo $userName; ?>!</h1>
      <p>You're logged in to your Bainevier Plumbers & Contractors account.</p>
    </div>

    <section class="section">
      <div class="container">
        <div class="welcome-card reveal">
          <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
          <h2>You're all set, <?php echo $userName; ?></h2>
          <p>Your account is ready. From here you can request a free quote or browse our services.</p>
          <div class="welcome-actions">
            <a href="contact_us.html" class="btn btn-primarys"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Request a Free Quote</a>
            <a href="services.html" class="btn btn-ghost" style="border-color: var(--line); color: var(--navy);">View Our Services</a>
          </div>
          <p style="margin-top:24px;"><a href="logout.php" style="color: var(--blue); font-weight:600; text-decoration:none;">Log out</a></p>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="footer-grid">
      <div>
        <img src="Images/Logo-2.jpg" alt="Bainevier Plumbers & Contractors logo" width="120" height="46">
        <p>Professional plumbing consultancy, system design, and tile fixing for residential, commercial, and industrial projects.</p>
      </div>
      <div>
        <h4>Quick Links</h4>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="about_us.html">About Us</a></li>
          <li><a href="services.html">Services</a></li>
          <li><a href="contact_us.html">Contact Us</a></li>
        </ul>
      </div>
      <div>
        <h4>Get in Touch</h4>
        <ul>
          <li><a href="tel:+256702714729"><i class="fa-solid fa-phone" aria-hidden="true"></i> 0702 714 729</a></li>
          <li><a href="tel:+256771180443"><i class="fa-solid fa-phone" aria-hidden="true"></i> 0771 180 443</a></li>
          <li><a href="https://wa.me/256702714729" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Chat on WhatsApp</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Bainevier Plumbers &amp; Contractors. All rights reserved.</p>
    </div>
  </footer>

  <a href="https://wa.me/256702714729" class="whatsapp-btn" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i><span>Chat on WhatsApp</span>
  </a>

  <script src="script.js"></script>
</body>
</html>
