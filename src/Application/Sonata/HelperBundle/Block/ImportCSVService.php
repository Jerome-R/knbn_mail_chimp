<?php

// src\Application\Sonata\HelperBundle\Block\ImportCSVService.php

namespace Application\Sonata\HelperBundle\Block;

use Symfony\Component\HttpFoundation\Response;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Application\Sonata\HelperBundle\Helper\CSVTypes;

class ImportCSVService extends BaseBlockService
{

    private $container = null;

    public function __construct($name, $templating, $container=null)
    {
        parent::__construct($name, $templating);
        $this->container = $container;  
    }

    public function getName()
    {
        return 'Import CSV';
    }

    public function getDefaultSettings()
    {
        return array();
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = array_merge($this->getDefaultSettings(), $blockContext->getSettings());

        $curBlock='ApplicationSonataHelperBundle:Block:block_importCSV.html.twig';
        /*if (!$this->container->get('security.context')->isGranted("ROLE_SUPER_ADMIN")) {
            $curBlock='ApplicationSonataHelperBundle:Block:block_empty.html.twig';
        }*/

        return $this->renderResponse($curBlock, array(
            'block'     => $blockContext->getBlock(),
            'allTypes'  => CSVTypes::getTypesAndIds(),
            'settings'  => $settings
            ), $response);
    }
}