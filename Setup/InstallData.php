<?php
namespace Lucky\CustomAttribute\Setup;


use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface 
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var \Magento\Eav\Model\Config
     */
    private $eavConfig;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        \Magento\Eav\Model\Config $eavConfig
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $setup->startSetup();

        $attributeCode = 'selectline_id';
        $eavSetup->addAttribute(CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER, 'selectline_id', [
            'label' => 'SelectLine ID',
            'required' => false,
            'user_defined' => 1,
            'system' => 0,
            'position' => 100,
            'input' => 'text'
        ]);

        $eavSetup->addAttributeToSet(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
            null,
            $attributeCode);

        $amountId = $this->eavConfig->getAttribute(CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER, $attributeCode);
        $amountId->setData('used_in_forms', [
            'adminhtml_customer',
        ]);
        $amountId->getResource()->save($amountId);

        $setup->endSetup();
    }
}