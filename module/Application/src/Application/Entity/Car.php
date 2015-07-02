<?php
  
namespace Application\Entity;
  
use Doctrine\ORM\Mapping as ORM;  
    
/**
 * A car entity.
 *
 * @ORM\Entity(repositoryClass="Application\Repository\CarRepository")
 * @ORM\Table(name="Cars")
 * @property int $carID
 * @property string $carName
 * @property string $carYear
 * @property string $carDescription
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $carID;
  
    /**
     * @ORM\Column(type="string")
     */
    protected $carName;
  
    /**
     * @ORM\Column(type="string")
     */
    protected $carYear;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $carDescription;
  
    /**
     * Magic getter or setter to expose protected properties.
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        $key = strtolower(substr($method, 3, 1)) . substr($method, 4);
        $value = isset($args[0]) ? $args[0] : null;
        $realMethod = substr($method, 0, 3);
        switch ($realMethod) {
            case 'get':
                if (property_exists($this, $key)) {
                    return $this->$key;
                }
                break;

            case 'set':
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                    return $this;
                }
                break;
        }

        throw new \Exception('Method "' . $method . '" does not exist and was not trapped in __call()');
    }

    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property) 
    {
        return $this->$property;
    }
  
    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value) 
    {
        $this->$property = $value;
    }
    
    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy() 
    {
        return get_object_vars($this);
    }
  
    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function exchangeArray ($data) 
    {
        $this->carName = $data['carName'];
        $this->carYear = $data['carYear'];
        $this->carDescription = $data['carDescription'];
        return $this;
    }
}