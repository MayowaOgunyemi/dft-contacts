<?php

namespace App\Interfaces\Interfaces;

use App\Data\ContactData;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContactServiceInterface
{    
    /**
     * Method getAllContacts
     * Description: Get all contacts
     * @return LengthAwarePaginator
     */
    public function getAllContacts(): LengthAwarePaginator;
        
    /**
     * Method createContact
     * Description: Create a new contact
     * @param ContactData $contactData [explicite description]
     *
     * @return ContactData
     */
    public function createContact(ContactData $contactData): ContactData;
        
    /**
     * Method updateContact
     * Description: Update an existing contact
     * @param int $id [explicite description]
     * @param ContactData $contactData [explicite description]
     *
     * @return ContactData
     */
    public function updateContact(int $id, ContactData $contactData): ContactData;
        
    /**
     * Method deleteContact
     * Description: Delete an existing contact
     * @param int $id [explicite description]
     *
     * @return void
     */
    public function deleteContact(int $id): void;
        
    /**
     * Method findContactById
     * Description: Find a contact by its ID
     * @param int $id [explicite description]
     *
     * @return ContactData
     */
    public function findContactById(int $id): ?ContactData;
        
    /**
     * Method searchContacts
     * Description: Search for contacts by a given search term
     * @param string $contactData [explicite description]
     *
     * @return LengthAwarePaginator
     */
    public function searchContacts(string $contactData): LengthAwarePaginator;
}
