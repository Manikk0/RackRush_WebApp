<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategoria;
use App\Models\Produkt;
use App\Models\ObrazokProduktu;

// Seeds categories, products, and product images.
class ProductSeeder extends Seeder
{
    // Defaults used to generate filter-related attributes.
    private array $categoryFilterDefaults = [
        'Ovocie a zelenina' => ['bio' => true, 'plastic' => true, 'allergens' => ['orechy']],
        'Mliečne a chladené' => ['bio' => true, 'plastic' => true, 'allergens' => ['mlieko', 'laktoza']],
        'Mäso a ryby' => ['bio' => false, 'plastic' => true, 'allergens' => ['ryby']],
        'Pečivo' => ['bio' => true, 'plastic' => true, 'allergens' => ['lepok', 'sezam']],
        'Trvanlivé potraviny' => ['bio' => true, 'plastic' => true, 'allergens' => ['lepok', 'soja']],
        'Nápoje' => ['bio' => false, 'plastic' => false, 'allergens' => []],
        'Sladké a slané' => ['bio' => false, 'plastic' => true, 'allergens' => ['orechy', 'mlieko', 'lepok']],
        'Mrazené produkty' => ['bio' => false, 'plastic' => true, 'allergens' => ['mlieko', 'lepok']],
        'Pre deti' => ['bio' => false, 'plastic' => true, 'allergens' => ['mlieko', 'lepok']],
        'Kozmetika a drogéria' => ['bio' => false, 'plastic' => false, 'allergens' => []],
        'Domácnosť' => ['bio' => false, 'plastic' => false, 'allergens' => []],
        'Pre zvieratá' => ['bio' => false, 'plastic' => false, 'allergens' => []],
    ];

