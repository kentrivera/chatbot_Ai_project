# 🤖 AI Chatbot System - Professor Information Assistant

## 📋 Overview
An intelligent chatbot system for querying professor information, schedules, and engaging in human-like conversations. Built with PHP, MySQL, and enhanced with conversational AI capabilities.

---

## ✨ Features

### 🎯 Core Functionality
- **Professor Directory** - View all professors and their information
- **Subject Search** - Find professors by subject they teach
- **Schedule Management** - Check professor schedules and availability
- **Expertise Finder** - Search professors by their area of expertise
- **Admin Panel** - Complete CRUD operations for professors and schedules

### 🧠 Conversational AI
- **13 Emotional Intents** - Love, Compliment, Farewell, Excitement, Flirt, Gratitude, Sadness, Anger, Boredom, Joke, Small Talk, Help, Confusion
- **Sentiment Analysis** - Detects positive, negative, and neutral emotions with intensity scoring
- **Context Tracking** - Remembers user names and conversation history
- **Personalization** - Uses detected names in responses for natural conversations
- **85 Human-like Responses** - Varied, contextual responses from database

### 🎨 User Interface
- **Minimalist Design** - Clean, modern interface with gradient accents
- **Responsive Layout** - Works perfectly on desktop, tablet, and mobile devices
- **Visual Feedback** - Color-coded intent badges with emoji indicators
- **8 Quick Suggestions** - Pre-made query buttons for instant interaction
- **Smooth Animations** - Typing indicators and message transitions

---

## 🚀 Quick Start

### Access URLs

**Local Access:**
```
http://localhost/chatbot_system/
http://localhost/chatbot_system/index_chatbot.php
```

**Network Access (Same WiFi):**
```
http://192.168.254.103/chatbot_system/
http://192.168.254.103/chatbot_system/index_chatbot.php
```

### Quick Chat Suggestions
The chatbot includes 8 quick-access buttons:
1. 👥 **All Professors** - View complete professor list
2. 📅 **Schedules** - Show all schedules
3. 📖 **Find Subject** - Search by subject (e.g., "Database")
4. 👋 **Greet** - Start a friendly conversation
5. 💚 **Say Thanks** - Express gratitude
6. ⭐ **Compliment** - Give praise to the chatbot
7. 😂 **Joke** - Request humor
8. ❓ **Help** - Get assistance

---

## 🧪 Testing Examples

### Academic Queries
```
✓ "Show all professors"
✓ "Who teaches Database?"
✓ "Show schedule"
✓ "Find Computer Science expert"
```

### Conversational Queries
```
✓ "Hello" → Greeting response with 💬 badge
✓ "My name is Alex" → Name recognition & personalization
✓ "Thank you!" → Gratitude response with 🙏 badge
✓ "You're awesome!" → Compliment response with ✨ badge
✓ "Tell me a joke" → Humorous response with 😂 badge
✓ "I need help" → Guidance response with ❓ badge
```

---

## 📊 Database

### Tables:
- **professors** - Professor information (name, expertise, contact)
- **schedules** - Class schedules with professor assignments
- **responses** - 85 conversational responses across 13 intent types
- **intent_patterns** - 173 pattern matches for intent detection
- **conversation_logs** - Chat history for analytics
- **users** - Admin user accounts

### Response Distribution:
```
Flirt: 9        Gratitude: 9    Sadness: 9
Anger: 9        Joke: 9         Small Talk: 9
Help: 9         Love: 3         Compliment: 3
Farewell: 3     Excitement: 3   Boredom: 3
Confusion: 3
```

---

## 🛠️ Technical Stack

- **Frontend**: HTML, CSS (Tailwind), JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Server**: Apache (XAMPP)
- **Icons**: Font Awesome 6
- **Alerts**: SweetAlert2

---

## 📁 Project Structure

