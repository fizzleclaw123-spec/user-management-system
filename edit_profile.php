<?php
// edit_profile.php
session_start();
require 'db.php';
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        $stmt = $db->prepare("UPDATE users SET firstname = ?, lastname = ?, gender = ?, dob = ?, street = ?, suburb = ?, postcode = ?, state = ?, country = ? WHERE username = ?");
        $stmt->execute([$firstname, $lastname, $gender, $dob, $street, $suburb, $postcode, $state, $country, $_SESSION['username']]);
        $message = "Profile updated successfully!";
    } catch (Exception $e) {
        $error = "Update failed: " . $e->getMessage();
    }
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
    <title>Edit Profile</title>
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
            padding: 20px;
        }
        .edit-card {
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
            padding: 12px 15px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
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
        <div class="card edit-card shadow-lg w-100" style="max-width: 500px;">
            <h2 class="text-center mb-4 fw-bold text-white"><i class="bi bi-pencil-square"></i> Edit Profile</h2>
            
            <?php if ($error): ?><div class="alert alert-danger border-0 rounded-3"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
            <?php if ($message): ?><div class="alert alert-success border-0 rounded-3"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3"><input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required class="form-control" placeholder="First Name"></div>
                    <div class="col-md-6 mb-3"><input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required class="form-control" placeholder="Last Name"></div>
                </div>
                
                <select name="gender" class="form-select mb-3">
                    <option value="male" <?php if($user['gender'] == 'male') echo 'selected'; ?>>Male</option>
                    <option value="female" <?php if($user['gender'] == 'female') echo 'selected'; ?>>Female</option>
                    <option value="other" <?php if($user['gender'] == 'other') echo 'selected'; ?>>Other</option>
                </select>
                
                <label class="form-label text-white ms-1 small">Date of Birth</label>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required class="form-control mb-3">

                <div class="row">
                    <div class="col-md-6 mb-3"><input type="text" name="street" value="<?php echo htmlspecialchars($user['street']); ?>" placeholder="Street" class="form-control"></div>
                    <div class="col-md-6 mb-3"><input type="text" name="suburb" value="<?php echo htmlspecialchars($user['suburb']); ?>" placeholder="Suburb" class="form-control"></div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3"><input type="text" name="postcode" value="<?php echo htmlspecialchars($user['postcode']); ?>" placeholder="Postcode" class="form-control"></div>
                    <div class="col-md-4 mb-3"><input type="text" name="state" value="<?php echo htmlspecialchars($user['state']); ?>" placeholder="State" class="form-control"></div>
                    <div class="col-md-4 mb-3"><input type="text" name="country" value="<?php echo htmlspecialchars($user['country']); ?>" placeholder="Country" class="form-control"></div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">Save Changes <i class="bi bi-save"></i></button>
                <a href="profile.php" class="btn btn-outline-light w-100 mt-2">Cancel</a>
            </form>
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