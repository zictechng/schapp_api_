<?php

namespace App\Imports;

use App\Models\GeneratePin;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $tid = substr(str_shuffle($permitted_chars), 0, 16);
        $userDetails = auth('sanctum')->user();
        return new GeneratePin([
            //
            'card_pin' => $row[0],
            'card_status' => "Pending",
            'card_date' => date('d/m/Y H:i:s'),
            'card_addedby' => $userDetails->username,
            'card_tid' => $tid,
        ]);
    }
}