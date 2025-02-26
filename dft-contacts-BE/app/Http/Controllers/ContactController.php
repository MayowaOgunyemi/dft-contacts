<?php

namespace App\Http\Controllers;

use App\Data\ContactData;
use Illuminate\Http\Request;
use App\Interfaces\ContactServiceInterface;
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
    public function index()
    {
        $contacts = $this->contactService->getAllContacts();
        return ContactData::collection($contacts->item());
    }

        
    /**
     * Method store
     * Description: Create a new contact
     *
     * @param ContactData $contactData [explicite description]
     * @return ContactData
     */
    public function store(Request $request)
    {
        $contactData = ContactData::from($request->all());
        $contact = $this->contactService->createContact($contactData);
        return ContactData::from($contact);
    }
    
    /**
     * Method show
     * Description: Display the specified resource.
     *
     * @param string $id [explicite description]
     * @return ContactData
     */
    public function show(int $id)
    {
        $contact = $this->contactService->getContactById($id);
        return ContactData::from($contact);
    }
    
    /**
     * Method update
     * Description: Update the specified resource in storage.
     *
     * @param int $id [explicite description]
     * @param ContactData $contactData [explicite description]
     *
     * @return ContactData
     */
    public function update(int $id, ContactData $contactData)
    {
        $contact = $this->contactService->updateContact($id, $contactData);
        return ContactData::from($contact);
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
        $contact = $this->contactService->deleteContact($id);
        return response()->json([
            'message' => 'Contact deleted successfully.'
        ], 204);
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
        $term = $request->query('term'); // Get the term from query params
        $contacts = $this->contactService->searchContacts(['term' => $term]);
        return ContactData::collection($contacts);
    }
}
