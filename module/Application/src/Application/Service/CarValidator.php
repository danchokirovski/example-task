<?php
namespace Application\Service;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;
use Zend\I18n\Validator\Alpha;

class CarValidator
{
    /**
     * @param array $postData
     * @param Doctrine\ORM\EntityManager $entityManager
     * @return boolean true || false
     */
    public function isValid($postData, $entityManager)
    {
        $carNameValidator = new Input('carName');
        $carYearValidator = new Input('carYear');
        $carDescriptionValidator = new Input('carDescription');
        
        $carNameValidator->getValidatorChain()->attach(new Validator\NotEmpty());
        $carNameValidator->getValidatorChain()->attach(new Alpha(array('allowWhiteSpace' => true)));
        $carNameValidator->getValidatorChain()->attach(new Validator\StringLength(array('min' => 1,'max' => 20)));
        
        $carYearValidator->getValidatorChain()->attach(new Validator\NotEmpty());
        $carYearValidator->getValidatorChain()->attach(new Validator\Digits());
        $carYearValidator->getValidatorChain()->attach(new Validator\Between(array('min' => 1970, 'max' => 2020)));
        
        $carDescriptionValidator->setAllowEmpty(true);
        $carDescriptionValidator->getValidatorChain()->attach(new Validator\StringLength(array('min' => 0 ,'max' => 255)));
        
        $inputFilter = new InputFilter();
        $inputFilter->add($carNameValidator);
        $inputFilter->add($carYearValidator);
        $inputFilter->add($carDescriptionValidator);
        $inputFilter->setData($postData);
        
        $result = false;
        
        if ($inputFilter->isValid()) {
            $result = true;
        }
        
        try { 
            $exitsCar = $entityManager->getRepository('Application\Entity\Car')->findOneBy(array('carName' => $inputFilter->getValue('carName')));
            
            if (!empty($exitsCar)) {
                $result = false;
            }
        } catch (\Exception $ex) {
            $result = false;
        }
        
        return $result;
    }
}