<?php
// index.php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        header("Location: profile.php");
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background: linear-gradient(135deg, #a8c0ff 0%, #3f2b96 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #ff9a9e;
            box-shadow: 0 0 0 0.25rem rgba(255, 154, 158, 0.25);
        }
        .btn-primary {
            border-radius: 12px;
            padding: 12px;
            background: #ff9a9e;
            border: none;
            transition: transform 0.2s ease;
        }
        .btn-primary:hover {
            background: #ff758c;
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
        <div class="card login-card shadow-lg w-100" style="max-width: 400px;">
            <h2 class="text-center mb-4 fw-bold text-white"><i class="bi bi-paw-fill"></i> Login</h2>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success border-0 rounded-3"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger border-0 rounded-3"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" onsubmit="return validateForm()">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-3"><i class="bi bi-person-fill text-muted"></i></span>
                        <input type="text" name="username" id="username" placeholder="Username" class="form-control border-start-0 ps-0">
                    </div>
                </div>
                
                <div class="mb-4 position-relative">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-3"><i class="bi bi-lock-fill text-muted"></i></span>
                        <input type="password" name="password" id="password" placeholder="Password" class="form-control border-start-0 ps-0">
                        <button type="button" onclick="togglePassword()" class="btn btn-outline-secondary border-start-0 rounded-end-3 bg-white" id="toggleBtn"><i class="bi bi-eye-fill"></i></button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">Login <i class="bi bi-arrow-right"></i></button>
                <p class="mt-4 text-center text-white">Don't have an account? <a href="register.php" class="text-white fw-bold">Register</a></p>
            </form>
        </div>
    </div>

    <script>
        function validateForm() {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (username === "") {
                alert("Username is required.");
                return false;
            }
            if (password === "") {
                alert("Password is required.");
                return false;
            }
            return true;
        }
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
