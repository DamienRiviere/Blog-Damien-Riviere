<?php

namespace App\Model;

use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    private $name;

    private $email;

    private $subject;

    private $message;

    private $check = false;

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
            $_SESSION['email_error'] = "Votre adresse email n'est pas valide !";
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

    public function setInformations($name, $email, $subject, $message)
    {
        if (!empty($name) and !empty($email) and !empty($subject) and !empty($message)) {
            $this->check = true;
            $this->setName($_POST['name']);
            $this->setEmail($_POST['email']);
            $this->setSubject($_POST['subject']);
            $this->setMessage($_POST['message']);
        }

        $this->check = false;
        $_SESSION['email_error'] = "Veuillez remplir tous les champs du formulaire !";
    }

    public function sendEmail()
    {
        if ($this->check) {
            //Create a new PHPMailer instance
            $mail = new PHPMailer();
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($this->email, $this->name); // Adresse de l'expéditeur
            $mail->addAddress('damien@d-riviere.fr', 'Damien RIVIERE'); // Adresse du destinataire
            $mail->addReplyTo('damien@d-riviere.fr', 'Damien RIVIERE'); // Adresse pour réponse par défaut

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
            !$mail->send() ? $_SESSION['email_error'] =
                $mail->ErrorInfo : $_SESSION['email_success'] = "Votre email vient d'être envoyé !";

            unset($this->name, $this->email, $this->subject, $this->message);
        }
    }
}
