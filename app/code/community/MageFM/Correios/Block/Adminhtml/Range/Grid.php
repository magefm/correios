<?php

class MageFM_Correios_Block_Adminhtml_Range_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('magefm_correios_range_grid');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('magefm_correios/entity_range')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => Mage::helper('magefm_correios')->__('ID'),
            'index' => 'id',
            'sortable' => false,
            'width' => '50',
        ));

        $this->addColumn('description', array(
            'header' => Mage::helper('magefm_correios')->__('Description'),
            'index' => 'description',
            'sortable' => true,
        ));

        $this->addColumn('cep_origin', array(
            'header' => Mage::helper('magefm_correios')->__('Origin'),
            'index' => 'cep_origin',
            'sortable' => true,
            'width' => '100',
        ));

        $this->addColumn('cep_destination_from', array(
            'header' => Mage::helper('magefm_correios')->__('Range from'),
            'index' => 'cep_destination_from',
            'sortable' => true,
            'width' => '100',
        ));

        $this->addColumn('cep_destination_to', array(
            'header' => Mage::helper('magefm_correios')->__('Range to'),
            'index' => 'cep_destination_to',
            'sortable' => true,
            'width' => '100',
        ));

        $this->addColumn('method', array(
            'header' => Mage::helper('magefm_correios')->__('Method'),
            'index' => 'method',
            'sortable' => true,
            'width' => '200',
            'type' => 'options',
            'options' => Mage::getModel('magefm_correios/source_methods')->toKeyValueArray(),
        ));

        $this->addColumn('weight', array(
            'header' => Mage::helper('magefm_correios')->__('Max Weight (kg)'),
            'index' => 'weight',
            'sortable' => true,
            'width' => '100',
            'type' => 'options',
            'options' => Mage::getModel('magefm_correios/source_weight')->toKeyValueArray(),
        ));

        $this->addColumn('price', array(
            'header' => Mage::helper('magefm_correios')->__('Price'),
            'index' => 'price',
            'sortable' => true,
            'width' => '100',
            'frame_callback' => array($this, 'formatPrice'),
        ));

        $this->addColumn('days', array(
            'header' => Mage::helper('magefm_correios')->__('Days'),
            'index' => 'days',
            'sortable' => true,
            'width' => '50',
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('magefm_correios')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('magefm_correios')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'action',
            'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('range_id');
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('magefm_correios')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('magefm_correios')->__('Are you sure?')
        ));

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/*', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function formatPrice($value)
    {
        return Mage::helper('core')->currency($value, true, false);
    }

}
