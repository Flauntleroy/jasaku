-- Add no_rekening field to users table
ALTER TABLE users
ADD COLUMN no_rekening VARCHAR(64) NULL AFTER golongan;

-- Optional: add index if you plan to search by account number frequently
-- CREATE INDEX idx_users_no_rekening ON users (no_rekening);
