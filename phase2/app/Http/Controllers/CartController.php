<?php

namespace App\Http\Controllers;

use App\Models\Produkt;
use Illuminate\Http\Request;

// Session cart operations for customer checkout.
class CartController extends Controller
{
    // Max quantity allowed for one cart line.
    private const MAX_KS = 99;

    // Show cart page with recommended products.
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

    // Add/update one cart line in session.
    public function add(Request $request, $id)
    {
        $idProduktu = (int) $id;
        $produkt = Produkt::findOrFail($idProduktu);
        $kosik = session('cart', []);
        $mnozstvo = (int) $request->input('quantity', 1);
        $mnozstvo = max(0, min(self::MAX_KS, $mnozstvo));

        $jePresne = $request->boolean('exact');

        if ($jePresne && $mnozstvo <= 0) {
            if (isset($kosik[$idProduktu])) {
                unset($kosik[$idProduktu]);
            }
            session(['cart' => $kosik]);
            return $this->returnCartResponse($request, $kosik);
        }

        if (isset($kosik[$idProduktu])) {
            if ($jePresne) {
                $kosik[$idProduktu]['quantity'] = $mnozstvo;
            } else {
                $kosik[$idProduktu]['quantity'] += $mnozstvo;
            }
        } else {
            if ($mnozstvo <= 0) {
                session(['cart' => $kosik]);
                return $this->returnCartResponse($request, $kosik);
            }

            $kosik[$idProduktu] = $this->polozkaKosikaZProduktu($produkt, $mnozstvo);
        }

        if (isset($kosik[$idProduktu])) {
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

    // Build cart row structure from product model.
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

    // Remove one product line from cart.
    public function remove(Request $request, $id)
    {
        $idProduktu = (int) $id;
        $kosik = session('cart', []);

        if (isset($kosik[$idProduktu])) {
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

    // Remove all items from cart.
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

    // Return current cart as JSON.
    public function getCart()
    {
        return response()->json(session('cart', []));
    }

    // Return JSON or redirect response based on request type.
    private function returnCartResponse(Request $request, array $kosik)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart' => $kosik,
            ]);
        }

        return redirect()->back();
    }
}
