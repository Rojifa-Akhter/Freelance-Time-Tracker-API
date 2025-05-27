<?php
namespace App\Http\Controllers\Freelancers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json([
            'status'  => true,
            'clients' => Auth::user()->clients,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'email'          => 'required|email',
            'contact_person' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $client = Auth::user()->clients()->create($validator->validated());

        return response()->json(['status' => true, 'client' => $client], 201);
    }

    public function show($id)
    {
        $client = Auth::user()->clients()->find($id);

        if (! $client) {
            return response()->json(['status' => false, 'message' => 'Client not found'], 404);
        }

        return response()->json(['status' => true, 'client' => $client]);
    }

    public function update(Request $request, $id)
    {
        $client = Auth::user()->clients()->find($id);
        if (! $client) {
            return response()->json(['status' => false, 'message' => 'Client not found'], 404);
        }

        $client->update($request->only('name', 'email', 'contact_person'));

        return response()->json([
            'status' => true,
            'message'       => 'Updated Successfully',
            'client'        => $client]);
    }

    public function destroy($id)
    {
        $client = Auth::user()->clients()->find($id);
        if (! $client) {
            return response()->json(['status' => false, 'message' => 'Client not found'], 404);
        }

        $client->delete();
        return response()->json(['status' => true, 'message' => 'Client deleted']);
    }
}
