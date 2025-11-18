<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class TweetSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        if (! $user) {
            $this->command->info('No users found; skipping Tweet seeder.');
            return;
        }

        // ensure storage folder exists
        Storage::disk('public')->makeDirectory('tweets');

        // sample text tweets
        Tweet::create([
            'user_id' => $user->id,
            'content' => 'Hello world! This is a seeded tweet with no image.',
        ]);

        // if public/movie_images exist, copy a sample image into storage and seed with it
        $sampleSrc = public_path('movie_images');
        if (is_dir($sampleSrc)) {
            $files = array_values(array_filter(scandir($sampleSrc), function ($f) use ($sampleSrc) {
                return is_file($sampleSrc . DIRECTORY_SEPARATOR . $f) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $f);
            }));

            if (count($files) > 0) {
                $filename = $files[0];
                $dest = 'tweets/' . uniqid() . '_' . $filename;
                copy($sampleSrc . DIRECTORY_SEPARATOR . $filename, storage_path('app/public/' . $dest));

                Tweet::create([
                    'user_id' => $user->id,
                    'content' => 'A seeded tweet with an image.',
                    'image_path' => $dest,
                ]);
            }
        }
    }
}
