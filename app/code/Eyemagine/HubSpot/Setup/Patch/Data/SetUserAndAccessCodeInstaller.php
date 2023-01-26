<?php

declare(strict_types=1);

namespace Eyemagine\HubSpot\Setup\Patch\Data;

use Eyemagine\HubSpot\Model\Config\Backend\Keys;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class SetUserAndAccessCodeInstaller implements DataPatchInterface
{
    const USER_KEY_VALUE = '9db42d8b9c1d5950207512a52f54ebc9';
    const ACCESS_CODE_VALUE = '9840a8e818be2ba4f4afc8ecfbf86308';

    /**
     * @var WriterInterface
     */
    private WriterInterface $writer;

    /**
     * @param WriterInterface $writer
     */
    public function __construct(
        WriterInterface $writer
    )
    {
        $this->writer = $writer;
    }

    /**
     * @return SetUserAndAccessCodeInstaller
     */
    public function apply(): SetUserAndAccessCodeInstaller
    {
        $this->writer->save(
            Keys::XML_PATH_EYEMAGINE_HUBSPOT_USER_KEY,
            self::USER_KEY_VALUE
        );
        $this->writer->save(
            Keys::XML_PATH_EYEMAGINE_HUBSPOT_PASS_CODE,
            self::ACCESS_CODE_VALUE
        );

        return $this;
    }


    /**
     * @return array|string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }


    /**
     * @return array|string[]
     */
    public function getAliases(): array
    {
        return [];
    }


}