# Professor Add Feature - Complete Account Creation

## Overview
The enhanced "Add Professor" feature now allows administrators to create complete professor accounts with both login credentials and profile information in a single operation.

## What Changed

### Frontend (professors.php)
The "Add Professor" modal now includes:

**Account Information Section** (New):
- Username* (required) - For login
- Password* (required, minimum 6 characters) - For login
- Email (optional) - Contact email

**Basic Information Section** (Existing):
- Full Name*, Photo*, Age, Sex, Birthdate, etc.
- Bio, Expertise, Academic Distinctions
- Professional details

**Schedule Information Section** (Existing):
- Schedule file upload or manual entry

### Backend (prof_add.php)
Complete rewrite with enhanced security:

1. **Validation**
   - Checks username uniqueness
   - Validates all required fields
   - Sanitizes all inputs

2. **Dual-Table Insertion with Transaction**
   ```
   BEGIN TRANSACTION
   → Insert into professors table (get professor_id)
   → Hash password with password_hash()
   → Insert into users table with professor_id link
   → Insert schedules (if provided)
   COMMIT (or ROLLBACK on error)
   ```

3. **Security Features**
   - Password hashing with PASSWORD_DEFAULT
   - Prepared statements for user insertion
   - Transaction support for data integrity
   - Automatic rollback on failure
   - File cleanup on errors

4. **Error Handling**
   - Username exists detection
   - Upload failure handling
   - Database error catching
   - User-friendly error messages

## How It Works

### Database Flow
```
Step 1: Insert Professor Profile
┌─────────────────────────────┐
│ professors Table            │
│ ─────────────────────────── │
│ professor_id (AUTO_INCREMENT)│
│ professor_name              │
│ bio, expertise, etc.        │
│ photo                       │
└─────────────────────────────┘
         │
         ▼ Get professor_id
         │
Step 2: Create User Account
┌─────────────────────────────┐
│ users Table                 │
│ ─────────────────────────── │
│ id (AUTO_INCREMENT)         │
│ username (from form)        │
│ password (hashed)           │
│ role = 'professor'          │
│ professor_id (linked)       │
│ email                       │
│ status = 'active'           │
└─────────────────────────────┘
         │
         ▼
Step 3: Insert Schedules
┌─────────────────────────────┐
│ schedules Table             │
│ ─────────────────────────── │
│ professor_id (linked)       │
│ subject, day, time, room    │
│ schedule_file (optional)    │
└─────────────────────────────┘
```

## Testing the Feature

### Test Case 1: Successful Professor Creation
1. Login as admin
2. Navigate to Professors page
3. Click "Add Professor"
4. Fill in ALL fields:
   - **Account Info**: username = "jdoe", password = "test123", email = "jdoe@university.edu"
   - **Basic Info**: name = "John Doe", upload photo, fill age, sex, etc.
   - **Schedule**: Add at least one schedule or upload file
5. Submit form
6. **Expected Result**:
   - Success message displayed
   - Professor appears in professors list
   - New user record created in database
   - Professor can login with username/password
   - Dashboard accessible after login

### Test Case 2: Duplicate Username
1. Add a professor with username "jdoe"
2. Try to add another professor with same username "jdoe"
3. **Expected Result**:
   - Error: "Username already exists. Please choose a different username."
   - No records created
   - No files uploaded

### Test Case 3: Missing Required Fields
1. Try to submit form without:
   - Username (should show HTML5 validation)
   - Password (should show HTML5 validation)
   - Photo (should show error after submit)
2. **Expected Result**:
   - Form validation prevents submission
   - Appropriate error messages shown

### Test Case 4: Professor Login
1. After creating professor account
2. Logout as admin
3. Go to login page
4. Login with professor credentials
5. **Expected Result**:
   - Successful login
   - Redirect to professor_dashboard.php
   - Professor can view profile and schedules

### Test Case 5: Transaction Rollback
To test this, you would need to simulate a database error:
1. Temporarily make users table read-only or remove insert permission
2. Try to add professor
3. **Expected Result**:
   - Error message displayed
   - NO professor record created
   - Uploaded files deleted
   - Database in consistent state

## Database Verification Queries

