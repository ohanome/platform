<?php

namespace Drupal\ohano_mail;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Mailer class extending the PHPMailer.
 *
 * @package Drupal\ohano_mail
 */
class OhanoMailer extends PHPMailer {

  /**
   * The name of the template that needs to be rendered.
   *
   * @var string
   */
  private string $templateName;

  /**
   * Class constructor.
   *
   * @param \Drupal\ohano_mail\OhanoMail $mail
   *   The mail that needs to be sent.
   * @param bool $exceptions
   *   Whether to throw external exceptions.
   */
  public function __construct(OhanoMail $mail, bool $exceptions = NULL) {
    $this->templateName = $mail->value;
    $this->isHTML(TRUE);

    $this->Host = $_ENV['SMTP_HOST'];
    $this->Username = $_ENV['SMTP_USERNAME'];
    $this->From = $_ENV['SMTP_ADDRESS'];
    $this->Sender = $_ENV['SMTP_ADDRESS'];
    $this->FromName = 'ohano';
    $this->Password = $_ENV['SMTP_PASSWORD'];
    $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $this->Port = 25;

    parent::__construct($exceptions);

    if (\Drupal::request()->getHttpHost() != 'ohano.lndo.site') {
      // Setup SMTP.
      $this->isSMTP();
      $this->SMTPAuth = TRUE;
    }
  }

  /**
   * Renders the template with the given data.
   *
   * @param array $data
   *   The data to use during rendering.
   *
   * @return \Drupal\ohano_mail\OhanoMailer
   *   The active instance of this class.
   */
  public function renderBody(array $data): OhanoMailer {
    $twig = \Drupal::service('twig');
    /** @var \Drupal\Core\Extension\ModuleExtensionList $moduleList */
    $moduleList = \Drupal::service('extension.list.module');
    $modulePath = $moduleList->getPath('ohano_mail');
    $template = $twig->loadTemplate($modulePath . '/templates/mail--' . $this->templateName . '.html.twig');
    $this->Body = $template->render($data);

    return $this;
  }

}
