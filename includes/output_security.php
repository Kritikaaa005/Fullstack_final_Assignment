<?php
/**
 * Output Security Helper Functions
 * Provides secure output escaping functions to prevent XSS attacks
 */

if (!function_exists('escape_html')) {
    /**
     * Escape HTML output to prevent XSS attacks
     * @param string $string The string to escape
     * @param int $flags Optional flags for htmlspecialchars()
     * @param string $encoding Character encoding (defaults to UTF-8)
     * @return string Escaped string
     */
    function escape_html($string, $flags = ENT_QUOTES | ENT_HTML5, $encoding = 'UTF-8') {
        return htmlspecialchars($string ?? '', $flags, $encoding);
    }
}

if (!function_exists('escape_attr')) {
    /**
     * Escape HTML attribute values
     * @param string $string The string to escape for HTML attributes
     * @return string Escaped string
     */
    function escape_attr($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}

if (!function_exists('escape_js')) {
    /**
     * Escape data for JavaScript output
     * @param mixed $data The data to escape for JavaScript
     * @return string JSON-encoded and escaped data
     */
    function escape_js($data) {
        return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
}

if (!function_exists('escape_url')) {
    /**
     * Escape data for URL output
     * @param string $string The string to escape for URL
     * @return string URL-encoded string
     */
    function escape_url($string) {
        return urlencode($string ?? '');
    }
}

if (!function_exists('safe_echo')) {
    /**
     * Safely echo HTML-escaped content
     * @param string $string The string to safely output
     */
    function safe_echo($string) {
        echo escape_html($string);
    }
}