<?php
namespace Neos\MetaData\ContentRepositoryAdapter\Eel\Helper;

/*
 * This file is part of the Neos.MetaData.ContentRepository package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\MetaData\ContentRepositoryAdapter\Domain\Repository\MetaDataRepository;
use TYPO3\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use TYPO3\Media\Domain\Model\Asset;
use TYPO3\Media\Domain\Model\ImageVariant;
use TYPO3\TYPO3CR\Domain\Factory\NodeFactory;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * EEL operation to get the meta data of an asset
 */
class MetaDataHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var MetaDataRepository
     */
    protected $metaDataRepository;

    /**
     * @Flow\Inject
     * @var NodeFactory
     */
    protected $nodeFactory;

    /**
     * All methods are considered safe, i.e. can be executed from within Eel
     *
     * @param string $methodName
     *
     * @return boolean
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }

    /**
     * @param Asset $asset
     * @param NodeInterface $contextNode
     *
     * @return NodeInterface The MetaData node
     *
     */
    public function find(Asset $asset, NodeInterface $contextNode)
    {
        if ($asset instanceof ImageVariant) {
            $asset = $asset->getOriginalAsset();
        }
        $metaDataNodeData = $this->metaDataRepository->findOneByAsset($asset, $contextNode->getWorkspace());

        return $metaDataNodeData === null ? null : $this->nodeFactory->createFromNodeData($metaDataNodeData, $contextNode->getContext());
    }
}
