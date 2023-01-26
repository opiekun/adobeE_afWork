<?php
return [
    'scopes' => [
        'websites' => [
            'admin' => [
                'website_id' => '0',
                'code' => 'admin',
                'name' => 'Admin',
                'sort_order' => '0',
                'default_group_id' => '0',
                'is_default' => '0'
            ],
            'base' => [
                'website_id' => '1',
                'code' => 'base',
                'name' => 'Main Website',
                'sort_order' => '0',
                'default_group_id' => '1',
                'is_default' => '1'
            ]
        ],
        'groups' => [
            [
                'group_id' => '0',
                'website_id' => '0',
                'name' => 'Default',
                'root_category_id' => '0',
                'default_store_id' => '0',
                'code' => 'default'
            ],
            [
                'group_id' => '1',
                'website_id' => '1',
                'name' => 'Main Website Store',
                'root_category_id' => '2',
                'default_store_id' => '1',
                'code' => 'main_website_store'
            ]
        ],
        'stores' => [
            'admin' => [
                'store_id' => '0',
                'code' => 'admin',
                'website_id' => '0',
                'group_id' => '0',
                'name' => 'Admin',
                'sort_order' => '0',
                'is_active' => '1'
            ],
            'default' => [
                'store_id' => '1',
                'code' => 'default',
                'website_id' => '1',
                'group_id' => '1',
                'name' => 'Default Store View',
                'sort_order' => '0',
                'is_active' => '1'
            ]
        ]
    ],
    'system' => [
        'default' => [
            'advanced' => [
                'modules_disable_output' => [
                    'Magento_Banner' => '1'
                ]
            ],
            'general' => [
                'locale' => [
                    'code' => 'en_US'
                ]
            ],
            'dev' => [
                'static' => [
                    'sign' => '1'
                ],
                'front_end_development_workflow' => [
                    'type' => 'server_side_compilation'
                ],
                'template' => [
                    'allow_symlink' => null,
                    'minify_html' => '0'
                ],
                'js' => [
                    'merge_files' => '0',
                    'enable_js_bundling' => null,
                    'minify_files' => '0',
                    'move_script_to_bottom' => '0',
                    'translate_strategy' => 'dictionary',
                    'session_storage_logging' => '0',
                    'minify_exclude' => [
                        'tiny_mce' => '/tiny_mce/',
                        'cardinal_commerce' => '/v1/songbird'
                    ]
                ],
                'css' => [
                    'merge_css_files' => null,
                    'minify_files' => '1',
                    'use_css_critical_path' => '0',
                    'minify_exclude' => [
                        'tiny_mce' => '/tiny_mce/'
                    ]
                ]
            ]
        ],
        'stores' => [

        ],
        'websites' => [

        ]
    ],
    'modules' => [
        'Magento_AdminAnalytics' => 1,
        'Magento_Store' => 1,
        'Magento_AdminGwsConfigurableProduct' => 1,
        'Magento_AdminGwsStaging' => 1,
        'Magento_Directory' => 1,
        'Magento_AdobeIms' => 1,
        'Magento_AdobeImsApi' => 1,
        'Magento_AdobeStockAdminUi' => 1,
        'Magento_MediaGallery' => 1,
        'Magento_AdobeStockAssetApi' => 1,
        'Magento_AdobeStockClient' => 1,
        'Magento_AdobeStockClientApi' => 1,
        'Magento_AdobeStockImage' => 1,
        'Magento_Theme' => 1,
        'Magento_AdobeStockImageApi' => 1,
        'Magento_Eav' => 1,
        'Magento_Customer' => 1,
        'Magento_AdvancedPricingImportExport' => 1,
        'Magento_Rule' => 1,
        'Magento_AdminNotification' => 1,
        'Magento_Backend' => 1,
        'Magento_Amqp' => 1,
        'Magento_AmqpStore' => 1,
        'Magento_Config' => 1,
        'Magento_User' => 1,
        'Magento_Authorization' => 1,
        'Magento_Indexer' => 1,
        'Magento_AwsS3CustomerCustomAttributes' => 1,
        'Magento_Variable' => 1,
        'Magento_Cms' => 1,
        'Magento_Backup' => 1,
        'Magento_Catalog' => 1,
        'Magento_Quote' => 1,
        'Magento_SalesSequence' => 1,
        'Magento_Payment' => 1,
        'Magento_Bundle' => 1,
        'Magento_GraphQl' => 1,
        'Magento_BundleImportExport' => 1,
        'Magento_BundleImportExportStaging' => 1,
        'Magento_DataExporter' => 1,
        'Magento_CatalogInventory' => 1,
        'Magento_CacheInvalidate' => 1,
        'Magento_MediaStorage' => 1,
        'Magento_Sales' => 1,
        'Magento_AdvancedCatalog' => 1,
        'Magento_Security' => 1,
        'Magento_CmsGraphQl' => 1,
        'Magento_EavGraphQl' => 1,
        'Magento_CatalogDataExporter' => 1,
        'Magento_Search' => 1,
        'Magento_StoreGraphQl' => 1,
        'Magento_CatalogImportExport' => 1,
        'Magento_CatalogImportExportStaging' => 1,
        'Magento_Widget' => 1,
        'Magento_CatalogInventoryDataExporter' => 1,
        'Magento_CatalogInventoryGraphQl' => 1,
        'Magento_CatalogRule' => 1,
        'Magento_CatalogPageBuilderAnalytics' => 1,
        'Magento_CatalogPageBuilderAnalyticsStaging' => 1,
        'Magento_CatalogUrlRewrite' => 1,
        'Magento_Checkout' => 1,
        'Magento_CustomerCustomAttributes' => 1,
        'Magento_Msrp' => 1,
        'Magento_CatalogRuleGraphQl' => 1,
        'Magento_SalesRule' => 1,
        'Magento_Captcha' => 1,
        'Magento_Downloadable' => 1,
        'Magento_Ui' => 1,
        'Magento_GiftCard' => 1,
        'Magento_ServicesId' => 1,
        'Magento_Wishlist' => 1,
        'Magento_UrlRewrite' => 1,
        'Magento_CatalogGraphQl' => 1,
        'Magento_CatalogEvent' => 1,
        'Magento_Robots' => 1,
        'Magento_ConfigurableProduct' => 1,
        'Magento_CheckoutAddressSearch' => 1,
        'Magento_GiftRegistry' => 1,
        'Magento_CheckoutAgreements' => 1,
        'Magento_CheckoutAgreementsGraphQl' => 1,
        'Magento_Staging' => 1,
        'Magento_CloudComponents' => 1,
        'Magento_MediaGalleryUi' => 1,
        'Magento_CatalogCmsGraphQl' => 1,
        'Magento_CmsPageBuilderAnalytics' => 1,
        'Magento_CmsPageBuilderAnalyticsStaging' => 1,
        'Magento_VersionsCms' => 1,
        'Magento_CmsUrlRewrite' => 1,
        'Magento_CmsUrlRewriteGraphQl' => 1,
        'Magento_CompareListGraphQl' => 1,
        'Magento_Integration' => 1,
        'Magento_ConfigurableImportExport' => 1,
        'Magento_CatalogRuleConfigurable' => 1,
        'Magento_ConfigurableProductDataExporter' => 1,
        'Magento_QuoteGraphQl' => 1,
        'Magento_ConfigurableProductSales' => 1,
        'Magento_PageCache' => 1,
        'Magento_Contact' => 1,
        'Magento_Cookie' => 1,
        'Magento_Cron' => 1,
        'Magento_Csp' => 1,
        'Magento_CurrencySymbol' => 1,
        'Magento_CustomAttributeManagement' => 1,
        'Magento_AdvancedCheckout' => 1,
        'Magento_Analytics' => 1,
        'Magento_CustomerBalance' => 1,
        'Magento_CustomerBalanceGraphQl' => 1,
        'Magento_CustomerSegment' => 1,
        'Magento_DownloadableGraphQl' => 1,
        'Magento_CustomerFinance' => 1,
        'Magento_CustomerGraphQl' => 1,
        'Magento_CustomerImportExport' => 1,
        'Magento_CatalogWidget' => 1,
        'Magento_BundleProductDataExporter' => 1,
        'Magento_DataServices' => 1,
        'Magento_Multishipping' => 1,
        'Magento_Deploy' => 1,
        'Magento_Developer' => 1,
        'Magento_Dhl' => 1,
        'Magento_AdvancedSearch' => 1,
        'Magento_DirectoryGraphQl' => 1,
        'Magento_ProductAlert' => 1,
        'Magento_CustomerDownloadableGraphQl' => 1,
        'Magento_ImportExport' => 1,
        'Magento_TargetRule' => 1,
        'Magento_AdvancedRule' => 1,
        'Magento_CatalogCustomerGraphQl' => 1,
        'Magento_CatalogSearch' => 1,
        'Magento_Elasticsearch' => 1,
        'Magento_Elasticsearch6' => 1,
        'Magento_WebsiteRestriction' => 1,
        'Magento_Email' => 1,
        'Magento_EncryptionKey' => 1,
        'Magento_Enterprise' => 1,
        'Magento_Fedex' => 1,
        'Magento_Tax' => 1,
        'Magento_GiftCardAccount' => 1,
        'Magento_GiftCardAccountGraphQl' => 1,
        'Magento_WishlistGraphQl' => 1,
        'Magento_GiftCardImportExport' => 1,
        'Magento_VisualMerchandiser' => 1,
        'Magento_GiftMessage' => 1,
        'Magento_GiftMessageGraphQl' => 1,
        'Magento_GiftMessageStaging' => 1,
        'Magento_CatalogPermissions' => 1,
        'Magento_GiftWrapping' => 1,
        'Magento_GiftWrappingGraphQl' => 1,
        'Magento_GiftWrappingStaging' => 1,
        'Magento_GoogleAdwords' => 1,
        'Magento_GoogleAnalytics' => 1,
        'Magento_GoogleOptimizer' => 1,
        'Magento_GoogleOptimizerStaging' => 1,
        'Magento_GoogleShoppingAds' => 1,
        'Magento_Banner' => 1,
        'Magento_BundleGraphQl' => 1,
        'Magento_GraphQlCache' => 1,
        'Magento_GroupedProduct' => 1,
        'Magento_GroupedImportExport' => 1,
        'Magento_GroupedCatalogInventory' => 1,
        'Magento_GroupedProductGraphQl' => 1,
        'Magento_GroupedProductStaging' => 1,
        'Magento_DownloadableImportExport' => 1,
        'Magento_Sitemap' => 1,
        'Magento_InstantPurchase' => 1,
        'Magento_CatalogAnalytics' => 1,
        'Magento_Inventory' => 1,
        'Magento_InventoryAdminUi' => 1,
        'Magento_InventoryAdvancedCheckout' => 1,
        'Magento_InventoryApi' => 1,
        'Magento_InventoryBundleImportExport' => 1,
        'Magento_InventoryBundleProduct' => 1,
        'Magento_InventoryBundleProductAdminUi' => 1,
        'Magento_InventoryBundleProductIndexer' => 1,
        'Magento_InventoryCatalog' => 1,
        'Magento_InventorySales' => 1,
        'Magento_InventoryCatalogAdminUi' => 1,
        'Magento_InventoryCatalogApi' => 1,
        'Magento_InventoryCatalogFrontendUi' => 1,
        'Magento_InventoryCatalogSearch' => 1,
        'Magento_InventoryConfigurableProduct' => 1,
        'Magento_InventoryConfigurableProductAdminUi' => 1,
        'Magento_InventoryConfigurableProductFrontendUi' => 1,
        'Magento_InventoryConfigurableProductIndexer' => 1,
        'Magento_InventoryConfiguration' => 1,
        'Magento_InventoryConfigurationApi' => 1,
        'Magento_InventoryDistanceBasedSourceSelection' => 1,
        'Magento_InventoryDistanceBasedSourceSelectionAdminUi' => 1,
        'Magento_InventoryDistanceBasedSourceSelectionApi' => 1,
        'Magento_InventoryElasticsearch' => 1,
        'Magento_InventoryExportStockApi' => 1,
        'Magento_InventoryIndexer' => 1,
        'Magento_InventorySalesApi' => 1,
        'Magento_InventoryGroupedProduct' => 1,
        'Magento_InventoryGroupedProductAdminUi' => 1,
        'Magento_InventoryGroupedProductIndexer' => 1,
        'Magento_InventoryImportExport' => 1,
        'Magento_InventoryInStorePickupApi' => 1,
        'Magento_InventoryInStorePickupAdminUi' => 1,
        'Magento_InventorySourceSelectionApi' => 1,
        'Magento_InventoryInStorePickup' => 1,
        'Magento_InventoryInStorePickupGraphQl' => 1,
        'Magento_Shipping' => 1,
        'Magento_InventoryInStorePickupShippingApi' => 1,
        'Magento_InventoryInStorePickupQuoteGraphQl' => 1,
        'Magento_InventoryInStorePickupSales' => 1,
        'Magento_InventoryInStorePickupSalesApi' => 1,
        'Magento_InventoryInStorePickupQuote' => 1,
        'Magento_InventoryInStorePickupShipping' => 1,
        'Magento_InventoryInStorePickupShippingAdminUi' => 1,
        'Magento_InventoryInStorePickupMultishipping' => 1,
        'Magento_Webapi' => 1,
        'Magento_InventoryCache' => 1,
        'Magento_InventoryLowQuantityNotification' => 1,
        'Magento_Reports' => 1,
        'Magento_InventoryLowQuantityNotificationApi' => 1,
        'Magento_InventoryMultiDimensionalIndexerApi' => 1,
        'Magento_InventoryProductAlert' => 1,
        'Magento_InventoryRequisitionList' => 1,
        'Magento_InventoryReservations' => 1,
        'Magento_InventoryReservationCli' => 1,
        'Magento_InventoryReservationsApi' => 1,
        'Magento_InventoryExportStock' => 1,
        'Magento_InventorySalesAdminUi' => 1,
        'Magento_InventoryGraphQl' => 1,
        'Magento_InventorySalesFrontendUi' => 1,
        'Magento_InventorySetupFixtureGenerator' => 1,
        'Magento_InventoryShipping' => 1,
        'Magento_InventoryShippingAdminUi' => 1,
        'Magento_InventorySourceDeductionApi' => 1,
        'Magento_InventorySourceSelection' => 1,
        'Magento_InventoryInStorePickupFrontend' => 1,
        'Magento_InventorySwatchesFrontendUi' => 1,
        'Magento_InventoryVisualMerchandiser' => 1,
        'Magento_InventoryWishlist' => 1,
        'Magento_Invitation' => 1,
        'Magento_LayeredNavigation' => 1,
        'Magento_LayeredNavigationStaging' => 1,
        'Magento_Logging' => 1,
        'Magento_LoginAsCustomer' => 1,
        'Magento_LoginAsCustomerAdminUi' => 1,
        'Magento_LoginAsCustomerApi' => 1,
        'Magento_LoginAsCustomerAssistance' => 1,
        'Magento_LoginAsCustomerFrontendUi' => 1,
        'Magento_LoginAsCustomerGraphQl' => 1,
        'Magento_LoginAsCustomerLog' => 1,
        'Magento_LoginAsCustomerLogging' => 1,
        'Magento_LoginAsCustomerPageCache' => 1,
        'Magento_LoginAsCustomerQuote' => 1,
        'Magento_LoginAsCustomerSales' => 1,
        'Magento_LoginAsCustomerWebsiteRestriction' => 1,
        'Magento_Marketplace' => 1,
        'Magento_MediaContent' => 1,
        'Magento_MediaContentApi' => 1,
        'Magento_MediaContentCatalog' => 1,
        'Magento_MediaContentCatalogStaging' => 1,
        'Magento_MediaContentCms' => 1,
        'Magento_MediaContentSynchronization' => 1,
        'Magento_MediaContentSynchronizationApi' => 1,
        'Magento_MediaContentSynchronizationCatalog' => 1,
        'Magento_MediaContentSynchronizationCms' => 1,
        'Magento_AdobeStockAsset' => 1,
        'Magento_MediaGalleryApi' => 1,
        'Magento_MediaGalleryCatalog' => 1,
        'Magento_MediaGalleryCatalogIntegration' => 1,
        'Magento_MediaGalleryCatalogUi' => 1,
        'Magento_MediaGalleryCmsUi' => 1,
        'Magento_MediaGalleryIntegration' => 1,
        'Magento_MediaGalleryMetadata' => 1,
        'Magento_MediaGalleryMetadataApi' => 1,
        'Magento_MediaGalleryRenditions' => 1,
        'Magento_MediaGalleryRenditionsApi' => 1,
        'Magento_MediaGallerySynchronization' => 1,
        'Magento_MediaGallerySynchronizationApi' => 1,
        'Magento_MediaGallerySynchronizationMetadata' => 1,
        'Magento_AdobeStockImageAdminUi' => 1,
        'Magento_MediaGalleryUiApi' => 1,
        'Magento_Weee' => 1,
        'Magento_MessageQueue' => 1,
        'Magento_CatalogStaging' => 1,
        'Magento_MsrpConfigurableProduct' => 1,
        'Magento_MsrpGroupedProduct' => 1,
        'Magento_MsrpStaging' => 1,
        'Magento_MultipleWishlist' => 1,
        'Magento_SalesGraphQl' => 1,
        'Magento_DataServicesMultishipping' => 1,
        'Magento_MysqlMq' => 1,
        'Magento_NewRelicReporting' => 1,
        'Magento_Newsletter' => 1,
        'Magento_NewsletterGraphQl' => 1,
        'Magento_OfflinePayments' => 1,
        'Magento_OfflineShipping' => 1,
        'Magento_BannerCustomerSegment' => 1,
        'Magento_PageBuilder' => 1,
        'Magento_PageBuilderAnalytics' => 1,
        'Magento_ProductRecommendationsAdmin' => 1,
        'Magento_CatalogStagingPageBuilder' => 1,
        'Magento_ParentProductDataExporter' => 1,
        'Magento_AdminGws' => 1,
        'Magento_PaymentStaging' => 1,
        'Magento_Vault' => 1,
        'Magento_Paypal' => 1,
        'Magento_PaypalGraphQl' => 1,
        'Magento_PaypalOnBoarding' => 1,
        'Magento_Persistent' => 1,
        'Magento_PersistentHistory' => 1,
        'Magento_PricePermissions' => 1,
        'Magento_DownloadableStaging' => 1,
        'Magento_ProductOverrideDataExporter' => 1,
        'Magento_ProductRecommendationsLayout' => 1,
        'Magento_PageBuilderProductRecommendations' => 1,
        'Magento_CatalogSyncAdmin' => 1,
        'Magento_ProductVariantDataExporter' => 1,
        'Magento_ProductVideo' => 1,
        'Magento_ProductVideoStaging' => 1,
        'Magento_PromotionPermissions' => 1,
        'Magento_QueryXml' => 1,
        'Magento_BannerPageBuilderAnalytics' => 1,
        'Magento_QuoteAnalytics' => 1,
        'Magento_QuoteBundleOptions' => 1,
        'Magento_QuoteConfigurableOptions' => 1,
        'Magento_QuoteDownloadableLinks' => 1,
        'Magento_QuoteGiftCardOptions' => 1,
        'Magento_ConfigurableProductGraphQl' => 1,
        'Magento_ReCaptchaAdminUi' => 1,
        'Magento_ReCaptchaCheckout' => 1,
        'Magento_ReCaptchaContact' => 1,
        'Magento_ReCaptchaCustomer' => 1,
        'Magento_ReCaptchaFrontendUi' => 1,
        'Magento_ReCaptchaMigration' => 1,
        'Magento_ReCaptchaNewsletter' => 1,
        'Magento_ReCaptchaPaypal' => 1,
        'Magento_ReCaptchaReview' => 1,
        'Magento_ReCaptchaSendFriend' => 1,
        'Magento_ReCaptchaUi' => 1,
        'Magento_ReCaptchaUser' => 1,
        'Magento_ReCaptchaValidation' => 1,
        'Magento_ReCaptchaValidationApi' => 1,
        'Magento_ReCaptchaVersion2Checkbox' => 1,
        'Magento_ReCaptchaVersion2Invisible' => 1,
        'Magento_ReCaptchaVersion3Invisible' => 1,
        'Magento_RelatedProductGraphQl' => 1,
        'Magento_ReleaseNotification' => 1,
        'Magento_Reminder' => 1,
        'Magento_RemoteStorage' => 1,
        'Magento_RemoteStorageCommerce' => 1,
        'Magento_InventoryLowQuantityNotificationAdminUi' => 1,
        'Magento_RequireJs' => 1,
        'Magento_ResourceConnections' => 1,
        'Magento_Review' => 1,
        'Magento_ReviewAnalytics' => 1,
        'Magento_ReviewGraphQl' => 1,
        'Magento_ReviewStaging' => 1,
        'Magento_Reward' => 1,
        'Magento_RewardGraphQl' => 1,
        'Magento_AdvancedSalesRule' => 1,
        'Magento_Rma' => 1,
        'Magento_RmaGraphQl' => 1,
        'Magento_RmaStaging' => 1,
        'Magento_AwsS3' => 1,
        'Magento_Rss' => 1,
        'Magento_SalesRuleStaging' => 1,
        'Magento_ServicesConnector' => 1,
        'Magento_BannerPageBuilder' => 1,
        'Magento_SalesAnalytics' => 1,
        'Magento_SalesArchive' => 1,
        'Magento_MultipleWishlistGraphQl' => 1,
        'Magento_SalesInventory' => 1,
        'Magento_CatalogRuleStaging' => 1,
        'Magento_RewardStaging' => 1,
        'Magento_GoogleTagManager' => 1,
        'Magento_SampleData' => 1,
        'Magento_ScalableCheckout' => 1,
        'Magento_ScalableInventory' => 1,
        'Magento_ScalableOms' => 1,
        'Magento_ScheduledImportExport' => 1,
        'Magento_CatalogPermissionsGraphQl' => 1,
        'Magento_SearchStaging' => 1,
        'Magento_CustomerAnalytics' => 1,
        'Magento_Securitytxt' => 1,
        'Magento_SendFriend' => 1,
        'Magento_SendFriendGraphQl' => 1,
        'Magento_SaaSCatalog' => 1,
        'Magento_ProductRecommendationsSyncAdmin' => 1,
        'Magento_InventoryInStorePickupSalesAdminUi' => 1,
        'Magento_AwsS3PageBuilder' => 1,
        'Magento_StagingGraphQl' => 1,
        'Magento_CatalogStagingGraphQl' => 1,
        'Magento_StagingPageBuilder' => 1,
        'Magento_CardinalCommerce' => 1,
        'Magento_UrlRewriteGraphQl' => 1,
        'Magento_Support' => 1,
        'Magento_Swagger' => 1,
        'Magento_SwaggerWebapi' => 1,
        'Magento_SwaggerWebapiAsync' => 1,
        'Magento_Swat' => 1,
        'Magento_Swatches' => 1,
        'Magento_SwatchesGraphQl' => 1,
        'Magento_SwatchesLayeredNavigation' => 1,
        'Magento_CatalogInventoryStaging' => 1,
        'Magento_TargetRuleGraphQl' => 1,
        'Magento_GiftCardStaging' => 1,
        'Magento_TaxGraphQl' => 1,
        'Magento_TaxImportExport' => 1,
        'Magento_ElasticsearchCatalogPermissions' => 1,
        'Magento_ThemeGraphQl' => 1,
        'Magento_Tinymce3' => 1,
        'Magento_Tinymce3Banner' => 1,
        'Magento_Translation' => 1,
        'Magento_TwoFactorAuth' => 0,
        'Magento_CheckoutStaging' => 1,
        'Magento_Ups' => 1,
        'Magento_CatalogUrlRewriteDataExporter' => 1,
        'Magento_CatalogUrlRewriteGraphQl' => 1,
        'Magento_AsynchronousOperations' => 1,
        'Magento_Usps' => 1,
        'Magento_Elasticsearch7' => 1,
        'Magento_PaypalCaptcha' => 1,
        'Magento_VaultGraphQl' => 1,
        'Magento_Version' => 1,
        'Magento_CmsStaging' => 1,
        'Magento_VersionsCmsUrlRewrite' => 1,
        'Magento_VersionsCmsUrlRewriteGraphQl' => 1,
        'Magento_CatalogUrlRewriteStaging' => 1,
        'Magento_VisualProductRecommendations' => 1,
        'Magento_InventoryInStorePickupWebapiExtension' => 1,
        'Magento_WebapiAsync' => 1,
        'Magento_WebapiSecurity' => 1,
        'Magento_BundleStaging' => 1,
        'Magento_ConfigurableProductStaging' => 1,
        'Magento_WeeeGraphQl' => 1,
        'Magento_WeeeStaging' => 1,
        'Magento_PageBuilderAdminAnalytics' => 1,
        'Magento_CheckoutAddressSearchGiftRegistry' => 1,
        'Magento_WishlistAnalytics' => 1,
        'Magento_WishlistGiftCard' => 1,
        'Magento_GiftCardGraphQl' => 1,
        'Amasty_Amp' => 1,
        'Amasty_BannersLite' => 1,
        'Amasty_Base' => 1,
        'Amasty_ImportExportCore' => 1,
        'Amasty_ImportCore' => 1,
        'Amasty_ExportCore' => 1,
        'Amasty_CatalogPriceRuleImportEntity' => 1,
        'Amasty_Geoip' => 1,
        'PayPal_Braintree' => 1,
        'Amasty_CmsExportEntity' => 1,
        'Amasty_CmsImportEntity' => 1,
        'Amasty_Conditions' => 1,
        'Amasty_CommonRules' => 1,
        'Amasty_CronSchedule' => 1,
        'Amasty_CronScheduleList' => 1,
        'Amasty_CrossLinks' => 1,
        'Amasty_CustomerExportEntity' => 1,
        'Amasty_CustomerExport' => 1,
        'Amasty_CustomerImportEntity' => 1,
        'Amasty_CustomerImport' => 1,
        'Amasty_EavExportEntity' => 1,
        'Amasty_EavImportEntity' => 1,
        'Amasty_CartPriceRuleExportEntity' => 1,
        'Amasty_ExportPro' => 1,
        'Dotdigitalgroup_Email' => 1,
        'Amasty_Finder' => 1,
        'Amasty_Gdpr' => 1,
        'Amasty_GdprCookie' => 1,
        'Amasty_Ccpa' => 1,
        'Amasty_GoogleAddressAutocomplete' => 1,
        'Amasty_ShopbyBase' => 1,
        'Amasty_ImageOptimizer' => 1,
        'Amasty_ImageOptimizerUi' => 1,
        'Amasty_CartPriceRuleImportEntity' => 1,
        'Amasty_ImportExport' => 1,
        'Amasty_CatalogPriceRuleExportEntity' => 1,
        'Amasty_ImportPro' => 1,
        'Amasty_InventoryExportEntity' => 1,
        'Amasty_Label' => 1,
        'Amasty_LazyLoad' => 1,
        'Amasty_LazyLoadUi' => 1,
        'Amasty_Mage24Fix' => 1,
        'Amasty_MegaMenuLite' => 1,
        'Amasty_MegaMenu' => 1,
        'Amasty_MegaMenuGraphQl' => 1,
        'Amasty_MegaMenuPageBuilder' => 1,
        'Amasty_Meta' => 1,
        'Amasty_OrderExportEntity' => 1,
        'Amasty_OrderExport' => 1,
        'Amasty_OrderImportEntity' => 1,
        'Amasty_OrderImport' => 1,
        'Amasty_Orderattr' => 1,
        'Amasty_PageSpeedOptimizer' => 1,
        'Amasty_PageSpeedTools' => 1,
        'Amasty_Payrestriction' => 1,
        'Dotdigitalgroup_Enterprise' => 1,
        'Amasty_Preorder' => 1,
        'Amasty_ProductExportEntity' => 1,
        'Amasty_ProductExport' => 1,
        'Amasty_ProductImportEntity' => 1,
        'Amasty_ProductImport' => 1,
        'Amasty_Promo' => 1,
        'Amasty_Rgrid' => 1,
        'Amasty_Rules' => 1,
        'Amasty_RulesPro' => 1,
        'Amasty_SalesRuleWizard' => 1,
        'Amasty_SeoHtmlSitemap' => 1,
        'Amasty_SeoToolKit' => 1,
        'Amasty_SeoSingleUrl' => 1,
        'Amasty_SeoRichData' => 1,
        'Amasty_ShippingArea' => 1,
        'Amasty_ShippingTableRates' => 1,
        'Amasty_Shiprestriction' => 1,
        'Amasty_Shiprules' => 1,
        'Amasty_Shopby' => 1,
        'Amasty_ShopbyBrand' => 1,
        'Amasty_ShopbySeo' => 1,
        'Amasty_ShopbyGraphQl' => 1,
        'Amasty_ShopbyPage' => 1,
        'Amasty_ShopbyRoot' => 1,
        'Amasty_GroupedOptions' => 1,
        'Amasty_SocialLogin' => 1,
        'Amasty_SocialLoginAppleId' => 1,
        'Amasty_Stockstatus' => 1,
        'Amasty_StockstatusGraphQl' => 1,
        'Amasty_Storelocator' => 1,
        'Amasty_StorePickupWithLocator' => 0,
        'Amasty_StorelocatorIndexer' => 1,
        'Amasty_UrlRewriteExportEntity' => 1,
        'Amasty_UrlRewriteImportEntity' => 1,
        'Amasty_XmlSitemap' => 1,
        'Amazon_Core' => 1,
        'Amazon_Login' => 1,
        'Amazon_Payment' => 1,
        'Astound_Affirm' => 1,
        'Bss_AjaxCart' => 1,
        'Bss_CustomProductAttributeExport' => 1,
        'Bss_ImportExportCore' => 1,
        'Bss_Popup' => 1,
        'Bss_ReviewsImport' => 1,
        'ClassyLlama_AvaTax' => 1,
        'Dotdigitalgroup_Chat' => 1,
        'Amasty_Feed' => 1,
        'Amasty_Pgrid' => 1,
        'Dotdigitalgroup_Sms' => 1,
        'Ecommerce121_AmastyFinder' => 1,
        'Ecommerce121_AmastyRules' => 1,
        'Ecommerce121_AmastyShippingTableRates' => 1,
        'Ecommerce121_AttributeAssetsIcon' => 1,
        'Ecommerce121_Core' => 1,
        'Ecommerce121_BssAjaxCart' => 1,
        'Ecommerce121_Catalog' => 1,
        'Ecommerce121_CatalogData' => 1,
        'Ecommerce121_Checkout' => 1,
        'Ecommerce121_ContentSecurityPolicies' => 1,
        'Ecommerce121_Basket' => 1,
        'Ecommerce121_ExtendedBasket' => 1,
        'Ecommerce121_Garage' => 1,
        'Eyemagine_HubSpot' => 1,
        'Ecommerce121_MasterCoupon' => 1,
        'Ecommerce121_NewsletterSignup' => 1,
        'Ecommerce121_PartFinder' => 1,
        'Ecommerce121_ProductListing' => 1,
        'Ecommerce121_ProductPage' => 1,
        'Ecommerce121_ProductVariation' => 1,
        'WeltPixel_Backend' => 1,
        'Ecommerce121_SalesPartialShipments' => 1,
        'Ecommerce121_SearchSpring' => 1,
        'Ecommerce121_StateFilter' => 1,
        'Ecommerce121_StickyBar' => 1,
        'Ecommerce121_StorePickupWithLocator' => 1,
        'Ecommerce121_TaxImport' => 1,
        'Ecommerce121_Variant' => 1,
        'Ecommerce121_VariantOrder' => 1,
        'Zonos_Checkout' => 1,
        'Ecommerce121_HubSpot' => 1,
        'FME_Photogallery' => 1,
        'Fastly_Cdn' => 1,
        'IWD_AddressValidation' => 1,
        'Klarna_Core' => 1,
        'Klarna_Ordermanagement' => 1,
        'Klarna_Kp' => 1,
        'Klarna_Onsitemessaging' => 1,
        'Klarna_KpGraphQl' => 1,
        'MageWorx_OptionBase' => 1,
        'MageWorx_Info' => 1,
        'MageWorx_OptionAdvancedPricing' => 1,
        'MageWorx_DynamicOptionsBase' => 1,
        'MageWorx_OptionDependency' => 1,
        'MageWorx_OptionFeatures' => 1,
        'MageWorx_OptionTemplates' => 1,
        'MageWorx_OptionInventory' => 1,
        'MageWorx_OptionLink' => 1,
        'MageWorx_OptionSkuPolicy' => 1,
        'MageWorx_OptionSwatches' => 1,
        'MageWorx_OptionImportExport' => 1,
        'MageWorx_OptionVisibility' => 1,
        'Magefan_Community' => 1,
        'Magefan_GuestToCustomer' => 1,
        'MagicToolbox_MagicZoomPlus' => 1,
        'MagicToolbox_MagicScroll' => 1,
        'Nanobots_DataPatchCreator' => 1,
        'Nextopia_Search' => 1,
        'NoFraud_Connect' => 1,
        'ParadoxLabs_TokenBase' => 1,
        'ParadoxLabs_Authnetcim' => 1,
        'Amasty_Checkout' => 1,
        'PayPal_BraintreeGraphQl' => 1,
        'Photoslurp_Pswidget' => 1,
        'RedChamps_Core' => 1,
        'RedChamps_ShareCart' => 1,
        'SearchSpring_Feed' => 1,
        'Taxjar_SalesTax' => 1,
        'Temando_ShippingRemover' => 1,
        'Vertex_Tax' => 1,
        'Vertex_AddressValidationApi' => 1,
        'Vertex_AddressValidation' => 1,
        'Vertex_TaxStaging' => 1,
        'WeltPixel_MobileDetect' => 1,
        'WeltPixel_GA4' => 1,
        'WeltPixel_OwlCarouselSlider' => 1,
        'Ecommerce121_RelatedProducts' => 1,
        'Wyomind_Framework' => 1,
        'Wyomind_GoogleCustomerReviews' => 1,
        'Yotpo_Yotpo' => 1,
        'Ecommerce121_ZonosCheckout' => 1,
        'Zonos_Hello' => 1
    ],
    'admin_user' => [
        'locale' => [
            'code' => [
                'en_US'
            ]
        ]
    ]
];
