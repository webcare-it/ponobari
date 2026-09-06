<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    // Define the fillable fields for SMTP settings
    protected $fillable = [
        'phone',
        'email',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'address',
        'logo',
        'pathao_client_id',
        'pathao_client_secret',
        'pathao_username',
        'pathao_password',
        'pathao_sandbox',
        'steadfast_api_key',
        'steadfast_secret_key',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'admin_notification_email',
        'whatsapp',
        'droploo_app_key',
        'droploo_app_secret',
        'droploo_username',
        'website_primary_color',
        'website_secondary_color',
        'primary_color',
        'secondary_color',
        'accent_color',
        'category_bg_color',
        'header_bg_color',
        'footer_bg_color'
    ];
}