<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\Contact;
use App\Data\ContactData;
use App\Interfaces\ContactServiceInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactControllerTest extends TestCase
{
    use WithFaker;

    protected $mockService;
    
    /**
     * Method setUp
     * Description: Set up the test
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // $this->withoutMiddleware();

        // Mock the service interface
        $this->mockService = Mockery::mock(ContactServiceInterface::class);
        $this->app->instance(ContactServiceInterface::class, $this->mockService);
    }

    /**
     * Method test_can_get_all_contacts
     * 
     * Description: Retrieve all contacts (paginated)
     * @return LengthAwarePaginator
     */
    #[Test]
    public function test_can_get_all_contacts(): void
    {
        //Arrange
        $contacts = Contact::factory()->count(5)->make();
        $this->mockService
            ->shouldReceive('getAllContacts')
            ->once()
            ->andReturn(new LengthAwarePaginator($contacts, 5, 15));

        //Act
        $response = $this->getJson('/api/contacts');

        //Assert
        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data'); // Ensure the response contains 5 contacts
    }

    /**
     * Method test_can_create_contact
     * 
     * Description: Create a new contact successfully
     * @return void
     */
    #[Test]
    public function test_can_create_contact(): void
    {
        //Arrange
        $contact = Contact::factory()->make();
        $contactData = ContactData::from($contact);
        $data= $contactData->toArray();
        $this->mockService
            ->shouldReceive('createContact')
            ->once()
            ->with($data)
            ->andReturn($contactData);

        //Act
        $response = $this->postJson('/api/contacts', $data);

        //Assert
        $response->assertStatus(201);
    }

    /**
     * Method test_can_update_contact
     * 
     * Description: Update an existing contact successfully
     * @return void
     */
    #[Test]
    public function test_can_update_contact(): void
    {
        //Arrange
        $contact = Contact::factory()->make();
        $updatedData = ContactData::from($contact);
        $data= $updatedData->toArray();
        $this->mockService
            ->shouldReceive('updateContact')
            ->once()
            ->with($contact->id, $data)
            ->andReturn($updatedData);

        //Act
        $response = $this->putJson("/api/contacts/{$contact->id}", $data);

        //Assert
        $response->assertStatus(200);
    }

    /**
     * Method test_can_delete_contact
     * 
     * Description: Delete an existing contact successfully
     * @return void
     */
    #[Test]
    public function test_can_delete_contact(): void
    {
        //Arrange
        $contact = Contact::factory()->make(['id' => 1]);
        $this->mockService
            ->shouldReceive('deleteContact')
            ->once()
            ->with($contact->id);

        //Act
        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        //Assert
        $response->assertStatus(204);
    }

    /**
     * Method test_can_find_contact_by_id
     * 
     * Description: Find a contact by its ID
     * @return void
     */
    #[Test]
    public function test_can_find_contact_by_id(): void
    {
        //Arrange
        $contact = Contact::factory()->make(['id' => 1]);
        $this->mockService
            ->shouldReceive('findContactById')
            ->once()
            ->with($contact->id)
            ->andReturn($contact);

        //Act
        $response = $this->getJson("/api/contacts/{$contact->id}");

        //Assert
        $response->assertStatus(200);
    }

    /**
     * Method test_can_search_contacts
     * 
     * Description: Search for contacts by a given search term
     * @return void
     */
    #[Test]
    public function test_can_search_contacts(): void
    {
        //Arrange
        $searchTerm = 'John';
        $contacts = Contact::factory()->count(5)->make(['fname' => $searchTerm]);
        $this->mockService
            ->shouldReceive('searchContacts')
            ->once()
            ->with($searchTerm)
            ->andReturn($contacts);

        //Act
        $response = $this->getJson("/api/contacts/search?search={$searchTerm}");

        //Assert
        $response->assertStatus(200);
    }

    /**
     * Method test_handle_empty_contact_list
     * 
     * Description: ❌ Handle empty contact list.
     * @return void
     */
    #[Test]
    public function test_handle_empty_contact_list(): void
    {
        //Arrange
        $this->mockService
            ->shouldReceive('getAllContacts')
            ->once()
            ->andReturn([]);

        //Act
        $response = $this->getJson('/api/contacts');

        //Assert
        $response->assertStatus(404);
    }

    /**
     * Method test_
     * 
     * Description: ❌ Create with missing fields.
     * @return void
     */
    #[Test]
    public function test_create_with_missing_fields(): void
    {
        //Arrange
        $contact = Contact::factory()->make();
        $contactData = ContactData::from($contact);
        $data= $contactData->toArray();
        unset($data['fname']);
        $this->mockService
            ->shouldReceive('createContact')
            ->once()
            ->with($data)
            ->andReturn($contact);

        //Act
        $response = $this->postJson('/api/contacts', $data);

        //Assert
        $response->assertStatus(422);
    }

    /**
     * Method test_update_a_non_existent_contact
     * 
     * Description: ❌ Update a non-existent contact.
     * @return void
     */
    #[Test]
    public function test_update_a_non_existent_contact(): void
    {
        //Arrange
        $contact = Contact::factory()->make();
        $updatedData = ContactData::from($contact);
        $data= $updatedData->toArray();
        $this->mockService
            ->shouldReceive('updateContact')
            ->once()
            ->with($contact->id, $data)
            ->andReturn(null);

        //Act
        $response = $this->putJson("/api/contacts/{$contact->id}", $data);

        //Assert
        $response->assertStatus(404);
    }

    /**
     * Method test_delete_a_non_existent_contact
     * 
     * Description: ❌ Delete a non-existent contact.
     * @return void
     */
    #[Test]
    public function test_delete_a_non_existent_contact(): void
    {
        //Arrange
        $nonExistentId = 999;
        // $contact = Contact::factory()->make(['id' => 1]);
        $this->mockService
            ->shouldReceive('deleteContact')
            ->once()
            ->with($nonExistentId);

        //Act
        $response = $this->deleteJson("/api/contacts/{$nonExistentId}");

        //Assert
        $response->assertStatus(404);
    }

    /**
     * Method test_find_a_non_existent_contact
     * 
     * Description: ❌ Find a non-existent contact.
     * @return void
     */
    #[Test]
    public function test_find_a_non_existent_contact(): void
    {
        //Arrange
        $contact = Contact::factory()->make();
        $this->mockService
            ->shouldReceive('findContactById')
            ->once()
            ->with($contact->id)
            ->andReturn(null);

        //Act
        $response = $this->getJson("/api/contacts/{$contact->id}");

        //Assert
        $response->assertStatus(404);
    }

    /**
     * Method test_search_with_empty_result
     * 
     * Description: ❌ Search with empty result.
     * @return void
     */
    #[Test]
    public function test_search_with_empty_result(): void
    {
        //Arrange
        $searchTerm = 'John';
        $this->mockService
            ->shouldReceive('searchContacts')
            ->once()
            ->with($searchTerm)
            ->andReturn([]);

        //Act
        $response = $this->getJson("/api/contacts/search?search={$searchTerm}");

        //Assert
        $response->assertStatus(404);
    }  
    
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

}
