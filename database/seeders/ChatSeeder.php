<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;

class ChatSeeder extends Seeder
{
    public function run()
    {
        // Get users
        $buyer = User::where('role', 'buyer')->first();
        $landowner = User::where('role', 'landowner')->first();
        $admin = User::where('role', 'admin')->first();

        if (!$buyer || !$landowner || !$admin) {
            $this->command->warn('Please create users with roles first!');
            return;
        }

        // Conversation 1: Buyer <-> Landowner
        $conv1 = Conversation::create([
            'user_one_id' => $buyer->id,
            'user_two_id' => $landowner->id,
            'type' => 'private',
            'last_message_at' => now()
        ]);

        Message::create([
            'conversation_id' => $conv1->id,
            'sender_id' => $buyer->id,
            'message' => 'Halo, saya mau tanya jadwal lapangan futsal untuk hari Sabtu'
        ]);

        Message::create([
            'conversation_id' => $conv1->id,
            'sender_id' => $landowner->id,
            'message' => 'Halo! Untuk hari Sabtu masih ada slot jam 3 sore dan jam 7 malam'
        ]);

        Message::create([
            'conversation_id' => $conv1->id,
            'sender_id' => $buyer->id,
            'message' => 'Baik, saya booking untuk jam 7 malam ya. Harganya berapa?',
            'is_read' => false
        ]);

        // Conversation 2: Buyer <-> Admin
        $conv2 = Conversation::create([
            'user_one_id' => $buyer->id,
            'user_two_id' => $admin->id,
            'type' => 'support',
            'last_message_at' => now()->subHours(2)
        ]);

        Message::create([
            'conversation_id' => $conv2->id,
            'sender_id' => $buyer->id,
            'message' => 'Halo admin, saya ada kendala dengan pembayaran'
        ]);

        Message::create([
            'conversation_id' => $conv2->id,
            'sender_id' => $admin->id,
            'message' => 'Halo! Bisa dijelaskan kendalanya seperti apa?',
            'is_read' => true
        ]);

        // Conversation 3: Landowner <-> Admin
        $conv3 = Conversation::create([
            'user_one_id' => $landowner->id,
            'user_two_id' => $admin->id,
            'type' => 'support',
            'last_message_at' => now()->subDays(1)
        ]);

        Message::create([
            'conversation_id' => $conv3->id,
            'sender_id' => $landowner->id,
            'message' => 'Saya ingin update foto lapangan, bagaimana caranya?'
        ]);

        Message::create([
            'conversation_id' => $conv3->id,
            'sender_id' => $admin->id,
            'message' => 'Silakan ke menu Lapangan > Edit > Upload Foto Baru',
            'is_read' => true
        ]);

        $this->command->info('Chat seeder completed!');
    }
}