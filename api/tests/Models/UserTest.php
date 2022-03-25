<?php

namespace Tests\Models;

use App\Models\Nordigen\StaticObjects;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UserTest extends TestCase
{
    private $user;
protected function setUp(): void
{
    parent::setUp();
   $this->user = $user = [
        'lastName' => 'Joe',
        'firstName' => 'Smith',
        'email' => 'tes2dedsmail@test.com',
        'password' => 'passwordtest',
        'passwordConfirmation' => 'passwordtest'];
}


    public function testRegisterUserSuccessed()
    {
        $response = $this->json('POST','api/register', $this->user);
        $response
            ->assertStatus(201)->assertJsonStructure(["user" => [
                'lastName',
                'firstName',
                'email',
                'updated_at',
                'created_at',
                'id',
                'hasBankAuthorization',
                'hasAccountChoices'
            ], "token"]);
    }

    public function testLoginUser() {
        $user = User::find(1);
        $response = $this->json('POST','api/login', [
            'email' => $this->user['email'],
            'password' => $this->user['password'],
        ]);
        $response->assertStatus(200);
}
    public function testRegisterUserFailed()
    {
        $this->json('POST','api/register', $this->user)
            ->assertStatus(422);
    }

    public function testHaveRequisitionValidated()
    {
        $user = User::find(1);
        $idRequisition = $user->idRequisition;
        $request = StaticObjects::$nordigenAPI->getRequisitionById($idRequisition);
        if (in_array($request->getStatusCode(), [200, 201, 202])) {
            //?ref=f1cb3135-f831-4f2a-be09-22e5c803e671
            $response = json_decode($request->getBody()->getContents());
            $this->assertEquals("LN", $response->status);
        }
    }

    public function testHaveAnyLimitedBudgetAt()
    {
        $user = User::find(1);
        $limitedBudgets = $user->limitedBudgets();
        $previsionDate = Carbon::parse('2022-02');
        $this->assertTrue($limitedBudgets->whereDate("previsionDate", "=", $previsionDate)->exists());
    }
}