    public function run(): void
    {
        $categories = [
            'Ovocie a zelenina' => ['assets/vegetable&fruit.png', [
                ['Hrozno biele, bezsemenné', 3.19, 0.5, 'kg', 'Peru'],
                ['Banány premium', 1.59, 1.0, 'kg', 'Ekvádor'],
                ['Jahody čerstvé', 2.99, 0.5, 'kg', 'Slovensko'],
                ['Paradajky cherry', 1.79, 0.25, 'kg', 'Taliansko'],
                ['Uhorka šalátová', 0.99, 1.0, 'ks', 'Slovensko'],
                ['Paprika červená', 2.29, 0.5, 'kg', 'Španielsko'],
                ['Jablká Gala', 1.89, 1.0, 'kg', 'Poľsko'],
                ['Avokádo Hass', 1.49, 1.0, 'ks', 'Peru'],
                ['Citróny', 1.39, 0.5, 'kg', 'Turecko'],
            ]],
            'Mliečne a chladené' => ['assets/dairy.png', [
                ['Mlieko plnotučné', 1.09, 1.0, 'l', 'Slovensko'],
                ['Maslo farmárske', 2.59, 0.25, 'kg', 'Slovensko'],
                ['Jogurt grécky', 1.29, 0.4, 'kg', 'Česko'],
                ['Syr Eidam 45%', 1.99, 0.2, 'kg', 'Holandsko'],
                ['Smotana na varenie', 1.19, 0.25, 'l', 'Slovensko'],
                ['Tvaroh jemný', 1.49, 0.25, 'kg', 'Slovensko'],
                ['Mozzarella', 1.39, 0.125, 'kg', 'Taliansko'],
                ['Kefír biely', 1.09, 0.5, 'l', 'Slovensko'],
                ['Bryndza ovčia', 2.69, 0.125, 'kg', 'Slovensko'],
            ]],
            'Mäso a ryby' => ['assets/meat.png', [
                ['Kuracie prsia', 5.99, 1.0, 'kg', 'Slovensko'],
                ['Losos filet', 6.49, 0.3, 'kg', 'Nórsko'],
                ['Bravčová krkovička', 4.99, 1.0, 'kg', 'Slovensko'],
                ['Morčacie prsia', 5.49, 0.6, 'kg', 'Slovensko'],
                ['Mleté hovädzie', 4.79, 0.5, 'kg', 'Poľsko'],
                ['Tuniak steak', 5.99, 0.25, 'kg', 'Španielsko'],
                ['Kuracie stehná', 4.29, 1.0, 'kg', 'Slovensko'],
                ['Treska filet', 4.99, 0.4, 'kg', 'Island'],
                ['Bravčové rebierka', 5.39, 0.8, 'kg', 'Česko'],
            ]],
            'Pečivo' => ['assets/breads.png', [
                ['Chlieb celozrnný', 1.49, 1.0, 'ks', 'Slovensko'],
                ['Rožok pšeničný', 0.19, 1.0, 'ks', 'Slovensko'],
                ['Bageta francúzska', 0.79, 1.0, 'ks', 'Slovensko'],
                ['Toastový chlieb', 1.39, 1.0, 'ks', 'Slovensko'],
                ['Kaiserka sezamová', 0.30, 1.0, 'ks', 'Slovensko'],
                ['Croissant maslový', 0.75, 1.0, 'ks', 'Francúzsko'],
                ['Ciabatta', 1.29, 1.0, 'ks', 'Taliansko'],
                ['Pita chlieb', 0.42, 1.0, 'ks', 'Grécko'],
                ['Žemľa grahamová', 0.22, 1.0, 'ks', 'Slovensko'],
            ]],
            'Trvanlivé potraviny' => ['assets/durable_food.png', [
                ['Cestoviny špagety', 0.89, 0.5, 'kg', 'Taliansko'],
                ['Ryža dlhozrnná', 1.29, 1.0, 'kg', 'India'],
                ['Paradajky konzervované', 0.99, 0.4, 'kg', 'Taliansko'],
                ['Olivový olej extra virgin', 5.49, 0.5, 'l', 'Španielsko'],
                ['Fazuľa červená', 1.09, 0.4, 'kg', 'Maďarsko'],
                ['Kuskus', 1.69, 0.5, 'kg', 'Maroko'],
                ['Múka hladká', 0.79, 1.0, 'kg', 'Slovensko'],
                ['Cukor kryštál', 1.19, 1.0, 'kg', 'Slovensko'],
                ['Tuniak v konzerve', 1.69, 0.16, 'kg', 'Portugalsko'],
            ]],
            'Nápoje' => ['assets/drinks.png', [
                ['Minerálna voda', 0.49, 1.5, 'l', 'Slovensko'],
                ['Pomarančový džús', 1.79, 1.0, 'l', 'Španielsko'],
                ['Kola', 1.39, 2.0, 'l', 'USA'],
                ['Ľadový čaj broskyňa', 1.19, 1.5, 'l', 'Poľsko'],
                ['Jablkový džús', 1.59, 1.0, 'l', 'Slovensko'],
                ['Energetický nápoj', 1.49, 0.5, 'l', 'Rakúsko'],
                ['Tonic', 1.09, 1.0, 'l', 'Česko'],
                ['Mandľový nápoj', 2.29, 1.0, 'l', 'Taliansko'],
                ['Kokosová voda', 1.79, 0.33, 'l', 'Thajsko'],
            ]],
            'Sladké a slané' => ['assets/sweet&snacks.png', [
                ['Čokoláda mliečna', 1.29, 0.1, 'kg', 'Belgicko'],
                ['Chipsy paprikové', 1.59, 0.15, 'kg', 'Slovensko'],
                ['Sušienky Oreo', 1.99, 0.154, 'kg', 'USA'],
                ['Tyčinka arašidová', 0.79, 0.05, 'kg', 'Nemecko'],
                ['Krekry syrové', 1.39, 0.12, 'kg', 'Česko'],
                ['Gumené cukríky', 1.69, 0.2, 'kg', 'Nemecko'],
                ['Slané tyčinky', 1.09, 0.25, 'kg', 'Slovensko'],
                ['Oriešky mix', 3.49, 0.3, 'kg', 'USA'],
                ['Karamelové sušienky', 1.89, 0.18, 'kg', 'Holandsko'],
            ]],
            'Mrazené produkty' => ['assets/frozen-food.png', [
                ['Zmrzlina vanilková', 3.49, 0.9, 'l', 'Taliansko'],
                ['Mrazená pizza Margherita', 2.99, 1.0, 'ks', 'Taliansko'],
                ['Mrazená zelenina mix', 2.19, 1.0, 'kg', 'Poľsko'],
                ['Hranolky', 1.99, 0.75, 'kg', 'Belgicko'],
                ['Mrazené maliny', 2.79, 0.5, 'kg', 'Poľsko'],
                ['Kuracie nugetky', 3.29, 0.4, 'kg', 'Slovensko'],
                ['Mrazené krevety', 4.99, 0.3, 'kg', 'Vietnam'],
                ['Špenát listový', 1.69, 0.45, 'kg', 'Maďarsko'],
                ['Mrazené croissanty', 2.59, 6.0, 'ks', 'Francúzsko'],
            ]],
            'Pre deti' => ['assets/baby.png', [
                ['Dojčenská výživa', 3.99, 0.25, 'kg', 'Nemecko'],
                ['Detské cestoviny zvieratká', 1.49, 0.5, 'kg', 'Taliansko'],
                ['Detská voda', 0.59, 0.5, 'l', 'Slovensko'],
                ['Detská kaša ryžová', 2.69, 0.3, 'kg', 'Rakúsko'],
                ['Piškóty detské', 1.29, 0.2, 'kg', 'Slovensko'],
                ['Ovocné kapsičky mix', 3.49, 0.36, 'kg', 'Česko'],
                ['Detský čaj feniklový', 1.99, 20.0, 'ks', 'Nemecko'],
                ['Jogurt pre deti', 2.39, 0.4, 'kg', 'Slovensko'],
                ['Kukuričné chrumky', 0.99, 0.06, 'kg', 'Poľsko'],
            ]],
            'Kozmetika a drogéria' => ['assets/cosmetics.png', [
                ['Šampón na vlasy', 3.49, 0.4, 'l', 'Nemecko'],
                ['Zubná pasta', 1.99, 0.075, 'l', 'Francúzsko'],
                ['Sprchový gél', 2.29, 0.25, 'l', 'Česko'],
                ['Tekuté mydlo', 1.89, 0.5, 'l', 'Slovensko'],
                ['Dezodorant roll-on', 2.19, 0.05, 'l', 'Poľsko'],
                ['Balzam na pery', 1.49, 0.005, 'l', 'Nemecko'],
                ['Krém na ruky', 2.59, 0.1, 'l', 'Česko'],
                ['Pena na holenie', 2.99, 0.2, 'l', 'Taliansko'],
                ['Vatové tampóny', 1.29, 120.0, 'ks', 'Slovensko'],
            ]],
            'Domácnosť' => ['assets/household.png', [
                ['Prostriedok na riad', 1.79, 0.5, 'l', 'Slovensko'],
                ['Toaletný papier', 3.49, 8.0, 'ks', 'Slovensko'],
                ['Prací prášok', 6.99, 1.5, 'kg', 'Nemecko'],
                ['Aviváž', 2.99, 1.0, 'l', 'Poľsko'],
                ['Tablety do umývačky', 5.99, 40.0, 'ks', 'Česko'],
                ['Sáčky na odpad', 2.19, 20.0, 'ks', 'Slovensko'],
                ['Čistič kúpeľne', 2.49, 0.75, 'l', 'Nemecko'],
                ['Univerzálna handrička', 1.39, 5.0, 'ks', 'Slovensko'],
                ['Papierové utierky', 2.09, 2.0, 'ks', 'Poľsko'],
            ]],
            'Pre zvieratá' => ['assets/pet-food.png', [
                ['Granule pre psov', 8.99, 3.0, 'kg', 'Nemecko'],
                ['Konzerva pre mačky', 1.49, 0.4, 'kg', 'Maďarsko'],
                ['Maškrty pre psov', 2.99, 0.15, 'kg', 'Slovensko'],
                ['Podstielka pre mačky', 4.99, 5.0, 'kg', 'Česko'],
                ['Krmivo pre rybičky', 1.69, 0.1, 'kg', 'Poľsko'],
                ['Pamlsky pre mačky', 1.29, 0.06, 'kg', 'Nemecko'],
                ['Miska pre psa', 3.49, 1.0, 'ks', 'Slovensko'],
                ['Šampón pre psa', 3.29, 0.25, 'l', 'Česko'],
                ['Tyčinky pre hlodavce', 1.19, 2.0, 'ks', 'Poľsko'],
            ]],
        ];

        $codeCounter = 1;
        $productIndex = 0;
        $categoryIdsByName = [];

        foreach ($categories as $categoryName => [$categoryImage, $products]) {
            $kategoria = Kategoria::create([
                'name' => $categoryName,
                'image' => $categoryImage,
            ]);
            $categoryIdsByName[$categoryName] = $kategoria->id;

            foreach ($products as [$name, $price, $quantity, $unit, $origin]) {
                $description = $this->buildDescription($name, $categoryName, $origin);
                $recipe = $this->shouldHaveRecipe($productIndex)
                    ? $this->buildRecipe($name, $categoryName)
                    : null;

                $produkt = Produkt::create([
                    'category_id'        => $kategoria->id,
                    'product_code'       => sprintf('PRD%05d', $codeCounter),
                    'name'               => $name,
                    'price'              => $price,
                    'quantity'           => $quantity,
                    'unit'               => $unit,
                    'description'        => $description,
                    'recipe'             => $recipe,
                    'discount'           => [0, 0, 0, 5, 10, 15][rand(0, 5)],
                    'sold_count'         => rand(20, 1400),
                    'country_of_origin'  => $origin,
                    'is_bio'             => $this->generateBioValue($categoryName),
                    'is_plastic_free'    => $this->generatePlasticValue($categoryName),
                    'allergens'          => $this->generateAllergensValue($categoryName),
                ]);

                $imageUrls = $this->buildImageUrlsForProduct($name);
                foreach ($imageUrls as $imageOrder => $imageUrl) {
                    ObrazokProduktu::create([
                        'product_id' => $produkt->id,
                        'url'        => $imageUrl,
                        'order'      => $imageOrder,
                    ]);
                }

                $codeCounter++;
                $productIndex++;
            }
        }

        // More products per category so every category has enough rows for pagination.
        foreach ($this->getExtraProductsByCategory() as $categoryName => $extraProducts) {
            $categoryId = $categoryIdsByName[$categoryName] ?? null;
            if ($categoryId === null) {
                continue;
            }

            foreach ($extraProducts as [$name, $price, $quantity, $unit, $origin]) {
                $description = $this->buildDescription($name, $categoryName, $origin);
                $recipe = $this->shouldHaveRecipe($productIndex)
                    ? $this->buildRecipe($name, $categoryName)
                    : null;

                $produkt = Produkt::create([
                    'category_id'       => $categoryId,
                    'product_code'      => sprintf('PRD%05d', $codeCounter),
                    'name'              => $name,
                    'price'             => $price,
                    'quantity'          => $quantity,
                    'unit'              => $unit,
                    'description'       => $description,
                    'recipe'            => $recipe,
                    'discount'          => [0, 0, 5, 10, 15][rand(0, 4)],
                    'sold_count'        => rand(15, 900),
                    'country_of_origin' => $origin,
                    'is_bio'            => $this->generateBioValue($categoryName),
                    'is_plastic_free'   => $this->generatePlasticValue($categoryName),
                    'allergens'         => $this->generateAllergensValue($categoryName),
                ]);

                $imageUrls = $this->buildImageUrlsForProduct($name);
                foreach ($imageUrls as $imageOrder => $imageUrl) {
                    ObrazokProduktu::create([
                        'product_id' => $produkt->id,
                        'url'        => $imageUrl,
                        'order'      => $imageOrder,
                    ]);
                }

                $codeCounter++;
                $productIndex++;
            }
        }
    }

