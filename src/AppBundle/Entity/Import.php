<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Import
 *
 * @ORM\Table(name="app_import_clients")
 * @ORM\Entity()
 */
class Import
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="id_client", type="string", length=255)
     */
    private $idClient;

    

    /**
     * @var string
     *
     * @ORM\Column(name="id_campaign_name", type="string", length=255)
     */
    private $idCampaignName;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idClient
     *
     * @param string $idClient
     *
     * @return Import
     */
    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;

        return $this;
    }

    /**
     * Get idClient
     *
     * @return string
     */
    public function getIdClient()
    {
        return $this->idClient;
    }

    /**
     * Set idCampaignName
     *
     * @param string $idCampaignName
     *
     * @return Import
     */
    public function setIdCampaignName($idCampaignName)
    {
        $this->idCampaignName = $idCampaignName;

        return $this;
    }

    /**
     * Get idCampaignName
     *
     * @return string
     */
    public function getIdCampaignName()
    {
        return $this->idCampaignName;
    }
}