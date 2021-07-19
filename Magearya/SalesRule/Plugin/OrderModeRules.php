<?php
namespace Magearya\SalesRule\Plugin\Condition;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Add order mode to actions rules
 *
 * Class OrderModeRules
 */
class OrderModeRules
{
    /**
     * @param \Magento\Rule\Model\Condition\Product\AbstractProduct $subject
     * @return \Magento\Rule\Model\Condition\Product\AbstractProduct
     */
    public function afterLoadAttributeOptions(
        \Magento\Rule\Model\Condition\Product\AbstractProduct $subject
    ) {
        $attributes = [
            'quote_item_order_mode' => __('Order Mode'),
        ];

        $subject->setAttributeOption(array_merge($subject->getAttributeOption(), $attributes));

        return $subject;
    }

    /**
     * @param \Magento\Rule\Model\Condition\Product\AbstractProduct $subject
     * @param \Magento\Framework\Model\AbstractModel $object
     */
    public function beforeValidate(
        \Magento\Rule\Model\Condition\Product\AbstractProduct $subject,
        \Magento\Framework\Model\AbstractModel $object
    ) {
        if ($object->getProduct() instanceof \Magento\Catalog\Model\Product) {
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $object->getProduct();
        } else {
            try {
                $product = $this->productRepository->getById($object->getProductId());
            } catch (NoSuchEntityException $e) {
                    $product = null;
            }
        }

        if ($product) {
            $product->setQuoteItemOrderMode($object->getOrderMode());
            $object->setProduct($product);
        }
    }

    /**
     * @param \Magento\Rule\Model\Condition\Product\AbstractProduct $subject
     * @param $result
     * @return string
     */
    public function afterGetInputType(
        \Magento\Rule\Model\Condition\Product\AbstractProduct $subject,
        $result
    ) {
        if ($subject->getAttribute()=='quote_item_order_mode') {
            $result = 'select';
        }

        return $result;
    }

    /**
     * @param \Magento\Rule\Model\Condition\Product\AbstractProduct $subject
     * @param $result
     * @return string
     */
    public function afterGetValueElementType(
        \Magento\Rule\Model\Condition\Product\AbstractProduct $subject,
        $result
    ) {
        if ($subject->getAttribute()=='quote_item_order_mode') {
            $result = 'select';
        }

        return $result;
    }

    /**
     * @param \Magento\Rule\Model\Condition\Product\AbstractProduct $subject
     * @param $result
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function afterGetValueSelectOptions(
        \Magento\Rule\Model\Condition\Product\AbstractProduct $subject,
        $result
    ) {
        if ($subject->getAttribute()=='quote_item_order_mode') {
            $options = $this->toOptionArray();
            $subject->setData('value_select_options', $options);
            $result = $subject->getData('value_select_options');
        }

        return $result;
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function toOptionArray()
    {
        
        $fulfilmentMethodData = [
		['value' => 'Digital', 'label' => 'Digital'],
		['value' => 'Live', 'label' => 'Live'],
	];

        return $fulfilmentMethodData;
    }
}

