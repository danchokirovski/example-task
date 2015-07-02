<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class CarController extends AbstractRestfulController
{
    public function getList()
    {
        $entityManager = $this->getEntityManager();
        
        $carRepo = $entityManager->getRepository('Application\Entity\Car');
        
        try {
            $allCars = $carRepo->findAll();
            /* @var $allCars \Application\Entity\Car[] */
        } catch (\Exception $ex) {
            return $this->returnError();
        }
        
        $results = array();
        
        if (!empty($allCars)) {
            foreach ($allCars as $carEntity) {
                $results['example-task']['cars']['car_'.$carEntity->getCarID()] = array(
                    'carID' => $carEntity->getCarID(),
                    'carName' => $carEntity->getCarName(),
                    'carYear' => $carEntity->getCarYear(),
                    'carDescription' => $carEntity->getCarDescription()
                );
            }
        }
        
        $results['example-task']['cars']['count']  = count($allCars);
        
        $this->response->setStatusCode(200);

        return new JsonModel($results);
    }
 
    public function get($id)
    {
        return $this->returnNoImplemented();
    }
 
    public function create($data)
    {
        $carValidator = $this->getServiceLocator()->get('Application\Service\CarValidator');
        /* @var $carValidator \Application\Service\CarValidator */
        
        $entityManager = $this->getEntityManager();
        
        if (!$carValidator->isValid($data, $entityManager)) {
            return $this->returnNoValidError();
        }
        
        $carEntity = new \Application\Entity\Car();
        /* @var $carEntity \Application\Entity\Car */
        
        $carEntity->exchangeArray($data);
                
        try {
            $entityManager->persist($carEntity);
            $entityManager->flush();
        } catch (\Exception $ex) {
            return $this->returnError();
        }
        
        $this->response->setStatusCode(201);
        
        return new JsonModel(array(
            'example-task' => array(
                'car' => $carEntity->getArrayCopy()
            ),
        ));
    }
 
    public function update($id, $data)
    {
        return $this->returnNoImplemented();
    }
 
    public function delete($id)
    {
        return $this->returnNoImplemented();
    }
    
    /**
     * Set status code 501 and error message
     * @return JsonModel
     */
    protected function returnError()
    {
        $this->response->setStatusCode(400);
        
        return new JsonModel(array(
            'example-task' => array(
                'message' => 'There are some error',
                'status'  => 'error'
            ),
        ));
    }
    
    /**
     * Set status code 400 and no valid data message
     * @return JsonModel
     */
    protected function returnNoValidError()
    {
        $this->response->setStatusCode(400);
        
        return new JsonModel(array(
            'example-task' => array(
                'message' => 'No valid data',
                'status'  => 'error'
            ),
        ));
    }
    
     /**
     * Set status code 501 and no implemented message
     * @return JsonModel
     */
    protected function returnNoImplemented()
    {
        $this->response->setStatusCode(501);

        return new JsonModel(array(
            'example-task' => array(
                'message' => 'Not implemented',
                'status'  => 'error'
            ),
        ));
    }
}
