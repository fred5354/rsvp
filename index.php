<?php
// Handle form submission
if ($_POST) {
    // Set timezone to Los Angeles
    date_default_timezone_set('America/Los_Angeles');
    $timestamp = date('Y-m-d H:i:s');
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $event_a = isset($_POST['event_a']) ? ($_POST['event_a'] === 'yes' ? 'Yes' : 'No') : 'No';
    $event_a_guests = isset($_POST['event_a_guests']) ? (int)$_POST['event_a_guests'] : 1;
    $banquet = isset($_POST['banquet']) ? ($_POST['banquet'] === 'yes' ? 'Yes' : 'No') : 'No';
    $banquet_guests = isset($_POST['banquet_guests']) ? (int)$_POST['banquet_guests'] : 1;
    
    // Collect banquet guest names
    $banquet_guest_names = [];
    for ($i = 1; $i <= $banquet_guests; $i++) {
        $guest_name = isset($_POST["banquet_guest_$i"]) ? trim($_POST["banquet_guest_$i"]) : '';
        if (!empty($guest_name)) {
            $banquet_guest_names[] = $guest_name;
        }
    }
    $banquet_guest_names_str = implode(', ', $banquet_guest_names);
    
    // Validate required fields
    if (!empty($name) && !empty($email)) {
        // Validate banquet guest names if attending
        if ($banquet === 'Yes' && $banquet_guests > 0) {
            $missing_names = [];
            for ($i = 1; $i <= $banquet_guests; $i++) {
                $guest_name = isset($_POST["banquet_guest_$i"]) ? trim($_POST["banquet_guest_$i"]) : '';
                if (empty($guest_name)) {
                    $missing_names[] = $i;
                }
            }
            if (!empty($missing_names)) {
                $errorMessage = "Please enter names for all banquet guests. Missing names for guest(s): " . implode(', ', $missing_names);
            }
        }
        
        // Only proceed with saving if no validation errors
        if (!isset($errorMessage)) {
            // Prepare CSV data
            $csvData = [
                $timestamp,
                $name,
                $email,
                $event_a,
                $event_a_guests,
                $banquet,
                $banquet_guests,
                $banquet_guest_names_str
            ];
            
            // Write to CSV file
            $csvFile = 'rsvp_data.csv';
            $fileExists = file_exists($csvFile);
            
            $file = fopen($csvFile, 'a');
            if ($file) {
                // Add header if file is new
                if (!$fileExists) {
                    fputcsv($file, ['Timestamp', 'Name', 'Email', 'Event A', 'Event A Guests', 'Banquet', 'Banquet Guests', 'Banquet Guest Names']);
                }
                fputcsv($file, $csvData);
                fclose($file);
                
                // Redirect to thank you page
                header('Location: thankyou.php');
                exit();
            } else {
                $errorMessage = "Sorry, there was an error saving your response. Please try again.";
            }
        }
    } else {
        $errorMessage = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crosspoint 25th Anniversary Celebration</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@300;400;500;600;700&family=Crimson+Text:ital,wght@0,400;0,600;1,400&family=Dancing+Script:wght@400;500;600;700&family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Playfair Display', 'Georgia', 'Times New Roman', serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(138, 99, 255, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 70% 50%, rgba(99, 179, 255, 0.15) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(30px, -30px) rotate(5deg); }
        }
        
        .container {
            max-width: 700px;
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(40px) saturate(180%);
            -webkit-backdrop-filter: blur(40px) saturate(180%);
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4),
                        inset 0 1px 0 rgba(255, 255, 255, 0.1);
            overflow: hidden;
            position: relative;
            z-index: 1;
        }
        
        .header {
            background: linear-gradient(135deg, 
                rgba(138, 99, 255, 0.3) 0%, 
                rgba(99, 179, 255, 0.3) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            color: white;
            padding: 60px 40px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 0%, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .header h1 {
            font-family: 'Great Vibes', cursive;
            font-size: 3.5em;
            margin-bottom: 12px;
            font-weight: 400;
            letter-spacing: 2px;
            text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            line-height: 1.2;
        }
        
        .header .subtitle {
            font-family: 'Crimson Text', serif;
            font-size: 1.4em;
            opacity: 0.9;
            font-weight: 400;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
            font-style: italic;
        }
        
        .content {
            padding: 50px 40px;
        }
        
        .greeting {
            font-family: 'Crimson Text', serif;
            font-size: 1.5em;
            margin-bottom: 35px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.8;
            font-weight: 400;
            font-style: italic;
        }
        
        .event-section {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 24px;
            padding: 28px;
            margin: 25px 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        /* Safari fallback for backdrop-filter */
        @supports not (backdrop-filter: blur(20px)) {
            .event-section {
                background: rgba(255, 255, 255, 0.2) !important;
            }
        }
        
        /* Safari-specific fix - more aggressive */
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            .event-section {
                background: rgba(255, 255, 255, 0.18) !important;
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
            }
        }
        
        /* Additional Safari targeting */
        @media screen and (-webkit-min-device-pixel-ratio: 0) and (max-width: 9999px) {
            .event-section {
                background: rgba(255, 255, 255, 0.2) !important;
            }
        }
        
        .event-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(138, 99, 255, 0.05) 0%, rgba(99, 179, 255, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.4s;
            pointer-events: none;
        }
        
        .event-section:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .event-section:hover::before {
            opacity: 1;
        }
        
        .event-section h2 {
            font-family: 'Playfair Display', serif;
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.4em;
            margin-bottom: 18px;
            font-weight: 500;
            letter-spacing: 0.5px;
            font-style: italic;
        }
        
        .event-details {
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.8;
            font-size: 1.1em;
        }
        
        .event-details strong {
            color: rgba(255, 255, 255, 0.95);
            display: inline-block;
            min-width: 70px;
            font-weight: 600;
        }

        .form-section {
            margin-top: 40px;
        }
        
        .form-group {
            margin-bottom: 28px;
        }
        
        .form-group label {
            font-family: 'Crimson Text', serif;
            display: block;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 1.4em;
            letter-spacing: 0.3px;
        }
        
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="number"],
        .form-group select {
            width: 100%;
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            font-size: 1.4em;
            font-family: 'Crimson Text', serif;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: rgba(255, 255, 255, 0.95);
        }
        
        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        
        /* Safari fallback for main form inputs */
        @supports not (backdrop-filter: blur(10px)) {
            .form-group input[type="text"],
            .form-group input[type="email"],
            .form-group input[type="number"],
            .form-group select {
                background: rgba(255, 255, 255, 0.2) !important;
            }
        }
        
        /* Safari-specific fix for main form inputs - more aggressive */
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            .form-group input[type="text"],
            .form-group input[type="email"],
            .form-group input[type="number"],
            .form-group select {
                background: rgba(255, 255, 255, 0.18) !important;
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
            }
        }
        
        /* Additional Safari targeting for form inputs */
        @media screen and (-webkit-min-device-pixel-ratio: 0) and (max-width: 9999px) {
            .form-group input[type="text"],
            .form-group input[type="email"],
            .form-group input[type="number"],
            .form-group select {
                background: rgba(255, 255, 255, 0.2) !important;
            }
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(138, 99, 255, 0.6);
            box-shadow: 0 0 0 4px rgba(138, 99, 255, 0.15),
                        0 8px 24px rgba(0, 0, 0, 0.2);
            transform: translateY(-1px);
        }
        
        .radio-group {
            display: flex;
            gap: 16px;
            margin-top: 12px;
        }
        
        .radio-group label {
            font-family: 'Crimson Text', serif;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            cursor: pointer;
            padding: 16px 24px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 1.4em;
            margin-bottom: 0;
        }
        
        .radio-group label:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }
        
        .radio-group input[type="radio"] {
            margin-right: 10px;
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #8a63ff;
        }
        
        .radio-group input[type="radio"]:checked + span {
            color: #8a63ff;
        }
        
        .radio-group label:has(input:checked) {
            background: rgba(138, 99, 255, 0.2);
            border-color: rgba(138, 99, 255, 0.5);
            box-shadow: 0 0 0 3px rgba(138, 99, 255, 0.1);
        }
        
        .radio-group label:has(input:checked) span {
            color: white !important;
        }
        
        /* Safari fallback for radio button groups */
        @supports not (backdrop-filter: blur(10px)) {
            .radio-group label {
                background: rgba(255, 255, 255, 0.15) !important;
            }
            .radio-group label:hover {
                background: rgba(255, 255, 255, 0.2) !important;
            }
            .radio-group label:has(input:checked) {
                background: rgba(138, 99, 255, 0.3) !important;
            }
        }
        
        /* Safari-specific fix for radio button groups */
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            .radio-group label {
                background: rgba(255, 255, 255, 0.12) !important;
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
            }
            .radio-group label:hover {
                background: rgba(255, 255, 255, 0.18) !important;
            }
            .radio-group label:has(input:checked) {
                background: rgba(138, 99, 255, 0.25) !important;
            }
        }

        .guests-input {
            margin-left: 30px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .guests-section {
            display: none;
            margin-top: 15px;
        }
        
        .guests-section.show {
            display: block;
        }

        .guests-input input[type="number"] {
            width: 90px;
            padding: 12px;
            text-align: center;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', sans-serif;
            color: rgba(255, 255, 255, 0.95);
            -moz-appearance: textfield; /* Firefox */
        }
        
        /* Safari fallback for number inputs */
        @supports not (backdrop-filter: blur(10px)) {
            .guests-input input[type="number"] {
                background: rgba(255, 255, 255, 0.2) !important;
            }
        }
        
        /* Safari-specific fix for number inputs - more aggressive */
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            .guests-input input[type="number"] {
                background: rgba(255, 255, 255, 0.18) !important;
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
            }
        }
        
        /* Additional Safari targeting for number inputs */
        @media screen and (-webkit-min-device-pixel-ratio: 0) and (max-width: 9999px) {
            .guests-input input[type="number"] {
                background: rgba(255, 255, 255, 0.2) !important;
            }
        }
        
        /* Direct Safari targeting - most aggressive */
        @media not all and (min-resolution:.001dpcm) {
            @supports (-webkit-appearance:none) {
                .event-section,
                .form-group input[type="text"],
                .form-group input[type="email"],
                .form-group input[type="number"],
                .form-group select,
                .guest-name-input input[type="text"],
                .guests-input input[type="number"],
                .radio-group label {
                    background: rgba(255, 255, 255, 0.25) !important;
                    backdrop-filter: none !important;
                    -webkit-backdrop-filter: none !important;
                }
                .radio-group label:hover {
                    background: rgba(255, 255, 255, 0.3) !important;
                }
                .radio-group label:has(input:checked) {
                    background: rgba(138, 99, 255, 0.3) !important;
                }
                .submit-btn {
                    background: linear-gradient(135deg, rgba(138, 99, 255, 0.95) 0%, rgba(99, 179, 255, 0.95) 100%) !important;
                    backdrop-filter: none !important;
                    -webkit-backdrop-filter: none !important;
                }
                .submit-btn:disabled {
                    background: rgba(255, 255, 255, 0.1) !important;
                }
            }
        }

        .guests-input input[type="number"]::-webkit-outer-spin-button,
        .guests-input input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .guests-input input[type="number"]:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(138, 99, 255, 0.6);
            box-shadow: 0 0 0 4px rgba(138, 99, 255, 0.15);
        }

        .guest-name-input {
            margin-bottom: 12px;
        }

        .guest-name-input input[type="text"] {
            width: 100%;
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            font-size: 1em;
            font-family: 'Crimson Text', serif;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: rgba(255, 255, 255, 0.95);
        }

        .guest-name-input input[type="text"]::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .guest-name-input input[type="text"]:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(138, 99, 255, 0.6);
            box-shadow: 0 0 0 4px rgba(138, 99, 255, 0.15),
                        0 8px 24px rgba(0, 0, 0, 0.2);
            transform: translateY(-1px);
        }
        
        /* Safari fallback for text inputs */
        @supports not (backdrop-filter: blur(10px)) {
            .guest-name-input input[type="text"] {
                background: rgba(255, 255, 255, 0.2) !important;
            }
        }
        
        /* Safari-specific fix for text inputs - more aggressive */
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            .guest-name-input input[type="text"] {
                background: rgba(255, 255, 255, 0.18) !important;
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
            }
        }
        
        /* Additional Safari targeting for guest name inputs */
        @media screen and (-webkit-min-device-pixel-ratio: 0) and (max-width: 9999px) {
            .guest-name-input input[type="text"] {
                background: rgba(255, 255, 255, 0.2) !important;
            }
        }

        .number-controls {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .number-btn {
            width: 45px;
            height: 45px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(138, 99, 255, 0.8) 0%, rgba(99, 179, 255, 0.8) 100%);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: white;
            font-size: 22px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .number-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(138, 99, 255, 0.4);
            background: linear-gradient(135deg, rgba(138, 99, 255, 0.9) 0%, rgba(99, 179, 255, 0.9) 100%);
        }

        .number-btn:active {
            transform: scale(0.95);
        }

        .number-btn:disabled {
            background: rgba(255, 255, 255, 0.1);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
            border-color: rgba(255, 255, 255, 0.1);
        }

        .submit-btn {
            background: linear-gradient(135deg, rgba(138, 99, 255, 0.9) 0%, rgba(99, 179, 255, 0.9) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 18px 40px;
            font-size: 1.3em;
            border-radius: 20px;
            cursor: pointer;
            width: 100%;
            font-family: 'Playfair Display', serif;
            letter-spacing: 0.5px;
            font-weight: 500;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 30px;
            box-shadow: 0 10px 30px rgba(138, 99, 255, 0.3);
            position: relative;
            overflow: hidden;
            font-style: italic;
        }
        
        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 50px rgba(138, 99, 255, 0.4);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .submit-btn:hover::before {
            left: 100%;
        }
        
        .submit-btn:active {
            transform: translateY(-1px);
        }

        .submit-btn:disabled {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.4);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .submit-btn:disabled::before {
            display: none;
        }
        
        /* Safari fallback for submit button */
        @supports not (backdrop-filter: blur(20px)) {
            .submit-btn {
                background: linear-gradient(135deg, rgba(138, 99, 255, 0.95) 0%, rgba(99, 179, 255, 0.95) 100%) !important;
            }
            .submit-btn:disabled {
                background: rgba(255, 255, 255, 0.1) !important;
            }
        }
        
        /* Safari-specific fix for submit button */
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            .submit-btn {
                background: linear-gradient(135deg, rgba(138, 99, 255, 0.95) 0%, rgba(99, 179, 255, 0.95) 100%) !important;
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
            }
            .submit-btn:disabled {
                background: rgba(255, 255, 255, 0.08) !important;
            }
        }

        .message {
            padding: 16px 20px;
            border-radius: 16px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 1em;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .error {
            background: rgba(239, 68, 68, 0.2);
            color: rgba(239, 68, 68, 0.9);
            border-color: rgba(239, 68, 68, 0.3);
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(to right, 
                transparent, 
                rgba(255, 255, 255, 0.2), 
                transparent);
            margin: 35px 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: -1px;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, 
                transparent, 
                rgba(138, 99, 255, 0.3), 
                transparent);
        }
        
        .note {
            font-family: 'Crimson Text', serif;
            font-style: italic;
            color: rgba(255, 255, 255, 0.6);
            font-size: 1em;
            margin-top: 30px;
            text-align: center;
            font-weight: 400;
            letter-spacing: 0.3px;
        }
        
        .logo-container {
            margin-top: 40px;
            text-align: center;
            padding: 20px 0;
        }
        
        .logo {
            display: inline-flex;
            align-items: center;
  
        }
        
        .logo:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .logo-symbol {
            width: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-symbol img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: brightness(0) invert(1);
            opacity: 0.9;
        }
        
        .logo-text {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 1.2em;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 0.5px;
        }
        
        @media (max-width: 600px) {
            .header h1 {
                font-size: 2.8em;
                letter-spacing: 1px;
            }
            
            .header .subtitle {
                font-size: 1.2em;
            }
            
            .content {
                padding: 35px 25px;
            }
            
            .radio-group {
                flex-direction: column;
                gap: 12px;
            }
            
            .container {
                border-radius: 30px;
            }
            
            .greeting {
                font-size: 1.2em;
            }
            
            .logo {
                
            }
            
            .logo-symbol {
                width: 200px;
                
            }
            
            .logo-text {
                font-size: 1.1em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>You're Invited</h1>
            <div class="subtitle">Crosspoint Church 25th Anniversary Celebration</div>
        </div>
        
        <div class="content">
            <div class="greeting">
                We joyfully invite you to join us as we celebrate 25 years of God's faithfulness at Crosspoint. This milestone celebration will include a special worship service and pastor ordination ceremony, followed by a banquet of fellowship and thanksgiving.
            </div>
            
            <div class="event-section">
                <h2>Worship Service & Pastor Ordination</h2>
                <div class="event-details">
                    <div><strong>Date:</strong> November 9, 2025</div>
                    <div><strong>Time:</strong> 3:00 PM</div>
                    <div><strong>Location:</strong> 658 Gibraltar Ct., Milpitas, CA 95035</div>
                </div>
            </div>
            
            <div class="event-section">
                <h2>Celebration Banquet</h2>
                <div class="event-details">
                    <div><strong>Time:</strong> 6:30 PM</div>
                    <div><strong>Location:</strong> HL Peninsula Restaurant</div>
                    <div style="margin-left: 70px;">136 Ranch Dr, Milpitas, CA 95035</div>
                </div>
            </div>
            
            <div class="divider"></div>
            
            <?php if (isset($errorMessage)): ?>
                <div class="message error"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            
            <form class="form-section" method="POST" action="" onsubmit="return validateGuestNames()">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="your.email@example.com" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Will you attend the Worship Service?</label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="event_a" value="yes" <?php echo (isset($_POST['event_a']) && $_POST['event_a'] === 'yes') ? 'checked' : ''; ?>>
                            <span>Yes, I'll be there</span>
                        </label>
                        <label>
                            <input type="radio" name="event_a" value="no" <?php echo (isset($_POST['event_a']) && $_POST['event_a'] === 'no') ? 'checked' : ''; ?>>
                            <span>Unable to attend</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-group guests-section" id="event_a_guests_section">
                    <label for="event_a_guests">Number of additional guests for Worship Service</label>
                    <div class="guests-input">
                        <div class="number-controls">
                            <button type="button" class="number-btn" id="event_a_minus" onclick="decreaseGuests('event_a_guests')">−</button>
                            <input type="number" id="event_a_guests" name="event_a_guests" min="0" max="20" value="<?php echo isset($_POST['event_a_guests']) ? htmlspecialchars($_POST['event_a_guests']) : '0'; ?>" placeholder="0">
                            <button type="button" class="number-btn" id="event_a_plus" onclick="increaseGuests('event_a_guests')">+</button>
                        </div>
                    </div>
                </div>
                
                <div class="divider"></div>
                
                <div class="form-group">
                    <label>Will you attend the Banquet?</label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="banquet" value="yes" <?php echo (isset($_POST['banquet']) && $_POST['banquet'] === 'yes') ? 'checked' : ''; ?>>
                            <span>Yes, I'll be there</span>
                        </label>
                        <label>
                            <input type="radio" name="banquet" value="no" <?php echo (isset($_POST['banquet']) && $_POST['banquet'] === 'no') ? 'checked' : ''; ?>>
                            <span>Unable to attend</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-group guests-section" id="banquet_guests_section">
                    <label for="banquet_guests">Number of additional guests for Banquet</label>
                    <div class="guests-input">
                        <div class="number-controls">
                            <button type="button" class="number-btn" id="banquet_minus" onclick="decreaseGuests('banquet_guests')">−</button>
                            <input type="number" id="banquet_guests" name="banquet_guests" min="0" max="20" value="<?php echo isset($_POST['banquet_guests']) ? htmlspecialchars($_POST['banquet_guests']) : '0'; ?>" placeholder="0">
                            <button type="button" class="number-btn" id="banquet_plus" onclick="increaseGuests('banquet_guests')">+</button>
                        </div>
                    </div>
                </div>
                
                <div class="form-group guests-section" id="banquet-guests-names">
                    <label>Guest Names for Banquet</label>
                    <div id="banquet-guest-names-container">
                        <!-- Dynamic guest name inputs will be added here -->
                    </div>
                </div>
                
                <button type="submit" class="submit-btn" id="submitBtn" disabled>Submit RSVP</button>
            </form>
            
            <div class="note">
                We look forward to celebrating this special milestone with you!
            </div>
            
            <div class="logo-container">
                <div class="logo">
                    <div class="logo-symbol">
                        <img src="XptID15-1C_white.png" alt="Crosspoint Church Logo">
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <script>
        // Plus/minus button functions
        function increaseGuests(inputId) {
            const input = document.getElementById(inputId);
            const currentValue = parseInt(input.value) || 0;
            input.value = currentValue + 1;
            updateButtonStates(inputId);
        }

        function decreaseGuests(inputId) {
            const input = document.getElementById(inputId);
            const currentValue = parseInt(input.value) || 0;
            
            // Both Event A and Banquet allow going down to 0
            if (currentValue > 0) {
                input.value = currentValue - 1;
            }
            updateButtonStates(inputId);
        }

        function updateButtonStates(inputId) {
            const input = document.getElementById(inputId);
            const currentValue = parseInt(input.value) || 0;
            const minusBtn = document.getElementById(inputId.replace('_guests', '_minus'));
            const plusBtn = document.getElementById(inputId.replace('_guests', '_plus'));
            
            // Both Event A and Banquet allow 0 as minimum
            minusBtn.disabled = currentValue < 0;
            plusBtn.disabled = currentValue >= 99; // Set a reasonable max limit
            
            // Update guest names for banquet
            if (inputId === 'banquet_guests') {
                updateBanquetGuestNames(currentValue);
            }
        }

        function updateBanquetGuestNames(guestCount) {
            const container = document.getElementById('banquet-guest-names-container');
            const section = document.getElementById('banquet-guests-names');
            
            // Store existing values before clearing
            const existingValues = {};
            const existingInputs = container.querySelectorAll('input[type="text"]');
            existingInputs.forEach(input => {
                const name = input.name;
                const value = input.value;
                if (value.trim() !== '') {
                    existingValues[name] = value;
                }
            });
            
            // Clear existing inputs
            container.innerHTML = '';
            
            if (guestCount > 0) {
                section.style.display = 'block';
                
                for (let i = 1; i <= guestCount; i++) {
                    const div = document.createElement('div');
                    div.className = 'guest-name-input';
                    const inputName = `banquet_guest_${i}`;
                    const existingValue = existingValues[inputName] || '';
                    div.innerHTML = `<input type="text" name="${inputName}" placeholder="Name of Guest ${i}" value="${existingValue}" required>`;
                    container.appendChild(div);
                }
            } else {
                section.style.display = 'none';
            }
        }

        function checkFormValidity() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const eventARadio = document.querySelector('input[name="event_a"]:checked');
            const banquetRadio = document.querySelector('input[name="banquet"]:checked');
            const submitBtn = document.getElementById('submitBtn');
            
            // Check if all required fields are filled
            const isFormValid = name !== '' && 
                               email !== '' && 
                               eventARadio !== null && 
                               banquetRadio !== null;
            
            // Enable/disable submit button
            submitBtn.disabled = !isFormValid;
        }

        function validateGuestNames() {
            // Validate Event A guests (allow 0)
            const eventARadio = document.querySelector('input[name="event_a"]:checked');
            if (eventARadio && eventARadio.value === 'yes') {
                const eventAGuestCount = parseInt(document.getElementById('event_a_guests').value) || 0;
                if (eventAGuestCount < 0) {
                    alert('Please enter a valid number of additional guests for Worship Service (0 or more).');
                    return false;
                }
            }
            
            // Validate Banquet guests and names
            const banquetRadio = document.querySelector('input[name="banquet"]:checked');
            if (banquetRadio && banquetRadio.value === 'yes') {
                const banquetGuestCount = parseInt(document.getElementById('banquet_guests').value) || 0;
                if (banquetGuestCount < 0) {
                    alert('Please enter a valid number of additional guests for Banquet (0 or more).');
                    return false;
                }
                
                // Only validate guest names if there are guests
                if (banquetGuestCount > 0) {
                    const missingNames = [];
                    for (let i = 1; i <= banquetGuestCount; i++) {
                        const guestInput = document.querySelector(`input[name="banquet_guest_${i}"]`);
                        if (guestInput && guestInput.value.trim() === '') {
                            missingNames.push(i);
                        }
                    }
                    
                    if (missingNames.length > 0) {
                        alert(`Please enter names for all banquet guests. Missing names for guest(s): ${missingNames.join(', ')}`);
                        return false;
                    }
                }
            }
            return true;
        }

        // Update guest counts when radio buttons are toggled
        document.querySelectorAll('input[name="event_a"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const guestsInput = document.getElementById('event_a_guests');
                const guestsSection = document.getElementById('event_a_guests_section');
                
                if (this.value === 'yes') {
                    guestsInput.value = guestsInput.value || '0';
                    guestsSection.classList.add('show');
                } else {
                    guestsInput.value = '0';
                    guestsSection.classList.remove('show');
                }
                updateButtonStates('event_a_guests');
            });
        });

        document.querySelectorAll('input[name="banquet"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const guestsInput = document.getElementById('banquet_guests');
                const guestsSection = document.getElementById('banquet_guests_section');
                const namesSection = document.getElementById('banquet-guests-names');
                
                if (this.value === 'yes') {
                    guestsInput.value = guestsInput.value || '0';
                    guestsSection.classList.add('show');
                    namesSection.classList.add('show');
                } else {
                    guestsInput.value = '0';
                    guestsSection.classList.remove('show');
                    namesSection.classList.remove('show');
                }
                updateButtonStates('banquet_guests');
            });
        });

        // Initialize guest counts based on radio button states
        document.addEventListener('DOMContentLoaded', function() {
            const eventARadio = document.querySelector('input[name="event_a"]:checked');
            const banquetRadio = document.querySelector('input[name="banquet"]:checked');
            
            // Handle Event A section
            if (eventARadio && eventARadio.value === 'yes') {
                document.getElementById('event_a_guests_section').classList.add('show');
            } else {
                document.getElementById('event_a_guests').value = '0';
            }
            
            // Handle Banquet section
            if (banquetRadio && banquetRadio.value === 'yes') {
                document.getElementById('banquet_guests_section').classList.add('show');
                document.getElementById('banquet-guests-names').classList.add('show');
            } else {
                document.getElementById('banquet_guests').value = '0';
            }
            
            // Initialize button states
            updateButtonStates('event_a_guests');
            updateButtonStates('banquet_guests');
            
            // Initialize banquet guest names if attending
            const banquetGuestCount = parseInt(document.getElementById('banquet_guests').value) || 0;
            if (banquetRadio && banquetRadio.value === 'yes' && banquetGuestCount > 0) {
                updateBanquetGuestNames(banquetGuestCount);
            }
            
            // Initialize form validity check
            checkFormValidity();
        });

        // Add event listeners for form validation
        document.getElementById('name').addEventListener('input', checkFormValidity);
        document.getElementById('email').addEventListener('input', checkFormValidity);
        
        // Add event listeners for radio buttons
        document.querySelectorAll('input[name="event_a"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                checkFormValidity();
            });
        });
        
        document.querySelectorAll('input[name="banquet"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                checkFormValidity();
            });
        });
    </script>
</body>
</html>