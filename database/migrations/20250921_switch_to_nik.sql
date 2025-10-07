-- Switch jasa_bonus and tanda_tangan to use NIK instead of user_id (idempotent)

-- 1) jasa_bonus: add nik, backfill, add index
-- Add nik column to jasa_bonus if not exists
SET @col := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'jasa_bonus' AND COLUMN_NAME = 'nik'
);
SET @sql := IF(@col = 0, 'ALTER TABLE jasa_bonus ADD COLUMN nik VARCHAR(50) NULL AFTER id', NULL);
PREPARE stmt FROM @sql; IF @sql IS NOT NULL THEN EXECUTE stmt; DEALLOCATE PREPARE stmt; END IF;

-- Backfill nik on jasa_bonus if user_id exists
SET @has_user_id := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'jasa_bonus' AND COLUMN_NAME = 'user_id'
);
SET @sql := IF(@has_user_id > 0, 'UPDATE jasa_bonus jb JOIN users u ON u.id = jb.user_id SET jb.nik = u.nik WHERE jb.nik IS NULL', NULL);
PREPARE stmt FROM @sql; IF @sql IS NOT NULL THEN EXECUTE stmt; DEALLOCATE PREPARE stmt; END IF;

-- Ensure nik is NOT NULL and index exists for jasa_bonus
ALTER TABLE jasa_bonus MODIFY nik VARCHAR(50) NOT NULL;
SET @idx := (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'jasa_bonus' AND INDEX_NAME = 'idx_jb_nik'
);
SET @sql := IF(@idx = 0, 'ALTER TABLE jasa_bonus ADD INDEX idx_jb_nik (nik)', NULL);
PREPARE stmt FROM @sql; IF @sql IS NOT NULL THEN EXECUTE stmt; DEALLOCATE PREPARE stmt; END IF;

-- 2) tanda_tangan: add nik, backfill, add index
-- Add nik column to tanda_tangan if not exists
SET @col := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tanda_tangan' AND COLUMN_NAME = 'nik'
);
SET @sql := IF(@col = 0, 'ALTER TABLE tanda_tangan ADD COLUMN nik VARCHAR(50) NULL AFTER id', NULL);
PREPARE stmt FROM @sql; IF @sql IS NOT NULL THEN EXECUTE stmt; DEALLOCATE PREPARE stmt; END IF;

-- Backfill nik on tanda_tangan if user_id exists
SET @has_user_id2 := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tanda_tangan' AND COLUMN_NAME = 'user_id'
);
SET @sql := IF(@has_user_id2 > 0, 'UPDATE tanda_tangan tt JOIN users u ON u.id = tt.user_id SET tt.nik = u.nik WHERE tt.nik IS NULL', NULL);
PREPARE stmt FROM @sql; IF @sql IS NOT NULL THEN EXECUTE stmt; DEALLOCATE PREPARE stmt; END IF;

-- Ensure nik is NOT NULL and index exists for tanda_tangan
ALTER TABLE tanda_tangan MODIFY nik VARCHAR(50) NOT NULL;
SET @idx := (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tanda_tangan' AND INDEX_NAME = 'idx_tt_nik'
);
SET @sql := IF(@idx = 0, 'ALTER TABLE tanda_tangan ADD INDEX idx_tt_nik (nik)', NULL);
PREPARE stmt FROM @sql; IF @sql IS NOT NULL THEN EXECUTE stmt; DEALLOCATE PREPARE stmt; END IF;

-- Drop FK on user_id if exists (legacy)
SET @fk1 := (SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'jasa_bonus' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME = 'users' LIMIT 1);
SET @fk2 := (SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tanda_tangan' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME = 'users' LIMIT 1);
SET @s1 = IF(@fk1 IS NOT NULL, CONCAT('ALTER TABLE jasa_bonus DROP FOREIGN KEY ', @fk1), NULL);
SET @s2 = IF(@fk2 IS NOT NULL, CONCAT('ALTER TABLE tanda_tangan DROP FOREIGN KEY ', @fk2), NULL);
PREPARE stmt FROM @s1; IF @s1 IS NOT NULL THEN EXECUTE stmt; DEALLOCATE PREPARE stmt; END IF;
PREPARE stmt2 FROM @s2; IF @s2 IS NOT NULL THEN EXECUTE stmt2; DEALLOCATE PREPARE stmt2; END IF;

-- Finally drop user_id columns
-- Drop user_id columns if exist
SET @has_user_id := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'jasa_bonus' AND COLUMN_NAME = 'user_id'
);
SET @sql := IF(@has_user_id > 0, 'ALTER TABLE jasa_bonus DROP COLUMN user_id', NULL);
PREPARE stmt FROM @sql; IF @sql IS NOT NULL THEN EXECUTE stmt; DEALLOCATE PREPARE stmt; END IF;

