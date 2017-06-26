<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed;

use Carbon\Carbon;
use Zhiyi\Plus\Models\Comment;
use Illuminate\Console\Command;
use Zhiyi\Plus\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Database\Schema\Blueprint;

class FeedPackageHandler extends PackageHandler
{
    public function removeHandle($command)
    {
        if ($command->confirm('This will delete your datas for feeds, continue?')) {
            Comment::where('component', 'feed')->delete();
            Permission::whereIn('name', ['feed-post', 'feed-comment', 'feed-digg', 'feed-collection'])->delete();
            Schema::dropIfExists('feed_atmes');
            Schema::dropIfExists('feed_diggs');
            Schema::dropIfExists('feed_comments');
            Schema::dropIfExists('feed_storages');
            Schema::dropIfExists('feed_collections');

            $command->info('The Feed Component has been removed');
        }
    }

    public function installHandle($command)
    {
        // publish public assets
        $command->call('vendor:publish', [
            '--provider' => FeedServiceProvider::class,
            '--tag' => 'public',
            '--force' => true,
        ]);

        // Run the database migrations
        $command->call('migrate');

        if (! Schema::hasTable('feed_atmes')) {
            Schema::create('feed_atmes', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include dirname(__DIR__).'/database/table_feed_atmes_columns.php';
        }

        if (! Schema::hasTable('feed_diggs')) {
            Schema::create('feed_diggs', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('primary key');
                $table->timestamps();
            });
            include dirname(__DIR__).'/database/table_feed_diggs_columns.php';
        }

        if (! Schema::hasTable('feed_comments')) {
            Schema::create('feed_comments', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('primary key');
                $table->timestamps();
                $table->softDeletes();
            });
            include dirname(__DIR__).'/database/table_feed_comments_columns.php';
        }

        if (! Schema::hasTable('feed_storages')) {
            Schema::create('feed_storages', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('primary key');
                $table->timestamps();
            });
            include dirname(__DIR__).'/database/table_feed_storages_columns.php';
        }

        if (! Schema::hasTable('feed_collections')) {
            Schema::create('feed_collections', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('primary key');
                $table->timestamps();
            });
            include dirname(__DIR__).'/database/table_feed_collections_columns.php';
        }

        if (! Schema::hasTable('feed_views')) {
            Schema::create('feed_views', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('primary key');
                $table->timestamps();
            });
            include dirname(__DIR__).'/database/table_feed_views_columns.php';
        }

        $time = Carbon::now();

        Permission::insert([
            [
                'name' => 'feed-post',
                'display_name' => '发送分享',
                'description' => '用户发送分享权限',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'feed-comment',
                'display_name' => '评论分享',
                'description' => '用户评论分享权限',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'feed-digg',
                'display_name' => '点赞分享',
                'description' => '用户点赞分享权限',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'feed-collection',
                'display_name' => '收藏分享',
                'description' => '用户收藏分享权限',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ]);

        $command->info('Install Successfully');
    }

    /**
     * Create a migration file.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createMigrationHandle(Command $command)
    {
        $path = str_replace(app('path.base'), '', dirname(__DIR__).'/database/migrations');
        $table = $command->ask('Enter the table name');
        $name = sprintf('create_%s_table', $table);
        $create = $command->confirm('Is it creating a new migration');

        return $command->call('make:migration', [
            'name' => $name,
            '--path' => $path,
            '--table' => $table,
            '--create' => $create,
        ]);
    }
}
