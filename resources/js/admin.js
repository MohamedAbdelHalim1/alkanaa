import './core';
import './alpine';

/**
 * Entry point for the admin panel, and — since they extend the same layout
 * shell (see plan Milestones 2/3/5) — the seller panel and POS screen too.
 * jQuery + the legacy AIZ compat shim load classically, before this module
 * runs — see components/legacy-js-bridge.blade.php.
 */
