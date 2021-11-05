<?php /** @noinspection PhpHierarchyChecksInspection */

namespace Perspective\Test1\ViewModel;

use Magento\Catalog\Helper\ImageFactory;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Customer\Model\ResourceModel\Group\CollectionFactory as GroupCollectionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Payment\Helper\Data;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Shipping\Model\Config\Source\Allmethods;

class Product extends Template implements ArgumentInterface
{
    protected $_productRepository;
    protected $_imageHelperFactory;
    protected $_customerFactory;
    protected $_orderCollectionFactory;
    protected $_collectionFactory;
    protected $_paymentHelper;
    protected $_shippingAllMethods;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ImageFactory               $imageHelperFactory,
        CustomerCollectionFactory  $customerFactory,
        OrderCollectionFactory     $orderCollectionFactory,
        GroupCollectionFactory     $collectionFactory,
        Data                       $paymentHelper,
        Context                    $context,
        Allmethods                 $shippingAllMethods,
        array                      $data = []
    )
    {
        $this->_productRepository = $productRepository;
        $this->_imageHelperFactory = $imageHelperFactory;
        $this->_customerFactory = $customerFactory;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->_paymentHelper = $paymentHelper;
        $this->_shippingAllMethods = $shippingAllMethods;
        parent::__construct($context, $data);
    }

    public function getProductById($productId): ?ProductInterface
    {
        if (is_null($productId)) {
            return null;
        }
        return $this->_productRepository->getById($productId);
    }

    public function getProductInfo()
    {
        $prodObj = $this->getProductById(1);
        return $prodObj->getExtensionAttributes()->getStockItem();
    }

    public function getImageInfo()
    {
        $product = $this->getProductById(1);
        return $this->_imageHelperFactory->create()->init($product, 'product_thumbnail_image');
    }

    public function getCustomerCollection()
    {
        return $this->_customerFactory->create();
    }

    public function collectionOrders($field, $condition)
    {
        return $this->_orderCollectionFactory->create()->addAttributeToSelect('*')->addFieldToFilter($field, $condition);
    }

    public function getCustomerGroup()
    {
        return $this->_collectionFactory->create();

    }

    public function getAllPaymentMethods()
    {
        return $this->_paymentHelper->getPaymentMethods();
    }

    public function getAllShippingMethods()
    {
        $methods = $this->_shippingAllMethods->toOptionArray();
        if (array_key_exists(0, $methods))
        {
            unset( $methods [0]);
        }
        return $methods;
    }
}
