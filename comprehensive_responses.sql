-- Comprehensive Responses for All Intents
-- Generated: 2025-01-28

USE chatbot;

-- ============================================
-- EMOTIONAL INTENTS RESPONSES
-- ============================================

-- Happiness
INSERT INTO responses (intent, sentiment, response_text) VALUES
('happiness', 'positive', 'I am so happy to hear that! Your happiness is contagious! 😊'),
('happiness', 'positive', 'That is wonderful! Keep spreading those good vibes! ✨'),
('happiness', 'positive', 'Your joy makes my day brighter! Stay happy! 🌟'),
('happiness', 'neutral', 'Happiness looks good on you! 😊'),
('happiness', 'neutral', 'I love seeing you happy! Keep smiling! 😄');

-- Fear
INSERT INTO responses (intent, sentiment, response_text) VALUES
('fear', 'negative', 'I understand you are scared. Remember, courage is not the absence of fear. You got this! 💪'),
('fear', 'negative', 'It is okay to feel afraid. Take a deep breath. I am here with you. 🤗'),
('fear', 'neutral', 'Fear is natural. Face it one step at a time. You are stronger than you think!'),
('fear', 'positive', 'You can overcome this! I believe in you! 🌟');

-- Heartbreak
INSERT INTO responses (intent, sentiment, response_text) VALUES
('heartbreak', 'negative', 'I am so sorry you are going through this. Heartbreak hurts, but you will heal. 💔→💗'),
('heartbreak', 'negative', 'Your heart will mend. Give yourself time and be kind to yourself. 🤗'),
('heartbreak', 'neutral', 'This too shall pass. You are stronger than you know. 💪'),
('heartbreak', 'neutral', 'Healing takes time. I am here for you every step of the way. 💙');

-- Motivation
INSERT INTO responses (intent, sentiment, response_text) VALUES
('motivation', 'neutral', 'Do not give up! Every expert was once a beginner. Keep pushing! 🚀'),
('motivation', 'neutral', 'You are capable of amazing things! Believe in yourself! ✨'),
('motivation', 'positive', 'Yes! That is the spirit! Go for it! 🔥'),
('motivation', 'negative', 'I know it is tough, but you are tougher. One step at a time! 💪');

-- Comfort
INSERT INTO responses (intent, sentiment, response_text) VALUES
('comfort', 'negative', 'I am here for you. Sending you a virtual hug! 🤗'),
('comfort', 'negative', 'You are not alone. I am here to listen and support you. 💙'),
('comfort', 'neutral', 'Everything will be okay. Take your time. 🌟'),
('comfort', 'neutral', 'I wish I could give you a real hug. Hang in there! 🫂');

-- Apology
INSERT INTO responses (intent, sentiment, response_text) VALUES
('apology', 'neutral', 'It is okay! No worries at all! 😊'),
('apology', 'neutral', 'Apology accepted! We all make mistakes. 🤗'),
('apology', 'positive', 'No need to apologize! You are doing great! ✨'),
('apology', 'negative', 'It is alright. What matters is you acknowledged it. 💙');

-- Loneliness
INSERT INTO responses (intent, sentiment, response_text) VALUES
('loneliness', 'negative', 'You are not alone. I am here with you. Want to talk? 💙'),
('loneliness', 'negative', 'Feeling lonely is hard, but remember you matter. I am here! 🤗'),
('loneliness', 'neutral', 'I am here to keep you company anytime! 😊'),
('loneliness', 'neutral', 'You always have a friend in me! Let us chat! 💬');

-- ============================================
-- SOCIAL INTENTS RESPONSES  
-- ============================================

-- Greeting
INSERT INTO responses (intent, sentiment, response_text) VALUES
('greeting', 'positive', 'Hello! Great to see you! How can I help today? 😊'),
('greeting', 'positive', 'Hi there! Welcome back! What can I do for you? 👋'),
('greeting', 'neutral', 'Hello! How are you doing today? 😊'),
('greeting', 'neutral', 'Hey! Nice to hear from you! What is up? 👋');

-- Goodbye
INSERT INTO responses (intent, sentiment, response_text) VALUES
('goodbye', 'neutral', 'Goodbye! Take care and talk to you soon! 👋'),
('goodbye', 'neutral', 'See you later! Have a great day! 😊'),
('goodbye', 'positive', 'Bye! Come back anytime! Miss you already! 💙'),
('goodbye', 'positive', 'See you! Stay awesome! ✨');

