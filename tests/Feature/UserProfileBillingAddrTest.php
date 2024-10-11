<?php

namespace Tests\Feature;

use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Data\TestData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserProfileBillingAddrTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/user/profile/billing-address";

    public function test_update_profile_billing_addr(): void
    {
        $this->seed();
        $provinceId = DB::table("provinces")->where("name", "Ontario")->first()->id;
        $user = User::first();
        $response = $this->actingAs($user)->post($this::URI, [
            "firstname" => TestData::FISTNAME, 
            "lastname" => TestData::LASTNAME,
            "address" => TestData::ADDRESS,
            "city" => TestData::CITY,
            "province_id" => $provinceId,
            "postcode" => TestData::POSTCODE,
            "phone" => TestData::PHONE,
            ]
        );
        $response->assertStatus(200);
        $response->assertJsonPath('status', 0);
        $this->assertNotNull($user->billing_address_id);

        $addr = Address::find($user->billing_address_id);
        $this->assertEquals($addr->firstname, TestData::FISTNAME);
        $this->assertEquals($addr->lastname, TestData::LASTNAME);
        $this->assertEquals($addr->address, TestData::ADDRESS);
        $this->assertEquals($addr->city, TestData::CITY);
        $this->assertEquals($addr->province_id, $provinceId);
        $this->assertEquals($addr->postcode, TestData::POSTCODE);
        $this->assertEquals($addr->phone, TestData::PHONE);
    }

    public function test_update_profile_billing_addr_missing_params(): void
    {
        $this->seed();
        $user = User::first();
        $response = $this->actingAs($user)->post($this::URI, [
            ]
        );
        $response->assertStatus(400);
        $response->assertJsonPath('status', 1);
        $this->assertNull($user->billing_address_id);
    }

    public function test_update_profile_billing_addr_unauth(): void
    {
        $this->seed();
        $provinceId = DB::table("provinces")->where("name", "Ontario")->first()->id;
        $response = $this->post($this::URI, [
            "firstname" => TestData::FISTNAME, 
            "lastname" => TestData::LASTNAME,
            "address" => TestData::ADDRESS,
            "city" => TestData::CITY,
            "province_id" => $provinceId,
            "postcode" => TestData::POSTCODE,
            "phone" => TestData::PHONE,
            ]
        );
        $response->assertStatus(400);
        $response->assertJsonPath('status', 1);
    }
}
