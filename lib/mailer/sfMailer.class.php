<?php

/**
 * sfMailer is the main entry point for the mailer system.
 *
 * This class is instanciated by sfContext on demand and compatible to swiftmailer ~5.2
 *
 * @package    symfony
 * @subpackage mailer
 * @author     Thomas A. Hirsch <thomas.hirsch@vema-eg.de>
 * @version    SVN: $Id$
 */
class sfMailer extends sfMailerBase
{
  /**
   * Sends the given message.
   *
   * @param Swift_Mime_Message $message         A transport instance
   * @param string[]           &$failedRecipients An array of failures by-reference
   *
   * @return int|false The number of sent emails
   */
  public function send(Swift_Mime_Message $message, &$failedRecipients = null)
  {
    if ($this->force)
    {
      $this->force = false;

      if (!$this->realtimeTransport->isStarted())
      {
        $this->realtimeTransport->start();
      }

      return $this->realtimeTransport->send($message, $failedRecipients);
    }

    return parent::send($message, $failedRecipients);
  }


  /**
   * @inheritDoc
   */
  public function compose($from = null, $to = null, $subject = null, $body = null)
  {
    $msg = Swift_Message::newInstance($subject);

    return $msg
      ->setFrom($from)
      ->setTo($to)
      ->setBody($body)
      ;
  }
}
