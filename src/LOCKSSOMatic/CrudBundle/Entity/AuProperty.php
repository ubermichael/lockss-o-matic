<?php

namespace LOCKSSOMatic\CrudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AuProperty
 *
 * @ORM\Table(name="au_properties", indexes={@ORM\Index(name="IDX_EFF7C3EEA3D201B3", columns={"au_id"}), @ORM\Index(name="IDX_EFF7C3EE727ACA70", columns={"parent_id"})})
 * @ORM\Entity
 */
class AuProperty
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="property_key", type="string", length=255, nullable=false)
     */
    private $propertyKey;

    /**
     * @var string
     *
     * @ORM\Column(name="property_value", type="text", nullable=true)
     */
    private $propertyValue;

    /**
     * @var AuProperty
     *
     * @ORM\ManyToOne(targetEntity="AuProperty")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var Au
     *
     * @ORM\ManyToOne(targetEntity="Au")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="au_id", referencedColumnName="id")
     * })
     */
    private $au;



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
     * Set propertyKey
     *
     * @param string $propertyKey
     * @return AuProperty
     */
    public function setPropertyKey($propertyKey)
    {
        $this->propertyKey = $propertyKey;

        return $this;
    }

    /**
     * Get propertyKey
     *
     * @return string 
     */
    public function getPropertyKey()
    {
        return $this->propertyKey;
    }

    /**
     * Set propertyValue
     *
     * @param string $propertyValue
     * @return AuProperty
     */
    public function setPropertyValue($propertyValue)
    {
        $this->propertyValue = $propertyValue;

        return $this;
    }

    /**
     * Get propertyValue
     *
     * @return string 
     */
    public function getPropertyValue()
    {
        return $this->propertyValue;
    }

    /**
     * Set parent
     *
     * @param AuProperty $parent
     * @return AuProperty
     */
    public function setParent(AuProperty $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return AuProperty 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set au
     *
     * @param Au $au
     * @return AuProperty
     */
    public function setAu(Au $au = null)
    {
        $this->au = $au;

        return $this;
    }

    /**
     * Get au
     *
     * @return Au 
     */
    public function getAu()
    {
        return $this->au;
    }
}