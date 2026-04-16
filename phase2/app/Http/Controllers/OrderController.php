<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Checkout flow: order form, submit, and success page.
class OrderController extends Controller
{
    // Show checkout page.
    public function details()
    {
        // Delivery fee is random in range 3.00€ - 20.00€.
        $shippingFee = session('checkout_shipping_fee');
        if ($shippingFee === null) {
            $shippingFee = rand(300, 2000) / 100;
            session(['checkout_shipping_fee' => $shippingFee]);
        }

        return view('order_details', [
            'shippingFee' => (float) $shippingFee,
        ]);
    }

    // Validate checkout data and create DB order.
    public function place(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:card,cash,transfer'],
            'delivery_city' => ['required', 'string', 'max:120', 'regex:/^[\pL0-9\s\-\.\,]+$/u'],
            'delivery_address' => ['required', 'string', 'max:180', 'min:5'],
            'delivery_floor' => ['nullable', 'integer', 'min:0', 'max:200'],
            'customer_name' => ['required', 'string', 'max:255', 'min:3'],
            'customer_phone' => ['required', 'string', 'max:50', 'regex:/^\+?[0-9\s]{7,20}$/'],
            'customer_email' => ['required', 'email', 'max:255'],
            'courier_note' => ['nullable', 'string', 'max:1200'],
        ], [
            'payment_method.required' => 'Vyberte spôsob platby.',
            'payment_method.in' => 'Vybraný spôsob platby nie je platný.',
            'delivery_city.required' => 'Vyplňte mesto doručenia.',
            'delivery_city.regex' => 'Mesto môže obsahovať iba písmená, čísla a bežné znaky.',
            'delivery_address.required' => 'Vyplňte doručovaciu adresu.',
            'delivery_address.min' => 'Doručovacia adresa je príliš krátka.',
            'delivery_floor.integer' => 'Poschodie musí byť číslo.',
            'delivery_floor.min' => 'Poschodie nemôže byť záporné číslo.',
            'delivery_floor.max' => 'Poschodie je príliš vysoké číslo.',
            'customer_name.required' => 'Vyplňte meno a priezvisko.',
            'customer_name.min' => 'Meno a priezvisko je príliš krátke.',
            'customer_phone.required' => 'Vyplňte telefónne číslo.',
            'customer_phone.regex' => 'Telefónne číslo má nesprávny formát.',
            'customer_email.required' => 'Vyplňte e-mail.',
            'customer_email.email' => 'Zadajte platnú e-mailovú adresu.',
        ], [
            'payment_method' => 'spôsob platby',
            'delivery_city' => 'mesto',
            'delivery_address' => 'adresa',
            'delivery_floor' => 'poschodie',
            'customer_name' => 'meno a priezvisko',
            'customer_phone' => 'telefónne číslo',
            'customer_email' => 'e-mail',
            'courier_note' => 'poznámka pre kuriéra',
        ]);

        $cart = session('cart', []);
        if (count($cart) === 0) {
            return redirect()->route('cart')->withErrors([
                'checkout' => 'Košík je prázdny. Najprv pridajte produkty.',
            ]);
        }

        $shippingFee = (float) (session('checkout_shipping_fee') ?? (rand(300, 2000) / 100));
        $shippingFee = round(max(3, min(20, $shippingFee)), 2);

        $orderId = DB::transaction(function () use ($validated, $cart, $shippingFee) {
            $orderId = DB::table('orders')->insertGetId([
                'user_id' => Auth::id(),
                'status' => 'confirmed',
                'payment_method' => $validated['payment_method'],
                'courier_note' => $validated['courier_note'] ?? null,
                'discount_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($cart as $productId => $item) {
                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_id' => (int) $productId,
                    'product_name' => $item['name'],
                    'price_per_item' => (float) $item['price'],
                    'quantity' => (int) $item['quantity'],
                    'unit' => (string) ($item['weight'] ?? 'ks'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $subtotal = 0.0;
            foreach ($cart as $item) {
                $subtotal += ((float) $item['price']) * ((int) $item['quantity']);
            }
            $total = round($subtotal + $shippingFee, 2);
            $vat = round($total * 0.2, 2);
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad((string) $orderId, 6, '0', STR_PAD_LEFT);

            $invoiceId = DB::table('invoices')->insertGetId([
                'order_id' => $orderId,
                'invoice_number' => $invoiceNumber,
                'total_amount' => $total,
                'vat_amount' => $vat,
                'billing_details' => json_encode([
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'customer_email' => $validated['customer_email'],
                    'shipping_fee' => $shippingFee,
                    'delivery_city' => $validated['delivery_city'],
                    'delivery_address' => $validated['delivery_address'],
                    'delivery_floor' => $validated['delivery_floor'] ?? null,
                ], JSON_UNESCAPED_UNICODE),
                'currency' => 'EUR',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('payments')->insert([
                'invoice_id' => $invoiceId,
                'method' => $validated['payment_method'],
                'status' => 'pending',
                'reference' => 'PAY-' . $orderId . '-' . now()->format('His'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $orderId;
        });

        session()->forget('cart');
        if (Auth::check()) {
            CartService::persistSessionForUser((int) Auth::id(), []);
        }
        session()->forget('checkout_shipping_fee');

        return redirect()->route('order_success', ['order' => $orderId]);
    }

    // Show success page with real order data.
    public function success(int $order)
    {
        $orderRow = DB::table('orders')->where('id', $order)->first();
        if ($orderRow === null) {
            abort(404);
        }

        $items = DB::table('order_items')
            ->where('order_id', $order)
            ->orderBy('id')
            ->get();

        $subtotal = 0.0;
        foreach ($items as $item) {
            $subtotal += ((float) $item->price_per_item) * ((float) $item->quantity);
        }

        $invoice = DB::table('invoices')->where('order_id', $order)->first();
        $shippingFee = 0.0;
        $total = round($subtotal, 2);
        if ($invoice !== null) {
            $total = (float) $invoice->total_amount;
            $decoded = json_decode((string) $invoice->billing_details, true);
            if (is_array($decoded) && isset($decoded['shipping_fee'])) {
                $shippingFee = (float) $decoded['shipping_fee'];
            }
        }

        return view('order_success', [
            'orderRow' => $orderRow,
            'items' => $items,
            'subtotal' => round($subtotal, 2),
            'shippingFee' => round($shippingFee, 2),
            'total' => round($total, 2),
        ]);
    }
}
