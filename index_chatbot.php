<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Professor Assistant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* 🎨 MINIMALIST MODERN DESIGN */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        /* Smooth animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes typing {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }
        
        .message-enter {
            animation: fadeInUp 0.4s ease-out;
        }
        
        .bot-message-enter {
            animation: slideIn 0.5s ease-out;
        }
        
        .typing-dot {
            animation: typing 1.4s infinite;
        }
        
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        
        /* Custom scrollbar */
        .chat-scroll::-webkit-scrollbar {
            width: 6px;
        }
        
        .chat-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .chat-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        
        .chat-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        /* Glass morphism */
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Modern card hover */
        .prof-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .prof-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Button hover effect */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        /* Chip hover */
        .chip {
            transition: all 0.2s ease;
        }
        
        .chip:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }
        
        /* Hide scrollbar for suggestions */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .chat-container {
                height: calc(100vh - 80px) !important;
            }
        }
        
        @media (min-width: 769px) {
            .chat-container {
                height: calc(100vh - 120px) !important;
            }
        }
        
        @media (min-width: 1440px) {
            .chat-container {
                height: calc(100vh - 140px) !important;
            }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 glass shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3 group cursor-pointer">
                    <div class="relative">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow">
                            <i class="fas fa-robot text-white text-lg"></i>
                        </div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white animate-pulse"></div>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold gradient-text">AI Assistant</h1>
                        <p class="text-xs text-gray-500">Database-Powered</p>
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="flex items-center gap-3">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-semibold text-gray-800">
                            <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>
                        </p>
                        <p class="text-xs text-gray-500 capitalize">
                            <?php echo htmlspecialchars($_SESSION['role']); ?>
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow">
                        <?php 
                            $initials = strtoupper(substr($_SESSION['first_name'], 0, 1) . substr($_SESSION['last_name'], 0, 1));
                            echo $initials;
                        ?>
                    </div>
                    <button onclick="confirmLogout()" class="ml-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition-all hover:scale-105 shadow-md">
                        <i class="fas fa-sign-out-alt mr-1"></i>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Chat Container -->
    <main class="pt-20 pb-4 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="glass rounded-2xl shadow-2xl overflow-hidden chat-container flex flex-col" style="height: calc(100vh - 120px);">
                
                <!-- Chat Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-4 text-white flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-robot text-purple-600 text-2xl"></i>
                                </div>
                                <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
                            </div>
                            <div>
                                <h2 class="font-bold text-lg">Professor Assistant</h2>
                                <p class="text-xs text-indigo-100 flex items-center gap-1">
                                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                    Online • Powered by Database
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button id="voiceBtn" onclick="toggleVoice()" class="px-3 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition backdrop-blur-sm">
                                <i class="fas fa-volume-mute"></i>
                            </button>
                            <button onclick="clearChat()" class="px-3 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition backdrop-blur-sm">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="messages" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gradient-to-b from-gray-50 to-white chat-scroll"></div>

                <!-- Quick Suggestions - Dynamic from Database -->
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex-shrink-0">
                    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1" id="suggestions">
                        <button onclick="quickAsk('Show all professors')" class="chip flex-shrink-0 px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium hover:bg-indigo-200 transition">
                            <i class="fas fa-users mr-1"></i>All Professors
                        </button>
                        <!-- Dynamic suggestions will be loaded here -->
                    </div>
                </div>

                <!-- Input Area -->
                <div class="p-4 bg-white border-t-2 border-gray-200 flex-shrink-0">
                    <div class="relative">
                        <input 
                            type="text" 
                            id="userInput" 
                            placeholder="Ask about professors, schedules, subjects..." 
                            class="w-full px-5 py-3 pr-14 border-2 border-gray-300 rounded-full focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition text-sm"
                            onkeypress="handleKeyPress(event)"
                            oninput="handleAutoSuggest(event)"
                            autocomplete="off"
                        />
                        <!-- Auto-suggest dropdown -->
                        <div id="autoSuggestBox" class="absolute bottom-full left-0 right-0 mb-2 bg-white border-2 border-indigo-300 rounded-lg shadow-xl max-h-48 overflow-y-auto hidden z-50">
                            <!-- Suggestions will appear here -->
                        </div>
                        <button onclick="sendMessage()" class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 btn-primary text-white rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <p class="text-xs text-center text-gray-400 mt-2">
                        <i class="fas fa-database text-green-500 mr-1"></i>
                        All responses from database • Press Enter to send
                    </p>
                </div>

            </div>
        </div>
    </main>

    <script>
        let voiceEnabled = false;
        let voiceSupported = 'speechSynthesis' in window;
        let conversationHistory = [];
        
        // Advanced conversation context tracking
        let conversationContext = {
            lastIntent: null,
            lastSentiment: null,
            lastTimestamp: null,
            conversationDepth: 0,
            topics: [],
            userName: null,
            emotionalState: 'neutral',
            previousQueries: []
        };
        
        // Update context after each interaction
        function updateContext(intent, sentiment) {
            conversationContext.lastIntent = intent;
            conversationContext.lastSentiment = sentiment;
            conversationContext.lastTimestamp = Date.now();
            conversationContext.conversationDepth++;
            
            // Track emotional trajectory
            if (sentiment === 'positive') conversationContext.emotionalState = 'positive';
            else if (sentiment === 'negative') conversationContext.emotionalState = 'negative';
            
            console.log('📊 Context updated:', conversationContext);
        }
        
        // Initialize
        window.onload = function() {
            showWelcome();
            setupVoice();
            loadDynamicSuggestions();
        }
        
        // Load schedules and subjects from database for quick suggestions
        async function loadDynamicSuggestions() {
            try {
                const res = await fetch('chatbot_ai.php?action=get_suggestions');
                const data = await res.json();
                
                console.log('Dynamic suggestions loaded:', data);
                
                const suggestionsDiv = document.getElementById('suggestions');
                suggestionsDiv.innerHTML = ''; // Clear existing
                
                // Add "Show all professors" button first
                const profBtn = document.createElement('button');
                profBtn.onclick = () => quickAsk('Show all professors');
                profBtn.className = 'chip flex-shrink-0 px-4 py-2 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full text-xs font-semibold hover:from-indigo-200 hover:to-purple-200 transition-all shadow-sm hover:shadow';
                profBtn.innerHTML = '<i class="fas fa-users mr-2"></i>All Professors';
                suggestionsDiv.appendChild(profBtn);
                
                // Add "Show schedules" button
                const schedBtn = document.createElement('button');
                schedBtn.onclick = () => quickAsk('Show all schedules');
                schedBtn.className = 'chip flex-shrink-0 px-4 py-2 bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-700 rounded-full text-xs font-semibold hover:from-blue-200 hover:to-cyan-200 transition-all shadow-sm hover:shadow';
                schedBtn.innerHTML = '<i class="fas fa-calendar-alt mr-2"></i>All Schedules';
                suggestionsDiv.appendChild(schedBtn);
                
                if (data.subjects && data.subjects.length > 0) {
                    const colors = [
                        { from: 'purple-100', to: 'pink-100', text: 'purple-700', hoverFrom: 'purple-200', hoverTo: 'pink-200' },
                        { from: 'green-100', to: 'teal-100', text: 'green-700', hoverFrom: 'green-200', hoverTo: 'teal-200' },
                        { from: 'orange-100', to: 'red-100', text: 'orange-700', hoverFrom: 'orange-200', hoverTo: 'red-200' },
                        { from: 'yellow-100', to: 'amber-100', text: 'yellow-700', hoverFrom: 'yellow-200', hoverTo: 'amber-200' }
                    ];
                    
                    // Add subject suggestions (limit to 3)
                    data.subjects.slice(0, 3).forEach((subject, index) => {
                        const color = colors[index % colors.length];
                        const btn = document.createElement('button');
                        btn.onclick = () => quickAsk(`Who teaches ${subject}?`);
                        btn.className = `chip flex-shrink-0 px-4 py-2 bg-gradient-to-r from-${color.from} to-${color.to} text-${color.text} rounded-full text-xs font-medium hover:from-${color.hoverFrom} hover:to-${color.hoverTo} transition-all shadow-sm hover:shadow`;
                        btn.innerHTML = `<i class="fas fa-book-open mr-2"></i>${subject}`;
                        suggestionsDiv.appendChild(btn);
                    });
                }
            } catch (error) {
                console.error('Error loading suggestions:', error);
                // Add fallback suggestions
                const suggestionsDiv = document.getElementById('suggestions');
                suggestionsDiv.innerHTML = `
                    <button onclick="quickAsk('Show all professors')" class="chip flex-shrink-0 px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium hover:bg-indigo-200 transition">
                        <i class="fas fa-users mr-2"></i>All Professors
                    </button>
                    <button onclick="quickAsk('Show all schedules')" class="chip flex-shrink-0 px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-xs font-medium hover:bg-blue-200 transition">
                        <i class="fas fa-calendar mr-2"></i>All Schedules
                    </button>
                `;
            }
        }
        
        function showWelcome() {
            const messages = [
                "👋 Hello! I'm your AI Professor Assistant with Smart Understanding!",
                "🧠 I can understand natural language - just ask me anything!",
                "💡 Examples: 'show schedules', 'who teaches AI?', or just type a professor name!"
            ];
            
            messages.forEach((msg, i) => {
                setTimeout(() => addBotMessage(msg), i * 600);
            });
            
            // Show conversational examples after welcome
            setTimeout(() => {
                showQueryExamples();
            }, 2000);
        }
        
        function showQueryExamples() {
            const hint = `
                <div class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 rounded-xl p-4 border-2 border-indigo-200 shadow-sm">
                    <h4 class="text-sm font-bold text-indigo-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-lightbulb text-yellow-500"></i>
                        Try these smart queries:
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                        <div class="bg-white rounded-lg p-3 shadow-sm border border-indigo-100">
                            <p class="font-semibold text-indigo-700 mb-1">📚 Academic Queries:</p>
                            <ul class="space-y-1 text-gray-700">
                                <li>• "Show all schedules"</li>
                                <li>• "Who teaches Algorithms?"</li>
                                <li>• "List all professors"</li>
                                <li>• "Expert in database"</li>
                            </ul>
                        </div>
                        <div class="bg-white rounded-lg p-3 shadow-sm border border-purple-100">
                            <p class="font-semibold text-purple-700 mb-1">💬 Natural Language:</p>
                            <ul class="space-y-1 text-gray-700">
                                <li>• Type professor name</li>
                                <li>• "schedule for adrada"</li>
                                <li>• "professors"</li>
                                <li>• "schedules"</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg p-2 border border-yellow-200">
                        <p class="text-xs text-gray-700 flex items-center gap-2">
                            <i class="fas fa-robot text-indigo-600"></i>
                            <span><strong>Plus emotional support:</strong> "Thank you", "I'm sad", "Tell me a joke"</span>
                        </p>
                    </div>
                </div>
            `;
            addBotMessage(hint, true);
        }
        
        function addUserMessage(text) {
            const div = document.createElement('div');
            div.className = 'flex justify-end message-enter';
            div.innerHTML = `
                <div class="max-w-md">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 py-3 rounded-2xl rounded-br-md shadow-lg">
                        <p class="text-sm">${escapeHtml(text)}</p>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 text-right">Just now</p>
                </div>
            `;
            document.getElementById('messages').appendChild(div);
            scrollToBottom();
            conversationHistory.push({role: 'user', message: text});
        }
        
        function addBotMessage(text, isHTML = false) {
            const div = document.createElement('div');
            div.className = 'flex justify-start bot-message-enter';
            const content = isHTML ? text : `<p class="text-sm text-gray-800">${escapeHtml(text)}</p>`;
            div.innerHTML = `
                <div class="max-w-2xl">
                    <div class="flex items-start gap-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0 shadow">
                            <i class="fas fa-robot text-white text-sm"></i>
                        </div>
                        <div class="bg-white px-4 py-3 rounded-2xl rounded-tl-md shadow-lg border border-gray-100">
                            ${content}
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 ml-10">
                        <i class="fas fa-database text-green-500 mr-1"></i>From database
                    </p>
                </div>
            `;
            document.getElementById('messages').appendChild(div);
            scrollToBottom();
            conversationHistory.push({role: 'bot', message: text});
            
            if (voiceEnabled && voiceSupported && !isHTML) {
                speak(text);
            }
        }
        
        function showTyping() {
            const div = document.createElement('div');
            div.id = 'typing';
            div.className = 'flex justify-start';
            div.innerHTML = `
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow">
                        <i class="fas fa-robot text-white text-sm"></i>
                    </div>
                    <div class="bg-white px-4 py-3 rounded-2xl shadow-lg border border-gray-100">
                        <div class="flex gap-1">
                            <span class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></span>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('messages').appendChild(div);
            scrollToBottom();
        }
        
        function removeTyping() {
            const typing = document.getElementById('typing');
            if (typing) typing.remove();
        }
        
        async function sendMessage() {
            const input = document.getElementById('userInput');
            const text = input.value.trim();
            if (!text) return;
            
            addUserMessage(text);
            input.value = '';
            showTyping();
            
            // Process with AI backend
            setTimeout(async () => {
                removeTyping();
                await processQuery(text);
            }, 800);
        }
        
        async function processQuery(query) {
            const lowerQuery = query.toLowerCase().trim();
            
            // Detect and remember user name
            if (lowerQuery.includes('my name is') || lowerQuery.includes('i am') || lowerQuery.includes("i'm")) {
                const nameMatch = query.match(/(?:my name is|i am|i'm)\s+([a-zA-Z]+)/i);
                if (nameMatch && nameMatch[1] && nameMatch[1].length > 2) {
                    conversationContext.userName = nameMatch[1];
                    console.log('👤 User name detected:', conversationContext.userName);
                    addBotMessage(`Nice to meet you, ${conversationContext.userName}! 😊 How can I help you today?`);
                    return;
                }
            }
            
            // 🧠 ENHANCED INTENT DETECTION - More natural language understanding
            const intentPatterns = {
                showAllSchedules: [
                    /show\s+(all\s+)?schedules?/i,
                    /view\s+(all\s+)?schedules?/i,
                    /list\s+(all\s+)?schedules?/i,
                    /see\s+(all\s+)?schedules?/i,
                    /display\s+(all\s+)?schedules?/i,
                    /^schedules?$/i,
                    /what\s+(are\s+)?the\s+schedules?/i,
                    /all\s+class(es)?\s+schedules?/i
                ],
                showAllProfessors: [
                    /show\s+(all\s+)?(professors?|teachers?|faculty|instructors?)/i,
                    /list\s+(all\s+)?(professors?|teachers?|faculty|instructors?)/i,
                    /view\s+(all\s+)?(professors?|teachers?|faculty|instructors?)/i,
                    /see\s+(all\s+)?(professors?|teachers?|faculty|instructors?)/i,
                    /display\s+(all\s+)?(professors?|teachers?|faculty|instructors?)/i,
                    /^(professors?|teachers?|faculty)$/i,
                    /who\s+are\s+the\s+(professors?|teachers?|faculty)/i,
                    /get\s+(all\s+)?(professors?|teachers?)/i
                ],
                findBySubject: [
                    /who\s+teach(es)?\s+(.+)/i,
                    /who\s+is\s+teaching\s+(.+)/i,
                    /teacher\s+for\s+(.+)/i,
                    /professor\s+for\s+(.+)/i,
                    /instructor\s+for\s+(.+)/i,
                    /(.+)\s+teacher/i,
                    /(.+)\s+professor/i,
                    /find\s+(.+)\s+teacher/i
                ],
                findByExpertise: [
                    /expert\s+in\s+(.+)/i,
                    /specialize\s+in\s+(.+)/i,
                    /specialization\s+in\s+(.+)/i,
                    /who\s+knows\s+(.+)/i,
                    /expertise\s+in\s+(.+)/i
                ],
                scheduleForProfessor: [
                    /schedule\s+(of|for)\s+(.+)/i,
                    /(.+)\s+schedule/i,
                    /when\s+does\s+(.+)\s+teach/i,
                    /(.+)\s+class(es)?\s+time/i
                ]
            };
            
            // Check for academic/technical queries with smart matching
            let matched = false;
            
            // Check: Show all schedules
            for (const pattern of intentPatterns.showAllSchedules) {
                if (pattern.test(lowerQuery)) {
                    await showSchedules(query);
                    return;
                }
            }
            
            // Check: Show all professors
            for (const pattern of intentPatterns.showAllProfessors) {
                if (pattern.test(lowerQuery)) {
                    await fetchAllProfessors();
                    return;
                }
            }
            
            // Check: Find by subject (who teaches X?)
            for (const pattern of intentPatterns.findBySubject) {
                const match = lowerQuery.match(pattern);
                if (match) {
                    const subject = match[1] || match[2];
                    if (subject && subject.length > 1) {
                        await findBySubject(subject.trim());
                        return;
                    }
                }
            }
            
            // Check: Find by expertise
            for (const pattern of intentPatterns.findByExpertise) {
                const match = lowerQuery.match(pattern);
                if (match && match[1]) {
                    await findByExpertise(match[1].trim());
                    return;
                }
            }
            
            // Check: Schedule for specific professor
            for (const pattern of intentPatterns.scheduleForProfessor) {
                const match = lowerQuery.match(pattern);
                if (match) {
                    const professorName = match[1] || match[2];
                    if (professorName && professorName.length > 2 && !professorName.includes('show') && !professorName.includes('all')) {
                        // Search for professor and show their schedule
                        await searchProfessorSchedule(professorName.trim());
                        return;
                    }
                }
            }
            
            // First, check for conversational intents from database
            const conversationalResponse = await checkConversationalIntent(query);
            if (conversationalResponse) {
                addBotMessage(conversationalResponse);
                return;
            }
            
            // If no specific pattern matched, try searching for professor by name
            await searchProfessor(query);
        }
        
        // New function: Search professor and show their schedule
        async function searchProfessorSchedule(name) {
            try {
                const res = await fetch(`chatbot_ai.php?name=${encodeURIComponent(name)}`);
                const data = await res.json();
                
                if (data.professors && data.professors.length > 0) {
                    addBotMessage(`📅 Schedule for "${name}":`);
                    data.professors.forEach(prof => displayProfessorCard(prof));
                } else {
                    addBotMessage(`No professor matching "${escapeHtml(name)}" found. Try: "Show all professors"`);
                }
            } catch (error) {
                console.error('Error searching professor:', error);
                addBotMessage("❌ Database error.");
            }
        }
        
        // Check for conversational intents from database
        async function checkConversationalIntent(query) {
            try {
                // Use backend API to detect intent from database patterns
                const res = await fetch(`chatbot_ai.php?action=detect_intent&query=${encodeURIComponent(query)}`);
                const data = await res.json();
                
                console.log('🎯 Intent detection result:', data);
                
                if (data.intent && data.intent !== 'unknown') {
                    // Show intent badge
                    showIntentBadge(data.intent, data.sentiment || 'neutral', null);
                    
                    // Return the response from database
                    if (data.response) {
                        return data.response;
                    }
                }
                
                return null;
            } catch (error) {
                console.error('Error detecting intent:', error);
                return null;
            }
        }
        
        // Advanced sentiment analysis
        function analyzeSentiment(text) {
            const positiveWords = ['good', 'great', 'happy', 'yes', 'awesome', 'wonderful', 'excellent', 'love', 'like', 'amazing', 'fantastic', 'best', 'beautiful', 'perfect', 'brilliant'];
            const negativeWords = ['no', 'not', 'never', 'bad', 'terrible', 'awful', 'hate', 'dislike', 'sad', 'angry', 'worst', 'horrible', 'disappointing', 'frustrated'];
            const veryPositiveWords = ['absolutely', 'definitely', 'totally', 'extremely', 'super', 'very'];
            const veryNegativeWords = ['absolutely not', 'definitely not', 'hate it', 'worst ever'];
            
            let positiveScore = 0;
            let negativeScore = 0;
            let intensity = 1;
            
            // Check for intensifiers
            veryPositiveWords.forEach(word => {
                if (text.includes(word)) intensity = 1.5;
            });
            
            veryNegativeWords.forEach(word => {
                if (text.includes(word)) intensity = 1.5;
            });
            
            // Count sentiment words
            positiveWords.forEach(word => {
                if (text.includes(word)) positiveScore++;
            });
            
            negativeWords.forEach(word => {
                if (text.includes(word)) negativeScore++;
            });
            
            // Apply intensity
            positiveScore *= intensity;
            negativeScore *= intensity;
            
            // Determine sentiment
            let sentiment = 'neutral';
            if (positiveScore > negativeScore) {
                sentiment = 'positive';
            } else if (negativeScore > positiveScore) {
                sentiment = 'negative';
            }
            
            return {
                sentiment: sentiment,
                positiveScore: positiveScore,
                negativeScore: negativeScore,
                intensity: intensity
            };
        }
        
        // Show intent detection badge
        function showIntentBadge(intent, sentiment, sentimentAnalysis = null) {
            const icons = {
                // Emotional
                love: '💖', happiness: '😊', sadness: '😢', anger: '😠', 
                                fear: '😰', heartbreak: '💔', motivation: '💪', comfort: '🤗',
                apology: '🙏', loneliness: '😔', excitement: '🎉', boredom: '😴',
                // Social
                compliment: '✨', gratitude: '🙏', greeting: '👋', goodbye: '👋',
                                farewell: '👋', flirt: '😘', joke: '😂', small_talk: '💬',
                // Functional
                help: '❓', question: '❔', confirm: '✅', deny: '❌',
                                cancel: '🔄', schedule: '📅', confusion: '🤔',
                // Entertainment
                                music: '🎵', movies: '🎬', gaming: '🎮', meme: '😂',
                random_fact: '💡', quotes: '📝', pickup_line: '😘',
                // Relationship
                missing_someone: '💙', sweet_talk: '🥰', breakup: '💔', caring: '💙',
                // Bot/System
                about_bot: '🤖', bot_name_query: '🤖', bot_capability: '⚙️',
                bot_thank: '🙏', unknown: '🤔'
            };
            
            const colors = {
                positive: 'green',
                negative: 'red',
                neutral: 'blue'
            };
            
            const intensityIndicator = sentimentAnalysis && sentimentAnalysis.intensity > 1 
                ? '<span class="ml-1 text-yellow-500">⚡</span>' 
                : '';
            
            const badge = `
                <div class="flex justify-center my-2">
                    <div class="bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-full px-4 py-2 shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105">
                        <div class="flex items-center gap-3 text-xs">
                            <span class="text-base">${icons[intent] || '🤖'}</span>
                            <span class="font-semibold text-purple-700 capitalize">Intent: ${intent.replace(/_/g, ' ')}</span>
                            <span class="text-gray-300">|</span>
                            <span class="font-semibold text-${colors[sentiment]}-700 capitalize">${sentiment}</span>
                            ${intensityIndicator}
                        </div>
                    </div>
                </div>
            `;
            
            const messagesDiv = document.getElementById('messages');
            const badgeDiv = document.createElement('div');
            badgeDiv.innerHTML = badge;
            messagesDiv.appendChild(badgeDiv);
            scrollToBottom();
        }
        
        async function fetchAllProfessors() {
            try {
                const res = await fetch('chatbot_ai.php?action=list_all');
                const data = await res.json();
                
                console.log('Professors data:', data); // Debug log
                
                if (data.professors && data.professors.length > 0) {
                    addBotMessage(`📚 Found ${data.professors.length} professor(s) in database:`);
                    data.professors.forEach(prof => displayProfessorCard(prof));
                } else {
                    addBotMessage("No professors found in database.");
                }
            } catch (error) {
                console.error('Error fetching professors:', error);
                addBotMessage("❌ Database error. Please try again.");
            }
        }
        
        async function findBySubject(subject) {
            try {
                const res = await fetch(`chatbot_ai.php?action=find_by_subject&subject=${encodeURIComponent(subject)}`);
                const data = await res.json();
                
                console.log('Subject search data:', data); // Debug log
                
                if (data.professors && data.professors.length > 0) {
                    addBotMessage(`📖 Found ${data.professors.length} professor(s) teaching "${subject}":`);
                    data.professors.forEach(prof => displayProfessorCard(prof));
                } else {
                    addBotMessage(`No professors found teaching "${escapeHtml(subject)}".`);
                }
            } catch (error) {
                console.error('Error finding by subject:', error);
                addBotMessage("❌ Database error.");
            }
        }
        
        async function findByExpertise(expertise) {
            try {
                const res = await fetch(`chatbot_ai.php?action=find_by_expertise&expertise=${encodeURIComponent(expertise)}`);
                const data = await res.json();
                
                if (data.professors && data.professors.length > 0) {
                    addBotMessage(`🎓 Found ${data.professors.length} expert(s) in "${expertise}":`);
                    data.professors.forEach(prof => displayProfessorCard(prof));
                } else {
                    addBotMessage(`No experts found in "${escapeHtml(expertise)}".`);
                }
            } catch (error) {
                addBotMessage("❌ Database error.");
            }
        }
        
        async function showSchedules(query) {
            try {
                const res = await fetch('chatbot_ai.php?action=list_schedules');
                const data = await res.json();
                
                if (data.schedules && data.schedules.length > 0) {
                    addBotMessage(`🗓️ Showing ${data.schedules.length} schedule entries:`);
                    displaySchedules(data.schedules);
                } else {
                    addBotMessage("No schedules found.");
                }
            } catch (error) {
                addBotMessage("❌ Database error.");
            }
        }
        
        async function searchProfessor(name) {
            try {
                const res = await fetch(`chatbot_ai.php?name=${encodeURIComponent(name)}`);
                const data = await res.json();
                
                console.log('Search data:', data); // Debug log
                
                if (data.professors && data.professors.length > 0) {
                    addBotMessage(`🔍 Found match(es) for "${name}":`);
                    data.professors.forEach(prof => displayProfessorCard(prof));
                } else {
                    addBotMessage(`No professor matching "${escapeHtml(name)}" found. Try: "Show all professors"`);
                }
            } catch (error) {
                console.error('Error searching professor:', error);
                addBotMessage("❌ Database error.");
            }
        }
        
        function displayProfessorCard(prof) {
            const scheduleRows = (prof.schedules || []).map(s => `
                <tr class="border-b border-gray-100">
                    <td class="px-3 py-2 text-xs">${escapeHtml(s.subject)}</td>
                    <td class="px-3 py-2 text-xs">${escapeHtml(s.day)}</td>
                    <td class="px-3 py-2 text-xs">${escapeHtml(s.time)}</td>
                    <td class="px-3 py-2 text-xs">${escapeHtml(s.room)}</td>
                </tr>
            `).join('');
            
            const expertise = (prof.expertise || '').split(',').map(e => 
                `<span class="inline-block bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded-full mr-1">${escapeHtml(e.trim())}</span>`
            ).join('');
            
            const card = `
                <div class="prof-card bg-white rounded-xl p-4 shadow-lg border border-gray-100 mb-3">
                    <div class="flex items-start gap-4 mb-3">
                        <img src="${prof.photo || 'Images/default.png'}" class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-md" alt="${escapeHtml(prof.professor_name)}">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold gradient-text">${escapeHtml(prof.professor_name)}</h3>
                            <p class="text-sm text-purple-600 font-medium">${escapeHtml(prof.plantilla_title || 'Professor')}</p>
                            <div class="flex gap-2 mt-2">
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                                    <i class="fas fa-birthday-cake mr-1"></i>${prof.age || 'N/A'} yrs
                                </span>
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">
                                    <i class="fas fa-briefcase mr-1"></i>${prof.years_in_service || 'N/A'} yrs
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    ${prof.bio ? `<p class="text-xs text-gray-600 italic mb-3 bg-gray-50 p-2 rounded">"${escapeHtml(prof.bio)}"</p>` : ''}
                    
                    ${expertise ? `<div class="mb-3"><h4 class="text-xs font-semibold mb-1"><i class="fas fa-brain text-purple-500 mr-1"></i>Expertise:</h4><div>${expertise}</div></div>` : ''}
                    
                    ${scheduleRows ? `
                    <div class="mt-3">
                        <h4 class="text-xs font-semibold mb-2"><i class="fas fa-calendar text-green-500 mr-1"></i>Schedule:</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-purple-50">
                                    <tr>
                                        <th class="px-3 py-1 text-xs font-semibold">Subject</th>
                                        <th class="px-3 py-1 text-xs font-semibold">Day</th>
                                        <th class="px-3 py-1 text-xs font-semibold">Time</th>
                                        <th class="px-3 py-1 text-xs font-semibold">Room</th>
                                    </tr>
                                </thead>
                                <tbody>${scheduleRows}</tbody>
                            </table>
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="mt-3 pt-3 border-t border-gray-200 text-xs text-gray-500">
                        <i class="fas fa-database text-green-500 mr-1"></i>From database
                    </div>
                </div>
            `;
            
            addBotMessage(card, true);
        }
        
        function displaySchedules(schedules) {
            const grouped = {};
            schedules.forEach(s => {
                const key = s.professor_name || 'Unknown';
                if (!grouped[key]) grouped[key] = [];
                grouped[key].push(s);
            });
            
            Object.entries(grouped).forEach(([name, items]) => {
                const rows = items.map(it => `
                    <tr class="border-b border-gray-100">
                        <td class="px-2 py-1 text-xs">${escapeHtml(it.day || '')}</td>
                        <td class="px-2 py-1 text-xs">${escapeHtml(it.time || '')}</td>
                        <td class="px-2 py-1 text-xs">${escapeHtml(it.room || '')}</td>
                        <td class="px-2 py-1 text-xs">${escapeHtml(it.subject || '')}</td>
                    </tr>
                `).join('');
                
                const card = `
                    <div class="bg-white rounded-xl p-4 shadow-lg border border-gray-100 mb-3">
                        <h3 class="font-bold text-sm mb-2 gradient-text">${escapeHtml(name)}</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-purple-50">
                                    <tr>
                                        <th class="px-2 py-1 text-xs">Day</th>
                                        <th class="px-2 py-1 text-xs">Time</th>
                                        <th class="px-2 py-1 text-xs">Room</th>
                                        <th class="px-2 py-1 text-xs">Subject</th>
                                    </tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>
                        </div>
                    </div>
                `;
                addBotMessage(card, true);
            });
        }
        
        function showHelp() {
            const help = `
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-4 border border-indigo-200">
                    <h3 class="font-bold text-indigo-800 mb-3 flex items-center gap-2">
                        <span class="text-lg">💡</span> What I Can Do
                    </h3>
                    
                    <div class="space-y-3">
                        <!-- Academic Queries -->
                        <div class="bg-white rounded-lg p-3">
                            <h4 class="font-semibold text-sm text-blue-700 mb-2">📚 Academic Queries</h4>
                            <ul class="space-y-1 text-xs text-gray-700">
                                <li>• <strong>"Show all professors"</strong> - List everyone</li>
                                <li>• <strong>"Who teaches Database?"</strong> - Find by subject</li>
                                <li>• <strong>"Find AI experts"</strong> - Search by expertise</li>
                                <li>• <strong>"Show schedules"</strong> - View all schedules</li>
                                <li>• <strong>"Dr. Smith"</strong> - Search by name</li>
                            </ul>
                        </div>
                        
                        <!-- Conversational -->
                        <div class="bg-white rounded-lg p-3">
                            <h4 class="font-semibold text-sm text-purple-700 mb-2">💬 Conversational (Emotional AI)</h4>
                            <ul class="space-y-1 text-xs text-gray-700">
                                <li>• <strong>😊 Flirting:</strong> "You're beautiful", "I love you"</li>
                                <li>• <strong>🙏 Gratitude:</strong> "Thank you", "Thanks so much"</li>
                                <li>• <strong>😔 Sadness:</strong> "I'm sad", "Feeling down"</li>
                                <li>• <strong>😠 Anger:</strong> "I'm angry", "This is frustrating"</li>
                                <li>• <strong>😄 Jokes:</strong> "Tell me a joke", "Make me laugh"</li>
                                <li>• <strong>👋 Greetings:</strong> "Hello", "How are you?"</li>
                            </ul>
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-600 mt-3 italic flex items-center gap-1">
                        <i class="fas fa-database text-green-500"></i>
                        All responses from database • I detect your emotions!
                    </p>
                </div>
            `;
            addBotMessage(help, true);
        }
        
        function quickAsk(question) {
            document.getElementById('userInput').value = question;
            sendMessage();
        }
        
        function handleKeyPress(e) {
            if (e.key === 'Enter') {
                hideAutoSuggest();
                sendMessage();
            } else if (e.key === 'Escape') {
                hideAutoSuggest();
            } else if (e.key === 'ArrowDown') {
                navigateSuggestions('down');
                e.preventDefault();
            } else if (e.key === 'ArrowUp') {
                navigateSuggestions('up');
                e.preventDefault();
            }
        }
        
        // Auto-suggest functionality
        let autoSuggestTimeout = null;
        let currentSuggestions = [];
        let selectedSuggestionIndex = -1;
        
        async function handleAutoSuggest(e) {
            const query = e.target.value.trim();
            
            // Clear previous timeout
            if (autoSuggestTimeout) {
                clearTimeout(autoSuggestTimeout);
            }
            
            // Hide if query is too short
            if (query.length < 2) {
                hideAutoSuggest();
                return;
            }
            
            // Debounce the API call
            autoSuggestTimeout = setTimeout(async () => {
                await fetchSuggestions(query);
            }, 300);
        }
        
        async function fetchSuggestions(query) {
            try {
                const res = await fetch(`chatbot_ai.php?suggest=${encodeURIComponent(query)}`);
                const suggestions = await res.json();
                
                if (suggestions && suggestions.length > 0) {
                    currentSuggestions = suggestions;
                    selectedSuggestionIndex = -1;
                    displaySuggestions(suggestions);
                } else {
                    hideAutoSuggest();
                }
            } catch (error) {
                console.error('Auto-suggest error:', error);
                hideAutoSuggest();
            }
        }
        
        function displaySuggestions(suggestions) {
            const box = document.getElementById('autoSuggestBox');
            
            const html = suggestions.map((suggestion, index) => `
                <div class="suggestion-item px-4 py-3 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 cursor-pointer transition-all duration-200 border-b border-gray-100 last:border-b-0 flex items-center gap-3 group"
                     onclick="selectSuggestion('${escapeHtml(suggestion).replace(/'/g, "\\'")}')"
                     onmouseenter="highlightSuggestion(${index})"
                     data-index="${index}">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-tie text-indigo-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <span class="text-sm font-medium text-gray-800 group-hover:text-indigo-700">${escapeHtml(suggestion)}</span>
                        <p class="text-xs text-gray-500 group-hover:text-indigo-600">Click to select professor</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 text-xs group-hover:text-indigo-600 group-hover:translate-x-1 transition-all"></i>
                </div>
            `).join('');
            
            box.innerHTML = html;
            box.classList.remove('hidden');
        }
        
        function highlightSuggestion(index) {
            selectedSuggestionIndex = index;
            const items = document.querySelectorAll('.suggestion-item');
            items.forEach((item, i) => {
                if (i === index) {
                    item.classList.add('bg-gradient-to-r', 'from-indigo-50', 'to-purple-50');
                } else {
                    item.classList.remove('bg-gradient-to-r', 'from-indigo-50', 'to-purple-50');
                }
            });
        }
        
        function selectSuggestion(suggestion) {
            const input = document.getElementById('userInput');
            input.value = suggestion;
            hideAutoSuggest();
            input.focus();
            
            // Auto-send the query after selecting
            setTimeout(() => {
                sendMessage();
            }, 200);
        }
        
        function hideAutoSuggest() {
            const box = document.getElementById('autoSuggestBox');
            box.classList.add('hidden');
            box.innerHTML = '';
            currentSuggestions = [];
            selectedSuggestionIndex = -1;
        }
        
        function navigateSuggestions(direction) {
            if (currentSuggestions.length === 0) return;
            
            if (direction === 'down') {
                selectedSuggestionIndex = Math.min(selectedSuggestionIndex + 1, currentSuggestions.length - 1);
            } else {
                selectedSuggestionIndex = Math.max(selectedSuggestionIndex - 1, -1);
            }
            
            // Highlight selected item
            const items = document.querySelectorAll('.suggestion-item');
            items.forEach((item, index) => {
                if (index === selectedSuggestionIndex) {
                    item.classList.add('bg-indigo-100');
                    document.getElementById('userInput').value = currentSuggestions[index];
                } else {
                    item.classList.remove('bg-indigo-100');
                }
            });
        }
        
        // Click outside to close suggestions
        document.addEventListener('click', function(e) {
            const box = document.getElementById('autoSuggestBox');
            const input = document.getElementById('userInput');
            if (box && input && !box.contains(e.target) && e.target !== input) {
                hideAutoSuggest();
            }
        });
        
        function scrollToBottom() {
            const msgs = document.getElementById('messages');
            msgs.scrollTo({ top: msgs.scrollHeight, behavior: 'smooth' });
        }
        
        function clearChat() {
            document.getElementById('messages').innerHTML = '';
            conversationHistory = [];
            if (voiceSupported) speechSynthesis.cancel();
            showWelcome();
        }
        
        function setupVoice() {
            const btn = document.getElementById('voiceBtn');
            if (!voiceSupported) {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }
        
        function toggleVoice() {
            if (!voiceSupported) return;
            voiceEnabled = !voiceEnabled;
            const btn = document.getElementById('voiceBtn');
            btn.innerHTML = voiceEnabled ? '<i class="fas fa-volume-up"></i>' : '<i class="fas fa-volume-mute"></i>';
            if (voiceEnabled) speak("Voice enabled");
        }
        
        function speak(text) {
            if (!voiceSupported || !voiceEnabled) return;
            speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.rate = 1;
            speechSynthesis.speak(utterance);
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function confirmLogout() {
            Swal.fire({
                title: 'Logout?',
                text: 'Are you sure you want to leave?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6366f1',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Stay'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        }
    </script>

</body>
</html>

