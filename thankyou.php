<?php
// Set timezone to Los Angeles
date_default_timezone_set('America/Los_Angeles');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - RSVP Confirmation</title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes:wght@400&family=Playfair+Display:wght@400;500;600;700&family=Crimson+Text:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Crimson Text', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: rgba(255, 255, 255, 0.95);
        }

        .thankyou-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            padding: 60px 40px;
            text-align: center;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .logo-symbol {
            width: 200px;
            height: 100px;
            margin: 0 auto 0px;
            display: flex;
            align-items: center;
            justify-content: center;
           
        }

        .logo-symbol img {
            width: 100%;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        .thankyou-title {
            font-family: 'Great Vibes', cursive;
            font-size: 3.5rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 20px;
            letter-spacing: 2px;
            line-height: 1.2;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .thankyou-message {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 30px;
            line-height: 1.4;
        }

        .sub-message {
            font-family: 'Crimson Text', serif;
            font-size: 1.2rem;
            font-style: italic;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .leave-message {
            font-family: 'Crimson Text', serif;
            font-size: 1.1rem;
            font-style: italic;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 30px;
            line-height: 1.5;
            text-align: center;
        }


        .button-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }


        .back-button {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 15px 30px;
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            color: rgba(255, 255, 255, 0.95);
        }

        .timestamp {
            font-family: 'Crimson Text', serif;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 30px;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .thankyou-container {
                padding: 40px 25px;
                margin: 10px;
            }

            .thankyou-title {
                font-size: 2.8rem;
            }

            .thankyou-message {
                font-size: 1.5rem;
            }

            .sub-message {
                font-size: 1.1rem;
            }

            .button-container {
                justify-content: center;
            }

            .back-button {
                font-size: 1rem;
                padding: 14px 28px;
                width: 100%;
                max-width: 300px;
            }

        }
    </style>
</head>
<body>
    <div class="thankyou-container">
        <div class="logo-symbol">
            <img src="XptID15-1C_white.png" alt="Crosspoint Church Logo">
        </div>
        
        <h1 class="thankyou-title">Thank You!</h1>
        
        <div class="thankyou-message">
            Your RSVP has been successfully recorded.
        </div>
        
        <div class="sub-message">
            We're excited to celebrate with you at our special events!
        </div>
        
        <div class="leave-message">
            You can leave this page now.
        </div>
        
        <div class="button-container">
            <a href="index.php" class="back-button">Submit Another RSVP</a>
        </div>
        
        <div class="timestamp">
            Confirmation received on <?php echo date('F j, Y \a\t g:i A T'); ?>
        </div>
    </div>
</body>
</html>
