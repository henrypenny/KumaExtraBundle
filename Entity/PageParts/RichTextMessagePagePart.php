<?php

namespace Hmp\KumaExtraBundle\Entity\PageParts;

use Doctrine\ORM\Mapping as ORM;
use Hmp\KumaExtraBundle\Form\PageParts\MessagePagePartAdminType;
use Hmp\KumaExtraBundle\Form\PageParts\RichTextMessagePagePartAdminType;

/**
 * MessagePagePart
 *
 * @ORM\Table(name="kuma_extra_rich_text_message_page_parts")
 * @ORM\Entity
 */
class RichTextMessagePagePart extends AbstractPagePart
{
    /**
     * @var string
     *
     * @ORM\Column(name="message_id", type="string", length=255, nullable=true)
     */
    private $messageId;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var boolean
     *
     * @ORM\Column(name="optional", type="boolean", nullable=true)
     */
    private $optional;

    /**
     * Set messageId
     *
     * @param string $messageId
     *
     * @return MessagePagePart
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * Get messageId
     *
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return MessagePagePart
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get the twig view.
     *
     * @return string
     */
    public function getDefaultView()
    {
        return 'HmpKumaExtraBundle:PageParts:RichTextMessagePagePart/view.html.twig';
    }

    /**
     * Get the admin form type.
     *
     * @return \Hmp\KumaExtraBundle\Form\PageParts\RichTextMessagePagePartAdminType
     */
    public function getDefaultAdminType()
    {
        return new RichTextMessagePagePartAdminType();
    }

    /**
     * Set optional
     *
     * @param boolean $optional
     *
     * @return MessagePagePart
     */
    public function setOptional($optional)
    {
        $this->optional = $optional;

        return $this;
    }

    /**
     * Get optional
     *
     * @return boolean
     */
    public function getOptional()
    {
        return $this->optional;
    }
}
