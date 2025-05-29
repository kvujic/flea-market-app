<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadItemImages extends Command
{
    protected $signature = 'images:download';
    protected $description = 'Download and store item images from external URLs';

    public function handle()
    {
        $imageUrls = [
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
        ];

        foreach($imageUrls as $url) {
            $ext = pathinfo($url, PATHINFO_EXTENSION);
            $filename = md5($url) . '.' . $ext;
            $path = "images/{$filename}";

            if (Storage::disk('public')->exists($path)) {
                $this->line("Skipped (already exists): {$filename}");
                continue;
            }

            $response = Http::get($url);

            if ($response->successful()) {
                Storage::disk('public')->put("images/{$filename}", $response->body());
                $this->info("Saved: {$filename}");
            }
        }
        $this->info('All images processed.');
        return Command::SUCCESS;
    }
}
