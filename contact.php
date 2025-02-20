<?php
require 'nav.php'; // รวมเมนูนำทาง
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดต่อเรา</title>
    <link rel="stylesheet" href="styles.css"> <!-- ใช้ไฟล์ CSS ของคุณ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* สไตล์สำหรับ .container */
        .container {
            font-family: Arial, sans-serif;
            background-color:rgba(54, 54, 54, 1);
            padding: 20px;
            margin: 0 auto;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color:rgb(255, 255, 255);
        }

        h3 {
            color:rgb(255, 255, 255);
        }

        p text{
            font-size: 16px;
            color:rgb(54, 86, 212);
        }

        a {
            color:rgb(54, 86, 212);
            text-decoration: none;
            text-align: center;
        }

        a:hover {
            text-decoration: underline;
        }

        .fa-brands {
            font-size: 24px;
            margin-right: 10px;
        }

        /* สไตล์สำหรับลิงค์โซเชียล */
        .social-links a {
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>ติดต่อเรา</h2>
        <p class="text">คุณสามารถติดต่อเราผ่านช่องทางต่อไปนี้:</p>
        
        <h3>โทรศัพท์</h3>
        <p class="text">095-162-4238</p>
        
        <h3>อีเมล</h3>
        <p class="text"><a href="mailto:s66309010009@kktech.ac.th">s66309010009@kktech.ac.th</a></p>
        <p class="text"><a href="mailto:s66309010016@kktech.ac.th">s66309010016@kktech.ac.th</a></p>
        <p class="text"><a href="mailto:s66309010021@kktech.ac.th">s66309010021@kktech.ac.th</a></p>
        
        <h3>โซเชียลมีเดีย</h3>
        <p class="text">
            <a href="https://www.facebook.com/phongtanes.yopratoom"><i class="fa-brands fa-facebook"></i></a>
            <a href="https://x.com/whyfoidontknow?s=11"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="https://www.instagram.com/bbfxmxst/"><i class="fa-brands fa-instagram"></i></a>
        </p>
    </div>
    <footer><?php include 'footer.php';?></footer>
</body>
</html>