    /** Extra rows: name, price, quantity, unit, country. */
    private function getExtraProductsByCategory(): array
    {
        return [
            'Ovocie a zelenina' => [
                ['Brokolica čerstvá', 1.89, 0.5, 'kg', 'Slovensko'],
                ['Mrkva mytá', 0.99, 1.0, 'kg', 'Poľsko'],
                ['Cibuľa žltá', 0.79, 1.0, 'kg', 'Slovensko'],
                ['Cesnak biely', 2.49, 0.25, 'kg', 'Španielsko'],
                ['Špenát baby', 2.19, 0.125, 'kg', 'Taliansko'],
                ['Rukola', 1.99, 0.1, 'kg', 'Taliansko'],
                ['Šalát ľadový', 1.29, 1.0, 'ks', 'Slovensko'],
                ['Zemiaky konzumné', 0.69, 2.5, 'kg', 'Slovensko'],
                ['Kukuričnica v struke', 1.59, 2.0, 'ks', 'Slovensko'],
                ['Hrušky Williams', 2.39, 1.0, 'kg', 'Taliansko'],
                ['Slivky modré', 2.79, 0.5, 'kg', 'Slovensko'],
                ['Kiwi zlaté', 3.49, 0.5, 'kg', 'Grécko'],
            ],
            'Mliečne a chladené' => [
                ['Smotana kyslá 20%', 1.39, 0.2, 'l', 'Slovensko'],
                ['Jogurt ovocný mix', 0.89, 0.15, 'kg', 'Slovensko'],
                ['Syr Gouda plátky', 2.19, 0.15, 'kg', 'Holandsko'],
                ['Tvaroh hrudkovitý', 1.69, 0.25, 'kg', 'Slovensko'],
                ['Maslo solené', 2.89, 0.25, 'kg', 'Poľsko'],
                ['Mlieko polotučné 1,5%', 0.99, 1.0, 'l', 'Slovensko'],
                ['Jogurt vanilkový', 0.79, 0.125, 'kg', 'Česko'],
                ['Smetana na šľahanie 33%', 1.99, 0.2, 'l', 'Slovensko'],
                ['Syr Cottage', 1.49, 0.2, 'kg', 'Poľsko'],
                ['Kyslá smotana 12%', 1.09, 0.18, 'l', 'Slovensko'],
                ['Jogurt biely prírodný', 0.69, 0.15, 'kg', 'Slovensko'],
                ['Syr Mozzarella guľka', 1.79, 0.125, 'kg', 'Taliansko'],
            ],
            'Mäso a ryby' => [
                ['Kuracie krídelká', 3.99, 1.0, 'kg', 'Slovensko'],
                ['Bravčová panenka', 8.99, 0.5, 'kg', 'Slovensko'],
                ['Hovädzí steak ribeye', 12.49, 0.4, 'kg', 'Argentína'],
                ['Pstruh celý', 6.29, 0.35, 'kg', 'Slovensko'],
                ['Krevety lúpané', 7.99, 0.25, 'kg', 'Vietnam'],
                ['Morčacie stehno', 4.59, 1.0, 'kg', 'Slovensko'],
                ['Kačacie prsia', 9.49, 0.4, 'kg', 'Maďarsko'],
                ['Sardinky čerstvé', 5.29, 0.3, 'kg', 'Portugalsko'],
                ['Klobása domáca', 6.19, 0.4, 'kg', 'Slovensko'],
                ['Slanina údená', 5.89, 0.3, 'kg', 'Česko'],
                ['Ryža s lososom (porcia)', 4.49, 0.25, 'kg', 'Nórsko'],
                ['Kuracia pečeň', 2.99, 0.5, 'kg', 'Slovensko'],
            ],
            'Pečivo' => [
                ['Chlieb ražný', 1.29, 1.0, 'ks', 'Slovensko'],
                ['Bageta celozrnná', 0.99, 1.0, 'ks', 'Slovensko'],
                ['Langoš', 1.19, 1.0, 'ks', 'Slovensko'],
                ['Šiška s džemom', 0.89, 1.0, 'ks', 'Slovensko'],
                ['Pagáč syrový', 0.45, 1.0, 'ks', 'Slovensko'],
                ['Brioška maslová', 0.65, 1.0, 'ks', 'Francúzsko'],
                ['Chlieb kváskový', 2.49, 1.0, 'ks', 'Slovensko'],
                ['Pita celozrnná', 0.99, 4.0, 'ks', 'Grécko'],
                ['Mini croissanty', 2.99, 0.2, 'kg', 'Francúzsko'],
                ['Chlieb toast celozrnný', 1.59, 1.0, 'ks', 'Slovensko'],
                ['Rohlík makový', 0.25, 1.0, 'ks', 'Slovensko'],
                ['Donut s čokoládou', 0.95, 1.0, 'ks', 'Slovensko'],
            ],
            'Trvanlivé potraviny' => [
                ['Ovsené vločky', 1.39, 0.5, 'kg', 'Poľsko'],
                ['Med agátový', 4.99, 0.35, 'kg', 'Slovensko'],
                ['Kečup jemný', 1.59, 0.5, 'kg', 'Česko'],
                ['Horčica dijon', 1.29, 0.2, 'kg', 'Francúzsko'],
                ['Bazalka sušená', 0.99, 0.02, 'kg', 'Taliansko'],
                ['Soľ morská jemná', 0.89, 0.25, 'kg', 'Španielsko'],
                ['Čierne korenie mleté', 1.09, 0.05, 'kg', 'India'],
                ['Cestoviny penne', 0.95, 0.5, 'kg', 'Taliansko'],
                ['Polievka v prášku', 0.79, 0.06, 'kg', 'Nemecko'],
                ['Omáčka na cestoviny', 1.89, 0.4, 'kg', 'Taliansko'],
                ['Červená šošovica', 1.49, 0.5, 'kg', 'Turecko'],
                ['Quinoa biela', 2.99, 0.4, 'kg', 'Peru'],
            ],
            'Nápoje' => [
                ['Sirup malina', 2.49, 0.7, 'l', 'Slovensko'],
                ['Ľadový čaj citrón', 1.29, 1.5, 'l', 'Poľsko'],
                ['Šťava višňová', 1.99, 1.0, 'l', 'Poľsko'],
                ['Voda perlivá citrón', 0.59, 1.5, 'l', 'Slovensko'],
                ['Energetický nápoj zero', 1.39, 0.5, 'l', 'Rakúsko'],
                ['Pivo ležiak 6 ks', 5.99, 6.0, 'ks', 'Česko'],
                ['Víno biele polosuché', 4.49, 0.75, 'l', 'Slovensko'],
                ['Prosecco', 7.99, 0.75, 'l', 'Taliansko'],
                ['Káva zrnková', 6.49, 0.25, 'kg', 'Taliansko'],
                ['Čaj zelený s citrónom', 1.79, 40.0, 'ks', 'Čína'],
                ['Kakao instantné', 2.29, 0.25, 'kg', 'Holandsko'],
                ['Sirup bazový', 3.19, 0.5, 'l', 'Slovensko'],
            ],
            'Sladké a slané' => [
                ['Sušienky maslové', 1.59, 0.2, 'kg', 'Česko'],
                ['Popcorn slaný', 1.29, 0.1, 'kg', 'USA'],
                ['Lupienky cibuľové', 1.89, 0.15, 'kg', 'Poľsko'],
                ['Marshmallows', 1.49, 0.2, 'kg', 'Nemecko'],
                ['Žuvačky mint', 0.99, 1.0, 'ks', 'USA'],
                ['Trubičky s krémom', 1.69, 0.18, 'kg', 'Slovensko'],
                ['Pralinky mix', 3.99, 0.15, 'kg', 'Belgicko'],
                ['Keksíky s čokoládou', 1.39, 0.2, 'kg', 'Poľsko'],
                ['Arašidy solené', 1.19, 0.15, 'kg', 'USA'],
                ['Hrozienka', 2.19, 0.25, 'kg', 'Turecko'],
                ['Tyčinka proteínová', 1.99, 0.06, 'kg', 'Nemecko'],
                ['Puding vanilkový 4 ks', 2.49, 4.0, 'ks', 'Francúzsko'],
            ],
            'Mrazené produkty' => [
                ['Mrazené brokolica', 1.89, 0.45, 'kg', 'Poľsko'],
                ['Mrkvové plátky mrazené', 1.59, 0.5, 'kg', 'Belgicko'],
                ['Rybie prsty', 3.29, 0.3, 'kg', 'Nórsko'],
                ['Palacinky mrazené', 2.19, 0.4, 'kg', 'Francúzsko'],
                ['Zmrzlina čokoládová', 3.99, 0.5, 'l', 'Taliansko'],
                ['Mrazené šampiňóny', 2.49, 0.3, 'kg', 'Poľsko'],
                ['Lasagne mrazená', 4.29, 0.4, 'kg', 'Taliansko'],
                ['Knedle slivkové mrazené', 3.59, 0.5, 'kg', 'Česko'],
                ['Ryža s kuracím mrazená', 2.99, 0.35, 'kg', 'Thajsko'],
                ['Zeleninová zmes wok', 2.79, 0.6, 'kg', 'Vietnam'],
                ['Mrazené maliny', 3.19, 0.25, 'kg', 'Poľsko'],
                ['Langoše mrazené', 2.89, 4.0, 'ks', 'Slovensko'],
            ],
            'Pre deti' => [
                ['Detské pyré jablko', 1.19, 0.09, 'kg', 'Slovensko'],
                ['Kaša kukuričná', 2.29, 0.2, 'kg', 'Nemecko'],
                ['Detské piškóty s vitamínmi', 1.59, 0.18, 'kg', 'Poľsko'],
                ['Nápoj jahoda', 0.99, 0.2, 'l', 'Slovensko'],
                ['Ovocné pyré broskyňa', 1.39, 0.09, 'kg', 'Česko'],
                ['Detské cereálie', 2.99, 0.25, 'kg', 'UK'],
                ['Žuvačky pre deti', 1.09, 1.0, 'ks', 'Nemecko'],
                ['Detský puding čokoláda', 1.79, 4.0, 'ks', 'Francúzsko'],
                ['Sušienky detské mrkva', 1.49, 0.15, 'kg', 'Slovensko'],
                ['Kaša ovsená detská', 2.19, 0.2, 'kg', 'Rakúsko'],
                ['Detský čaj ovocný', 1.69, 0.3, 'l', 'Slovensko'],
                ['Príkrm zeleninový', 1.29, 0.19, 'kg', 'Taliansko'],
            ],
            'Kozmetika a drogéria' => [
                ['Gél na holenie', 3.99, 0.2, 'l', 'Nemecko'],
                ['Pleťová voda', 4.49, 0.2, 'l', 'Francúzsko'],
                ['Micelárna voda', 5.29, 0.4, 'l', 'Poľsko'],
                ['Kondicionér na vlasy', 3.79, 0.25, 'l', 'Česko'],
                ['Telové mlieko', 4.19, 0.4, 'l', 'Slovensko'],
                ['Holiace žiletky 5 ks', 2.99, 5.0, 'ks', 'Nemecko'],
                ['Vatové tyčinky', 1.09, 200.0, 'ks', 'Slovensko'],
                ['Ústna voda', 3.49, 0.5, 'l', 'USA'],
                ['Maska na tvár', 2.49, 1.0, 'ks', 'Kórea'],
                ['Suchý šampón', 4.99, 0.2, 'l', 'UK'],
                ['Krém na nohy', 3.29, 0.1, 'l', 'Nemecko'],
                ['Odlakovač', 2.19, 0.125, 'l', 'Poľsko'],
            ],
            'Domácnosť' => [
                ['Guma na riad', 0.99, 2.0, 'ks', 'Slovensko'],
                ['Handra z mikrovlákna', 2.49, 3.0, 'ks', 'Čína'],
                ['Saponát na podlahy', 2.79, 1.0, 'l', 'Poľsko'],
                ['WC blok modrý', 1.89, 1.0, 'ks', 'Nemecko'],
                ['Vreckovky box', 1.59, 1.0, 'ks', 'Slovensko'],
                ['Fólia na potraviny', 1.29, 1.0, 'ks', 'Poľsko'],
                ['Alobal', 1.99, 1.0, 'ks', 'Taliansko'],
                ['Rukavice gumové', 2.19, 1.0, 'ks', 'Malajzia'],
                ['Hubka na riad 5 ks', 1.49, 5.0, 'ks', 'Slovensko'],
                ['Čistič skla', 2.39, 0.75, 'l', 'Nemecko'],
                ['Osviežovač vzduchu', 3.49, 0.25, 'l', 'USA'],
                ['Batérie AA 4 ks', 3.99, 4.0, 'ks', 'Japonsko'],
            ],
            'Pre zvieratá' => [
                ['Granule pre mačky', 7.49, 2.0, 'kg', 'Francúzsko'],
                ['Konzerva pre psa', 1.99, 0.41, 'kg', 'Nemecko'],
                ['Pamlsky dentálne pre psa', 3.29, 0.2, 'kg', 'USA'],
                ['Vitamíny pre mačky', 4.99, 0.05, 'kg', 'Nemecko'],
                ['Podstielka drevená', 5.49, 7.0, 'kg', 'Česko'],
                ['Hračka pre mačku', 2.99, 1.0, 'ks', 'Čína'],
                ['Obojok nylonový', 6.99, 1.0, 'ks', 'Poľsko'],
                ['Vodítko pre psa', 8.49, 1.0, 'ks', 'Nemecko'],
                ['Krmivo pre vtáky', 2.19, 0.5, 'kg', 'Slovensko'],
                ['Sen pre hlodavce', 3.99, 1.0, 'kg', 'Maďarsko'],
                ['Miska keramická mačka', 4.49, 1.0, 'ks', 'Poľsko'],
                ['Šampón pre mačky', 3.99, 0.25, 'l', 'Česko'],
            ],
        ];
    }

