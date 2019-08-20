<?php

namespace SlackBatch;

/**
 * Class App
 *
 * This class will execute the appropriate commands.
 *
 * @author  Mehdi Bounya <contact.mehdi@pm.me>
 * @license MIT <https://opensource.org/licenses/MIT>
 */
class App extends \splitbrain\phpcli\CLI
{
    /**
     * E-mails fetcher
     *
     * @var \SlackBatch\EmailsFetcher
     */
    private $fetcher;

    /**
     * Available fetchers
     *
     * @var array
     */
    private $fetchers = [];

    /**
     * Invite sender
     *
     * @var \SlackBatch\InviteSender
     */
    private $sender;

    public function __construct(array $fetchers, \SlackBatch\InviteSender $sender)
    {
        parent::__construct();
        $this->fetchers = $fetchers;
        $this->sender = $sender;
    }

    /**
     * Define available options and arguments.
     *
     * @param \splitbrain\phpcli\Options $options Options instance
     *
     * @return void
     */
    protected function setup(\splitbrain\phpcli\Options $options)
    {
        $options->setHelp('Send batch Slack invitations using the Slack API.');
        $avl = implode(' - ', array_keys($this->fetchers));
        $options->registerOption('f', "Source options: $avl", null, 'format');
        $options->registerOption('channels', "Default channels Comma-separated list of IDs (not names!)", null, 'channels');
        $options->registerOption('fail-abort', 'If an invite fails abort sending.');
        $options->registerOption('token', 'Slack legacy auth token.', null, 'token');
        $options->registerArgument('src', 'E-mails source.');
    }

    /**
     * Route commands and execute them
     *
     * @param \splitbrain\phpcli\Options $options Options instance
     *
     * @return void
     */
    protected function main(\splitbrain\phpcli\Options $options)
    {
        if (!$options->getOpt('token')) {
            return $this->error('You need to pass a token (--token <token>).');
        }
        $args = [];
        if ($options->getOpt('channels') !== FALSE)
            $args['channels'] = $options->getOpt('channels');
        $this->sender->token = $options->getOpt('token');
        $this->debug("Token set to: ".$this->sender->token);

        // Make sure the passed fetcher is valid
        if (!in_array($options->getOpt('f'), array_keys($this->fetchers))) {
            return $this->error(
                'Invalid source, options: '
                .implode(' - ', array_keys($this->fetchers))
            );
        }
        $this->debug("Selected fetcher: ".$options->getOpt('f'));

        // Create appropriate fetcher class
        $this->fetcher = new $this->fetchers[$options->getOpt('f')];

        // Get e-mails
        $emails = $this->fetcher->fetch($options->getArgs('src')[0]);
        $this->sendInvites($emails, (bool) $options->getOpt('fail-abort'), $args);
    }

    /**
     * Send invites
     *
     * @param array $emails Array of e-mails
     * @param bool  $abort  TRUE to abort when an invite fails
     * 
     * @return void
     */
    private function sendInvites(array $emails, bool $abort, array $args)
    {
        // Send invites
        foreach ($emails as $email) {
            $success = true;
            try {
                $this->sender->send($email, $args);
            } catch(\Exception $e) {
                $this->error('Failed sending invite to: '.$email);
                $this->warning($e->getMessage());
                if ($abort || $e->getCode() == 1) {
                    $this->fatal("Error occured and we had to stop :/");
                }
                $success = false;
            }
            if ($success) {
                $this->success('Invite sent to: '.$email);
            }
        }
    }
}