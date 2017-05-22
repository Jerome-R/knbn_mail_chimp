<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

use AppBundle\Entity\ImportFile;

class ImportFileLogService
{
  private $em;

  public function __construct(EntityManager $entityManager)
  {
    $this->em = $entityManager;
  }

  public function AddImportFile($result)
  {

    $importFile = new ImportFile();
    $importFile->setName($result['name']);
    $importFile->setStartTime($result['startTime']);
    $importFile->setEndTime($result['endTime']);
    $importFile->setErrorCount(0);
    $importFile->setSuccessCount(0);
    $importFile->setTotalProcessedCount($result['totalProcessedCount']);
    $importFile->setHasErrors(0);

    $this->em->persist($importFile);

    //$importFile->setErrorCount($result->getErrorCount());
    //$importFile->setSuccessCount($result->getSuccessCount());
    //$importFile->setHasErrors($result->hasErrors());

    $this->em->flush();

  }

}