-- Small Talk  
INSERT INTO responses (intent, sentiment, response_text) VALUES
('small_talk', 'neutral', 'I am doing great, thanks for asking! How about you? 😊'),
('small_talk', 'neutral', 'All good here! Ready to chat with you! 💬'),
('small_talk', 'positive', 'I am fantastic! Thanks for checking in! How are you? 🌟');

-- Joke
INSERT INTO responses (intent, sentiment, response_text) VALUES
('joke', 'positive', 'Why did the student eat his homework? Because the teacher said it was a piece of cake! 😂'),
('joke', 'positive', 'What do you call a bear with no teeth? A gummy bear! 🐻'),
('joke', 'positive', 'Why do not scientists trust atoms? Because they make up everything! ⚛️'),
('joke', 'neutral', 'What did one wall say to the other? I will meet you at the corner! 😄'),
('joke', 'neutral', 'Why did the computer go to the doctor? It had a virus! 💻');

-- ============================================
-- FUNCTIONAL INTENTS RESPONSES
-- ============================================

-- Question
INSERT INTO responses (intent, sentiment, response_text) VALUES
('question', 'neutral', 'That is a great question! Let me help you find the answer. 🤔'),
('question', 'neutral', 'Interesting question! What specifically would you like to know? 💡'),
('question', 'neutral', 'I am here to help! Could you give me more details? 😊');

-- Confirm
INSERT INTO responses (intent, sentiment, response_text) VALUES
('confirm', 'positive', 'Great! Let us proceed then! ✅'),
('confirm', 'positive', 'Awesome! I am on it! 🚀'),
('confirm', 'neutral', 'Okay, understood! 👍'),
('confirm', 'neutral', 'Got it! Proceeding now! ✓');

-- Deny
INSERT INTO responses (intent, sentiment, response_text) VALUES
('deny', 'neutral', 'No problem! Let me know if you change your mind. 😊'),
('deny', 'neutral', 'Okay, I understand. Is there anything else I can help with? 💙'),
('deny', 'neutral', 'Alright, no worries! 👍');

-- Cancel
INSERT INTO responses (intent, sentiment, response_text) VALUES
('cancel', 'neutral', 'Operation cancelled. Anything else I can help with? 🔄'),
('cancel', 'neutral', 'Okay, cancelled! Let me know if you need anything. 😊'),
('cancel', 'neutral', 'No problem! Cancelled. Ready when you are! ✓');

-- Schedule
INSERT INTO responses (intent, sentiment, response_text) VALUES
('schedule', 'neutral', 'Let me check the schedules for you! 📅'),
('schedule', 'neutral', 'Looking up the schedule information now! ⏰'),
('schedule', 'positive', 'Sure! I will get the schedule details for you! 📋');

-- ============================================
-- ENTERTAINMENT INTENTS RESPONSES
-- ============================================

-- Music
INSERT INTO responses (intent, sentiment, response_text) VALUES
('music', 'positive', 'Music is life! What genre are you into? 🎵'),
('music', 'positive', 'I love music too! Tell me your favorite artist! 🎶'),
('music', 'neutral', 'Music makes everything better! What do you want to hear? 🎧');

-- Movies
INSERT INTO responses (intent, sentiment, response_text) VALUES
('movies', 'positive', 'Movies are awesome! What is your favorite genre? 🎬'),
('movies', 'positive', 'I love movies! Action, romance, or comedy? 🍿'),
('movies', 'neutral', 'Movie time! What are you in the mood for? 🎥');

-- Gaming
INSERT INTO responses (intent, sentiment, response_text) VALUES
('gaming', 'positive', 'Gaming is fun! What games do you play? 🎮'),
('gaming', 'positive', 'Awesome! Are you more into mobile or PC games? 🕹️'),
('gaming', 'neutral', 'Cool! I would love to hear about your favorite games! 🎯');

-- Meme
INSERT INTO responses (intent, sentiment, response_text) VALUES
('meme', 'positive', 'Memes make the world go round! 😂'),
('meme', 'positive', 'I love a good meme! Share with me! 🤣'),
('meme', 'neutral', 'Memes are the best! What is trending? 😄');

-- Random Fact
INSERT INTO responses (intent, sentiment, response_text) VALUES
('random_fact', 'neutral', 'Did you know? Honey never spoils! Archaeologists found 3000-year-old honey in Egyptian tombs that was still edible! 🍯'),
('random_fact', 'neutral', 'Fun fact: A group of flamingos is called a flamboyance! 🦩'),
('random_fact', 'positive', 'Here is something cool: Octopuses have three hearts! 🐙'),
('random_fact', 'neutral', 'Interesting fact: Bananas are berries, but strawberries are not! 🍌');

