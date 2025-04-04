<!DOCTYPE html>
<html lang="ar">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lumière clinic</title>
  <link rel="icon" type="image/png" href="images/logo2.png">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="css/index.css">
</head>

<body>

<header class="header">
    <div class="logo">
      <img src="images/logogreen.png" alt="Logo">
    </div>
    <div class="header-right">
      <a href="login.php"> <button class="login-btn">Login</button></a>
      <a href="signup.php" class="signup-link">Sign Up</a>
    </div>
  </header>
  <br>
  <div class="slider-container">

    <div class="swiper">
      <div class="swiper-wrapper">
        <!-- Slides -->
        <div class="swiper-slide"><img src="images/slide1.jpeg" alt="Slide 1"></div>
        <div class="swiper-slide"><img src="images/slide2.jpeg" alt="Slide 2"></div>
        <div class="swiper-slide"><img src="images/slide3.jpeg" alt="Slide 2"></div>

      </div>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>

    </div>

  </div>



  <div class="content">

    <h1 class="title">Our Vision</h1>
    <p style="text-align: center; padding: 50px;">Our vision is to be a leading medical and aesthetic clinic that
      transforms lives through innovative healthcare solutions. We aim to set new standards in patient care by combining
      expertise, cutting-edge technology, and a holistic approach to wellness. At Lumière Clinic, we believe in
      empowering individuals by enhancing their natural beauty and promoting overall well-being,
      ensuring that every patient leaves our clinic feeling rejuvenated and confident.</p>


    <h1 class="title">About us</h1>
    <p style="text-align: center; padding: 50px;">At Lumière Clinic, we are dedicated to providing world-class medical
      and aesthetic services tailored to meet the unique needs of our patients. Our team of highly skilled professionals
      utilizes the latest technology and advanced medical techniques to ensure exceptional care and outstanding results.
      We take pride in offering a wide range of services, from general medicine and dermatology to cosmetic
      surgery and wellness treatments, all designed to enhance your health and confidence. At Lumière, your well-being
      is our top priority, and we strive to create a comfortable and personalized experience for every patient.</p>


    <h2 class="title">Our Specialities</h2>

    <div class="specialities-container">
      <!-- General Medicine Clinic -->
      <div class="speciality-box">
        <img src="https://cdn-icons-png.flaticon.com/128/2920/2920177.png" alt="General Medicine">
        <h3>General Medicine Clinic</h3>
        <p>Routine Check-ups, Consultations, Vaccinations, Geriatric Care.</p>
      </div>

      <!-- Dermatology & Aesthetic Clinic -->
      <div class="speciality-box">
        <img src="https://cdn-icons-png.flaticon.com/128/3050/3050444.png" alt="Dermatology">
        <h3>Dermatology & Aesthetic Clinic</h3>
        <p>Skin Treatments, Laser Procedures, and Cosmetic Enhancements.</p>
      </div>

      <!-- Dentistry -->
      <div class="speciality-box">
        <img src="https://cdn-icons-png.flaticon.com/128/3343/3343832.png" alt="Dentistry">
        <h3>Dentistry</h3>
        <p>Comprehensive dental care and hygiene treatments.</p>
      </div>

      <!-- Cosmetic Surgery -->
      <div class="speciality-box">
        <img src="https://cdn-icons-png.flaticon.com/128/1506/1506349.png" alt="Cosmetic Surgery">
        <h3>Cosmetic Surgery</h3>
        <p>Enhancing beauty with advanced surgical techniques.</p>
      </div>

      <!-- Post-Surgical Care -->
      <div class="speciality-box">
        <img src="https://cdn-icons-png.flaticon.com/128/4357/4357428.png" alt="Post-Surgical Care">
        <h3>Post-Surgical Care</h3>
        <p>Recovery support for a smooth healing journey.</p>
      </div>

      <!-- Hair Transplant -->
      <div class="speciality-box">
        <img src="https://cdn-icons-png.flaticon.com/128/822/822141.png" alt="Hair Transplant">
        <h3>Hair Transplant</h3>
        <p>Effective solutions for hair regrowth and restoration.</p>
      </div>

      <!-- Wellness & Rejuvenation -->
      <div class="speciality-box">
        <img src="https://cdn-icons-png.flaticon.com/128/3209/3209014.png" alt="Wellness">
        <h3>Wellness & Rejuvenation</h3>
        <p>Holistic care for mind and body refreshment.</p>
      </div>
    </div>

  </div>

  <footer class="footer">
    <!-- Contact Us-->
    <h2>Contact Us</h2>
    <ul>
      <li>
        <img src="images/facebook.png" alt="facebook" />
        <a href="#">Lumièreclinic</a>
      </li>
      <li>
        <img src="images/social-media.png" alt="x" />
        <a href="#">@LumièreClinic</a>
      </li>
      <li>
        <img src="images/instagram.png" alt="instagram" />
        <a href="#">@Lumière_clinic</a>
      </li>
      <li>
        <img src="images/mail.png" alt="gmail" />
        <a href="#">info@lumièreclinic.com</a>
      </li>
      <li>
        <img src="images/phone.png" alt="phone" />
        <a href="#">+966 501 234 567</a>
      </li>
    </ul>
    <div class="copyright">
      <p>
        © 2025 | All Rights Reserved by <strong>Lumière clinic</strong>
      </p>
    </div>
  </footer>


  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script>
    const swiper = new Swiper('.swiper', {
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      loop: true,

      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },

      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },


    });

  </script>
</body>

</html>