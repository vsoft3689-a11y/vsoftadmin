<?php
session_start(); 
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    header("Location: admin.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Page</title>
    <style>
        .login-box {
            display: flex;
            width: 100%;
            height: 100%;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .login-box h2 {
            margin-top: 30px;
        }

        #loginForm {
            width: 350px;
            height: 250px;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .login-box input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-box button {
            width: 100%;
            padding: 10px;
            background: #06BBCC;
            margin-top: 10px;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-box button:hover {
            background: #06BBCC;
        }
    </style>
</head>

<body>
    <?php include "./adminnavbar.php" ?>

    <div class="login-box">
        <h2>Admin Login</h2>
        <form id="loginForm" method="POST">
            <input type="email" name="email" placeholder="Admin">
            <input type="password" name="password" placeholder="Password">
            <button type="submit">Login</button>
        </form>
    </div>

    <?php include "./footer.php" ?>

    <script>
        const apiUrl = "../controller/UserController.php";

        document.getElementById("loginForm").addEventListener("submit", async function(e) {
            e.preventDefault();

            let form = e.target;
            let email = form.email.value.trim();
            let password = form.password.value.trim();

            try {
                let formData = new FormData(this);
                formData.append("action", "getUser");
                let response = await fetch(apiUrl, {
                    method: "POST",
                    body: formData
                });

                let data = await response.json();
                console.log(data);

                if (!data.success) {
                    alert("Invalid email or password.");
                    return;
                }

                // Check role
                if (data.user.role === "admin") {
                    alert("Welcome Admin!");
                    window.location.href = "admin.php";
                } else {
                    alert("Access denied. You are not an admin.");
                }

            } catch (error) {
                console.error("Error:", error);
                alert("Something went wrong. Please try again.");
            }
        });
    </script>
</body>

</html>