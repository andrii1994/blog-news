<?php
/**
 * Created by PhpStorm.
 * User: as
 * Date: 10/13/18
 * Time: 6:37 PM
 */

namespace Blog\News\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Blog\News\Model\Post;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Class InlineEdit
 * @package Blog\News\Controller\Adminhtml\Post
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     *
     */
    const ADMIN_RESOURCE = 'Blog_News::post';

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    public function __construct(Action\Context $context, JsonFactory $jsonFactory)
    {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $postId) {
                    /** @var \Blog\News\Model\Post $post */
                    $post = $this->_objectManager
                        ->create(Post::class)
                        ->load($postId);
                    try {
                        $post->setData(array_merge($post->getData(), $postItems[$postId]));
                        $post->save();
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithPostId(
                            $post,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add block title to error message
     *
     * @param Post $post
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithPostId(Post $post, $errorText)
    {
        return '[Post ID: ' . $post->getId() . '] ' . $errorText;
    }
}
