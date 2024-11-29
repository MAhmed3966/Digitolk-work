<?php

namespace DTApi\Units;
use Illuminate\Foundation\Testing\RefreshDatabase;

use DTApi\Helpers\TeHelper;

class CreateOrUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateNewUserWithCustomerRole()
    {
        $request = [
            'role' => env('CUSTOMER_ROLE_ID'),
            'name' => 'John Doe',
            'company_id' => '',
            'department_id' => '',
            'email' => 'john@example.com',
            'dob_or_orgid' => '123456789',
            'phone' => '1234567890',
            'mobile' => '0987654321',
            'password' => 'password123',
            'consumer_type' => 'paid',
            'customer_type' => 'premium',
            'username' => 'johndoe',
            'post_code' => '12345',
            'address' => '123 Main St',
            'city' => 'New York',
            'town' => 'Manhattan',
            'country' => 'USA',
            'reference' => 'yes',
            'additional_info' => 'Test additional info',
            'cost_place' => '1',
            'fee' => '100',
            'time_to_charge' => '10:00',
            'time_to_pay' => '17:00',
            'charge_ob' => '5',
            'customer_id' => 'CUST001',
            'charge_km' => '10',
            'maximum_km' => '50',
        ];

        $helper = new \DTApi\Helpers\YourHelperClass();
        $model = $helper->createOrUpdate(null, $request);

        $this->assertNotNull($model);
        $this->assertInstanceOf(User::class, $model);
        $this->assertEquals('John Doe', $model->name);
        $this->assertEquals(env('CUSTOMER_ROLE_ID'), $model->user_type);

        // Assert related data creation
        $this->assertDatabaseHas('companies', ['name' => 'John Doe']);
        $this->assertDatabaseHas('departments', ['name' => 'John Doe']);
        $this->assertDatabaseHas('user_meta', ['user_id' => $model->id, 'consumer_type' => 'paid']);
    }

    public function testUpdateExistingUserWithTranslatorRole()
    {
        $existingUser = User::factory()->create(['user_type' => env('TRANSLATOR_ROLE_ID')]);
        $request = [
            'role' => env('TRANSLATOR_ROLE_ID'),
            'name' => 'Jane Doe',
            'company_id' => '1',
            'department_id' => '1',
            'email' => 'jane@example.com',
            'dob_or_orgid' => '987654321',
            'phone' => '1111111111',
            'mobile' => '2222222222',
            'translator_type' => 'freelance',
            'worked_for' => 'yes',
            'organization_number' => '123456',
            'gender' => 'female',
            'translator_level' => 'expert',
            'additional_info' => 'Updated info',
            'post_code' => '54321',
            'address' => '456 Elm St',
            'address_2' => 'Apt 2',
            'town' => 'Brooklyn',
        ];

        $helper = new \DTApi\Helpers\YourHelperClass();
        $model = $helper->createOrUpdate($existingUser->id, $request);

        $this->assertNotNull($model);
        $this->assertInstanceOf(User::class, $model);
        $this->assertEquals('Jane Doe', $model->name);
        $this->assertEquals('freelance', $model->userMeta->translator_type);

        // Assert related data updates
        $this->assertDatabaseHas('user_meta', ['user_id' => $model->id, 'translator_type' => 'freelance']);
    }

    public function testHandleBlacklistUpdates()
    {
        $existingUser = User::factory()->create(['user_type' => env('CUSTOMER_ROLE_ID')]);
        $translator = User::factory()->create(['user_type' => env('TRANSLATOR_ROLE_ID')]);

        $request = [
            'role' => env('CUSTOMER_ROLE_ID'),
            'translator_ex' => [$translator->id],
        ];

        $helper = new \DTApi\Helpers\YourHelperClass();
        $helper->createOrUpdate($existingUser->id, $request);

        $this->assertDatabaseHas('users_blacklist', [
            'user_id' => $existingUser->id,
            'translator_id' => $translator->id,
        ]);
    }
}