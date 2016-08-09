<?php
/**
 * Created by PhpStorm.
 * User: henrypenny
 * Date: 18/02/15
 * Time: 9:04 AM
 */

namespace Hmp\KumaExtraBundle\Twig;

use Hmp\KumaExtraBundle\Entity\PageParts\MessagePagePart;
use Hmp\KumaExtraBundle\Twig\BaseTwigExtension;
use Kunstmaan\NodeBundle\Entity\AbstractPage;
use Kunstmaan\PagePartBundle\Twig\Extension\PagePartTwigExtension;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Stringy\StaticStringy as S;

/**
 * Class ContentService
 * @package App\Bundle\Services
 */
class ContentTwigExtension extends BaseTwigExtension
{
    function getServiceName()
    {
        return 'contentService';
    }

    public function getName()
    {
        return 'hmp_content_twig_extension';
    }

    function getFilters() {
        return [
        ];
    }

    function getFunctions()
    {
        return array (
            'has_content' => new \Twig_SimpleFunction('has_content', array($this, 'hasContent'), array('needs_context' => true)),
            'get_content' => new \Twig_SimpleFunction('get_content', array($this, 'getContent'), array('needs_context' => true, 'is_safe' => array('all'))),
        );
    }

    protected $messageMap = [];

    public function getMessageMap(AbstractPage $page)
    {
        $messageHash = md5(get_class($page) . $page->getId());
        if(!isset($this->messageMap[$messageHash])) {

            $ppx = new PagePartTwigExtension($this->em);
            /** @var MessagePagePart[] $pageParts */
            $pageParts = $ppx->getPageParts($page, 'messages');

            $ppMap = [];

            foreach ($pageParts as $pagePart) {
                $ppMap[$pagePart->getMessageId()] = $pagePart;
            }
            $this->messageMap[$messageHash] = $ppMap;
        }
        else {
            $ppMap = $this->messageMap[$messageHash];
        }
        return $ppMap;
    }

    /**
     * @param $context
     * @param $id
     * @return bool
     * @throws \Twig_Error
     */
    public function hasContent($context, $id)
    {
        $page = $this->getPageFromContext($context, 'get_content');
        $messageMap = $this->getMessageMap($page);

        /** @var MessagePagePart $message */
        $message = null;

        if(isset($messageMap[$id])) {
            $message = $messageMap[$id];
        }
        if(!$message) {
            return false;
        }
        if(!$message->getMessage()) {
            return false;
        }
        return true;
    }

    /**
     * @param $context
     * @param $id
     * @return string
     * @throws \Twig_Error
     *
     * Note: this function is not safe to use with user supplied content.
     */
    public function getContent($context, $id)
    {
        $page = $this->getPageFromContext($context, 'get_content');

        $messageMap = $this->getMessageMap($page);

        /** @var MessagePagePart $message */
        $message = null;

        if(isset($messageMap[$id])) {
            $message = $messageMap[$id];
        }
        if(!$message) {
            return 'Please add a message with messageId = ' . $id . ' to the page: ' . $page->getTitle();
        }
        if(!$message->getMessage() && !$message->getOptional()) {
            return 'Please add a message for message with messageId = \'' . $id . '\' on page: ' . $page->getTitle();
        }
        return $message->getMessage();
    }

    public function post($slug, $name = null, $content = null)
    {
        $content = new Content();
        $content
            ->setSlug($slug)
            ->setLabel(S::humanize($slug));

        $this->em->persist($content);
        $this->em->flush();

        $editLink = $this->router->generate('app_admin_content_edit', ['id' => $content->getId()]);

        $content->setContent('New content! You can edit me <a href="' . $editLink . '">here</a>');

        $this->em->persist($content);
        $this->em->flush();

        return $content;
    }

    /**
     * @param array $context
     * @return AbstractPage
     * @throws \Twig_Error
     */
    private function getPageFromContext($context, $functionName)
    {
        if(!isset($context['page'])) {
            throw new \Twig_Error(
                sprintf('Use of %s requires that a variable \'page\' is within the current context and be of type Kunstmaan\NodeBundle\Entity.', $functionName)
            );
        }
        /** @var AbstractPage $page */
        $page = $context['page'];
        return $page;
    }
}
