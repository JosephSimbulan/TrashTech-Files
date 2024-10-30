<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrashTech</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(270deg, #5e0d7a, #8b28a3, #b54cc7, #ffffff, #f4f4f4);
            background-size: 400% 400%; 
            animation: gradientAnimation 15s ease infinite; 
            color: #333;
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 40px;
            background: rgba(118, 11, 154, 0.8); /* Semi-transparent background */
            color: #ffffff; /* Changed to bright white */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .header .logo {
            display: flex;
            align-items: center;
            font-size: 28px;
            font-weight: 700;
        }

        .logo-image {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .header .login-button {
            font-size: 18px;
            text-decoration: none;
            color: #ffffff; /* Changed to bright white */
            background-color: #333;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .header .login-button:hover {
            background-color: #555;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px 40px;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .left-section, .right-section {
            width: 50%;
            padding: 20px;
            box-sizing: border-box;
        }

        .left-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-right: 40px;
        }

        .left-section h1,
        .left-section h2,
        .left-section p {
            background: linear-gradient(270deg, #ffffff, #5e0d7a);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientAnimation 15s ease infinite;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5); /* Text shadow for contrast */
        }

        .left-section h1 {
            font-size: 48px;
            margin-bottom: 20px;
            color: #ffffff; /* Changed to bright white */
        }

        .left-section h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #ffffff; /* Changed to bright white */
        }

        .left-section p {
            font-size: 18px;
            margin-bottom: 40px;
            color: #ffffff; /* Changed to bright white */
        }

        .left-section .cta-button {
            font-size: 18px;
            text-decoration: none;
            color: white;
            background: linear-gradient(to right, #760b9a, #a832a3);
            padding: 15px 30px;
            border-radius: 5px;
            text-align: center;
            width: 200px;
            transition: transform 0.3s; /* Scale effect on hover */
        }

        .left-section .cta-button:hover {
            transform: scale(1.05); /* Scale effect on hover */
        }

        .right-section {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .right-section img {
            width: 100%; /* Responsive image */
            max-width: 600px; /* Maximum width */
            height: auto; /* Maintain aspect ratio */
            object-fit: cover;
            background-color: #e0e0e0;
        }

        .section {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px; /* Adjusted padding */
            margin: 20px 0; /* Added margin between sections */
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent white */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Soft shadow for depth */
        }

        .section-content {
            width: 50%;
            padding-left: 20px; /* Adjusted padding */
            box-sizing: border-box;
        }

        .section h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333; /* Darker color for headings */
        }

        .section p {
            font-size: 18px;
            margin-bottom: 40px;
            color: #555; /* Slightly lighter text for paragraphs */
        }

        .message-section {
            text-align: center;
            padding: 20px; /* Adjusted padding */
            margin: 20px 0; /* Added margin */
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent white */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .message-section h2 {
            color: #333; /* Darker color for message section heading */
        }

        .message-section p {
            font-size: 18px;
            margin-bottom: 40px;
            color: #555; /* Slightly lighter text for message */
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f4f4f4;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="images/TTechLogo.png" alt="TTech Logo" class="logo-image">
            TrashTech
        </div>
        <a href="login.php" class="login-button">Login</a>
    </div>
    <div class="container">
        <div class="left-section">
            <h1>Welcome to TrashTech</h1>
            <h2>Where your waste is in our hands.</h2>
            <p>Empowering Tomorrow’s Environment Today: Let TrashTech Handle Your Waste.</p>
            <a href="register_company.php" class="cta-button">Register Your Company Here!</a>
        </div>
        <div class="right-section">
            <img src="images/index_2.jpg">
        </div>
    </div>
    <div class="section">
        <div class="section-content">
            <h2>Your Seamless Path to Sustainable Solutions</h2>
            <p>At TrashTech, we're committed to making sustainability effortless for everyone. With our user-friendly platform, even the most complex waste management tasks become simple and straightforward. Join us in paving the way towards a cleaner, greener future, where sustainable solutions are as easy to use as they are impactful. Welcome to TrashTech, where sustainability is simplified for you.</p>
        </div>
        <div class="right-section">
            <img src="images/index_2.png">
        </div>
    </div>
    <div class="section">
        <div class="right-section">
            <img src="images/revol pic 1.png">
        </div>
        <div class="section-content">
            <h2>Revolutionary Waste Sorting</h2>
            <p>TrashTech introduces a futuristic approach to waste management, utilizing state-of-the-art Arduino and IoT technology to streamline sorting processes. Say goodbye to manual sorting hassles and hello to a hassle-free, efficient solution that delights our customers.</p>
        </div>
    </div>
    <div class="section">
        <div class="right-section">
            <img src="images/byte pics 1.png">
        </div>
        <div class="section-content">
            <h2>Environmental Heroism in Every Byte</h2>
            <p>TrashTech isn't just a waste management tool; it's an environmental superhero in disguise. With its innovative technology and cutting-edge approach, TrashTech champions the cause of sustainability. By revolutionizing waste management practices, we're not just cleaning up messes; we're paving the way for a brighter, greener tomorrow. Join us in the adventure of environmental heroism with TrashTech, where every data byte is a step towards a cleaner, greener future.</p>
        </div>
    </div>
    <div class="message-section">
        <h2>Join Us in Making a Difference</h2>
        <p>Become part of our mission to transform waste management. Together, we can make a positive impact on our environment and promote sustainability for generations to come.</p>
    </div>
    <div class="footer">
        &copy; 2024 TrashTech. All rights reserved.
    </div>
</body>
</html>
