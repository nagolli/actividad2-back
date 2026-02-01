<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientUser as ClientUserModel;
use App\Models\AppUser as AppUserModel;
use App\Http\Resources\ClientUserResource;
use App\Http\Resources\ClientUserListResource;

class ClientUser extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $clients = ClientUserModel::with('appUser')->get();
            return ClientUserListResource::collection($clients);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching clients'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|unique:appUsers|max:64',
                'name' => 'required|string|max:64',
                'surname' => 'required|string|max:128',
                'phone' => 'required|string|max:32',
                'password' => 'required|string|min:8',
                'addresses' => 'nullable|array',
                'addresses.*.addressId' => 'required|exists:addresses,id',
                'addresses.*.name' => 'required|string|max:128'
            ]);
            //Primero crear el appUser al que va a vincular el clientUser
            $guest = AppUserModel::create($validated);

            if (isset($validated['addresses'])) {
                foreach ($validated['addresses'] as $address) {
                    $guest->addresses()->attach($address['addressId'], ['name' => $address['name']]);
                }
            }
            //Luego inicilaizar el clientUser vinculado al appUser creado
            $client = ClientUserModel::create([
                'appUserId' => $guest->id,
                'password' => Hash::make($request->password),
                'points' => 0,
                'level' => 1
            ]);

            return new ClientUserResource($client->load('appUser.addresses'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating client'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $client = ClientUserModel::with('appUser.addresses')->findOrFail($id);
            return new ClientUserResource($client);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Client not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {


            $appUser = AppUserModel::findOrFail($id);
            $client = ClientUserModel::where('appUserId', $appUser->id)->firstOrFail();

            $validated = $request->validate([
                'email' => 'sometimes|email|unique:appUsers,email,' . $client->appUserId,
                'name' => 'sometimes|string|max:64',
                'surname' => 'sometimes|string|max:128',
                'phone' => 'sometimes|string|max:32',
                'addresses' => 'sometimes|array',
                'points' => 'sometimes|integer|min:0',
                'password' => 'sometimes|string|min:8',
                'addresses.*.addressId' => 'required|exists:addresses,id',
                'addresses.*.name' => 'required|string|max:128',
            ]);

            $appUser->update(
                collect($validated)->only([
                    'email',
                    'name',
                    'surname',
                    'phone'
                ])->toArray()
            );

            $client->update(
                collect($validated)->only([
                    'points',
                    'password'
                ])->toArray()
            );

            if (array_key_exists('addresses', $validated)) {
                $syncAddresses = [];

                foreach ($validated['addresses'] as $address) {
                    $syncAddresses[$address['addressId']] = [
                        'name' => $address['name']
                    ];
                }

                $appUser->addresses()->sync($syncAddresses);
            }
            return new ClientUserResource($client->load('appUser.addresses'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating client'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $appUser = AppUserModel::findOrFail($id);
            $client = ClientUserModel::where('appUserId', $appUser->id)->firstOrFail();

            //Caso 1, el cliente no es tambien empleado -> Borra en cadena appUser y desvincula direcc
            if (!$client->appUser->employeeUser) {
                $appUser->addresses()->detach();
                $appUser->delete();
            }
            //Caso 2, el cliente es tambien empleado, solo elimina este cliente
            $client->delete();
            return response()->json(['message' => 'Client deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting client'], 500);
        }
    }
}
