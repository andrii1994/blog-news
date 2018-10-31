<?php
/**
 * DataProvider
 *
 * @copyright
 * @author    sushynskyiar@gmail.com
 */

namespace Blog\News\Model\Post;

use Blog\News\Model\ResourceModel\Post\CollectionFactory;
// todo: check CMS Block DataProvider for use DataPersistorInterface
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 * @package Blog\News\Model\Post
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected $_loadedData;

    /**
     * DataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $postCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $postCollectionFactory,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $postCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();

        foreach ($items as $post) {
            $this->_loadedData[$post->getId()] = $post->getData();

            if ($post->getData('featured_image')) {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
                $currentStore = $storeManager->getStore();
                $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

                $imageName = $this->_loadedData[$post->getId()]['featured_image'];
                unset($this->_loadedData[$post->getId()]['featured_image']);
                $this->_loadedData[$post->getId()]['featured_image'][0]['name'] = $imageName;
                $this->_loadedData[$post->getId()]['featured_image'][0]['url'] = $mediaUrl . 'blog_news/image/tmp/' . $imageName;

                $directoryList = $objectManager->get('\Magento\Framework\App\Filesystem\DirectoryList');
                $mediaPath = $directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                $basePath = $mediaPath . '/blog_news/image/tmp/' . $imageName;
                if (file_exists($basePath)) {
                    $this->_loadedData[$post->getId()]['featured_image'][0]['size'] = filesize($basePath);
                }
            }
        }
        return $this->_loadedData;
    }
}
