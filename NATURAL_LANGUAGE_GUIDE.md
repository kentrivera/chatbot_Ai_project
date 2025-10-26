# 🧠 Natural Language Understanding Guide

## 📖 Overview
This chatbot uses advanced pattern matching and natural language processing to understand various ways users ask questions. This guide shows all supported query patterns and how they work.

## 🎯 Query Categories

### 1. 📅 Schedule Queries

#### Show All Schedules
**Patterns:**
- "show all schedules"
- "view schedules"
- "list schedules"
- "see all schedules"
- "display schedules"
- "schedules" (single word)
- "what are the schedules"
- "all class schedules"

**Example Response:**
```
🗓️ Showing 15 schedule entries:
[Displays all schedules grouped by professor with file attachments]
```

---

#### Show Specific Professor's Schedule
**Patterns:**
- "schedule of [professor name]"
- "schedule for [professor name]"
- "[professor name]'s schedule"
- "[professor name] schedule"
- "show [professor name] schedule"
- "view [professor name] schedule"
- "when does [professor name] teach"
- "[professor name] class time"
- "[professor name] teaching schedule"
- "what does [professor name] teach"
- "display [professor name] schedule"

**Examples:**
```
✅ "schedule of Dr. Adrada"
✅ "show smith's schedule"
✅ "when does johnson teach"
✅ "garcia teaching schedule"
✅ "what does lee teach"
✅ "view professor brown schedule"
```

**Smart Processing:**
- Automatically removes: "show", "view", "display", "schedule", "professor"
- Handles possessive: "Smith's" → "Smith"
- Filters noise words: "the", "a", "an", "all"
- Minimum 3 characters required

---

### 2. 👨‍🏫 Professor Queries

#### List All Professors
**Patterns:**
- "show all professors"
- "list professors"
- "view professors"
- "see all professors"
- "display professors"
- "professors" (single word)
- "who are the professors"
- "get all professors"
- "show teachers"
- "list faculty"
- "show instructors"

**Example Response:**
```
📚 Found 10 professor(s) in database:
[Displays professor cards with photos, expertise, and schedules]
```

---

#### Search Professor by Name
**Patterns:**
- Just type the professor's name (partial match supported)
- "Dr. [name]"
- "Professor [name]"
- "[first/last name]"

**Examples:**
```
✅ "Smith"
✅ "Dr. Garcia"
✅ "Professor Lee"
✅ "John" (finds all Johns)
```

---

### 3. 📚 Subject/Course Queries

#### Find Who Teaches a Subject
**Patterns:**
- "who teaches [subject]"
- "who is teaching [subject]"
- "teacher for [subject]"
- "professor for [subject]"
- "instructor for [subject]"
- "[subject] teacher"
- "[subject] professor"
- "find [subject] teacher"

**Examples:**
```
✅ "who teaches Algorithms"
✅ "who is teaching Database"
✅ "teacher for AI"
✅ "Computer Science professor"
✅ "Data Structures teacher"
```

**Example Response:**
```
📖 Found 2 professor(s) teaching "Algorithms":
[Displays matching professors with their full details]
```

---

### 4. 🎓 Expertise Queries

#### Find Experts in a Field
**Patterns:**
- "expert in [field]"
- "specialize in [field]"
- "specialization in [field]"
- "who knows [field]"
- "expertise in [field]"

**Examples:**
```
✅ "expert in database"
✅ "specialize in machine learning"
✅ "who knows AI"
✅ "expertise in web development"
```

**Example Response:**
```
🎓 Found 3 expert(s) in "database":
[Shows professors with matching expertise areas]
```

---

### 5. 💬 Conversational/Emotional Queries

#### Gratitude
**Patterns:**
- "thank you"
- "thanks"
- "ty"
- "thank you so much"

**Response:**
```
You're welcome! 😊 Glad I could help!
```

---

#### Greetings
**Patterns:**
- "hello"
- "hi"
- "hey"
- "good morning"
- "good afternoon"

**Response:**
```
Hello! 👋 How can I help you today?
```

---

#### Sadness/Support
**Patterns:**
- "I'm sad"
- "feeling down"
- "I'm upset"
- "having a bad day"

**Response:**
```
I'm sorry to hear that. 😔 Remember, tough times don't last!
Is there anything I can help you with?
```

---

#### Jokes
**Patterns:**
- "tell me a joke"
- "make me laugh"
- "say something funny"
- "joke"

**Response:**
```
😂 [Random joke from database]
```

---

#### Help/Confusion
**Patterns:**
- "help"
- "what can you do"
- "how do I use this"
- "I'm confused"

**Response:**
```
💡 I can help you:
- Find professors and their schedules
- Search by subject or expertise
- View schedule files (PDF, DOC, etc.)
- Answer emotional/conversational queries
```

---

## 🔍 Pattern Matching Logic

### How It Works:

1. **Input Normalization**
   ```
   User: "SHOW DR. SMITH'S SCHEDULE"
   ↓
   Normalized: "show dr smith's schedule"
   ```

2. **Pattern Matching**
   ```
   Check against regex patterns:
   /(.+)['']s?\s+schedule/i
   ↓
   Match found: "dr smith"
   ```

3. **Name Cleaning**
   ```
   "dr smith" 
   ↓ Remove common words
   "smith"
   ```

