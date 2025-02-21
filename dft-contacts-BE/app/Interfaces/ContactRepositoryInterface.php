<?php

namespace App\Interfaces;

use App\Data\ContactData;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContactRepositoryInterface
{
    public function getAll(): LengthAwarePaginator;
    public function create(ContactData $contactData): ContactData;
    public function update(int $id, ContactData $contactData): ContactData;
    public function delete(int $id): void;
    public function findById(int $id): ?ContactData;
    public function findByEmail(string $email): ContactData;
    public function search(string $contactData): LengthAwarePaginator;
}
