# 📎 Schedule Files & Enhanced Natural Language Understanding

## 🎯 Overview
The chatbot system now supports displaying attached files in schedule responses and has enhanced natural language understanding for professor schedule queries.

## ✨ New Features

### 1. **Schedule File Attachments Display**
- **Automatic File Detection**: System checks if schedule entries have attached files
- **Visual File Indicators**: Files displayed with appropriate icons based on type
- **Direct File Access**: Click to view/download files in a new tab
- **Supported File Types**:
  - 📄 PDF documents
  - 📝 Word documents (DOC, DOCX)
  - 📊 Excel spreadsheets (XLS, XLSX)
  - 📈 PowerPoint presentations (PPT, PPTX)
  - 🖼️ Images (JPG, PNG, GIF)
  - 📋 Text files (TXT)
  - 📦 Archives (ZIP, RAR)

### 2. **Enhanced Natural Language Understanding for Schedules**

#### Supported Query Patterns:
```
✅ "Schedule of Dr. Smith"
✅ "Show Adrada's schedule"
✅ "View professor Lee schedule"
✅ "When does Johnson teach?"
✅ "What does Brown teach?"
✅ "Display Garcia's schedule"
✅ "professor name schedule"
✅ "professor name teaching schedule"
✅ "professor name subjects"
```

#### Smart Name Extraction:
- Automatically removes common words: "show", "view", "display", "schedule", etc.
- Handles possessive forms: "Smith's" → "Smith"
- Filters out noise words: "the", "a", "an", "all"
- Validates name length (minimum 3 characters)

### 3. **File Display in Responses**

#### Professor Schedule Cards:
```
┌────────────────────────────────────────┐
│ Professor Name                         │
│ ┌──────────────────────────────────┐  │
│ │ Subject | Day | Time | Room | File│  │
│ ├──────────────────────────────────┤  │
│ │ CS101   | Mon | 10AM | R101 | 📄 │  │
│ │ [Syllabus.pdf] (clickable)       │  │
│ └──────────────────────────────────┘  │
└────────────────────────────────────────┘
```

#### Schedule Listing Cards:
```
┌────────────────────────────────────────┐
│ 📅 All Schedules                       │
│ ┌──────────────────────────────────┐  │
│ │ Day | Time | Room | Subject | File│  │
│ ├──────────────────────────────────┤  │
│ │ Mon | 10AM | R101 | CS101   | 📄 │  │
│ │ [Click to view PDF]              │  │
│ └──────────────────────────────────┘  │
└────────────────────────────────────────┘
```

## 🔧 Technical Implementation

### Backend Changes (`chatbot_ai.php`)

#### 1. Enhanced `getSchedulesForProfessor()` Function:
```php
function getSchedulesForProfessor(mysqli $conn, int $professorId): array {
    // Includes schedule file information
    // Checks if file exists on filesystem
    // Adds metadata: has_file, file_name, file_extension
}
```

#### 2. Enhanced `getSchedulesBySubject()` Function:
```php
function getSchedulesBySubject(mysqli $conn, string $subject): array {
    // Returns schedules with file information
    // Validates file existence
    // Provides file type metadata
}
```

#### 3. Enhanced `list_schedules` Action:
```php
// Retrieves schedule ID for file access
// Checks file existence on server
// Returns complete file metadata
```

### Frontend Changes (`index_chatbot.php`)

#### 1. New `getFileIcon()` Helper Function:
```javascript
function getFileIcon(extension) {
    // Maps file extensions to Font Awesome icons
    // Supports 15+ file types
    // Returns appropriate icon class
}
```

#### 2. Enhanced `displayProfessorCard()` Function:
```javascript
// Renders file download buttons
// Shows file type icons
// Provides "No file" indicator when applicable
// Opens files in new tab on click
```

#### 3. Enhanced `displaySchedules()` Function:
```javascript
// Groups schedules by professor
// Displays file attachments in table
// Includes hover effects and transitions
```

#### 4. Enhanced Query Pattern Matching:
```javascript
scheduleForProfessor: [
    /schedule\s+(of|for)\s+(.+)/i,
    /(.+)['']s?\s+schedule/i,
    /show\s+(.+)\s+schedule/i,
    /when\s+does\s+(.+)\s+teach/i,
    /(.+)\s+teaching\s+schedule/i,
    /what\s+does\s+(.+)\s+teach/i,
    // ... more patterns
]
```

## 📊 Database Schema

### Schedules Table:
```sql
CREATE TABLE schedules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    professor_id INT,
    subject VARCHAR(255),
    day VARCHAR(50),
    time VARCHAR(50),
    room VARCHAR(50),
    schedule_file VARCHAR(255),  -- File path
    FOREIGN KEY (professor_id) REFERENCES professors(professor_id)
);
```

## 🎨 UI/UX Enhancements

### File Button Styling:
- **Blue background** for visibility
- **Hover effects** with shadow transitions
- **Icon + filename** display
- **"No file"** gray text when absent
- **Responsive design** for mobile/desktop

