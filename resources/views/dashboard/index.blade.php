@extends('layouts.app')

@section('content')

    <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          {{ __('Dashboard') }}
      </h2>
    </div>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-4 sm:px-0">
            <!-- Portfolio Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">Cash Balance</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">${{ number_format($cashBalance, 2) }}</dd>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        
                    </div>
                </div>
            </div>

            <!-- Holdings Table -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Portfolio Holdings</h3>
                </div>

                @if($holdings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Symbol</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($holdings as $holding)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $holding['symbol'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($holding['shares'], 0) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($holding['avg_price'], 2) }}</td>
                        
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <form action="{{ route('stock.sell') }}" method="POST" class="inline" onsubmit="return confirm('Sell shares of {{ $holding['symbol'] }}?');">
                                        @csrf
                                        <input type="hidden" name="symbol" value="{{ $holding['symbol'] }}">
                                        <input type="number" name="quantity" min="1" max="{{ $holding['quantity'] }}" value="1" class="w-20 px-2 py-1 border rounded">
                                        <button type="submit" class="ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs">
                                            Sell
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="px-4 py-5 sm:p-6 text-center text-gray-500">
                    No holdings yet. <a href="{{ route('portfolio.index') }}" class="text-indigo-600 hover:text-indigo-900">Search for stocks</a> to start trading.
                </div>                
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
