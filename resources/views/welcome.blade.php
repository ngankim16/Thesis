<!DOCTYPE html>
<html lang="vi">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Đại Học Cần Thơ</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <style>
        body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-image: url('font-end/img/bachground.jpg');
                background-size: cover;
                background-position: center;
                font-family: Arial, sans-serif;
        }

        .login-form {
                text-align: center;
                padding: 40px;
                border: 1px solid #ced4da;
                border-radius: 10px;
                background-color: rgba(255, 255, 255, 0.9);
                /* Làm mờ nền trắng */
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 400px;
        }

        .login-button {
                background-color: #007bff;
                color: white;
                transition: background-color 0.3s;
                width: 100%;
        }

        .login-button:hover {
                background-color: #0056b3;
        }

        h4 {
                font-weight: bold;
                margin-bottom: 20px;
                font-size: 1.25rem;
                color: #333;
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

        .input-group {
                margin-bottom: 15px;
        }

        .alert {
                display: none;
        }
        </style>
</head>

<body>
        <div class="login-form">
                <img src="font-end/img/h1.png" alt="Logo" class="mb-4" style="height: 80px;">
                <h4>HỆ THỐNG SẮP LỊCH BẢO VỆ LUẬN VĂN</h4>

                <form action="{{ URL::to('/login_dashboard') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                                <input type="email" id="email" class="form-control" name="email_gv" placeholder="Email"
                                        required>
                        </div>
                        <div class="form-group">
                                <div class="input-group">
                                        <input type="password" id="password" name="matkhau_gv" class="form-control"
                                                placeholder="Password" required>
                                        <div class="input-group-append">
                                                <span class="input-group-text toggle-password"
                                                        onclick="togglePassword();">
                                                        <i id="toggleIcon" class="fas fa-eye"></i>
                                                </span>
                                        </div>
                                </div>
                        </div>
                        <button type="submit" name="submit" class="btn login-button">Đăng Nhập</button>
                </form>

                <div id="message" class="mt-3">
                        @if(Session::has('error'))
                        <div class="alert alert-danger" role="alert">
                                {{ Session::get('error') }}
                        </div>
                        @endif
                </div>
        </div>

        <script>
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

        setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                        alert.style.transition = 'opacity 0.5s';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                }
        }, 3000); // Thông báo biến mất sau 3 giây
        </script>

        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>