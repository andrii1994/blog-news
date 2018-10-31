<?php
/**
 * Post
 *
 * @copyright
 * @author    sushynskyiar@gmail.com
 */

namespace Blog\News\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use \Blog\News\Model\PostFactory;

/**
 * Class Post
 * @package Blog\News\Block
 *
 * todo: all frontend, test block
 */
class Post extends Template
{
    protected $_postFactory;

    public function __construct(Context $context, PostFactory $postFactory, array $data = [])
    {
        $this->_postFactory = $postFactory;
        parent::__construct($context, $data);
    }


    public function getCollection()
    {
        return $this->_postFactory->create()->getCollection();
    }
}
