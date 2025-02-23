<?php

namespace Tests\Unit;

use Mockery;
use App\Models\Contact;
use App\Data\ContactData;
use PHPUnit\Framework\TestCase;
use App\Services\ContactService;
use App\Interfaces\ContactRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactServiceTest extends TestCase
{
    protected $mockRepository;
    protected $contactService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the repository interface
        $this->mockRepository = Mockery::mock(ContactRepositoryInterface::class);
        $this->contactService = new ContactService($this->mockRepository);
    }

    /**
     * Method test_can_get_all_contacts
     * Description: ✅ Returns a paginated list of all contacts.
     * @return void
     */
    #[Test]
    public function test_can_get_all_contacts(): void
    {
        // Arrange
        $contacts = Contact::factory()->count(5)->make();
        $this->mockRepository
            ->shouldReceive('getAll')
            ->once()
            ->andReturn(new LengthAwarePaginator($contacts, 5, 15));
        // Act
        $response = $this->contactService->getAllContacts();

        // Assert
        $this->assertCount(5, $response);
    }

    /**
     * Method test_can_create_contact
     * Description: ✅ Successfully creates a new contact using ContactData.
     * @return void
     */
    #[Test]
    public function test_can_create_contact(): void
    {
        // Arrange
        $contact = Contact::factory()->make();
        $contactData = ContactData::from($contact);
        $this->mockRepository
            ->shouldReceive('create')
            ->once()
            ->with($contactData)
            ->andReturn($contactData);
        // Act
        $response = $this->contactService->createContact($contactData);

        // Assert
        $this->assertEquals($contactData, $response);
    }

    /**
     * Method test_can_update_contact
     * Description: ✅ Successfully updates an existing contact using ContactData.
     * @return void
     */
    #[Test]
    public function test_can_update_contact(): void
    {
        // Arrange
        $contact = Contact::factory()->make(['id' => 1]);
        $updatedData = ContactData::from($contact);
        $this->mockRepository
            ->shouldReceive('update')
            ->once()
            ->with($contact->id, $updatedData)
            ->andReturn($updatedData);
        // Act
        $response = $this->contactService->updateContact($contact->id, $updatedData);

        // Assert
        $this->assertEquals($updatedData, $response);
    }

    /**
     * Method test_can_delete_contact
     * Description: ✅ Successfully deletes an existing contact by ID.
     * @return void
     */
    #[Test]
    public function test_can_delete_contact(): void
    {
        // Arrange
        $contact = Contact::factory()->make(['id' => 1]);
        $this->mockRepository
            ->shouldReceive('delete')
            ->once()
            ->with($contact->id);
        // Act
        $this->contactService->deleteContact($contact->id);

        // Assert
        $this->assertTrue(true);
    }

    /**
     * Method test_can_find_contact_by_id
     * Description: ✅ Returns ContactData for a valid contact ID.
     * @return void
     */
    #[Test]
    public function test_can_find_contact_by_id(): void
    {
        // Arrange
        $contact = Contact::factory()->make(['id' => 1]);
        $contactData = ContactData::from($contact);
        $this->mockRepository
            ->shouldReceive('findById')
            ->once()
            ->with($contact->id)
            ->andReturn($contactData);
        // Act
        $response = $this->contactService->findContactById($contact->id);

        // Assert
        $this->assertEquals($contactData, $response);
    }

    /**
     * Method test_can_search_contacts
     * Description: ✅ Returns a paginated list of contacts matching a search term.
     * @return void
     */
    #[Test]
    public function test_can_search_contacts(): void
    {
        // Arrange
        $searchTerm = 'John';
        $contacts = Contact::factory()->count(5)->make(['fname' => $searchTerm]);
        $this->mockRepository
            ->shouldReceive('search')
            ->once()
            ->with($searchTerm)
            ->andReturn(new LengthAwarePaginator($contacts, 5, 15));
        // Act
        $response = $this->contactService->searchContacts($searchTerm);

        // Assert
        $this->assertCount(5, $response);
    }

    /**
     * Method test_returns_empty_paginator_when_no_contacts_exist
     * 
     * Description: ❌ Returns an empty paginator when no contacts exist.
     * @return void
     */
    #[Test]
    public function test_returns_empty_paginator_when_no_contacts_exist(): void
    {
        // Arrange
        $this->mockRepository
            ->shouldReceive('getAll')
            ->once()
            ->andReturn(new LengthAwarePaginator([], 0, 15));
        // Act
        $response = $this->contactService->getAllContacts();

        // Assert
        $this->assertCount(0, $response);
    }

    /**
     * Method test_fails_when_required_fields_are_missing
     * 
     * Description: ❌ Fails when required fields in ContactData are missing.
     * @return void
     */
    #[Test]
    public function test_fails_when_required_fields_are_missing(): void
    {
        // Arrange
        $contactData = ContactData::from([]);
        $this->mockRepository
            ->shouldReceive('create')
            ->once()
            ->with($contactData)
            ->andThrow(new \InvalidArgumentException('Required fields are missing'));

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->contactService->createContact($contactData);
    }

    /**
     * Method test_fails_to_update_when_contact_id_does_not_exist
     * 
     * Description: ❌ Fails when contact ID does not exist.
     * @return void
     */
    #[Test]
    public function test_fails_to_update_when_contact_id_does_not_exist(): void
    {
        // Arrange
        $contact = Contact::factory()->make();
        $contactData = ContactData::from($contact);
        $nonExistentId = 999;

        $this->mockRepository
            ->shouldReceive('update')
            ->once()
            ->with($nonExistentId, $contactData)
            ->andThrow(new \InvalidArgumentException('Contact not found'));

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->contactService->updateContact($nonExistentId, $contactData);
    }


    /**
     * Method test_fails_when_invalid_data_is_provided_in_contact_data
     * 
     * Description: ❌ Fails when invalid data is provided in ContactData.
     * @return void
     */
    #[Test]
    public function test_fails_when_invalid_data_is_provided_in_contact_data(): void
    {
        // Arrange
        $contact = Contact::factory()->make();
        $invalidData = ContactData::from([]);
        $this->mockRepository
            ->shouldReceive('update')
            ->once()
            ->with($contact->id, $invalidData)
            ->andThrow(new \InvalidArgumentException('Invalid data provided'));

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->contactService->updateContact($invalidData);
    }

    /**
     * Method test_fails_to_delete_when_contact_id_does_not_exist
     * 
     * Description: ❌ Fails when contact ID does not exist.
     * @return void
     */
    #[Test] 
    public function test_fails_to_delete_when_contact_id_does_not_exist(): void
    {
        // Arrange
        $nonExistentId = 999;
        $this->mockRepository
            ->shouldReceive('delete')
            ->once()
            ->with($nonExistentId)
            ->andThrow(new \InvalidArgumentException('Contact not found'));

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->contactService->deleteContact($nonExistentId);
    }

    /**
     * Method test_returns_null_when_contact_id_does_not_exist
     * 
     * Description: ❌ Returns null when contact ID does not exist.
     * @return void
     */
    #[Test]
    public function test_returns_null_when_contact_id_does_not_exist(): void
    {   
        // Arrange
        $nonExistentId = 999;
        $this->mockRepository
            ->shouldReceive('findById')
            ->once()
            ->with($nonExistentId)
            ->andReturn(null);

        // Act
        $response = $this->contactService->findContactById($nonExistentId);

        // Assert
        $this->assertNull($response);
    }

    /**
     * Method test_returns_empty_paginator_when_no_contacts_match_search_term
     * 
     * Description: ❌ Returns an empty paginator when no contacts match the search term.
     * @return void
     */
    #[Test]
    public function test_returns_empty_paginator_when_no_contacts_match_search_term(): void
    {
        // Arrange
        $searchTerm = 'NonExistentName';
        $this->mockRepository
            ->shouldReceive('search')
            ->once()
            ->with($searchTerm)
            ->andReturn(new LengthAwarePaginator([], 0, 15));

        // Act
        $response = $this->contactService->searchContacts($searchTerm); 

        // Assert
        $this->assertCount(0, $response);
    }


    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
