<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindMyProf - Professor Information Assistant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .animate-slideInLeft {
            animation: slideInLeft 0.8s ease-out forwards;
        }
        
        .animate-slideInRight {
            animation: slideInRight 0.8s ease-out forwards;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 min-h-screen">
    
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-lg shadow-md z-50">
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            <div class="flex justify-between items-center h-12 sm:h-14 lg:h-16 xl:h-20">
                <div class="flex items-center gap-1 sm:gap-1.5 lg:gap-3">
                    <img src="Images/Bsit.logo.ico" alt="Logo" class="w-6 h-6 sm:w-7 sm:h-7 lg:w-10 lg:h-10 object-contain flex-shrink-0">
                    <div class="min-w-0">
                        <h1 class="text-xs sm:text-sm lg:text-lg font-bold gradient-text truncate">FindMyProf</h1>
                        <p class="text-[8px] sm:text-[9px] lg:text-xs text-gray-500 hidden sm:block truncate">Professor Info</p>
                    </div>
                </div>
                <div class="flex items-center gap-1 sm:gap-1.5 lg:gap-3">
                    <a href="login.php" class="hidden md:inline-block px-3 lg:px-6 py-1.5 lg:py-2 text-purple-600 hover:text-purple-700 font-semibold transition text-xs lg:text-base">
                        Sign In
                    </a>
                    <a href="register.php" class="hidden sm:inline-flex items-center gap-1 sm:gap-1.5 px-2 sm:px-2.5 lg:px-6 py-1 sm:py-1.5 lg:py-2 text-blue-600 hover:text-blue-700 font-semibold transition border border-blue-600 hover:border-blue-700 rounded-md sm:rounded-lg text-[10px] sm:text-xs lg:text-base">
                        <i class="fas fa-user-plus text-[10px] sm:text-xs"></i>
                        <span class="hidden lg:inline">Sign Up</span>
                    </a>
                    <a href="login.php" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-2 sm:px-3 lg:px-6 py-1 sm:py-1.5 lg:py-2.5 rounded-md sm:rounded-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-xl text-[10px] sm:text-xs lg:text-base whitespace-nowrap">
                        <i class="fas fa-sign-in-alt mr-0.5 sm:mr-1 text-[10px] sm:text-xs"></i>
                        <span>Login</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-16 sm:pt-20 lg:pt-24 xl:pt-32 pb-6 sm:pb-8 lg:pb-12 xl:pb-20 px-2 sm:px-3 lg:px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8 xl:gap-12 items-center">
                <!-- Left Content -->
                <div class="animate-slideInLeft text-center lg:text-left">
                    <div class="inline-block px-2 sm:px-2.5 lg:px-4 py-1 sm:py-1 lg:py-2 bg-purple-100 text-purple-600 rounded-full text-[9px] sm:text-[10px] lg:text-sm font-semibold mb-2 sm:mb-3 lg:mb-6">
                        <i class="fas fa-sparkles mr-0.5 sm:mr-1"></i>AI Assistant
                    </div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl 2xl:text-6xl font-bold text-gray-900 mb-2 sm:mb-3 lg:mb-4 xl:mb-6 leading-tight">
                        Your Smart
                        <span class="gradient-text block">Professor Assistant</span>
                    </h1>
                    <p class="text-xs sm:text-sm lg:text-base xl:text-lg 2xl:text-xl text-gray-600 mb-3 sm:mb-4 lg:mb-6 xl:mb-8 leading-relaxed px-1 sm:px-2 lg:px-0">
                        Discover professor information, schedules, and expertise instantly with our intelligent chatbot.
                    </p>
                    <div class="flex flex-col gap-1.5 sm:gap-2 lg:gap-3 xl:gap-4 px-1 sm:px-0">
                        <a href="login.php" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-4 sm:px-5 lg:px-8 py-2 sm:py-2.5 lg:py-3 xl:py-4 rounded-lg sm:rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl inline-flex items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm lg:text-base">
                            <i class="fas fa-rocket text-xs sm:text-sm lg:text-base"></i>
                            Launch Chatbot
                        </a>
                        <a href="register.php" class="bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white px-4 sm:px-5 lg:px-8 py-2 sm:py-2.5 lg:py-3 xl:py-4 rounded-lg sm:rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl inline-flex items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm lg:text-base">
                            <i class="fas fa-user-plus text-xs sm:text-sm lg:text-base"></i>
                            Sign Up Free
                        </a>
                        <a href="#features" class="bg-white hover:bg-gray-50 text-gray-700 px-4 sm:px-5 lg:px-8 py-2 sm:py-2.5 lg:py-3 xl:py-4 rounded-lg sm:rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl inline-flex items-center justify-center gap-1.5 sm:gap-2 border border-gray-200 text-xs sm:text-sm lg:text-base">
                            <i class="fas fa-info-circle text-xs sm:text-sm lg:text-base"></i>
                            Learn More
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-2 sm:gap-3 lg:gap-6 mt-4 sm:mt-6 lg:mt-12 max-w-md mx-auto lg:mx-0">
                        <div class="text-center lg:text-left bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg p-2 sm:p-3">
                            <div class="text-lg sm:text-xl lg:text-2xl xl:text-3xl font-bold gradient-text">13</div>
                            <div class="text-[9px] sm:text-[10px] lg:text-xs xl:text-sm text-gray-500">Intents</div>
                        </div>
                        <div class="text-center lg:text-left bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-2 sm:p-3">
                            <div class="text-lg sm:text-xl lg:text-2xl xl:text-3xl font-bold gradient-text">85+</div>
                            <div class="text-[9px] sm:text-[10px] lg:text-xs xl:text-sm text-gray-500">Responses</div>
                        </div>
                        <div class="text-center lg:text-left bg-gradient-to-br from-pink-50 to-red-50 rounded-lg p-2 sm:p-3">
                            <div class="text-lg sm:text-xl lg:text-2xl xl:text-3xl font-bold gradient-text">24/7</div>
                            <div class="text-[9px] sm:text-[10px] lg:text-xs xl:text-sm text-gray-500">Available</div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Content - Chatbot Preview -->
                <div class="animate-slideInRight mt-4 sm:mt-6 lg:mt-0">
                    <div class="relative max-w-sm mx-auto lg:max-w-none">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-400 rounded-lg sm:rounded-xl lg:rounded-2xl xl:rounded-3xl blur-lg sm:blur-xl lg:blur-2xl xl:blur-3xl opacity-20 animate-float"></div>
                        <div class="relative bg-white rounded-lg sm:rounded-xl lg:rounded-2xl xl:rounded-3xl shadow-2xl p-2 sm:p-3 lg:p-4 xl:p-6 border border-gray-100">
                            <div class="flex items-center gap-1.5 sm:gap-2 lg:gap-2.5 xl:gap-3 mb-2 sm:mb-3 lg:mb-4 xl:mb-6 pb-1.5 sm:pb-2 lg:pb-3 xl:pb-4 border-b">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 lg:w-10 lg:h-10 xl:w-12 xl:h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-robot text-white text-sm sm:text-base lg:text-lg xl:text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-gray-800 text-[10px] sm:text-xs lg:text-sm xl:text-base truncate">AI Assistant</h3>
                                    <div class="flex items-center gap-0.5 sm:gap-1 text-[9px] sm:text-[10px] lg:text-xs xl:text-sm text-green-500">
                                        <div class="w-1 h-1 sm:w-1.5 sm:h-1.5 lg:w-2 lg:h-2 bg-green-500 rounded-full animate-pulse"></div>
                                        Online
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sample Chat -->
                            <div class="space-y-1.5 sm:space-y-2 lg:space-y-3 xl:space-y-4 mb-2 sm:mb-2.5 lg:mb-3 xl:mb-4 max-h-40 sm:max-h-48 lg:max-h-64 xl:max-h-80 overflow-y-auto">
                                <div class="flex gap-1 sm:gap-1.5 lg:gap-2 xl:gap-3">
                                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg sm:rounded-xl lg:rounded-2xl rounded-tl-none p-1.5 sm:p-2 lg:p-3 xl:p-4 max-w-[85%] lg:max-w-xs">
                                        <p class="text-[10px] sm:text-[11px] lg:text-xs xl:text-sm text-gray-700 leading-tight sm:leading-normal">Hello! How can I help?</p>
                                    </div>
                                </div>
                                <div class="flex gap-1 sm:gap-1.5 lg:gap-2 xl:gap-3 justify-end">
                                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg sm:rounded-xl lg:rounded-2xl rounded-tr-none p-1.5 sm:p-2 lg:p-3 xl:p-4 max-w-[85%] lg:max-w-xs">
                                        <p class="text-[10px] sm:text-[11px] lg:text-xs xl:text-sm leading-tight sm:leading-normal">Who teaches Database?</p>
                                    </div>
                                </div>
                                <div class="flex gap-1 sm:gap-1.5 lg:gap-2 xl:gap-3">
                                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg sm:rounded-xl lg:rounded-2xl rounded-tl-none p-1.5 sm:p-2 lg:p-3 xl:p-4 max-w-[85%] lg:max-w-xs">
                                        <p class="text-[10px] sm:text-[11px] lg:text-xs xl:text-sm text-gray-700 leading-tight sm:leading-normal">Prof. John Smith teaches Database. View schedule?</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Input Preview -->
                            <div class="flex gap-1 sm:gap-1.5 lg:gap-2 items-center bg-gray-100 rounded-md sm:rounded-lg lg:rounded-xl p-1.5 sm:p-2 lg:p-2.5 xl:p-3">
                                <input type="text" placeholder="Type your message..." class="flex-1 bg-transparent outline-none text-sm text-gray-500" disabled>
                                <button class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg p-2 w-10 h-10 flex items-center justify-center">
                                    <i class="fas fa-paper-plane text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-8 sm:py-12 lg:py-16 xl:py-20 px-3 sm:px-4 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-6 sm:mb-10 lg:mb-16 animate-fadeInUp">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2 sm:mb-3 lg:mb-4">Powerful Features</h2>
                <p class="text-sm sm:text-base lg:text-xl text-gray-600 px-4 sm:px-0">Everything you need for seamless academic information access</p>
            </div>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6 xl:gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg sm:rounded-xl lg:rounded-2xl p-4 sm:p-6 lg:p-8 transition-all duration-300 border-2 border-blue-100 hover:border-blue-300 hover:shadow-xl">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg sm:rounded-xl lg:rounded-2xl flex items-center justify-center mb-3 sm:mb-4 lg:mb-6">
                        <i class="fas fa-brain text-white text-base sm:text-xl lg:text-2xl"></i>
                    </div>
                    <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 mb-1.5 sm:mb-2 lg:mb-3">Intelligent AI</h3>
                    <p class="text-xs sm:text-sm lg:text-base text-gray-600">13 emotional intents with sentiment analysis for natural, human-like conversations and context understanding.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg sm:rounded-xl lg:rounded-2xl p-4 sm:p-6 lg:p-8 transition-all duration-300 border-2 border-purple-100 hover:border-purple-300 hover:shadow-xl">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg sm:rounded-xl lg:rounded-2xl flex items-center justify-center mb-3 sm:mb-4 lg:mb-6">
                        <i class="fas fa-chalkboard-teacher text-white text-base sm:text-xl lg:text-2xl"></i>
                    </div>
                    <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 mb-1.5 sm:mb-2 lg:mb-3">Professor Directory</h3>
                    <p class="text-xs sm:text-sm lg:text-base text-gray-600">Complete information about professors including expertise, biography, and academic achievements.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card bg-gradient-to-br from-pink-50 to-red-50 rounded-lg sm:rounded-xl lg:rounded-2xl p-4 sm:p-6 lg:p-8 transition-all duration-300 border-2 border-pink-100 hover:border-pink-300 hover:shadow-xl">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-pink-600 to-red-600 rounded-lg sm:rounded-xl lg:rounded-2xl flex items-center justify-center mb-3 sm:mb-4 lg:mb-6">
                        <i class="fas fa-calendar-alt text-white text-base sm:text-xl lg:text-2xl"></i>
                    </div>
                    <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 mb-1.5 sm:mb-2 lg:mb-3">Schedule Management</h3>
                    <p class="text-xs sm:text-sm lg:text-base text-gray-600">Access professor schedules, class times, rooms, and availability instantly through chat.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card bg-gradient-to-br from-green-50 to-teal-50 rounded-lg sm:rounded-xl lg:rounded-2xl p-4 sm:p-6 lg:p-8 transition-all duration-300 border-2 border-green-100 hover:border-green-300 hover:shadow-xl">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-green-600 to-teal-600 rounded-lg sm:rounded-xl lg:rounded-2xl flex items-center justify-center mb-3 sm:mb-4 lg:mb-6">
                        <i class="fas fa-search text-white text-base sm:text-xl lg:text-2xl"></i>
                    </div>
                    <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 mb-1.5 sm:mb-2 lg:mb-3">Smart Search</h3>
                    <p class="text-xs sm:text-sm lg:text-base text-gray-600">Find professors by subject, expertise, or ask natural questions to get instant answers.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg sm:rounded-xl lg:rounded-2xl p-4 sm:p-6 lg:p-8 transition-all duration-300 border-2 border-yellow-100 hover:border-yellow-300 hover:shadow-xl">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-16 lg:h-16 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg sm:rounded-xl lg:rounded-2xl flex items-center justify-center mb-3 sm:mb-4 lg:mb-6">
                        <i class="fas fa-comments text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Natural Conversations</h3>
                    <p class="text-gray-600">85+ varied responses with personalization, context tracking, and emotional intelligence.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl p-8 transition-all duration-300 border-2 border-indigo-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Responsive Design</h3>
                    <p class="text-gray-600">Works perfectly on all devices - desktop, tablet, and mobile with smooth animations.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 px-4 bg-gradient-to-br from-blue-50 to-purple-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-xl text-gray-600">Get started in three simple steps</p>
            </div>
            
            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl sm:text-3xl font-bold mx-auto mb-4 sm:mb-6">1</div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Sign Up</h3>
                    <p class="text-sm sm:text-base text-gray-600">Create a free account or log in to access the chatbot system</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-white text-2xl sm:text-3xl font-bold mx-auto mb-4 sm:mb-6">2</div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Ask Questions</h3>
                    <p class="text-sm sm:text-base text-gray-600">Type your questions naturally or use quick suggestions</p>
                </div>
                <div class="text-center sm:col-span-2 md:col-span-1">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-pink-600 to-red-600 rounded-full flex items-center justify-center text-white text-2xl sm:text-3xl font-bold mx-auto mb-4 sm:mb-6">3</div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Get Instant Answers</h3>
                    <p class="text-sm sm:text-base text-gray-600">Receive intelligent responses with professor information and schedules</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-8 sm:py-12 lg:py-16 xl:py-20 px-3 sm:px-4">
        <div class="max-w-4xl mx-auto text-center">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl sm:rounded-2xl lg:rounded-3xl p-6 sm:p-8 lg:p-12 shadow-2xl">
                <h2 class="text-xl sm:text-2xl lg:text-3xl xl:text-4xl font-bold text-white mb-3 sm:mb-4 lg:mb-6">Ready to Get Started?</h2>
                <p class="text-sm sm:text-base lg:text-lg xl:text-xl text-blue-100 mb-4 sm:mb-6 lg:mb-8 px-2 sm:px-0">Join us and experience the future of academic information access</p>
                <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 lg:gap-4 justify-center">
                    <a href="register.php" class="bg-white hover:bg-gray-100 text-purple-600 px-5 sm:px-6 lg:px-8 py-2.5 sm:py-3 lg:py-4 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 inline-flex items-center justify-center gap-2 text-sm sm:text-base">
                        <i class="fas fa-user-plus text-sm sm:text-base"></i>
                        Sign Up Free
                    </a>
                    <a href="login.php" class="bg-purple-800 hover:bg-purple-900 text-white px-5 sm:px-6 lg:px-8 py-2.5 sm:py-3 lg:py-4 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 inline-flex items-center justify-center gap-2 text-sm sm:text-base">
                        <i class="fas fa-sign-in-alt text-sm sm:text-base"></i>
                        Login Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-6 sm:py-8 lg:py-12 px-3 sm:px-4">
        <div class="max-w-7xl mx-auto text-center">
            <div class="flex items-center justify-center gap-1.5 sm:gap-2 lg:gap-3 mb-3 sm:mb-4 lg:mb-6">
                <img src="Images/Bsit.logo.ico" alt="Logo" class="w-7 h-7 sm:w-8 sm:h-8 lg:w-10 lg:h-10 object-contain">
                <h3 class="text-lg sm:text-xl lg:text-2xl font-bold">FindMyProf</h3>
            </div>
            <p class="text-xs sm:text-sm lg:text-base text-gray-400 mb-3 sm:mb-4 lg:mb-6">Your intelligent professor information assistant</p>
            <div class="flex gap-3 sm:gap-4 lg:gap-6 justify-center mb-3 sm:mb-4 lg:mb-6">
                <a href="#" class="text-gray-400 hover:text-white transition" aria-label="Facebook"><i class="fab fa-facebook text-lg sm:text-xl lg:text-2xl"></i></a>
                <a href="#" class="text-gray-400 hover:text-white transition" aria-label="Twitter"><i class="fab fa-twitter text-lg sm:text-xl lg:text-2xl"></i></a>
                <a href="#" class="text-gray-400 hover:text-white transition" aria-label="LinkedIn"><i class="fab fa-linkedin text-lg sm:text-xl lg:text-2xl"></i></a>
                <a href="#" class="text-gray-400 hover:text-white transition" aria-label="GitHub"><i class="fab fa-github text-lg sm:text-xl lg:text-2xl"></i></a>
            </div>
            <p class="text-gray-500 text-[10px] sm:text-xs lg:text-sm">&copy; 2025 FindMyProf. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