4. **Database Query**
   ```
   SELECT * FROM professors 
   WHERE professor_name LIKE '%smith%'
   ```

5. **Response Generation**
   ```
   Found: Dr. John Smith
   ↓
   Display professor card with schedule + files
   ```

---

## 🎨 Response Types

### 1. Professor Cards
```
┌────────────────────────────────────┐
│ 👨‍🏫 Dr. John Smith                 │
│ Professor of Computer Science      │
│ Age: 45 | Experience: 15 years     │
├────────────────────────────────────┤
│ 💡 Expertise:                      │
│ [AI] [Machine Learning] [Database] │
├────────────────────────────────────┤
│ 📅 Schedule:                       │
│ Mon 10-11AM: Algorithms (CS-101)   │
│ 📄 Syllabus.pdf                    │
└────────────────────────────────────┘
```

### 2. Schedule Tables
```
┌────────────────────────────────────┐
│ Professor: Dr. Garcia              │
├────────────────────────────────────┤
│ Day | Time  | Room  | Subject      │
│ Mon | 10AM  | R101  | Database     │
│     | 📄 [DB_Syllabus.pdf]         │
│ Wed | 2PM   | R102  | AI Basics    │
│     | 📝 [AI_Notes.docx]           │
└────────────────────────────────────┘
```

### 3. List Responses
```
📚 Found 3 professors teaching "Database":
1. Dr. Smith - Plantilla IV
2. Prof. Garcia - Associate Professor
3. Dr. Lee - Assistant Professor
```

---

## 🧪 Testing Your Queries

### Quick Test Examples:

#### ✅ Schedule Queries
```
"show all schedules"           → Lists all schedules
"schedule of adrada"           → Dr. Adrada's schedule
"when does smith teach"        → Prof. Smith's schedule
"garcia's schedule"            → Prof. Garcia's schedule
```

#### ✅ Professor Queries
```
"show all professors"          → Lists all professors
"smith"                        → Finds Prof. Smith
"expert in AI"                 → Finds AI experts
```

#### ✅ Subject Queries
```
"who teaches Database"         → Professors teaching DB
"teacher for Algorithms"       → Algorithm instructors
"Computer Science professor"   → CS professors
```

#### ✅ Conversational
```
"thank you"                    → Gratitude response
"hello"                        → Greeting
"tell me a joke"               → Random joke
```

---

## 🚫 Common Mistakes to Avoid

### ❌ Too Short
```
"s"              → Too short (minimum 3 characters)
"ab"             → Too short
```

### ❌ Only Stop Words
```
"show the"       → Only common words
"view all"       → No specific target
```

### ✅ Good Queries
```
"smith"          → Valid name
"show adrada"    → Specific professor
"database"       → Valid subject
```

---

## 🔧 Advanced Features

### 1. Fuzzy Matching
- Handles typos: "Smth" might match "Smith"
- Partial names: "Gar" matches "Garcia"
- Case insensitive: "SMITH" = "smith" = "Smith"

### 2. Context Awareness
- Remembers recent queries
- Suggests related searches
- Tracks conversation flow

### 3. Multi-Intent Detection
- "Show Smith's database schedule"
  - Intent 1: Specific professor
  - Intent 2: Specific subject
  - Result: Smith's database classes

---

## 📊 Pattern Priority

When multiple patterns match, priority order:

1. **Exact Commands** (highest priority)
   - "show all schedules"
   - "list all professors"

2. **Specific Queries**
   - "schedule of Smith"
   - "who teaches Database"

3. **Name Search** (lowest priority)
   - "Smith"
   - "Garcia"

---

## 🎯 Best Practices

### For Best Results:

1. **Be Specific**
   ```
   ✅ "schedule of Dr. Adrada"
   ⚠️ "schedule" (too vague)
   ```

2. **Use Natural Language**
   ```
   ✅ "when does smith teach"
   ✅ "show me smith's classes"
   ✅ "what subjects does smith handle"
   ```

3. **Include Key Words**
   ```
   ✅ "schedule", "teach", "expert", "professor"
   ```

4. **Avoid Ambiguity**
   ```
   ✅ "John Smith schedule"
   ⚠️ "that professor" (unclear reference)
   ```

---

## 📱 Voice/Natural Queries

The system understands conversational queries:

```
"Hey, can you show me when Professor Garcia teaches?"
→ Extracts: "garcia" + "teach" → Shows schedule

"I need to find someone who knows about databases"
→ Extracts: "expert" + "databases" → Shows DB experts

"Who's teaching algorithms this semester?"
→ Extracts: "who teaches" + "algorithms" → Lists professors
```

---

## 🔄 Continuous Learning

The system improves with usage:
- Tracks successful query patterns
- Learns common phrasings
- Adapts to user language style
- Suggests alternatives for failed queries

---

## 📚 Related Documentation

- `SCHEDULE_FILES_FEATURE.md` - File attachment details
- `ENHANCED_AI_FEATURES.md` - AI capabilities
- `README.md` - General usage guide
- `QUICK_TEST_GUIDE.md` - Testing procedures

---

**Last Updated**: October 26, 2025
**Version**: 2.0
**Supported Languages**: English (with Filipino professor names)
