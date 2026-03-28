<?php
// register.php
session_start();
require 'db.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['username'])) {
        $error = "Username is required.";
    } elseif (empty($_POST['password'])) {
        $error = "Password is required.";
    } else {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $street = $_POST['street'];
        $suburb = $_POST['suburb'];
        $postcode = $_POST['postcode'];
        $state = $_POST['state'];
        $country = $_POST['country'];

        try {
            $stmt = $db->prepare("INSERT INTO users (username, password, firstname, lastname, gender, dob, street, suburb, postcode, state, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $password, $firstname, $lastname, $gender, $dob, $street, $suburb, $postcode, $state, $country]);
            $_SESSION['success'] = "Registration successful!";
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'UNIQUE constraint failed: users.username') !== false) {
                $error = "Username already taken. Please choose another.";
            } else {
                $error = "Registration failed. Please try again later.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .reg-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .form-control, .form-select {
            border-radius: 12px;
            padding: 10px 15px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 0.25rem rgba(79, 172, 254, 0.25);
        }
        .btn-primary {
            border-radius: 12px;
            padding: 12px;
            background: #4facfe;
            border: none;
            transition: transform 0.2s ease;
        }
        .btn-primary:hover {
            background: #00c6ff;
            transform: translateY(-2px);
        }
        .paw-mark {
            position: absolute;
            pointer-events: none;
            animation: fadeOut 1.5s forwards;
            font-size: 20px;
            z-index: 9999;
            user-select: none;
        }
        @keyframes fadeOut {
            0% { opacity: 0.8; transform: scale(1) translateY(0); }
            100% { opacity: 0; transform: scale(1.5) translateY(-20px); }
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="card reg-card shadow-lg w-100" style="max-width: 500px;">
            <h2 class="text-center mb-4 fw-bold text-white"><i class="bi bi-person-plus-fill"></i> Create Account</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger border-0 rounded-3"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <div id="js-error" class="alert alert-danger border-0 rounded-3 d-none"></div>

            <form method="POST" id="reg-form">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-3"><i class="bi bi-person-fill text-muted"></i></span>
                        <input type="text" name="username" placeholder="Username *" class="form-control border-start-0 ps-0">
                    </div>
                </div>
                
                <div class="mb-3 position-relative">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-3"><i class="bi bi-lock-fill text-muted"></i></span>
                        <input type="password" name="password" id="password" placeholder="Password *" class="form-control border-start-0 ps-0">
                        <button type="button" onclick="togglePassword()" class="btn btn-outline-secondary border-start-0 rounded-end-3 bg-white" id="toggleBtn"><i class="bi bi-eye-fill"></i></button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3"><input type="text" name="firstname" placeholder="First Name" class="form-control"></div>
                    <div class="col-md-6 mb-3"><input type="text" name="lastname" placeholder="Last Name" class="form-control"></div>
                </div>
                
                <select name="gender" class="form-select mb-3">
                    <option value="" disabled selected>Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                
                <div class="mb-3">
                    <label class="form-label text-white ms-1 small">Date of Birth</label>
                    <input type="date" name="dob" class="form-control">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3"><input type="text" name="street" placeholder="Street" class="form-control"></div>
                    <div class="col-md-6 mb-3"><input type="text" name="suburb" placeholder="Suburb" class="form-control"></div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3"><input type="text" name="postcode" placeholder="Postcode" class="form-control"></div>
                    <div class="col-md-4 mb-3"><input type="text" name="state" placeholder="State" class="form-control"></div>
                    <div class="col-md-4 mb-3"><input type="text" name="country" placeholder="Country" class="form-control"></div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">Register <i class="bi bi-check-circle"></i></button>
                <p class="mt-4 text-center text-white">Already have an account? <a href="index.php" class="text-white fw-bold">Login here</a></p>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function togglePassword() {
            const p = document.getElementById('password');
            const btn = document.getElementById('toggleBtn');
            if (p.type === 'password') {
                p.type = 'text';
                btn.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';
            } else {
                p.type = 'password';
                btn.innerHTML = '<i class="bi bi-eye-fill"></i>';
            }
        }

        $('#reg-form').on('submit', function(e) {
            const username = $('input[name="username"]').val();
            const password = $('input[name="password"]').val();
            
            if (!username) {
                e.preventDefault();
                alert("Username is required.");
            } else if (!password) {
                e.preventDefault();
                alert("Password is required.");
            }
        });

        document.addEventListener('click', (e) => {
            if (e.target.closest('button') || e.target.closest('a')) return;
            const mark = document.createElement('div');
            mark.className = 'paw-mark';
            mark.innerText = '🐾';
            mark.style.left = e.clientX + 'px';
            mark.style.top = e.clientY + 'px';
            document.body.appendChild(mark);
            setTimeout(() => mark.remove(), 1500);
        });
    </script>
</body>
</html>
