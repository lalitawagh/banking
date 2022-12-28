<?php

namespace Kanexy\Banking\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kanexy\Banking\Policies\TransactionPolicy;
use Kanexy\Cms\Controllers\Controller;
use Kanexy\PartnerFoundation\Core\Models\Transaction;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class TransactionController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize(TransactionPolicy::INDEX, Transaction::class);

        $workspace = null;

        if ($request->has('filter.workspace_id')) {
            $workspace = Workspace::findOrFail($request->input('filter.workspace_id'));
        }

        return view("banking::banking.transactions", compact('workspace'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize(TransactionPolicy::EDIT, $transaction);

        $data['note'] = $request->input('note');

        $transaction->update($data);

        if (!empty($request->has('attachment'))) {

            $validator = Validator::make($request->all(), [
                'attachment' => 'required|max:5000|mimes:png,jpg,jpeg|file'
            ], [
                'attachment.max' => 'The attachment may not be greater than 5 MB',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with(['message' => 'Transaction is not updated. ' . $validator->errors(), 'status' => 'failed']);
            }
            $transaction->update(['attachment' => $request->file('attachment')->store('Images', 'azure')]);
        }

        return redirect()->back()->with(['message' => 'Transaction updated successfully.', 'status' => 'success']);
    }
}
