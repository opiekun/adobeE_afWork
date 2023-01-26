# nxt-magento2-enterprise-search
Nextopia Magento 2 Enterprise Search Extension

# Manual Installation
*The commands below should be run from the Magento 2 base directory*
1. Create folder `app/code/Nextopia/Search`
2. Copy contents of this repository into that folder.
3. Run `php bin/magento module:enable Nextopia_Search`
4. Run `php bin/magento setup:upgrade`
5. Run `php bin/magento setup:di:compile`
6. Run `php bin/magento setup:static-content:deploy`
7. Run `php bin/magento cache:clean`
8. Run `php -f bin/magento cache:flush`

# Enable Nextopia Search Extension
After installing the module, complete the following configurations in your Magento Store:
1. Login to your Magento Store Admin Panel
2. Navigate to: Stores -> Configuration -> Nextopia -> Search
3. `Enabled`: Yes
4. `Public Client ID`: Found in your Nextopia Dashboard on your [Account page](https://merchant.nextopiasoftware.com/preferences.php).

## Contribute
Learn how to contribute 
[here](https://github.com/team-nxt/nxt-magento2-enterprise-search/blob/master/.github/CONTRIBUTING.md).
