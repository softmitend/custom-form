<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_project_requests', function (Blueprint $table): void {
            $table->id();
            $table->string('customer_name');
            $table->string('business_name');
            $table->string('whatsapp_number', 30);
            $table->string('website_type');
            $table->string('website_type_other')->nullable();
            $table->json('website_goals');
            $table->string('website_goal_other')->nullable();
            $table->json('target_users')->nullable();
            $table->string('target_user_other')->nullable();
            $table->text('desired_workflow');
            $table->json('required_features')->nullable();
            $table->string('required_feature_other')->nullable();
            $table->json('available_materials')->nullable();
            $table->text('design_reference')->nullable();
            $table->date('target_completion_date');
            $table->string('budget_range');
            $table->text('additional_information')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_project_requests');
    }
};
