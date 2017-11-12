<?php

namespace Svi\FileBundle\Twig;

use Svi\Application;
use Svi\FileBundle\Service\ImageService;

class ImageTwigExtension extends \Twig_Extension
{
	/**
	 * @var Application
	 */
	private $app;

	function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'twig.svi_image';
	}

	public function getFilters()
	{
		return [
			new \Twig_SimpleFilter('imageResize', [$this, 'imageResizeFunction']),
		];
	}

	public function imageResizeFunction($image, $width, $height, $mode = 0)
	{
		return $this->app[ImageService::class]->getImagePath($image, $width, $height, $mode);
	}

}