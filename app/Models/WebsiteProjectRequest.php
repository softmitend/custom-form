<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'customer_name',
    'business_name',
    'whatsapp_number',
    'website_type',
    'website_type_other',
    'website_goals',
    'website_goal_other',
    'target_users',
    'target_user_other',
    'desired_workflow',
    'required_features',
    'required_feature_other',
    'available_materials',
    'design_reference',
    'target_completion_date',
    'budget_range',
    'additional_information',
    'status',
])]
class WebsiteProjectRequest extends Model
{
    public const STATUS_NEW = 'new';

    public const STATUS_REVIEWED = 'reviewed';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_CLOSED = 'closed';

    /**
     * @return array<string, string>
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_NEW => 'Baru',
            self::STATUS_REVIEWED => 'Sudah ditinjau',
            self::STATUS_CONTACTED => 'Sudah dihubungi',
            self::STATUS_CLOSED => 'Selesai',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusLabels()[$this->status] ?? ucfirst((string) $this->status);
    }

    protected function casts(): array
    {
        return [
            'website_goals' => 'array',
            'target_users' => 'array',
            'required_features' => 'array',
            'available_materials' => 'array',
            'target_completion_date' => 'date',
        ];
    }
}
