<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$categories = [
			[
				'name' => 'Yoghurt',
				'is_active' => true,
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'name' => 'Ice Packet',
				'is_active' => true,
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'name' => 'Curd',
				'is_active' => true,
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'name' => 'Ge Oil',
				'is_active' => true,
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'name' => 'Drinking Bottel',
				'is_active' => true,
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'name' => 'Popcilcles',
				'is_active' => true,
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'name' => 'Milk Toffe',
				'is_active' => true,
				'created_at' => now(),
				'updated_at' => now(),
			],
		];

		DB::table('product_categories')->insert($categories);
	}
}
