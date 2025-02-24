<?php

namespace App\Services;

use Exception;
use App\Data\ContactData;
use App\Interfaces\ContactServiceInterface;
use App\Interfaces\ContactRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactService implements ContactServiceInterface
{
    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }
    
    /**
     * Method getAllContacts
     * Description: Get all contacts
     *
     * @return LengthAwarePaginator
     */
    public function getAllContacts(): LengthAwarePaginator
    {
        return $this->contactRepository->getAll();
    }

    /**
     * Method createContact
     * Description: Create a new contact
     *
     * @param ContactData $contactData [explicite description]
     *
     * @return ContactData
     * @throws Exception
     */
    public function createContact(ContactData $contactData): ContactData
    {
        //check if the contact already exists
        $existingContact = $this->contactRepository->findByEmail($contactData->email);
        if ($existingContact) {
            throw new \Exception("Contact with email: {$contactData->email} already exists");
        }
        //create a new contact
        return $this->contactRepository->create($contactData);
    }

    /**
     * Method updateContact
     * Description: Update an existing contact
     *
     * @param int $id [explicite description]
     * @param ContactData $contactData [explicite description]
     *
     * @return ContactData
     * @throws Exception
     */ 
    public function updateContact(int $id, ContactData $contactData): ContactData
    {
        //check if the contact exists
        $existingContact = $this->contactRepository->findById($id);
        if (!$existingContact) {
            throw new \Exception("Contact with ID: {$id} not found");
        }
        //update the contact
        return $this->contactRepository->update($id, $contactData);
    }

    /**
     * Method deleteContact
     * Description: Delete an existing contact
     *
     * @param int $id [explicite description]
     *
     * @return void
     */
    public function deleteContact(int $id): void
    {
        //delete the contact
        $this->contactRepository->delete($id);
    }

    /**
     * Method findContactById
     * Description: Find a contact by its ID
     *
     * @param int $id [explicite description]
     *
     * @return ContactData
     */
    public function findContactById(int $id): ?ContactData
    {
        return $this->contactRepository->findById($id);
    }

    /**
     * Method searchContacts
     * Description: Search for contacts by a given search term
     *
     * @param string $contactData [explicite description]
     *
     * @return LengthAwarePaginator
     */
    public function searchContacts(string $contactData): LengthAwarePaginator
    {
        return $this->contactRepository->search($contactData);
    }
}
