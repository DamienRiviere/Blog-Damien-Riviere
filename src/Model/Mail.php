<?php

namespace App\Model;

use App\Helpers\Data;
use App\Helpers\Session;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    private $name;

    private $email;

    private $subject;

    private $message;

    private $check = false;

    private $session;

    private $data;

    public function __construct()
    {
        $this->session = new Session();
        $this->data = new Data();
    }

    public function setName(string $name): void
    {
        $this->name = htmlspecialchars($name);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setEmail(string $email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = htmlspecialchars($email);
        } else {
            $this->check = false;
            $this->session->setSession(
                "email_error",
                "Votre adresse email n'est pas valide !"
            );
        }
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = htmlspecialchars($subject);
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setMessage(string $message): void
    {
        $this->message = htmlspecialchars($message);
    }

    public function setInformations(array $data)
    {
        if (
            !empty($data['name'])
            and !empty($data['email'])
            and !empty($data['subject'])
            and !empty($data['message'])
        ) {
            $this->check = true;
            $this->setName($data['name']);
            $this->setEmail($data['email']);
            $this->setSubject($data['subject']);
            $this->setMessage($data['message']);
        } else {
            $this->session->setSession("email_error", "Veuillez remplir tous les champs du formulaire !");
        }
    }

    public function sendEmail()
    {
        if ($this->check) {
            //Create a new PHPMailer instance
            $mail = new PHPMailer();
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($this->email, $this->name);
            $mail->addAddress('damien@d-riviere.fr', 'Damien RIVIERE');
            $mail->addReplyTo('damien@d-riviere.fr', 'Damien RIVIERE');

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $this->subject;
            $mail->Body    = '
				<p>Vous avez reçu un message de <b>' . $this->name . '</b>.</p>
				<p>' . $this->message . '</p>
				<p>' .  $this->name . '<br>' . $this->email . '</p>
			';
            $mail->AltBody = '
            	Message de ' . $this->name . ' - ' . $this->message . ' - ' .  $this->name . ' - ' . $this->email
            ;

            // Send the message, check for errors
            !$mail->send() ?
                $this->session->setSession("email_error", $mail->ErrorInfo)
                :
                $this->session->setSession("email_success", "Votre email vient d'être envoyé !");
            ;

            unset($this->name, $this->email, $this->subject, $this->message);
            $this->session->deleteItem("email_error");
        }
    }
}
