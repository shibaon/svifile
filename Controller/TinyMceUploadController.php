<?php

namespace Svi\FileBundle\Controller;

use Svi\HttpBundle\Controller\Controller;
use Svi\FileBundle\Bundle;
use Svi\FileBundle\BundleTrait;
use Symfony\Component\HttpFoundation\Request;
use Svi\FileBundle\Classes\File;

abstract class TinyMceUploadController extends Controller
{
    use BundleTrait;
    use \Svi\TengineBundle\BundleTrait;

	public function imageAction(Request $request)
	{
		$type = $request->query->get('type');

		$form = $this->createForm()
			->add('upload', $type == 'file' ? 'file' : 'image', array(
				'label' => 'Файл',
				'required' => true,
			));

		$result = false;

		if ($form->handleRequest($request)->isValid()) {
			$file = new File($this->getFileService()
				->uploadFile($form->get('upload')->getData(), 'uploaded_images/' . date('Ym')));
			$result = $file->getUrl();
		}

		$this->getTemplateService();

        if (isset($this->app['twig']) && $this->app['twig']) {
            /** @var \Twig_Loader_Filesystem $loader */
            $loader = $this->app['twig']->getLoader();
            $loader->addPath($this->app[Bundle::class]->getDir());
        }

		return $this->render('Views/TinyMceUpload/image', array(
			'form' => $form,
			'result' => $result,
			'type' => $type,
		));
	}

}
