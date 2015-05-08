<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\Contract;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;


interface DefinitionExtractorInterface
{

	/**
	 * @param Bundle[] $bundles
	 * @return Definition[]
	 */
	function extractFromBundles($bundles);


	/**
	 * @return Definition[]
	 */
	function extractFromBundle(Bundle $bundle);


	/**
	 * @return Definition[]
	 */
	public function extractFromExtension(ExtensionInterface $extension);

}