</html>
<!DOCTYPE html>
<html lang="vi">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Đại Học Cần Thơ</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
        body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #f8f9fa;
                font-family: Arial, sans-serif;
        }

        .login-form {
                text-align: center;
                padding: 30px;
                border: 1px solid #ced4da;
                border-radius: 5px;
                background-color: white;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .login-button {
                background-color: #007bff;
                color: white;
                transition: background-color 0.3s;
        }

        .login-button:hover {
                background-color: #0056b3;
        }

        h1,
        h3 {
                font-weight: bold;
        }

        input {
                border-radius: 5px;
                border: 1px solid #ced4da;
        }

        input:focus {
                border-color: #007bff;
                box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .toggle-password {
                cursor: pointer;
        }
        </style>
</head>

<body>
        <div class="login-form">
                <img src="font-end/img/ctu1.png" alt="Logo" class="mb-4" style="height: 80px;">
                <h3>HỆ THỐNG SẮP LỊCH </h3>
                <h3>BẢO VỆ LUẬN VĂN</h3>
                <form onsubmit="return handleLogin(event);">
                        <div class="form-group">
                                <input type="email" id="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                                <div class="input-group">
                                        <input type="password" id="password" class="form-control" placeholder="Password"
                                                required>
                                        <div class="input-group-append">
                                                <span class="input-group-text toggle-password"
                                                        onclick="togglePassword();">
                                                        <i id="toggleIcon" class="fas fa-eye"></i>
                                                </span>
                                        </div>
                                </div>
                        </div>
                        <button type="submit" class="btn login-button">Đăng Nhập</button>
                </form>
                <div id="message" class="mt-3"></div>
        </div>

        <script>
        function handleLogin(event) {
                event.preventDefault(); // Ngăn không cho form gửi đi  

                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;

                // Kiểm tra thông tin đăng nhập  
                if (email && password) {
                        document.getElementById('message').innerText = "Đăng nhập thành công!";
                        document.getElementById('message').style.color = "green";
                } else {
                        document.getElementById('message').innerText = "Vui lòng điền đầy đủ thông tin!";
                        document.getElementById('message').style.color = "red";
                }
        }

        function togglePassword() {
                const passwordInput = document.getElementById('password');
                const toggleIcon = document.getElementById('toggleIcon');

                if (passwordInput.type === "password") {
                        passwordInput.type = "text";
                        toggleIcon.classList.remove("fa-eye");
                        toggleIcon.classList.add("fa-eye-slash");
                } else {
                        passwordInput.type = "password";
                        toggleIcon.classList.remove("fa-eye-slash");
                        toggleIcon.classList.add("fa-eye");
                }
        }
        </script>
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>