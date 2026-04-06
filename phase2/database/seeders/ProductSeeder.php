<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategoria;
use App\Models\Produkt;
use App\Models\ObrazokProduktu;

class ProductSeeder extends Seeder
{
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

        foreach ($categories as $categoryName => [$categoryImage, $products]) {
            $kategoria = Kategoria::create([
                'name' => $categoryName,
                'image' => $categoryImage,
            ]);

            foreach ($products as [$name, $price, $quantity, $unit, $origin]) {
                $description = $this->buildDescription($name, $categoryName, $origin);
                $recipe = $this->shouldHaveRecipe($productIndex)
                    ? $this->buildRecipe($name, $categoryName)
                    : null;

                $produkt = Produkt::create([
                    'category_id'        => $kategoria->id,
                    'product_code'       => sprintf('PRD%03d', $codeCounter),
                    'name'               => $name,
                    'price'              => $price,
                    'quantity'           => $quantity,
                    'unit'               => $unit,
                    'description'        => $description,
                    'recipe'             => $recipe,
                    'discount'           => [0, 0, 0, 5, 10, 15][rand(0, 5)],
                    'sold_count'         => rand(20, 1400),
                    'country_of_origin'  => $origin,
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

    private function shouldHaveRecipe(int $index): bool
    {
        // 9/20 = 45% of products will have a recipe.
        return ($index % 20) < 9;
    }

    private function buildDescription(string $name, string $categoryName, string $origin): string
    {
        return $name . ' patrí medzi obľúbené produkty v kategórii ' . $categoryName . ' a je starostlivo vyberaný pre stabilnú kvalitu. '
            . 'Tento produkt má vyváženú chuť, praktické balenie a výborné využitie pri každodennom varení alebo rýchlych jedlách. '
            . 'Pochádza z krajiny ' . $origin . ', pričom pri skladovaní podľa odporúčania si zachováva čerstvosť aj dobré senzorické vlastnosti.';
    }

    private function buildRecipe(string $name, string $categoryName): string
    {
        return 'Recept s produktom ' . $name . ': Najprv si pripravte základ zo surovín, ktoré bežne používate pri jedlách z kategórie ' . $categoryName . ' a všetko nakrájajte na menšie časti. '
            . 'Následne produkt krátko tepelne upravte alebo premiešajte podľa typu jedla, dochuťte soľou, korením a bylinkami, a nechajte chute prepojiť aspoň 5 minút. '
            . 'Hotové jedlo podávajte s čerstvou prílohou alebo pečivom; pre výraznejšiu chuť môžete pridať citrónovú šťavu, olivový olej alebo jemný dresing.';
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
}