### File Icons:
- 📄 PDF - Red file icon
- 📝 Word - Blue file icon
- 📊 Excel - Green file icon
- 📈 PowerPoint - Orange file icon
- 🖼️ Images - Yellow file icon
- 📋 Text - Gray file icon
- 📦 Archives - Brown file icon

## 🔍 Usage Examples

### Example 1: View Professor Schedule with Files
**User**: "Show Dr. Adrada's schedule"

**Bot Response**:
```
📅 Schedule for "Dr. Adrada":

┌──────────────────────────────────────────┐
│ Dr. John Adrada                          │
│ Professor of Computer Science            │
├──────────────────────────────────────────┤
│ Subject      | Day  | Time    | Room    │
│ Algorithms   | Mon  | 10-11AM | CS-101  │
│ 📄 Syllabus_Algorithms.pdf               │
├──────────────────────────────────────────┤
│ Database     | Wed  | 2-3PM   | CS-102  │
│ 📄 DB_Schedule.pdf                       │
└──────────────────────────────────────────┘
```

### Example 2: View All Schedules
**User**: "Show all schedules"

**Bot Response**:
```
🗓️ Showing 15 schedule entries:

[Lists all professors with their schedules, each
 with file attachments displayed as clickable buttons]
```

### Example 3: Natural Language Variations
All these queries work:
- "schedule of adrada"
- "adrada schedule"
- "show adrada's schedule"
- "view professor adrada schedule"
- "when does adrada teach"
- "what does adrada teach"
- "adrada teaching schedule"

## 🚀 Benefits

### For Users:
1. **Direct File Access** - One-click to view syllabi, schedules, materials
2. **Visual Clarity** - Icons make file types immediately recognizable
3. **Natural Queries** - Ask in your own words
4. **Complete Information** - Schedule + files in one view

### For Administrators:
1. **Better Organization** - All schedule materials in database
2. **Easy Updates** - Files linked to schedule entries
3. **User Analytics** - Track which files are accessed
4. **Flexible Format** - Support multiple file types

## 🛠️ File Management

### Adding Files to Schedules:
1. Upload file via admin panel
2. File stored in `schedules/` directory
3. Path saved to `schedule_file` column
4. Automatically displayed in chatbot responses

### File Viewing:
- Uses `view_schedule.php?id={schedule_id}`
- Sets appropriate Content-Type headers
- Supports inline viewing (PDF, images)
- Downloads for other types

## 🔐 Security Considerations

1. **File Path Validation** - Checks if file exists before displaying
2. **Access Control** - Users must be logged in
3. **Content Type Validation** - Proper MIME types
4. **XSS Prevention** - HTML escaping for filenames
5. **Direct Access Prevention** - Files served through PHP script

## 📱 Responsive Design

### Desktop View:
- Full table with all columns
- Large file buttons with icons + names
- Hover effects and transitions

### Mobile View:
- Stacked layout for schedules
- Compact file buttons
- Touch-friendly click targets
- Horizontal scroll for wide tables

## 🎯 Future Enhancements

### Potential Additions:
1. **File Preview Modal** - View without leaving chat
2. **File Search** - "Find all PDF schedules"
3. **Batch Download** - Download all professor files
4. **File Upload via Chat** - Admin file management
5. **File Version History** - Track updates
6. **File Analytics** - Most accessed files
7. **Smart Recommendations** - "View related files"

## 📝 Testing Checklist

- [ ] Upload schedule file via admin panel
- [ ] Verify file appears in chatbot schedule display
- [ ] Test file download/view functionality
- [ ] Try various professor name formats
- [ ] Test with different file types (PDF, DOC, etc.)
- [ ] Verify "No file" message when absent
- [ ] Test on mobile devices
- [ ] Verify proper icon display
- [ ] Test file access permissions
- [ ] Validate XSS protection

## 🐛 Troubleshooting

### File Not Displaying:
1. Check if `schedule_file` column has value
2. Verify file exists at specified path
3. Check file permissions (readable by web server)
4. Ensure correct relative path format

### Wrong Icon Showing:
1. Check file extension is correct
2. Verify `getFileIcon()` has mapping
3. Add custom icon if needed

### File Not Opening:
1. Check `view_schedule.php` is accessible
2. Verify user is logged in
3. Check Content-Type headers
4. Validate schedule ID parameter

## 📚 Related Files

- `chatbot_ai.php` - Backend file handling logic
- `index_chatbot.php` - Frontend display and UI
- `view_schedule.php` - File serving script
- `schedule_add.php` - File upload interface
- `db_config.php` - Database configuration

## 🎓 Documentation

For more information:
- See `README.md` for general chatbot features
- See `ENHANCED_AI_FEATURES.md` for AI capabilities
- See `DEPLOYMENT_COMPLETE.md` for setup instructions

---

**Last Updated**: October 26, 2025
**Version**: 2.0
**Author**: Chatbot Development Team
