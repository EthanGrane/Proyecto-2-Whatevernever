<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Friend;
use App\Models\FriendGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);
    }

    public function test_user_sent_and_received_friend_requests()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $friend = Friend::factory()->create([
            'sender_user_id' => $sender->id,
            'reciver_user_id' => $receiver->id,
        ]);

        $this->assertCount(1, $sender->sentFriendRequests);
        $this->assertTrue($sender->sentFriendRequests->contains($friend));

        $this->assertCount(1, $receiver->receivedFriendRequests);
        $this->assertTrue($receiver->receivedFriendRequests->contains($friend));
    }

    public function test_user_friends_sent_and_received_with_relations()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $friend = Friend::factory()->create([
            'sender_user_id' => $sender->id,
            'reciver_user_id' => $receiver->id,
        ]);

        $this->assertEquals($receiver->id, $sender->friendsSent->first()->reciver->id);
        $this->assertEquals($sender->id, $receiver->friendsReceived->first()->sender->id);
    }

    public function test_user_can_have_friend_groups()
    {
        $user = User::factory()->create();
        $group = FriendGroup::factory()->create();

        $group->friends()->attach($user->id);

        $this->assertTrue($group->friends->contains($user));
        $this->assertTrue($user->friendGroups->contains($group));
    }

}
