<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row w-full justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Details') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white flex flex-col gap-y-5 overflow-hidden p-10 shadow-sm sm:rounded-lg">

                <div class="item-card flex gap-y-3 flex-col md:flex-row justify-between md:items-center">
                    <div class="flex flex-row items-center gap-x-3">
                        <div>
                            <p class="text-base text-slate-500">
                                Total Transaksi
                            </p>

                            <h3 class="text-xl font-bold text-indigo-950" >
                                Rp {{$transactionProduct->total_amount}}
                            </h3>
                        </div>
                    </div>

                    <div>
                        <p class="text-base text-slate-500">
                            Tanggal
                        </p>

                        <h3 class="text-xl font-bold text-indigo-950" >
                            {{$transactionProduct->created_at}}
                        </h3>
                    </div>

                    @if($transactionProduct->is_paid)
                        <span class="py-1 px-3 w-fit rounded-full text-white bg-green-500">
                            <p class="text-white font-bold text-sm">
                                SUCCESS!
                            </p>
                        </span>
                    @else
                        <span class="py-1 px-3 w-fit rounded-full text-white bg-orange-500">
                            <p class="text-white font-bold text-sm">
                                PENDING!
                            </p>
                        </span>
                    @endif
                </div>

                <hr class="my-3">

                <h3 class="text-xl font-bold text-indigo-950" >
                    List of Items
                </h3>

                <div class="grid-cols-1 md:grid-cols-4 grid gap-x-10">
                    <div class="flex flex-col gap-y-5 col-span-2">

                        @forelse($transactionProduct->transactionDetails as $detail)
                            <div class="item-card flex flex-row justify-between items-center">
                                <div class="flex flex-row items-center gap-x-3">
                                    <img src="{{Storage::url($detail->product->photo)}}" alt="" class="w-[50px] h-[50px]">
                                    <div>
                                        <h3 class="text-xl font-bold text-indigo-950" >
                                            {{$detail->product->name}}
                                        </h3>
                
                                        <p class="text-base text-slate-500">
                                            Rp {{$detail->product->price}}
                                        </p>
                                    </div>
                                </div>
            
                                <p class="text-base text-slate-500">
                                    {{$detail->product->category->name}}
                                </p>
                            </div>
                        @empty
                        @endforelse

                        <h3 class="text-xl font-bold text-indigo-950" >
                            Details of Delivery
                        </h3>
                        <div class="item-card flex flex-row justify-between items-center">
                            <p class="text-base text-slate-500">
                                Address
                            </p>
                            <h3 class="text-xl font-bold text-indigo-950" >
                                {{$transactionProduct->address}}
                            </h3>
                        </div>
                        <div class="item-card flex flex-row justify-between items-center">
                            <p class="text-base text-slate-500">
                                City
                            </p>
                            <h3 class="text-xl font-bold text-indigo-950" >
                                {{$transactionProduct->city}}
                            </h3>
                        </div>
                        <div class="item-card flex flex-row justify-between items-center">
                            <p class="text-base text-slate-500">
                                Post Code
                            </p>
                            <h3 class="text-xl font-bold text-indigo-950" >
                                {{$transactionProduct->postcode}}
                            </h3>
                        </div>
                        <div class="item-card flex flex-row justify-between items-center">
                            <p class="text-base text-slate-500">
                                Phone Number
                            </p>
                            <h3 class="text-xl font-bold text-indigo-950" >
                                {{$transactionProduct->phone_number}}
                            </h3>
                        </div>
                        <div class="item-card flex flex-col items-start">
                            <p class="text-base text-slate-500">
                                Notes
                            </p>
                            <h3 class="text-lg font-bold text-indigo-950" >
                                {{$transactionProduct->notes}}
                            </h3>
                        </div>
                    </div>

                    <div class="flex flex-col gap-y-5 col-span-2 items-end">
                        <h3 class="text-xl font-bold text-indigo-950" >
                            Proof of Payment: 
                        </h3>
                        <img src="{{Storage::url($transactionProduct->proof)}}" alt="{{Storage::url($transactionProduct->proof)}}" class="w-[300px] h-[400px]">
                    </div>
                </div>

                <hr class="my-3">

                @role('owner')
                @if($transactionProduct->is_paid)
                    <a href="#" class=" w-fit py-3 px-5 rounded-full text-white bg-indigo-700">
                        Contact Customer
                    </a>
                @else
                    <form method="POST" action="{{ route('transaction_products.update', $transactionProduct) }}">
                        @csrf
                        @method('PUT')
                        <button class="py-3 px-5 rounded-full text-white bg-indigo-700">
                            Approve Order
                        </button>
                    </form>
                @endif
                @endrole
                
                @role('buyer')
                <a href="#" class=" w-fit py-3 px-5 rounded-full text-white bg-indigo-700">
                    Contact Admin
                </a>
                @endrole
                
            </div>
        </div>
    </div>
</x-app-layout>

