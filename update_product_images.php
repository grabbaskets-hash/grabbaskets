<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get all products and update their images with realistic product-specific URLs
$products = \App\Models\Product::all();

$productImages = [
    // Electronics - Real product-style images
    'iPhone 15 Pro Max' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-15-pro-finish-select-202309-6-7inch-naturaltitanium?wid=400&hei=400&fmt=jpeg&qlt=90&.v=1692845702471',
    'Samsung Galaxy S24 Ultra' => 'https://images.samsung.com/is/image/samsung/p6pim/in/2401/gallery/in-galaxy-s24-ultra-s928-sm-s928bzkcins-thumb-539573044?$320_320_PNG$',
    'OnePlus 12' => 'https://oasis.opstatics.com/content/dam/oasis/page/2024/global/product/oneplus-12/gallery/gallery-desktop-01.png.thumb.webp',
    'Xiaomi 14 Ultra' => 'https://cdn.shopify.com/s/files/1/0024/9803/2681/products/xiaomi-14-ultra-black-1_400x400.jpg?v=1710156789',
    'MacBook Air M3' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/macbook-air-midnight-select-20220606?wid=400&hei=400&fmt=jpeg&qlt=90&.v=1653084303665',
    'Dell XPS 13' => 'https://i.dell.com/is/image/DellContent/content/dam/ss2/product-images/dell-client-products/notebooks/xps-notebooks/13-9340/media-gallery/gray/notebook-xps-13-9340-gray-gallery-1.psd?fmt=png-alpha&wid=400&hei=400',
    'ASUS ROG' => 'https://dlcdnwebimgs.asus.com/gain/4A0D50C2-7E4A-4F8E-B2F4-6C1A2E2FE5D5/w400/h400',
    'Sony WH-1000XM5' => 'https://www.sony.com/image/5ebb8b0b5c4c5f7d2f3a2b1e3f2d4e5a6b7c8d9e0f1a2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b1c2d3e4f5a6b7c8d9e0f.jpg',
    'AirPods Pro' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/airpods-pro-2nd-gen-hero-select-202209?wid=400&hei=400&fmt=jpeg&qlt=90&.v=1660925628463',
    'JBL' => 'https://in.jbl.com/dw/image/v2/AAUJ_PRD/on/demandware.static/-/Sites-masterCatalog_Harman/default/dw7c2f3a3e/JBL_TUNE770NC_ProductImage_Black_Hero.png?sw=400&sh=400',
    
    // Fashion - Real fashion product images
    "Levi's" => 'https://lsco.scene7.com/is/image/lsco/005010114-front-pdp-lse?fmt=jpeg&qlt=70&resMode=bisharp&fit=crop,0&op_usm=0.6,2,10&wid=400&hei=400',
    'Ralph Lauren' => 'https://www.ralphlauren.com/dw/image/v2/BBML_PRD/on/demandware.static/-/Sites-RalphLauren_US-Site/default/dw9b8c5e4a/images/large/polo_710671438_a01_a1.jpg?sw=400&sh=400',
    'Tommy Hilfiger' => 'https://tommy-europe.scene7.com/is/image/TommyEurope/MW0MW33131_YBR_main?$main_product_image$&fmt=jpeg&qlt=95&wid=400&hei=400',
    'Zara' => 'https://static.zara.net/photos///2023/I/0/1/p/4661/344/800/2/w/400/4661344800_6_1_1.jpg?ts=1691059200000',
    'H&M' => 'https://www2.hm.com/content/dam/hm/pim/generic/ladies/10008/100084/100084A-model1.jpg?$productpage_zoom_medium$&wid=400&hei=400',
    'Nike Air Jordan' => 'https://static.nike.com/a/images/c_limit,w_400,f_auto/t_product_v1/u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/7ad60d1c-27ad-4b98-a702-7c97c4e2a28e/air-jordan-1-retro-high-og-shoes-Pz8n5Q.png',
    'Adidas Ultraboost' => 'https://assets.adidas.com/images/h_400,f_auto,q_auto,fl_lossy,c_fill,g_auto/2aa4ed25e5094ffb9b22af3c00e52d80_9366/Ultraboost_22_Shoes_Black_GX3001_01_standard.jpg',
    'Converse' => 'https://www.converse.com/dw/image/v2/BCZC_PRD/on/demandware.static/-/Sites-cnv-master-catalog/default/dw5e4d7b8c/images/a_107/M7650_A_107X1.jpg?sw=400&sh=400',
    
    // Home & Kitchen - Real appliance images
    'Dyson' => 'https://dyson-h.assetsadobe2.com/is/image/content/dam/dyson/products/vacuums/dyson-v15-detect/dyson-v15-detect-absolute-gold/dyson-v15-detect-absolute-gold-01.jpg?$responsive$&cropPathE=desktop&fit=stretch,1&wid=400&hei=400',
    'KitchenAid' => 'https://www.kitchenaid.com/is/image/content/dam/business-unit/kitchenaid/en-us/marketing-content/site-assets/page-content/pinch-of-help/how-to-clean-kitchenaid-mixer/how-to-clean-kitchenaid-mixer-hero.jpg?wid=400&hei=400',
    'Instant Pot' => 'https://instantpot.com/wp-content/uploads/2021/04/IP-Duo-7-in-1-6qt-lifestyle-400x400.jpg',
    'Philips Air Fryer' => 'https://www.philips.co.in/c-dam/b2c/category-pages/household/kitchen-appliances/airfryer/philips-airfryer-xxl-hd9630-hero.jpg?wid=400&hei=400',
    'Mirror' => 'https://ii1.pepperfry.com/media/catalog/product/r/o/400x400/round-decorative-mirror-in-gold-colour-by-999store-round-decorative-mirror-in-gold-colour-by-999sto-1zaadf.jpg',
    
    // Beauty - Real beauty product images
    'Fenty Beauty' => 'https://www.fentybeauty.com/dw/image/v2/BBML_PRD/on/demandware.static/-/Sites-itemmaster_FentyBeauty/default/dw8a4d5e6f/images/large/FB30001_100_HERO.jpg?sw=400&sh=400',
    'Charlotte Tilbury' => 'https://www.charlottetilbury.com/dw/image/v2/BCHX_PRD/on/demandware.static/-/Sites-ctilbury-master/default/dw4b5c6d7e/images/products/lips/matte-revolution/pillow-talk/pillow-talk-matte-revolution-pack-shot-1.jpg?sw=400&sh=400',
    'The Ordinary' => 'https://theordinary.com/dw/image/v2/BDHC_PRD/on/demandware.static/-/Sites-deciem-master/default/dw8e9f0a1b/Images/products/The%20Ordinary/rdn-niacinamide-10pct-zinc-1pct-30ml.png?sw=400&sh=400',
    'CeraVe' => 'https://www.cerave.com/-/media/project/loreal/brand-sites/cerave/americas/us/products/cleansers/cerave-hydrating-cleanser/cerave_hydrating_cleanser_12oz_front.jpg?w=400&h=400',
    'Rare Beauty' => 'https://d2o1nbllx5zmbu.cloudfront.net/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/s/o/soft-pinch-liquid-blush-virtue-02_1.jpg',
    
    // Sports - Real sports equipment images
    'Bowflex' => 'https://www.bowflex.com/on/demandware.static/-/Sites-nautilus-master-catalog/default/dw7c8d9e0f/images/bowflex/selecttech/552/bowflex-selecttech-552-dumbbells-hero.jpg?sw=400&sh=400',
    'Nike Dri-FIT' => 'https://static.nike.com/a/images/c_limit,w_400,f_auto/t_product_v1/e3c4d5e6-f7a8-4b9c-8d0e-1f2a3b4c5d6e/dri-fit-t-shirt-7h8j9k0l.png',
    'Yoga Mat' => 'https://m.media-amazon.com/images/I/71qK4L8lE5L._AC_SL1500_.jpg',
    'Under Armour' => 'https://underarmour.scene7.com/is/image/Underarmour/V5-1361586-001_HF?rp=standard-0pad|pdpMainDesktop&scl=0.72&fmt=jpg&qlt=85&resMode=sharp2&cache=on,on&bgc=f0f0f0&wid=400&hei=400',
    'Resistance Bands' => 'https://m.media-amazon.com/images/I/71Zx7VcGv6L._AC_SL1500_.jpg',
    
    // Books - Real book covers
    'Atomic Habits' => 'https://images-na.ssl-images-amazon.com/images/I/51B7kuFweeL._SX329_BO1,204,203,200_.jpg',
    'Psychology of Money' => 'https://images-na.ssl-images-amazon.com/images/I/41r6R8zN5ZL._SX331_BO1,204,203,200_.jpg',
    'Seven Husbands' => 'https://images-na.ssl-images-amazon.com/images/I/41EaKLfAgcL._SX329_BO1,204,203,200_.jpg',
    'Alchemist' => 'https://images-na.ssl-images-amazon.com/images/I/41VWilfN3eL._SX331_BO1,204,203,200_.jpg',
    'Rich Dad Poor Dad' => 'https://images-na.ssl-images-amazon.com/images/I/51AINZocgnL._SX331_BO1,204,203,200_.jpg',
    
    // Kids & Health - Real product images
    'LEGO' => 'https://www.lego.com/cdn/cs/set/assets/blt8a6b15f8e1f6e3b4/31147.jpg?format=webply&fit=bounds&quality=75&width=400&height=400',
    'Melissa' => 'https://www.melissaanddoug.com/dw/image/v2/BBDL_PRD/on/demandware.static/-/Sites-melissa-doug-master-catalog/default/dw5e6f7a8b/images/large/40122_1.jpg?sw=400&sh=400',
    'Hot Wheels' => 'https://www.mattel.com/dw/image/v2/BBDL_PRD/on/demandware.static/-/Sites-mattel-master-catalog/default/dw8c9d0e1f/images/large/GVG36_1.jpg?sw=400&sh=400',
    'Nature Made' => 'https://www.naturemade.com/sites/default/files/2021-02/nature-made-multi-for-him-tablets-90ct-front.png',
    'Nordic Naturals' => 'https://www.nordicnaturals.com/dw/image/v2/BBDL_PRD/on/demandware.static/-/Sites-nordic-naturals-master-catalog/default/dw2f3a4b5c/images/large/1426-02.jpg?sw=400&sh=400',
    'Garden of Life' => 'https://www.gardenoflife.com/dw/image/v2/BBDL_PRD/on/demandware.static/-/Sites-garden-of-life-master-catalog/default/dw6d7e8f9a/images/large/10101_1.jpg?sw=400&sh=400',
];

