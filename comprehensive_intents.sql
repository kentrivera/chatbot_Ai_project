-- Comprehensive Intent Pattern Library
-- 100+ Intents with Keywords and Examples
-- Generated: 2025-01-28

USE chatbot;

-- Clear existing patterns (optional - comment out if you want to keep existing)
-- TRUNCATE TABLE intent_patterns;

-- ============================================
-- 💖 EMOTIONAL INTENTS
-- ============================================

-- Happiness
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('happiness', 'happy', 8),
('happiness', 'hapi', 8),
('happiness', 'joy', 8),
('happiness', 'joyful', 8),
('happiness', 'glad', 8),
('happiness', 'excited', 8),
('happiness', 'ecstatic', 8),
('happiness', 'thrilled', 8),
('happiness', 'delighted', 8),
('happiness', 'blessed', 8),
('happiness', 'grateful', 8),
('happiness', 'wonderful day', 8),
('happiness', 'best day ever', 8),
('happiness', 'feeling great', 8),
('happiness', 'masaya', 8),
('happiness', 'masaya ako', 8),
('happiness', 'yay', 8),
('happiness', '😊', 8),
('happiness', '😄', 8),
('happiness', '🥰', 8);

-- Sadness
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('sadness', 'sad', 9),
('sadness', 'unhappy', 9),
('sadness', 'depressed', 9),
('sadness', 'down', 9),
('sadness', 'blue', 9),
('sadness', 'upset', 9),
('sadness', 'crying', 9),
('sadness', 'tears', 9),
('sadness', 'heartbroken', 9),
('sadness', 'malungkot', 9),
('sadness', 'lungkot', 9),
('sadness', 'iyak', 9),
('sadness', 'feel bad', 9),
('sadness', 'feeling down', 9),
('sadness', 'not okay', 9),
('sadness', 'di okay', 9),
('sadness', '😢', 9),
('sadness', '😭', 9),
('sadness', '😔', 9),
('sadness', '💔', 9);

-- Anger
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('anger', 'angry', 8),
('anger', 'mad', 8),
('anger', 'furious', 8),
('anger', 'pissed', 8),
('anger', 'annoyed', 8),
('anger', 'irritated', 8),
('anger', 'frustrated', 8),
('anger', 'hate', 8),
('anger', 'galit', 8),
('anger', 'inis', 8),
('anger', 'badtrip', 8),
('anger', 'bad trip', 8),
('anger', 'naiinis', 8),
('anger', 'nakakainis', 8),
('anger', 'wtf', 8),
('anger', 'so annoying', 8),
('anger', '😠', 8),
('anger', '😡', 8),
('anger', '🤬', 8);

-- Fear
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('fear', 'scared', 8),
('fear', 'afraid', 8),
('fear', 'terrified', 8),
('fear', 'frightened', 8),
('fear', 'nervous', 8),
('fear', 'anxious', 8),
('fear', 'worried', 8),
('fear', 'panic', 8),
('fear', 'takot', 8),
('fear', 'nakakatakot', 8),
('fear', 'kinakabahan', 8),
('fear', 'kabado', 8),
('fear', 'nerbiyos', 8),
('fear', 'scary', 8),
('fear', 'freaked out', 8),
('fear', '😰', 8),
('fear', '😨', 8);

-- Heartbreak
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('heartbreak', 'heartbroken', 9),
('heartbreak', 'broken heart', 9),
('heartbreak', 'heart break', 9),
('heartbreak', 'broke up', 9),
('heartbreak', 'breakup', 9),
('heartbreak', 'dumped', 9),
('heartbreak', 'left me', 9),
('heartbreak', 'betrayed', 9),
('heartbreak', 'cheated', 9),
('heartbreak', 'iniwan', 9),
('heartbreak', 'niloko', 9),
('heartbreak', 'nasaktanako', 9),
('heartbreak', 'nasaktan', 9),
('heartbreak', 'painful', 9),
('heartbreak', 'hurts so much', 9),
('heartbreak', '💔', 9),
('heartbreak', '😭💔', 9);