SET @has_user_id2 := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tanda_tangan' AND COLUMN_NAME = 'user_id'
);
SET @sql := IF(@has_user_id2 > 0, 'ALTER TABLE tanda_tangan DROP COLUMN user_id', NULL);
PREPARE stmt FROM @sql; IF @sql IS NOT NULL THEN EXECUTE stmt; DEALLOCATE PREPARE stmt; END IF;

-- Ensure foreign keys on nik use ON UPDATE CASCADE (drop existing nik FKs first if needed)
SET @fk_jb_nik := (SELECT CONSTRAINT_NAME FROM information_schema.REFERENTIAL_CONSTRAINTS
                   WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME LIKE 'jasa_bonus%nik%' AND REFERENCED_TABLE_NAME = 'users');
SET @fk_tt_nik := (SELECT CONSTRAINT_NAME FROM information_schema.REFERENTIAL_CONSTRAINTS
                   WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME LIKE 'tanda_tangan%nik%' AND REFERENCED_TABLE_NAME = 'users');
SET @s1 = IF(@fk_jb_nik IS NOT NULL, CONCAT('ALTER TABLE jasa_bonus DROP FOREIGN KEY ', @fk_jb_nik), NULL);
SET @s2 = IF(@fk_tt_nik IS NOT NULL, CONCAT('ALTER TABLE tanda_tangan DROP FOREIGN KEY ', @fk_tt_nik), NULL);
PREPARE stmt FROM @s1; IF @s1 IS NOT NULL THEN EXECUTE stmt; DEALLOCATE PREPARE stmt; END IF;
PREPARE stmt2 FROM @s2; IF @s2 IS NOT NULL THEN EXECUTE stmt2; DEALLOCATE PREPARE stmt2; END IF;

-- Add nik FKs with ON UPDATE CASCADE (if not already present)
SET @fk_exists := (
  SELECT COUNT(*) FROM information_schema.KEY_COLUMN_USAGE
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'jasa_bonus' AND COLUMN_NAME = 'nik' AND REFERENCED_TABLE_NAME = 'users'
);
SET @sql := IF(@fk_exists = 0, 'ALTER TABLE jasa_bonus ADD CONSTRAINT fk_jb_users_nik FOREIGN KEY (nik) REFERENCES users(nik) ON DELETE CASCADE ON UPDATE CASCADE', NULL);
PREPARE stmt FROM @sql; IF @sql IS NOT NULL THEN EXECUTE stmt; DEALLOCATE PREPARE stmt; END IF;

SET @fk_exists2 := (
  SELECT COUNT(*) FROM information_schema.KEY_COLUMN_USAGE
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tanda_tangan' AND COLUMN_NAME = 'nik' AND REFERENCED_TABLE_NAME = 'users'
);
SET @sql := IF(@fk_exists2 = 0, 'ALTER TABLE tanda_tangan ADD CONSTRAINT fk_tt_users_nik FOREIGN KEY (nik) REFERENCES users(nik) ON DELETE CASCADE ON UPDATE CASCADE', NULL);
PREPARE stmt FROM @sql; IF @sql IS NOT NULL THEN EXECUTE stmt; DEALLOCATE PREPARE stmt; END IF;

-- 4) (Optional) add FK to users.nik if you want referential integrity by NIK
-- This requires users.nik to be UNIQUE (already is) and matching collation/length.
-- ALTER TABLE jasa_bonus ADD CONSTRAINT fk_jb_nik FOREIGN KEY (nik) REFERENCES users(nik) ON DELETE CASCADE;
-- ALTER TABLE tanda_tangan ADD CONSTRAINT fk_tt_nik FOREIGN KEY (nik) REFERENCES users(nik) ON DELETE CASCADE;
