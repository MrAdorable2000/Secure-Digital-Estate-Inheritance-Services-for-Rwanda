-- ============================================
-- R-DEIP Database Schema
-- Phase 1: Foundation & Authentication
-- MySQL 8+
-- ============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `login_logs`;
DROP TABLE IF EXISTS `password_resets`;
DROP TABLE IF EXISTS `user_roles`;
DROP TABLE IF EXISTS `role_permissions`;
DROP TABLE IF EXISTS `departments`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `users`;

-- ============================================
-- USERS
-- ============================================
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NOT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `profile_photo` VARCHAR(500) DEFAULT NULL,
  `status` ENUM('active','inactive','suspended','pending') NOT NULL DEFAULT 'pending',
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `last_login_at` TIMESTAMP NULL DEFAULT NULL,
  `failed_login_attempts` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `locked_until` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_users_uuid` (`uuid`),
  UNIQUE KEY `uk_users_email` (`email`),
  INDEX `idx_users_status` (`status`),
  INDEX `idx_users_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ROLES
-- ============================================
CREATE TABLE `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `slug` VARCHAR(50) NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_roles_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PERMISSIONS
-- ============================================
CREATE TABLE `permissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `module` VARCHAR(50) NOT NULL DEFAULT 'general',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_permissions_slug` (`slug`),
  INDEX `idx_permissions_module` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ROLE_PERMISSIONS (many-to-many)
-- ============================================
CREATE TABLE `role_permissions` (
  `role_id` BIGINT UNSIGNED NOT NULL,
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`role_id`,`permission_id`),
  CONSTRAINT `fk_rp_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rp_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- USER_ROLES (many-to-many)
-- ============================================
CREATE TABLE `user_roles` (
  `user_id` BIGINT UNSIGNED NOT NULL,
  `role_id` BIGINT UNSIGNED NOT NULL,
  `assigned_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `assigned_by` BIGINT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  CONSTRAINT `fk_ur_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ur_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  INDEX `idx_ur_role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DEPARTMENTS
-- ============================================
CREATE TABLE `departments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) DEFAULT NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_departments_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PASSWORD_RESETS
-- ============================================
CREATE TABLE `password_resets` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `expires_at` TIMESTAMP NOT NULL,
  `used_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_pr_token` (`token`),
  INDEX `idx_pr_user_id` (`user_id`),
  INDEX `idx_pr_expires_at` (`expires_at`),
  CONSTRAINT `fk_pr_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- LOGIN_LOGS
-- ============================================
CREATE TABLE `login_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(500) DEFAULT NULL,
  `status` ENUM('success','failed','locked') NOT NULL DEFAULT 'success',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_ll_user_id` (`user_id`),
  INDEX `idx_ll_status` (`status`),
  INDEX `idx_ll_created_at` (`created_at`),
  CONSTRAINT `fk_ll_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- AUDIT_LOGS
