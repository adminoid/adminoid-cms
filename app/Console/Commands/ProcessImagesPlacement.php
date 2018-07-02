<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Page;
use Illuminate\Support\Facades\Storage;

class ProcessImagesPlacement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:placement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        die;
        $pages = Page::all();
        foreach ($pages as $page) {
            $content = $page->content;
            preg_match_all("#img.+src=\"([^\"]+)#i", $content, $matches);
            $images = $matches[1];
            foreach ($images as $image) {
                $fromPath = "public/" . $image;
                $filename = pathinfo($fromPath, PATHINFO_FILENAME) . "." . pathinfo($fromPath, PATHINFO_EXTENSION);
                $toUrl =  $page->uri . "/" . $filename;
                $toPath = "public/img1/" . $toUrl;
                Storage::disk('base')->copy($fromPath, $toPath);
                $to = "/img/pages/" . $toUrl;
                if ($to != $image) {
                    $this->info($image . " -->> \n\r" . $to . "\n\r\n\r");
                }
            }
        }
    }
}