-- Motivation
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('motivation', 'motivate me', 7),
('motivation', 'inspire me', 7),
('motivation', 'need motivation', 7),
('motivation', 'encourage', 7),
('motivation', 'give up', 7),
('motivation', 'quitting', 7),
('motivation', 'cant do it', 7),
('motivation', 'cant go on', 7),
('motivation', 'losing hope', 7),
('motivation', 'hopeless', 7),
('motivation', 'susuko na', 7),
('motivation', 'ayaw ko na', 7),
('motivation', 'pagod na', 7),
('motivation', 'tired of trying', 7),
('motivation', 'wala na', 7),
('motivation', 'push me', 7);

-- Comfort
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('comfort', 'comfort me', 8),
('comfort', 'need comfort', 8),
('comfort', 'hug', 8),
('comfort', 'hold me', 8),
('comfort', 'be there', 8),
('comfort', 'need someone', 8),
('comfort', 'alone', 8),
('comfort', 'lonely', 8),
('comfort', 'nag-iisa', 8),
('comfort', 'walang kasama', 8),
('comfort', 'yakap', 8),
('comfort', 'kailangan kita', 8),
('comfort', 'stay with me', 8),
('comfort', 'dont leave', 8),
('comfort', '🤗', 8);

-- Apology
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('apology', 'sorry', 8),
('apology', 'apologize', 8),
('apology', 'my bad', 8),
('apology', 'my fault', 8),
('apology', 'forgive me', 8),
('apology', 'pasensya', 8),
('apology', 'paumanhin', 8),
('apology', 'sori', 8),
('apology', 'patawad', 8),
('apology', 'sorry po', 8),
('apology', 'i apologize', 8),
('apology', 'didnt mean to', 8),
('apology', 'kasalanan ko', 8),
('apology', 'mali ko', 8),
('apology', '🙏', 8);

-- Loneliness
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('loneliness', 'lonely', 9),
('loneliness', 'alone', 9),
('loneliness', 'no one', 9),
('loneliness', 'nobody', 9),
('loneliness', 'isolated', 9),
('loneliness', 'walang kasama', 9),
('loneliness', 'mag-isa', 9),
('loneliness', 'nag-iisa', 9),
('loneliness', 'walang kaibigan', 9),
('loneliness', 'no friends', 9),
('loneliness', 'feel alone', 9),
('loneliness', 'all alone', 9),
('loneliness', 'no one cares', 9),
('loneliness', 'walang pake', 9);

-- ============================================
-- 💬 SOCIAL INTENTS
-- ============================================

-- Greeting
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('greeting', 'hello', 7),
('greeting', 'hi', 7),
('greeting', 'hey', 7),
('greeting', 'good morning', 7),
('greeting', 'good afternoon', 7),
('greeting', 'good evening', 7),
('greeting', 'gud morning', 7),
('greeting', 'morning', 7),
('greeting', 'kamusta', 7),
('greeting', 'kumusta', 7),
('greeting', 'musta', 7),
('greeting', 'whats up', 7),
('greeting', 'sup', 7),
('greeting', 'yo', 7),
('greeting', 'hola', 7),
('greeting', 'magandang umaga', 7),
('greeting', 'magandang hapon', 7),
('greeting', 'magandang gabi', 7),
('greeting', '👋', 7),
('greeting', 'hii', 7);

-- Goodbye
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('goodbye', 'goodbye', 9),
('goodbye', 'bye', 9),
('goodbye', 'see you', 9),
('goodbye', 'see ya', 9),
('goodbye', 'later', 9),
('goodbye', 'gotta go', 9),
('goodbye', 'gtg', 9),
('goodbye', 'paalam', 9),
('goodbye', 'aalis na', 9),
('goodbye', 'alis na', 9),
('goodbye', 'sige na', 9),
('goodbye', 'bye bye', 9),
('goodbye', 'byeee', 9),
('goodbye', 'talk later', 9),
('goodbye', 'catch you later', 9),
('goodbye', 'good night', 9),
('goodbye', 'tulog na', 9),
('goodbye', '👋', 9);

