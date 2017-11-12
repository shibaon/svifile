<?php

namespace Svi\FileBundle\Service;

use Svi\AppContainer;
use Svi\HttpBundle\Utils\Utils;
use Svi\FileBundle\BundleTrait;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService extends AppContainer
{
    use BundleTrait;

	public function getNewFileUriFromField($oldValue, UploadedFile $field = null, $destDir = null, $delete = false)
	{
		if ($field) {
			$newValue = $this->uploadFile($field, $destDir);
			if ($newValue && $oldValue) {
				$this->deleteFile($oldValue);
			}
			if ($newValue) {
				return $newValue;
			}
		} else if ($delete) {

			$this->deleteFile($oldValue);

			return null;
		}

		return $oldValue;
	}

	public function deleteFile($uri, $isAbsolute = false)
	{
		$realPath = $isAbsolute ? $uri : 'files/' . $uri;
		if (file_exists($realPath)) {
			unlink($realPath);
		}
	}

	public function uploadFile(File $uploadedFile, $destinationDirectoryUri)
	{
		$realDirectory = $this->app->getRootDir() . '/web/files/' . $destinationDirectoryUri;
		if (!file_exists($realDirectory)) {
			if (!mkdir($realDirectory, 0777, true)) {
				throw new \Exception('Cannot create directory ' . $realDirectory);
			}
		}

		if ($uploadedFile instanceof UploadedFile) {
			$ext = $uploadedFile->getClientOriginalExtension();
			$name = str_replace('.' . $ext, '', $uploadedFile->getClientOriginalName());
		} else {
			$ext = $uploadedFile->getExtension();
			$name = $uploadedFile->getBasename('.' . $ext);
		}
		$name = str_replace(' ', '_', Utils::transliterate($name));

		$newName = false;

		for ($n = 0; !$newName; $n++) {
			if (!file_exists($realDirectory . '/' . $name . ($n ? $n : '') . '.' . $ext)) {
				$newName = $name . ($n ? $n : '');
			}
		}

		$uploadedFile->move($realDirectory, $newName . '.' . $ext);

		return $destinationDirectoryUri . '/' . $newName . '.' . $ext;
	}

}
