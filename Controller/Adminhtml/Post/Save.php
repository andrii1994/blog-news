<?php
/**
 * Save
 *
 * @copyright
 * @author    sushynskyiar@gmail.com
 */

namespace Blog\News\Controller\Adminhtml\Post;

use Magento\Backend\App\Action\Context;
use Blog\News\Model\Post;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 * @package Blog\News\Controller\Adminhtml\Post
 */
class Save extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Blog_News::post';
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $coreRegistry
        // DataPersistorInterface $dataPersistor
    )
    {
        // $this->dataPersistor = $dataPersistor;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('post_id');

            if (empty($data['post_id'])) {
                $data['post_id'] = null;
            }

            /** @var \Magento\Cms\Model\Block $model */
            $model = $this->_objectManager->create(Post::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This post no longer exists.'));
                return $resultRedirect->setPath('*/*/index');
            }

            // Add custom image field to data
            if (isset($data['featured_image']) && is_array($data['featured_image'])) {
                $data['featured_image'] = $data['featured_image'][0]['name'];
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the post.'));
                // $this->dataPersistor->clear('cms_block');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['post_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/index');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the post.'));
            }

            // $this->dataPersistor->set('cms_block', $data);
            return $resultRedirect->setPath('*/*/edit', ['post_id' => $this->getRequest()->getParam('post_id')]);
        }
        return $resultRedirect->setPath('*/*/index');
    }
}
