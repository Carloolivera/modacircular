<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Mostrar el carrito
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = $this->calculateTotal($cart);

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Agregar producto al carrito
     */
    public function add(Request $request, Product $product)
    {
        if (!$product->isAvailable()) {
            return redirect()->back()->with('error', 'Este producto no está disponible');
        }

        $cart = session()->get('cart', []);

        $quantity = $request->input('quantity', 1);

        // Verificar stock disponible
        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $quantity;
        } else {
            $newQuantity = $quantity;
        }

        if ($newQuantity > $product->stock) {
            return redirect()->back()->with('error', 'No hay suficiente stock disponible');
        }

        // Agregar o actualizar producto en el carrito
        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $newQuantity,
            'image' => $product->primaryImage?->path ?? null,
            'size' => $product->size,
            'color' => $product->color,
        ];

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Producto agregado al carrito');
    }

    /**
     * Actualizar cantidad de un producto
     */
    public function update(Request $request, $productId)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$productId])) {
            return redirect()->back()->with('error', 'Producto no encontrado en el carrito');
        }

        $quantity = $request->input('quantity', 1);

        // Verificar stock
        $product = Product::find($productId);
        if ($quantity > $product->stock) {
            return redirect()->back()->with('error', 'No hay suficiente stock disponible');
        }

        if ($quantity <= 0) {
            return $this->remove($productId);
        }

        $cart[$productId]['quantity'] = $quantity;
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Carrito actualizado');
    }

    /**
     * Eliminar producto del carrito
     */
    public function remove($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Producto eliminado del carrito');
    }

    /**
     * Vaciar el carrito
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Carrito vaciado');
    }

    /**
     * Checkout - Preparar pedido para WhatsApp
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'El carrito está vacío');
        }

        $total = $this->calculateTotal($cart);

        // Obtener configuraciones
        $whatsappNumber = Setting::get('whatsapp_number', '5491112345678');
        $messageTemplate = Setting::get('whatsapp_message_template', '¡Hola! Me gustaría hacer el siguiente pedido:');

        return view('cart.checkout', compact('cart', 'total', 'whatsappNumber', 'messageTemplate'));
    }

    /**
     * Generar mensaje de WhatsApp y redireccionar
     */
    public function sendToWhatsApp(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'shipping_method' => 'required|string',
            'payment_method' => 'required|string',
            'address' => 'required_if:shipping_method,moto|string|max:500',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'El carrito está vacío');
        }

        // Construir mensaje
        $message = $this->buildWhatsAppMessage($cart, $request);

        // Número de WhatsApp
        $whatsappNumber = Setting::get('whatsapp_number', '5491112345678');

        // URL de WhatsApp
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);

        // Limpiar carrito
        session()->forget('cart');

        return redirect()->away($whatsappUrl);
    }

    /**
     * Calcular total del carrito
     */
    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    /**
     * Construir mensaje de WhatsApp
     */
    private function buildWhatsAppMessage($cart, $request)
    {
        $message = "🛍️ *NUEVO PEDIDO - Moda Circular*\n\n";
        $message .= "👤 *Cliente:* {$request->name}\n";
        $message .= "📱 *Teléfono:* {$request->phone}\n\n";

        $message .= "📦 *Productos:*\n";
        $total = 0;
        foreach ($cart as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;

            $message .= "• {$item['name']}\n";
            if ($item['size']) $message .= "  Talle: {$item['size']}\n";
            if ($item['color']) $message .= "  Color: {$item['color']}\n";
            $message .= "  Cantidad: {$item['quantity']} x \${$item['price']}\n";
            $message .= "  Subtotal: \${$subtotal}\n\n";
        }

        $message .= "💰 *Total: \$" . number_format($total, 2) . "*\n\n";

        // Método de envío
        $shippingMethods = [
            'moto' => 'Envío en moto',
            'pickup' => 'Retiro en persona',
        ];
        $message .= "🚚 *Envío:* {$shippingMethods[$request->shipping_method]}\n";

        if ($request->shipping_method === 'moto' && $request->address) {
            $message .= "📍 *Dirección:* {$request->address}\n";
        }

        // Método de pago
        $paymentMethods = [
            'mercadopago' => 'Mercado Pago',
            'transfer' => 'Transferencia',
            'cash' => 'Efectivo',
        ];
        $message .= "💳 *Pago:* {$paymentMethods[$request->payment_method]}\n";

        if ($request->notes) {
            $message .= "\n📝 *Notas:* {$request->notes}\n";
        }

        return $message;
    }
}
