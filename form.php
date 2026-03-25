<?php
/**PHP Form Validation Lab*/
// Initialize state and error variables
$name = $email = $gender = $website = $phone = "";
$nameErr = $emailErr = $genderErr = $websiteErr = $phoneErr = $passwordErr = $confirmErr = $termsErr = "";
$attempts = 0;
$valid = false;
$successMsg = "";

// Security: Sanitize input to prevent XSS
function test_input($data) {
    if ($data === null) return "";
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Increment submission counter
    $attempts = isset($_POST["attempts"]) ? intval($_POST["attempts"]) + 1 : 1;

    // Name Validation
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
    }

    // Email Validation
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Phone Validation (Regex)
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match("/^[+]?[0-9 \-]{7,15}$/", $phone)) {
            $phoneErr = "Invalid phone format";
        }
    }

    // Optional Website Validation
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format";
        }
    }

    // Gender Validation
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }

    // Password & Match Validation
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

    // Terms Agreement Validation
    if (!isset($_POST["terms"])) {
        $termsErr = "You must agree to the terms and conditions";
    }

    // Check if form is error-free
    $valid = !$nameErr && !$emailErr && !$genderErr && !$websiteErr && !$phoneErr && !$passwordErr && !$confirmErr && !$termsErr;

    if ($valid) {
        $successMsg = "Form submitted successfully!";
    }
} else {
    $attempts = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Lab: Form Validation</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@github/monaspace@1.0.1/release/variable.css">

    <style>
        :root {
            --primary: #818cf8;
            --primary-hover: #6366f1;
            --bg: #0f172a;
            --card: rgba(30, 41, 59, 0.7);
            --text: #ffffff;
            --muted: #94a3b8;
            --error: #ff0026ff;
            --success: #00ff9d;
            --border: rgba(148, 163, 184, 0.1);
        }

        /* Use Neon for general text */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Monaspace Neon', monospace; }

        body {
            background-color: var(--bg);
            background-image: radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.1) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(139, 92, 246, 0.1) 0px, transparent 50%);
            color: var(--text);
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
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 40px;
        }

        /* Use Xenon for headers */
        h2 { font-family: 'Monaspace Xenon', monospace; font-size: 32px; font-weight: 700; text-align: center; margin-bottom: 8px; }

        /* Use Krypton for tech data */
        .counter {
            display: block;
            text-align: center;
            font-family: 'Monaspace Krypton', monospace;
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 30px;
        }

        .form-group { margin-bottom: 22px; position: relative; }

        /* Use Argon for readability */
        label { display: block; font-family: 'Monaspace Argon', monospace; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #cbd5e1; }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: white;
            font-size: 15px;
            outline: none;
        }

        /* Krypton for technical errors */
        .error-msg {
            color: var(--error);
            font-family: 'Monaspace Krypton', monospace;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .radio-group { display: flex; gap: 20px; }
        .radio-label { display: flex; align-items: center; gap: 8px; font-size: 14px; color: #94a3b8; }
        .radio-label input { accent-color: var(--primary); }

        .checkbox-row { display: flex; align-items: center; gap: 10px; margin-top: 25px; font-size: 14px; }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-family: 'Monaspace Xenon', monospace;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
        }

        /* Radon for success branding */
        .success-banner {
            margin-top: 30px;
            padding: 20px;
            background: rgba(52, 211, 153, 0.1);
            border-radius: 16px;
        }
        .success-banner h3 { font-family: 'Monaspace Radon', monospace; color: var(--success); font-size: 18px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Registration</h2>
    <span class="counter">Submission Attempt: <?php echo $attempts; ?></span>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="attempts" value="<?php echo $attempts; ?>">

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" value="<?php echo $name; ?>">
            <span class="error-msg"><?php echo $nameErr; ?></span>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" value="<?php echo $email; ?>">
            <span class="error-msg"><?php echo $emailErr; ?></span>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" value="<?php echo $phone; ?>">
            <span class="error-msg"><?php echo $phoneErr; ?></span>
        </div>

        <div class="form-group">
            <label>Website (Optional)</label>
            <input type="text" name="website" value="<?php echo $website; ?>">
            <span class="error-msg"><?php echo $websiteErr; ?></span>
        </div>

        <div class="form-group">
            <label>Gender</label>
            <div class="radio-group">
                <label class="radio-label"><input type="radio" name="gender" value="male" <?php if($gender=="male") echo "checked";?>> Male</label>
                <label class="radio-label"><input type="radio" name="gender" value="female" <?php if($gender=="female") echo "checked";?>> Female</label>
                <label class="radio-label"><input type="radio" name="gender" value="other" <?php if($gender=="other") echo "checked";?>> Other</label>
            </div>
            <span class="error-msg"><?php echo $genderErr; ?></span>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password">
            <span class="error-msg"><?php echo $passwordErr; ?></span>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm">
            <span class="error-msg"><?php echo $confirmErr; ?></span>
        </div>

        <label class="checkbox-row">
            <input type="checkbox" name="terms" <?php if(isset($_POST["terms"])) echo "checked";?>>
            <span>I agree to the Terms and Conditions</span>
        </label>
        <span class="error-msg"><?php echo $termsErr; ?></span>

        <button type="submit" class="btn-submit">Register Now</button>
    </form>

    <?php if($valid): ?>
    <div class="success-banner">
        <h3><?php echo $successMsg; ?></h3>
        <p>
            <strong>Name:</strong> <?php echo $name; ?><br>
            <strong>Email:</strong> <?php echo $email; ?><br>
            <strong>Phone:</strong> <?php echo $phone; ?><br>
            <strong>Gender:</strong> <?php echo $gender; ?><br>
            <strong>Website:</strong> <?php echo empty($website) ? "N/A" : $website; ?>
        </p>
    </div>
    <?php endif; ?>
</div>

</body>
</html>
