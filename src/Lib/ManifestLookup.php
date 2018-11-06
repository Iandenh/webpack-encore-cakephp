<?php

namespace Iandenh\WebpackEncore\Lib;

class ManifestLookup
{
    private $manifestJsonPath;
    private $manifestData;

    public function __construct($manifestJsonPath)
    {
        $this->manifestJsonPath = $manifestJsonPath;
    }

    public function getFile($path)
    {
        return $this->getManifestData($path);
    }

    private function getManifestData($path)
    {
        if ($this->manifestData === null) {
            if (file_exists($this->manifestJsonPath)) {
                $this->manifestData = json_decode(file_get_contents($this->manifestJsonPath), true);
            }
        }

        return isset($this->manifestData[$path]) ? $this->manifestData[$path] : null;
    }
}
