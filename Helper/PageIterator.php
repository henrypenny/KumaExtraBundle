<?php
/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 15/03/16
 * Time: 3:14 PM
 */

namespace Hmp\KumaExtraBundle\Helper;


use Doctrine\ORM\EntityManager;

class PageIterator implements \Iterator
{
    protected $pages = [];
    protected $index = 0;

    /** @var EntityManager $em */
    protected $em;

    public function __construct($pages, $em)
    {
        foreach($pages as $page) {

            $this->pages[] = new PageItem($page, $em);
        }
        $this->index = 0;
        $this->em = $em;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        /** @var PageItem $item */
        $item = $this->pages[$this->index];
        $item->init();
        return $item;
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        if(!count($this->pages)) {
            return;
        }
        $this->index++;
        if($this->index >= count($this->pages)) {
           $this->rewind();
        }
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        if($this->index < count($this->pages)) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->index = 0;
    }
    
    public function all()
    {
        array_walk($this->pages, function(PageItem $item) {
            $item->init();
        });
        return $this->pages;
    }
}