@extends('layouts.app')

@section('content')


    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Portfolio') }}
        </h2>
      </div>
    </div>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
              
    @if(session('error')) <div class="alert alert-danger mt-3">{{ session('error') }}</div> @endif
    @if(session('success')) <div class="alert alert-success mt-3">{{ session('success') }}</div> @endif

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="card">
                      <div class="card-body">
                        <h3 class="card-title fw-bold">Your Portfolio</h3>
                        <p class="float-right"><strong>Cash Balance:</strong> ${{ number_format($balance->cash, 2) }}</p>

                        <table class="table table-striped">
                          <thead><tr><th>Symbol</th><th>Shares</th><th>Avg Price</th></tr></thead>
                          <tbody>
                            @forelse($portfolio as $item)
                            <tr>
                              <td>{{ $item->symbol }}</td>
                              <td>{{ $item->shares }}</td>
                              <td>${{ number_format($item->avg_price, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3">No holdings yet.</td></tr>
                            @endforelse
                          </tbody>
                        </table>                       
                      </div>
                    </div>

                    <hr class="mt-5 mb-5">

                    <div class="form-container">
                      <div class="row">
                        <!-- Left Section (50%) -->
                        <div class="col-6">
                          <div class="card">
                            <div class="card-body">
                              <h3 class="card-title fw-bold">Buy Stock</h3>                  
                                <form method="POST" action="{{ route('stock.buy') }}" class="row g-2 align-items-center">
                                  @csrf
                                  <div class="col-auto"><input name="symbol" class="form-control" placeholder="Symbol (AAPL)" required></div>
                                  <div class="col-auto"><input name="quantity" type="number" min="1" class="form-control" placeholder="Quantity" required></div>
                                  <div class="col-auto"><button type="submit" class="btn btn-success">Buy</button></div>
                                </form>                  
                            </div>
                          </div>
                        </div>

                        <!-- Right Section (50%) -->
                        <div class="col-6">
                          <div class="card">
                            <div class="card-body">
                              <h3 class="card-title fw-bold">Sell Stock</h3>                  

                                <form method="POST" action="{{ route('stock.sell') }}" class="row g-2 align-items-center">
                                  @csrf
                                  <div class="col-auto"><input name="symbol" class="form-control" placeholder="Symbol (AAPL)" required></div>
                                  <div class="col-auto"><input name="quantity" type="number" min="1" class="form-control" placeholder="Quantity" required></div>
                                  <div class="col-auto"><button type="submit" class="btn btn-danger">Sell</button></div>
                                </form>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    


@endsection
