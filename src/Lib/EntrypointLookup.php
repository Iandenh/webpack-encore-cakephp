<?php

namespace Iandenh\WebpackEncore\Lib;

class EntrypointLookup
{
    private $entrypointJsonPath;
    private $entrypointData;

    public function __construct($entrypointJsonPath)
    {
        $this->entrypointJsonPath = $entrypointJsonPath;
    }

    /**
     * Get Scripts
     *
     * @param string $entryName
     * @return array
     */
    public function getScripts($entryName)
    {
        return $this->getEntryFiles($entryName, 'js');
    }

    /**
     * Get Css
     *
     * @param string $entryName
     * @return array
     */
    public function getCss($entryName)
    {
        return $this->getEntryFiles($entryName, 'css');
    }

    /**
     * Get the Entry files
     * @param string $entryName
     * @param string $key
     * @return array
     */
    private function getEntryFiles($entryName, $key)
    {
        $entriesData = $this->getEntriesData();
        if (!isset($entriesData['entrypoints'][$entryName])) {
            return [];
        }

        $entryData = $entriesData['entrypoints'][$entryName];

        if (!isset($entryData[$key])) {
            return [];
        }

        $entryFiles = $entryData[$key];
        return $entryFiles;
    }

    /**
     * @return array|null
     */
    private function getEntriesData()
    {
        if ($this->entrypointData === null) {
            if (file_exists($this->entrypointJsonPath)) {
                $this->entrypointData = json_decode(file_get_contents($this->entrypointJsonPath), true);
            }
            if (null === $this->entrypointData) {
                throw new \InvalidArgumentException(sprintf('There was a problem JSON decoding the "%s" file', $this->entrypointJsonPath));
            }
            if (!isset($this->entrypointData['entrypoints'])) {
                throw new \InvalidArgumentException(sprintf('Could not find an "entrypoints" key in the "%s" file', $this->entrypointJsonPath));
            }
        }

        return $this->entrypointData;
    }
}
