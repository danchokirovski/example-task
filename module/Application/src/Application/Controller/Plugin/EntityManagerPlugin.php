<?php
namespace Application\Controller\Plugin;
 
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Doctrine\ORM\EntityManager;

class EntityManagerPlugin extends AbstractPlugin
{
    
    /**
    * @var \Doctrine\ORM\EntityManager
    */
    protected $entityManager;
    
    public function __invoke()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getController()->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->entityManager;
    }
}