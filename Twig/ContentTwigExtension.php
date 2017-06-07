<?php
/**
 * Created by PhpStorm.
 * User: henrypenny
 * Date: 18/02/15
 * Time: 9:04 AM
 */

namespace Hmp\KumaExtraBundle\Twig;

use Doctrine\ORM\EntityManager;
use Hmp\KumaExtraBundle\Entity\PageParts\MessagePagePart;
use Hmp\KumaExtraBundle\Twig\BaseTwigExtension;
use Kunstmaan\MediaBundle\Entity\Media;
use Kunstmaan\NodeBundle\Entity\AbstractPage;
use Kunstmaan\NodeBundle\Entity\Node;
use Kunstmaan\NodeBundle\Entity\NodeTranslation;
use Kunstmaan\NodeBundle\Entity\NodeVersion;
use Kunstmaan\NodeBundle\Helper\NodeMenuItem;
use Kunstmaan\NodeBundle\Router\SlugRouter;
use Kunstmaan\PagePartBundle\Twig\Extension\PagePartTwigExtension;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Stringy\StaticStringy as S;


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


    public function __construct(ContainerInterface $container, EntityManager $em, CacheManager $cm)
    {
        parent::__construct($container, $em);
        $this->cacheManager = $cm;
    }

    function getFilters() {
        return [
            new \Twig_SimpleFilter('img', [$this, 'img'], array()),
            new \Twig_SimpleFilter('is_video', [$this, 'isVideo'], array()),
            new \Twig_SimpleFilter('shortTags', [$this, 'shortTags'])
        ];
    }

    function getFunctions()
    {
        return array (
            'has_content' => new \Twig_SimpleFunction('has_content', array($this, 'hasContent'), array('needs_context' => true)),
            'get_content' => new \Twig_SimpleFunction('get_content', array($this, 'getContent'), array('needs_context' => true, 'is_safe' => array('all'))),
            'get_page' => new \Twig_SimpleFunction('get_page', array($this, 'getPage')),
            'get_page_by_url' => new \Twig_SimpleFunction('get_page_by_url', array($this, 'getPageByUrl'), array('needs_context' => true, 'is_safe' => array('all'))),
            'alt' => new \Twig_SimpleFunction('alt', array($this, 'alt'), array('needs_environment' => true, 'is_safe' => array('all'))),
            'stash' => new \Twig_Function_Method($this, 'stash', ['needs_context' => true, 'needs_environment' => true]),
            'isLastPP' => new \Twig_Function_Method($this, 'isLastPP', ['needs_context' => true]),
            'shortTags' => new \Twig_Function_Method($this, 'shortTags', ['needs_context' => true]),
        );
    }

    public function getPage($object)
    {
        if($object instanceof AbstractPage) {
            return $object;
        }
        else if($object instanceof Node) {
            return $object->getNodeTranslation('en', true)->getPublicNodeVersion()->getRef($this->em);
        }
        else if($object instanceof NodeTranslation) {
            return $object->getPublicNodeVersion()->getRef($this->em);
        }
        else if($object instanceof NodeVersion) {
            return $object->getRef($this->em);
        }
        else if($object instanceof NodeMenuItem) {
            return $object->getPage();
        }
        else {
            throw new \Exception('Argument is not instance of Node, NodeTranslation or NodeVersion. Argument instance of ' . get_class($object));
        }
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
    public function hasContent($context, $id, $pathOverride = null)
    {
        $page = $this->getCurrentPage($context, $pathOverride);

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
    public function getContent($context, $id, $pathOverride = null)
    {
        $page = $this->getCurrentPage($context, $pathOverride);
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
    
    public function getCurrentPage($context, $pathOverride = null)
    {
        if ($pathOverride) {
            try {
                $nt = $this->getNodeTranslationForCurrentUrl($pathOverride);
                $page = $nt->getPublicNodeVersion()->getRef($this->container->get('doctrine.orm.entity_manager'));
            }
            catch(\Exception $e) {
                return null;
            }
        } else {
            $page = $this->getPageFromContext($context, 'get_content');
        }
        return $page;
    }
    
    /**
     * @param $context
     * @param $path
     * @return AbstractPage|\Kunstmaan\NodeBundle\Entity\HasNodeInterface
     */
    public function getPageByUrl($context, $path)
    {
        $page = $this->getCurrentPage($context, $path);
        return $page;
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
    /**
     * @param $pathInfo
     * @return NodeTranslation
     * @throws ResourceNotFoundException
     */
    public function getNodeTranslationForCurrentUrl($pathInfo)
    {
        if($pathInfo instanceof Request) {
            $pathInfo = $pathInfo->getPathInfo();
        }
        if($pathInfo != '/') {
            $pathInfo = rtrim($pathInfo, '/');
        }
        $router = new SlugRouter($this->container);
        try {
            $result = $router->match($pathInfo);
        }
        catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException(__CLASS__ . '::' . __FUNCTION__ . ' (get_node_translation_for_current_url), could not find nodeTranslation for path: ' . $pathInfo, 0, $e);
        }
        /** @var NodeTranslation $nt */
        $nt = $result['_nodeTranslation'];
        return $nt;
    }


    /**
     * @param $context
     * @param $values
     * @return mixed
     */
    public function alt(\Twig_Environment $env, array $values)
    {
        if(!count($values)) {
            throw new \Exception('$values argument is empty, requires array of strings');
        }
        $key = '_alt_'. hash("md5", implode('|', $values));

        $app = $env->getGlobals()['app'];

        /** @var \Iterator $generator */
        if(!$app->getRequest()->attributes->has($key)) {
            $generator = function() use ($values) {
                $index = 0;
                while(true) {
                    if ($index < count($values)) {
                        $yield = $values[$index];
                        $index++;
                    } else {
                        $index = 0;
                        $yield = $values[$index];
                        $index = 1;
                    }
                    yield $yield;
                }
            };

            $app->getRequest()->attributes->set($key, $generator());
        }
        $generator = $app->getRequest()->attributes->get($key);
        $res = $generator->current();
        $generator->next();
        return $res;
    }


    public function stash(\Twig_Environment $env, $ctx, $id, $value = null)
    {
        /** @var AppVariable $app */
        $app = $env->getGlobals()['app'];
        if($value != null) {
            $app->getRequest()->attributes->add([$id => $value]);
            return '';
        }
        else {
            $value = $app->getRequest()->attributes->get($id);
        }
        return $value;
    }

    public function isLastPP($ctx)
    {
        if(!isset($ctx['page']) || !isset($ctx['pageparts']) || !isset($ctx['resource'])) {
            throw new \Exception('Not in page part template');
        }

        /** @var AbstractPagePart[] $pageparts */
        $pageparts = $ctx['pageparts'];
        /** @var AbstractPagePart $page */
        $pagepart = $ctx['resource'];

        if(end($pageparts) == $pagepart) {
            return true;
        }
        else {
            return false;
        }
    }

    public function shortTags($text, $tag, $start, $end)
    {
        $text = str_replace('{' . $tag . '}', $start, $text);
        $text = str_replace('{/' . $tag . '}', $end, $text);
        return $text;
    }

    /**
     * @param Media $media
     * @param null $width
     * @param null $height
     * @param string $mode
     * @param bool $allow_upscale
     * @return string
     * @throws \Exception
     */
    public function img($media, $width = null, $height = null, $mode = 'outbound', $allow_upscale = false)
    {
        if($media === null) {
            return sprintf("//unsplash.it/%sx%s", $width, $height);
        }

        $passThroughTypes = [
            'image/gif'
        ];

        if(in_array($media->getContentType(), $passThroughTypes)) {
            return $media->getUrl();
        }

        if (is_string($media)) {
            $path = $media;
        }
        else if($media instanceof Media || method_exists($media, 'getUrl')) {
            $path = $media->getUrl();
        }
        else {
            throw new \Exception('invalid argument ' . $media);
        }

        $thumbnail = [];

        if($width || $height) {

            $width = $width?$width:$height;
            $height = $height?$height:$width;

            $thumbnail = array_merge($thumbnail, ['size' => [$width, $height]]);
        }

        if($mode) {
            $thumbnail = array_merge($thumbnail, compact('mode'));
        }

        if($allow_upscale) {
            $thumbnail = array_merge($thumbnail, compact('allow_upscale'));
        }

        return $this->cacheManager->getBrowserPath($path, 'img', compact('thumbnail'));
    }

    public function isVideo(Media $media)
    {
        $videoTypes = [
            'video/mp4'
        ];

        $result = in_array($media->getContentType(), $videoTypes);

        return $result;
    }
}

class GeneratorHolder {

    public function __construct($gen)
    {
        $this->gen = $gen;
    }

    public $gen;
}
