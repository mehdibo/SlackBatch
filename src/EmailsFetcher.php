<?php

namespace SlackBatch;

/**
 * Interface EmailsFetcher
 *
 * This interface must be implemented by any e-mails fetcher.
 *
 * @author  Mehdi Bounya <contact.mehdi@pm.me>
 * @license MIT <https://opensource.org/licenses/MIT>
 */
interface EmailsFetcher
{
    /**
     * Fetch e-mails
     *
     * This method will fetch e-mails depending on the arg given
     *
     * @param string $arg This is the argument passed in the CLI by the user.
     *
     * @return array Array of e-mails
     */
    public function fetch(string $arg): array;
}