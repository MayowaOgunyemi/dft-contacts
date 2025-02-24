<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Interfaces\ContactRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactRepository implements ContactRepositoryInterface
{    
    /**
     * Method getAll
     * Description: Get all contacts
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        $contacts = Contact::orderBy('id', 'desc')->paginate();
        return $contacts;
    }

    /**
     * Method create
     * Description: Create a new contact
     *
     * @param ContactData $contactData [explicite description]
     *
     * @return ContactData
     */
    public function create(ContactData $contactData): ContactData
    {
        $contact = Contact::create($contactData->toArray());
        return ContactData::from($contact);
    }

    /**
     * Method update
     * Description: Update an existing contact
     *
     * @param int $id [explicite description]
     * @param ContactData $contactData [explicite description]
     *
     * @return ContactData
     */
    public function update(int $id, ContactData $contactData): ContactData
    {
        $contact = Contact::findOrFail($id);
        $contact->update($contactData->toArray());
        return ContactData::from($contact);
    }

    /**
     * Method delete
     * Description: Delete an existing contact
     *
     * @param int $id [explicite description]
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
    }

    /**
     * Method findById
     * Description: Find a contact by its ID
     *
     * @param int $id [explicite description]
     *
     * @return ContactData
     */
    public function findById(int $id): ?ContactData
    {
        $contact = Contact::find($id);
        return $contact ? ContactData::from($contact) : null;
    }
    
    /**
     * Method findByEmail
     * Description: Find a contact by its email
     *
     * @param string $email [explicite description]
     *
     * @return ContactData
     */
    public function findByEmail(string $email): ?ContactData
    {
        $contact = Contact::where('email', $email)->first();
        return $contact ? ContactData::from($contact) : null;
    }

    /**
     * Method search
     * Description: Search for contacts by a given search term
     *
     * @param string $contactData [explicite description]
     *
     * @return LengthAwarePaginator
     */
    public function search(string $contactData): LengthAwarePaginator
    {
        return Contact::where('name', 'like', "%$contactData%")
            ->orWhere('email', 'like', "%$contactData%")
            ->orderBy('id', 'desc')
            ->paginate();
    }
}
