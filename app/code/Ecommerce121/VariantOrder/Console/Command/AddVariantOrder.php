<?php declare(strict_types=1);

namespace Ecommerce121\VariantOrder\Console\Command;

use Magento\Eav\Api\AttributeManagementInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Add variant order attribute to all attribute sets missing it
 */
class AddVariantOrder extends Command
{
    private EavSetupFactory $eavSetupFactory;
    private AttributeManagementInterface $attributeManagement;
    private ModuleDataSetupInterface $moduleDataSetup;
    private const DO_IT = 'doit';
    private const ATTRIBUTE_CODE = 'variant_order';

    /**
     * @param EavSetupFactory $eavSetupFactory
     * @param AttributeManagementInterface $attributeManagement
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        EavSetupFactory                               $eavSetupFactory,
        AttributeManagementInterface $attributeManagement,
        ModuleDataSetupInterface                      $moduleDataSetup)
    {
        parent::__construct();
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeManagement = $attributeManagement;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('variant:add:order');
        $this->setDescription('Adds variant_order attribute on attribute sets using variant_option and variant_key');
        $this->addOption(
            self::DO_IT,
            null,
            InputOption::VALUE_NONE,
            'Create the attributes, default behavior is dry run'
        );
        parent::configure();
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);

        $dryRun = !$input->getOption(self::DO_IT);

        foreach ($attributeSetIds as $attributeSetId) {

            $attrSet = $eavSetup->getAttributeSet($entityTypeId, $attributeSetId);
            $attrs = $this->attributeManagement->getAttributes($entityTypeId, $attributeSetId);

            $variantFound = false;
            $variantOptionFound = false;
            $variantOrderPresent = false;
            foreach ($attrs as $atr) {
                if (str_starts_with($atr->getAttributeCode(), "variant")) {
                    $variantFound = true;
                    echo ($attrSet['attribute_set_name'] . " has " . $atr->getAttributeCode() . " group: " . $atr->getAttributeGroupId() . " sort order: " . $atr->getSortOrder() . "\n");
                    if ($atr->getAttributeCode() == 'variant_option') {
                        $groupId = $atr->getAttributeGroupId();
                        $sortOrder = $atr->getSortOrder() + 1;
                        $variantOptionFound = true;
                    }
                    if ($atr->getAttributeCode() == 'variant_order') {
                        $variantOrderPresent = true;
                    }
                }
            }
            if (!$variantFound) {
                echo "Variant not found in " . $attrSet['attribute_set_name'] . ". Skipping.\n";
            }
            if ($variantOrderPresent) {
                echo "Variant order already present, found in " . $attrSet['attribute_set_name'] . "\n";
                continue;
            }
            if ($variantOptionFound) {
                // Adds attribute next to variant_option
                if ($dryRun) {
                    echo "Will create attribute in sort order: " . $sortOrder . "\n";
                } else {
                    $this->attributeManagement->assign(
                        'catalog_product',
                        $attributeSetId,
                        $groupId,
                        self::ATTRIBUTE_CODE,
                        $sortOrder
                    );
                    echo "Created attribute in sort order: " . $sortOrder . "\n";
                }
            } else {
                echo "No variant option. Skipping\n";
            }
        }
        if ($dryRun) {
            echo "****** This was just a report, to create the attributes run with --doit    ******\n";
        }
    }
}


