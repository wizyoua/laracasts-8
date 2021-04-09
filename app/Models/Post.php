<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File as FacadesFile;

class Post extends Model
{
    use HasFactory;

    public static function allPosts()
    {
        $files =  FacadesFile::files(resource_path("posts/"));

        return array_map(function ($file) {
            return $file->getContents();
        }, $files);
    }

    public static function find($slug)
    {
        if(! file_exists($path = resource_path("posts/{$slug}.html"))) {
            throw new ModelNotFoundException();
        }

        //caching hitting the post
        return cache()->remember("posts.{$slug}", 1200 , function() use ($path) {
            return  $post = file_get_contents($path);
        });

    }
}