    private function shouldHaveRecipe(int $index): bool
    {
        // 9/20 = 45% of products will have a recipe.
        return ($index % 20) < 9;
    }

    private function buildDescription(string $name, string $categoryName, string $origin): string
    {
        return $name . ' patrí medzi obľúbené produkty v kategórii ' . $categoryName . ' a je starostlivo vyberaný pre stabilnú kvalitu. '
            . 'Tento produkt má vyváženú chuť, praktické balenie a výborné využitie pri každodennom varení alebo rýchlych jedlách. '
            . 'Pochádza z krajiny ' . $origin . ', pričom pri skladovaní podľa odporúčania si zachováva kvalitu a dobré senzorické vlastnosti.';
    }

    private function buildRecipe(string $name, string $categoryName): string
    {
        return 'Recept s produktom ' . $name . ': Najprv si pripravte základ zo surovín, ktoré bežne používate pri jedlách z kategórie ' . $categoryName . ' a všetko nakrájajte na menšie časti. '
            . 'Následne produkt krátko tepelne upravte alebo premiešajte podľa typu jedla, dochuťte soľou, korením a bylinkami, a nechajte chute prepojiť aspoň 5 minút. '
            . 'Hotové jedlo podávajte so zeleninovou prílohou alebo pečivom; pre výraznejšiu chuť môžete pridať citrónovú šťavu, olivový olej alebo jemný dresing.';
    }

