<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli("localhost", "root", "", "chatbot");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Save full name, assuming you have first_name and last_name columns
    $sql = "INSERT INTO users (username, password, role, first_name, last_name) 
            VALUES ( '$username', '$password', 'student', '$first_name', '$last_name')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Registration failed: " . $conn->error;
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - Chatbot System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 p-8 text-white text-center">
            <div class="bg-white rounded-full w-20 h-20 mx-auto flex items-center justify-center mb-4">
                <i class="fas fa-user-graduate text-4xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent"></i>
            </div>
            <h2 class="text-3xl font-bold">Student Registration</h2>
            <p class="text-blue-100 mt-2">Create your account</p>
        </div>

        <!-- Form -->
        <div class="p-8">
            <?php if (isset($error)): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span><?php echo $error; ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-user text-green-500 mr-1"></i>First Name
                        </label>
                        <input type="text" name="first_name" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                               placeholder="First name">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Last Name
                        </label>
                        <input type="text" name="last_name" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                               placeholder="Last name">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-id-card text-purple-500 mr-2"></i>Username
                    </label>
                    <input type="text" name="username" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                           placeholder="Choose a username">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-lock text-purple-500 mr-2"></i>Password
                    </label>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                           placeholder="Create a password">
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white py-3 rounded-lg font-semibold hover:from-blue-600 hover:via-purple-600 hover:to-pink-600 transform hover:scale-105 transition duration-200 shadow-lg mt-6">
                    <i class="fas fa-user-plus mr-2"></i>Register
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">or</span>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-gray-600">Already have an account?</p>
                <a href="index.php" class="inline-block mt-2 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent font-semibold hover:from-blue-700 hover:to-purple-700 transition duration-200">
                    <i class="fas fa-sign-in-alt mr-1"></i>Login here
                </a>
            </div>
        </div>
    </div>
</body>
</html>
