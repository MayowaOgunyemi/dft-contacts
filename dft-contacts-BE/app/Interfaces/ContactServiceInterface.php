<?php

namespace App\Interfaces\Interfaces;

use App\Data\ContactData;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContactServiceInterface
{
    public function getAllContacts(): LengthAwarePaginator;
    public function createContact(ContactData $contactData): ContactData;
    public function updateContact(int $id, ContactData $contactData): ContactData;
    public function deleteContact(int $id): void;
    public function findContactById(int $id): ?ContactData;
    public function searchContacts(string $contactData): LengthAwarePaginator;
}