-- Quotes
INSERT INTO responses (intent, sentiment, response_text) VALUES
('quotes', 'positive', 'The only way to do great work is to love what you do. - Steve Jobs ✨'),
('quotes', 'positive', 'Believe you can and you are halfway there. - Theodore Roosevelt 🌟'),
('quotes', 'neutral', 'In the middle of difficulty lies opportunity. - Albert Einstein 💡'),
('quotes', 'neutral', 'Success is not final, failure is not fatal. - Winston Churchill 🚀');

-- Pickup Line
INSERT INTO responses (intent, sentiment, response_text) VALUES
('pickup_line', 'positive', 'Are you a magician? Because whenever I look at you, everyone else disappears! ✨'),
('pickup_line', 'positive', 'Do you have a map? I keep getting lost in your eyes! 😍'),
('pickup_line', 'neutral', 'Is your name Google? Because you have everything I have been searching for! 🔍'),
('pickup_line', 'neutral', 'Are you a camera? Because every time I look at you, I smile! 📸');

-- ============================================
-- RELATIONSHIP INTENTS RESPONSES
-- ============================================

-- Missing Someone
INSERT INTO responses (intent, sentiment, response_text) VALUES
('missing_someone', 'negative', 'I can feel how much you miss them. Distance makes the heart grow fonder! 💙'),
('missing_someone', 'negative', 'Missing someone special is hard. They must be lucky to have you! 💕'),
('missing_someone', 'neutral', 'Absence makes the heart grow fonder. Hang in there! 🤗'),
('missing_someone', 'neutral', 'I hope you get to see them soon! Stay strong! 💪');

-- Sweet Talk
INSERT INTO responses (intent, sentiment, response_text) VALUES
('sweet_talk', 'positive', 'Aww, that is so sweet! You have a beautiful heart! 💕'),
('sweet_talk', 'positive', 'You are such a sweetheart! Keep being amazing! 🥰'),
('sweet_talk', 'neutral', 'That is really sweet of you! 😊'),
('sweet_talk', 'neutral', 'You have such a kind way with words! 💙');

-- Breakup
INSERT INTO responses (intent, sentiment, response_text) VALUES
('breakup', 'negative', 'I am sorry you are going through this. You deserve happiness. 💔→💗'),
('breakup', 'negative', 'Breakups are tough. Give yourself time to heal. I am here. 🤗'),
('breakup', 'neutral', 'You will get through this. Better days are ahead! 🌈'),
('breakup', 'neutral', 'Sometimes endings are new beginnings. Stay strong! 💪');

-- Caring
INSERT INTO responses (intent, sentiment, response_text) VALUES
('caring', 'positive', 'Thank you for caring! You too, take care! 💙'),
('caring', 'positive', 'That is so thoughtful! Stay safe and healthy! 🤗'),
('caring', 'neutral', 'I appreciate it! You take care as well! 😊'),
('caring', 'neutral', 'Thanks! Remember to rest too! 🌟');

-- ============================================
-- SYSTEM / BOT INTENTS RESPONSES
-- ============================================

-- About Bot
INSERT INTO responses (intent, sentiment, response_text) VALUES
('about_bot', 'neutral', 'I am your friendly AI chatbot! I am here to help with professor info and have great conversations! 🤖'),
('about_bot', 'neutral', 'I am an AI assistant designed to help students find professor information and chat naturally! 😊'),
('about_bot', 'positive', 'I am your AI companion! I can help with academics and be your chat buddy! 💙');

-- Bot Name Query
INSERT INTO responses (intent, sentiment, response_text) VALUES
('bot_name_query', 'neutral', 'You can call me Professor Bot! But I am also your friend! 😊'),
('bot_name_query', 'neutral', 'I am your AI Assistant! What would you like to call me? 🤖'),
('bot_name_query', 'positive', 'I am here to help! You can call me whatever you like! 💙');

-- Bot Capability
INSERT INTO responses (intent, sentiment, response_text) VALUES
('bot_capability', 'neutral', 'I can help you find professor information, schedules, and have meaningful conversations! 💡'),
('bot_capability', 'neutral', 'I can answer academic questions and chat about life! I am here for you! 😊'),
('bot_capability', 'positive', 'I can do many things! Search professors, check schedules, and be your chat companion! 🌟');

-- Bot Thank
INSERT INTO responses (intent, sentiment, response_text) VALUES
('bot_thank', 'positive', 'You are very welcome! Happy to help anytime! 😊'),
('bot_thank', 'positive', 'My pleasure! That is what I am here for! 💙'),
('bot_thank', 'neutral', 'No problem at all! Glad I could help! ✨');

SELECT 'Responses loaded successfully!' as status, COUNT(*) as total_responses FROM responses;
