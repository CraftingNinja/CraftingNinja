<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('node', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('type');
            $table->unsignedInteger('level');
            $table->foreignId('bonus_id')->nullable();
            $table->foreignId('zone_id')->nullable();
            $table->foreignId('area_id')->nullable();
            $table->string('coordinates')->nullable();
            $table->string('timer')->nullable();
            $table->string('timer_type')->nullable();

            $table->index(['level', 'type']);
            $table->index(['zone_id', 'area_id']);
        });

        Schema::create('node_bonuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('condition');
            $table->string('bonus');
        });

        Schema::create('item_node', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('item_id');
            $table->foreignId('node_id');

            $table->index('item_id');
            $table->index('node_id');
        });

        Schema::create('fishing', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->foreignId('category_id');
            $table->unsignedInteger('level');
            $table->unsignedInteger('radius');
            $table->decimal('x', 5, 2)->unsigned()->nullable();
            $table->decimal('y', 5, 2)->unsigned()->nullable();
            $table->foreignId('zone_id')->nullable();
            $table->foreignId('area_id')->nullable();

            $table->index('level');
            $table->index(['zone_id', 'area_id']);
        });

        Schema::create('fishing_item', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('item_id');
            $table->foreignId('fishing_id');

            $table->index('item_id');
            $table->index('fishing_id');
        });

        Schema::create('mob', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->boolean('quest')->nullable();
            $table->string('level')->nullable();
            $table->foreignId('zone_id')->nullable();

            $table->index('zone_id');
        });

        Schema::create('item_mob', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('item_id');
            $table->unsignedBigInteger('mob_id');

            $table->index('item_id');
            $table->index('mob_id');
        });

        Schema::create('location', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->foreignId('location_id')->nullable();
            $table->unsignedSmallInteger('size')->nullable();
        });

        Schema::create('npc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->foreignId('zone_id')->nullable();
            $table->boolean('approx')->nullable();
            $table->decimal('x', 5, 2)->unsigned()->nullable();
            $table->decimal('y', 5, 2)->unsigned()->nullable();
        });

        Schema::create('npc_shop', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('npc_id');
            $table->foreignId('shop_id');

            $table->index('npc_id');
            $table->index('shop_id');
        });

        Schema::create('npc_quest', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('npc_id');
            $table->foreignId('quest_id');

            $table->index('npc_id');
            $table->index('quest_id');
        });

        Schema::create('npc_base', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('title')->nullable();
        });

        Schema::create('npc_npc_base', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('npc_id');
            $table->foreignId('npc_base_id');

            $table->index('npc_id');
            $table->index('npc_base_id');
        });

        Schema::create('shop', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
        });

        Schema::create('item_shop', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('item_id');
            $table->foreignId('shop_id');
            $table->boolean('alt_currency')->nullable();

            $table->index('item_id');
            $table->index('shop_id');
        });

        Schema::create('instance', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('type');
            $table->string('name');
            $table->unsignedInteger('icon');
            $table->foreignId('zone_id')->nullable();

            $table->index('type');
            $table->index('zone_id');
        });

        Schema::create('instance_item', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('instance_id');
            $table->foreignId('item_id');

            $table->index('instance_id');
            $table->index('item_id');
        });

        Schema::create('instance_mob', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('instance_id');
            $table->unsignedBigInteger('mob_id');

            $table->index('instance_id');
            $table->index('mob_id');
        });

        Schema::create('quest', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->foreignId('job_category_id')->nullable();
            $table->unsignedSmallInteger('level');
            $table->unsignedSmallInteger('sort');
            $table->foreignId('zone_id');
            $table->unsignedInteger('icon')->nullable();
            $table->foreignId('issuer_id')->nullable();
            $table->foreignId('target_id')->nullable();
            $table->unsignedSmallInteger('genre');

            $table->index('zone_id');
            $table->index('issuer_id');
            $table->index('target_id');
        });

        Schema::create('quest_reward', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('item_id');
            $table->foreignId('quest_id');
            $table->unsignedSmallInteger('amount')->nullable();

            $table->index('item_id');
            $table->index('quest_id');
        });

        Schema::create('quest_required', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('item_id');
            $table->foreignId('quest_id');

            $table->index('item_id');
            $table->index('quest_id');
        });

        Schema::create('achievement', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->foreignId('item_id');
            $table->unsignedInteger('icon');

            $table->index('item_id');
        });

        Schema::create('fate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedSmallInteger('level');
            $table->unsignedSmallInteger('max_level');
            $table->unsignedSmallInteger('type');
            $table->foreignId('zone_id')->nullable();
            $table->decimal('x', 5, 2)->unsigned()->nullable();
            $table->decimal('y', 5, 2)->unsigned()->nullable();

            $table->index('zone_id');
        });

        Schema::create('job_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('job_job_category', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('job_id');
            $table->foreignId('job_category_id');

            $table->index('job_id');
            $table->index('job_category_id');
        });

        Schema::create('job', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('abbr');
        });

        Schema::create('venture', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('amounts')->nullable();
            $table->foreignId('job_category_id')->nullable();
            $table->unsignedSmallInteger('level');
            $table->unsignedSmallInteger('cost');
            $table->unsignedSmallInteger('minutes');

            $table->index('job_category_id');
        });

        Schema::create('item_venture', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('item_id');
            $table->foreignId('venture_id');

            $table->index('item_id');
            $table->index('venture_id');
        });

        Schema::create('leve', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type', 25)->nullable();
            $table->unsignedSmallInteger('level');
            $table->foreignId('job_category_id');
            $table->foreignId('area_id');
            $table->unsignedTinyInteger('repeats')->nullable();
            $table->unsignedInteger('xp')->nullable();
            $table->unsignedInteger('gil')->nullable();
            $table->unsignedInteger('plate');
            $table->unsignedInteger('frame');
            $table->unsignedInteger('area_icon');

            $table->index('level');
            $table->index('job_category_id');
            $table->index('area_id');
        });

        Schema::create('leve_reward', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('item_id');
            $table->foreignId('leve_id');
            $table->unsignedSmallInteger('rate');
            $table->unsignedSmallInteger('amount')->nullable();

            $table->index('item_id');
            $table->index('leve_id');
        });

        Schema::create('leve_required', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('item_id');
            $table->foreignId('leve_id');
            $table->unsignedSmallInteger('amount')->nullable();

            $table->index('item_id');
            $table->index('leve_id');
        });

        Schema::create('item_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            // $table->foreignId('attribute_id')->nullable();
            $table->string('attribute')->nullable();
            $table->decimal('rank', 8, 3)->nullable();
        });

        Schema::create('item', function (Blueprint $table) {
            $table->increments('id');
            $table->string('eorzea_id', 15)->nullable();
            $table->string('name');
            $table->string('de_name');
            $table->string('fr_name');
            $table->string('jp_name');
            $table->string('help')->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->unsignedInteger('gc_price')->nullable();
            $table->boolean('special_buy')->nullable();
            $table->unsignedInteger('sell_price')->nullable();
            $table->unsignedInteger('ilvl');
            $table->unsignedInteger('elvl')->nullable();
            $table->foreignId('item_category_id');
            $table->foreignId('job_category_id')->nullable();
            $table->boolean('unique')->nullable();
            $table->boolean('tradeable')->nullable();
            $table->unsignedSmallInteger('equip')->nullable();
            $table->unsignedSmallInteger('slot')->nullable();
            $table->unsignedTinyInteger('rarity')->nullable();
            $table->string('icon', 25);
            $table->unsignedSmallInteger('sockets')->nullable();

            $table->index('ilvl');
            $table->index('elvl');
            $table->index('job_category_id');
            $table->index('item_category_id');
        });


        Schema::create('item_attribute', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('item_id');
            $table->enum('quality', ['nq', 'hq', 'max']);
            $table->string('attribute');
            $table->decimal('amount', 8, 2)->unsigned()->nullable();
            $table->unsignedInteger('limit')->nullable();

            $table->index('item_id');
            $table->index('quality');
            $table->index('attribute');
        });

        Schema::create('recipe', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('item_id');
            $table->foreignId('job_id');
            $table->unsignedInteger('level');
            $table->unsignedInteger('recipe_level');
            $table->unsignedTinyInteger('stars')->nullable();
            $table->unsignedSmallInteger('difficulty')->nullable();
            $table->unsignedSmallInteger('durability')->nullable();
            $table->unsignedSmallInteger('quality')->nullable();
            $table->unsignedSmallInteger('yield');
            $table->boolean('quick_synth')->nullable();
            $table->boolean('hq')->nullable();
            $table->boolean('fc')->nullable();

            $table->index('item_id');
            $table->index('job_id');
            $table->index('recipe_level');
            $table->index('level');
        });

        Schema::create('recipe_reagents', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('item_id');
            $table->foreignId('recipe_id');
            $table->unsignedSmallInteger('amount');

            $table->index('item_id');
            $table->index('recipe_id');
        });

        Schema::create('notebook', function(Blueprint $table) {
            $table->increments('id')->unsigned();
        });

        Schema::create('notebook_recipe', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('recipe_id')->unsigned();
            $table->integer('notebook_id')->unsigned();
            $table->tinyinteger('slot')->unsigned();
        });

        Schema::create('notebookdivision', function(Blueprint $table) {
            $table->increments('id')->unsigned(); // 0 indexed, but artificially +1'd
            $table->integer('category_id')->unsigned();
            $table->string('name');
        });

        Schema::create('notebook_notebookdivision', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('notebookdivision_id')->unsigned(); // 0 indexed, but artificially +1'd
            $table->integer('notebook_id')->unsigned();
        });

        Schema::create('notebookdivision_category', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
        });
    }

    public function down(): void
    {
        $tables = [
            'node',
            'node_bonuses',
            'item_node',
            'fishing',
            'fishing_item',
            'mob',
            'item_mob',
            'location',
            'npc',
            'npc_shop',
            'npc_quest',
            'npc_base',
            'npc_npc_base',
            'shop',
            'item_shop',
            'instance',
            'instance_item',
            'instance_mob',
            'quest',
            'quest_reward',
            'quest_required',
            'achievement',
            'fate',
            'job_category',
            'job_job_category',
            'job',
            'venture',
            'item_venture',
            'leve',
            'leve_reward',
            'leve_required',
            'item_category',
            'item',
            'item_attribute',
            'recipe',
            'recipe_reagents',
            'notebook',
            'notebook_recipe',
            'notebookdivision',
            'notebook_notebookdivision',
            'notebookdivision_category',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