```
chatbot_system/
├── index_chatbot.php      # Main chatbot interface
├── chatbot_ai.php         # Backend API endpoints
├── db_config.php          # Database configuration
├── admin.php              # Admin dashboard
├── professors.php         # Professor management
├── students.php           # Student management
├── chatbot.sql            # Database schema & seed data
├── Images/                # Uploaded images
├── includes/              # Shared components
└── schedules/             # Schedule files

Documentation:
├── README.md              # This file
├── DEPLOYMENT_COMPLETE.md # Deployment summary
├── ENHANCED_AI_FEATURES.md # AI feature documentation
└── QUICK_TEST_GUIDE.md    # Testing guide
```

---

## 🎯 Intent Detection System

### Visual Badges:
The chatbot shows color-coded badges for detected intents:

- 💖 **Love** - Pink badge
- ✨ **Compliment** - Yellow badge
- 👋 **Farewell** - Blue badge
- 🎉 **Excitement** - Orange badge
- 😘 **Flirt** - Purple badge
- 🙏 **Gratitude** - Green badge
- 😢 **Sadness** - Gray badge
- 😠 **Anger** - Red badge
- 😴 **Boredom** - Indigo badge
- 😂 **Joke** - Teal badge
- 💬 **Small Talk** - Cyan badge
- ❓ **Help** - Blue badge
- 🤔 **Confusion** - Amber badge

### Matching Algorithm:
- **Exact Match**: +20 points
- **Partial Match**: +10 points
- **Priority Multiplier**: 0.6x - 1.0x
- Highest score wins!

---

## 🔧 Admin Functions

Access admin panel at: `http://localhost/chatbot_system/admin.php`

Features:
- Add/Edit/Delete Professors
- Manage Class Schedules
- View Conversation Logs
- User Management
- File Upload Support

---

## 🔍 Debugging

Press **F12** in browser to see console logs:
- 🎯 Intent detection
- 📊 Context updates
- 👤 Name detection
- 💬 Response fetching
- ⚡ Intensity markers

---

## 📱 Mobile Support

Fully responsive design tested on:
- Desktop (1920x1080+)
- Laptop (1366x768)
- Tablet (768x1024)
- Mobile (375x667)

---

## 🆘 Troubleshooting

**Chatbot not responding?**
- ✓ Ensure XAMPP Apache & MySQL are running
- ✓ Check `db_config.php` for correct credentials
- ✓ Verify database tables exist (`chatbot` database)

**Intent not detected?**
- ✓ Check browser console (F12) for errors
- ✓ Verify `responses` table has 85 records
- ✓ Test with examples from QUICK_TEST_GUIDE.md

**Network access not working?**
- ✓ Check Windows Firewall settings
- ✓ Ensure devices on same WiFi network
- ✓ Verify Apache is listening on 0.0.0.0

---

## 📈 System Statistics

**Database Content:**
- Total Responses: 85
- Intent Patterns: 173
- Intent Types: 13
- Professors: 2 (sample data)
- Schedules: 4 (sample data)

**Performance:**
- Average response time: <800ms
- Database queries optimized
- Async operations for smooth UI
- Lightweight assets (~2MB)

---

## 📖 Documentation

For detailed information, see:
1. **ENHANCED_AI_FEATURES.md** - Complete AI capabilities
2. **QUICK_TEST_GUIDE.md** - Testing examples
3. **DEPLOYMENT_COMPLETE.md** - Full system details

---

## 🔒 Security

- Prepared SQL statements prevent injection
- Session-based authentication
- Password hashing for admin accounts
- XSS protection on user inputs
- File upload validation

---

## 🚀 Future Enhancements

- Voice input/output
- Multi-language support (Spanish, French)
- Dark mode toggle
- Mobile app version
- Learning from user feedback
- Advanced analytics dashboard

---

**Version**: 3.0 - Deep Understanding Edition  
**Status**: ✅ Production Ready  
**Last Updated**: January 28, 2025  
**Server**: XAMPP (Apache + MySQL)  
**Network IP**: 192.168.254.103

---

🎉 **Ready to use! Open the chatbot and start chatting!** 🚀
