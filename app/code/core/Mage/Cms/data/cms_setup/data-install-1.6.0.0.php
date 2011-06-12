<?php
/**
 * Camilooframework
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Camilooframework to newer
 * versions in the future. If you wish to customize Camilooframework for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Cms
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$cmsBlocks = array(
    array(
        'title'         => 'Footer Links',
        'identifier'    => 'footer_links',
        'content'       => "&nbsp;",
        'is_active'     => 1,
        'stores'        => 0
    )
);

$cmsPages = array(
    array(
        'title'         => '404 Not Found 1',
        'root_template' => 'two_columns_left',
        'meta_keywords' => 'Page keywords',
        'meta_description'
                        => 'Page description',
        'identifier'    => 'no-route',
        'content'       => "<div class=\"page-title\"><h1>Whoops, our bad...</h1></div>\r\n<dl>\r\n<dt>The page you requested was not found, and we have a fine guess why.</dt>\r\n<dd>\r\n<ul class=\"disc\">\r\n<li>If you typed the URL directly, please make sure the spelling is correct.</li>\r\n<li>If you clicked on a link to get here, the link is outdated.</li>\r\n</ul></dd>\r\n</dl>\r\n<dl>\r\n<dt>What can you do?</dt>\r\n<dd>Have no fear, help is near! There are many ways you can get back on track with Camilooframework Store.</dd>\r\n<dd>\r\n<ul class=\"disc\">\r\n<li><a href=\"#\" onclick=\"history.go(-1); return false;\">Go back</a> to the previous page.</li>\r\n<li>Use the search bar at the top of the page to search for your products.</li>\r\n<li>Follow these links to get you back on track!<br /><a href=\"{{store url=\"\"}}\">Store Home</a> <span class=\"separator\">|</span> <a href=\"{{store url=\"user/account\"}}\">My Account</a></li></ul></dd></dl>\r\n",
        'is_active'     => 1,
        'stores'        => array(0),
        'sort_order'    => 0
    ),
    array(
        'title'         => 'Home page',
        'root_template' => 'two_columns_left',
        'identifier'    => 'home',
		'page_layout_update_xml' => '<reference name="left"><block type="cms/block" name="cms_test_block"><action method="setBlockId"><block_id>sidebar</block_id></action></block><action method="unsetChild"><name>left.newsletter</name></action></reference>',
        'content'       => "<div class=\"page-title\">
<h1>Welcome to Camiloo Framework</h1>
</div>
<p>Congratulations. Camiloo Framework has been installed on your server. In building Camiloo Framework, we took all of our experience in modifying and developing for Magento eCommerce and realised that if the eCommerce element was removed, Magento would actually make for a very strong and easily extendable SaaS app development framework.</p>
<p>This product is aimed at developers. Rather than developing an ACL system from the ground up, and rather needing to do everything by hand, we present the Magento way of doing things: Extend, Share, Sell and Profit.</p>
<p>We will be launching an Extension Store for the Camiloo Framework in Q3 2011. This will allow you to create a set of extensions and modifications to the framework and enable other developers to 'one click install' them from within the Camiloo Framework. More details to be revealed soon, but this will be designed and operated to work like the iOS 'App Store' or 'Android Marketplace'.</p>
<p>This is a 100% open source platform. We have a public GitHub open for issue reporting and change suggestion. But as with the Magento classes and methods which we have developed this solution on, extensions you make do not need to be made public. This enables developers to gain a major advantage through using the Camiloo Framework.</p>
<p>We have removed all 'catalog', 'payment' and sales segments from the core framework in producing this platform, but in testing we have found that many non sales related Magento extesions fully support the platform. To underline this example, we have included the highly popular OSL licensed 'NavAdmin' extension in the platform.</p>
<p>Feel free to get in touch with us via our website at &lt;a href=\"http://www.camiloo.co.uk/\"&gt;http://www.camiloo.co.uk/&lt;/a&gt;<br /> - we will be switching this from Wordpress to a Camiloo Framework based solution later in the year and publishing guides on this switch as we go.</p>",
        'is_active'     => 1,
        'stores'        => array(0),
        'sort_order'    => 0
    ),
    array(
        'title'         => 'About Us',
        'root_template' => 'two_columns_right',
        'identifier'    => 'about-magento-demo-store',
        'content'       => "<div class=\"page-title\">&nbsp;/div>",
        'is_active'     => 1,
        'stores'        => array(0),
        'sort_order'    => 0
    ),
);

/**
 * Insert default blocks
 */
foreach ($cmsBlocks as $data) {
    Mage::getModel('cms/block')->setData($data)->save();
}

/**
 * Insert default and system pages
 */
foreach ($cmsPages as $data) {
    Mage::getModel('cms/page')->setData($data)->save();
}