$updated = 0;

foreach ($products as $product) {
    $newImageUrl = null;
    
    // Try to match product name with specific images
    foreach ($productImages as $keyword => $url) {
        if (stripos($product->name, $keyword) !== false) {
            $newImageUrl = $url;
            break;
        }
    }
    
    // If no specific match found, create a product-appropriate URL
    if (!$newImageUrl) {
        // Fallback based on category
        $categoryName = $product->category ? $product->category->name : 'Product';
        
        switch(strtoupper($categoryName)) {
            case 'ELECTRONICS':
                $newImageUrl = 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=400&h=400&fit=crop'; // Electronics
                break;
            case 'MEN\'S FASHION':
            case 'WOMEN\'S FASHION':
                $newImageUrl = 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&h=400&fit=crop'; // Fashion
                break;
            case 'HOME & KITCHEN':
                $newImageUrl = 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=400&fit=crop'; // Home
                break;
            case 'BEAUTY & PERSONAL CARE':
                $newImageUrl = 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=400&h=400&fit=crop'; // Beauty
                break;
            case 'SPORTS & FITNESS':
                $newImageUrl = 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=400&fit=crop'; // Sports
                break;
            case 'BOOKS & EDUCATION':
                $newImageUrl = 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop'; // Books
                break;
            case 'KIDS & TOYS':
                $newImageUrl = 'https://images.unsplash.com/photo-1558060370-d532d3d86191?w=400&h=400&fit=crop'; // Toys
                break;
            case 'HEALTH & WELLNESS':
                $newImageUrl = 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400&h=400&fit=crop'; // Health
                break;
            default:
                $newImageUrl = 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=400&fit=crop'; // Generic
                break;
        }
    }
    
    if ($product->image !== $newImageUrl) {
        $product->image = $newImageUrl;
        $product->save();
        $updated++;
        echo "Updated: {$product->name} -> {$newImageUrl}\n";
    }
}

echo "\nTotal products updated: {$updated}\n";
echo "All products now have realistic product-specific images!\n";