<?php

namespace SlackBatch;

/**
 * Class InviteSender
 *
 * This class will send an invite using the Slack API.
 *
 * @author  Mehdi Bounya <contact.mehdi@pm.me>
 * @license MIT <https://opensource.org/licenses/MIT>
 */
class InviteSender
{
    const API = 'https://slack.com/api/users.admin.invite';

    const WARNS = [
        'already_invited'           => 'Invitation already sent to user.',
        'already_in_team'           => 'User is already a team member.',
        'invalid_email'             => 'E-mail is invalid.',
    ];
    
    const ERRORS = [
        'not_allowed_token_type'    => 'Token type is not valid.',
        'invalid_auth'              => 'Invalid token',
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    private $fields = [];

    /**
     * Slack authentication token
     *
     * @var string
     */
    public $token;

    /**
     * Send invitation
     *
     * @param string $email Receiver's e-mail
     *
     * @throws Exception When the invitation is not sent
     * @return void
     */
    public function send(string $email)
    {
        $post = [
            'token' => $this->token,
            'email' => $email,
        ];
        $post = array_merge($post, $this->fields);
        $response = json_decode($this->curl($post), true);
        if ($response['ok'] === true) {
            return;
        }
        $msg = $response['error'];
        $code = 1;
        if (in_array($response['error'], array_keys(self::WARNS))) {
            $msg = self::WARNS[$response['error']];
            $code = 0;
        } elseif (in_array($response['error'], array_keys(self::ERRORS))) {
            $msg = self::ERRORS[$response['error']];
        }
        
        throw new \Exception($msg, $code);
    }

    /**
     * Send POST request
     *
     * @param array $post POST data
     * 
     * @throws \Exception If the request fails
     * @return string 
     */
    private function curl(array $post): string
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::API);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            ['Content-Type: application/x-www-form-urlencoded']
        );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($curl);
        
        if ($response === false) {
            throw new Exception("cURL failed: ".curl_error($curl));
        }

        curl_close($curl);
        return $response;
    }
}