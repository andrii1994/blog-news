<?php
/**
 * Status
 *
 * @copyright
 * @author    sushynskyiar@gmail.com
 */

namespace Blog\News\Model\Post\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 * @package Blog\News\Model\Post\Source
 */
class Status implements OptionSourceInterface
{
    /**
     * @var \Blog\News\Model\Post
     */
    protected $post;

    /**
     * Constructor
     *
     * @param \Blog\News\Model\Post $post
     */
    public function __construct(\Blog\News\Model\Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->post->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
