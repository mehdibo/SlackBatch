# SlackBatch
Send batch invites from the CLI.

# Usage

```sh
$>./slackbatch.php -h
USAGE:
   slackbatch.php <OPTIONS> <src>

   Send batch Slack invitations using the Slack API.                                                                                                                                               

OPTIONS:
   --f <format>                                Source options: file                                                                                  

   --fail-abort                                If an invite fails abort sending.                                                                     

   --token <token>                             Slack legacy auth token.                                                                                     

   -h, --help                                  Display this help screen and exit immeadiately.                                                       

   --no-colors                                 Do not use any colors in output. Useful when piping output to other tools or files.                   

   --loglevel <level>                          Minimum level of messages to display. Default is info. Valid levels are: debug, info, notice, success,
                                               warning, error, critical, alert, emergency.

ARGUMENTS:
   <src>                                       E-mails source.
```

Currently SlackBatch only handles files, so you need to pass a file containing new line separated e-mails.

## Example:
```sh
$> ./slackbatch.php --f file --token "MY_LEGACY_TOKEN" emails.txt
✓ Invite sent to: email@example.com
✓ Invite sent to: emaila@example.com
✓ Invite sent to: emailb@example.com
✓ Invite sent to: emailc@example.com
✓ Invite sent to: emaild@example.com
✓ Invite sent to: emaile@example.com
```
