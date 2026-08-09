<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * هذا الكتالوج هو نفسه المنتجات الـ 32 الموجودة أصلاً (وبنفس الأسماء
     * والأوصاف وقصص المنشأ) في public/app.js ضمن مصفوفة PRODUCTS الثابتة،
     * والتي كانت تُعرض فقط من طرف المتصفح (JS) دون أي اتصال بقاعدة البيانات.
     * ننقلها هنا حرفياً حتى تصبح صفحات Women/Kids/Beauty/Accessories تعرض
     * نفس المحتوى الحالي تماماً، لكن من قاعدة البيانات الحقيقية.
     */
    public function run(): void
    {
        $catalog = [
            // ---- Women ----
            ['sku' => 'POST-W1', 'category' => 'women', 'name' => 'Marlowe Silk Slip Dress', 'price' => 248, 'description' => 'A bias-cut slip in washed silk that moves like water and holds its line. Cut from a single bolt of mulberry silk sourced through a third-generation mill in Como, finished by a small atelier we have worked with since our first season.'],
            ['sku' => 'POST-W2', 'category' => 'women', 'name' => 'Lenore Wool Overcoat', 'price' => 420, 'description' => 'A double-faced wool coat with a quiet drape and an unlined, hand-finished interior. The cloth is a recycled-wool blend woven in Yorkshire; the buttons are turned from corozo nut rather than plastic.'],
            ['sku' => 'POST-W3', 'category' => 'women', 'name' => 'Halden Pleated Midi Skirt', 'price' => 165, 'description' => 'Knife pleats set into a soft satin-back crepe that swings with every step. Pleated by heat-set on a vintage press in a family workshop outside Kyoto.'],
            ['sku' => 'POST-W4', 'category' => 'women', 'name' => 'Adair Cashmere Knit', 'price' => 198, 'description' => 'A relaxed boatneck in grade-A cashmere, light enough for spring evenings. Spun from fibre traced to a single herding cooperative in Inner Mongolia.'],
            ['sku' => 'POST-W5', 'category' => 'women', 'name' => 'June Poplin Blouse', 'price' => 128, 'description' => 'Crisp organic-cotton poplin with a softly gathered shoulder. Woven from GOTS-certified organic cotton, dyed in low-water vats.'],
            ['sku' => 'POST-W6', 'category' => 'women', 'name' => 'Solène Tailored Trousers', 'price' => 176, 'description' => 'A high-waisted, wide-leg trouser with a pressed crease and a clean fall. Tailored in a workshop in Porto run entirely on renewable energy.'],
            ['sku' => 'POST-W7', 'category' => 'women', 'name' => 'Wren Linen Wrap Dress', 'price' => 189, 'description' => 'A breathable linen wrap that ties softly and packs without complaint. European flax, spun and woven within 200km of where it was grown.'],
            ['sku' => 'POST-W8', 'category' => 'women', 'name' => 'Cosima Quilted Jacket', 'price' => 235, 'description' => 'A lightly quilted jacket with a rounded collar and patch pockets. Filled with a recycled-down alternative; shell made from regenerated nylon.'],

            // ---- Children ----
            ['sku' => 'POST-K1', 'category' => 'kids', 'name' => 'Poppy Pinafore Dress', 'price' => 64, 'description' => 'A swingy pinafore in soft brushed cotton, built for cartwheels. Made from organic cotton offcuts rescued from our womenswear cutting floor.'],
            ['sku' => 'POST-K2', 'category' => 'kids', 'name' => 'Bramble Knit Cardigan', 'price' => 58, 'description' => 'A cosy little cardigan with wooden buttons and roomy sleeves. Knitted from a soft merino blend, gentle on small skin.'],
            ['sku' => 'POST-K3', 'category' => 'kids', 'name' => 'Hazel Cotton Romper', 'price' => 48, 'description' => 'An easy snap-button romper that survives the wash and the playground. GOTS-certified cotton, dyed with low-impact colour.'],
            ['sku' => 'POST-K4', 'category' => 'kids', 'name' => 'Tilly Heart Tee', 'price' => 32, 'description' => 'A soft everyday tee with a tiny embroidered heart at the chest. Hand-embroidered detail added by a women-led cooperative we partner with.'],
            ['sku' => 'POST-K5', 'category' => 'kids', 'name' => 'Otto Cord Trousers', 'price' => 52, 'description' => 'Fine-wale corduroy with an elastic back and reinforced knees. Woven from organic cotton corduroy in a small Portuguese mill.'],
            ['sku' => 'POST-K6', 'category' => 'kids', 'name' => 'Maple Quilted Coat', 'price' => 78, 'description' => 'A warm little coat with a soft-lined hood for cold mornings. Filled with recycled insulation; shell made from regenerated fibres.'],
            ['sku' => 'POST-K7', 'category' => 'kids', 'name' => 'Wisp Pleated Skirt', 'price' => 44, 'description' => 'A twirl-tested pleated skirt with a comfy elastic waist. Pleated from the same satin-back crepe as our womenswear, scaled down.'],
            ['sku' => 'POST-K8', 'category' => 'kids', 'name' => 'Birch Linen Blouse', 'price' => 46, 'description' => 'A featherlight linen blouse with a scalloped collar. European flax, softened through a gentle stone-wash.'],

            // ---- Beauty ----
            ['sku' => 'POST-B1', 'category' => 'beauty', 'name' => 'Origin Satin Lipstick', 'price' => 34, 'description' => 'A creamy satin-finish lipstick in six story-led shades. Formulated without parabens or fragrance, in refillable brass cases.'],
            ['sku' => 'POST-B2', 'category' => 'beauty', 'name' => 'Dusk Eyeshadow Quartet', 'price' => 48, 'description' => 'Four blendable shades — matte to soft shimmer — in one slim compact. Pressed with mineral pigments and finished in a recyclable case.'],
            ['sku' => 'POST-B3', 'category' => 'beauty', 'name' => 'Halo Glow Serum', 'price' => 62, 'description' => 'A lightweight hydrating serum with niacinamide and squalane. Made in small batches; bottled in recycled, refill-ready glass.'],
            ['sku' => 'POST-B4', 'category' => 'beauty', 'name' => 'Première Eau de Parfum', 'price' => 96, 'description' => 'A warm, woody-floral signature scent with notes of rose and amber. Blended by an independent perfumer in Grasse, the heart of fine fragrance.'],
            ['sku' => 'POST-B5', 'category' => 'beauty', 'name' => 'Veil Tinted Balm', 'price' => 26, 'description' => 'A sheer, buildable balm that conditions while it tints. Built on a base of shea and jojoba; cruelty-free and refillable.'],
            ['sku' => 'POST-B6', 'category' => 'beauty', 'name' => 'Glow Cheek Compact', 'price' => 38, 'description' => 'A duo blush-and-highlight compact for an easy lit-from-within flush. Mineral pigments pressed in a refillable magnetic case.'],
            ['sku' => 'POST-B7', 'category' => 'beauty', 'name' => 'Quiet Hour Night Oil', 'price' => 58, 'description' => 'A nourishing facial oil with rosehip and bakuchiol for overnight repair. Cold-pressed botanicals, bottled with a glass dropper.'],
            ['sku' => 'POST-B8', 'category' => 'beauty', 'name' => 'Soir Travel Perfume', 'price' => 46, 'description' => 'A purse-sized refillable spray of our signature evening scent. Refillable atomiser designed to be kept, not discarded.'],

            // ---- Accessories ----
            ['sku' => 'POST-A1', 'category' => 'accessories', 'name' => 'Edie Leather Tote', 'price' => 265, 'description' => 'A structured everyday tote in vegetable-tanned leather with a soft handle. Cut and stitched by a family leatherworks in Florence, tanned without chrome.'],
            ['sku' => 'POST-A2', 'category' => 'accessories', 'name' => 'Lune Drop Earrings', 'price' => 88, 'description' => 'Sculptural drop earrings in 14k gold-plated recycled brass. Cast from recycled brass and plated in a workshop in Jaipur.'],
            ['sku' => 'POST-A3', 'category' => 'accessories', 'name' => 'Faye Silk Scarf', 'price' => 74, 'description' => 'A hand-rolled silk twill scarf printed with an original house motif. Printed and hand-rolled by an artisan studio in Lyon.'],
            ['sku' => 'POST-A4', 'category' => 'accessories', 'name' => 'Margaux Sunglasses', 'price' => 135, 'description' => 'Rounded acetate frames with polarised lenses and a timeless line. Hand-polished from bio-acetate in a small Italian eyewear atelier.'],
            ['sku' => 'POST-A5', 'category' => 'accessories', 'name' => 'Sol Wide-Brim Hat', 'price' => 92, 'description' => 'A packable wide-brim hat woven from natural straw with a grosgrain band. Hand-woven from sustainably harvested raffia by a cooperative in Madagascar.'],
            ['sku' => 'POST-A6', 'category' => 'accessories', 'name' => 'Ines Minimal Watch', 'price' => 178, 'description' => 'A pared-back watch with a clean dial and an interchangeable strap. Assembled with a Swiss movement and a vegetable-tanned leather strap.'],
            ['sku' => 'POST-A7', 'category' => 'accessories', 'name' => 'Vela Quilted Crossbody', 'price' => 185, 'description' => 'A compact quilted crossbody with a gold-tone chain and roomy interior. Made from regenerated leather offcuts, stitched in Florence.'],
            ['sku' => 'POST-A8', 'category' => 'accessories', 'name' => 'Romy Pearl Studs', 'price' => 64, 'description' => 'Freshwater pearl studs set on recycled gold-plated posts. Ethically sourced freshwater pearls, hand-set in a small studio.'],
        ];

        foreach ($catalog as $item) {
            $category = Category::where('slug', $item['category'])->first();

            Product::updateOrCreate(
                ['sku' => $item['sku']],
                [
                    'category_id' => $category?->id,
                    'name'        => $item['name'],
                    'description' => $item['description'],
                    'price'       => $item['price'],
                    'image'       => null,
                    'stock'       => 25,
                ]
            );
        }
    }
}