```sql
-- Check professor and user were created together
SELECT 
    u.id, u.username, u.role, u.professor_id,
    p.professor_id, p.professor_name, p.email
FROM users u
LEFT JOIN professors p ON u.professor_id = p.professor_id
WHERE u.role = 'professor'
ORDER BY u.id DESC
LIMIT 5;

-- Check schedules linked correctly
SELECT 
    s.schedule_id, s.subject, s.day, s.time,
    p.professor_name
FROM schedules s
JOIN professors p ON s.professor_id = p.professor_id
ORDER BY s.schedule_id DESC
LIMIT 10;

-- Verify password is hashed
SELECT id, username, password, role 
FROM users 
WHERE role = 'professor' 
ORDER BY id DESC 
LIMIT 1;
-- Password should start with $2y$ (bcrypt hash)
```

## Security Features

1. **Password Security**
   - Uses `password_hash()` with PASSWORD_DEFAULT (bcrypt)
   - Never stores plain text passwords
   - 60-character hash length

2. **SQL Injection Prevention**
   - Prepared statements for user insertion
   - Real_escape_string for other inputs
   - Parameter binding

3. **Transaction Safety**
   - BEGIN TRANSACTION before operations
   - COMMIT only if all succeed
   - ROLLBACK on any failure
   - File cleanup on rollback

4. **File Security**
   - Timestamp-based unique filenames
   - Directory existence check
   - File deletion on transaction failure

## Error Messages

| Scenario | Error Code | User Message |
|----------|------------|--------------|
| Username exists | `username_exists` | "Username already exists. Please choose a different username." |
| No photo uploaded | `no_photo` | "Please upload a professor photo." |
| Upload failed | `upload_failed` | "Failed to upload photo. Please try again." |
| Database error | `<error message>` | Shows actual error (for debugging) |

## Success Flow

```
User fills form
     │
     ▼
Validate username unique
     │
     ▼
Insert professor → Get ID
     │
     ▼
Hash password
     │
     ▼
Insert user with professor_id
     │
     ▼
Insert schedules
     │
     ▼
Commit transaction
     │
     ▼
Redirect with success=1
     │
     ▼
Show SweetAlert success message
```

## Integration Points

### Related Files
- `professors.php` - Admin UI for adding professors
- `prof_add.php` - Backend processor (this file)
- `prof_update.php` - For updating existing professors
- `prof_delete.php` - For deleting professors
- `login.php` - Professor login with new credentials
- `professor_dashboard.php` - Post-login destination

### Database Tables
- `users` - Stores login credentials
- `professors` - Stores profile information
- `schedules` - Stores teaching schedules

### Session Variables Set on Login
- `$_SESSION['user_id']` - From users.id
- `$_SESSION['username']` - Login username
- `$_SESSION['role']` - Always 'professor'
- `$_SESSION['professor_id']` - Links to professors table
- `$_SESSION['first_name']` - Split from full name
- `$_SESSION['last_name']` - Split from full name

## Troubleshooting

### Issue: "Username already exists" but user doesn't appear in list
**Solution**: Check if username exists in users table but not linked to professor:
```sql
SELECT * FROM users WHERE username = 'problematic_username';
```

### Issue: Professor created but can't login
**Solution**: Check role and status:
```sql
SELECT username, role, status FROM users WHERE username = 'professor_username';
-- Role should be 'professor', status should be 'active'
```

### Issue: Files uploaded but not in database
**Solution**: Check transaction didn't rollback. Look for files in Images/ and schedules/ directories without database entries - these are orphaned files from failed transactions.

### Issue: Password doesn't work
**Solution**: Verify password is hashed:
```sql
SELECT password FROM users WHERE username = 'professor_username';
-- Should start with $2y$ and be 60 characters long
```

## Future Enhancements

1. **Email Verification**
   - Send welcome email with credentials
   - Email verification before activation

2. **Bulk Import**
   - CSV upload for multiple professors
   - Template download

3. **Password Strength Meter**
   - Visual indicator in form
   - Password requirements display

4. **Username Suggestions**
   - Auto-suggest available usernames
   - Generate from name

5. **Profile Completion**
   - Allow professor to complete profile after creation
   - Email notification to complete profile

## Conclusion

This feature streamlines professor account creation by combining user account and profile creation into a single atomic operation. The use of transactions ensures data integrity, and security best practices protect sensitive information.
