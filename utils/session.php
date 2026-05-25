<?php

/**
 * =====================================================================
 * session.php - PHP session bootstrap
 * =====================================================================
 *
 * Purpose:
 *   Single entry point to start a session for all project pages.
 *   Ensures the session is started exactly once, even if this file
 *   is included multiple times.
 *
 * Usage:
 *   require_once 'utils/session.php';
 *
 * Behaviour:
 *   - Checks whether a session is already active
 *   - If not, starts a new session
 *   - If yes, does nothing (safe to include repeatedly)
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
