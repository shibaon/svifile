<?php

namespace Svi\FileBundle;

use Svi\Application;
use Svi\FileBundle\Service\FileService;
use Svi\FileBundle\Service\ImageService;
use Svi\FileBundle\Twig\ImageTwigExtension;

class Bundle extends \Svi\Service\BundlesService\Bundle
{
    use \Svi\TengineBundle\BundleTrait;

	function __construct(Application $app)
	{
		parent::__construct($app);

		if ($this->getTemplateService()->hasTwig()) {
			$this->getTemplateService()->getTwig()->addExtension(new ImageTwigExtension($app));
		}
	}

	protected function getServices()
	{
		return [
			FileService::class,
			ImageService::class,
		];
	}

} 