-- ============================================
CREATE TABLE `audit_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `action` VARCHAR(50) NOT NULL,
  `module` VARCHAR(50) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(500) DEFAULT NULL,
  `properties` JSON DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_al_user_id` (`user_id`),
  INDEX `idx_al_action` (`action`),
  INDEX `idx_al_module` (`module`),
  INDEX `idx_al_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- SEED DATA
-- ============================================

-- Roles
INSERT INTO `roles` (`name`, `slug`, `description`) VALUES
  ('Super Administrator', 'super_admin', 'Complete system control with all permissions'),
  ('Administrator', 'administrator', 'Administrative management and user oversight'),
  ('Government Officer', 'government_officer', 'Can work on assigned government workflows'),
  ('Citizen', 'citizen', 'Can access own profile and future applications/cases');

-- Permissions
INSERT INTO `permissions` (`name`, `slug`, `module`, `description`) VALUES
  -- Users module
  ('View Users', 'users.view', 'users', 'View user list and details'),
  ('Create Users', 'users.create', 'users', 'Create new user accounts'),
  ('Edit Users', 'users.edit', 'users', 'Edit existing user accounts'),
  ('Delete Users', 'users.delete', 'users', 'Delete user accounts'),
  ('Suspend Users', 'users.suspend', 'users', 'Suspend user accounts'),
  ('Activate Users', 'users.activate', 'users', 'Activate user accounts'),
  -- Roles module
  ('View Roles', 'roles.view', 'roles', 'View roles list'),
  ('Manage Roles', 'roles.manage', 'roles', 'Create, edit, delete roles'),
  ('Assign Roles', 'roles.assign', 'roles', 'Assign roles to users'),
  -- Permissions module
  ('View Permissions', 'permissions.view', 'permissions', 'View permissions list'),
  ('Manage Permissions', 'permissions.manage', 'permissions', 'Create, edit, delete permissions'),
  -- Dashboard module
  ('View Dashboard', 'dashboard.view', 'dashboard', 'Access the dashboard'),
  ('View Admin Dashboard', 'dashboard.admin', 'dashboard', 'Access admin-level dashboard'),
  ('View Super Admin Dashboard', 'dashboard.super_admin', 'dashboard', 'Access super admin dashboard'),
  -- Audit module
  ('View Audit Logs', 'audit.view', 'audit', 'View audit log entries'),
  ('Export Audit Logs', 'audit.export', 'audit', 'Export audit logs'),
  -- Departments module
  ('View Departments', 'departments.view', 'departments', 'View departments'),
  ('Manage Departments', 'departments.manage', 'departments', 'Create, edit, delete departments'),
  -- Profile module
  ('Edit Own Profile', 'profile.edit', 'profile', 'Edit own profile information'),
  ('Change Own Password', 'profile.password', 'profile', 'Change own password'),
  -- Reports module
  ('View Reports', 'reports.view', 'reports', 'View system reports');

-- Super Admin gets ALL permissions
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, `id` FROM `permissions`;

-- Administrator gets most permissions (not super admin dashboard, not manage permissions)
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 2, `id` FROM `permissions`
WHERE `slug` NOT IN ('dashboard.super_admin', 'permissions.manage', 'roles.manage');

-- Government Officer gets limited permissions
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 3, `id` FROM `permissions`
WHERE `slug` IN ('dashboard.view', 'audit.view', 'profile.edit', 'profile.password', 'reports.view', 'departments.view');

-- Citizen gets minimal permissions
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 4, `id` FROM `permissions`
WHERE `slug` IN ('dashboard.view', 'profile.edit', 'profile.password');

-- Departments
INSERT INTO `departments` (`name`, `slug`, `description`) VALUES
  ('Estate Management', 'estate-management', 'Manages digital estate records and inheritance cases'),
  ('Legal Affairs', 'legal-affairs', 'Handles legal documentation and court workflows'),
  ('Citizen Services', 'citizen-services', 'Provides services to citizens including registrations'),
  ('IT Administration', 'it-administration', 'Manages system configuration, security, and technical operations'),
  ('Finance & Accounting', 'finance-accounting', 'Handles financial records and asset valuations');

-- Demo Users
-- Password for all demo users: Password@123
-- Hashed with bcrypt (cost 12)
INSERT INTO `users` (`uuid`, `first_name`, `last_name`, `email`, `phone`, `password`, `status`, `email_verified_at`, `last_login_at`) VALUES
  (UUID(), 'Jean Pierre', 'HABIMANA', 'superadmin@rdeip.gov.rw', '+250788000001', '$2a$12$ZYphnOEzIc0uM09zU1aK5eV9ptuNby5nn5cdVPpgswofEvor1PILu', 'active', NOW(), NOW()),
  (UUID(), 'Marie Claire', 'MUKAMANA', 'admin@rdeip.gov.rw', '+250788000002', '$2a$12$ZYphnOEzIc0uM09zU1aK5eV9ptuNby5nn5cdVPpgswofEvor1PILu', 'active', NOW(), NOW()),
  (UUID(), 'Emmanuel', 'NTAGANIRA', 'officer@rdeip.gov.rw', '+250788000003', '$2a$12$ZYphnOEzIc0uM09zU1aK5eV9ptuNby5nn5cdVPpgswofEvor1PILu', 'active', NOW(), NOW()),
  (UUID(), 'Alice', 'UWIMANA', 'citizen@rdeip.gov.rw', '+250788000004', '$2a$12$ZYphnOEzIc0uM09zU1aK5eV9ptuNby5nn5cdVPpgswofEvor1PILu', 'active', NOW(), NOW());

-- Assign roles to demo users
INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
  (1, 1),
  (2, 2),
  (3, 3),
  (4, 4);

-- Some sample audit logs
INSERT INTO `audit_logs` (`user_id`, `action`, `module`, `description`, `ip_address`, `created_at`) VALUES
  (1, 'login', 'auth', 'Super Administrator logged in', '127.0.0.1', NOW()),
  (2, 'login', 'auth', 'Administrator logged in', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 1 HOUR)),
  (3, 'login', 'auth', 'Government Officer logged in', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 2 HOUR)),
  (4, 'user.create', 'users', 'Citizen account created during registration', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 3 HOUR)),
  (1, 'settings.update', 'system', 'System configuration updated', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 4 HOUR));

-- Some sample login logs
INSERT INTO `login_logs` (`user_id`, `ip_address`, `user_agent`, `status`, `created_at`) VALUES
  (1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'success', NOW()),
  (2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'success', DATE_SUB(NOW(), INTERVAL 1 HOUR)),
  (1, '192.168.1.50', 'Mozilla/5.0 (Linux; Android 12)', 'success', DATE_SUB(NOW(), INTERVAL 2 HOUR)),
  (3, '10.0.0.5', 'Mozilla/5.0 (Macintosh; Intel Mac OS X)', 'failed', DATE_SUB(NOW(), INTERVAL 3 HOUR));
