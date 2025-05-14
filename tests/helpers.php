<?php

/**
 * Helper function to include a file if it exists
 */
function includeIfExists(string $file): mixed
{
    if (file_exists($file)) {
        return include $file;
    }

    return null;
}
