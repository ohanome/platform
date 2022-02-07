<?php

namespace Drupal\ohano_mail;

use PHPMailer\PHPMailer\PHPMailer;

class OhanoMailer extends PHPMailer {

  private string $templateName;

  public function __construct(OhanoMail $mail, $exceptions = NULL) {
    $this->templateName = $mail->value;

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
