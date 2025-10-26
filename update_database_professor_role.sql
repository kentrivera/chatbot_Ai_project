-- SQL Migration Script to Add Professor Role
-- Run this script to update the database for professor functionality

USE chatbot;

-- Step 1: Modify users table to support professor role
ALTER TABLE `users` 
MODIFY COLUMN `role` ENUM('admin', 'student', 'professor') DEFAULT 'student';

-- Step 2: Add professor_id column to link users with professors table
ALTER TABLE `users` 
ADD COLUMN `professor_id` INT(11) DEFAULT NULL AFTER `last_name`,
ADD INDEX `idx_professor_id` (`professor_id`);

-- Step 3: Add foreign key constraint (optional - can be skipped if you prefer loose coupling)
-- ALTER TABLE `users` 
-- ADD CONSTRAINT `fk_users_professor` 
-- FOREIGN KEY (`professor_id`) REFERENCES `professors`(`professor_id`) 
-- ON DELETE SET NULL ON UPDATE CASCADE;

-- Step 4: Sample data - Create a professor user linked to existing professor
-- Update this with actual professor_id from your professors table
-- Example: If you have a professor with professor_id = 1, create a user account for them

INSERT INTO `users` (`username`, `password`, `role`, `first_name`, `last_name`, `professor_id`, `created_at`) 
VALUES 
('prof.smith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'professor', 'John', 'Smith', NULL, NOW());
-- Password for above user is: password123

-- Step 5: Update existing professor data if needed
-- If you want to link an existing user to a professor record:
-- UPDATE users SET professor_id = 1, role = 'professor' WHERE username = 'existinguser';

-- Step 6: Verify the changes
SELECT * FROM users WHERE role = 'professor';

-- Step 7: Add email column to users table for better communication (optional)
ALTER TABLE `users` 
ADD COLUMN `email` VARCHAR(255) DEFAULT NULL AFTER `username`,
ADD UNIQUE INDEX `idx_email` (`email`);

-- Step 8: Add last_login column to track user activity (optional)
ALTER TABLE `users` 
ADD COLUMN `last_login` TIMESTAMP NULL DEFAULT NULL AFTER `created_at`;

-- Step 9: Add status column for account management (optional)
ALTER TABLE `users` 
ADD COLUMN `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active' AFTER `role`;

-- Verification Queries
-- Check the updated structure
DESCRIBE users;

-- Check all user roles
SELECT role, COUNT(*) as count FROM users GROUP BY role;

-- Migration complete!
SELECT 'Database migration completed successfully!' as status;
