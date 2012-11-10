<?php
$specialQueries = array(
"article_created_by" => array ("com_users","", "SELECT #__users.username FROM #__users LEFT JOIN #__content ON #__content.created_by=#__users.id WHERE #__content.id={id}", "SSDefaultFilter"),
"article_category" => array ("com_content","", "SELECT #__categories.title FROM #__categories LEFT JOIN #__content ON #__content.catid=#__categories.id WHERE #__content.id={id}", "SSDefaultFilter"),
"virtuemart_product_price" => array("com_virtuemart","1", "SELECT {id}", "SSVirtuemartProductPrice"),
"link_to_article" => array("com_content","", "SELECT {id}", "SSLinkToArticle"),
"link_to_category" => array("com_content","", "SELECT catid FROM #__content WHERE id={id}", "SSLinkToCategory"),
"link_to_k2_item" => array("com_k2","", "SELECT {id}", "SSLinkToK2Item"),
"link_to_k2_category" => array("com_k2","", "SELECT #__k2_items.catid FROM #__k2_items WHERE id={id}", "SSLinkToK2Category"),
"link_to_easyblog_category" => array("com_easyblog","", "SELECT #__easyblog_post.category_id FROM #__easyblog_post WHERE id={id}", "SSLinkToEasyBlogCategory"),
"link_to_easyblog" => array("com_easyblog","", "SELECT {id}", "SSLinkToEasyBlog"),
"menu_item_link" => array("com_menus","", "SELECT #__menu.id, #__menu.link FROM #__menu WHERE id={id}", "SSMenuItemLink"),
"phocagallery_linktoimg" => array("com_phocagallery","", "SELECT #__phocagallery.filename FROM #__phocagallery WHERE id={id} ", "SSLinkToPhocagalleryImg"),
"phocagallery_linktocategory" => array("com_phocagallery","", "SELECT #__phocagallery.catid FROM #__phocagallery WHERE id={id}", "SSLinkToPhocagalleryCategory"),
"ignitegallery_linktoimg" => array("com_igallery","", "SELECT #__igallery_img.id, #__igallery_img.filename FROM #__igallery_img WHERE id={id}", "SSLinkToIgnitegalleryImg"),
"ignitegallery_linktocategory" => array("com_igallery","", "SELECT #__igallery_img.gallery_id FROM #__igallery_img WHERE id={id}", "SSLinkToIgnitegalleryCategory"),
"vm_link_to_img" => array("com_virtuemart","1", "SELECT #__vm_product.product_full_image FROM #__vm_product WHERE product_id={id}", "SSLinkToVmProductImage"),
"vm_name_of_category" => array("com_virtuemart","1", "SELECT {id}", "SSNameOfVmCategory"),
"vm_link_to_category" => array("com_virtuemart","1", "SELECT #__vm_product_category_xref.category_id FROM #__vm_product_category_xref WHERE product_id={id}", "SSLinkToVmCategory"),
"vm_link_to_product" => array("com_virtuemart","1", "SELECT {id}", "SSLinkToVmProduct"),
"virtuemart2_product_price" => array("com_virtuemart","2", "SELECT {id}", "SSVirtuemart2ProductPrice"),
"vm2_link_to_img" => array("com_virtuemart","2", "SELECT #__virtuemart_medias.file_url FROM #__virtuemart_medias LEFT JOIN #__virtuemart_product_medias ON
                                                     #__virtuemart_medias.virtuemart_media_id=#__virtuemart_product_medias.virtuemart_media_id WHERE
                                                     #__virtuemart_product_medias.virtuemart_product_id={id}", "SSLinkToVm2ProductImage"),
"vm2_name_of_category" => array("com_virtuemart","2", "SELECT #__virtuemart_product_categories.virtuemart_category_id FROM
                                                              #__virtuemart_product_categories WHERE #__virtuemart_product_categories.virtuemart_product_id={id}", "SSNameOfVm2Category"),
"vm2_link_to_category" => array("com_virtuemart","2", "SELECT #__virtuemart_product_categories.virtuemart_category_id FROM
                                                              #__virtuemart_product_categories WHERE #__virtuemart_product_categories.virtuemart_product_id={id}", "SSLinkToVm2Category"),
"vm2_link_to_product" => array("com_virtuemart","2", "SELECT {id}", "SSLinkToVm2Product")
);

?>

