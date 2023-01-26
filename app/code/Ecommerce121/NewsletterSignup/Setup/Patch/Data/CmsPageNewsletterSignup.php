<?php

declare(strict_types=1);

namespace Ecommerce121\NewsletterSignup\Setup\Patch\Data;

use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\ResourceModel\Page as PageResource;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CmsPageNewsletterSignup implements DataPatchInterface
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
        return [
            \Ecommerce121\NewsletterSignup\Setup\Patch\Data\DeleteUrlRewrite::class
        ];
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
     */
    public function apply(): DataPatchInterface
    {
        try {
            $cmsPage = $this->pageFactory->create();
            $this->pageResource->load($cmsPage, 'newsletter-signup', 'identifier');
            $cmsPage->addData([
                'title' => 'Newsletter Signup',
                'page_layout' => 'cms-full-width',
                'meta_keywords' => '',
                'meta_description' => '',
                'identifier' => 'newsletter-signup',
                'content_heading' => '',
                'content' => '<style>#html-body [data-pb-style=VUKGLWS]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}</style><div data-content-type="row" data-appearance="contained" data-element="main"><div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="VUKGLWS"><div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true"><div class="main">
                    <div class="wrap">
                            <h1>10% OFF</h1>
                            <h2>Your Next Order</h2>
                            <p>Sign Up For the aFe POWER Newsletter Here.<br/>
                                    You’ll Be The First To Know About Future Special Offers, Exciting New Products, Tech Videos, Motorsport News and More.</p>
                    </div>
            </div>
            {{widget type="Ecommerce121\\NewsletterSignup\\Block\\Widget\\Form" title="Join Our Newsletter"}}
            <div class="gdpr">
            aFe POWER is committed to protecting and respecting your privacy, and we’ll only use your personal information to administer your account and to provide the products and services you requested from us.  From time to time, we would like to contact you about our products and services, as well as other content that may be of interest to you.  By clicking on subscribe you are providing consent to us contacting you for this purpose.  You may unsubscribe from these communications at any time.
            </div>
            <br/><br/><br/></div></div></div>',
                'creation_time' => '2021-10-21 16:16:25',
                'update_time' => '2021-10-21 16:16:25',
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
                'created_in' => '1',
                'updated_in' => '2147483647',
                'amp_content' => '',
                'amasty_hreflang_uuid' => '',
                'amshopby_settings' => [
                    'entity_id' => '51',
                    'page_id' => '62',
                    'enabled' => '0',
                ],
                'store_id' => [
                    0 => '0',
                ],
            ]);
            $this->pageResource->save($cmsPage);
        } catch (\Exception $e) {
            throw new LocalizedException(__('Cannot save CMS Page: %1', $e->getMessage()));
        }

        return $this;
    }
}
