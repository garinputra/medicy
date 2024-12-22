<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use App\Models\TransactionProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TransactionProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::user();

        if($user->hasRole('buyer')){
            $transaction_products = $user->transaction_products()->orderBy('id', 'DESC')->get();
        }else{
            $transaction_products = TransactionProduct::orderBy('id', 'DESC')->get();
        }

        return view('admin.transaction_products.index',[
            'transaction_products' => $transaction_products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $user = Auth::user();

        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postcode' => 'required|integer',
            'phone_number' => 'required|integer',
            'notes' => 'required|string|max:65535',
            'proof' => 'required|image|mimes:png,jpg,jpeg',

        ]);

        DB::beginTransaction();

        try{
            $subTotalCents = 0;
            $deliveryFeeCents = 10000 * 100;

            $cartItems = $user->carts;

            foreach($cartItems as $item){
                $subTotalCents += $item->product->price * 100;
            }

            $taxCents = (int)round(10/100 * $subTotalCents);
            $insuranceCents = (int)round(20/100 * $subTotalCents);
            $grandTotalCents = $subTotalCents + $taxCents + $deliveryFeeCents + $insuranceCents;

            $grandTotal = $grandTotalCents / 100;

            $validated['user_id'] = $user->id;
            $validated['total_amount'] = $grandTotal;
            $validated['is_paid'] = false;

            if($request->hasFile('proof')){
                $proofPath = $request->file('proof')->store('payment_proofs', 'public');
                $validated['proof'] = $proofPath;
            }

            $newTransaction = TransactionProduct::create($validated);

            foreach($cartItems as $item){
                TransactionDetail::create([
                    'transaction_product_id' => $newTransaction->id,
                    'product_id' => $item->product_id,
                    'price' => $item->product->price,
                ]);

                $item->delete();
            }

            DB::commit();

            return redirect()->route('transaction_products.index');

        }

        catch (\Exception $e  ) {
            //throw $th;
            DB::rollBack();
            $error = ValidationException::withMessages([
                'system_error' => ['System Error!' . $e->getMessage()],
            ]);
            throw $error;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionProduct $transactionProduct)
    {
        //
        $transactionProduct = TransactionProduct::with('transactionDetails.product')->find($transactionProduct->id);
        return view('admin.transaction_products.details', [
            'transactionProduct' => $transactionProduct
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionProduct $transactionProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionProduct $transactionProduct)
    {
        //
        $transactionProduct->update([
            'is_paid' => true
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionProduct $transactionProduct)
    {
        //
    }
}
