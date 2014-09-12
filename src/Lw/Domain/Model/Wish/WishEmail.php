<?php

namespace Lw\Domain\Model\Wish;

class WishEmail extends Wish
{
    private $mailer;
    private $email;
    private $content;

    public function __construct(WishId $wishId, $title, $email, $content)
    {
        parent::__construct($wishId, $title);

        $this->email = $email;
        $this->content = $content;

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
}
