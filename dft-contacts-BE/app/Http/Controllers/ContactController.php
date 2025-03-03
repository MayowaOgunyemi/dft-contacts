<?php

namespace App\Http\Controllers;

use Exception;
use App\Data\ContactData;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Interfaces\ContactServiceInterface;
use Spatie\LaravelData\Exceptions\CannotCreateData;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContactController
{
    private ContactServiceInterface $contactService;
    
    /**
     * Method __construct
     *
     * @param ContactServiceInterface $contactService [explicite description]
     *
     * @return void
     */
    public function __construct(ContactServiceInterface $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Method index
     * Description: Get all cocntacts
     *
     * @return AnonymousResourceCollection
     */
    public function index():JsonResponse
    {

        try {
            $contacts = $this->contactService->getAllContacts();
            return response()->json([
                'data' => $contacts
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

        
    /**
     * Method store
     * Description: Create a new contact
     *
     * @param Request $request [explicite description]
     * @return ContactData
     */
    public function store(Request $request): JsonResponse
    {
        $contactData = ContactData::from($request->all());
        try {
            $contact = $this->contactService->createContact($contactData);
            return response()->json(ContactData::from($contact), 201); // 201 Created
        } catch (Exception $e) {
            // Handle the specific exception thrown by Spatie Data
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
    /**
     * Method show
     * Description: Display the specified resource.
     *
     * @param int $id [explicite description]
     * @return ContactData
     */
    public function show(int $id): JsonResponse
    {
        try {
            $contact = $this->contactService->findContactById($id);
            return response()->json(ContactData::from($contact));
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
    
    /**
     * Method update
     * Description: Update the specified resource in storage.
     *
     * @param int $id [explicite description]
     * @param Request $request [explicite description]
     *
     * @return ContactData
     */
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $contactData = ContactData::from($request->all());
            $contact = $this->contactService->updateContact($id, $contactData);
            return response()->json(ContactData::from($contact));
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
    
    /**
     * Method destroy
     * Description: Remove the specified resource from storage.
     *
     * @param int $id [explicite description]
     *
     * @return void
     */
    public function destroy(int $id)
    {
        try {
            $this->contactService->deleteContact($id);
            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
    
    /**
     * Method search
     * Description: Search for contacts using a search term
     *
     * @param Request $request [explicite description]
     *
     * @return ContactData
     */
    public function search(Request $request)
    {
        // dd($request);
        $searchTerm = $request->query('query');
        // dd($searchTerm);
        try {
            $contacts = $this->contactService->searchContacts($searchTerm);
            // dd($contacts);
            return response()->json([
                'data' => $contacts
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }  
    }
}
