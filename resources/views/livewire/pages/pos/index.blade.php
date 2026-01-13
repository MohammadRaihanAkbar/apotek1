<?php

use function Livewire\Volt\{state, computed};
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

state([
    'search' => '',
    'cart' => [],
    'discountPercent' => 0,
    'paid' => 0,
    'paymentMethod' => 'cash',
    'showReceipt' => false,
    'lastSaleId' => null,
]);

$searchProducts = computed(function () {
    if (strlen($this->search) < 2) {
        return [];
    }

    return Product::where('is_active', true)
        ->where('stock', '>', 0)
        ->where(function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('kode_obat', 'like', '%' . $this->search . '%');
        })
        ->limit(10)
        ->get();
});

$receiptSale = computed(function () {
    if (!$this->lastSaleId) return null;
    return Sale::with(['items', 'user'])->find($this->lastSaleId);
});

$addToCart = function ($productId) {
    if ($this->showReceipt) return;
    
    $product = Product::find($productId);

    if (!$product || $product->stock < 1) {
        session()->flash('error', 'Produk tidak tersedia atau stok habis!');
        return;
    }

    $existingIndex = collect($this->cart)->search(fn($item) => $item['product_id'] == $productId);

    if ($existingIndex !== false) {
        if ($this->cart[$existingIndex]['qty'] < $product->stock) {
            $this->cart[$existingIndex]['qty']++;
            $this->cart[$existingIndex]['subtotal'] = $this->cart[$existingIndex]['qty'] * $this->cart[$existingIndex]['price'];
        } else {
            session()->flash('error', 'Stok tidak mencukupi!');
        }
    } else {
        $this->cart[] = [
            'product_id' => $product->id,
            'product_code' => $product->kode_obat,
            'product_name' => $product->name,
            'price' => $product->price,
            'qty' => 1,
            'stock' => $product->stock,
            'subtotal' => $product->price,
        ];
    }

    $this->search = '';
};

$updateQty = function ($index, $qty) {
    if ($qty < 1) {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        return;
    }

    if ($qty > $this->cart[$index]['stock']) {
        session()->flash('error', 'Qty melebihi stok tersedia!');
        return;
    }

    $this->cart[$index]['qty'] = $qty;
    $this->cart[$index]['subtotal'] = $qty * $this->cart[$index]['price'];
};

$removeFromCart = function ($index) {
    unset($this->cart[$index]);
    $this->cart = array_values($this->cart);
};

$total = computed(function () {
    return collect($this->cart)->sum('subtotal');
});

$discountAmount = computed(function () {
    return ((float)$this->total * (float)$this->discountPercent) / 100;
});

$grandTotal = computed(function () {
    return (float)$this->total - (float)$this->discountAmount;
});

$change = computed(function () {
    return max(0, $this->paid - $this->grandTotal);
});

