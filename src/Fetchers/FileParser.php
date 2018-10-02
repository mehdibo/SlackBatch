<?php

namespace SlackBatch\Fetchers;

/**
 * Class FileParser
 *
 * Get new line separated e-mails from a file
 *
 * @author  Mehdi Bounya <contact.mehdi@pm.me>
 * @license MIT <https://opensource.org/licenses/MIT>
 */
class FileParser implements \SlackBatch\EmailsFetcher
{
    /**
     * Fetch e-mails
     *
     * Fetch e-mails from the file $arg
     *
     * @param string $arg Path to file containing new lines separated e-mails.
     *
     * @return array Array of e-mails
     */
    public function fetch(string $arg): array
    {
        $emails = explode("\n", file_get_contents($arg));
        // Remove empty elements (empty lines)
        $emails = array_filter($emails);
        // Trim all elements
        $emails = array_map('trim', $emails);
        return $emails;
    }
}