<?php
/**
 * PHP Form Validation Lab - Optimized Edition
 * Features: Server-side validation, Sanitization, Sticky Fields, and Premium UI.
 */

// 1. Initialize variables and error messages
$name = $email = $gender = $website = $phone = "";
$nameErr = $emailErr = $genderErr = $websiteErr = $phoneErr = $passwordErr = $confirmErr = $termsErr = "";
$attempts = 0;
$valid = false;
$successMsg = "";

/**
 * Helper function to sanitize user input
 * Removes whitespace, backslashes, and converts special chars to HTML entities.
 */
function test_input($data) {
    if ($data === null) return "";
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// 2. Process Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Exercise 5: Increment submission counter via hidden field
    $attempts = isset($_POST["attempts"]) ? intval($_POST["attempts"]) + 1 : 1;

    // Validate Name (Required)
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
    }

    // Validate Email (Required + Format Check)
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Exercise 1: Validate Phone Number (Required + Regex)
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        // Regex: Digits, spaces, dashes allowed, optional leading +
        if (!preg_match("/^[+]?[0-9 \-]{7,15}$/", $phone)) {
            $phoneErr = "Invalid phone format";
        }
    }

    // Exercise 2: Validate Website (Optional but validated if typed)
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format";
        }
    }

    // Validate Gender (Required)
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }

    // Exercise 3: Validate Password & Confirmation
    $passValue = $_POST["password"] ?? "";
    $confValue = $_POST["confirm"] ?? "";

    if (empty($passValue)) {
        $passwordErr = "Password is required";
    } elseif (strlen($passValue) < 8) {
        $passwordErr = "Password must be at least 8 characters long";
    }

    if (empty($confValue)) {
        $confirmErr = "Please confirm your password";
    } elseif ($passValue !== $confValue) {
        $confirmErr = "Passwords do not match";
    }

    // Exercise 4: Validate Terms Checkbox (Required)
    if (!isset($_POST["terms"])) {
        $termsErr = "You must agree to the terms and conditions";
    }

    // 3. Check if everything is valid
    $valid = !$nameErr && !$emailErr && !$genderErr && !$websiteErr && !$phoneErr && !$passwordErr && !$confirmErr && !$termsErr;

    if ($valid) {
        $successMsg = "Registration Successful!";
    }
} else {
    // Initial load: start at 0
    $attempts = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Secure PHP Form validation with modern dark-mode aesthetics.">
    <title>PHP Lab: Form Validation</title>
    <!-- Google Fonts: Outfit for a modern, geometric look -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.4);
            --bg: #030712;
            --card: rgba(17, 24, 39, 0.8);
            --text-heading: #f8fafc;
            --text-body: #94a3b8;
            --error: #ef4444;
            --success: #10b981;
            --border: rgba(255, 255, 255, 0.05);
            --input-bg: rgba(2, 6, 23, 0.8);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Outfit', sans-serif; }

        body {
            background-color: var(--bg);
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.1) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 40%);
            color: var(--text-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .container {
            width: 100%;
            max-width: 500px;
            background: var(--card);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 32px;
            padding: 48px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 { 
            color: var(--text-heading);
            font-size: 36px; 
            font-weight: 700; 
            text-align: center; 
            margin-bottom: 4px; 
            letter-spacing: -1px; 
        }

        .counter {
            display: block;
            text-align: center;
            font-size: 13px;
            color: var(--primary);
            margin-bottom: 32px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group { margin-bottom: 24px; position: relative; }

        label { 
            display: block; 
            font-size: 14px; 
            font-weight: 500; 
            margin-bottom: 8px; 
            color: #d1d5db; 
            transition: color 0.3s;
        }

        .required-star { color: var(--error); margin-left: 2px; }

        .form-group:focus-within label { color: var(--primary); }

        input[type="text"], 
        input[type="email"], 
        input[type="password"] {
            width: 100%;
            padding: 14px 18px;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            color: #f8fafc;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-glow);
            transform: scale(1.02);
        }

        .error-msg {
            color: var(--error);
            font-size: 12px;
            font-weight: 500;
            margin-top: 6px;
            display: block;
            animation: shake 0.4s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }

        .radio-group { display: flex; gap: 24px; margin-top: 8px; }
        .radio-label { 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            font-size: 14px; 
            cursor: pointer; 
            color: #94a3b8; 
            transition: color 0.2s;
        }
        .radio-label:hover { color: #f8fafc; }
        .radio-label input { 
            width: 18px;
            height: 18px;
            accent-color: var(--primary); 
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 32px;
            cursor: pointer;
            font-size: 14px;
            color: #cbd5e1;
        }
        .checkbox-row input { 
            width: 20px; 
            height: 20px; 
            accent-color: var(--primary); 
            border-radius: 6px;
            cursor: pointer; 
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 20px -5px var(--primary-glow);
            margin-top: 24px;
        }

        .btn-submit:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 15px 30px -5px var(--primary-glow);
            filter: brightness(1.1);
        }

        .btn-submit:active { transform: translateY(0); }

        .success-banner {
            margin-top: 40px;
            padding: 24px;
            background: rgba(16, 185, 129, 0.05);
            border: 1px solid rgba(16, 185, 129, 0.1);
            border-radius: 20px;
            animation: slideUp 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .success-banner h3 { 
            color: var(--success); 
            font-size: 20px; 
            margin-bottom: 16px; 
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .success-banner h3::before {
            content: "✓";
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background: var(--success);
            color: var(--bg);
            border-radius: 50%;
            font-size: 14px;
        }

        .info-grid {
            display: grid;
            gap: 12px;
            font-size: 14px;
            color: #cbd5e1;
        }
        .info-item { display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .info-label { color: var(--text-body); font-weight: 500; }
        .info-value { color: var(--text-heading); font-weight: 600; }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .container { padding: 32px 24px; }
            h2 { font-size: 28px; }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Join the Community</h2>
    <span class="counter">Submission Attempt: <?php echo $attempts; ?></span>
    <p style="text-align: center; font-size: 12px; margin-bottom: 20px; color: var(--muted);">Fields marked with <span class="required-star">*</span> are required</p>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <!-- Exercise 5: Hidden input to persist submission counter -->
        <input type="hidden" name="attempts" value="<?php echo $attempts; ?>">

        <div class="form-group">
            <label>Full Name<span class="required-star">*</span></label>
            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="e.g. John Wick">
            <span class="error-msg"><?php echo $nameErr; ?></span>
        </div>

        <div class="form-group">
            <label>Email Address<span class="required-star">*</span></label>
            <input type="email" name="email" value="<?php echo $email; ?>" placeholder="e.g. contact@example.com">
            <span class="error-msg"><?php echo $emailErr; ?></span>
        </div>

        <div class="form-group">
            <label>Phone Number<span class="required-star">*</span></label>
            <input type="text" name="phone" value="<?php echo $phone; ?>" placeholder="e.g. +63 912-345-6789">
            <!-- Exercise 1: Phone number error display -->
            <span class="error-msg"><?php echo $phoneErr; ?></span>
        </div>

        <div class="form-group">
            <label>Website (Optional)</label>
            <input type="text" name="website" value="<?php echo $website; ?>" placeholder="https://yourprofile.com">
            <!-- Exercise 2: Website URL error display (sticky value maintained) -->
            <span class="error-msg"><?php echo $websiteErr; ?></span>
        </div>

        <div class="form-group">
            <label>Gender Selection<span class="required-star">*</span></label>
            <div class="radio-group">
                <label class="radio-label"><input type="radio" name="gender" value="male" <?php if($gender=="male") echo "checked";?>> Male</label>
                <label class="radio-label"><input type="radio" name="gender" value="female" <?php if($gender=="female") echo "checked";?>> Female</label>
                <label class="radio-label"><input type="radio" name="gender" value="other" <?php if($gender=="other") echo "checked";?>> Other</label>
            </div>
            <span class="error-msg"><?php echo $genderErr; ?></span>
        </div>

        <div class="form-group">
            <!-- Exercise 3: Password fields -->
            <label>Create Password<span class="required-star">*</span></label>
            <input type="password" name="password" placeholder="Minimum 8 characters">
            <span class="error-msg"><?php echo $passwordErr; ?></span>
        </div>

        <div class="form-group">
            <label>Confirm Password<span class="required-star">*</span></label>
            <input type="password" name="confirm" placeholder="Repeat password to verify">
            <span class="error-msg"><?php echo $confirmErr; ?></span>
        </div>

        <label class="checkbox-row">
            <!-- Exercise 4: Terms checkbox -->
            <input type="checkbox" name="terms" <?php if(isset($_POST["terms"])) echo "checked";?>>
            <span>I agree to the Terms and Conditions<span class="required-star">*</span></span>
        </label>
        <span class="error-msg" style="margin-bottom: 24px;"><?php echo $termsErr; ?></span>

        <button type="submit" class="btn-submit">Complete Registration</button>
    </form>

    <?php if($valid): ?>
    <div class="success-banner">
        <h3><?php echo $successMsg; ?></h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Name</span>
                <span class="info-value"><?php echo $name; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Email</span>
                <span class="info-value"><?php echo $email; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Phone</span>
                <span class="info-value"><?php echo $phone; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Gender</span>
                <span class="info-value"><?php echo ucfirst($gender); ?></span>
            </div>
            <div class="info-item" style="border-bottom: none;">
                <span class="info-label">Website</span>
                <span class="info-value"><?php echo empty($website) ? "Not provided" : $website; ?></span>
            </div>
            <!-- Note: Password is NOT displayed here for security (Exercise 3) -->
        </div>
    </div>
    <?php endif; ?>
</div>

</body>
</html>