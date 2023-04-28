<?php
/*
Plugin Name: Slack Error Logger
Description: Sends all errors from the website to a Slack group.
*/

// Replace these values with your own Slack webhook URL and channel name
define('SLACK_WEBHOOK_URL', 'https://hooks.slack.com/services/XXXXXXXXXXXXXXXXXXXXXXX');
define('SLACK_CHANNEL_NAME', '#sajtovi');


#curl -X POST -H 'Content-type: application/json' --data '{"text":"This is a test message"}' https://hooks.slack.com/services/XXXXXXXXXXXXXXXXXXXXXx

function send_slack_message($message) {
  $data = array(
    'text' => $message,
    'channel' => SLACK_CHANNEL_NAME
  );

  $options = array(
    'http' => array(
      'header'  => 'Content-type: application/json',
      'method'  => 'POST',
      'content' => json_encode($data)
    )
  );

  $context  = stream_context_create($options);
  $result = file_get_contents(SLACK_WEBHOOK_URL, false, $context);
}

function log_error_to_slack($errno, $errstr, $errfile, $errline) {
  $website_url = get_site_url();
  $parsed_url = parse_url($website_url);
  $website_domain = $parsed_url['host'];
  $message = "Error on $website_domain: [$errno] $errstr in $errfile on line $errline";
  send_slack_message($message);
}





// Register the error handler
set_error_handler('log_error_to_slack');
