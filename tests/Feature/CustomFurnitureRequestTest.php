<?php

namespace Tests\Feature;

use App\Models\CustomFurnitureRequest;
use App\Models\FurnitureType;
use App\Models\User;
use App\Models\WoodType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomFurnitureRequestTest extends TestCase
{
    use RefreshDatabase;

    protected FurnitureType $furnitureType;
    protected WoodType $woodType;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->furnitureType = FurnitureType::create([
            'name' => 'Sofa',
            'slug' => 'sofa',
            'description' => 'A comfortable sofa',
            'starting_price' => 500,
            'is_active' => true,
            'display_order' => 1,
        ]);

        $this->woodType = WoodType::create([
            'name' => 'Oak',
            'slug' => 'oak',
            'short_description' => 'Strong and durable oak',
            'durability' => 'High',
            'recommended_use' => 'Indoor furniture',
            'color_texture' => 'Light brown, straight grain',
            'price_adjustment_type' => 'percentage',
            'price_adjustment_value' => 10,
            'availability' => 'in_stock',
            'is_active' => true,
            'display_order' => 1,
        ]);
    }

    protected function validPayload(array $overrides = []): array
    {
        return array_merge([
            'furniture_type_id' => $this->furnitureType->id,
            'wood_type_id' => $this->woodType->id,
            'length' => 120,
            'width' => 60,
            'height' => 75,
            'dimension_unit' => 'cm',
            'quantity' => 1,
            'finish' => 'Matte',
            'color_preference' => 'Walnut Brown',
            'design_style' => 'Modern',
            'upholstery_fabric' => 'Linen',
            'budget_min' => 1000,
            'budget_max' => 5000,
            'delivery_date' => now()->addDays(14)->toDateString(),
            'delivery_address' => '123 Main Street, Dhaka',
            'delivery_preference' => 'Home Delivery',
            'additional_notes' => 'Please use rounded edges.',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+8801234567890',
            'customer_address' => '123 Main Street, Dhaka',
            'terms_agreed' => true,
        ], $overrides);
    }

    public function test_guest_can_submit_a_valid_request(): void
    {
        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload());

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'request_number',
                'data' => [
                    'request_number',
                    'status',
                    'status_label',
                    'furniture_type',
                    'wood_type',
                    'quantity',
                    'submitted_at',
                ],
            ]);

        $requestNumber = $response->json('request_number');
        $this->assertMatchesRegularExpression('/^CFR-\d{4}-\d{6}$/', $requestNumber);

        $this->assertDatabaseHas('custom_furniture_requests', [
            'request_number' => $requestNumber,
            'user_id' => null,
            'status' => 'pending',
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertEquals('pending', $response->json('data.status'));
        $this->assertEquals('Pending Review', $response->json('data.status_label'));
        $this->assertEquals($this->furnitureType->name, $response->json('data.furniture_type'));
        $this->assertEquals($this->woodType->name, $response->json('data.wood_type'));
    }

    public function test_authenticated_user_request_is_linked_to_their_account(): void
    {
        $user = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/custom-furniture-requests', $this->validPayload());

        $response->assertStatus(201);

        $this->assertDatabaseHas('custom_furniture_requests', [
            'request_number' => $response->json('request_number'),
            'user_id' => $user->id,
        ]);
    }

    public function test_furniture_type_id_is_required_and_must_exist(): void
    {
        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload([
            'furniture_type_id' => null,
        ]));
        $response->assertStatus(422)->assertJsonValidationErrors('furniture_type_id');

        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload([
            'furniture_type_id' => 999999,
        ]));
        $response->assertStatus(422)->assertJsonValidationErrors('furniture_type_id');

        $this->assertDatabaseCount('custom_furniture_requests', 0);
    }

    public function test_wood_type_id_is_required_and_must_exist(): void
    {
        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload([
            'wood_type_id' => null,
        ]));
        $response->assertStatus(422)->assertJsonValidationErrors('wood_type_id');

        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload([
            'wood_type_id' => 999999,
        ]));
        $response->assertStatus(422)->assertJsonValidationErrors('wood_type_id');

        $this->assertDatabaseCount('custom_furniture_requests', 0);
    }

    public function test_inactive_furniture_type_is_rejected(): void
    {
        $inactive = FurnitureType::create([
            'name' => 'Discontinued Chair',
            'slug' => 'discontinued-chair',
            'is_active' => false,
            'display_order' => 2,
        ]);

        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload([
            'furniture_type_id' => $inactive->id,
        ]));

        $response->assertStatus(422)
            ->assertJsonMissingValidationErrors('furniture_type_id')
            ->assertJsonStructure(['message'])
            ->assertJsonMissing(['errors']);

        $this->assertDatabaseCount('custom_furniture_requests', 0);
    }

    public function test_unavailable_wood_type_is_rejected(): void
    {
        $outOfStock = WoodType::create([
            'name' => 'Rare Ebony',
            'slug' => 'rare-ebony',
            'availability' => 'out_of_stock',
            'is_active' => true,
            'display_order' => 2,
        ]);

        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload([
            'wood_type_id' => $outOfStock->id,
        ]));

        $response->assertStatus(422)
            ->assertJsonStructure(['message'])
            ->assertJsonMissing(['errors']);

        $inactive = WoodType::create([
            'name' => 'Retired Pine',
            'slug' => 'retired-pine',
            'availability' => 'in_stock',
            'is_active' => false,
            'display_order' => 3,
        ]);

        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload([
            'wood_type_id' => $inactive->id,
        ]));

        $response->assertStatus(422)
            ->assertJsonStructure(['message'])
            ->assertJsonMissing(['errors']);

        $this->assertDatabaseCount('custom_furniture_requests', 0);
    }

    public function test_required_customer_fields_are_validated(): void
    {
        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload([
            'name' => '',
            'email' => 'not-an-email',
            'phone' => '',
            'terms_agreed' => false,
        ]));

        $response->assertStatus(422)->assertJsonValidationErrors(['name', 'email', 'phone', 'terms_agreed']);

        $this->assertDatabaseCount('custom_furniture_requests', 0);
    }

    public function test_dimensions_must_be_positive_numbers(): void
    {
        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload([
            'length' => 0,
            'width' => -5,
            'height' => 'not-a-number',
        ]));

        $response->assertStatus(422)->assertJsonValidationErrors(['length', 'width', 'height']);

        $this->assertDatabaseCount('custom_furniture_requests', 0);
    }

    public function test_quantity_must_be_a_positive_integer(): void
    {
        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload([
            'quantity' => 0,
        ]));
        $response->assertStatus(422)->assertJsonValidationErrors('quantity');

        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload([
            'quantity' => 101,
        ]));
        $response->assertStatus(422)->assertJsonValidationErrors('quantity');

        $this->assertDatabaseCount('custom_furniture_requests', 0);
    }

    public function test_dimension_unit_must_be_a_valid_option(): void
    {
        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload([
            'dimension_unit' => 'meters',
        ]));

        $response->assertStatus(422)->assertJsonValidationErrors('dimension_unit');
        $this->assertDatabaseCount('custom_furniture_requests', 0);
    }

    public function test_budget_max_must_be_greater_than_or_equal_to_budget_min(): void
    {
        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload([
            'budget_min' => 5000,
            'budget_max' => 1000,
        ]));

        $response->assertStatus(422)->assertJsonValidationErrors('budget_max');
        $this->assertDatabaseCount('custom_furniture_requests', 0);
    }

    public function test_delivery_date_cannot_be_in_the_past(): void
    {
        $response = $this->postJson('/api/custom-furniture-requests', $this->validPayload([
            'delivery_date' => now()->subDay()->toDateString(),
        ]));

        $response->assertStatus(422)->assertJsonValidationErrors('delivery_date');
        $this->assertDatabaseCount('custom_furniture_requests', 0);
    }

    public function test_file_with_invalid_mime_type_is_rejected(): void
    {
        $response = $this->post('/api/custom-furniture-requests', array_merge($this->validPayload(), [
            'files' => [UploadedFile::fake()->create('notes.txt', 10, 'text/plain')],
        ]), ['Accept' => 'application/json']);

        $response->assertStatus(422)->assertJsonValidationErrors('files.0');
        $this->assertDatabaseCount('custom_furniture_requests', 0);
    }

    public function test_file_exceeding_size_limit_is_rejected(): void
    {
        $response = $this->post('/api/custom-furniture-requests', array_merge($this->validPayload(), [
            'files' => [UploadedFile::fake()->create('large.pdf', 6000, 'application/pdf')],
        ]), ['Accept' => 'application/json']);

        $response->assertStatus(422)->assertJsonValidationErrors('files.0');
        $this->assertDatabaseCount('custom_furniture_requests', 0);
    }

    public function test_more_than_five_files_is_rejected(): void
    {
        $files = [];
        for ($i = 0; $i < 6; $i++) {
            $files[] = UploadedFile::fake()->image("design{$i}.jpg");
        }

        $response = $this->post('/api/custom-furniture-requests', array_merge($this->validPayload(), [
            'files' => $files,
        ]), ['Accept' => 'application/json']);

        $response->assertStatus(422)->assertJsonValidationErrors('files');
        $this->assertDatabaseCount('custom_furniture_requests', 0);
    }

    public function test_uploaded_files_are_stored_and_linked_to_request(): void
    {
        $files = [
            UploadedFile::fake()->image('design1.jpg', 800, 600),
            UploadedFile::fake()->create('design2.pdf', 200, 'application/pdf'),
        ];

        $response = $this->post('/api/custom-furniture-requests', array_merge($this->validPayload(), [
            'files' => $files,
        ]), ['Accept' => 'application/json']);

        $response->assertStatus(201);

        $customRequest = CustomFurnitureRequest::where('request_number', $response->json('request_number'))->firstOrFail();

        $this->assertCount(2, $customRequest->files);

        foreach ($customRequest->files as $file) {
            Storage::disk('public')->assertExists($file->file_path);
            $this->assertEquals('customer', $file->uploaded_by);
        }
    }

    public function test_request_numbers_are_sequential(): void
    {
        $first = $this->postJson('/api/custom-furniture-requests', $this->validPayload());
        $second = $this->postJson('/api/custom-furniture-requests', $this->validPayload());

        $first->assertStatus(201);
        $second->assertStatus(201);

        $firstNumber = (int) substr($first->json('request_number'), -6);
        $secondNumber = (int) substr($second->json('request_number'), -6);

        $this->assertEquals($firstNumber + 1, $secondNumber);
    }

    public function test_rate_limiting_blocks_excessive_requests(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $response = $this->postJson('/api/custom-furniture-requests', []);
            $response->assertStatus(422);
        }

        $response = $this->postJson('/api/custom-furniture-requests', []);
        $response->assertStatus(429);
    }
}
