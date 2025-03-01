<?php
/**
 * OpenMage
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2022 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Admin tag edit block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 * @deprecated after 1.3.2.3
 */
class Mage_Adminhtml_Block_Tag_Tag_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'tag_id';
        $this->_controller = 'tag';

        parent::__construct();

        if ($this->getRequest()->getParam('product_id')) {
            $this->_updateButton('back', 'onclick', "setLocation('" . $this->getUrl('*/catalog_product/edit', ['id' => $this->getRequest()->getParam('product_id')]) . "')");
        }

        if ($this->getRequest()->getParam('customer_id')) {
            $this->_updateButton('back', 'onclick', "setLocation('" . $this->getUrl('*/customer/edit', ['id' => $this->getRequest()->getParam('customer_id')]) . "')");
        }

        if ($this->getRequest()->getParam('ret', false) == 'pending') {
            $this->_updateButton('back', 'onclick', 'setLocation(\'' . $this->getUrl('*/*/pending') . '\')');
            $this->_updateButton('delete', 'onclick', 'deleteConfirm(\''
                . Mage::helper('core')->jsQuoteEscape(
                    Mage::helper('tag')->__('Are you sure you want to do this?')
                )
                . '\', \''
                . $this->getUrl(
                    '*/*/delete',
                    [$this->_objectId => $this->getRequest()->getParam($this->_objectId), 'ret' => 'pending',
                    ]
                )
                . '\')');
            Mage::register('ret', 'pending');
        }

        $this->_updateButton('save', 'label', Mage::helper('tag')->__('Save Tag'));
        $this->_updateButton('delete', 'label', Mage::helper('tag')->__('Delete Tag'));
    }

    /**
     * Add to layout accordion block
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setChild('accordion', $this->getLayout()->createBlock('adminhtml/tag_edit_accordion'));
        return $this;
    }

    /**
     * Adds to html of form html of accordion block
     *
     * @return string
     */
    public function getFormHtml()
    {
        $html = parent::getFormHtml();
        return $html . $this->getChildHtml('accordion');
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('tag_tag')->getId()) {
            return Mage::helper('tag')->__("Edit Tag '%s'", $this->escapeHtml(Mage::registry('tag_tag')->getName()));
        }
        return Mage::helper('tag')->__('New Tag');
    }
}