-- Gratitude
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('gratitude', 'thank you', 10),
('gratitude', 'thanks', 10),
('gratitude', 'thank u', 10),
('gratitude', 'thankyou', 10),
('gratitude', 'thx', 10),
('gratitude', 'ty', 10),
('gratitude', 'salamat', 10),
('gratitude', 'maraming salamat', 10),
('gratitude', 'appreciate', 10),
('gratitude', 'grateful', 10),
('gratitude', 'appreciate it', 10),
('gratitude', 'thanks a lot', 10),
('gratitude', 'tenkyu', 10),
('gratitude', 'tysm', 10),
('gratitude', '🙏', 10);

-- Compliment
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('compliment', 'awesome', 9),
('compliment', 'amazing', 9),
('compliment', 'great', 9),
('compliment', 'excellent', 9),
('compliment', 'wonderful', 9),
('compliment', 'fantastic', 9),
('compliment', 'brilliant', 9),
('compliment', 'youre the best', 9),
('compliment', 'you rock', 9),
('compliment', 'galing', 9),
('compliment', 'galing mo', 9),
('compliment', 'astig', 9),
('compliment', 'impressive', 9),
('compliment', 'well done', 9),
('compliment', 'good job', 9),
('compliment', '👏', 9),
('compliment', '✨', 9);

-- Joke
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('joke', 'joke', 8),
('joke', 'tell me a joke', 8),
('joke', 'make me laugh', 8),
('joke', 'funny', 8),
('joke', 'humor', 8),
('joke', 'haha', 8),
('joke', 'lol', 8),
('joke', 'lmao', 8),
('joke', 'rofl', 8),
('joke', 'patawa', 8),
('joke', 'nakakatawa', 8),
('joke', 'nakakaloka', 8),
('joke', 'something funny', 8),
('joke', '😂', 8),
('joke', '🤣', 8);

-- Small Talk
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('small_talk', 'how are you', 7),
('small_talk', 'hows it going', 7),
('small_talk', 'whats new', 7),
('small_talk', 'kumusta ka', 7),
('small_talk', 'ano meron', 7),
('small_talk', 'what you doing', 7),
('small_talk', 'whatcha doin', 7),
('small_talk', 'ano ginagawa mo', 7),
('small_talk', 'hows your day', 7),
('small_talk', 'howdy', 7),
('small_talk', 'anything new', 7);

-- ============================================
-- ⚙️ FUNCTIONAL INTENTS
-- ============================================

-- Help
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('help', 'help', 6),
('help', 'help me', 6),
('help', 'i need help', 6),
('help', 'assist', 6),
('help', 'support', 6),
('help', 'tulong', 6),
('help', 'tulungan mo ko', 6),
('help', 'patulong', 6),
('help', 'what can you do', 6),
('help', 'how do you work', 6),
('help', 'guide me', 6),
('help', 'stuck', 6),
('help', 'confused', 6);

-- Question
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('question', 'what is', 6),
('question', 'who is', 6),
('question', 'where is', 6),
('question', 'when is', 6),
('question', 'why', 6),
('question', 'how', 6),
('question', 'ano', 6),
('question', 'sino', 6),
('question', 'saan', 6),
('question', 'kailan', 6),
('question', 'bakit', 6),
('question', 'paano', 6);

-- Confirm
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('confirm', 'yes', 7),
('confirm', 'yeah', 7),
('confirm', 'yep', 7),
('confirm', 'yup', 7),
('confirm', 'sure', 7),
('confirm', 'okay', 7),
('confirm', 'ok', 7),
('confirm', 'oo', 7),
('confirm', 'opo', 7),
('confirm', 'sige', 7),
('confirm', 'alright', 7),
('confirm', 'go', 7),
('confirm', 'proceed', 7),
('confirm', '👍', 7),
('confirm', '✅', 7);

