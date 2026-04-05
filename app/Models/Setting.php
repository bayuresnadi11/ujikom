<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'app_name',
        'app_logo',
        'japati_api_token',
        'japati_gateway_number',
        'midtrans_server_key',
        'midtrans_client_key',
        'midtrans_is_production',
    ];
}

