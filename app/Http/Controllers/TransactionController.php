<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->user_type !== 'admin')
        {
            $transactions = Transaction::where('user_id', auth()->user()->id)->get();
            return response()->json($transactions, 200);
        }else {
            $transactions = Transaction::all();
            return response()->json($transactions, 200);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = $request->validate([
                'transaction_id' => 'required',
                'transaction_type' => 'required',
                'transaction_amount' => 'required|numeric',
                'transaction_status' => 'required|integer',
                'user_id' => 'required|integer'
            ]);
            Transaction::create($data);

            DB::commit();
            return response()->json(['success'=>true], 200);

        }catch (Throwable $th) {
            DB::rollBack();
            return response([
                'success' => false
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            if(auth()->user()->user_type !== 'admin')
                $transaction = Transaction::where('user_id', auth()->user()->id)->find($id);
            else
                $transaction = Transaction::find($id);

            return response()->json($transaction, 200);
        }catch (Throwable $th) {
            return response([
                'success' => false
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function recordPayment($id, Request $request)
    {
        DB::beginTransaction();
        try{
            $transaction = Transaction::find($id);
            Payment::create([
                'payment_id' => date('Ymd') . rand(1000, 9999),
                'payment_type' => 'Paypal',
                'payment_amount' => $transaction['transaction_amount'],
                'payment_status' => $transaction['transaction_status'],
                'payment_currency' => 'USD',
                'user_id' => $transaction['user_id'],
                'transaction_id' => $transaction['id']
            ]);
            DB::commit();
            return response()->json($transaction, 200);
        }catch (Throwable $th) {
            DB::rollBack();
            return response([
                'success' => false
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function generateReport($userId, Request $request)
    {
        $transactions = Transaction::where('user_id', $userId)->get();
        return response()->json(['transactions'=>$transactions,'count'=>$transactions->count()], 200);
    }
}