-- Deny
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('deny', 'no', 7),
('deny', 'nope', 7),
('deny', 'nah', 7),
('deny', 'hindi', 7),
('deny', 'ayaw', 7),
('deny', 'not really', 7),
('deny', 'i dont think so', 7),
('deny', 'disagree', 7),
('deny', 'never', 7),
('deny', 'wag', 7),
('deny', 'huwag', 7),
('deny', '❌', 7),
('deny', '🚫', 7);

-- Cancel
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('cancel', 'cancel', 8),
('cancel', 'stop', 8),
('cancel', 'abort', 8),
('cancel', 'nevermind', 8),
('cancel', 'forget it', 8),
('cancel', 'itigil', 8),
('cancel', 'tigil', 8),
('cancel', 'wag na', 8),
('cancel', 'undo', 8),
('cancel', 'back', 8);

-- Schedule
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('schedule', 'schedule', 7),
('schedule', 'sched', 7),
('schedule', 'calendar', 7),
('schedule', 'appointment', 7),
('schedule', 'time', 7),
('schedule', 'oras', 7),
('schedule', 'when', 7),
('schedule', 'kailan', 7),
('schedule', 'class schedule', 7),
('schedule', 'timetable', 7);

-- ============================================
-- 🎮 ENTERTAINMENT INTENTS
-- ============================================

-- Music
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('music', 'music', 7),
('music', 'song', 7),
('music', 'kanta', 7),
('music', 'tugtog', 7),
('music', 'play', 7),
('music', 'playlist', 7),
('music', 'artist', 7),
('music', 'band', 7),
('music', 'album', 7),
('music', 'listen', 7),
('music', '🎵', 7),
('music', '🎶', 7);

-- Movies
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('movies', 'movie', 7),
('movies', 'film', 7),
('movies', 'pelikula', 7),
('movies', 'cinema', 7),
('movies', 'watch', 7),
('movies', 'netflix', 7),
('movies', 'recommend movie', 7),
('movies', '🎬', 7),
('movies', '🍿', 7);

-- Gaming
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('gaming', 'game', 7),
('gaming', 'gaming', 7),
('gaming', 'laro', 7),
('gaming', 'play', 7),
('gaming', 'gamer', 7),
('gaming', 'mobile legends', 7),
('gaming', 'ml', 7),
('gaming', 'valorant', 7),
('gaming', 'cod', 7),
('gaming', 'minecraft', 7),
('gaming', '🎮', 7),
('gaming', '🕹️', 7);

-- Meme
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('meme', 'meme', 7),
('meme', 'funny picture', 7),
('meme', 'viral', 7),
('meme', 'trending', 7),
('meme', 'lol', 7),
('meme', 'nakakatawa', 7),
('meme', 'patawa', 7);

-- Random Fact
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('random_fact', 'fact', 7),
('random_fact', 'did you know', 7),
('random_fact', 'trivia', 7),
('random_fact', 'random fact', 7),
('random_fact', 'tell me something', 7),
('random_fact', 'interesting', 7),
('random_fact', 'fun fact', 7);

-- Quotes
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('quotes', 'quote', 7),
('quotes', 'quotes', 7),
('quotes', 'inspire', 7),
('quotes', 'motivational quote', 7),
('quotes', 'wisdom', 7),
('quotes', 'saying', 7);

-- Pickup Line
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('pickup_line', 'pickup line', 8),
('pickup_line', 'pick up line', 8),
('pickup_line', 'banat', 8),
('pickup_line', 'flirt line', 8),
('pickup_line', 'pang akit', 8),
('pickup_line', 'romantic line', 8);

-- ============================================
-- 💞 RELATIONSHIP INTENTS
-- ============================================

