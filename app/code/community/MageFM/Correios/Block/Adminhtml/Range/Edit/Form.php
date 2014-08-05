<?php

class MageFM_Correios_Block_Adminhtml_Range_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $model = Mage::registry('magefm_correios_range');

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ));

        $fieldset = $form->addFieldset('range_form', array(
            'legend' => Mage::helper('magefm_correios')->__('General Information'),
            'class' => 'fieldset-wide',
        ));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }

        $fieldset->addField('description', 'text', array(
            'label' => Mage::helper('magefm_correios')->__('Description'),
            'name' => 'description',
        ));

        $fieldset->addField('cep_origin', 'text', array(
            'label' => Mage::helper('magefm_correios')->__('Origin'),
            'name' => 'cep_origin',
        ));

        $fieldset->addField('cep_destination_from', 'text', array(
            'label' => Mage::helper('magefm_correios')->__('Range from'),
            'name' => 'cep_destination_from',
        ));

        $fieldset->addField('cep_destination_to', 'text', array(
            'label' => Mage::helper('magefm_correios')->__('Range to'),
            'name' => 'cep_destination_to',
        ));

        $fieldset->addField('method', 'select', array(
            'label' => Mage::helper('magefm_correios')->__('Method'),
            'name' => 'method',
            'options' => Mage::getModel('magefm_correios/source_methods')->toKeyValueArray(),
        ));

        $fieldset->addField('weight', 'select', array(
            'label' => Mage::helper('magefm_correios')->__('Max Weight (kg)'),
            'name' => 'weight',
            'options' => Mage::getModel('magefm_correios/source_weight')->toKeyValueArray(),
        ));

        $fieldset->addField('price', 'text', array(
            'label' => Mage::helper('magefm_correios')->__('Price'),
            'name' => 'price',
        ));

        $fieldset->addField('days', 'text', array(
            'label' => Mage::helper('magefm_correios')->__('Days'),
            'name' => 'days',
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
