<?php

declare(strict_types=1);

namespace Ecommerce121\NewsletterSignup\Setup\Patch\Data;

use Exception;
use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\ResourceModel\Page as PageResource;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class ThankYouPage implements DataPatchInterface
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var PageResource
     */
    private $pageResource;

    /**
     * @param PageFactory $pageFactory
     * @param PageResource $pageResource
     */
    public function __construct(
        PageFactory $pageFactory,
        PageResource $pageResource
    ) {
        $this->pageFactory = $pageFactory;
        $this->pageResource = $pageResource;
    }

    /**
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @return DataPatchInterface
     * @throws LocalizedException
     */
    public function apply(): DataPatchInterface
    {
        try {
            $cmsPage = $this->pageFactory->create();
            $this->pageResource->load($cmsPage, 'signup/thank-you', 'identifier');
            $cmsPage->addData([
                'title' => 'signup-thank-you',
                'page_layout' => 'cms-full-width',
                'meta_keywords' => '',
                'meta_description' => '',
                'identifier' => 'signup/thank-you',
                'content_heading' => '',
                'content' => '<style>#html-body [data-pb-style=S3GONKH]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}</style><div data-content-type="row" data-appearance="contained" data-element="main"><div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="S3GONKH"><div data-content-type="html" data-appearance="default" data-element="main"><div><style>
                    .page-header-container,
                    .footer-container,
                    .alert-block,
                    .breadcrumbs { display: none; }
                    .thankyou {
                            max-width: 500px;
                            width: 100%;
                            margin: 0 auto;
                            margin-top: 50px;
                            padding: 50px;
                            border: 1px solid #ccc;
                            text-align: center;
                    }
                    .thankyou a {
                            background: #ca0120;
                            padding: 20px;
                            display: block;
                            color: white;
                            font-size: 22px;
                    }
            </style>
            <div class="thankyou">
            	<h1>Success!</h1>
            	<a href="/">Go Back</a>
            </div></div></div></div></div>',
                'creation_time' => '2021-10-20 08:19:27',
                'update_time' => '2021-10-20 08:36:05',
                'is_active' => '1',
                'sort_order' => '0',
                'layout_update_xml' => null,
                'custom_theme' => '',
                'custom_root_template' => null,
                'custom_layout_update_xml' => null,
                'layout_update_selected' => null,
                'custom_theme_from' => null,
                'custom_theme_to' => null,
                'meta_title' => '',
                'website_root' => '1',
                'row_id' => '59',
                'created_in' => '1',
                'updated_in' => '2147483647',
                'amp_content' => '',
                'amasty_hreflang_uuid' => '',
                'amshopby_settings' => [
                    'entity_id' => '50',
                    'page_id' => '59',
                    'enabled' => '0',
                ],
                'store_id' => [
                    0 => '0',
                ],
            ]);
            $this->pageResource->save($cmsPage);

            return $this;
        } catch (Exception $e) {
            throw new LocalizedException(__('Cannot save CMS Page: %1', $e->getMessage()));
        }
    }
}