$processPayment = function () {
    if (empty($this->cart)) {
        session()->flash('error', 'Keranjang masih kosong!');
        return;
    }

    if ($this->paid < $this->grandTotal) {
        session()->flash('error', 'Pembayaran kurang dari total!');
        return;
    }

    DB::beginTransaction();
    try {
        $sale = Sale::create([
            'invoice_number' => Sale::generateInvoiceNumber(),
            'user_id' => auth()->id(),
            'total' => $this->total,
            'discount' => $this->discountAmount,
            'grand_total' => $this->grandTotal,
            'paid' => $this->paid,
            'change' => $this->change,
            'status' => 'PAID',
        ]);

        foreach ($this->cart as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'product_code' => $item['product_code'],
                'product_name' => $item['product_name'],
                'qty' => $item['qty'],
                'unit_price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);

            $product = Product::find($item['product_id']);
            $product->decrement('stock', $item['qty']);

            StockMovement::recordMovement(
                $item['product_id'], 'OUT', $item['qty'], 'sale', $sale->id, 'Penjualan - ' . $sale->invoice_number
            );
        }

        DB::commit();

        $this->lastSaleId = $sale->id;
        $this->showReceipt = true;
        $this->cart = [];
        $this->discountPercent = 0;
        $this->paid = 0;
        $this->search = '';

    } catch (\Exception $e) {
        DB::rollBack();
        session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
};

$clearCart = function () {
    $this->cart = [];
    $this->discountPercent = 0;
    $this->paid = 0;
};

$closeReceipt = function () {
    $this->showReceipt = false;
    $this->lastSaleId = null;
};

?>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Point of Sale (POS)
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session()->has('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Search -->
                <div class="bg-white shadow-xl sm:rounded-lg p-6">
                    <div class="relative">
                        <input type="text" wire:model.live="search" placeholder="Cari obat..." class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-lg">
                        <div class="absolute left-3 top-3.5 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>

                    @if(strlen($search) >= 2 && count($this->searchProducts) > 0)
                        <div class="mt-4 border border-gray-200 rounded-lg max-h-64 overflow-y-auto">
                            @foreach($this->searchProducts as $product)
                                <div wire:key="search-{{ $product->id }}" wire:click="addToCart({{ $product->id }})" class="p-4 hover:bg-blue-50 cursor-pointer border-b last:border-b-0 transition flex justify-between items-center">
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $product->kode_obat }} • Stok: {{ $product->stock }}</div>
                                    </div>
                                    <div class="font-bold text-blue-600">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Cart -->
                <div class="bg-white shadow-xl sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4 text-gray-800">
                        <h3 class="text-lg font-semibold">Keranjang Belanja</h3>
                        @if(count($cart) > 0) <button wire:click="clearCart" class="text-red-500 text-sm font-bold">Kosongkan</button> @endif
                    </div>

                    @if(empty($cart))
                        <div class="text-center py-12 text-gray-400">Keranjang masih kosong</div>
                    @else
                        <div class="space-y-3">
                            @foreach($cart as $index => $item)
                                <div wire:key="cart-{{ $index }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <div class="font-bold text-gray-900">{{ $item['product_name'] }}</div>
                                        <div class="text-xs text-gray-500">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                                    </div>
                                    <div class="flex items-center space-x-4 font-mono">
                                        <div class="flex items-center space-x-2">
                                            <button wire:click="updateQty({{ $index }}, {{ $item['qty'] - 1 }})" class="w-8 h-8 rounded-full bg-gray-200">-</button>
                                            <span class="w-8 text-center">{{ $item['qty'] }}</span>
                                            <button wire:click="updateQty({{ $index }}, {{ $item['qty'] + 1 }})" class="w-8 h-8 rounded-full bg-gray-200">+</button>
                                        </div>
                                        <div class="w-24 text-right font-bold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                                        <button wire:click="removeFromCart({{ $index }})" class="text-red-500">✕</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-xl sm:rounded-lg p-6 sticky top-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Pembayaran</h3>
                    <div class="flex justify-between"><span>Subtotal:</span> <span class="font-bold">Rp {{ number_format($this->total, 0, ',', '.') }}</span></div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">DISKON (%)</label>
                        <input type="number" wire:model.live="discountPercent" min="0" max="100" class="w-full border-gray-300 rounded-lg">
                        @if($this->discountPercent > 0)
                            <div class="text-xs text-green-600 mt-1 font-medium italic">Potongan: Rp {{ number_format($this->discountAmount, 0, ',', '.') }}</div>
                        @endif
                    </div>

                    <div class="border-t pt-2">
                        <div class="flex justify-between text-xl font-black text-blue-600">
                            <span>TOTAL:</span>
                            <span>Rp {{ number_format($this->grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">BAYAR (RP)</label>
                        <input type="number" wire:model.live="paid" class="w-full border-gray-300 rounded-xl text-lg font-bold">
                    </div>

                    @if($paid >= $this->grandTotal && $paid > 0)
                        <div class="bg-green-50 border-2 border-green-500 p-4 rounded-xl text-center">
                            <div class="text-xs text-green-600 font-bold uppercase">Kembalian</div>
                            <div class="text-2xl font-black text-green-700">Rp {{ number_format($this->change, 0, ',', '.') }}</div>
                        </div>
                    @endif

                    <button wire:click="processPayment" @disabled(empty($cart) || $paid < $this->grandTotal) class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white font-bold py-4 rounded-xl shadow-lg transition">Proses Pembayaran</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    @if($showReceipt && $this->receiptSale)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm" wire:click="closeReceipt"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl overflow-hidden max-w-md w-full animate-in zoom-in duration-300">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-8 text-white text-center">
                    <div class="bg-white/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold">Transaksi Berhasil!</h3>
                    <p class="text-blue-100 text-sm mt-1">Invoice A4 kini tersedia untuk diunduh.</p>
                </div>

                <div class="p-8 space-y-6">
                    <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl p-6 font-mono text-xs">
                        <div class="text-center mb-6">
                            <div class="font-bold text-gray-900 uppercase tracking-widest text-lg">Apotek Kita</div>
                            <div class="text-[10px] text-gray-400">INVOICE: {{ $this->receiptSale->invoice_number }}</div>
                            <div class="text-[10px] text-gray-400">{{ $this->receiptSale->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        
                        <!-- Mini Preview List -->
                        <div class="divide-y divide-gray-200 mb-4 max-h-32 overflow-y-auto pr-2">
                            @foreach($this->receiptSale->items as $item)
                                <div class="py-1.5 flex justify-between text-[11px]">
                                    <span class="text-gray-600">{{ $item->qty }}x {{ $item->product_name }}</span>
                                    <span class="font-bold text-gray-900">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-200 pt-4 space-y-1">
                            <div class="flex justify-between font-bold text-base text-blue-600">
                                <span>TOTAL</span>
                                <span>Rp {{ number_format($this->receiptSale->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button onclick="window.open('{{ route('print.receipt', $this->lastSaleId) }}', '_blank')" 
                            class="flex justify-center items-center px-4 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg active:scale-95">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Invoice PDF
                        </button>
                        <button wire:click="closeReceipt" class="flex justify-center items-center px-4 py-4 bg-gray-100 text-gray-700 rounded-2xl font-bold hover:bg-gray-200 transition">
                            Selesai
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