    private function buildImageUrlsForProduct(string $name): array
    {
        // Ready for up to 3 product images. For now, only grapes
        // intentionally have a 3-image gallery (same asset repeated),
        // so slider behavior is visible immediately.
        if ($name === 'Hrozno biele, bezsemenné') {
            return [
                'assets/grapes_white_tray.png',
                'assets/grapes_white_tray.png',
                'assets/grapes_white_tray.png',
            ];
        }

        return ['assets/grapes_white_tray.png'];
    }

    private function generateBioValue(string $categoryName): ?bool
    {
        if (!isset($this->categoryFilterDefaults[$categoryName])) {
            return null;
        }

        $canHaveBio = $this->categoryFilterDefaults[$categoryName]['bio'];
        if (!$canHaveBio) {
            return null;
        }

        return rand(0, 1) === 1;
    }

    private function generatePlasticValue(string $categoryName): ?bool
    {
        if (!isset($this->categoryFilterDefaults[$categoryName])) {
            return null;
        }

        $canHavePlasticInfo = $this->categoryFilterDefaults[$categoryName]['plastic'];
        if (!$canHavePlasticInfo) {
            return null;
        }

        return rand(0, 1) === 1;
    }

    private function generateAllergensValue(string $categoryName): ?string
    {
        if (!isset($this->categoryFilterDefaults[$categoryName])) {
            return null;
        }

        $allergens = $this->categoryFilterDefaults[$categoryName]['allergens'];
        if (count($allergens) === 0) {
            return null;
        }

        $picked = [];
        foreach ($allergens as $allergen) {
            if (rand(0, 1) === 1) {
                $picked[] = $allergen;
            }
        }

        if (count($picked) === 0) {
            return null;
        }

        return implode(',', $picked);
    }
}
