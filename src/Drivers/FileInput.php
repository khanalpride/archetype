<?php

namespace Archetype\Drivers;

use Archetype\Drivers\InputInterface;
use Illuminate\Support\Str;
use Archetype\Support\PHPFileStorage;

class FileInput implements InputInterface
{
    public $filename;

    public $extension;

    public $relativeDir;

    public $absoluteDir;

    public $root;

    public function readPath($path = null)
    {
        $this->ensureDefaultRootExists();
        $this->extractPathProperties($path);
        return $this;
    }

    public function load($path = null)
    {
        $this->ensureDefaultRootExists();
        $this->extractPathProperties($path);

        return (new PHPFileStorage)->get($this->absolutePath());
    }

    public function absolutePath()
    {
        return "$this->absoluteDir/$this->filename" . ($this->extension ? ".$this->extension" : "");
    }

    public function filename()
    {
        return $this->filename;
    }

    protected function ensureDefaultRootExists()
    {
        $this->root = $this->root ?? config('archetype')['roots']['input'];
    }

    protected function extractPathProperties($path)
    {
        preg_match('/(.*)\..*/', basename($path), $matches);
        $this->filename = $path ? basename($path, '.php') : null;
        
        preg_match('/.*\.(.*)/', basename($path), $matches);
        $this->extension = $matches[1] ?? null;
        
        $pathIsAbsolute = Str::startsWith($path, '/');
        
        if($pathIsAbsolute) {
            $this->absoluteDir = dirname($path);
        } else {
            $this->absoluteDir = dirname($this->root['root'] . "/" . $path);
        }

        $this->relativeDir = Str::of($this->absoluteDir)
            ->replaceFirst($this->root['root'], '')
            ->ltrim('/')
            ->__toString();
    }
}