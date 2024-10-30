<?php
// Disable error display for users and log errors instead
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL); // Ensure all errors are reported in the logs

// Start the session if it isn't already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db_connection.php';
include 'header.php';
include 'sidebar.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$page_title = "About Us Page";
?>

<style>
    /* Reset & General Styling */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Arial', sans-serif;
        display: flex;
        height: 100vh;
        overflow: hidden;
    }

    /* Sidebar */
    #sidebar {
        width: 250px;
        background-color: #2A2A2A;
        flex-shrink: 0;
    }

    /* Content Section */
    #content {
        flex-grow: 1;
        margin-left: 250px; /* Align left margin with sidebar's right margin */
        margin-top: 25; /* Remove margin to connect with header */
        background: linear-gradient(135deg, #8e44ad, #e4b7ff); /* Updated gradient background */
        padding: 60px 40px; /* Space inside the content */
        min-height: calc(100vh - 60px); /* Adjust height for the header */
        overflow-y: auto;
        color: #fff;
    }

    h1 {
        font-size: 3rem;
        margin-bottom: 20px;
        font-weight: bold;
        text-transform: uppercase;
        background: linear-gradient(to right, #E0BBE4, #957DAD, #D291BC);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    h2 {
        margin-top: 20px;
        font-size: 2.2rem;
        color: #F0E4F7;
    }

    p {
        margin-bottom: 15px;
        line-height: 1.7;
        font-size: 1.1rem;
    }

    .info-section {
        background-color: rgba(255, 255, 255, 0.15);
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    ul {
        margin: 10px 0;
        padding-left: 20px;
        list-style-type: square;
    }

    .cta-button {
        display: inline-block;
        margin-top: 20px;
        padding: 15px 30px;
        background-color: #957DAD;
        color: #fff;
        font-size: 1rem;
        font-weight: bold;
        text-transform: uppercase;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        text-decoration: none;
    }

    .cta-button:hover {
        background-color: #B565A7;
    }
</style>

<!-- Content Section -->
<div id="content">
    <h1>About Us</h1>

    <div class="info-section">
        <h2>Who We Are</h2>
        <p>TrashTech is a pioneering platform blending technology with waste management to make recycling more efficient and sustainable. We automate waste segregation and use IoT solutions to transform waste into a valuable resource, empowering communities to adopt eco-friendly practices.</p>
    </div>

    <div class="info-section">
        <h2>Our Mission</h2>
        <p>We aim to revolutionize waste management by providing innovative, sustainable solutions. Our goal is to reduce environmental pollution by integrating automated segregation tools that enhance recycling rates and minimize landfill waste.</p>
    </div>

    <div class="info-section">
        <h2>Our Vision</h2>
        <p>Our vision is to become a global leader in sustainable waste management, creating systems that turn waste into resources and foster a circular economy. We aspire to build a future where materials are reused, ensuring a cleaner, greener world for future generations.</p>
    </div>

    <div class="info-section">
        <h2>Our Approach</h2>
        <p>Using a combination of **automation, IoT, and data analytics**, we enhance the segregation and tracking of recyclable materials. Our platform assists waste facilities, LGUs, and businesses by providing real-time monitoring and actionable insights, improving operational efficiency.</p>
        <p>With automated segregators, we ensure precise sorting of metals, plastics, and glass, reducing contamination and making recycling efforts more effective. Our tools enable communities to engage in sustainable practices effortlessly.</p>
    </div>

    <div class="info-section">
        <h2>Our Core Values</h2>
        <ul>
            <li><strong>Innovation:</strong> Leveraging advanced technologies to tackle environmental challenges.</li>
            <li><strong>Sustainability:</strong> Designing solutions that promote long-term environmental health.</li>
            <li><strong>Collaboration:</strong> Partnering with communities, businesses, and governments for collective success.</li>
            <li><strong>Transparency:</strong> Ensuring data accessibility for all stakeholders to make informed decisions.</li>
        </ul>
    </div>

    <div class="info-section">
        <h2>Our Impact</h2>
        <p>TrashTech has already enabled communities to optimize waste management processes, reduce landfill contributions, and increase recycling rates. By empowering local government units and businesses with automated tools, we ensure faster, smarter, and more sustainable waste management.</p>
        <p>Our platform provides real-time data and insights that allow users to track waste trends, improve recycling efforts, and align with sustainability goals.</p>
    </div>

    <div class="info-section">
        <h2>Looking to the Future</h2>
        <p>At TrashTech, innovation is at the heart of everything we do. We are continuously improving our platform to meet the evolving needs of communities and businesses. Our long-term goal is to expand globally, bringing sustainable waste management practices to cities worldwide.</p>
    </div>

    <a href="contact.php" class="cta-button">Contact Us</a>
</div>
