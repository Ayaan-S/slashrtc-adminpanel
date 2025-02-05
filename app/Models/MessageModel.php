<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table = 'messages'; // Assuming a table named 'messages' exists
    protected $primaryKey = 'id';
    protected $allowedFields = ['sender_id', 'receiver_id', 'message', 'timestamp', 'read_status'];


    public function saveMessage($senderId, $receiverId, $message, $readStatus = 0)

    {
        return $this->insert([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message,
            'timestamp' => time(),
            'read_status' => $readStatus

        ]);
    }

    public function getMessages($receiverId)
    {
        return $this->where('receiver_id', $receiverId)->findAll();
    }
}
