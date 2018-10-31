<?php
/**
 * Delete
 *
 * @copyright
 * @author    sushynskyiar@gmail.com
 */

namespace Blog\News\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Blog\News\Model\Post;

/**
 * Class Delete
 * @package Blog\News\Controller\Adminhtml\Post
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Blog_News::post';

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
        $id = $this->getRequest()->getParam('post_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!($post = $this->_objectManager->create(Post::class)->load($id))) {
            $this->messageManager->addErrorMessage(__('Unable to proceed. Please, try again.'));
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }
        try {
            $post->delete();
            $this->messageManager->addSuccessMessage(__('Your post has been deleted successfully.'));
        } catch (\Exception $e) {
            // $this->messageManager->addErrorMessage(__('Error while trying to delete post: '));
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/*/edit', array('post_id' => $id));
        }

        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}
