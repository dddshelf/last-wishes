<?php

namespace Lw\Domain\Model\Wish;

use Assert\Assertion;
use Lw\Domain\Model\User\UserId;

class WishEmail extends Wish
{
    private $mailer;
    private $email;
    private $content;

    public function __construct(WishId $wishId, UserId $userId, $title, $email, $content)
    {
        parent::__construct($wishId, $userId, $title);

        $this->setEmail($email);
        $this->setContent($content);

        $transport = \Swift_SmtpTransport::newInstance('smtp.mandrillapp.com', 587)
            ->setUsername('carlos.buenosvinos@gmail.com')
            ->setPassword('cIX_fPnttAiI59bAmhuwpw');

        $this->mailer = \Swift_Mailer::newInstance($transport);
    }

    public function grant()
    {
        // Create a message
        $message = \Swift_Message::newInstance('Wonderful Subject')
            ->setFrom(array('carlos.buenosvinos@lastwishes.com' => 'Carlos Buenosvinos'))
            ->setTo(array($this->email))
            ->setSubject($this->title())
            ->setBody($this->content)
        ;

        // Send the message
        $result = $this->mailer->send($message);
        if (!$result) {
            throw new \Exception('Mail not send');
        }
    }

    /**
     * @param $email
     */
    private function setEmail($email)
    {
        $email = trim($email);
        if (!$email) {
            throw new \InvalidArgumentException('Email cannot be empty');
        }

        Assertion::email($email);
        $this->email = strtolower($email);
    }

    /**
     * @param $content
     */
    private function setContent($content)
    {
        $content = trim($content);
        if (!$content) {
            throw new \InvalidArgumentException('Message cannot be empty');
        }

        Assertion::notEmpty($content);
        $this->content = $content;
    }

    public function content()
    {
        return $this->content;
    }

    public function email()
    {
        return $this->email;
    }
}