-- Love
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('love', 'i love you', 10),
('love', 'ily', 10),
('love', 'love you', 10),
('love', 'luv u', 10),
('love', 'mahal kita', 10),
('love', 'mahal na mahal', 10),
('love', 'in love', 10),
('love', 'adore', 10),
('love', 'my love', 10),
('love', 'love of my life', 10),
('love', '❤️', 10),
('love', '💕', 10),
('love', '😍', 10),
('love', '🥰', 10);

-- Flirt
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('flirt', 'cute', 8),
('flirt', 'beautiful', 8),
('flirt', 'handsome', 8),
('flirt', 'pretty', 8),
('flirt', 'hot', 8),
('flirt', 'sexy', 8),
('flirt', 'gorgeous', 8),
('flirt', 'ganda', 8),
('flirt', 'pogi', 8),
('flirt', 'crush', 8),
('flirt', 'crush kita', 8),
('flirt', 'babe', 8),
('flirt', 'bb', 8),
('flirt', 'sweetie', 8),
('flirt', 'darling', 8),
('flirt', 'mwah', 8),
('flirt', '😘', 8),
('flirt', '😍', 8),
('flirt', '🥰', 8);

-- Missing Someone
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('missing_someone', 'miss you', 9),
('missing_someone', 'miss u', 9),
('missing_someone', 'i miss', 9),
('missing_someone', 'miss na kita', 9),
('missing_someone', 'namimiss', 9),
('missing_someone', 'namimiss kita', 9),
('missing_someone', 'long distance', 9),
('missing_someone', 'away', 9),
('missing_someone', 'come back', 9),
('missing_someone', 'wanna see you', 9);

-- Sweet Talk
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('sweet_talk', 'baby', 8),
('sweet_talk', 'honey', 8),
('sweet_talk', 'sweetheart', 8),
('sweet_talk', 'my world', 8),
('sweet_talk', 'my everything', 8),
('sweet_talk', 'special', 8),
('sweet_talk', 'mahal', 8),
('sweet_talk', 'sinta', 8),
('sweet_talk', 'precious', 8);

-- Breakup
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('breakup', 'break up', 9),
('breakup', 'broke up', 9),
('breakup', 'its over', 9),
('breakup', 'we are done', 9),
('breakup', 'end this', 9),
('breakup', 'hiwalay', 9),
('breakup', 'tapos na', 9),
('breakup', 'leave me', 9),
('breakup', 'iniwan', 9);

-- Caring
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('caring', 'take care', 8),
('caring', 'be safe', 8),
('caring', 'stay safe', 8),
('caring', 'ingat', 8),
('caring', 'mag-ingat', 8),
('caring', 'kain na', 8),
('caring', 'eat well', 8),
('caring', 'rest', 8),
('caring', 'pahinga', 8),
('caring', 'tulog na', 8),
('caring', 'sleep', 8);

-- ============================================
-- 🤖 SYSTEM / BOT INTENTS
-- ============================================

-- About Bot
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('about_bot', 'who are you', 6),
('about_bot', 'what are you', 6),
('about_bot', 'tell me about yourself', 6),
('about_bot', 'about you', 6),
('about_bot', 'sino ka', 6),
('about_bot', 'ano ka', 6);

-- Bot Name Query
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('bot_name_query', 'your name', 6),
('bot_name_query', 'whats your name', 6),
('bot_name_query', 'name mo', 6),
('bot_name_query', 'pangalan mo', 6),
('bot_name_query', 'what should i call you', 6);

-- Bot Capability
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('bot_capability', 'what can you do', 6),
('bot_capability', 'your skills', 6),
('bot_capability', 'capabilities', 6),
('bot_capability', 'ano kaya mo', 6),
('bot_capability', 'features', 6);

-- Bot Thank
INSERT INTO intent_patterns (intent, pattern, weight) VALUES
('bot_thank', 'youre welcome', 8),
('bot_thank', 'your welcome', 8),
('bot_thank', 'no problem', 8),
('bot_thank', 'walang anuman', 8),
('bot_thank', 'okay lang', 8),
('bot_thank', 'dont mention it', 8);

SELECT 'Intent patterns loaded successfully!' as status;
