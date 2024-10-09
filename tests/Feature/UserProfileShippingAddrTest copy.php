<?php

namespace Tests\Feature;

use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Data\TestData;
use App\Models\User;
use Database\Seeders\ProvinceSeeder;

class UserProfileShippingAddrTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/user/profile/shipping-address";

    public function test_update_profile_shipping_addr(): void
    {
        $this->seed();
        $this->seed(ProvinceSeeder::class);
        $user = User::first();
        $response = $this->actingAs($user)->post($this::URI, [
            "firstname" => TestData::FISTNAME, 
            "lastname" => TestData::LASTNAME,
            "address" => TestData::ADDRESS,
            "city" => TestData::CITY,
            "province_id" => TestData::PROVINCE_ID,
            "postcode" => TestData::POSTCODE,
            "phone" => TestData::PHONE,
            ]
        );
        $response->assertStatus(200);
        $response->assertJsonPath('status', 0);
        $this->assertNotNull($user->shipping_address_id);

        $addr = Address::find($user->shipping_address_id);
        $this->assertEquals($addr->firstname, TestData::FISTNAME);
        $this->assertEquals($addr->lastname, TestData::LASTNAME);
        $this->assertEquals($addr->address, TestData::ADDRESS);
        $this->assertEquals($addr->city, TestData::CITY);
        $this->assertEquals($addr->province_id, TestData::PROVINCE_ID);
        $this->assertEquals($addr->postcode, TestData::POSTCODE);
        $this->assertEquals($addr->phone, TestData::PHONE);
    }

    public function test_update_profile_shipping_addr_missing_params(): void
    {
        $this->seed();
        $this->seed(ProvinceSeeder::class);
        $user = User::first();
        $response = $this->actingAs($user)->post($this::URI, [
            ]
        );
        $response->assertStatus(400);
        $response->assertJsonPath('status', 1);
        $this->assertNull($user->shipping_address_id);
    }

    public function test_update_profile_shipping_addr_unauth(): void
    {
        $this->seed();
        $this->seed(ProvinceSeeder::class);
        $response = $this->post($this::URI, [
            "firstname" => TestData::FISTNAME, 
            "lastname" => TestData::LASTNAME,
            "address" => TestData::ADDRESS,
            "city" => TestData::CITY,
            "province_id" => TestData::PROVINCE_ID,
            "postcode" => TestData::POSTCODE,
            "phone" => TestData::PHONE,
            ]
        );
        $response->assertStatus(400);
        $response->assertJsonPath('status', 1);
    }
}
