<?php

namespace App\Http\Controllers;

use App\Models\Produkt;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Session cart operations for customer checkout.
class CartController extends Controller
{
    // Max quantity allowed for one cart line.
    private const MAX_KS = 99;

    // Show cart page with recommended products.
    public function index()
    {
        $this->restorePersistentCartIfNeeded();
        $kosik = session('cart', []);
        $shippingFee = session('checkout_shipping_fee');
        if ($shippingFee === null) {
            $shippingFee = rand(300, 2000) / 100;
            session(['checkout_shipping_fee' => $shippingFee]);
        }
        $odporucaneProdukty = Produkt::with(['hlavnyObrazok'])
            ->orderBy('sold_count', 'desc')
            ->limit(6)
            ->get();

        return view('cart', [
            'cart' => $kosik,
            'shippingFee' => (float) $shippingFee,
            'odporucaneProdukty' => $odporucaneProdukty,
        ]);
    }

    // Add/update one cart line in session.
    public function add(Request $request, $id)
    {
        $this->restorePersistentCartIfNeeded();
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
        $this->persistForAuthenticatedUser($kosik);
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
        $this->restorePersistentCartIfNeeded();
        $idProduktu = (int) $id;
        $kosik = session('cart', []);

        if (isset($kosik[$idProduktu])) {
            unset($kosik[$idProduktu]);
            session(['cart' => $kosik]);
            $this->persistForAuthenticatedUser($kosik);
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
        $this->persistForAuthenticatedUser([]);

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
        $this->restorePersistentCartIfNeeded();

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

    // Keep user cart in DB and restore it if local session is empty.
    private function restorePersistentCartIfNeeded(): void
    {
        if (!Auth::check()) {
            return;
        }

        $currentSession = session('cart', []);
        if (count($currentSession) > 0) {
            return;
        }

        $restored = CartService::restoreUserCartForSession((int) Auth::id());
        session(['cart' => $restored]);
    }

    // Save session cart into persistent DB cart for logged users.
    private function persistForAuthenticatedUser(array $kosik): void
    {
        if (!Auth::check()) {
            return;
        }

        CartService::persistSessionForUser((int) Auth::id(), $kosik);
    }
}
