<?php

namespace App\Http\Controllers;

use App\Models\Produkt;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // MAX QTY PER LINE (SAME AS FRONTEND)
    private const MAX_KS = 99;

    // CART PAGE VIEW
    public function index()
    {
        $kosik = session('cart', []);
        $odporucaneProdukty = Produkt::with(['hlavnyObrazok'])
            ->orderBy('sold_count', 'desc')
            ->limit(6)
            ->get();

        return view('cart', [
            'cart' => $kosik,
            'odporucaneProdukty' => $odporucaneProdukty,
        ]);
    }

    // ADD OR UPDATE LINE (SESSION). JSON: exact true = set qty; false = add delta; exact + qty 0 = remove line
    public function add(Request $request, $id)
    {
        $idProduktu = (int) $id;

        $produkt = Produkt::findOrFail($idProduktu);

        $kosik = session('cart', []);

        // REQUEST QTY (DEFAULT 1)
        $mnozstvo = (int) $request->input('quantity', 1);
        if ($mnozstvo < 0) {
            $mnozstvo = 0;
        }
        if ($mnozstvo > self::MAX_KS) {
            $mnozstvo = self::MAX_KS;
        }

        $jePresne = $request->boolean('exact');

        // REMOVE LINE: exact + qty <= 0
        if ($jePresne === true && $mnozstvo <= 0) {
            if (array_key_exists($idProduktu, $kosik)) {
                unset($kosik[$idProduktu]);
            }
            session(['cart' => $kosik]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'cart' => $kosik,
                ]);
            }

            return redirect()->back();
        }

        // EXISTING LINE: REPLACE OR ADD TO QTY
        if (array_key_exists($idProduktu, $kosik) === true) {
            if ($jePresne === true) {
                $kosik[$idProduktu]['quantity'] = $mnozstvo;
            } else {
                $kosik[$idProduktu]['quantity'] = $kosik[$idProduktu]['quantity'] + $mnozstvo;
            }
        } else {
            // NEW LINE: SKIP IF qty 0 WITHOUT exact MODE
            if ($mnozstvo <= 0) {
                session(['cart' => $kosik]);

                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'cart' => $kosik,
                    ]);
                }

                return redirect()->back();
            }

            $kosik[$idProduktu] = $this->polozkaKosikaZProduktu($produkt, $mnozstvo);
        }

        // CLAMP MAX; REMOVE IF QTY <= 0
        if (array_key_exists($idProduktu, $kosik) === true) {
            if ($kosik[$idProduktu]['quantity'] > self::MAX_KS) {
                $kosik[$idProduktu]['quantity'] = self::MAX_KS;
            }
            if ($kosik[$idProduktu]['quantity'] <= 0) {
                unset($kosik[$idProduktu]);
            }
        }

        session(['cart' => $kosik]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart' => $kosik,
            ]);
        }

        return redirect()->back()->with('success', 'Produkt pridaný do košíka!');
    }

    // SESSION CART ROW ARRAY (FOR JSON / VIEWS)
    private function polozkaKosikaZProduktu(Produkt $produkt, int $mnozstvo): array
    {
        $obrazok = 'assets/grapes_white_tray.png';
        if ($produkt->hlavnyObrazok !== null) {
            $obrazok = $produkt->hlavnyObrazok->url;
        }

        return [
            'name' => $produkt->name,
            'quantity' => $mnozstvo,
            'price' => $produkt->cena_po_zlave,
            'old_price' => $produkt->price,
            'discount' => $produkt->discount,
            'image' => $obrazok,
            'weight' => $produkt->mnozstvo_display ?? '1 ks',
        ];
    }

    // REMOVE ONE PRODUCT LINE
    public function remove(Request $request, $id)
    {
        $idProduktu = (int) $id;

        $kosik = session('cart', []);

        if (array_key_exists($idProduktu, $kosik) === true) {
            unset($kosik[$idProduktu]);
            session(['cart' => $kosik]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart' => session('cart', []),
            ]);
        }

        return redirect()->back()->with('success', 'Produkt odstránený.');
    }

    // CLEAR ENTIRE CART
    public function emptyCart(Request $request)
    {
        session()->forget('cart');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart' => [],
            ]);
        }

        return redirect()->back()->with('success', 'Košík bol vyprázdnený.');
    }

    // JSON: FULL CART (DRAWER / JS)
    public function getCart()
    {
        return response()->json(session('cart', []));
    }
}
