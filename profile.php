<?php
// profile.php
session_start();
require 'db.php';
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .profile-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .btn-primary {
            border-radius: 12px;
            padding: 12px;
            background: #764ba2;
            border: none;
            transition: transform 0.2s ease;
        }
        .btn-primary:hover {
            background: #667eea;
            transform: translateY(-2px);
        }
        .btn-danger {
            border-radius: 12px;
            padding: 12px;
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
        <div class="card profile-card shadow-lg w-100" style="max-width: 450px;">
            <h1 class="text-center mb-4 text-white fw-bold"><i class="bi bi-person-circle"></i> Profile</h1>
            
            <ul class="list-group list-group-flush mb-4 bg-transparent">
                <li class="list-group-item d-flex justify-content-between bg-transparent text-white border-white-50">
                    <span class="fw-bold"><i class="bi bi-person-fill"></i> Username</span>
                    <span><?php echo htmlspecialchars($user['username']); ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between bg-transparent text-white border-white-50">
                    <span class="fw-bold"><i class="bi bi-card-text"></i> Name</span>
                    <span><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between bg-transparent text-white border-white-50">
                    <span class="fw-bold"><i class="bi bi-gender-ambiguous"></i> Gender</span>
                    <span><?php echo htmlspecialchars(ucfirst($user['gender'])); ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between bg-transparent text-white border-white-50">
                    <span class="fw-bold"><i class="bi bi-calendar-event"></i> Birth Date</span>
                    <span><?php echo htmlspecialchars(date("d/m/Y", strtotime($user['dob']))); ?></span>
                </li>
                <li class="list-group-item bg-transparent text-white border-0">
                    <span class="fw-bold d-block"><i class="bi bi-geo-alt-fill"></i> Address</span>
                    <span class="small opacity-75">
                        <?php 
                            $addressParts = array_filter([$user['street'], $user['suburb'], $user['state'], $user['postcode'], $user['country']]);
                            echo htmlspecialchars(implode(', ', $addressParts));
                        ?>
                    </span>
                </li>
            </ul>
            
            <div class="d-grid gap-2">
                <a href="edit_profile.php" class="btn btn-primary fw-bold shadow-sm">Edit Profile <i class="bi bi-pencil-square"></i></a>
                <a href="logout.php" class="btn btn-danger fw-bold shadow-sm">Logout <i class="bi bi-box-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <script>
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
