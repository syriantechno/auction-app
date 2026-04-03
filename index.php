<?php

/**
 * Laravel shared-hosting bootstrap for subdomain deployment.
 * Place this file at the subdomain root (same level as /public).
 * This redirects all requests through public/index.php
 * without exposing the Laravel internals.
 */

define('LARAVEL_START', microtime(true));

require __DIR__.'/public/index.php';
