<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo_url')->nullable();
            $table->timestamps();
        });

        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            $table->unique(['brand_id', 'slug']);
        });

        Schema::table('cars', function (Blueprint $table) {
            $table->foreignId('brand_id')->nullable()->after('id')->constrained('brands')->nullOnDelete();
            $table->foreignId('car_model_id')->nullable()->after('brand_id')->constrained('car_models')->nullOnDelete();
        });

        DB::table('cars')
            ->orderBy('id')
            ->chunkById(100, function ($cars) {
                foreach ($cars as $car) {
                    $make = trim((string) $car->make);
                    $model = trim((string) $car->model);

                    if ($make === '' || $model === '') {
                        continue;
                    }

                    $brandSlug = Str::slug($make) ?: Str::of($make)->lower()->replaceMatches('/[^a-z0-9]+/i', '-')->trim('-')->toString();
                    $brandId = DB::table('brands')->where('slug', $brandSlug)->value('id');

                    if (!$brandId) {
                        $brandId = DB::table('brands')->insertGetId([
                            'name' => $make,
                            'slug' => $brandSlug,
                            'logo_url' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    $modelSlug = Str::slug($model) ?: Str::of($model)->lower()->replaceMatches('/[^a-z0-9]+/i', '-')->trim('-')->toString();
                    $modelId = DB::table('car_models')
                        ->where('brand_id', $brandId)
                        ->where('slug', $modelSlug)
                        ->value('id');

                    if (!$modelId) {
                        $modelId = DB::table('car_models')->insertGetId([
                            'brand_id' => $brandId,
                            'name' => $model,
                            'slug' => $modelSlug,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    DB::table('cars')
                        ->where('id', $car->id)
                        ->update([
                            'brand_id' => $brandId,
                            'car_model_id' => $modelId,
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropConstrainedForeignId('car_model_id');
            $table->dropConstrainedForeignId('brand_id');
        });

        Schema::dropIfExists('car_models');
        Schema::dropIfExists('brands');
    }
};
