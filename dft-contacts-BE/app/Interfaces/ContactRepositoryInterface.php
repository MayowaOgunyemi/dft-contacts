<?php

namespace App\Interfaces;

use App\Data\ContactData;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContactRepositoryInterface
{    
    /**
     * Method getAll
     * Description: Get all contacts
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;    
    /**
     * Method create
     * Description: Create a new contact
     * @param ContactData $contactData [explicite description]
     *
     * @return ContactData
     */
    public function create(ContactData $contactData): ContactData;    
    /**
     * Method update
     * Description: Update an existing contact
     * @param int $id [explicite description]
     * @param ContactData $contactData [explicite description]
     *
     * @return ContactData
     */
    public function update(int $id, ContactData $contactData): ContactData;    
    /**
     * Method delete
     * Description: Delete an existing contact
     * @param int $id [explicite description]
     *
     * @return void
     */
    public function delete(int $id): void;    
    /**
     * Method findById
     * Description: Find a contact by its ID
     * @param int $id [explicite description]
     *
     * @return ContactData
     */
    public function findById(int $id): ?ContactData;    
    /**
     * Method search
     * Description: Search for contacts by a given search term
     * @param string $contactData [explicite description]
     *
     * @return LengthAwarePaginator
     */
    public function search(string $contactData): LengthAwarePaginator;
}
