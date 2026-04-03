<div class="bg-gray-900 text-white p-6 rounded-lg shadow-2xl border border-gray-800 backdrop-blur-md" wire:poll.1s>
    <div class="flex justify-between items-center mb-6">
        <div>
            <p class="text-gray-400 text-xs uppercase tracking-widest font-semibold">Current Price</p>
            <h2 class="text-4xl font-bold text-green-400 mt-1 transition-all duration-500">
                ${{ number_format($currentPrice, 2) }}
            </h2>
        </div>
        <div class="text-right">
            <p class="text-gray-400 text-xs uppercase tracking-widest font-semibold">Ends In</p>
            <div class="text-3xl font-mono font-bold @if($endTime->diffInSeconds(now()) <= 30) text-red-500 animate-pulse @else text-blue-400 @endif mt-1">
                {{ $endTime->diff(now())->format('%H:%I:%S') }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <button 
            wire:click="placeBid({{ $nextMinBid }})"
            class="col-span-2 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-500 hover:to-indigo-600 py-4 rounded-md font-bold text-lg shadow-lg transform active:scale-95 transition-all"
        >
            Bid ${{ number_format($nextMinBid, 2) }}
        </button>
        
        <button 
            wire:click="placeBid({{ $nextMinBid + 500 }})"
            class="bg-gray-800 hover:bg-gray-700 py-3 rounded-lg font-semibold text-sm border border-gray-700 transition-all"
        >
            + $500
        </button>

        <button 
            wire:click="placeBid({{ $nextMinBid + 1000 }})"
            class="bg-gray-800 hover:bg-gray-700 py-3 rounded-lg font-semibold text-sm border border-gray-700 transition-all"
        >
            + $1000
        </button>
    </div>

    <div class="space-y-3 mt-8">
        <h3 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Recent Bids</h3>
        <div class="max-h-40 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
            {{-- This would be populated from a collection or listener --}}
            <div class="flex justify-between text-sm py-2 border-b border-gray-800 last:border-0 opacity-50 italic">
                <span>Waiting for initial bids...</span>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
           Livewire.on('notify', (data) => {
               // Logic for toast notifications
               alert(data[0].message);
           });
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #111827;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 10px;
        }
    </style>
</div>

